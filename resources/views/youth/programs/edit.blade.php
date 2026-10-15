@extends('layouts.dashboard')

@section('title', 'Edit Program Kerja Remaja')
@section('page-title', 'Edit Program Kerja Remaja Masjid')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Program Kerja</h1>
            <p class="mt-1 text-sm text-gray-500">Perbarui progres atau detail pelaksanaan kegiatan pemuda remaja masjid.</p>
        </div>
        <a href="{{ route('youth-programs.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('youth-programs.update', $youthProgram) }}" method="POST" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nama Program Kerja *</label>
            <input type="text" name="name" required value="{{ old('name', $youthProgram->name) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Divisi Penyelenggara</label>
                <input type="text" name="division" value="{{ old('division', $youthProgram->division) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('division') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Penanggung Jawab (Ketua Pelaksana)</label>
                <select name="youth_member_id" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                    <option value="">Pilih anggota remaja masjid...</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" @selected(old('youth_member_id', $youthProgram->youth_member_id) == $m->id)>{{ $m->name }} ({{ $m->position }} - {{ $m->division }})</option>
                    @endforeach
                </select>
                @error('youth_member_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Mulai *</label>
                <input type="date" name="start_date" required value="{{ old('start_date', $youthProgram->start_date?->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('start_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date', $youthProgram->end_date?->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('end_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Estimasi Anggaran (Rp)</label>
                <input type="number" name="budget" value="{{ old('budget', $youthProgram->budget) }}" min="0" step="1000" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                @error('budget') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Status Program *</label>
            <select name="status" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                <option value="rencana" @selected(old('status', $youthProgram->status) === 'rencana')>Rencana</option>
                <option value="berjalan" @selected(old('status', $youthProgram->status) === 'berjalan')>Sedang Berjalan</option>
                <option value="selesai" @selected(old('status', $youthProgram->status) === 'selesai')>Selesai</option>
                <option value="dibatalkan" @selected(old('status', $youthProgram->status) === 'dibatalkan')>Dibatalkan</option>
            </select>
            @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Deskripsi / Sasaran Program</label>
            <textarea name="description" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">{{ old('description', $youthProgram->description) }}</textarea>
            @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('youth-programs.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                Perbarui Program Kerja
            </button>
        </div>
    </form>
</div>
@endsection

