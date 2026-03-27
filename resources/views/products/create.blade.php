@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('page_title', 'Tambah Produk')

@section('content')
  <div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
      @include('products._form')
    </form>
  </div>
@endsection
