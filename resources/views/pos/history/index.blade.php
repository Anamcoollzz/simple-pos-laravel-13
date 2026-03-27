@extends('layouts.app')

@section('title', 'History Transaksi POS')
@section('page_title', 'History Transaksi POS')

@section('content')
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="mb-5 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-slate-900">Daftar Transaksi</h3>
      <a href="{{ route('pos.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-cyan-700">
        Kembali ke POS
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full border-separate border-spacing-0 text-sm">
        <thead>
          <tr>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Invoice</th>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Tanggal</th>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Total Item</th>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Total Bayar</th>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($orders as $order)
            <tr class="hover:bg-slate-50/70">
              <td class="border-b border-slate-100 px-4 py-3 font-medium text-slate-900">{{ $order->invoice_no }}</td>
              <td class="border-b border-slate-100 px-4 py-3 text-slate-600">{{ $order->created_at->format('d M Y H:i') }}</td>
              <td class="border-b border-slate-100 px-4 py-3">{{ $order->total_items }}</td>
              <td class="border-b border-slate-100 px-4 py-3 font-semibold text-slate-900">Rp {{ number_format($order->total_amount, 2, ',', '.') }}</td>
              <td class="border-b border-slate-100 px-4 py-3">
                <div class="flex items-center gap-3">
                  <a href="{{ route('pos.transactions.show', $order) }}" class="font-medium text-cyan-700 hover:text-cyan-800">Detail</a>
                  <a href="{{ route('pos.transactions.receipt', $order) }}" target="_blank" class="font-medium text-slate-700 hover:text-slate-900">Cetak</a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada transaksi.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-5">
      {{ $orders->links() }}
    </div>
  </div>
@endsection
