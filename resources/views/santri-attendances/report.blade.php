@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <div>
       
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Rekap dan Laporan TPQ/TPA
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Pantau kehadiran santri berdasarkan periode dan kelas.
                </p>
            </div>

            <a href="{{ route('santri-attendances.export', [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'class_id' => $classId,
            ]) }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-800">
                <span>↓</span>
                Ekspor Excel
            </a>
        </div>

        <p class="mt-1 text-sm text-slate-500">
            Pantau kehadiran santri berdasarkan periode dan kelas.
        </p>
    </div>

    @if($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter laporan --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET"
              action="{{ route('santri-attendances.report') }}"
              class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div>
                <label class="mb-2 block text-sm font-medium">Dari Tanggal</label>
                <input type="date" name="start_date"
                       value="{{ $startDate }}"
                       max="{{ today()->toDateString() }}"
                       required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">Sampai Tanggal</label>
                <input type="date" name="end_date"
                       value="{{ $endDate }}"
                       max="{{ today()->toDateString() }}"
                       required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">Kelas</label>
                <select name="class_id"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
                    <option value="">Semua kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            @selected((string) $classId === (string) $class->id)>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Tampilkan
                </button>
                <a href="{{ route('santri-attendances.report') }}"
                   class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <p class="text-sm text-slate-500">
        Periode:
        <span class="font-medium text-slate-700">
            {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
            – {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        </span>
    </p>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Catatan Absensi</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ number_format($totalRecords) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Hadir</p>
            <p class="mt-2 text-3xl font-bold text-green-700">
                {{ number_format($totalPresent) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Sakit</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">
                {{ number_format($totalSick) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Izin</p>
            <p class="mt-2 text-3xl font-bold text-blue-700">
                {{ number_format($totalPermission) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Alpa</p>
            <p class="mt-2 text-3xl font-bold text-red-700">
                {{ number_format($totalAbsent) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Persentase Hadir</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ number_format($attendancePercentage, 1) }}%
            </p>
            <p class="mt-1 text-xs text-slate-500">
                Dari catatan absensi yang tersedia
            </p>
        </div>
    </div>

    {{-- Rekap per kelas --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 p-5">
            <h2 class="font-semibold text-slate-800">Rekap per Kelas</h2>
            <p class="mt-1 text-sm text-slate-500">
                Ringkasan status kehadiran setiap kelas dalam periode terpilih.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Kelas</th>
                        <th class="px-5 py-4">Santri Aktif</th>
                        <th class="px-5 py-4">Hadir</th>
                        <th class="px-5 py-4">Sakit</th>
                        <th class="px-5 py-4">Izin</th>
                        <th class="px-5 py-4">Alpa</th>
                        <th class="px-5 py-4">Persentase Hadir</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($classReports as $class)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 font-medium text-slate-800">
                                {{ $class->name }}
                                @if($class->level)
                                    <p class="mt-1 text-xs font-normal text-slate-500">
                                        {{ $class->level }}
                                    </p>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                {{ $class->active_santris_count }}
                            </td>

                            <td class="px-5 py-4 text-green-700">
                                {{ $class->present_total }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $class->sick_total }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $class->permission_total }}
                            </td>

                            <td class="px-5 py-4 text-red-700">
                                {{ $class->absent_total }}
                            </td>

                            <td class="px-5 py-4">
                                {{ number_format($class->attendance_percentage, 1) }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-500">
                                Belum ada data kelas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Detail catatan absensi --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 p-5">
            <h2 class="font-semibold text-slate-800">Detail Catatan Absensi</h2>
            <p class="mt-1 text-sm text-slate-500">
                Daftar catatan kehadiran dalam periode yang dipilih.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Nama Santri</th>
                        <th class="px-5 py-4">Kelas</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Catatan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($recentAttendances as $attendance)
                        <tr>
                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $attendance->attendance_date->format('d/m/Y') }}
                            </td>

                            <td class="px-5 py-4 font-medium text-slate-800">
                                {{ $attendance->santri?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $attendance->santri?->tpqClass?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                @switch($attendance->status)
                                    @case('present')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">Hadir</span>
                                        @break
                                    @case('sick')
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs text-amber-700">Sakit</span>
                                        @break
                                    @case('permission')
                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs text-blue-700">Izin</span>
                                        @break
                                    @case('absent')
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs text-red-700">Alpa</span>
                                        @break
                                @endswitch
                            </td>

                            <td class="px-5 py-4">
                                {{ $attendance->notes ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-500">
                                Belum ada catatan absensi dalam periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $recentAttendances->links() }}
        </div>
    </div>

</div>
@endsection
