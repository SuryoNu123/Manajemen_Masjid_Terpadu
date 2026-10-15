@extends('layouts.dashboard')

@section('title', 'Edit Barang Unit Usaha')
@section('page-title', 'Edit Barang / Produk')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('business-unit-items.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
            ← Kembali ke Inventaris Toko
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800 mb-1">Edit Produk: {{ $item->name }}</h2>
        <p class="text-sm text-slate-500 mb-6">Perbarui detail produk, harga, dan informasi inventaris.</p>

        <form action="{{ route('business-unit-items.update', $item) }}" method="POST">
            @method('PUT')
            @include('business-unit-items._form')
        </form>
    </div>
</div>
@endsection

