@extends('layouts.dashboard')

@section('title', 'Detail Pengumuman')
@section('page-title', 'Detail Pengumuman')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('announcements.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
            ← Kembali ke Daftar Pengumuman
        </a>

        <div class="flex items-center gap-3">
            <a href="{{ route('announcements.edit', $announcement) }}"
               class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Edit
            </a>

            <form action="{{ route('announcements.destroy', $announcement) }}"
                  method="POST"
                  onsubmit="return confirm('Hapus pengumuman ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg border border-red-300 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            @if($announcement->type === 'emergency')
                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                    ⚠️ Informasi Darurat
                </span>
            @else
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                    Pengumuman Biasa
                </span>
            @endif

            @if($announcement->status === 'published')
                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                    Dipublikasikan
                </span>
            @else
                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                    Draf
                </span>
            @endif
        </div>

        <h1 class="text-3xl font-bold text-gray-900 leading-tight">
            {{ $announcement->title }}
        </h1>

        <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-gray-500 border-b border-gray-100 pb-4">
            <span>Dibuat oleh: <strong>{{ $announcement->author?->name ?? 'Pengurus' }}</strong></span>
            <span>•</span>
            <span>Waktu Terbit: {{ $announcement->published_at?->format('d M Y H:i') ?? 'Belum dipublikasikan' }}</span>
            @if($announcement->expires_at)
                <span>•</span>
                <span>Batas Tayang: {{ $announcement->expires_at->format('d M Y H:i') }}</span>
            @endif
        </div>

        <div class="prose max-w-none mt-6 text-gray-700 leading-relaxed whitespace-pre-line text-base">
            {{ $announcement->content }}
        </div>
    </div>
</div>
@endsection

