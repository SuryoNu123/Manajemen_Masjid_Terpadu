@extends('layouts.dashboard')

@section('title', 'Perawatan Aset')
@section('page-title', 'Perawatan Aset')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Data tidak dapat diproses. Periksa kembali input yang dikirim.
        </div>
    @endif

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">
                Jadwal dan Riwayat Perawatan
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Pantau jadwal pemeliharaan dan progres perawatan aset masjid.
            </p>
        </div>

        <a href="{{ route('asset-maintenances.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
            + Jadwalkan Perawatan
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET"
              action="{{ route('asset-maintenances.index') }}"
              class="grid grid-cols-1 gap-3 sm:grid-cols-3">

            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Cari aset atau nama perawatan..."
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">

            <select name="status"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                <option value="">Semua Status</option>
                <option value="terjadwal" @selected($status === 'terjadwal')>
                    Terjadwal
                </option>
                <option value="dalam_proses" @selected($status === 'dalam_proses')>
                    Dalam Proses
                </option>
                <option value="selesai" @selected($status === 'selesai')>
                    Selesai
                </option>
                <option value="dibatalkan" @selected($status === 'dibatalkan')>
                    Dibatalkan
                </option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">
                    Filter
                </button>

                <a href="{{ route('asset-maintenances.index') }}"
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
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Aset</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Perawatan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Jadwal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Penyelesaian</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Biaya</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($maintenances as $maintenance)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                {{ $maintenances->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-sm font-medium text-slate-800">
                                    {{ $maintenance->asset->name ?? 'Aset tidak tersedia' }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $maintenance->asset->asset_code ?? '-' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-700">
                                {{ $maintenance->title }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $maintenance->scheduled_date->format('d/m/Y') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $maintenance->completed_date?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                Rp {{ number_format((float) $maintenance->cost, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @php
                                    $statusLabels = [
                                        'terjadwal' => 'Terjadwal',
                                        'dalam_proses' => 'Dalam Proses',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                    ];

                                    $statusClasses = [
                                        'terjadwal' => 'bg-blue-100 text-blue-700',
                                        'dalam_proses' => 'bg-amber-100 text-amber-700',
                                        'selesai' => 'bg-green-100 text-green-700',
                                        'dibatalkan' => 'bg-slate-100 text-slate-600',
                                    ];
                                @endphp

                                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $statusClasses[$maintenance->status] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $statusLabels[$maintenance->status] ?? $maintenance->status }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-center text-sm">
                                <a href="{{ route('asset-maintenances.edit', $maintenance) }}"
                                   class="font-medium text-blue-700 hover:underline">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-sm text-slate-500">
                                Belum ada jadwal perawatan aset.
                                <a href="{{ route('asset-maintenances.create') }}"
                                   class="font-medium text-blue-700 hover:underline">
                                    Buat jadwal pertama.
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $maintenances->links() }}
        </div>
    </div>
</div>
@endsection
