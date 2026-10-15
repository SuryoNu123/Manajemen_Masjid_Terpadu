@extends('layouts.dashboard')

@section('title', 'Tambah Program Kerja Remaja')
@section('page-title', 'Tambah Program Kerja Remaja Masjid')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Program Kerja</h1>
            <p class="mt-1 text-sm text-gray-500">Susun rencana kegiatan pemuda remaja masjid terpisah dari kegiatan umum.</p>
        </div>
        <a href="{{ route('youth-programs.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('youth-programs.store') }}" method="POST" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
        @csrf

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nama Program Kerja *</label>
            <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Pesantren Kilat Ramadhan, Futsal Ukhuwah, dll" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Divisi Penyelenggara</label>
                <input type="text" name="division" value="{{ old('division') }}" placeholder="Contoh: Kajian & Dakwah, Olahraga" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('division') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Penanggung Jawab (Ketua Pelaksana)</label>
                <select name="youth_member_id" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                    <option value="">Pilih anggota remaja masjid...</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" @selected(old('youth_member_id') == $m->id)>{{ $m->name }} ({{ $m->position }} - {{ $m->division }})</option>
                    @endforeach
                </select>
                @error('youth_member_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Mulai *</label>
                <input type="date" name="start_date" required value="{{ old('start_date', date('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('start_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('end_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Estimasi Anggaran (Rp)</label>
                <input type="number" name="budget" value="{{ old('budget', 0) }}" min="0" step="1000" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('budget') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Status Program *</label>
            <select name="status" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                <option value="rencana" @selected(old('status', 'rencana') === 'rencana')>Rencana</option>
                <option value="berjalan" @selected(old('status') === 'berjalan')>Sedang Berjalan</option>
                <option value="selesai" @selected(old('status') === 'selesai')>Selesai</option>
                <option value="dibatalkan" @selected(old('status') === 'dibatalkan')>Dibatalkan</option>
            </select>
            @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Deskripsi / Sasaran Program</label>
            <textarea name="description" rows="3" placeholder="Jelaskan tujuan kegiatan, target peserta, dan teknis acara..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">{{ old('description') }}</textarea>
            @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('youth-programs.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                Simpan Program Kerja
            </button>
        </div>
    </form>
</div>
@endsection

