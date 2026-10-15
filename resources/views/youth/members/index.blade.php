@extends('layouts.dashboard')

@section('title', 'Remaja Masjid - Anggota')
@section('page-title', 'Remaja Masjid (IRMA/Risma)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Struktur Organisasi & Anggota Remaja Masjid
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola data pengurus, kader pemuda, dan pembagian divisi kepemudaan masjid.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('youth-programs.index') }}"
               class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                📋 Program Kerja
            </a>

            <a href="{{ route('youth-members.create') }}"
               class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                + Tambah Anggota
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('youth-members.index') }}" class="flex flex-wrap gap-3">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama, jabatan, atau telepon..."
                   class="min-w-[220px] flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">

            <select name="division" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua Divisi</option>
                @foreach($divisions as $div)
                    <option value="{{ $div }}" @selected(request('division') === $div)>{{ $div }}</option>
                @endforeach
            </select>

            <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                <option value="alumni" @selected(request('status') === 'alumni')>Alumni</option>
                <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
            </select>

            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Cari
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-5 py-3">Nama Lengkap</th>
                        <th class="px-5 py-3">Divisi</th>
                        <th class="px-5 py-3">Jabatan</th>
                        <th class="px-5 py-3">Kontak</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4">
                                <div class="font-bold text-gray-900">{{ $member->name }}</div>
                                @if($member->jamaah)
                                    <div class="text-xs text-gray-400">Terdaftar Jamaah: {{ $member->jamaah->domicile }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-700">
                                {{ $member->division }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-200">
                                    {{ $member->position }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-600">
                                {{ $member->phone ?: '-' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    {{ $member->status === 'aktif' ? 'bg-green-100 text-green-700' : ($member->status === 'alumni' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('youth-members.edit', $member) }}" class="font-medium text-blue-600 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('youth-members.destroy', $member) }}" method="POST" onsubmit="return confirm('Hapus data anggota ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                                Belum ada anggota remaja masjid terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 p-4">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection

