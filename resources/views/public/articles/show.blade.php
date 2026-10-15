@extends('layouts.public')

@section('title', $article->title . ' - Artikel Dakwah Masjid Terpadu')

@section('content')
<section class="bg-slate-900 py-14 text-white">
    <div class="mx-auto max-w-4xl px-6">
        <a href="{{ route('dakwah.public') }}" class="inline-flex items-center gap-2 text-sm text-blue-400 hover:text-blue-300 mb-4">
            ← Kembali ke Daftar Artikel
        </a>

        <div class="flex items-center gap-3 text-xs text-slate-400 mb-3">
            <span>Diterbitkan: {{ $article->published_at?->format('d M Y') ?? '-' }}</span>
            <span>•</span>
            <span>Penulis: {{ $article->author?->name ?? 'DKM Masjid Terpadu' }}</span>
        </div>

        <h1 class="text-3xl md:text-5xl font-bold leading-tight">
            {{ $article->title }}
        </h1>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="mx-auto max-w-4xl px-6">
        <article class="rounded-2xl border border-gray-200 bg-white p-8 md:p-10 shadow-sm">
            @if($article->cover_image)
                <div class="mb-8 overflow-hidden rounded-xl">
                    <img src="{{ asset('storage/' . $article->cover_image) }}"
                         alt="{{ $article->title }}"
                         class="w-full max-h-[450px] object-cover">
                </div>
            @endif

            <div class="prose max-w-none text-gray-800 leading-relaxed text-base md:text-lg whitespace-pre-line">
                {{ $article->content }}
            </div>

            <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400">Bagikan artikel ini kepada kerabat dan keluarga jamaah.</p>
                </div>

                <a href="{{ route('dakwah.public') }}" class="text-sm font-semibold text-blue-600 hover:underline">
                    ← Artikel Lainnya
                </a>
            </div>
        </article>

        @if($recentArticles->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Artikel Dakwah Lainnya</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($recentArticles as $other)
                        <div class="rounded-xl border border-gray-200 p-5 bg-white hover:shadow-md transition">
                            <span class="text-xs text-gray-400">{{ $other->published_at?->format('d M Y') }}</span>
                            <h3 class="font-bold text-gray-900 mt-1 hover:text-blue-600">
                                <a href="{{ route('dakwah.show', $other->slug) }}">
                                    {{ $other->title }}
                                </a>
                            </h3>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

