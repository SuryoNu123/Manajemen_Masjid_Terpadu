@extends('layouts.dashboard')

@section('title', 'Tambah Peminjaman')
@section('page-title', 'Tambah Peminjaman')

@section('content')
<div class="mx-auto max-w-2xl rounded-xl bg-white p-6 shadow-sm">

    <h2 class="mb-6 text-xl font-bold">
        Form Peminjaman Buku
    </h2>

    @if($errors->any())
        <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('book-loans.store') }}"
          class="space-y-5">
        @csrf

        <div>
            <label class="mb-2 block text-sm font-medium">
                Buku
            </label>

            <select name="library_book_id" required
                    class="w-full rounded-lg border border-slate-300">
                <option value="">Pilih Buku</option>

                @foreach($books as $book)
                    <option value="{{ $book->id }}"
                        @selected(old('library_book_id') == $book->id)>
                        {{ $book->title }}
                        (Tersedia:
                        {{ $book->total_copies - $book->borrowed_copies }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">
                Jamaah
            </label>

            <select name="jamaah_id" required
                    class="w-full rounded-lg border border-slate-300">
                <option value="">Pilih Jamaah</option>

                @foreach($jamaahs as $jamaah)
                    <option value="{{ $jamaah->id }}"
                        @selected(old('jamaah_id') == $jamaah->id)>
                        {{ $jamaah->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">
                Tanggal Pinjam
            </label>
            <input type="date" name="borrowed_at" required
                   value="{{ old('borrowed_at', today()->toDateString()) }}"
                   class="w-full rounded-lg border border-slate-300">
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">
                Batas Pengembalian
            </label>
            <input type="date" name="due_date" required
                   value="{{ old('due_date', today()->addDays(7)->toDateString()) }}"
                   class="w-full rounded-lg border border-slate-300">
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">
                Catatan
            </label>
            <textarea name="notes" rows="3"
                      class="w-full rounded-lg border border-slate-300">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('book-loans.index') }}"
               class="rounded-lg border px-5 py-2">
                Batal
            </a>

            <button type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-2 text-white">
                Simpan Peminjaman
            </button>
        </div>
    </form>

</div>
@endsection
