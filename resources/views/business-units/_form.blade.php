<div class="space-y-5">

    <div>
        <label for="code" class="mb-2 block text-sm font-medium text-slate-700">
            Kode Unit Usaha
        </label>
        <input id="code" name="code" type="text"
               value="{{ old('code', $businessUnit?->code) }}"
               placeholder="Contoh: USH-001"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
            Nama Unit Usaha <span class="text-red-500">*</span>
        </label>
        <input id="name" name="name" type="text" required
               value="{{ old('name', $businessUnit?->name) }}"
               placeholder="Contoh: Koperasi Masjid"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="type" class="mb-2 block text-sm font-medium text-slate-700">
            Jenis Usaha <span class="text-red-500">*</span>
        </label>
        <input id="type" name="type" type="text" required
               value="{{ old('type', $businessUnit?->type) }}"
               placeholder="Contoh: Koperasi, Kantin, Penyewaan"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="location" class="mb-2 block text-sm font-medium text-slate-700">
            Lokasi
        </label>
        <input id="location" name="location" type="text"
               value="{{ old('location', $businessUnit?->location) }}"
               placeholder="Lokasi unit usaha"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('location')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="manager_name" class="mb-2 block text-sm font-medium text-slate-700">
            Penanggung Jawab
        </label>
        <input id="manager_name" name="manager_name" type="text"
               value="{{ old('manager_name', $businessUnit?->manager_name) }}"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('manager_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="phone" class="mb-2 block text-sm font-medium text-slate-700">
            Nomor Telepon
        </label>
        <input id="phone" name="phone" type="text"
               value="{{ old('phone', $businessUnit?->phone) }}"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="initial_capital" class="mb-2 block text-sm font-medium text-slate-700">
            Modal Awal (Rp)
        </label>
        <input id="initial_capital" name="initial_capital" type="number"
               min="0" step="0.01"
               value="{{ old('initial_capital', $businessUnit?->initial_capital) }}"
               placeholder="Contoh: 5000000"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('initial_capital')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-medium text-slate-700">
            Deskripsi
        </label>
        <textarea id="description" name="description" rows="3"
                  class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('description', $businessUnit?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-center gap-3 rounded-lg border border-slate-200 p-4">
        <input type="checkbox" name="is_active" value="1"
               @checked(old('is_active', $businessUnit?->is_active ?? true))
               class="h-4 w-4 rounded border-slate-300">
        <span>
            <span class="block text-sm font-medium text-slate-700">
                Unit Usaha Aktif
            </span>
            <span class="block text-xs text-slate-500">
                Hilangkan centang untuk menonaktifkan unit usaha.
            </span>
        </span>
    </label>
</div>
