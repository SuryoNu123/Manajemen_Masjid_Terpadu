<div class="space-y-5">

    <div>
        <label for="asset_id" class="mb-2 block text-sm font-medium text-slate-700">
            Pilih Aset <span class="text-red-500">*</span>
        </label>
        <select id="asset_id" name="asset_id" required
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
            <option value="">-- Pilih Aset --</option>
            @foreach ($assets as $asset)
                <option value="{{ $asset->id }}"
                    @selected((string) old('asset_id', $maintenance?->asset_id) === (string) $asset->id)>
                    {{ $asset->asset_code ? $asset->asset_code . ' - ' : '' }}{{ $asset->name }}
                </option>
            @endforeach
        </select>
        @error('asset_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @if ($assets->isEmpty())
            <p class="mt-1 text-sm text-amber-700">
                Belum ada aset aktif. Tambahkan aset terlebih dahulu.
            </p>
        @endif
    </div>

    <div>
        <label for="title" class="mb-2 block text-sm font-medium text-slate-700">
            Nama Perawatan <span class="text-red-500">*</span>
        </label>
        <input id="title" name="title" type="text" required
               value="{{ old('title', $maintenance?->title) }}"
               placeholder="Contoh: Servis AC ruang utama"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-medium text-slate-700">
            Deskripsi Pekerjaan
        </label>
        <textarea id="description" name="description" rows="3"
                  class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('description', $maintenance?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="scheduled_date" class="mb-2 block text-sm font-medium text-slate-700">
                Tanggal Jadwal <span class="text-red-500">*</span>
            </label>
            <input id="scheduled_date" name="scheduled_date" type="date" required
                   value="{{ old('scheduled_date', $maintenance?->scheduled_date?->format('Y-m-d')) }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            @error('scheduled_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="completed_date" class="mb-2 block text-sm font-medium text-slate-700">
                Tanggal Selesai
            </label>
            <input id="completed_date" name="completed_date" type="date"
                   max="{{ today()->toDateString() }}"
                   value="{{ old('completed_date', $maintenance?->completed_date?->format('Y-m-d')) }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            @error('completed_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
            Status Perawatan <span class="text-red-500">*</span>
        </label>
        <select id="status" name="status" required
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
            <option value="terjadwal" @selected(old('status', $maintenance?->status ?? 'terjadwal') === 'terjadwal')>
                Terjadwal
            </option>
            <option value="dalam_proses" @selected(old('status', $maintenance?->status) === 'dalam_proses')>
                Dalam Proses
            </option>
            <option value="selesai" @selected(old('status', $maintenance?->status) === 'selesai')>
                Selesai
            </option>
            <option value="dibatalkan" @selected(old('status', $maintenance?->status) === 'dibatalkan')>
                Dibatalkan
            </option>
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="cost" class="mb-2 block text-sm font-medium text-slate-700">
                Biaya Perawatan (Rp)
            </label>
            <input id="cost" name="cost" type="number" min="0" step="0.01"
                   value="{{ old('cost', $maintenance?->cost ?? '0') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            @error('cost')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="performed_by" class="mb-2 block text-sm font-medium text-slate-700">
                Pelaksana Perawatan
            </label>
            <input id="performed_by" name="performed_by" type="text"
                   value="{{ old('performed_by', $maintenance?->performed_by) }}"
                   placeholder="Nama teknisi atau petugas"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
            @error('performed_by')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="notes" class="mb-2 block text-sm font-medium text-slate-700">
            Catatan Hasil Perawatan
        </label>
        <textarea id="notes" name="notes" rows="3"
                  placeholder="Catatan pekerjaan, komponen yang diganti, dan lain-lain"
                  class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('notes', $maintenance?->notes) }}</textarea>
        @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>
