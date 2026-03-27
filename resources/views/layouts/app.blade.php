<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Aplikasi Produk')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
  <div class="flex min-h-screen">
    <aside class="w-72 bg-slate-900 text-slate-100">
      <div class="border-b border-slate-800 p-6">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">Admin Panel</p>
        <h1 class="mt-2 text-lg font-bold">CRUD Produk</h1>
      </div>

      <nav class="space-y-1 p-4">
        <a href="{{ route('dashboard') }}"
          class="flex items-center rounded-lg px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-cyan-500/20 text-cyan-200' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          Dashboard
        </a>
        <a href="{{ route('products.index') }}"
          class="flex items-center rounded-lg px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('products.*') ? 'bg-cyan-500/20 text-cyan-200' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          Modul Produk
        </a>
        <a href="{{ route('categories.index') }}"
          class="flex items-center rounded-lg px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('categories.*') ? 'bg-cyan-500/20 text-cyan-200' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          Modul Kategori
        </a>
        <a href="{{ route('pos.index') }}"
          class="flex items-center rounded-lg px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('pos.*') ? 'bg-cyan-500/20 text-cyan-200' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          Modul POS
        </a>
      </nav>
    </aside>

    <div class="flex-1">
      <header class="border-b border-slate-200 bg-white px-6 py-5 sm:px-10">
        <h2 class="text-2xl font-bold text-slate-900">@yield('page_title', 'Dashboard Admin')</h2>
      </header>

      <main class="px-6 py-6 sm:px-10">
        @yield('content')
      </main>
    </div>
  </div>
</body>

</html>
