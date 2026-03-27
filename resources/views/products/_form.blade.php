@csrf

<div>
  <label for="image" class="mb-2 block text-sm font-semibold text-slate-700">Gambar Produk</label>
  <input type="file" id="image" name="image" accept="image/*"
    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
  <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, WEBP. Maksimal 2MB.</p>
  @if (!empty($product?->image_path))
    <div class="mt-3">
      <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
        class="h-24 w-24 rounded-lg border border-slate-200 object-cover">
    </div>
  @endif
  @error('image')
    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
  @enderror
</div>

<div>
  <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700">Kategori</label>
  <select id="category_id" name="category_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
    <option value="">Tanpa Kategori</option>
    @foreach ($categories as $categoryOption)
      <option value="{{ $categoryOption->id }}" @selected((string) old('category_id', $product->category_id ?? '') === (string) $categoryOption->id)>
        {{ $categoryOption->name }}
      </option>
    @endforeach
  </select>
  @error('category_id')
    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
  @enderror
</div>

<div>
  <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Produk</label>
  <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required
    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
  @error('name')
    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
  @enderror
</div>

<div>
  <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi</label>
  <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">{{ old('description', $product->description ?? '') }}</textarea>
  @error('description')
    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
  @enderror
</div>

<div class="grid gap-4 sm:grid-cols-2">
  <div>
    <label for="price" class="mb-2 block text-sm font-semibold text-slate-700">Harga</label>
    <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $product->price ?? 0) }}" required
      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
    @error('price')
      <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
    @enderror
  </div>

  <div>
    <label for="stock" class="mb-2 block text-sm font-semibold text-slate-700">Stok</label>
    <input type="number" min="0" id="stock" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required
      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
    @error('stock')
      <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
    @enderror
  </div>
</div>

<div class="flex items-center gap-3 pt-2">
  <button type="submit" class="inline-flex cursor-pointer items-center rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-cyan-700">
    Simpan
  </button>
  <a href="{{ route('products.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-cyan-700">
    Batal
  </a>
</div>
