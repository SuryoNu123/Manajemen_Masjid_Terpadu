<div class="space-y-5">

    <div>
        <label for="asset_code" class="mb-2 block text-sm font-medium text-slate-700">
            Kode Aset
        </label>
        <input id="asset_code" name="asset_code" type="text"
               value="{{ old('asset_code', $asset?->asset_code) }}"
               placeholder="Contoh: AST-001"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('asset_code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
            Nama Aset <span class="text-red-500">*</span>
        </label>
        <input id="name" name="name" type="text" required
               value="{{ old('name', $asset?->name) }}"
               placeholder="Contoh: AC Ruang Utama"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="category" class="mb-2 block text-sm font-medium text-slate-700">
            Kategori <span class="text-red-500">*</span>
        </label>
        <input id="category" name="category" type="text" required
               value="{{ old('category', $asset?->category) }}"
               placeholder="Contoh: Elektronik, Perabot, Peralatan Ibadah"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('category')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="condition" class="mb-2 block text-sm font-medium text-slate-700">
            Kondisi Aset <span class="text-red-500">*</span>
        </label>
        <select id="condition" name="condition" required
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
            <option value="baik" @selected(old('condition', $asset?->condition ?? 'baik') === 'baik')>
                Baik
            </option>
            <option value="perlu_perbaikan" @selected(old('condition', $asset?->condition ?? 'baik') === 'perlu_perbaikan')>
                Perlu Perbaikan
            </option>
            <option value="rusak" @selected(old('condition', $asset?->condition ?? 'baik') === 'rusak')>
                Rusak
            </option>
        </select>
        @error('condition')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="location" class="mb-2 block text-sm font-medium text-slate-700">
            Lokasi Aset
        </label>
        <input id="location" name="location" type="text"
               value="{{ old('location', $asset?->location) }}"
               placeholder="Contoh: Ruang utama"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('location')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="acquisition_date" class="mb-2 block text-sm font-medium text-slate-700">
            Tanggal Perolehan
        </label>
        <input id="acquisition_date" name="acquisition_date" type="date"
               max="{{ today()->toDateString() }}"
               value="{{ old('acquisition_date', $asset && $asset->acquisition_date ? $asset->acquisition_date->format('Y-m-d') : '') }}"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('acquisition_date')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="purchase_value" class="mb-2 block text-sm font-medium text-slate-700">
            Nilai Perolehan (Rp)
        </label>
        <input id="purchase_value" name="purchase_value" type="number"
               min="0" step="0.01"
               value="{{ old('purchase_value', $asset?->purchase_value) }}"
               placeholder="Contoh: 2500000"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('purchase_value')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-medium text-slate-700">
            Deskripsi
        </label>
        <textarea id="description" name="description" rows="3"
                  placeholder="Keterangan tambahan tentang aset..."
                  class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('description', $asset?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-center gap-3 rounded-lg border border-slate-200 p-4">
        <input type="checkbox" name="is_active" value="1"
               @checked(old('is_active', $asset?->is_active ?? true))
               class="h-4 w-4 rounded border-slate-300">
        <span>
            <span class="block text-sm font-medium text-slate-700">
                Aset Aktif
            </span>
            <span class="block text-xs text-slate-500">
                Hilangkan centang jika aset sudah tidak digunakan.
            </span>
        </span>
    </label>

</div>
