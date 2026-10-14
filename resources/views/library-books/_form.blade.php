<div class="space-y-5">

    <div>
        <label for="book_code" class="mb-2 block text-sm font-medium text-slate-700">
            Kode Buku <span class="text-red-500">*</span>
        </label>
        <input id="book_code" name="book_code" type="text" required
               value="{{ old('book_code', $book?->book_code) }}"
               placeholder="Contoh: BUK-001"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('book_code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="title" class="mb-2 block text-sm font-medium text-slate-700">
            Judul Buku / Kitab <span class="text-red-500">*</span>
        </label>
        <input id="title" name="title" type="text" required
               value="{{ old('title', $book?->title) }}"
               placeholder="Masukkan judul buku atau kitab"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="isbn" class="mb-2 block text-sm font-medium text-slate-700">
            ISBN
        </label>
        <input id="isbn" name="isbn" type="text"
               value="{{ old('isbn', $book?->isbn) }}"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('isbn')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="author" class="mb-2 block text-sm font-medium text-slate-700">
                Penulis
            </label>
            <input id="author" name="author" type="text"
                   value="{{ old('author', $book?->author) }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            @error('author')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="publisher" class="mb-2 block text-sm font-medium text-slate-700">
                Penerbit
            </label>
            <input id="publisher" name="publisher" type="text"
                   value="{{ old('publisher', $book?->publisher) }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            @error('publisher')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="publication_year" class="mb-2 block text-sm font-medium text-slate-700">
                Tahun Terbit
            </label>
            <input id="publication_year" name="publication_year" type="number"
                   min="1000" max="{{ date('Y') }}"
                   value="{{ old('publication_year', $book?->publication_year) }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            @error('publication_year')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category" class="mb-2 block text-sm font-medium text-slate-700">
                Kategori <span class="text-red-500">*</span>
            </label>
            <input id="category" name="category" type="text" required
                   list="library-categories"
                   value="{{ old('category', $book?->category) }}"
                   placeholder="Contoh: Fikih, Tafsir, Akidah"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            <datalist id="library-categories">
                <option value="Al-Qur'an">
                <option value="Tafsir">
                <option value="Hadis">
                <option value="Fikih">
                <option value="Akidah">
                <option value="Sejarah Islam">
                <option value="Umum">
            </datalist>
            @error('category')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="total_copies" class="mb-2 block text-sm font-medium text-slate-700">
                Jumlah Eksemplar <span class="text-red-500">*</span>
            </label>
            <input id="total_copies" name="total_copies" type="number"
                   min="1" step="1" required
                   value="{{ old('total_copies', $book?->total_copies ?? 1) }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            @error('total_copies')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @isset($book->borrowed_copies)
                <p class="mt-1 text-xs text-slate-500">
                    Sedang dipinjam: {{ $book->borrowed_copies }} eksemplar.
                </p>
            @endisset
        </div>

        <div>
            <label for="shelf_location" class="mb-2 block text-sm font-medium text-slate-700">
                Lokasi Rak
            </label>
            <input id="shelf_location" name="shelf_location" type="text"
                   value="{{ old('shelf_location', $book?->shelf_location) }}"
                   placeholder="Contoh: Rak A-01"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            @error('shelf_location')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-medium text-slate-700">
            Deskripsi
        </label>
        <textarea id="description" name="description" rows="3"
                  class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('description', $book?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-center gap-3 rounded-lg border border-slate-200 p-4">
        <input type="checkbox" name="is_active" value="1"
               @checked(old('is_active', $book?->is_active ?? true))
               class="h-4 w-4 rounded border-slate-300">
        <span>
            <span class="block text-sm font-medium text-slate-700">
                Buku Aktif
            </span>
            <span class="block text-xs text-slate-500">
                Hilangkan centang jika buku tidak lagi tersedia untuk peminjaman.
            </span>
        </span>
    </label>

</div>
