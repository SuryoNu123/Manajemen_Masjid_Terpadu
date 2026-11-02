<div class="space-y-6">
    <div>
        <h2 class="mb-4 border-b border-slate-200 pb-2 font-semibold text-slate-800">
            Informasi Santri
        </h2>

        @isset($santri)
            <div class="mb-4">
                <label class="mb-2 block text-sm font-medium">Nomor Pendaftaran</label>
                <input type="text" value="{{ $santri->registration_number }}" readonly
                       class="w-full rounded-lg border border-slate-200 bg-slate-100 px-4 py-2.5 text-slate-600">
            </div>
        @endisset

        <div class="mb-4">
            <label class="mb-2 block text-sm font-medium">Nama Lengkap *</label>
            <input type="text" name="name" required maxlength="100"
                   value="{{ old('name', $santri->name ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium">Jenis Kelamin</label>
                <select name="gender"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="Laki-laki"
                        @selected(old('gender', $santri->gender ?? '') === 'Laki-laki')>
                        Laki-laki
                    </option>
                    <option value="Perempuan"
                        @selected(old('gender', $santri->gender ?? '') === 'Perempuan')>
                        Perempuan
                    </option>
                </select>
                @error('gender')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">Tanggal Lahir</label>
                <input type="date" name="birth_date"
                       value="{{ old('birth_date', isset($santri) && $santri->birth_date ? $santri->birth_date->format('Y-m-d') : '') }}"
                       max="{{ now()->toDateString() }}"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
                @error('birth_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div>
        <h2 class="mb-4 border-b border-slate-200 pb-2 font-semibold text-slate-800">
            Informasi Orang Tua atau Wali
        </h2>

        <div class="mb-4">
            <label class="mb-2 block text-sm font-medium">Nama Orang Tua/Wali</label>
            <input type="text" name="parent_name" maxlength="100"
                   value="{{ old('parent_name', $santri->parent_name ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
        </div>

        <div class="mb-4">
            <label class="mb-2 block text-sm font-medium">Nomor Telepon Wali</label>
            <input type="tel" name="parent_phone" maxlength="25"
                   value="{{ old('parent_phone', $santri->parent_phone ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Alamat</label>
            <textarea name="address" rows="3"
                      class="w-full rounded-lg border border-slate-300 px-4 py-2.5">{{ old('address', $santri->address ?? '') }}</textarea>
        </div>
    </div>

    <div>
        <h2 class="mb-4 border-b border-slate-200 pb-2 font-semibold text-slate-800">
            Informasi Kelas
        </h2>

        <div class="mb-4">
            <label class="mb-2 block text-sm font-medium">Kelas TPQ/TPA</label>
            <select name="tpq_class_id"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
                <option value="">Belum ditentukan</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}"
                        @selected((string) old('tpq_class_id', $santri->tpq_class_id ?? '') === (string) $class->id)>
                        {{ $class->name }}{{ $class->level ? ' - '.$class->level : '' }}
                    </option>
                @endforeach
            </select>
            @error('tpq_class_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium">Tanggal Pendaftaran</label>
                <input type="date" name="enrollment_date"
                       value="{{ old('enrollment_date', isset($santri) && $santri->enrollment_date ? $santri->enrollment_date->format('Y-m-d') : now()->toDateString()) }}"
                       max="{{ now()->toDateString() }}"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
                @error('enrollment_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">Status *</label>
                <select name="status" required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
                    <option value="active"
                        @selected(old('status', $santri->status ?? 'active') === 'active')>
                        Aktif
                    </option>
                    <option value="inactive"
                        @selected(old('status', $santri->status ?? 'active') === 'inactive')>
                        Tidak aktif
                    </option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div>
        <h2 class="mb-4 border-b border-slate-200 pb-2 font-semibold text-slate-800">
            Progres Pembelajaran & Hafalan
        </h2>

        <div class="grid gap-4 sm:grid-cols-2 mb-4">
            <div>
                <label class="mb-2 block text-sm font-medium">Progres Hafalan Al-Qur'an</label>
                <input type="text" name="progress_hafalan" maxlength="255"
                       placeholder="Contoh: Juz 30 / Surat An-Naba ayat 1-20"
                       value="{{ old('progress_hafalan', $santri->progress_hafalan ?? '') }}"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                @error('progress_hafalan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">Progres Bacaan / Kitab</label>
                <input type="text" name="progress_bacaan" maxlength="255"
                       placeholder="Contoh: Iqro Jilid 4 Hal 12 / Al-Qur'an Juz 2"
                       value="{{ old('progress_bacaan', $santri->progress_bacaan ?? '') }}"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                @error('progress_bacaan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Catatan Pembelajaran / Ustadz</label>
            <textarea name="learning_notes" rows="2"
                      placeholder="Catatan tajwid, makhraj, atau akhlak santri..."
                      class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('learning_notes', $santri->learning_notes ?? '') }}</textarea>
            @error('learning_notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-5">
    <a href="{{ route('santris.index') }}"
       class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm">
        Batal
    </a>

    <button type="submit"
            class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
        Simpan Data
    </button>
</div>
