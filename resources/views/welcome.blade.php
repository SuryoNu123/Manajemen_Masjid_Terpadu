@extends('layouts.public')

@section('title', 'Beranda - Masjid Terpadu')

@section('content')

{{-- ========================================================= --}}
{{-- HERO --}}
{{-- ========================================================= --}}

<section class="relative overflow-hidden bg-slate-950 text-white">

    {{-- Decorative Background --}}
    <div class="absolute inset-0">

        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-blue-600/20 blur-3xl"></div>

        <div class="absolute top-1/2 -left-24 w-80 h-80 rounded-full bg-indigo-500/10 blur-3xl"></div>

        <div class="absolute bottom-0 right-1/3 w-72 h-72 rounded-full bg-cyan-500/10 blur-3xl"></div>

    </div>


    <div class="relative max-w-7xl mx-auto px-6 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">


            {{-- HERO TEXT --}}

            <div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-sm text-slate-300 mb-6">

                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>

                    Sistem Manajemen Masjid Terpadu

                </div>


                <h1 class="text-4xl md:text-5xl xl:text-6xl font-bold leading-tight tracking-tight">

                    Kelola Masjid

                    <span class="text-blue-400">
                        Lebih Mudah,
                    </span>

                    <br>

                    Layani Jamaah
                    <span class="text-blue-400">
                        Lebih Baik.
                    </span>

                </h1>


                <p class="mt-6 text-lg md:text-xl text-slate-300 leading-relaxed max-w-xl">

                    Satu platform untuk mengelola kegiatan,
                    keuangan, donasi, zakat, jamaah,
                    dan berbagai layanan masjid secara terintegrasi.

                </p>


                {{-- CTA --}}

                <div class="flex flex-wrap gap-4 mt-8">

                    <a
                        href="{{ route('donasi.create') }}"
                        class="inline-flex items-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 rounded-xl font-semibold transition"
                    >

                        Donasi Sekarang

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"
                            />
                        </svg>

                    </a>


                    <a
                        href="{{ route('zakat.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3.5 bg-white text-slate-900 hover:bg-slate-100 rounded-xl font-semibold transition"
                    >

                        Kalkulator Zakat

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 7h6m-6 4h6m-6 4h3m7-10v12a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2h10l3 3z"
                            />
                        </svg>

                    </a>

                </div>


                {{-- SMALL INFO --}}

                <div class="flex flex-wrap gap-8 mt-10 pt-8 border-t border-white/10">

                    <div>

                        <p class="text-2xl font-bold">
                            5
                        </p>

                        <p class="text-sm text-slate-400 mt-1">
                            Waktu Sholat
                        </p>

                    </div>


                    <div>

                        <p class="text-2xl font-bold">
                            7
                        </p>

                        <p class="text-sm text-slate-400 mt-1">
                            Modul Sistem
                        </p>

                    </div>


                    <div>

                        <p class="text-2xl font-bold">
                            24/7
                        </p>

                        <p class="text-sm text-slate-400 mt-1">
                            Akses Informasi
                        </p>

                    </div>

                </div>

            </div>


            {{-- HERO PRAYER CARD --}}

            <div class="lg:pl-8">

                <div class="relative">

                    {{-- Main Card --}}

                    <div class="bg-white rounded-3xl p-6 md:p-8 text-slate-900 shadow-2xl shadow-black/20">


                        {{-- Card Header --}}

                        <div class="flex items-center justify-between mb-8">

                            <div>

                                <p class="text-sm text-slate-500">
                                    Jadwal Sholat Hari Ini
                                </p>

                                <h2 class="text-xl font-bold mt-1">
                                    Masjid Terpadu
                                </h2>

                            </div>


                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-blue-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 3v18m9-9H3"
                                    />
                                </svg>

                            </div>

                        </div>


                        {{-- Next Prayer --}}

                        <div class="bg-slate-950 text-white rounded-2xl p-6 mb-6">

                            <div class="flex items-center justify-between">

                                <div>
                                    <p class="text-sm text-slate-400">
                                        Waktu Berikutnya
                                    </p>

                                    <p class="text-3xl font-bold mt-2">
                                        {{ $nextPrayer['name'] ?? 'Belum tersedia' }}
                                    </p>
                                </div>

                                <div class="text-right">

                                    <p class="text-4xl font-bold">
                                        {{ $nextPrayer['time'] ?? '--:--' }}
                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">
                                        Jadwal sholat hari ini
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Prayer List --}}

                        <div class="space-y-3">

                            @foreach($schedules as $schedule)

                                <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-50">

                                    <span class="font-medium">
                                        {{ $prayerNames[$schedule->prayer_name] ?? ucfirst($schedule->prayer_name) }}
                                    </span>

                                    <span class="font-semibold">
                                        {{ \Carbon\Carbon::parse($schedule->prayer_time)->format('H:i') }}
                                    </span>

                                </div>

                            @endforeach

                        </div>


                        <div class="mt-6 pt-5 border-t">

                            <p class="text-xs text-slate-500">
                                Jadwal otomatis berdasarkan lokasi masjid
                                akan terhubung pada modul Jadwal Sholat.
                            </p>

                        </div>

                    </div>


                    {{-- Floating Badge --}}

                    <div class="absolute -bottom-5 -left-5 bg-white rounded-2xl shadow-xl px-5 py-4 hidden md:block">

                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-emerald-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                            </div>

                            <div>

                                <p class="text-xs text-slate-500">
                                    Sistem
                                </p>

                                <p class="font-semibold text-sm">
                                    Terintegrasi
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- LAYANAN UTAMA --}}
{{-- ========================================================= --}}

