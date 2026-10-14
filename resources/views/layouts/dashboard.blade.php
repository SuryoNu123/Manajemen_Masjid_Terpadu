<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Dashboard') - Sistem Manajemen Masjid
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Menyembunyikan scrollbar sidebar tanpa mematikan scroll */
        .sidebar-menu {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .sidebar-menu::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-800">

<div class="min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="fixed inset-y-0 left-0 z-40 flex h-screen w-64 flex-col overflow-hidden bg-slate-900 text-white">

        {{-- HEADER SIDEBAR --}}
        <div class="shrink-0 border-b border-slate-700 px-6 py-5">
            <h1 class="text-lg font-bold">
                Masjid Terpadu
            </h1>

            <p class="mt-1 text-xs text-slate-400">
                Sistem Manajemen Masjid
            </p>
        </div>

        {{-- MENU SIDEBAR --}}
        <nav class="sidebar-menu min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-contain p-4">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-3 hover:bg-slate-800">
                <span>Dashboard</span>
            </a>

            {{-- MENU BERDASARKAN ROLE --}}
            @if(auth()->user()->hasRole('ketua'))
                @include('layouts.sidebar.ketua')
            @elseif(auth()->user()->hasRole('bendahara'))
                @include('layouts.sidebar.bendahara')
            @elseif(auth()->user()->hasRole('sekretaris'))
                @include('layouts.sidebar.sekretaris')
            @elseif(auth()->user()->hasRole('marbot'))
                @include('layouts.sidebar.marbot')
            @elseif(auth()->user()->hasRole('jamaah'))
                @include('layouts.sidebar.jamaah')
            @endif

            {{-- NOTIFIKASI --}}
            <a href="{{ route('notifications.index') }}"
               class="flex items-center justify-between gap-3 rounded-lg px-4 py-3 hover:bg-slate-800">

                <span>Notifikasi</span>

                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-xs font-semibold text-white">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif

            </a>

        </nav>

    </aside>

    {{-- KONTEN UTAMA --}}
    <main class="ml-64 min-h-screen">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-8">

            <div>
                <h2 class="font-semibold text-gray-800">
                    @yield('page-title', 'Dashboard')
                </h2>
            </div>

            <div class="flex items-center gap-4">

                <div class="text-right">
                    <p class="text-sm font-semibold">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs capitalize text-gray-500">
                        {{ auth()->user()->role?->display_name }}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="text-sm text-red-600 hover:text-red-800">
                        Logout
                    </button>
                </form>

            </div>

        </header>

        {{-- KONTEN HALAMAN --}}
        <section class="p-8">

            @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-100 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-lg bg-red-100 px-4 py-3 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </section>

    </main>

</div>

@stack('scripts')

</body>
</html>
