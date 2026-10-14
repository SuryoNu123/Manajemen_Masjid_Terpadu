@extends('layouts.dashboard')

@section('title', 'Peminjaman Buku')
@section('page-title', 'Peminjaman Buku')

@section('content')
<div class="space-y-6">

    {{-- NOTIFIKASI --}}
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

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Terjadi kesalahan:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">
                Data Peminjaman Buku
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola peminjaman, keterlambatan, dan pengembalian buku perpustakaan masjid.
            </p>
        </div>

        <a href="{{ route('book-loans.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
            + Tambah Peminjaman
        </a>
    </div>

    {{-- FILTER --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET"
              action="{{ route('book-loans.index') }}"
              class="flex flex-col gap-3 sm:flex-row sm:items-end">

            <div class="w-full sm:max-w-xs">
                <label for="status"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Filter Status
                </label>

                <select id="status"
                        name="status"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                    <option value="">Semua Status</option>

                    <option value="borrowed" @selected($status === 'borrowed')>
                        Sedang Dipinjam
                    </option>

                    <option value="overdue" @selected($status === 'overdue')>
                        Terlambat
                    </option>

                    <option value="returned" @selected($status === 'returned')>
                        Sudah Dikembalikan
                    </option>

                    <option value="lost" @selected($status === 'lost')>
                        Hilang
                    </option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Filter
                </button>

                <a href="{{ route('book-loans.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm text-slate-600 hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- TABEL PEMINJAMAN --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">
                Riwayat Peminjaman
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Daftar seluruh transaksi peminjaman buku.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500">No.</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500">Kode Peminjaman</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500">Judul Buku</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500">Nama Jamaah</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500">Tanggal Pinjam</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500">Batas Kembali</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($loans as $loan)
                        @php
                            $isOverdue = $loan->status === 'borrowed'
                                && $loan->due_date
                                && $loan->due_date->lt(today());
                        @endphp

                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                                {{ $loans->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-800">
                                {{ $loan->loan_code }}
                            </td>

                            <td class="px-5 py-4 text-slate-700">
                                {{ $loan->book?->title ?? 'Buku tidak ditemukan' }}
                            </td>

                            <td class="px-5 py-4 text-slate-700">
                                {{ $loan->jamaah?->name ?? 'Jamaah tidak ditemukan' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                {{ $loan->borrowed_at?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                {{ $loan->due_date?->format('d/m/Y') ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                @if ($loan->status === 'returned')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Dikembalikan
                                    </span>
                                @elseif ($loan->status === 'lost')
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        Hilang
                                    </span>
                                @elseif ($isOverdue)
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                        Dipinjam
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4">
                                @if ($loan->status === 'borrowed')
                                    <div class="flex flex-col items-start gap-2">

                                        <form method="POST"
                                              action="{{ route('book-loans.return', $loan) }}"
                                              onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="font-medium text-blue-700 hover:underline">
                                                Kembalikan
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('book-loans.lost', $loan) }}"
                                              onsubmit="return confirm('Yakin buku ini hilang?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="font-medium text-red-600 hover:underline">
                                                Tandai Hilang
                                            </button>
                                        </form>

                                    </div>
                                @else
                                    <span class="text-slate-400">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <p class="font-medium text-slate-700">
                                    Belum ada data peminjaman.
                                </p>
                                <p class="mt-1 text-sm text-slate-500">
                                    Silakan tambahkan transaksi peminjaman buku.
                                </p>

                                <a href="{{ route('book-loans.create') }}"
                                   class="mt-4 inline-flex rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                                    + Tambah Peminjaman
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="border-t border-slate-200 px-5 py-4">
            {{ $loans->links() }}
        </div>
    </div>

</div>
@endsection
