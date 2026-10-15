@extends('layouts.dashboard')

@section('title', 'Tambah Anggota Remaja Masjid')
@section('page-title', 'Tambah Anggota Remaja Masjid')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Anggota Remaja Masjid</h1>
            <p class="mt-1 text-sm text-gray-500">Daftarkan kader dan pemuda masjid ke dalam struktur organisasi.</p>
        </div>
        <a href="{{ route('youth-members.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('youth-members.store') }}" method="POST" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
        @csrf

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Hubungkan dengan Data Jamaah (Opsional)</label>
            <select name="jamaah_id" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                <option value="">Bukan dari database jamaah / belum terdaftar</option>
                @foreach($jamaahs as $j)
                    <option value="{{ $j->id }}" @selected(old('jamaah_id') == $j->id)>{{ $j->name }} ({{ $j->domicile }})</option>
                @endforeach
            </select>
            @error('jamaah_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nama Lengkap *</label>
            <input type="text" name="name" required value="{{ old('name') }}" placeholder="Nama kader remaja masjid..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Divisi *</label>
                <input type="text" name="division" required value="{{ old('division') }}" placeholder="Contoh: Kajian & Dakwah, Humas & Media, dll" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('division') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Jabatan *</label>
                <input type="text" name="position" required value="{{ old('position') }}" placeholder="Contoh: Ketua, Sekretaris, Koordinator, Anggota" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('position') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Nomor Telepon/WA</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" max="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('birth_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Status Keanggotaan *</label>
                <select name="status" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                    <option value="alumni" @selected(old('status') === 'alumni')>Alumni</option>
                    <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                </select>
                @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Tahun Bergabung</label>
            <input type="number" name="joined_year" value="{{ old('joined_year', date('Y')) }}" min="2000" max="{{ date('Y') + 1 }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
            @error('joined_year') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Catatan Tambahan</label>
            <textarea name="notes" rows="2" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">{{ old('notes') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('youth-members.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                Simpan Anggota
            </button>
        </div>
    </form>
</div>
@endsection

