@extends('layouts.dashboard')

@section('title', 'Data Jamaah')
@section('page-title', 'Data Jamaah')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Terdapat kesalahan pada data yang dikirim.
        </div>
    @endif

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Data Jamaah</h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola informasi dan status jamaah masjid.
            </p>
        </div>

        <a href="{{ route('jamaahs.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
            + Tambah Jamaah
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('jamaahs.index') }}"
              class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <input
                type="search"
                name="search"
                value="{{ $filters['search'] ?? '' }}"
                placeholder="Cari nama, domisili, atau nomor telepon..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-500 focus:outline-none"
            >

            <select name="status" aria-label="Filter status jamaah"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">Semua status</option>
                <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(($filters['status'] ?? '') === 'nonaktif')>Nonaktif</option>
            </select>

            <input type="number" name="min_age" min="0" max="120"
                   value="{{ $filters['min_age'] ?? '' }}" placeholder="Usia minimum"
                   aria-label="Usia minimum"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">

            <input type="number" name="max_age" min="0" max="120"
                   value="{{ $filters['max_age'] ?? '' }}" placeholder="Usia maksimum"
                   aria-label="Usia maksimum"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">

            <button type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                Cari
            </button>

            <a href="{{ route('jamaahs.index') }}"
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
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Domisili</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Usia</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kontak</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($jamaahs as $jamaah)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                {{ $jamaahs->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4 text-sm font-medium text-slate-800">
                                {{ $jamaah->name }}
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $jamaah->domicile }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $jamaah->age !== null ? $jamaah->age . ' tahun' : '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $jamaah->phone ?: '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @if ($jamaah->status === 'aktif')
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
                                    <a href="{{ route('jamaahs.edit', $jamaah) }}"
                                       class="font-medium text-blue-700 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('jamaahs.destroy', $jamaah) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data jamaah ini?')">
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
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-slate-500">
                                Belum ada data jamaah.
                                <a href="{{ route('jamaahs.create') }}"
                                   class="font-medium text-blue-700 hover:underline">
                                    Tambahkan data pertama.
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $jamaahs->links() }}
        </div>
    </div>
</div>
@endsection
