@extends('layouts.public')

@section('title', 'Artikel Dakwah - Masjid Terpadu')

@section('content')
<section class="bg-slate-900 py-16 text-white">
    <div class="mx-auto max-w-7xl px-6">
        <span class="text-sm font-semibold uppercase tracking-wider text-blue-200">Pojok Literasi & Dakwah</span>
        <h1 class="mt-2 text-3xl md:text-4xl font-bold">Artikel Dakwah & Khazanah Islam</h1>
        <p class="mt-3 text-slate-300 max-w-2xl">
            Kumpulan artikel keagamaan, mutiara hikmah, dan berita seputar aktivitas kemakmuran Masjid Terpadu.
        </p>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-2xl font-bold text-gray-900">Semua Artikel</h2>

            <form method="GET" action="{{ route('dakwah.public') }}" class="flex w-full items-center gap-2 sm:w-auto">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari artikel dakwah..."
                       class="min-w-0 flex-1 rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-blue-600 sm:w-64 sm:flex-none">
                <button type="submit" class="min-h-10 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Cari
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articles as $article)
                <article class="flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-lg transition duration-200">
                    @if($article->cover_image)
                        <img src="{{ asset('storage/' . $article->cover_image) }}"
                             alt="{{ $article->title }}"
                             class="h-48 w-full object-cover">
                    @else
                        <div class="flex h-48 w-full items-center justify-center bg-blue-700 text-sm font-medium text-white">
                            📖
                        </div>
                    @endif

                    <div class="flex flex-1 flex-col justify-between p-6">
                        <div>
                            <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
                                <span>{{ $article->published_at?->format('d M Y') ?? '-' }}</span>
                                <span>•</span>
                                <span>{{ $article->author?->name ?? 'DKM Masjid' }}</span>
                            </div>

                            <h3 class="text-xl font-semibold text-slate-900 hover:text-blue-600">
                                <a href="{{ route('dakwah.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>

                            <p class="mt-3 text-sm text-gray-600 leading-relaxed line-clamp-3">
                                {{ $article->excerpt ?: Str::limit(strip_tags($article->content), 140) }}
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <a href="{{ route('dakwah.show', $article->slug) }}"
                               class="text-sm font-semibold text-blue-700 hover:text-blue-800">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-gray-400">
                    Belum ada artikel dakwah yang dipublikasikan.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </div>
</section>
@endsection

