@extends('layouts.dashboard')

@section('title', 'Tambah Barang Unit Usaha')
@section('page-title', 'Tambah Barang / Produk')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('business-unit-items.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
            ← Kembali ke Inventaris Toko
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800 mb-1">Tambah Barang / Produk Baru</h2>
        <p class="text-sm text-slate-500 mb-6">Masukkan data produk toko atau koperasi unit usaha masjid.</p>

        <form action="{{ route('business-unit-items.store') }}" method="POST">
            @include('business-unit-items._form')
        </form>
    </div>
</div>
@endsection

