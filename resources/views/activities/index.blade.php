@extends('layouts.dashboard')

@section('title', 'Kegiatan Ibadah')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Kegiatan Ibadah
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Kelola jadwal kajian, khutbah Jumat, dan kegiatan ibadah.
            </p>
        </div>

        <a href="{{ route('activities.create') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-slate-900 text-white font-medium hover:bg-slate-800 transition">
            + Tambah Kegiatan
        </a>

    </div>

    @if(session('success'))

        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif

    <form method="GET" action="{{ route('activities.index') }}" class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="sm:col-span-2">
            <label for="activity-search" class="mb-1 block text-sm font-medium text-slate-700">Cari kegiatan</label>
            <input id="activity-search" name="search" type="search" value="{{ request('search') }}" maxlength="100" placeholder="Judul, penceramah, atau lokasi" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="activity-type" class="mb-1 block text-sm font-medium text-slate-700">Jenis</label>
            <select id="activity-type" name="type" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua jenis</option>
                @foreach($activityTypes as $activityType)
                    <option value="{{ $activityType }}" @selected(request('type') === $activityType)>{{ ucfirst(str_replace('_', ' ', $activityType)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="activity-status" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
            <select id="activity-status" name="status" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua status</option>
                @foreach(['scheduled' => 'Terjadwal', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:col-span-2 lg:col-span-4">
            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Terapkan filter</button>
            <a href="{{ route('activities.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr>

                        <th class="text-left px-6 py-4 font-semibold text-slate-700">
                            Kegiatan
                        </th>

                        <th class="text-left px-6 py-4 font-semibold text-slate-700">
                            Jenis
                        </th>

                        <th class="text-left px-6 py-4 font-semibold text-slate-700">
                            Tanggal
                        </th>

                        <th class="text-left px-6 py-4 font-semibold text-slate-700">
                            Waktu
                        </th>

                        <th class="text-left px-6 py-4 font-semibold text-slate-700">
                            Lokasi
                        </th>

                        <th class="text-left px-6 py-4 font-semibold text-slate-700">
                            Status
                        </th>

                        <th class="text-right px-6 py-4 font-semibold text-slate-700">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($activities as $activity)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">

                                <div class="font-semibold text-slate-900">
                                    {{ $activity->title }}
                                </div>

                                @if($activity->speaker)
                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ $activity->speaker }}
                                    </div>
                                @endif

                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ ucfirst(str_replace('_', ' ', $activity->type)) }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $activity->activity_date?->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">

                                {{ $activity->start_time }}

                                @if($activity->end_time)
                                    - {{ $activity->end_time }}
                                @endif

                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $activity->location ?? '-' }}
                            </td>

                            <td class="px-6 py-4">

                                @php
                                    $statusClasses = [
                                        'scheduled' => 'bg-blue-50 text-blue-700',
                                        'ongoing' => 'bg-yellow-50 text-yellow-700',
                                        'completed' => 'bg-green-50 text-green-700',
                                        'cancelled' => 'bg-red-50 text-red-700',
                                    ];
                                @endphp

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClasses[$activity->status] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst($activity->status) }}
                                </span>

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    {{-- Detail --}}
                                    <a href="{{ route('activities.show', $activity) }}"
                                    class="px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100">
                                        Detail
                                    </a>


                                    {{-- Edit --}}
                                    <a href="{{ route('activities.edit', $activity) }}"
                                    class="px-3 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">
                                        Edit
                                    </a>


                                    {{-- Hapus --}}
                                    <form action="{{ route('activities.destroy', $activity) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus kegiatan ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="px-6 py-12 text-center text-slate-500">

                                Belum ada kegiatan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($activities->hasPages())

            <div class="px-6 py-4 border-t border-slate-200">
                {{ $activities->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
