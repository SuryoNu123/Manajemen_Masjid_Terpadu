@extends('layouts.public')

@section('title', 'Transparansi Keuangan')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-12">


    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <div class="text-center mb-10">

        <h1 class="text-3xl md:text-4xl font-bold">
            Transparansi Keuangan
        </h1>

        <p class="text-gray-500 mt-3 max-w-2xl mx-auto">
            Informasi laporan keuangan masjid yang telah
            dipublikasikan oleh pengurus.
        </p>

    </div>


    {{-- ================================================= --}}
    {{-- SUMMARY --}}
    {{-- ================================================= --}}

    @if($summaryReport)

        <div class="mb-10">

            <div class="bg-slate-900 text-white rounded-2xl p-8">

                <div class="flex flex-col md:flex-row
                            md:items-center
                            md:justify-between
                            gap-5">

                    <div>

                        <p class="text-slate-400 text-sm">
                            Laporan Terbaru
                        </p>

                        <h2 class="text-2xl font-bold mt-2">

                            @if($summaryReport->period_type === 'monthly')

                                @php

                                    $months = [
                                        1 => 'Januari',
                                        2 => 'Februari',
                                        3 => 'Maret',
                                        4 => 'April',
                                        5 => 'Mei',
                                        6 => 'Juni',
                                        7 => 'Juli',
                                        8 => 'Agustus',
                                        9 => 'September',
                                        10 => 'Oktober',
                                        11 => 'November',
                                        12 => 'Desember',
                                    ];

                                @endphp

                                {{ $months[$summaryReport->month] }}
                                {{ $summaryReport->year }}

                            @else

                                Tahun {{ $summaryReport->year }}

                            @endif

                        </h2>

                        <p class="text-slate-400 text-sm mt-2">
                            Laporan yang telah disetujui dan dipublikasikan.
                        </p>

                    </div>


                    <div class="text-left md:text-right">

                        <p class="text-slate-400 text-sm">
                            Saldo
                        </p>

                        <p class="text-3xl font-bold mt-1">
                            Rp {{ number_format(
                                $summaryReport->balance,
                                0,
                                ',',
                                '.'
                            ) }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    @else

        <div class="bg-yellow-50 border border-yellow-200
                    text-yellow-800 rounded-xl p-6 mb-10">

            <h2 class="font-semibold">
                Belum ada laporan yang dipublikasikan
            </h2>

            <p class="text-sm mt-1">
                Informasi transparansi akan muncul setelah
                laporan keuangan disetujui dan dipublikasikan.
            </p>

        </div>

    @endif


    {{-- ================================================= --}}
    {{-- RINGKASAN KEUANGAN --}}
    {{-- ================================================= --}}

    @if($summaryReport)

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-12">


            {{-- PEMASUKAN --}}

            <div class="bg-white rounded-xl border
                        border-gray-200 p-6">

                <p class="text-sm text-gray-500">
                    Pemasukan
                </p>

                <p class="text-2xl font-bold text-green-600 mt-2">
                    Rp {{ number_format(
                        $summaryReport->total_income,
                        0,
                        ',',
                        '.'
                    ) }}
                </p>

            </div>


            {{-- PENGELUARAN --}}

            <div class="bg-white rounded-xl border
                        border-gray-200 p-6">

                <p class="text-sm text-gray-500">
                    Pengeluaran
                </p>

                <p class="text-2xl font-bold text-red-600 mt-2">
                    Rp {{ number_format(
                        $summaryReport->total_expense,
                        0,
                        ',',
                        '.'
                    ) }}
                </p>

            </div>


            {{-- SALDO --}}

            <div class="bg-white rounded-xl border
                        border-gray-200 p-6">

                <p class="text-sm text-gray-500">
                    Saldo
                </p>

                <p class="text-2xl font-bold text-slate-900 mt-2">
                    Rp {{ number_format(
                        $summaryReport->balance,
                        0,
                        ',',
                        '.'
                    ) }}
                </p>

            </div>

        </div>

    @endif


    {{-- ================================================= --}}
    {{-- LAPORAN TERBARU --}}
    {{-- ================================================= --}}

    <div class="mb-6">

        <h2 class="text-2xl font-bold">
            Riwayat Laporan
        </h2>

        <p class="text-gray-500 mt-1">
            Daftar laporan keuangan yang telah dipublikasikan.
        </p>

    </div>


    <div class="bg-white rounded-2xl border
                border-gray-200 overflow-hidden">

        <p class="px-4 pt-3 text-xs text-gray-500 lg:hidden">
            Geser tabel ke samping untuk melihat semua kolom.
        </p>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[720px] text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="px-6 py-4 text-left">
                            Periode
                        </th>

                        <th class="px-6 py-4 text-right">
                            Pemasukan
                        </th>

                        <th class="px-6 py-4 text-right">
                            Pengeluaran
                        </th>

                        <th class="px-6 py-4 text-right">
                            Saldo
                        </th>

                        <th class="px-6 py-4 text-left">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($publishedReports as $report)

                        <tr class="hover:bg-gray-50">


                            {{-- PERIODE --}}

                            <td class="px-6 py-4 font-medium">

                                @if($report->period_type === 'monthly')

                                    @php

                                        $months = [
                                            1 => 'Januari',
                                            2 => 'Februari',
                                            3 => 'Maret',
                                            4 => 'April',
                                            5 => 'Mei',
                                            6 => 'Juni',
                                            7 => 'Juli',
                                            8 => 'Agustus',
                                            9 => 'September',
                                            10 => 'Oktober',
                                            11 => 'November',
                                            12 => 'Desember',
                                        ];

                                    @endphp

                                    {{ $months[$report->month] }}
                                    {{ $report->year }}

                                @else

                                    Tahun {{ $report->year }}

                                @endif

                            </td>


                            {{-- PEMASUKAN --}}

                            <td class="px-6 py-4 text-right text-green-600 font-semibold">

                                Rp {{ number_format(
                                    $report->total_income,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>


                            {{-- PENGELUARAN --}}

                            <td class="px-6 py-4 text-right text-red-600 font-semibold">

                                Rp {{ number_format(
                                    $report->total_expense,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>


                            {{-- SALDO --}}

                            <td class="px-6 py-4 text-right font-semibold">

                                Rp {{ number_format(
                                    $report->balance,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>


                            {{-- STATUS --}}

                            <td class="px-6 py-4">

                                <span
                                    class="px-2 py-1 rounded-full
                                           text-xs
                                           bg-blue-100
                                           text-blue-700"
                                >
                                    Dipublikasikan
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                Belum ada laporan keuangan
                                yang dipublikasikan.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        <div class="px-6 py-4 border-t">

            {{ $publishedReports->links() }}

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- INFORMASI --}}
    {{-- ================================================= --}}

    <div class="mt-8 bg-blue-50 rounded-xl p-6">

        <h3 class="font-semibold text-blue-900">
            Tentang Transparansi
        </h3>

        <p class="text-sm text-blue-800 mt-2 leading-relaxed">
            Halaman ini hanya menampilkan laporan keuangan
            yang telah melewati proses review dan telah
            dipublikasikan oleh pengurus masjid.
        </p>

    </div>


</div>

@endsection