<section class="py-20 bg-white">

    <div class="max-w-7xl mx-auto px-6">


        {{-- Section Header --}}

        <div class="max-w-2xl mb-12">

            <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">
                Layanan Jamaah
            </span>

            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-3">
                Semua kebutuhan masjid
                dalam satu tempat.
            </h2>

            <p class="text-slate-500 mt-4 text-lg">
                Akses layanan utama masjid dengan cepat
                dan mudah melalui sistem terpadu.
            </p>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


            {{-- DONASI --}}

            <a
                href="{{ route('donasi.create') }}"
                class="group bg-slate-50 rounded-2xl border border-slate-200 p-7 hover:bg-blue-600 hover:border-blue-600 transition duration-300"
            >

                <div class="w-14 h-14 rounded-2xl bg-blue-100 group-hover:bg-white/15 flex items-center justify-center">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7 text-blue-600 group-hover:text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3m0-14v2m0 14v2M5 12H3m18 0h-2"
                        />
                    </svg>

                </div>


                <h3 class="text-xl font-bold mt-6 group-hover:text-white">
                    Donasi
                </h3>

                <p class="text-slate-500 group-hover:text-blue-100 mt-3 leading-relaxed">
                    Salurkan infaq, sedekah, zakat,
                    dan qurban untuk mendukung kegiatan masjid.
                </p>


                <div class="mt-6 text-sm font-semibold text-blue-600 group-hover:text-white">

                    Mulai Donasi
                    →

                </div>

            </a>


            {{-- ZAKAT --}}

            <a
                href="{{ route('zakat.index') }}"
                class="group bg-slate-50 rounded-2xl border border-slate-200 p-7 hover:bg-emerald-600 hover:border-emerald-600 transition duration-300"
            >

                <div class="w-14 h-14 rounded-2xl bg-emerald-100 group-hover:bg-white/15 flex items-center justify-center">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7 text-emerald-600 group-hover:text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 3l7 4v6c0 4.5-3 7-7 8-4-1-7-3.5-7-8V7l7-4z"
                        />
                    </svg>

                </div>


                <h3 class="text-xl font-bold mt-6 group-hover:text-white">
                    Kalkulator Zakat
                </h3>

                <p class="text-slate-500 group-hover:text-emerald-100 mt-3 leading-relaxed">
                    Hitung estimasi zakat fitrah
                    dan zakat maal dengan mudah.
                </p>


                <div class="mt-6 text-sm font-semibold text-emerald-600 group-hover:text-white">

                    Hitung Zakat
                    →

                </div>

            </a>


            {{-- TRANSPARANSI --}}

            <a
                href="{{ route('transparansi.index') }}"
                class="group bg-slate-50 rounded-2xl border border-slate-200 p-7 hover:bg-amber-500 hover:border-amber-500 transition duration-300"
            >

                <div class="w-14 h-14 rounded-2xl bg-amber-100 group-hover:bg-white/15 flex items-center justify-center">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7 text-amber-600 group-hover:text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 19V5m0 14h16M8 16v-5m4 5V8m4 8V4"
                        />
                    </svg>

                </div>


                <h3 class="text-xl font-bold mt-6 group-hover:text-white">
                    Transparansi
                </h3>

                <p class="text-slate-500 group-hover:text-amber-50 mt-3 leading-relaxed">
                    Lihat laporan keuangan masjid
                    yang telah dipublikasikan.
                </p>


                <div class="mt-6 text-sm font-semibold text-amber-600 group-hover:text-white">

                    Lihat Laporan
                    →

                </div>

            </a>

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- JADWAL SHOLAT --}}
{{-- ========================================================= --}}

