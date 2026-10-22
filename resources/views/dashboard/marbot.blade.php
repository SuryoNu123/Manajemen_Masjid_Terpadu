@extends('layouts.dashboard')

@section('title', 'Dashboard Marbot')
@section('page-title', 'Dashboard Marbot & Operasional')

@section('content')

<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Dashboard Marbot & Petugas
        </h1>
        <p class="text-gray-500 mt-1">
            Kelola operasional harian masjid: jadwal sholat 5 waktu, presensi jamaah, pemeliharaan fasilitas dan aset masjid.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('prayer-attendances.index') }}"
           class="rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-blue-700">
            + Presensi Sholat
        </a>
        <a href="{{ route('digital-signage.index') }}" target="_blank"
           class="rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white hover:bg-slate-800">
             Buka TV Masjid ↗
        </a>
    </div>
</div>

{{-- STATUS OPERASIONAL HARI INI --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Presensi Jamaah Hari Ini</span>
        <p class="mt-2 text-3xl font-extrabold text-blue-600">{{ number_format($todayAttendances) }}</p>
        <div class="mt-2 text-xs text-slate-500">
            Catatan kehadiran sholat berjamaah hari ini
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Fasilitas Butuh Perbaikan</span>
        <p class="mt-2 text-3xl font-extrabold {{ count($damagedAssets) > 0 ? 'text-red-600' : 'text-emerald-600' }}">
            {{ count($damagedAssets) }}
        </p>
        <div class="mt-2 text-xs text-slate-500">
            Aset dengan status rusak / perlu perbaikan
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Layar Digital Signage TV</span>
        <p class="mt-2 text-base font-bold text-slate-900">Digital Clock & Countdown</p>
        <div class="mt-2">
            <a href="{{ route('digital-signage.index') }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline">
                Buka Tampilan TV Layar Penuh →
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- JADWAL SHOLAT HARI INI --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-base text-slate-800">Jadwal Sholat Hari Ini ({{ date('d M Y') }})</h2>
            <a href="{{ route('jadwal-sholat.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                Sinkron / Edit →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @forelse($todaySchedules as $schedule)
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 text-center">
                    <span class="text-xs font-bold uppercase text-slate-500">{{ $schedule->prayer_name }}</span>
                    <p class="text-lg font-extrabold text-slate-900 mt-1">{{ substr($schedule->prayer_time, 0, 5) }}</p>
                </div>
            @empty
                <div class="col-span-3 text-center py-6 text-xs text-slate-400">
                    Jadwal sholat hari ini belum tersedia.
                    <a href="{{ route('jadwal-sholat.index') }}" class="text-blue-600 underline ml-1">Sinkronkan sekarang</a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ASET BUTUH PERBAIKAN --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-base text-slate-800">Aset Perlu Perhatian / Perbaikan</h2>
            <a href="{{ route('assets.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                Daftar Aset →
            </a>
        </div>

        <div class="space-y-3">
            @forelse($damagedAssets as $asset)
                <div class="flex items-center justify-between gap-3 rounded-lg border border-red-100 bg-red-50/50 p-3">
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">{{ $asset->name }}</h4>
                        <p class="text-xs text-slate-500">Lokasi: {{ $asset->location ?? 'Masjid' }} • Kode: {{ $asset->asset_code }}</p>
                    </div>
                    <span class="rounded bg-red-100 px-2 py-0.5 text-xs font-bold text-red-700 uppercase">
                        {{ str_replace('_', ' ', $asset->condition) }}
                    </span>
                </div>
            @empty
                <p class="text-center py-6 text-xs text-slate-400">Alhamdulillah, seluruh fasilitas dan inventaris dalam kondisi baik.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection
