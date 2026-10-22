@extends('layouts.dashboard')

@section('title', 'Dashboard Jamaah')
@section('page-title', 'Portal Jamaah')

@section('content')

<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Ahlan wa Sahlan, {{ auth()->user()->name }}
        </h1>
        <p class="text-gray-500 mt-1">
            Selamat datang di Portal Jamaah Masjid Terpadu. Pantau jadwal sholat, pengumuman, kajian dakwah, dan infaq online.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('donasi.create') }}"
           class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">
             Infaq & Donasi Online
        </a>
        <a href="{{ route('zakat.index') }}"
           class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
            Kalkulator Zakat
        </a>
    </div>
</div>

{{-- BANNER INFORMASI RINGKAS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="rounded-xl bg-slate-900 p-5 text-white">
        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Transparansi Kas Masjid</span>
        <p class="mt-2 text-2xl font-bold text-white">Rp {{ number_format($balance, 0, ',', '.') }}</p>
        <div class="mt-3">
            <a href="{{ route('transparansi.index') }}" class="text-xs font-semibold text-blue-400 hover:text-blue-300">
                Lihat Laporan Transparansi Keuangan →
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Layanan Donasi Online</span>
        <p class="mt-2 text-sm text-slate-600">
            Dukung program operasional masjid, renovasi, santunan yatim, dan zakat secara digital dengan QRIS.
        </p>
        <div class="mt-3">
            <a href="{{ route('donasi.create') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                Salurkan Donasi Anda →
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Website & Media Masjid</span>
        <p class="mt-2 text-sm text-slate-600">
            Akses jadwal kajian, profil struktur DKM, artikel dakwah, dan layar TV digital masjid.
        </p>
        <div class="mt-3 flex gap-3 text-xs font-semibold text-blue-600">
            <a href="{{ route('home') }}" class="hover:underline">Beranda Website</a>
            <span>•</span>
            <a href="{{ route('digital-signage.index') }}" target="_blank" class="hover:underline">TV Masjid ↗</a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- JADWAL KEGIATAN & KAJIAN --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-base text-slate-800">Kajian & Kegiatan Terdekat</h2>
            <a href="{{ route('activities.public') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                Lihat Semua →
            </a>
        </div>

        <div class="space-y-3">
            @forelse($upcomingActivities as $activity)
                <div class="rounded-lg border border-slate-100 p-3.5 hover:bg-slate-50">
                    <span class="rounded bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 uppercase">
                        {{ $activity->category }}
                    </span>
                    <h4 class="font-bold text-slate-900 text-sm mt-1">{{ $activity->title }}</h4>
                    <p class="text-xs text-slate-500 mt-1">
                         {{ \Carbon\Carbon::parse($activity->activity_date)->format('d F Y') }} • ⏰ {{ substr($activity->activity_time, 0, 5) }} WIB
                    </p>
                    @if($activity->speaker)
                        <p class="text-xs text-slate-600 mt-0.5"> Penceramah: {{ $activity->speaker }}</p>
                    @endif
                </div>
            @empty
                <p class="text-center py-6 text-xs text-slate-400">Belum ada agenda kegiatan mendatang.</p>
            @endforelse
        </div>
    </div>

    {{-- PENGUMUMAN TERBARU --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-base text-slate-800">Pengumuman & Warta Masjid</h2>
            <a href="{{ route('announcements.public') }}" class="text-xs font-semibold text-blue-700 hover:underline">
                Semua Pengumuman →
            </a>
        </div>

        <div class="space-y-3">
            @forelse($latestAnnouncements as $announcement)
                <div class="rounded-lg border border-slate-100 p-3.5 hover:bg-slate-50">
                    <div class="flex items-center gap-2">
                        @if($announcement->type === 'emergency')
                            <span class="rounded bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-700">DARURAT</span>
                        @endif
                        <span class="text-xs text-slate-400">{{ $announcement->published_at?->format('d M Y') }}</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mt-1">
                        <a href="{{ route('announcements.show', $announcement) }}" class="hover:text-blue-600">
                            {{ $announcement->title }}
                        </a>
                    </h4>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                        {{ \Illuminate\Support\Str::limit($announcement->content, 120) }}
                    </p>
                </div>
            @empty
                <p class="text-center py-6 text-xs text-slate-400">Belum ada pengumuman.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection
