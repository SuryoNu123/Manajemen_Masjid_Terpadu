@extends('layouts.dashboard')

@section('title', 'Dashboard Ketua DKM')
@section('page-title', 'Dashboard Ketua DKM (Super Admin)')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">
        Selamat Datang, {{ auth()->user()->name }}
    </h1>
    <p class="text-gray-500 mt-1">
        Ringkasan lintas modul operasional, keuangan, dan dakwah Masjid Terpadu.
    </p>
</div>

{{-- STATISTIK KEUANGAN UTAMA --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            Total Saldo Kas
        </p>
        <p class="text-2xl font-bold text-slate-900 mt-2">
            Rp {{ number_format($balance, 0, ',', '.') }}
        </p>
        <p class="text-xs text-slate-400 mt-1">Saldo riil operasional masjid</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            Pemasukan (Bulan Ini)
        </p>
        <p class="text-2xl font-bold text-green-600 mt-2">
            Rp {{ number_format($monthlyIncome, 0, ',', '.') }}
        </p>
        <p class="text-xs text-slate-400 mt-1">Total: Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            Pengeluaran (Bulan Ini)
        </p>
        <p class="text-2xl font-bold text-red-600 mt-2">
            Rp {{ number_format($monthlyExpense, 0, ',', '.') }}
        </p>
        <p class="text-xs text-slate-400 mt-1">Total: Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
    </div>
 
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            Donasi Terverifikasi
        </p>
        <p class="text-2xl font-bold text-blue-700 mt-2">
            Rp {{ number_format($totalDonation, 0, ',', '.') }}
        </p>
        <p class="text-xs text-slate-400 mt-1">Infaq digital & QRIS</p>
    </div>
</div>

{{-- RINGKASAN LINTAS MODUL (DSH-F-002) --}}
<div class="mb-8">
    <h2 class="text-lg font-bold text-slate-800 mb-4">Ringkasan Lintas Modul Masjid</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- JAMAAH --}}
        <a href="{{ route('jamaahs.index') }}"
           class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:border-blue-300 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-600">Jamaah Terdaftar</span>

            </div>
            <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ number_format($totalJamaah) }}</p>
            <p class="mt-1 text-xs text-blue-700 font-medium">Buka Database Jamaah →</p>
        </a>

        {{-- SANTRI TPQ --}}
        <a href="{{ route('santris.index') }}"
           class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:border-blue-400 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-600">Santri TPQ/TPA</span>

            </div>
            <p class="mt-3 text-3xl font-extrabold text-emerald-700">{{ number_format($totalSantri) }}</p>
            <p class="mt-1 text-xs text-emerald-600 font-medium">Data & Capaian Santri →</p>
        </a>

        {{-- REMAJA MASJID --}}
        <a href="{{ route('youth-members.index') }}"
           class="group rounded-xl border border-slate-200 bg-white p-5 transition hover:border-purple-400 hover:shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-600">Remaja Masjid</span>

            </div>
            <p class="mt-3 text-3xl font-extrabold text-purple-700">{{ number_format($totalYouthMembers) }}</p>
            <p class="mt-1 text-xs text-purple-600 font-medium">{{ $totalYouthPrograms }} Program Kerja Aktif →</p>
        </a>

        {{-- ASET & INVENTARIS --}}
        <a href="{{ route('assets.index') }}"
           class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:border-amber-400 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-600">Inventaris Aset</span>

            </div>
            <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ number_format($totalAssets) }}</p>
            <p class="mt-1 text-xs {{ $needsRepairAssets > 0 ? 'text-amber-600 font-bold' : 'text-slate-400' }}">
                {{ $needsRepairAssets }} butuh perbaikan →
            </p>
        </a>
    </div>
</div>

{{-- STATUS LAPORAN & TRANSAKSI --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    {{-- KOTAK PERSETUJUAN LAPORAN KEUANGAN --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
        <div>
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-base text-slate-800">
                    Approval Laporan
                </h3>
                @if($pendingReports > 0)
                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                        {{ $pendingReports }} Menunggu
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-800 text-xs font-bold">
                        Up to date
                    </span>
                @endif
            </div>

            <p class="text-sm text-slate-500 leading-relaxed">
                Laporan keuangan bulanan diajukan oleh Bendahara dan membutuhkan tinjauan serta persetujuan resmi Ketua DKM sebelum dipublikasikan ke publik.
            </p>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100">
            <a href="{{ route('laporan-keuangan.index') }}"
               class="inline-flex items-center justify-center w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Tinjau Laporan Keuangan →
            </a>
        </div>
    </div>

    {{-- TRANSAKSI TERAKHIR --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-base text-slate-800">Transaksi Kas Terakhir</h3>
            <a href="{{ route('transaksi.index') }}" class="text-xs font-semibold text-blue-700 hover:underline">
                Semua Transaksi →
            </a>
        </div>

        <p class="px-5 pt-3 text-xs text-slate-500 lg:hidden">
            Geser tabel ke samping untuk melihat semua kolom.
        </p>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        <th class="px-5 py-3 text-left">Kategori</th>
                        <th class="px-5 py-3 text-left">Jenis</th>
                        <th class="px-5 py-3 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($latestTransactions as $tx)
                        <tr>
                            <td class="px-5 py-3 text-slate-600 text-xs whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-3 text-slate-800 font-medium">
                                {{ $tx->category?->name ?? 'Kas Umum' }}
                            </td>
                            <td class="px-5 py-3">
                                @if($tx->type === 'income')
                                    <span class="rounded bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700">Masuk</span>
                                @else
                                    <span class="rounded bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">Keluar</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right font-bold {{ $tx->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-slate-400">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- LOG AKTIVITAS SISTEM (AUT-F-004) --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="font-bold text-base text-slate-800">Audit Trail / Log Aktivitas Terkini</h3>
            <p class="text-xs text-slate-500">Pencatatan tindakan pengurus dan operasional sistem</p>
        </div>
        <a href="{{ route('activity-logs.index') }}" class="text-xs font-semibold text-blue-700 hover:underline">
            Buka Riwayat Lengkap →
        </a>
    </div>

    <div class="divide-y divide-slate-100 text-sm">
        @forelse($recentLogs as $log)
            <div class="py-2.5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                    <div>
                        <span class="font-semibold text-slate-800">{{ $log->user?->name ?? 'System' }}</span>
                        <span class="text-slate-600 text-xs ml-1">{{ $log->description }}</span>
                    </div>
                </div>
                <span class="text-xs text-slate-400 whitespace-nowrap">
                    {{ $log->created_at->diffForHumans() }}
                </span>
            </div>
        @empty
            <p class="text-center text-slate-400 py-4 text-xs">Belum ada log aktivitas tercatat.</p>
        @endforelse
    </div>
</div>

@endsection
