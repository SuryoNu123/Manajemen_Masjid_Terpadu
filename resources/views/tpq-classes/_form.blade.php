<div class="space-y-5">
    <div>
        <label class="mb-2 block text-sm font-medium">Nama Kelas *</label>
        <input type="text" name="name" required maxlength="100"
               value="{{ old('name', $tpqClass->name ?? '') }}"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-medium">Tingkat</label>
            <input type="text" name="level"
                   value="{{ old('level', $tpqClass->level ?? '') }}"
                   placeholder="Contoh: Iqra, Al-Qur'an"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Pengajar</label>
            <input type="text" name="teacher_name"
                   value="{{ old('teacher_name', $tpqClass->teacher_name ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium">Jadwal Belajar</label>
        <input type="text" name="schedule"
               value="{{ old('schedule', $tpqClass->schedule ?? '') }}"
               placeholder="Contoh: Senin dan Rabu, 15.30–17.00"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium">Deskripsi</label>
        <textarea name="description" rows="4"
                  class="w-full rounded-lg border border-slate-300 px-4 py-2.5">{{ old('description', $tpqClass->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium">Status *</label>
        <select name="is_active" required
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
            <option value="1" @selected((string) old('is_active', isset($tpqClass) ? (int) $tpqClass->is_active : 1) === '1')>
                Aktif
            </option>
            <option value="0" @selected((string) old('is_active', isset($tpqClass) ? (int) $tpqClass->is_active : 1) === '0')>
                Tidak aktif
            </option>
        </select>
    </div>
</div>

<div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-5">
    <a href="{{ route('tpq-classes.index') }}"
       class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm">
        Batal
    </a>

    <button type="submit"
            class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
        Simpan
    </button>
</div>
