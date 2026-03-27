<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

    public function add(Request $request): RedirectResponse
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
            return back()->with('error', "Stok {$product->name} tidak mencukupi.");
        }

        $cart[$product->id] = $newQty;
        session(['pos_cart' => $cart]);

        return back()->with('success', "{$product->name} ditambahkan ke keranjang.");
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        if ((int) $validated['qty'] > $product->stock) {
            return back()->with('error', "Stok {$product->name} tidak mencukupi.");
        }

        $cart = session('pos_cart', []);
        $cart[$product->id] = (int) $validated['qty'];
        session(['pos_cart' => $cart]);

        return back()->with('success', 'Qty keranjang diperbarui.');
    }

    public function remove(Product $product): RedirectResponse
    {
        $cart = session('pos_cart', []);
        unset($cart[$product->id]);
        session(['pos_cart' => $cart]);

        return back()->with('success', "{$product->name} dihapus dari keranjang.");
    }

    public function checkout(): RedirectResponse
    {
        $cart = session('pos_cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        try {
            DB::transaction(function () use ($cart) {
                $products = Product::whereIn('id', array_keys($cart))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($cart as $productId => $qty) {
                    $product = $products->get((int) $productId);
                    $productName = $product?->name ?? 'produk';

                    if (!$product || $product->stock < $qty) {
                        throw new \RuntimeException("Stok {$productName} tidak mencukupi.");
                    }
                }

                foreach ($cart as $productId => $qty) {
                    $product = $products->get((int) $productId);
                    $product->decrement('stock', $qty);
                }
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        session()->forget('pos_cart');

        return redirect()->route('pos.index')->with('success', 'Checkout berhasil, stok produk telah diperbarui.');
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
