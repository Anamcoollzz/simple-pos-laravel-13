<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk {{ $order->invoice_no }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>

<body class="bg-slate-100 p-4 text-slate-800">
  <div class="mx-auto max-w-md rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-4 text-center">
      <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Struk POS</p>
      <h1 class="text-lg font-bold text-slate-900">{{ $order->invoice_no }}</h1>
      <p class="text-xs text-slate-500">{{ $order->created_at->format('d M Y H:i') }}</p>
    </div>

    <div class="space-y-2 border-y border-slate-200 py-3 text-sm">
      @foreach ($order->items as $item)
        <div class="flex items-start justify-between gap-2">
          <div>
            <p class="font-semibold text-slate-900">{{ $item->product_name }}</p>
            <p class="text-xs text-slate-500">{{ $item->qty }} x Rp {{ number_format($item->price, 2, ',', '.') }}</p>
          </div>
          <p class="font-semibold text-slate-900">Rp {{ number_format($item->subtotal, 2, ',', '.') }}</p>
        </div>
      @endforeach
    </div>

    <div class="mt-3 space-y-1 text-sm">
      <div class="flex items-center justify-between">
        <span class="text-slate-600">Total Item</span>
        <span class="font-semibold text-slate-900">{{ $order->total_items }}</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-slate-600">Total Bayar</span>
        <span class="text-base font-bold text-slate-900">Rp {{ number_format($order->total_amount, 2, ',', '.') }}</span>
      </div>
    </div>

    <p class="mt-4 text-center text-xs text-slate-500">Terima kasih telah berbelanja.</p>

    <div class="no-print mt-5 flex items-center gap-2">
      <button onclick="window.print()" class="inline-flex flex-1 items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
        Cetak Struk
      </button>
      <a href="{{ route('pos.transactions.show', $order) }}" class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-cyan-400 hover:text-cyan-700">
        Kembali
      </a>
    </div>
  </div>
</body>

</html>
