@extends('layouts.app')

@section('title', 'POS Sederhana')
@section('page_title', 'POS Sederhana')

@section('content')
  <div id="pos-page" class="space-y-5">
    <div id="pos-alert" class="hidden rounded-lg border px-4 py-3 text-sm font-medium"></div>

    @if (session('success'))
      <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
        {{ session('success') }}
        @if (session('receipt_url'))
          <a href="{{ session('receipt_url') }}" target="_blank" class="ml-2 font-semibold underline">
            Cetak struk
          </a>
        @endif
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

              <form action="{{ route('pos.add') }}" method="POST" class="js-pos-form mt-3 flex items-center gap-2">
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

      <div id="pos-cart-panel">
        @include('pos._cart')
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    (function() {
      const page = document.getElementById('pos-page');

      if (!page) {
        return;
      }

      const alertBox = document.getElementById('pos-alert');
      const cartPanel = document.getElementById('pos-cart-panel');

      const showAlert = (type, message, receiptUrl = null) => {
        alertBox.classList.remove('hidden', 'border-emerald-200', 'bg-emerald-50', 'text-emerald-700', 'border-rose-200', 'bg-rose-50', 'text-rose-700');

        if (type === 'success') {
          alertBox.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-700');
        } else {
          alertBox.classList.add('border-rose-200', 'bg-rose-50', 'text-rose-700');
        }

        alertBox.textContent = message;

        if (receiptUrl) {
          const link = document.createElement('a');
          link.href = receiptUrl;
          link.target = '_blank';
          link.className = 'ml-2 font-semibold underline';
          link.textContent = 'Cetak struk';
          alertBox.appendChild(link);
        }
      };

      const submitAjax = async (form) => {
        const formData = new FormData(form);

        const response = await fetch(form.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: formData,
        });

        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || 'Permintaan gagal diproses.');
        }

        if (data.cart_html && cartPanel) {
          cartPanel.innerHTML = data.cart_html;
        }

        showAlert(data.status || 'success', data.message || 'Berhasil.', data.receipt_url || null);

        if (form.action.includes('/checkout') && data.receipt_url) {
          window.open(data.receipt_url, '_blank');

          setTimeout(() => {
            window.location.reload();
          }, 500);
        }
      };

      page.addEventListener('submit', async (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || !form.classList.contains('js-pos-form')) {
          return;
        }

        event.preventDefault();

        try {
          await submitAjax(form);
        } catch (error) {
          showAlert('error', error.message || 'Terjadi kesalahan.');
        }
      });
    })();
  </script>
@endpush
