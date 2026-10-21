<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <link rel="icon" type="image/png" href="{{ asset('masjid-mark.png') }}">

    <title>
        @yield('title', 'Masjid Terpadu')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')

</head>


<body class="bg-gray-50 text-gray-900 antialiased">

    {{-- ================= NAVBAR PUBLIK ================= --}}

    <header class="sticky top-0 z-50 border-b border-gray-200 bg-white" x-data="{ mobileMenuOpen: false }">

        <div class="mx-auto max-w-7xl px-4 sm:px-6">

            <div class="flex h-16 items-center justify-between gap-3 sm:h-20">

                {{-- LOGO --}}

                <a
                    href="{{ url('/') }}"
                    class="flex items-center gap-3"
                >

                    <x-application-logo class="h-11 w-11 shrink-0" />

                    <div>

                        <h1 class="font-bold text-lg leading-tight">
                            Masjid Terpadu
                        </h1>

                        <p class="text-xs text-gray-500">
                            Sistem Manajemen Masjid
                        </p>

                    </div>

                </a>


                {{-- MENU PUBLIK --}}

                <nav class="hidden lg:flex items-center gap-6">

                    <a
                        href="{{ url('/') }}"
                    class="text-sm font-medium hover:text-blue-600 {{ request()->routeIs('home') ? 'text-blue-700' : 'text-gray-700' }}"
                    @if(request()->routeIs('home')) aria-current="page" @endif
                    >
                        Beranda
                    </a>

                    <a
                        href="{{ route('profile.public') }}"
                        class="text-sm font-medium hover:text-blue-600 {{ request()->routeIs('profile.public') ? 'text-blue-700' : 'text-gray-700' }}"
                        @if(request()->routeIs('profile.public')) aria-current="page" @endif
                    >
                        Profil
                    </a>

                    <a
                        href="{{ route('activities.public') }}"
                        class="text-sm font-medium hover:text-blue-600 {{ request()->routeIs('activities.public') ? 'text-blue-700' : 'text-gray-700' }}"
                        @if(request()->routeIs('activities.public')) aria-current="page" @endif
                    >
                        Kegiatan
                    </a>

                    <a
                        href="{{ route('announcements.public') }}"
                        class="text-sm font-medium hover:text-blue-600 {{ request()->routeIs('announcements.public', 'announcements.show') ? 'text-blue-700' : 'text-gray-700' }}"
                        @if(request()->routeIs('announcements.public', 'announcements.show')) aria-current="page" @endif
                    >
                        Pengumuman
                    </a>

                    <a
                        href="{{ route('dakwah.public') }}"
                        class="text-sm font-medium hover:text-blue-600 {{ request()->routeIs('dakwah.public', 'dakwah.show') ? 'text-blue-700' : 'text-gray-700' }}"
                        @if(request()->routeIs('dakwah.public', 'dakwah.show')) aria-current="page" @endif
                    >
                        Artikel
                    </a>

                    <a href="{{ route('library.public') }}" class="text-sm font-medium hover:text-blue-600 {{ request()->routeIs('library.public*') ? 'text-blue-700' : 'text-gray-700' }}" @if(request()->routeIs('library.public*')) aria-current="page" @endif>Perpustakaan</a>

                    <a
                        href="{{ route('donasi.create') }}"
                        class="text-sm font-medium hover:text-blue-600 {{ request()->routeIs('donasi.*') ? 'text-blue-700' : 'text-gray-700' }}"
                        @if(request()->routeIs('donasi.*')) aria-current="page" @endif
                    >
                        Donasi
                    </a>

                    <a
                        href="{{ route('zakat.index') }}"
                        class="text-sm font-medium hover:text-blue-600 {{ request()->routeIs('zakat.*') ? 'text-blue-700' : 'text-gray-700' }}"
                        @if(request()->routeIs('zakat.*')) aria-current="page" @endif
                    >
                        Zakat
                    </a>

                    <a
                        href="{{ route('transparansi.index') }}"
                        class="text-sm font-medium hover:text-blue-600 {{ request()->routeIs('transparansi.*') ? 'text-blue-700' : 'text-gray-700' }}"
                        @if(request()->routeIs('transparansi.*')) aria-current="page" @endif
                    >
                        Transparansi
                    </a>

                </nav>


                {{-- LOGIN / DASHBOARD --}}

                <div class="flex shrink-0 items-center gap-2">

                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-600 lg:hidden"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        :aria-expanded="mobileMenuOpen.toString()"
                        aria-controls="public-mobile-navigation"
                        aria-label="Buka atau tutup menu"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    @auth

                        <a
                            href="{{ route('dashboard') }}"
                            class="px-4 py-2.5 bg-slate-900 text-white rounded-lg text-sm font-semibold hover:bg-slate-800"
                        >
                            Dashboard
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold hover:bg-gray-50"
                        >
                            Login
                        </a>

                    @endauth

                </div>

            </div>

            <nav
                id="public-mobile-navigation"
                x-cloak
                x-show="mobileMenuOpen"
                x-transition
                @keydown.escape.window="mobileMenuOpen = false"
                class="grid gap-1 border-t border-gray-100 py-3 lg:hidden"
                aria-label="Navigasi utama"
            >
                <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('home') ? 'text-blue-700' : 'text-gray-700' }}" @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a>
                <a href="{{ route('profile.public') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('profile.public') ? 'text-blue-700' : 'text-gray-700' }}" @if(request()->routeIs('profile.public')) aria-current="page" @endif>Profil</a>
                <a href="{{ route('activities.public') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('activities.public') ? 'text-blue-700' : 'text-gray-700' }}" @if(request()->routeIs('activities.public')) aria-current="page" @endif>Kegiatan</a>
                <a href="{{ route('announcements.public') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('announcements.public', 'announcements.show') ? 'text-blue-700' : 'text-gray-700' }}" @if(request()->routeIs('announcements.public', 'announcements.show')) aria-current="page" @endif>Pengumuman</a>
                <a href="{{ route('dakwah.public') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('dakwah.public', 'dakwah.show') ? 'text-blue-700' : 'text-gray-700' }}" @if(request()->routeIs('dakwah.public', 'dakwah.show')) aria-current="page" @endif>Artikel</a>
                <a href="{{ route('library.public') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('library.public*') ? 'text-blue-700' : 'text-gray-700' }}" @if(request()->routeIs('library.public*')) aria-current="page" @endif>Perpustakaan</a>
                <a href="{{ route('donasi.create') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('donasi.*') ? 'text-blue-700' : 'text-gray-700' }}" @if(request()->routeIs('donasi.*')) aria-current="page" @endif>Donasi</a>
                <a href="{{ route('zakat.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('zakat.*') ? 'text-blue-700' : 'text-gray-700' }}" @if(request()->routeIs('zakat.*')) aria-current="page" @endif>Zakat</a>
                <a href="{{ route('transparansi.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('transparansi.*') ? 'text-blue-700' : 'text-gray-700' }}" @if(request()->routeIs('transparansi.*')) aria-current="page" @endif>Transparansi</a>
            </nav>

        </div>

    </header>


    {{-- ================= CONTENT ================= --}}

    <main class="min-h-screen">

        @yield('content')

    </main>


    {{-- ================= FOOTER ================= --}}

    <footer class="bg-slate-900 text-white mt-16">

        <div class="max-w-7xl mx-auto px-6 py-12">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">


                {{-- BRAND --}}

                <div>

                    <h2 class="text-xl font-bold">
                        Masjid Terpadu
                    </h2>

                    <p class="text-slate-400 text-sm mt-3 leading-relaxed">
                        Sistem Manajemen Masjid Terpadu untuk
                        mendukung pengelolaan masjid dan pelayanan
                        kepada jamaah.
                    </p>

                </div>


                {{-- LAYANAN --}}

                <div>

                    <h3 class="font-semibold mb-4">
                        Layanan
                    </h3>

                    <div class="space-y-2 text-sm">

                        <a
                            href="{{ route('donasi.create') }}"
                            class="block text-slate-400 hover:text-white"
                        >
                            Donasi
                        </a>

                        <a
                            href="{{ route('zakat.index') }}"
                            class="block text-slate-400 hover:text-white"
                        >
                            Kalkulator Zakat
                        </a>

                        <a
                            href="{{ route('activities.public') }}"
                            class="block text-slate-400 hover:text-white"
                        >
                            Kegiatan Masjid
                        </a>

                        <a href="{{ route('library.public') }}" class="block text-slate-400 hover:text-white">Katalog Perpustakaan</a>

                        <a
                            href="{{ route('transparansi.index') }}"
                            class="block text-slate-400 hover:text-white"
                        >
                            Transparansi Keuangan
                        </a>

                        <a
                            href="{{ route('digital-signage.index') }}"
                            class="block text-slate-400 hover:text-white"
                            target="_blank"
                        >
                            Digital Signage TV
                        </a>

                    </div>

                </div>


                {{-- INFORMASI --}}

                <div>

                    <h3 class="font-semibold mb-4">
                        Informasi Masjid
                    </h3>

                    <p class="text-slate-400 text-sm leading-relaxed">
                        Sistem Manajemen Masjid Terpadu menyediakan
                        informasi kegiatan, layanan jamaah,
                        dan informasi publik masjid.
                    </p>

                </div>

            </div>


            <div class="border-t border-slate-700 mt-10 pt-6">

                <p class="text-center text-sm text-slate-500">
                    © {{ date('Y') }} Masjid Terpadu.
                    Semua hak dilindungi.
                </p>

            </div>

        </div>

    </footer>


    @stack('scripts')

</body>

</html>
