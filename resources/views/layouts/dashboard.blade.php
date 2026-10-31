<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('masjid-mark.png') }}?v=2">

    <title>
        @yield('title', 'Dashboard') - Sistem Manajemen Masjid
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

<div class="min-h-screen" x-data="{ sidebarOpen: false }">

    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        class="fixed inset-0 z-30 bg-slate-950/45 backdrop-blur-[1px] lg:hidden"
        @click="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false"
        aria-hidden="true"
    ></div>

    {{-- SIDEBAR --}}
    <aside
        class="sidebar-menu fixed inset-y-0 left-0 z-40 flex h-dvh w-72 max-w-[85vw] flex-col overflow-hidden bg-slate-900 text-white transition-transform duration-200 ease-out lg:w-64 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        id="dashboard-sidebar"
        aria-label="Navigasi dashboard"
    >

        {{-- HEADER SIDEBAR --}}
        <div class="shrink-0 border-b border-slate-700 px-5 py-4">
            <div class="flex items-center gap-3">
                <x-application-logo class="h-11 w-11 shrink-0" />
                <div>
                    <h1 class="text-sm font-semibold tracking-wide">
                        Masjid Terpadu
                    </h1>
                    <p class="mt-1 text-xs text-slate-300">
                        Sistem Manajemen Masjid
                    </p>
                </div>
            </div>
        </div>

        {{-- MENU SIDEBAR --}}
        <nav class="sidebar-menu min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-contain p-3">

            <a href="{{ route('dashboard') }}"
               @class([
                   'flex min-h-10 items-center gap-3 rounded-md px-3 py-2.5 text-sm transition hover:bg-slate-800',
                   'bg-slate-800 font-semibold text-white' => request()->routeIs('dashboard'),
                   'text-slate-300' => ! request()->routeIs('dashboard'),
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
               @class([
                   'flex min-h-10 items-center justify-between gap-3 rounded-md px-3 py-2.5 text-sm transition',
                   'bg-slate-800 font-semibold text-white' => request()->routeIs('notifications.*'),
                   'text-slate-300 hover:bg-slate-800' => ! request()->routeIs('notifications.*'),
               ])
               @if(request()->routeIs('notifications.*')) aria-current="page" @endif>

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
        <header class="sticky top-0 z-20 flex min-h-16 items-center justify-between gap-3 border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-8">

            <div class="flex min-w-0 items-center gap-3">
                <button
                    type="button"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-md border border-slate-200 text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-600 lg:hidden"
                    @click="sidebarOpen = !sidebarOpen"
                    :aria-expanded="sidebarOpen.toString()"
                    aria-controls="dashboard-sidebar"
                    aria-label="Buka atau tutup menu navigasi"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="min-w-0 truncate text-sm font-semibold text-slate-800 sm:text-base" title="@yield('page-title', 'Dashboard')">
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
                        class="rounded-md px-2 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        Logout
                    </button>
                </form>

            </div>

        </header>

        {{-- KONTEN HALAMAN --}}
        <section class="mx-auto min-w-0 max-w-[1600px] p-4 sm:p-6 lg:p-8">

            @if(session('success'))
                <div role="status" class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div role="alert" class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
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
