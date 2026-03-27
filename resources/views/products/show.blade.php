@extends('layouts.app')

@section('title', 'Detail Produk')
@section('page_title', 'Detail Produk')

@section('content')
  <div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="grid gap-4 sm:grid-cols-2">
      <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Gambar Produk</p>
        <div class="mt-2">
          @if ($product->image_path)
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-56 w-full rounded-lg object-cover sm:w-72">
          @else
            <div class="flex h-40 w-full items-center justify-center rounded-lg border border-dashed border-slate-300 bg-white text-sm text-slate-500 sm:w-72">
              Tidak ada gambar
            </div>
          @endif
        </div>
      </div>

      <div class="rounded-xl bg-slate-50 p-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kategori</p>
        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $product->category?->name ?: '-' }}</p>
      </div>

      <div class="rounded-xl bg-slate-50 p-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</p>
        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $product->name }}</p>
      </div>

      <div class="rounded-xl bg-slate-50 p-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Harga</p>
        <p class="mt-1 text-sm font-semibold text-slate-900">Rp {{ number_format($product->price, 2, ',', '.') }}</p>
      </div>

      <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Deskripsi</p>
        <p class="mt-1 text-sm text-slate-700">{{ $product->description ?: '-' }}</p>
      </div>

      <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Stok</p>
        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $product->stock }}</p>
      </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
      <a href="{{ route('products.index') }}"
        class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-cyan-700">Kembali</a>
      <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600">Edit</a>
    </div>
  </div>
@endsection