<section class="py-20 bg-slate-50">

    <div class="max-w-7xl mx-auto px-6">


        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">

            <div>

                <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">
                    Waktu Ibadah
                </span>

                <h2 class="text-3xl md:text-4xl font-bold mt-3">
                    Jadwal Sholat Hari Ini
                </h2>

                <p class="text-slate-500 mt-3">
                    Informasi jadwal sholat masjid untuk hari ini.
                </p>

            </div>


            <a
                href="#"
                class="text-sm font-semibold text-blue-600"
            >
                Lihat Jadwal Lengkap →
            </a>

        </div>


        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">

            @foreach([
                ['name' => 'Subuh', 'time' => '04:30'],
                ['name' => 'Dzuhur', 'time' => '12:00'],
                ['name' => 'Ashar', 'time' => '15:15'],
                ['name' => 'Maghrib', 'time' => '18:00'],
                ['name' => 'Isya', 'time' => '19:15'],
            ] as $prayer)

                <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition">

                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-5">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-blue-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6l4 2"
                            />

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                                stroke-width="2"
                            />
                        </svg>

                    </div>

                    <p class="text-sm text-slate-500">
                        {{ $prayer['name'] }}
                    </p>

                    <p class="text-2xl font-bold mt-2">
                        {{ $prayer['time'] }}
                    </p>

                </div>

            @endforeach

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- KEGIATAN MASJID --}}
{{-- ========================================================= --}}

<section class="py-20 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">

            <div>

                <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">
                    Agenda
                </span>

                <h2 class="text-3xl md:text-4xl font-bold mt-3">
                    Kegiatan Masjid
                </h2>

                <p class="text-slate-500 mt-3">
                    Ikuti berbagai program dan kegiatan masjid.
                </p>

            </div>

            <a href="{{ route('activities.public') }}"
               class="text-sm font-semibold text-blue-600">
                Lihat Semua →
            </a>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            @forelse($activities as $activity)

                <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white hover:shadow-xl transition">

                    <div class="h-44 bg-slate-900 flex items-end p-5">

                        <div>

                            <span class="inline-block px-3 py-1 rounded-full bg-white/10 text-white text-xs font-semibold">
                                {{ ucfirst(str_replace('_', ' ', $activity->type)) }}
                            </span>

                            <h3 class="text-lg text-white font-bold mt-3">
                                {{ $activity->title }}
                            </h3>

                        </div>

                    </div>


                    <div class="p-5">

                        @if($activity->description)

                            <p class="text-sm text-slate-500 leading-relaxed">
                                {{ Str::limit($activity->description, 100) }}
                            </p>

                        @endif


                        <div class="space-y-2 mt-5 text-sm text-slate-500">

                            <div>
                                📅 {{ $activity->activity_date?->format('d M Y') }}
                            </div>

                            <div>
                                🕐 {{ $activity->start_time }}
                            </div>

                            <div>
                                📍 {{ $activity->location ?? 'Masjid' }}
                            </div>

                        </div>


                        @if($activity->speaker)

                            <div class="mt-4 text-sm font-medium text-slate-700">
                                {{ $activity->speaker }}
                            </div>

                        @endif

                    </div>

                </article>

            @empty

                <div class="lg:col-span-4 text-center py-12 text-slate-500">

                    Belum ada kegiatan yang dijadwalkan.

                </div>

            @endforelse

        </div>

    </div>

