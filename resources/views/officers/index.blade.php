@extends('layouts.dashboard')

@section('title', 'Data Petugas')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Data Petugas
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Kelola imam, khatib, muadzin, dan petugas kegiatan masjid.
            </p>
        </div>

        <a href="{{ route('officers.create') }}"
           class="px-5 py-3 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800">
            + Tambah Petugas
        </a>

    </div>

    @if(session('success'))

        <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>

    @endif

    <form method="GET" action="{{ route('officers.index') }}" class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="sm:col-span-2">
            <label for="officer-search" class="mb-1 block text-sm font-medium text-slate-700">Cari nama petugas</label>
            <input id="officer-search" name="search" type="search" value="{{ request('search') }}" maxlength="100" placeholder="Nama petugas" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="officer-position" class="mb-1 block text-sm font-medium text-slate-700">Posisi</label>
            <select id="officer-position" name="position" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua posisi</option>
                @foreach(['imam' => 'Imam', 'khatib' => 'Khatib', 'muadzin' => 'Muadzin', 'petugas' => 'Petugas', 'lainnya' => 'Lainnya'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('position') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="officer-status" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
            <select id="officer-status" name="status" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Tidak aktif</option>
            </select>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:col-span-2 lg:col-span-4">
            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Terapkan filter</button>
            <a href="{{ route('officers.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr>
                        <th class="px-6 py-4 text-left">Nama</th>
                        <th class="px-6 py-4 text-left">Posisi</th>
                        <th class="px-6 py-4 text-left">Telepon</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($officers as $officer)

                        <tr>

                            <td class="px-6 py-4 font-semibold">
                                {{ $officer->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ ucfirst($officer->position) }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $officer->phone ?? '-' }}
                            </td>

                            <td class="px-6 py-4">

                                @if($officer->is_active)

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        Aktif
                                    </span>

                                @else

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                        Tidak Aktif
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('officers.edit', $officer) }}"
                                       class="px-3 py-2 rounded-lg bg-slate-100">
                                        Edit
                                    </a>

                                    <form action="{{ route('officers.destroy', $officer) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus petugas ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="px-3 py-2 rounded-lg bg-red-50 text-red-600">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                Belum ada data petugas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($officers->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $officers->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
