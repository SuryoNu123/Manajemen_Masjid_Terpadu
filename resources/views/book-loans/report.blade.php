@extends('layouts.dashboard')

@section('title', 'Laporan Perpustakaan')
@section('page-title', 'Laporan Perpustakaan')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">
                Dashboard Perpustakaan
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Ringkasan koleksi dan aktivitas peminjaman buku.
            </p>
        </div>

        <a href="{{ route('book-loans.index') }}"
           class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">
            Lihat Peminjaman
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Judul Buku</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ number_format($totalBooks) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Eksemplar</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ number_format($totalCopies) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Sedang Dipinjam</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">
                {{ number_format($activeLoans) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Terlambat</p>
            <p class="mt-2 text-3xl font-bold text-red-600">
                {{ number_format($overdueLoans) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Sudah Dikembalikan</p>
            <p class="mt-2 text-3xl font-bold text-green-600">
                {{ number_format($returnedLoans) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Peminjaman Buku Hilang</p>
            <p class="mt-2 text-3xl font-bold text-red-600">
                {{ number_format($lostLoans) }}
            </p>
        </div>

    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">
                Transaksi Peminjaman Terbaru
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Kode</th>
                        <th class="px-5 py-3">Judul Buku</th>
                        <th class="px-5 py-3">Jamaah</th>
                        <th class="px-5 py-3">Tanggal Pinjam</th>
                        <th class="px-5 py-3">Batas Kembali</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($recentLoans as $loan)
                        <tr>
                            <td class="px-5 py-4 font-medium text-slate-800">
                                {{ $loan->loan_code }}
                            </td>
                            <td class="px-5 py-4">
                                {{ $loan->book?->title ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                {{ $loan->jamaah?->name ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                {{ $loan->borrowed_at?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                {{ $loan->due_date?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                @if($loan->status === 'returned')
                                    <span class="text-green-700">Dikembalikan</span>
                                @elseif($loan->status === 'lost')
                                    <span class="text-red-700">Hilang</span>
                                @elseif($loan->due_date && $loan->due_date->lt(today()))
                                    <span class="text-red-700">Terlambat</span>
                                @else
                                    <span class="text-amber-700">Dipinjam</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                                Belum ada transaksi peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