</section>

{{-- ========================================================= --}}
{{-- TRANSPARANSI --}}
{{-- ========================================================= --}}

<section class="py-20 bg-slate-950 text-white">

    <div class="max-w-7xl mx-auto px-6">


        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">


            {{-- TEXT --}}

            <div>

                <span class="text-sm font-semibold text-blue-400 uppercase tracking-wider">
                    Transparansi
                </span>

                <h2 class="text-3xl md:text-4xl font-bold mt-3">
                    Keuangan Masjid
                    Terbuka untuk Jamaah
                </h2>

                <p class="text-slate-400 mt-5 leading-relaxed text-lg">
                    Laporan keuangan yang telah melalui proses
                    review dan publikasi dapat diakses jamaah
                    secara terbuka.
                </p>


                <a
                    href="{{ route('transparansi.index') }}"
                    class="inline-flex items-center gap-2 mt-8 px-6 py-3 bg-white text-slate-900 rounded-xl font-semibold hover:bg-slate-100 transition"
                >

                    Lihat Transparansi Lengkap

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"
                        />
                    </svg>

                </a>

            </div>


            {{-- STATS --}}

            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-4">


                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">

                    <p class="text-slate-400 text-sm">
                        Total Pemasukan
                    </p>

                    <p class="text-3xl font-bold mt-2">
                        Rp 0
                    </p>

                    <p class="text-xs text-slate-500 mt-2">
                        Belum ada laporan publik
                    </p>

                </div>


                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">

                    <p class="text-slate-400 text-sm">
                        Total Pengeluaran
                    </p>

                    <p class="text-3xl font-bold mt-2">
                        Rp 0
                    </p>

                    <p class="text-xs text-slate-500 mt-2">
                        Menunggu data publik
                    </p>

                </div>


                <div class="bg-blue-600 rounded-2xl p-6">

                    <p class="text-blue-100 text-sm">
                        Saldo
                    </p>

                    <p class="text-3xl font-bold mt-2">
                        Rp 0
                    </p>

                    <p class="text-xs text-blue-100 mt-2">
                        Akan diperbarui otomatis
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- CTA DONASI --}}
{{-- ========================================================= --}}

<section class="py-20 bg-white">

    <div class="max-w-4xl mx-auto px-6 text-center">


        <div class="w-16 h-16 mx-auto rounded-2xl bg-blue-100 flex items-center justify-center">

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-8 h-8 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3m0-14v2m0 14v2M5 12H3m18 0h-2"
                />
            </svg>

        </div>


        <h2 class="text-3xl md:text-4xl font-bold mt-7">
            Mari Berkontribusi
            untuk Masjid
        </h2>


        <p class="text-slate-500 text-lg mt-4 max-w-2xl mx-auto leading-relaxed">
            Dukung kegiatan ibadah, pendidikan,
            sosial, dan berbagai program masjid
            melalui kontribusi Anda.
        </p>


        <div class="flex flex-wrap justify-center gap-4 mt-8">

            <a
                href="{{ route('donasi.create') }}"
                class="px-7 py-3.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition"
            >
                Donasi Sekarang
            </a>


            <a
                href="{{ route('zakat.index') }}"
                class="px-7 py-3.5 bg-slate-100 text-slate-900 rounded-xl font-semibold hover:bg-slate-200 transition"
            >
                Hitung Zakat
            </a>

        </div>

    </div>

</section>

@endsection