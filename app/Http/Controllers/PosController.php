<?php

namespace App\Http\Controllers;

use App\Models\PosOrder;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $products = Product::with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        [$cartItems, $total] = $this->buildCartItems();

        return view('pos.index', compact('products', 'cartItems', 'total', 'search'));
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $cart = session('pos_cart', []);
        $currentQty = (int) ($cart[$product->id] ?? 0);
        $newQty = $currentQty + (int) $validated['qty'];

        if ($newQty > $product->stock) {
            if ($this->isAjax($request)) {
                return $this->jsonResponse('error', "Stok {$product->name} tidak mencukupi.", 422);
            }

            return back()->with('error', "Stok {$product->name} tidak mencukupi.");
        }

        $cart[$product->id] = $newQty;
        session(['pos_cart' => $cart]);

        if ($this->isAjax($request)) {
            return $this->jsonResponse('success', "{$product->name} ditambahkan ke keranjang.");
        }

        return back()->with('success', "{$product->name} ditambahkan ke keranjang.");
    }

    public function update(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        if ((int) $validated['qty'] > $product->stock) {
            if ($this->isAjax($request)) {
                return $this->jsonResponse('error', "Stok {$product->name} tidak mencukupi.", 422);
            }

            return back()->with('error', "Stok {$product->name} tidak mencukupi.");
        }

        $cart = session('pos_cart', []);
        $cart[$product->id] = (int) $validated['qty'];
        session(['pos_cart' => $cart]);

        if ($this->isAjax($request)) {
            return $this->jsonResponse('success', 'Qty keranjang diperbarui.');
        }

        return back()->with('success', 'Qty keranjang diperbarui.');
    }

    public function remove(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $cart = session('pos_cart', []);
        unset($cart[$product->id]);
        session(['pos_cart' => $cart]);

        if ($this->isAjax($request)) {
            return $this->jsonResponse('success', "{$product->name} dihapus dari keranjang.");
        }

        return back()->with('success', "{$product->name} dihapus dari keranjang.");
    }

    public function checkout(Request $request): RedirectResponse|JsonResponse
    {
        $cart = session('pos_cart', []);

        if (empty($cart)) {
            if ($this->isAjax($request)) {
                return $this->jsonResponse('error', 'Keranjang masih kosong.', 422);
            }

            return back()->with('error', 'Keranjang masih kosong.');
        }

        $invoiceNo = null;
        $orderId = null;

        try {
            DB::transaction(function () use ($cart, &$invoiceNo, &$orderId) {
                $products = Product::whereIn('id', array_keys($cart))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $totalItems = 0;
                $totalAmount = 0;

                foreach ($cart as $productId => $qty) {
                    $product = $products->get((int) $productId);
                    $productName = $product?->name ?? 'produk';

                    if (!$product || $product->stock < $qty) {
                        throw new \RuntimeException("Stok {$productName} tidak mencukupi.");
                    }

                    $totalItems += $qty;
                    $totalAmount += $product->price * $qty;
                }

                $order = PosOrder::create([
                    'invoice_no' => 'POS-' . now()->format('YmdHis') . '-' . random_int(100, 999),
                    'total_items' => $totalItems,
                    'total_amount' => $totalAmount,
                ]);

                $invoiceNo = $order->invoice_no;
                $orderId = $order->id;

                foreach ($cart as $productId => $qty) {
                    $product = $products->get((int) $productId);

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'qty' => $qty,
                        'subtotal' => $product->price * $qty,
                    ]);

                    $product->decrement('stock', $qty);
                }
            });
        } catch (\RuntimeException $e) {
            if ($this->isAjax($request)) {
                return $this->jsonResponse('error', $e->getMessage(), 422);
            }

            return back()->with('error', $e->getMessage());
        }

        session()->forget('pos_cart');

        $successMessage = 'Checkout berhasil. Transaksi tersimpan dengan nomor ' . $invoiceNo . '.';
        $receiptUrl = route('pos.transactions.receipt', $orderId);

        if ($this->isAjax($request)) {
            return $this->jsonResponse('success', $successMessage, 200, [
                'receipt_url' => $receiptUrl,
            ]);
        }

        return redirect()
            ->route('pos.index')
            ->with('success', $successMessage)
            ->with('receipt_url', $receiptUrl);
    }

    private function isAjax(Request $request): bool
    {
        return $request->expectsJson() || $request->ajax();
    }

    private function jsonResponse(string $type, string $message, int $status = 200, array $extra = []): JsonResponse
    {
        [$cartItems, $total] = $this->buildCartItems();

        return response()->json(array_merge([
            'status' => $type,
            'message' => $message,
            'cart_html' => view('pos._cart', compact('cartItems', 'total'))->render(),
        ], $extra), $status);
    }

    /**
     * @return array{0: array<int, array<string, mixed>>, 1: float}
     */
    private function buildCartItems(): array
    {
        $cart = session('pos_cart', []);
        $productIds = array_map('intval', array_keys($cart));

        if (empty($productIds)) {
            return [[], 0];
        }

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $qty) {
            $product = $products->get((int) $productId);

            if (!$product) {
                continue;
            }

            $subtotal = $product->price * $qty;
            $total += $subtotal;

            $items[] = [
                'product' => $product,
                'qty' => (int) $qty,
                'subtotal' => (float) $subtotal,
            ];
        }

        return [$items, (float) $total];
    }
}
