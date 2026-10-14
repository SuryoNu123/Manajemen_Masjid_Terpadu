<form action="{{ $action }}"
      method="POST"
      enctype="multipart/form-data"
      class="space-y-5">

    @csrf

    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div>
        <label for="title" class="mb-2 block text-sm font-medium text-gray-700">
            Judul Konten
        </label>
        <input type="text"
               id="title"
               name="title"
               value="{{ old('title', $content?->title) }}"
               required
               maxlength="255"
               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="excerpt" class="mb-2 block text-sm font-medium text-gray-700">
            Ringkasan Artikel
        </label>
        <textarea id="excerpt"
                  name="excerpt"
                  rows="3"
                  maxlength="1000"
                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">{{ old('excerpt', $content?->excerpt) }}</textarea>
        @error('excerpt')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="content" class="mb-2 block text-sm font-medium text-gray-700">
            Isi Konten
        </label>
        <textarea id="content"
                  name="content"
                  rows="10"
                  required
                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">{{ old('content', $content?->content) }}</textarea>
        @error('content')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="cover_image" class="mb-2 block text-sm font-medium text-gray-700">
            Gambar Sampul
        </label>
        <input type="file"
               id="cover_image"
               name="cover_image"
               accept="image/*"
               class="block w-full rounded-lg border border-gray-300 p-2 text-sm">
        <p class="mt-1 text-xs text-gray-500">
            Format gambar yang didukung oleh validasi aplikasi, maksimal 2 MB.
        </p>

        @if($content?->cover_image)
            <img src="{{ asset('storage/' . $content->cover_image) }}"
                 alt="Gambar sampul"
                 class="mt-3 h-32 rounded-lg object-cover">
        @endif

        @error('cover_image')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
            Status Publikasi
        </label>
        <select id="status"
                name="status"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
            <option value="draft"
                @selected(old('status', $content?->status ?? 'draft') === 'draft')>
                Simpan sebagai draf
            </option>
            <option value="published"
                @selected(old('status', $content?->status ?? 'draft') === 'published')>
                Publikasikan
            </option>
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-wrap gap-3 border-t border-gray-100 pt-5">
        <button type="submit"
                class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
            Simpan Konten
        </button>

        <a href="{{ route('dakwah-contents.index') }}"
           class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>
