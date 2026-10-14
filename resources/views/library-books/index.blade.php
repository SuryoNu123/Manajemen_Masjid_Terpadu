@extends('layouts.dashboard')

@section('title', 'Katalog Perpustakaan')
@section('page-title', 'Katalog Perpustakaan')

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
                Katalog Buku dan Kitab
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola koleksi buku dan kitab perpustakaan masjid.
            </p>
        </div>

        <a href="{{ route('library-books.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
            + Tambah Buku
        </a>
    </div>

    {{-- Pencarian dan filter --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET"
              action="{{ route('library-books.index') }}"
              class="grid grid-cols-1 gap-3 sm:grid-cols-3">

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari kode, judul, penulis, ISBN..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-500 focus:outline-none"
            >

            <select name="category"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $item)
                    <option value="{{ $item }}" @selected($category === $item)>
                        {{ $item }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <select name="status"
                        class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                    <option value="">Semua Status</option>
                    <option value="aktif" @selected($status === 'aktif')>
                        Aktif
                    </option>
                    <option value="nonaktif" @selected($status === 'nonaktif')>
                        Nonaktif
                    </option>
                </select>

                <button type="submit"
                        class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">
                    Cari
                </button>

                <a href="{{ route('library-books.index') }}"
                   class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-600">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel katalog --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">Daftar Koleksi</h3>
            <p class="mt-1 text-sm text-slate-500">
                Total {{ $books->total() }} buku atau kitab.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">No.</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kode / ISBN</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Judul Buku</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kategori</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Total</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Dipinjam</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Tersedia</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($books as $book)
                        @php
                            $borrowed = (int) $book->borrowed_copies;
                            $available = max(0, $book->total_copies - $borrowed);
                        @endphp

                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                {{ $books->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                <p class="font-medium text-slate-800">
                                    {{ $book->book_code }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    ISBN: {{ $book->isbn ?: '-' }}
                                </p>
                            </td>

                            <td class="min-w-56 px-5 py-4">
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $book->title }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $book->author ?: 'Penulis tidak dicantumkan' }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Rak: {{ $book->shelf_location ?: '-' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $book->category }}
                            </td>

                            <td class="px-5 py-4 text-center text-sm text-slate-600">
                                {{ $book->total_copies }}
                            </td>

                            <td class="px-5 py-4 text-center text-sm text-amber-700">
                                {{ $borrowed }}
                            </td>

                            <td class="px-5 py-4 text-center text-sm font-semibold {{ $available > 0 ? 'text-green-700' : 'text-red-600' }}">
                                {{ $available }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                @if ($book->is_active)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                <a href="{{ route('library-books.edit', $book) }}"
                                   class="inline-flex rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center text-sm text-slate-500">
                                Belum ada buku atau kitab.
                                <a href="{{ route('library-books.create') }}"
                                   class="font-medium text-blue-700 hover:underline">
                                    Tambahkan koleksi pertama.
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $books->links() }}
        </div>
    </div>
</div>
@endsection
