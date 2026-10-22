<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('masjid-mark.png') }}">

    <title>
        @yield('title', 'Dashboard') - Sistem Manajemen Masjid
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-800">

<div class="min-h-screen" x-data="{ sidebarOpen: false }">

    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        class="fixed inset-0 z-30 bg-slate-950/50 lg:hidden"
        @click="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false"
        aria-hidden="true"
    ></div>

    {{-- SIDEBAR --}}
    <aside
        class="sidebar-menu fixed inset-y-0 left-0 z-40 flex h-screen w-72 max-w-[85vw] flex-col overflow-hidden bg-slate-900 text-white transition-transform duration-200 ease-out lg:w-64 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        id="dashboard-sidebar"
        aria-label="Navigasi dashboard"
    >

        {{-- HEADER SIDEBAR --}}
        <div class="shrink-0 border-b border-slate-700 px-6 py-5">
            <div class="flex items-center gap-3">
                <x-application-logo class="h-11 w-11 shrink-0" />
                <div>
                    <h1 class="text-base font-bold">
                        Masjid Terpadu
                    </h1>
                    <p class="mt-1 text-xs text-slate-400">
                        Sistem Manajemen Masjid
                    </p>
                </div>
            </div>
        </div>

        {{-- MENU SIDEBAR --}}
        <nav class="sidebar-menu min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-contain p-4">

            <a href="{{ route('dashboard') }}"
               @class([
                   'flex items-center gap-3 rounded-lg px-4 py-3 hover:bg-slate-800',
                   'bg-slate-800 font-semibold text-white' => request()->routeIs('dashboard'),
                   'text-slate-200' => ! request()->routeIs('dashboard'),
               ])
               @if(request()->routeIs('dashboard')) aria-current="page" @endif>
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
    <main class="min-h-screen min-w-0 lg:ml-64">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-3 border-b border-gray-200 bg-white px-4 sm:px-8">

            <div class="flex min-w-0 items-center gap-3">
                <button
                    type="button"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-600 lg:hidden"
                    @click="sidebarOpen = !sidebarOpen"
                    :aria-expanded="sidebarOpen.toString()"
                    aria-controls="dashboard-sidebar"
                    aria-label="Buka atau tutup menu navigasi"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="min-w-0 truncate text-sm font-semibold text-gray-800 sm:text-base" title="@yield('page-title', 'Dashboard')">
                    @yield('page-title', 'Dashboard')
                </h2>
            </div>

            <div class="flex shrink-0 items-center gap-3 sm:gap-4">

                <div class="hidden text-right sm:block">
                    <p class="text-sm font-semibold">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs capitalize text-gray-500">
                        {{ auth()->user()->roleDisplayName() }}
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
        <section class="min-w-0 p-4 sm:p-6 lg:p-8">

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
