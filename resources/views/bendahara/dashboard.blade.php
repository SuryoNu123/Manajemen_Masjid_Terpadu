@extends('shared.layouts.dashboard')

@section('title', 'Dashboard Bendahara')
@section('page-title', 'Dashboard Keuangan & Bendahara')

@section('content')

<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
            Dashboard Bendahara
        </h1>
        <p class="mt-2 max-w-3xl text-base leading-6 text-slate-600">
            Pantau arus kas, verifikasi donasi digital, zakat mustahik, dan laporan pertanggungjawaban keuangan masjid.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('transaksi.create', ['type' => 'income']) }}"
           class="rounded-lg bg-green-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-green-700">
            + Pemasukan
        </a>
        <a href="{{ route('transaksi.create', ['type' => 'expense']) }}"
           class="rounded-lg bg-red-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-red-700">
            - Pengeluaran
        </a>
        <a href="{{ route('laporan-keuangan.create') }}"
           class="rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
            + Buat Laporan
        </a>
    </div>
</div>

{{-- STATISTIK UTAMA --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <x-shared::metric-card label="Saldo Kas Riil" :value="'Rp '.number_format($balance, 0, ',', '.')" detail="Akumulasi pemasukan dikurangi pengeluaran" tone="emerald" :featured="true" />
    <x-shared::metric-card label="Pemasukan Bulan Ini" :value="'Rp '.number_format($monthlyIncome, 0, ',', '.')" :detail="'Akumulasi Rp '.number_format($totalIncome, 0, ',', '.')" tone="emerald" />
    <x-shared::metric-card label="Pengeluaran Bulan Ini" :value="'Rp '.number_format($monthlyExpense, 0, ',', '.')" :detail="'Akumulasi Rp '.number_format($totalExpense, 0, ',', '.')" tone="rose" />
    <x-shared::metric-card label="Donasi Terverifikasi" :value="'Rp '.number_format($totalDonation, 0, ',', '.')" tone="blue">
        <x-slot:detail>
            <span class="inline-flex rounded-full {{ $pendingDonations > 0 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }} px-2.5 py-1 text-xs font-semibold">
                {{ $pendingDonations }} menunggu verifikasi
            </span>
        </x-slot:detail>
    </x-shared::metric-card>
</div>

{{-- MODUL DONASI, ZAKAT & UNIT USAHA --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="rounded-2xl border border-slate-200 border-l-4 border-l-emerald-600 bg-emerald-50/50 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Penyaluran Zakat</span>

        </div>
        <p class="mt-2 text-xl font-bold text-emerald-700">
            Rp {{ number_format($totalZakatDisbursed, 0, ',', '.') }}
        </p>
        <div class="mt-2 flex items-center justify-between text-xs">
            <span class="text-slate-500">{{ $totalMustahik }} Mustahik Terdaftar</span>
            <a href="{{ route('mustahik.index') }}" class="font-semibold text-emerald-600 hover:underline">Kelola →</a>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 border-l-4 border-l-blue-600 bg-blue-50/50 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Omzet Unit Usaha (Bulan Ini)</span>

        </div>
        <p class="mt-2 text-xl font-bold text-slate-900">
            Rp {{ number_format($totalBusinessSalesMonth, 0, ',', '.') }}
        </p>
        <div class="mt-2 flex items-center justify-between text-xs">
            <span class="text-slate-500">Koperasi / Kantin</span>
            <a href="{{ route('business-unit-sales.index') }}" class="font-semibold text-blue-600 hover:underline">Catat Omzet →</a>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 border-l-4 border-l-amber-500 bg-amber-50/50 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Status Laporan Keuangan</span>

        </div>
        <p class="mt-3"><span class="inline-flex rounded-full {{ $pendingReports > 0 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }} px-3 py-1.5 text-sm font-semibold">{{ $pendingReports }} Menunggu</span></p>
        <div class="mt-2 flex items-center justify-between text-xs">
            <span class="text-slate-500">Draft / Menunggu Ketua</span>
            <a href="{{ route('laporan-keuangan.index') }}" class="font-semibold text-slate-900 hover:underline">Lihat Laporan →</a>
        </div>
    </div>
</div>

{{-- TRANSAKSI TERBARU --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-base text-slate-800">
                Transaksi Kas Terkini
            </h2>
            <p class="text-xs text-slate-500">
                5 transaksi terbaru yang tercatat di pembukuan
            </p>
        </div>
        <a href="{{ route('transaksi.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
            Semua Transaksi →
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-6 py-3">Kode</th>
                    <th class="text-left px-6 py-3">Tanggal</th>
                    <th class="text-left px-6 py-3">Kategori</th>
                    <th class="text-left px-6 py-3">Jenis</th>
                    <th class="text-right px-6 py-3">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($latestTransactions as $transaction)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-3.5 font-medium text-xs text-slate-800">
                            {{ $transaction->transaction_code }}
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-600">
                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-800 font-medium">
                            {{ $transaction->category?->name ?? 'Kas Umum' }}
                        </td>
                        <td class="px-6 py-3.5">
                            @if($transaction->type === 'income')
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                    Pemasukan
                                </span>
                            @else
                                <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800">
                                    Pengeluaran
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-right font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                            Belum ada transaksi kas tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
