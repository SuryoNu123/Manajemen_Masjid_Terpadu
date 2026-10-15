@extends('layouts.public')

@section('title', 'Pengumuman - Masjid Terpadu')

@section('content')
<section class="bg-slate-900 py-16 text-white">
    <div class="mx-auto max-w-7xl px-6">
        <span class="text-sm font-semibold uppercase tracking-wider text-blue-400">Pusat Informasi</span>
        <h1 class="mt-2 text-3xl md:text-4xl font-bold">Pengumuman & Informasi Masjid</h1>
        <p class="mt-3 text-slate-300 max-w-2xl">
            Informasi terkini seputar kegiatan ibadah, layanan sosial, dan kabar penting untuk seluruh jamaah Masjid Terpadu.
        </p>
    </div>
</section>

@if($emergencyAnnouncements->isNotEmpty())
<section class="bg-red-50 border-y border-red-200 py-6">
    <div class="mx-auto max-w-7xl px-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
            </span>
            <h2 class="text-lg font-bold text-red-800">Informasi Darurat / Penting</h2>
        </div>

        <div class="space-y-3">
            @foreach($emergencyAnnouncements as $item)
                <div class="rounded-xl border border-red-300 bg-white p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="rounded bg-red-600 px-2 py-0.5 text-xs font-semibold text-white">DARURAT</span>
                            <span class="text-xs text-gray-500">{{ $item->published_at?->format('d M Y H:i') }}</span>
                        </div>
                        <h3 class="mt-1 font-bold text-gray-900 text-lg">
                            <a href="{{ route('announcements.show', $item) }}" class="hover:text-red-700">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                            {{ Str::limit($item->content, 180) }}
                        </p>
                    </div>
                    <a href="{{ route('announcements.show', $item) }}"
                       class="whitespace-nowrap inline-flex items-center gap-1 font-semibold text-sm text-red-600 hover:text-red-800">
                        Baca Lengkap →
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-12 bg-white">
    <div class="mx-auto max-w-7xl px-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Pengumuman</h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('announcements.public') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium {{ !request('type') ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua
                </a>
                <a href="{{ route('announcements.public', ['type' => 'normal']) }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium {{ request('type') === 'normal' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Biasa
                </a>
                <a href="{{ route('announcements.public', ['type' => 'emergency']) }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium {{ request('type') === 'emergency' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Darurat
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($announcements as $announcement)
                <article class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            @if($announcement->type === 'emergency')
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Darurat
                                </span>
                            @else
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                    Pengumuman
                                </span>
                            @endif

                            <span class="text-xs text-gray-400">
                                {{ $announcement->published_at?->format('d M Y') }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900">
                            <a href="{{ route('announcements.show', $announcement) }}" class="hover:text-blue-600">
                                {{ $announcement->title }}
                            </a>
                        </h3>

                        <p class="mt-3 text-sm text-gray-600 leading-relaxed line-clamp-3">
                            {{ Str::limit($announcement->content, 150) }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs text-gray-400">
                            {{ $announcement->published_at?->format('H:i') }} WIB
                        </span>

                        <a href="{{ route('announcements.show', $announcement) }}"
                           class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                            Selengkapnya →
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-gray-400">
                    Belum ada pengumuman yang dipublikasikan.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $announcements->links() }}
        </div>
    </div>
</section>
@endsection

