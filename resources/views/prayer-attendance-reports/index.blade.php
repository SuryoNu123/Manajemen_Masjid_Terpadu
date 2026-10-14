@extends('layouts.dashboard')

@section('title', 'Laporan Presensi')
@section('page-title', 'Laporan Presensi')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-800">
            Laporan Kehadiran Jamaah
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Rekap presensi jamaah untuk evaluasi kegiatan masjid.
        </p>
    </div>

    {{-- Filter laporan --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET"
              action="{{ route('prayer-attendance-reports.index') }}"
              class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:items-end">

            <div>
                <label for="period"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Periode Laporan
                </label>

                <select id="period" name="period"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                    <option value="weekly" @selected($period === 'weekly')>
                        Mingguan
                    </option>
                    <option value="monthly" @selected($period === 'monthly')>
                        Bulanan
                    </option>
                </select>
            </div>

            <div>
                <label for="reference_date"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Pilih Tanggal Acuan
                </label>

                <input id="reference_date"
                       type="date"
                       name="reference_date"
                       max="{{ today()->toDateString() }}"
                       value="{{ $referenceDate->toDateString() }}"
                       required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
            </div>

            <div>
                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Tampilkan Laporan
                </button>
            </div>
        </form>

        <p class="mt-4 text-sm text-slate-500">
            Periode:
            <span class="font-medium text-slate-700">
                {{ $startDate->translatedFormat('d F Y') }}
                – {{ $endDate->translatedFormat('d F Y') }}
            </span>
        </p>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Catatan Presensi</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">
                {{ number_format($totalRecords) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Status Hadir</p>
            <p class="mt-2 text-2xl font-bold text-green-700">
                {{ number_format($totalByStatus['hadir']) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Status Izin</p>
            <p class="mt-2 text-2xl font-bold text-amber-600">
                {{ number_format($totalByStatus['izin']) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Status Sakit</p>
            <p class="mt-2 text-2xl font-bold text-blue-700">
                {{ number_format($totalByStatus['sakit']) }}
            </p>
        </div>

    </div>

    {{-- Rekap setiap salat --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">
                Rekap per Waktu Salat
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Waktu Salat</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Hadir</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Izin</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Sakit</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Total Tercatat</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach ($summaryByPrayer as $prayer => $summary)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 text-sm font-medium capitalize text-slate-800">
                                {{ $prayer }}
                            </td>
                            <td class="px-5 py-4 text-center text-sm text-green-700">
                                {{ number_format($summary['hadir']) }}
                            </td>
                            <td class="px-5 py-4 text-center text-sm text-amber-600">
                                {{ number_format($summary['izin']) }}
                            </td>
                            <td class="px-5 py-4 text-center text-sm text-blue-700">
                                {{ number_format($summary['sakit']) }}
                            </td>
                            <td class="px-5 py-4 text-center text-sm font-semibold text-slate-800">
                                {{ number_format($summary['total']) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot class="bg-slate-50">
                    <tr>
                        <td class="px-5 py-3 text-sm font-semibold text-slate-800">Total</td>
                        <td class="px-5 py-3 text-center text-sm font-semibold">{{ number_format($totalByStatus['hadir']) }}</td>
                        <td class="px-5 py-3 text-center text-sm font-semibold">{{ number_format($totalByStatus['izin']) }}</td>
                        <td class="px-5 py-3 text-center text-sm font-semibold">{{ number_format($totalByStatus['sakit']) }}</td>
                        <td class="px-5 py-3 text-center text-sm font-bold">{{ number_format($totalRecords) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Rincian per tanggal --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">
                Rincian Kehadiran per Tanggal
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Hanya tanggal dan waktu salat yang memiliki catatan presensi ditampilkan.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Waktu Salat</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Hadir</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Izin</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Sakit</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Total</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($dailyRows as $row)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ \Carbon\Carbon::parse($row['date'])->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-5 py-4 text-sm font-medium capitalize text-slate-800">
                                {{ $row['prayer_name'] }}
                            </td>
                            <td class="px-5 py-4 text-center text-sm text-green-700">
                                {{ number_format($row['hadir']) }}
                            </td>
                            <td class="px-5 py-4 text-center text-sm text-amber-600">
                                {{ number_format($row['izin']) }}
                            </td>
                            <td class="px-5 py-4 text-center text-sm text-blue-700">
                                {{ number_format($row['sakit']) }}
                            </td>
                            <td class="px-5 py-4 text-center text-sm font-semibold text-slate-800">
                                {{ number_format($row['total']) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">
                                Belum ada catatan presensi pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
