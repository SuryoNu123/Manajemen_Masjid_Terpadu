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

    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">

        <div class="max-w-7xl mx-auto px-6">

            <div class="h-20 flex items-center justify-between">

                {{-- LOGO --}}

                <a
                    href="{{ url('/') }}"
                    class="flex items-center gap-3"
                >

                    <div
                        class="w-11 h-11 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xl"
                    >
                        🕌
                    </div>

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
                        class="text-sm font-medium text-gray-700 hover:text-blue-600"
                    >
                        Beranda
                    </a>

                    <a
                        href="{{ route('profile.public') }}"
                        class="text-sm font-medium text-gray-700 hover:text-blue-600"
                    >
                        Profil
                    </a>

                    <a
                        href="{{ route('activities.public') }}"
                        class="text-sm font-medium text-gray-700 hover:text-blue-600"
                    >
                        Kegiatan
                    </a>

                    <a
                        href="{{ route('announcements.public') }}"
                        class="text-sm font-medium text-gray-700 hover:text-blue-600"
                    >
                        Pengumuman
                    </a>

                    <a
                        href="{{ route('dakwah.public') }}"
                        class="text-sm font-medium text-gray-700 hover:text-blue-600"
                    >
                        Artikel
                    </a>

                    <a
                        href="{{ route('donasi.create') }}"
                        class="text-sm font-medium text-gray-700 hover:text-blue-600"
                    >
                        Donasi
                    </a>

                    <a
                        href="{{ route('zakat.index') }}"
                        class="text-sm font-medium text-gray-700 hover:text-blue-600"
                    >
                        Zakat
                    </a>

                    <a
                        href="{{ route('transparansi.index') }}"
                        class="text-sm font-medium text-gray-700 hover:text-blue-600"
                    >
                        Transparansi
                    </a>

                </nav>


                {{-- LOGIN / DASHBOARD --}}

                <div>

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