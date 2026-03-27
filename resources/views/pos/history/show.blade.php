@extends('layouts.app')

@section('title', 'Detail Transaksi POS')
@section('page_title', 'Detail Transaksi POS')

@section('content')
  <div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Invoice</p>
          <p class="text-lg font-bold text-slate-900">{{ $order->invoice_no }}</p>
        </div>
        <div class="flex items-center gap-2">
          <a href="{{ route('pos.transactions.receipt', $order) }}" target="_blank" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
            Cetak Struk
          </a>
          <a href="{{ route('pos.transactions.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-cyan-700">
            Kembali
          </a>
        </div>
      </div>

      <div class="mt-4 grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-slate-50 p-4">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal</p>
          <p class="mt-1 text-sm font-semibold text-slate-900">{{ $order->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="rounded-xl bg-slate-50 p-4">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Item</p>
          <p class="mt-1 text-sm font-semibold text-slate-900">{{ $order->total_items }}</p>
        </div>
        <div class="rounded-xl bg-slate-50 p-4">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Bayar</p>
          <p class="mt-1 text-sm font-semibold text-slate-900">Rp {{ number_format($order->total_amount, 2, ',', '.') }}</p>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h3 class="mb-4 text-lg font-semibold text-slate-900">Item Transaksi</h3>

      <div class="overflow-x-auto">
        <table class="min-w-full border-separate border-spacing-0 text-sm">
          <thead>
            <tr>
              <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Produk</th>
              <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Harga</th>
              <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Qty</th>
              <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->items as $item)
              <tr>
                <td class="border-b border-slate-100 px-4 py-3 font-medium text-slate-900">{{ $item->product_name }}</td>
                <td class="border-b border-slate-100 px-4 py-3">Rp {{ number_format($item->price, 2, ',', '.') }}</td>
                <td class="border-b border-slate-100 px-4 py-3">{{ $item->qty }}</td>
                <td class="border-b border-slate-100 px-4 py-3 font-semibold text-slate-900">Rp {{ number_format($item->subtotal, 2, ',', '.') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
