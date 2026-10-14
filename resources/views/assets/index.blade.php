@extends('layouts.dashboard')

@section('title', 'Data Aset')
@section('page-title', 'Data Aset')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">
                Data Aset dan Inventaris
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola inventaris dan kondisi aset masjid.
            </p>
        </div>

        <a href="{{ route('assets.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
            + Tambah Aset
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('assets.index') }}"
              class="flex flex-col gap-3 sm:flex-row">

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari kode, nama, kategori, atau lokasi aset..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-500 focus:outline-none"
            >

            <button type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                Cari
            </button>

            <a href="{{ route('assets.index') }}"
               class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-medium text-slate-600 hover:bg-slate-50">
                Reset
            </a>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">No.</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kode Aset</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama Aset</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kategori</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Lokasi</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kondisi</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($assets as $asset)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                {{ $assets->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $asset->asset_code ?: '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm font-medium text-slate-800">
                                {{ $asset->name }}
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $asset->category }}
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $asset->location ?: '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @switch($asset->condition)
                                    @case('baik')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                            Baik
                                        </span>
                                        @break

                                    @case('perlu_perbaikan')
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                            Perlu Perbaikan
                                        </span>
                                        @break

                                    @case('rusak')
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                            Rusak
                                        </span>
                                        @break
                                @endswitch
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @if ($asset->is_active)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-center text-sm">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('assets.edit', $asset) }}"
                                       class="font-medium text-blue-700 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('assets.destroy', $asset) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="font-medium text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="px-5 py-10 text-center text-sm text-slate-500">
                                Belum ada data aset.
                                <a href="{{ route('assets.create') }}"
                                   class="font-medium text-blue-700 hover:underline">
                                    Tambahkan aset pertama.
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $assets->links() }}
        </div>
    </div>
</div>
@endsection
