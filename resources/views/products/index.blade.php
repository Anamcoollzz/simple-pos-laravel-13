@extends('layouts.app')

@section('title', 'Daftar Produk')
@section('page_title', 'Daftar Produk')

@section('content')
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-lg font-semibold text-slate-900">Semua Produk</h2>
      <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-cyan-700">
        + Tambah Produk
      </a>
    </div>

    @if (session('success'))
      <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
        {{ session('success') }}
      </div>
    @endif

    <div class="overflow-x-auto">
      <table class="min-w-full border-separate border-spacing-0 text-sm">
        <thead>
          <tr>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Gambar</th>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Kategori</th>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Harga</th>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Stok</th>
            <th class="border-b border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($products as $product)
            <tr class="hover:bg-slate-50/70">
              <td class="border-b border-slate-100 px-4 py-3">
                @if ($product->image_path)
                  <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded-md object-cover">
                @else
                  <div class="h-12 w-12 rounded-md border border-dashed border-slate-300 bg-slate-50"></div>
                @endif
              </td>
              <td class="border-b border-slate-100 px-4 py-3 font-medium text-slate-900">{{ $product->name }}</td>
              <td class="border-b border-slate-100 px-4 py-3 text-slate-600">{{ $product->category?->name ?: '-' }}</td>
              <td class="border-b border-slate-100 px-4 py-3">Rp {{ number_format($product->price, 2, ',', '.') }}</td>
              <td class="border-b border-slate-100 px-4 py-3">{{ $product->stock }}</td>
              <td class="border-b border-slate-100 px-4 py-3">
                <div class="flex flex-wrap items-center gap-3">
                  <a href="{{ route('products.show', $product) }}" class="font-medium text-cyan-700 hover:text-cyan-800">Detail</a>
                  <a href="{{ route('products.edit', $product) }}" class="font-medium text-amber-700 hover:text-amber-800">Edit</a>
                  <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cursor-pointer font-medium text-rose-700 hover:text-rose-800">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada data produk.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-5">
      {{ $products->links() }}
    </div>
  </div>
@endsection
