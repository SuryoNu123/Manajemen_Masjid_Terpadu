@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Absensi Santri TPQ/TPA</h1>
        <p class="mt-1 text-sm text-slate-500">
            Catat dan pantau kehadiran santri berdasarkan kelas dan tanggal.
        </p>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="mb-4 font-semibold text-slate-800">Pilih Kelas dan Tanggal</h2>

        <form method="GET" action="{{ route('santri-attendances.index') }}"
              class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="mb-2 block text-sm font-medium">Tanggal Absensi</label>
                <input type="date" name="date" value="{{ $date }}"
                       max="{{ today()->toDateString() }}" required
                       class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">Kelas TPQ/TPA</label>
                <select name="class_id" required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
                    <option value="">Pilih kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            @selected((string) $classId === (string) $class->id)>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Tampilkan Santri
                </button>
            </div>
        </form>
    </div>

    @if($classId)
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @php
                $attendanceSummary = [
                    ['label' => 'Hadir', 'status' => 'present'],
                    ['label' => 'Sakit', 'status' => 'sick'],
                    ['label' => 'Izin', 'status' => 'permission'],
                    ['label' => 'Alpa', 'status' => 'absent'],
                ];
            @endphp

            @foreach($attendanceSummary as $item)
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">{{ $item['label'] }}</p>
                    <p class="mt-2 text-2xl font-bold text-slate-800">
                        {{ $report[$item['status']] ?? 0 }}
                    </p>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('santri-attendances.store') }}"
              class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            @csrf

            <input type="hidden" name="attendance_date" value="{{ $date }}">
            <input type="hidden" name="class_id" value="{{ $classId }}">

            <div class="flex flex-col justify-between gap-2 border-b border-slate-200 p-5 sm:flex-row sm:items-center">
                <div>
                    <h2 class="font-semibold text-slate-800">Daftar Kehadiran</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $santris->count() }} santri aktif ·
                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                    </p>
                </div>

                @if($santris->isNotEmpty())
                    <button type="submit"
                            class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                        Simpan Absensi
                    </button>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-4">No.</th>
                            <th class="px-5 py-4">Nama Santri</th>
                            <th class="px-5 py-4">Status Kehadiran</th>
                            <th class="px-5 py-4">Catatan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($santris as $santri)
                            @php
                                $existing = $attendanceRecords->get($santri->id);
                                $oldStatus = old("attendances.{$loop->index}.status");
                                $currentStatus = $oldStatus ?? $existing?->status;
                            @endphp

                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4">{{ $loop->iteration }}</td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-800">
                                        {{ $santri->name }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $santri->registration_number }}
                                    </p>
                                </td>

                                <td class="min-w-48 px-5 py-4">
                                    <input type="hidden"
                                           name="attendances[{{ $loop->index }}][santri_id]"
                                           value="{{ $santri->id }}">

                                    <select name="attendances[{{ $loop->index }}][status]"
                                            required
                                            class="w-full rounded-lg border border-slate-300 px-3 py-2">
                                        <option value="">Pilih status</option>
                                        <option value="present" @selected($currentStatus === 'present')>
                                            Hadir
                                        </option>
                                        <option value="sick" @selected($currentStatus === 'sick')>
                                            Sakit
                                        </option>
                                        <option value="permission" @selected($currentStatus === 'permission')>
                                            Izin
                                        </option>
                                        <option value="absent" @selected($currentStatus === 'absent')>
                                            Alpa
                                        </option>
                                    </select>
                                </td>

                                <td class="min-w-56 px-5 py-4">
                                    <input type="text"
                                           name="attendances[{{ $loop->index }}][notes]"
                                           value="{{ old("attendances.{$loop->index}.notes", $existing?->notes) }}"
                                           placeholder="Catatan (opsional)"
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-slate-500">
                                    Belum ada santri aktif di kelas ini.
                                    Tambahkan data santri atau periksa kelas yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($santris->isNotEmpty())
                <div class="flex justify-end border-t border-slate-200 p-5">
                    <button type="submit"
                            class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                        Simpan Absensi
                    </button>
                </div>
            @endif
        </form>
    @else
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center">
            <h2 class="font-semibold text-slate-800">Pilih kelas terlebih dahulu</h2>
            <p class="mt-2 text-sm text-slate-500">
                Daftar santri dan rekap kehadiran akan muncul setelah kelas dipilih.
            </p>
        </div>
    @endif

</div>
@endsection
