@extends('shared.layouts.dashboard')

@section('title', 'Dashboard Sekretaris')
@section('page-title', 'Dashboard Sekretaris & Humas')

@section('content')

<div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
            Dashboard Sekretaris & Humas
        </h1>
        <p class="mt-2 max-w-3xl text-base leading-6 text-slate-600">
            Kelola administrasi jamaah, jadwal kegiatan ibadah/kajian, publikasi pengumuman, remaja masjid, dan santri TPQ.
        </p>
    </div>

    <div class="flex shrink-0 flex-nowrap items-center gap-2">
        <a href="{{ route('announcements.create') }}"
           class="whitespace-nowrap rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-blue-700">
            + Buat Pengumuman
        </a>
        <a href="{{ route('activities.create') }}"
           class="whitespace-nowrap rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
            + Tambah Kegiatan
        </a>
        <a href="{{ route('jamaahs.create') }}"
           class="whitespace-nowrap rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
            + Tambah Jamaah
        </a>
    </div>
</div>

{{-- STATISTIK ADMINISTRASI --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="rounded-2xl border border-slate-200 border-t-4 border-t-blue-600 bg-blue-50/40 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Database Jamaah</span>

        </div>
        <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ number_format($totalJamaah) }}</p>
        <a href="{{ route('jamaahs.index') }}" class="mt-2 inline-block text-xs font-semibold text-blue-700 hover:underline">
            Kelola Data Jamaah →
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 border-t-4 border-t-emerald-600 bg-emerald-50/40 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Santri TPQ/TPA</span>

        </div>
        <p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ number_format($totalSantri) }}</p>
        <a href="{{ route('santris.index') }}" class="mt-2 inline-block text-xs font-semibold text-emerald-600 hover:underline">
            Santri & Absensi TPQ →
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 border-t-4 border-t-violet-600 bg-violet-50/40 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Remaja Masjid</span>

        </div>
        <p class="mt-2 text-3xl font-extrabold text-purple-700">{{ number_format($totalYouth) }}</p>
        <a href="{{ route('youth-members.index') }}" class="mt-2 inline-block text-xs font-semibold text-purple-600 hover:underline">
            Anggota & Program Kerja →
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 border-t-4 border-t-amber-500 bg-amber-50/40 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Perpustakaan</span>

        </div>
        <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ number_format($totalBooks) }}</p>
        <p class="mt-1 text-xs text-slate-500">
            <span class="font-semibold text-amber-600">{{ $activeLoans }}</span> buku sedang dipinjam
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- KEGIATAN MENDATANG --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-base text-slate-800">Kegiatan & Kajian Terdekat</h2>
            <a href="{{ route('activities.index') }}" class="text-xs font-semibold text-blue-700 hover:underline">
                Kelola Semua →
            </a>
        </div>

        <div class="space-y-3">
            @forelse($upcomingActivities as $activity)
                <div class="flex items-start justify-between gap-3 rounded-lg border border-slate-100 p-3 hover:bg-slate-50">
                    <div>
                        <span class="text-xs font-bold uppercase text-blue-700">{{ $activity->category }}</span>
                        <h4 class="font-semibold text-slate-900 text-sm mt-0.5">{{ $activity->title }}</h4>
                        <p class="text-xs text-slate-500 mt-1">
                             {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }} • Waktu: {{ substr($activity->activity_time, 0, 5) }} WIB
                        </p>
                    </div>
                    <a href="{{ route('activities.edit', $activity) }}" class="text-xs text-slate-500 hover:text-blue-700 font-medium">
                        Edit
                    </a>
                </div>
            @empty
                <p class="text-center py-6 text-xs text-slate-400">Tidak ada jadwal kegiatan mendatang.</p>
            @endforelse
        </div>
    </div>

    {{-- PENGUMUMAN TERAKHIR --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-base text-slate-800">Pengumuman Masjid</h2>
            <a href="{{ route('announcements.index') }}" class="text-xs font-semibold text-blue-700 hover:underline">
                Kelola Semua →
            </a>
        </div>

        <div class="space-y-3">
            @forelse($recentAnnouncements as $announcement)
                <div class="flex items-start justify-between gap-3 rounded-lg border border-slate-100 p-3 hover:bg-slate-50">
                    <div>
                        <div class="flex items-center gap-2">
                            @if($announcement->type === 'emergency')
                                <span class="rounded-full bg-rose-100 px-2 py-1 text-[10px] font-bold tracking-wide text-rose-800">DARURAT</span>
                            @endif
                            @if($announcement->status === 'published')
                                <span class="rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-semibold text-emerald-800">Dipublikasikan</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-700">Draf</span>
                            @endif
                            <span class="text-[11px] text-slate-400">{{ $announcement->created_at->format('d/m/Y') }}</span>
                        </div>
                        <h4 class="font-semibold text-slate-900 text-sm mt-1">{{ $announcement->title }}</h4>
                    </div>
                    <a href="{{ route('announcements.edit', $announcement) }}" class="text-xs text-blue-700 hover:underline font-semibold">
                        Edit
                    </a>
                </div>
            @empty
                <p class="text-center py-6 text-xs text-slate-400">Belum ada pengumuman.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection
