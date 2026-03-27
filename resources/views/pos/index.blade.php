@extends('layouts.app')

@section('title', 'POS Sederhana')
@section('page_title', 'POS Sederhana')

@section('content')
  <div class="space-y-5">
    @if (session('success'))
      <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
        {{ session('error') }}
      </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-3">
      <div class="xl:col-span-2 space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
          <form action="{{ route('pos.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama produk..."
              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-cyan-700">
              Cari
            </button>
          </form>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          @forelse ($products as $product)
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
              <div class="mb-3">
                @if ($product->image_path)
                  <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-32 w-full rounded-lg object-cover">
                @else
                  <div class="flex h-32 w-full items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 text-xs text-slate-500">
                    Tanpa gambar
                  </div>
                @endif
              </div>

              <h3 class="text-sm font-semibold text-slate-900">{{ $product->name }}</h3>
              <p class="mt-1 text-xs text-slate-500">{{ $product->category?->name ?: 'Tanpa kategori' }}</p>
              <p class="mt-2 text-sm font-semibold text-cyan-700">Rp {{ number_format($product->price, 2, ',', '.') }}</p>
              <p class="text-xs text-slate-500">Stok: {{ $product->stock }}</p>

              <form action="{{ route('pos.add') }}" method="POST" class="mt-3 flex items-center gap-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="qty" min="1" value="1"
                  class="w-20 rounded-lg border border-slate-300 px-2 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-800">
                  Tambah
                </button>
              </form>
            </div>
          @empty
            <div class="sm:col-span-2 lg:col-span-3 rounded-2xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500 shadow-sm">
              Produk tidak ditemukan.
            </div>
          @endforelse
        </div>

        <div>
          {{ $products->links() }}
        </div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm xl:sticky xl:top-6 h-fit">
        <h2 class="mb-4 text-base font-semibold text-slate-900">Keranjang POS</h2>

        <div class="space-y-3">
          @forelse ($cartItems as $item)
            <div class="rounded-lg border border-slate-200 p-3">
              <p class="text-sm font-semibold text-slate-900">{{ $item['product']->name }}</p>
              <p class="text-xs text-slate-500">Rp {{ number_format($item['product']->price, 2, ',', '.') }}</p>

              <div class="mt-2 flex items-center gap-2">
                <form action="{{ route('pos.update', $item['product']) }}" method="POST" class="flex items-center gap-2">
                  @csrf
                  @method('PATCH')
                  <input type="number" name="qty" min="1" value="{{ $item['qty'] }}"
                    class="w-16 rounded-lg border border-slate-300 px-2 py-1 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                  <button type="submit" class="rounded-lg border border-slate-300 px-2 py-1 text-xs font-semibold text-slate-700 hover:border-cyan-400 hover:text-cyan-700">Update</button>
                </form>

                <form action="{{ route('pos.remove', $item['product']) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="rounded-lg border border-rose-300 px-2 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                </form>
              </div>

              <p class="mt-2 text-xs font-semibold text-slate-700">Subtotal: Rp {{ number_format($item['subtotal'], 2, ',', '.') }}</p>
            </div>
          @empty
            <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-center text-sm text-slate-500">
              Keranjang masih kosong.
            </div>
          @endforelse
        </div>

        <div class="mt-4 border-t border-slate-200 pt-3">
          <p class="text-sm text-slate-600">Total</p>
          <p class="text-xl font-bold text-slate-900">Rp {{ number_format($total, 2, ',', '.') }}</p>
        </div>

        <form action="{{ route('pos.checkout') }}" method="POST" class="mt-4">
          @csrf
          <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700" @disabled(empty($cartItems))>
            Checkout
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection
