<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Dashboard') - Sistem Manajemen Masjid
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-800">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-slate-900 text-white fixed inset-y-0 left-0">

        <div class="px-6 py-5 border-b border-slate-700">
            <h1 class="text-lg font-bold">
                Masjid Terpadu
            </h1>

            <p class="text-xs text-slate-400 mt-1">
                Sistem Manajemen Masjid
            </p>
        </div>

        <nav class="p-4 space-y-1">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">
                <span>Dashboard</span>
            </a>

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
            class="flex items-center justify-between gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>
                    Notifikasi
                </span>

                @if(auth()->user()->unreadNotifications->count() > 0)

                    <span class="inline-flex items-center justify-center min-w-5 h-5 px-1 rounded-full bg-red-500 text-white text-xs font-semibold">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>

                @endif

            </a>

        </nav>

    </aside>


    {{-- CONTENT --}}
    <main class="ml-64 flex-1">

        {{-- TOPBAR --}}
        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-8">

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

                    <p class="text-xs text-gray-500 capitalize">
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


        {{-- PAGE --}}
        <section class="p-8">

            @if(session('success'))
                <div class="mb-6 bg-green-100 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 text-red-700 px-4 py-3 rounded-lg">
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