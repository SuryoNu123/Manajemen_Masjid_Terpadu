@extends('layouts.public')

@section('title', $announcement->title . ' - Pengumuman Masjid Terpadu')

@section('content')
<section class="bg-slate-900 py-12 text-white">
    <div class="mx-auto max-w-4xl px-6">
        <a href="{{ route('announcements.public') }}" class="mb-4 inline-flex items-center gap-2 text-sm text-blue-200 hover:text-white">
            ← Kembali ke Semua Pengumuman
        </a>

        <div class="flex items-center gap-3 mb-3">
            @if($announcement->type === 'emergency')
                <span class="rounded-full bg-red-500/20 text-red-300 border border-red-500/30 px-3 py-1 text-xs font-semibold">
                     Darurat
                </span>
            @else
                <span class="rounded-full border border-blue-400/25 bg-blue-400/10 px-3 py-1 text-xs font-semibold text-blue-200">
                    Pengumuman Resmi
                </span>
            @endif

            <span class="text-xs text-slate-400">
                Diterbitkan pada {{ $announcement->published_at?->format('d M Y, H:i') ?? '-' }} WIB
            </span>
        </div>

        <h1 class="text-3xl md:text-4xl font-bold leading-tight">
            {{ $announcement->title }}
        </h1>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="mx-auto max-w-4xl px-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <div class="prose max-w-none text-gray-800 leading-relaxed text-base whitespace-pre-line">
                {{ $announcement->content }}
            </div>

            @if($announcement->expires_at)
                <div class="mt-8 rounded-xl bg-gray-50 border border-gray-200 p-4 text-xs text-gray-500">
                    Pengumuman ini berlaku sampai {{ $announcement->expires_at->format('d M Y H:i') }} WIB.
                </div>
            @endif

            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">
                    Pengurus DKM Masjid Terpadu
                </span>

                <a href="{{ route('announcements.public') }}" class="text-sm font-semibold text-blue-700 hover:underline">
                    Lihat Pengumuman Lainnya →
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

