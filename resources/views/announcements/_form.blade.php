<form action="{{ $action }}" method="POST" class="space-y-5">
    @csrf

    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div>
        <label for="title" class="mb-2 block text-sm font-medium text-gray-700">
            Judul Pengumuman
        </label>

        <input type="text"
               id="title"
               name="title"
               value="{{ old('title', $announcement?->title) }}"
               required
               maxlength="255"
               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:outline-none">

        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="content" class="mb-2 block text-sm font-medium text-gray-700">
            Isi Pengumuman
        </label>

        <textarea id="content"
                  name="content"
                  rows="8"
                  required
                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:outline-none">{{ old('content', $announcement?->content) }}</textarea>

        @error('content')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="type" class="mb-2 block text-sm font-medium text-gray-700">
                Jenis Pengumuman
            </label>

            <select id="type"
                    name="type"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                <option value="normal"
                    @selected(old('type', $announcement?->type ?? 'normal') === 'normal')>
                    Pengumuman Biasa
                </option>

                <option value="emergency"
                    @selected(old('type', $announcement?->type ?? 'normal') === 'emergency')>
                    Informasi Darurat
                </option>
            </select>

            @error('type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                Status
            </label>

            <select id="status"
                    name="status"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                <option value="draft"
                    @selected(old('status', $announcement?->status ?? 'draft') === 'draft')>
                    Draf
                </option>

                <option value="published"
                    @selected(old('status', $announcement?->status ?? 'draft') === 'published')>
                    Publikasikan
                </option>
            </select>

            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="expires_at" class="mb-2 block text-sm font-medium text-gray-700">
            Batas Tayang (Opsional)
        </label>

        <input type="datetime-local"
               id="expires_at"
               name="expires_at"
               value="{{ old('expires_at', $announcement?->expires_at?->format('Y-m-d\TH:i')) }}"
               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:outline-none">

        <p class="mt-1 text-xs text-gray-500">
            Kosongkan jika pengumuman tidak memiliki batas tayang otomatis.
        </p>

        @error('expires_at')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-wrap gap-3 border-t border-gray-100 pt-5">
        <button type="submit"
                class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
            Simpan Pengumuman
        </button>

        <a href="{{ route('announcements.index') }}"
           class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>
