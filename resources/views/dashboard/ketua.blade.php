@extends('layouts.dashboard')

@section('title', 'Dashboard Ketua')
@section('page-title', 'Dashboard Ketua DKM')

@section('content')

<div class="mb-8">

    <h1 class="text-2xl font-bold text-gray-900">
        Selamat Datang, {{ auth()->user()->name }}
    </h1>

    <p class="text-gray-500 mt-1">
        Ringkasan pengelolaan dan aktivitas masjid.
    </p>

</div>


{{-- STATISTIK --}}

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-xl shadow-sm p-5">

        <p class="text-sm text-gray-500">
            Saldo Kas
        </p>

        <p class="text-2xl font-bold mt-2">
            Rp {{ number_format($balance, 0, ',', '.') }}
        </p>

    </div>


    <div class="bg-white rounded-xl shadow-sm p-5">

        <p class="text-sm text-gray-500">
            Pemasukan
        </p>

        <p class="text-2xl font-bold text-green-600 mt-2">
            Rp {{ number_format($totalIncome, 0, ',', '.') }}
        </p>

    </div>


    <div class="bg-white rounded-xl shadow-sm p-5">

        <p class="text-sm text-gray-500">
            Pengeluaran
        </p>

        <p class="text-2xl font-bold text-red-600 mt-2">
            Rp {{ number_format($totalExpense, 0, ',', '.') }}
        </p>

    </div>


    <div class="bg-white rounded-xl shadow-sm p-5">

        <p class="text-sm text-gray-500">
            Donasi
        </p>

        <p class="text-2xl font-bold text-blue-600 mt-2">
            Rp {{ number_format($totalDonation, 0, ',', '.') }}
        </p>

    </div>

</div>


{{-- KEUANGAN --}}

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm p-6">

        <h2 class="font-semibold text-lg mb-5">
            Ringkasan Keuangan
        </h2>

        <div class="space-y-4">

            <div class="flex justify-between">

                <span class="text-gray-500">
                    Pemasukan
                </span>

                <span class="font-semibold text-green-600">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </span>

            </div>


            <div class="flex justify-between">

                <span class="text-gray-500">
                    Pengeluaran
                </span>

                <span class="font-semibold text-red-600">
                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                </span>

            </div>


            <div class="border-t pt-4 flex justify-between">

                <span class="font-semibold">
                    Saldo
                </span>

                <span class="font-bold">
                    Rp {{ number_format($balance, 0, ',', '.') }}
                </span>

            </div>

        </div>

    </div>


    {{-- LAPORAN --}}

    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="flex justify-between items-center">

            <h2 class="font-semibold text-lg">
                Laporan Keuangan
            </h2>

            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">
                {{ $pendingReports }} Menunggu
            </span>

        </div>

        <p class="text-gray-500 mt-3">
            Laporan keuangan yang perlu diperiksa atau diproses.
        </p>

        <a
            href="#"
            class="inline-block mt-5 text-sm text-blue-600">
            Tinjau Laporan →
        </a>

    </div>

</div>


{{-- TRANSAKSI TERBARU --}}

<div class="bg-white rounded-xl shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-lg">
                Transaksi Terbaru
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Aktivitas keuangan terbaru
            </p>

        </div>

        <a
            href="{{ route('transaksi.index') }}"
            class="text-sm text-blue-600">
            Lihat Semua →
        </a>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-50">

                <tr>

                    <th class="text-left px-6 py-3">
                        Kode
                    </th>

                    <th class="text-left px-6 py-3">
                        Tanggal
                    </th>

                    <th class="text-left px-6 py-3">
                        Kategori
                    </th>

                    <th class="text-left px-6 py-3">
                        Jenis
                    </th>

                    <th class="text-right px-6 py-3">
                        Nominal
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y">

                @forelse($latestTransactions as $transaction)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4">
                            {{ $transaction->transaction_code }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $transaction->transaction_date->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $transaction->category->name }}
                        </td>

                        <td class="px-6 py-4">

                            @if($transaction->type === 'income')

                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    Pemasukan
                                </span>

                            @else

                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                    Pengeluaran
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-right font-semibold">

                            Rp {{ number_format(
                                $transaction->amount,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-10 text-center text-gray-400">

                            Belum ada transaksi.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection