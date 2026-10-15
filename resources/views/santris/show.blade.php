@extends('layouts.dashboard')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Santri</h1>
            <p class="mt-1 text-sm text-slate-500">
                Nomor pendaftaran: {{ $santri->registration_number }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('santris.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
                Kembali
            </a>
            <a href="{{ route('santris.edit', $santri) }}"
               class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                Edit Santri
            </a>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 border-b border-slate-200 pb-3 font-semibold text-slate-800">
            Informasi Santri
        </h2>

        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-sm text-slate-500">Nama lengkap</dt>
                <dd class="mt-1 font-medium">{{ $santri->name }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Jenis kelamin</dt>
                <dd class="mt-1 font-medium">{{ $santri->gender ?: '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Tanggal lahir</dt>
                <dd class="mt-1 font-medium">
                    {{ $santri->birth_date?->format('d/m/Y') ?? '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Kelas TPQ/TPA</dt>
                <dd class="mt-1 font-medium">{{ $santri->tpqClass?->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Tanggal pendaftaran</dt>
                <dd class="mt-1 font-medium">
                    {{ $santri->enrollment_date?->format('d/m/Y') ?? '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Status</dt>
                <dd class="mt-1 font-medium">
                    {{ $santri->status === 'active' ? 'Aktif' : 'Tidak aktif' }}
                </dd>
            </div>
        </dl>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 border-b border-slate-200 pb-3 font-semibold text-slate-800">
            Informasi Orang Tua atau Wali
        </h2>

        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-sm text-slate-500">Nama wali</dt>
                <dd class="mt-1 font-medium">{{ $santri->parent_name ?: '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Nomor telepon</dt>
                <dd class="mt-1 font-medium">{{ $santri->parent_phone ?: '-' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm text-slate-500">Alamat</dt>
                <dd class="mt-1 whitespace-pre-line font-medium">{{ $santri->address ?: '-' }}</dd>
            </div>
        </dl>
    </div>

    <div class="rounded-xl border border-emerald-200 bg-emerald-50/40 p-6 shadow-sm">
        <h2 class="mb-5 border-b border-emerald-200 pb-3 font-semibold text-emerald-900">
            Progres Hafalan & Bacaan Santri
        </h2>

        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-sm text-emerald-700 font-medium">Hafalan Al-Qur'an Terakhir</dt>
                <dd class="mt-1 font-bold text-slate-800 text-lg">{{ $santri->progress_hafalan ?: 'Belum dicatat' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-emerald-700 font-medium">Capaian Jilid / Kitab Bacaan</dt>
                <dd class="mt-1 font-bold text-slate-800 text-lg">{{ $santri->progress_bacaan ?: 'Belum dicatat' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm text-emerald-700 font-medium">Catatan Pembimbing / Ustadz</dt>
                <dd class="mt-1 text-slate-700 whitespace-pre-line">{{ $santri->learning_notes ?: '-' }}</dd>
            </div>
        </dl>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-4 font-semibold text-slate-800">10 Riwayat Kehadiran Terakhir</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($santri->attendances as $attendance)
                        <tr>
                            <td class="px-4 py-3">
                                {{ $attendance->attendance_date->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                @switch($attendance->status)
                                    @case('present') Hadir @break
                                    @case('sick') Sakit @break
                                    @case('permission') Izin @break
                                    @case('absent') Alpa @break
                                    @default {{ $attendance->status }}
                                @endswitch
                            </td>
                            <td class="px-4 py-3">{{ $attendance->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                                Belum ada riwayat kehadiran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
