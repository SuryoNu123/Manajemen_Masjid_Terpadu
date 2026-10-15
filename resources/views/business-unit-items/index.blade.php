@extends('layouts.dashboard')

@section('title', 'Inventaris & Stok Unit Usaha')
@section('page-title', 'Inventaris & Stok Toko')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Terdapat kesalahan input pada pembaruan stok.
        </div>
    @endif

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">
                Stok Produk Unit Usaha Masjid
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola data barang dagangan, ketersediaan stok, harga beli, dan harga jual koperasi/kantin masjid.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('business-units.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Data Unit Usaha
            </a>
            <a href="{{ route('business-unit-sales.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Penjualan & Omset
            </a>
            <a href="{{ route('business-unit-items.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                + Tambah Produk
            </a>
        </div>
    </div>

    {{-- FILTER BOX --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('business-unit-items.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Unit Usaha</label>
                <select name="business_unit_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Semua Unit Usaha</option>
                    @foreach($businessUnits as $unit)
                        <option value="{{ $unit->id }}" @selected(request('business_unit_id') == $unit->id)>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Cari Produk / SKU</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama produk, SKU, kategori..."
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Filter Stok</label>
                <select name="low_stock" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Semua Kondisi Stok</option>
                    <option value="1" @selected(request('low_stock') == '1')>⚠️ Stok Menipis (<= Min)</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('business-unit-items.index') }}"
                   class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- TABEL INVENTARIS --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-3">No.</th>
                        <th class="px-5 py-3">Produk / Barang</th>
                        <th class="px-5 py-3">Unit Usaha</th>
                        <th class="px-5 py-3">Harga Beli</th>
                        <th class="px-5 py-3">Harga Jual</th>
                        <th class="px-5 py-3 text-center">Stok Saat Ini</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi & Atur Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 text-slate-500">
                                {{ $items->firstItem() + $loop->index }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-800">{{ $item->name }}</div>
                                <div class="text-xs text-slate-500">
                                    SKU: {{ $item->sku ?? '-' }} • Kategori: {{ $item->category ?? 'Umum' }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $item->businessUnit?->name ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                Rp {{ number_format($item->cost_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                Rp {{ number_format($item->selling_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($item->stock <= 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        Habis (0 {{ $item->unit }})
                                    </span>
                                @elseif($item->stock <= $item->min_stock)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                        {{ $item->stock }} {{ $item->unit }} (Menipis)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        {{ $item->stock }} {{ $item->unit }}
                                    </span>
                                @endif
                                <div class="text-[10px] text-slate-400 mt-0.5">Min: {{ $item->min_stock }} {{ $item->unit }}</div>
                            </td>
                            <td class="px-5 py-4">
                                @if($item->is_active)
                                    <span class="rounded bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800">Aktif</span>
                                @else
                                    <span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- FORM CEPAT PENYESUAIAN STOK --}}
                                    <details class="relative">
                                        <summary class="cursor-pointer rounded border border-slate-300 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100 list-none">
                                            ± Stok
                                        </summary>
                                        <div class="absolute right-0 top-8 z-20 w-64 rounded-xl border border-slate-200 bg-white p-4 shadow-lg text-left">
                                            <form action="{{ route('business-unit-items.adjust-stock', $item) }}" method="POST" class="space-y-3">
                                                @csrf
                                                <div>
                                                    <span class="text-xs font-bold text-slate-800">Atur Stok: {{ $item->name }}</span>
                                                </div>
                                                <div>
                                                    <label class="text-[11px] font-semibold text-slate-600">Jenis Perubahan</label>
                                                    <select name="adjustment_type" class="w-full text-xs rounded border border-slate-300 p-1.5 mt-0.5">
                                                        <option value="add">+ Tambah Stok (Masuk)</option>
                                                        <option value="subtract">- Kurangi Stok (Keluar/Rusak)</option>
                                                        <option value="set">= Set Stok Persis</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="text-[11px] font-semibold text-slate-600">Jumlah ({{ $item->unit }})</label>
                                                    <input type="number" name="quantity" min="1" required class="w-full text-xs rounded border border-slate-300 p-1.5 mt-0.5" value="1">
                                                </div>
                                                <div>
                                                    <label class="text-[11px] font-semibold text-slate-600">Keterangan</label>
                                                    <input type="text" name="notes" placeholder="Kulakan / rusak..." class="w-full text-xs rounded border border-slate-300 p-1.5 mt-0.5">
                                                </div>
                                                <button type="submit" class="w-full rounded bg-slate-900 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">
                                                    Update Stok
                                                </button>
                                            </form>
                                        </div>
                                    </details>

                                    <a href="{{ route('business-unit-items.edit', $item) }}"
                                       class="rounded border border-slate-300 px-2.5 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-50">
                                        Edit
                                    </a>

                                    <form action="{{ route('business-unit-items.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus produk {{ $item->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded border border-red-200 px-2.5 py-1 text-xs font-semibold text-red-600 hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-slate-500">
                                Belum ada data produk / inventaris unit usaha.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="border-t border-slate-200 px-5 py-3">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

