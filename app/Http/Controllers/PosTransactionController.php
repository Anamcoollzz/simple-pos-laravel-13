<?php

namespace App\Http\Controllers;

use App\Models\PosOrder;
use Illuminate\View\View;

class PosTransactionController extends Controller
{
    public function index(): View
    {
        $orders = PosOrder::latest()->paginate(12);

        return view('pos.history.index', compact('orders'));
    }

    public function show(PosOrder $order): View
    {
        $order->load(['items.product']);

        return view('pos.history.show', compact('order'));
    }

    public function receipt(PosOrder $order): View
    {
        $order->load('items');

        return view('pos.history.receipt', compact('order'));
    }
}
