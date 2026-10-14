@extends('layouts.dashboard')

@section('title', 'Unit Usaha')
@section('page-title', 'Unit Usaha')

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
            <h2 class="text-xl font-bold text-slate-800">
                Data Unit Usaha Masjid
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola informasi koperasi, kantin, dan unit usaha lainnya.
            </p>
        </div>

        <a href="{{ route('business-units.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
            + Tambah Unit Usaha
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET"
              action="{{ route('business-units.index') }}"
              class="grid grid-cols-1 gap-3 sm:grid-cols-3">

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari kode, nama, jenis, atau lokasi..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm"
            >

            <select name="status"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                <option value="">Semua Status</option>
                <option value="aktif" @selected($status === 'aktif')>
                    Aktif
                </option>
                <option value="nonaktif" @selected($status === 'nonaktif')>
                    Nonaktif
                </option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">
                    Cari
                </button>

                <a href="{{ route('business-units.index') }}"
                   class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-600">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">No.</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kode</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama Unit Usaha</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Jenis</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Lokasi</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Modal Awal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Omzet Tercatat</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($businessUnits as $unit)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                {{ $businessUnits->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $unit->code ?: '-' }}
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-sm font-medium text-slate-800">
                                    {{ $unit->name }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $unit->manager_name ?: 'Penanggung jawab belum diisi' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $unit->type }}
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $unit->location ?: '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                @if ($unit->initial_capital !== null)
                                    Rp {{ number_format((float) $unit->initial_capital, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                Rp {{ number_format((float) ($unit->sales_sum_amount ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @if ($unit->is_active)
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
                                <a href="{{ route('business-units.edit', $unit) }}"
                                   class="font-medium text-blue-700 hover:underline">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center text-sm text-slate-500">
                                Belum ada unit usaha.
                                <a href="{{ route('business-units.create') }}"
                                   class="font-medium text-blue-700 hover:underline">
                                    Tambahkan unit usaha pertama.
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $businessUnits->links() }}
        </div>
    </div>
</div>
@endsection
