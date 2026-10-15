@extends('layouts.dashboard')

@section('title', 'Remaja Masjid - Program Kerja')
@section('page-title', 'Program Kerja Remaja Masjid')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Program Kerja Remaja Masjid (IRMA/Risma)
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Pencatatan program kerja, kegiatan kepemudaan, anggaran, dan penanggung jawab program.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('youth-members.index') }}"
               class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                👥 Data Anggota
            </a>

            <a href="{{ route('youth-programs.create') }}"
               class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                + Tambah Program Kerja
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('youth-programs.index') }}" class="flex flex-wrap gap-3">
            <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="rencana" @selected(request('status') === 'rencana')>Rencana</option>
                <option value="berjalan" @selected(request('status') === 'berjalan')>Sedang Berjalan</option>
                <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                <option value="dibatalkan" @selected(request('status') === 'dibatalkan')>Dibatalkan</option>
            </select>

            <select name="division" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua Divisi</option>
                @foreach($divisions as $div)
                    <option value="{{ $div }}" @selected(request('division') === $div)>{{ $div }}</option>
                @endforeach
            </select>

            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Filter
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-5 py-3">Nama Program</th>
                        <th class="px-5 py-3">Divisi</th>
                        <th class="px-5 py-3">Jadwal Pelaksanaan</th>
                        <th class="px-5 py-3">Penanggung Jawab</th>
                        <th class="px-5 py-3">Anggaran</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($programs as $prog)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4">
                                <div class="font-bold text-gray-900">{{ $prog->name }}</div>
                                @if($prog->description)
                                    <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">{{ $prog->description }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-700">
                                {{ $prog->division ?: '-' }}
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-600">
                                {{ $prog->start_date->format('d/m/Y') }}
                                @if($prog->end_date)
                                    s/d {{ $prog->end_date->format('d/m/Y') }}
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-800">
                                {{ $prog->leader?->name ?? 'Belum ditentukan' }}
                            </td>
                            <td class="px-5 py-4 font-mono font-semibold text-slate-700">
                                Rp {{ number_format($prog->budget, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    @if($prog->status === 'selesai') bg-green-100 text-green-700
                                    @elseif($prog->status === 'berjalan') bg-blue-100 text-blue-700
                                    @elseif($prog->status === 'dibatalkan') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($prog->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('youth-programs.edit', $prog) }}" class="font-medium text-blue-600 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('youth-programs.destroy', $prog) }}" method="POST" onsubmit="return confirm('Hapus program kerja ini?')">
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
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                Belum ada program kerja remaja masjid.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 p-4">
            {{ $programs->links() }}
        </div>
    </div>
</div>
@endsection

