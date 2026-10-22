@extends('layouts.public')

@section('title', 'Beranda - Masjid Terpadu')

@section('content')

@if(isset($emergencyAnnouncements) && $emergencyAnnouncements->isNotEmpty())
    <div class="bg-red-600 text-white px-6 py-3.5">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white text-red-600">
                    DARURAT
                </span>
                <span class="text-sm font-semibold">
                    {{ $emergencyAnnouncements->first()->title }}:
                </span>
                <span class="text-sm text-red-100 hidden sm:inline">
                    {{ Str::limit($emergencyAnnouncements->first()->content, 120) }}
                </span>
            </div>
            <a href="{{ route('announcements.show', $emergencyAnnouncements->first()) }}"
               class="text-xs font-bold underline hover:text-red-200">
                Lihat Selengkapnya →
            </a>
        </div>
    </div>
@endif

{{-- ========================================================= --}}
{{-- HERO --}}
{{-- ========================================================= --}}

<section class="overflow-hidden bg-slate-900 text-white">

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8 lg:py-14">

        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-[1.05fr_.95fr] lg:gap-12">


            {{-- HERO TEXT --}}

            <div>

                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm text-slate-300">

                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>

                    Sistem Manajemen Masjid Terpadu

                </div>


                <h1 class="max-w-2xl text-3xl font-semibold leading-tight tracking-tight sm:text-4xl lg:text-5xl">

                    Kelola Masjid

                    <span class="text-blue-400">
                        Lebih Mudah,
                    </span>

                    <span class="block">Layani Jamaah
                    <span class="text-blue-400">
                        Lebih Baik.
                    </span>
                    </span>

                </h1>


                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-300 sm:text-lg">

                    Satu platform untuk mengelola kegiatan,
                    keuangan, donasi, zakat, jamaah,
                    dan berbagai layanan masjid secara terintegrasi.

                </p>


                {{-- CTA --}}

                <div class="mt-7 flex flex-wrap gap-3">

                    <a
                        href="{{ route('donasi.create') }}"
                        class="inline-flex min-h-11 items-center gap-2 rounded-lg bg-blue-600 px-5 py-3 font-semibold transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-slate-900"
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
                        class="inline-flex min-h-11 items-center gap-2 rounded-lg border border-white/25 bg-white/5 px-5 py-3 font-semibold text-white transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-blue-300"
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


            </div>


            {{-- HERO PRAYER CARD --}}

            <div class="min-w-0 lg:pl-4">

                <div class="relative">

                    {{-- Main Card --}}

                    <div class="rounded-xl border border-slate-200 bg-white p-5 text-slate-900 shadow-lg shadow-black/10 sm:p-7">


                        {{-- Card Header --}}

                        <div class="mb-5 flex items-center justify-between">

                            <div>

                                <p class="text-sm text-slate-500">
                                    Jadwal Sholat Hari Ini
                                </p>

                                <h2 class="text-xl font-bold mt-1">
                                    Masjid Terpadu
                                </h2>

                            </div>


                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-600">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
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

                        <div class="mb-5 rounded-xl bg-slate-950 p-4 text-white sm:p-5">

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                <div>
                                    <p class="text-sm text-slate-400">
                                        Waktu Berikutnya
                                    </p>

                                    <p id="next-prayer-name" class="mt-2 text-2xl font-bold sm:text-3xl">
                                        Memuat jadwal…
                                    </p>
                                </div>

                                <div class="text-right">

                                    <p id="next-prayer-time" class="text-3xl font-bold sm:text-4xl">
                                        --:--
                                    </p>

                                    <p id="next-prayer-date" class="text-xs text-slate-400 mt-1">
                                        Jadwal sholat
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        <span id="device-clock">--:--:--</span>
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Prayer List --}}

                        <div id="prayer-schedule-list" class="space-y-2">

                            @foreach($schedules as $schedule)

                                <div data-prayer-row="{{ $schedule->prayer_name }}" class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-2.5">

                                    <span class="font-medium">
                                        {{ $prayerNames[$schedule->prayer_name] ?? ucfirst($schedule->prayer_name) }}
                                    </span>

                                    <span class="font-semibold">
                                        {{ \Carbon\Carbon::parse($schedule->prayer_time)->format('H:i') }}
                                    </span>

                                </div>

                            @endforeach

                            <p id="prayer-schedule-empty" class="text-sm text-slate-500" @if($schedules->isNotEmpty()) hidden @endif>
                                Jadwal sholat belum tersedia saat ini.
                            </p>

                        </div>


                        <div class="mt-5 border-t pt-4">

                            <p class="text-xs text-slate-500">
                                Jadwal berdasarkan lokasi masjid. Jam mengikuti waktu lokal perangkat Anda.
                            </p>

                        </div>

                    </div>


                    {{-- Floating Badge --}}

                    <div class="absolute -bottom-5 -left-5 bg-white rounded-2xl shadow-xl px-5 py-4 hidden md:block">

                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">

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

            <span class="text-sm font-semibold text-blue-700 uppercase tracking-wider">
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
                        class="w-7 h-7 text-blue-700 group-hover:text-white"
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


                <div class="mt-6 text-sm font-semibold text-blue-700 group-hover:text-white">

                    Mulai Donasi
                    →

                </div>

            </a>


            {{-- ZAKAT --}}

            <a
                href="{{ route('zakat.index') }}"
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
                            d="M12 3l7 4v6c0 4.5-3 7-7 8-4-1-7-3.5-7-8V7l7-4z"
                        />
                    </svg>

                </div>


                <h3 class="text-xl font-bold mt-6 group-hover:text-white">
                    Kalkulator Zakat
                </h3>

                <p class="text-slate-500 group-hover:text-blue-100 mt-3 leading-relaxed">
                    Hitung estimasi zakat fitrah
                    dan zakat maal dengan mudah.
                </p>


                <div class="mt-6 text-sm font-semibold text-blue-600 group-hover:text-white">

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

                <span class="text-sm font-semibold text-blue-700 uppercase tracking-wider">
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
                href="#prayer-schedule-cards"
                class="text-sm font-semibold text-blue-700"
            >
                Lihat Jadwal Lengkap →
            </a>

        </div>


        <div id="prayer-schedule-cards" class="grid grid-cols-2 md:grid-cols-5 gap-4">

            @foreach($schedules as $schedule)

                <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition">

                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-5">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-blue-700"
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
                        {{ $prayerNames[$schedule->prayer_name] ?? ucfirst($schedule->prayer_name) }}
                    </p>

                    <p class="text-2xl font-bold mt-2">
                        {{ \Carbon\Carbon::parse($schedule->prayer_time)->format('H:i') }}
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

                <span class="text-sm font-semibold text-blue-700 uppercase tracking-wider">
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
               class="text-sm font-semibold text-blue-700">
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

                <span class="text-sm font-semibold text-blue-300 uppercase tracking-wider">
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
                    <p class="text-3xl font-bold mt-2 text-blue-400">
                        Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-2">
                        {{ $reportPeriodLabel ?? 'Data Terverifikasi' }}
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                    <p class="text-slate-400 text-sm">
                        Total Pengeluaran
                    </p>
                    <p class="text-3xl font-bold mt-2 text-rose-400">
                        Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-2">
                        Penyaluran & Operasional
                    </p>
                </div>

                <div class="bg-blue-600 rounded-2xl p-6 shadow-lg shadow-blue-500/20">
                    <p class="text-blue-100 text-sm font-medium">
                        Saldo Kas Masjid
                    </p>
                    <p class="text-3xl font-bold mt-2 text-white">
                        Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-blue-100 mt-2">
                        Tersedia untuk program umat
                    </p>
                </div>

            </div>

        </div>

    </div>

</section>

{{-- ========================================================= --}}
{{-- PENGUMUMAN MASJID --}}
{{-- ========================================================= --}}

<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">
            <div>
                <span class="text-sm font-semibold text-blue-700 uppercase tracking-wider">
                    Informasi
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-slate-900">
                    Pengumuman Masjid
                </h2>
                <p class="text-slate-500 mt-2">
                    Kabar dan pemberitahuan penting untuk jamaah Masjid Terpadu.
                </p>
            </div>

            <a href="{{ route('announcements.public') }}" class="text-sm font-semibold text-blue-700 hover:underline">
                Lihat Semua Pengumuman →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($latestAnnouncements ?? [] as $announcement)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-lg transition">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            @if($announcement->type === 'emergency')
                                <span class="rounded-full bg-red-100 text-red-700 px-2.5 py-0.5 text-xs font-semibold">
                                    Darurat
                                </span>
                            @else
                                <span class="rounded-full bg-blue-100 text-blue-800 px-2.5 py-0.5 text-xs font-semibold">
                                    Pengumuman
                                </span>
                            @endif

                            <span class="text-xs text-slate-400">
                                {{ $announcement->published_at?->format('d M Y') }}
                            </span>
                        </div>

                        <h3 class="font-bold text-lg text-slate-900 hover:text-blue-700">
                            <a href="{{ route('announcements.show', $announcement) }}">
                                {{ $announcement->title }}
                            </a>
                        </h3>

                        <p class="text-sm text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                            {{ Str::limit($announcement->content, 130) }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-400">
                            {{ $announcement->published_at?->format('H:i') }} WIB
                        </span>
                        <a href="{{ route('announcements.show', $announcement) }}"
                           class="text-xs font-semibold text-blue-700 hover:underline">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400 bg-white rounded-2xl border border-slate-200">
                    Belum ada pengumuman terbaru.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ========================================================= --}}
{{-- ARTIKEL DAKWAH --}}
{{-- ========================================================= --}}

<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">
            <div>
                <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">
                    Dakwah & Berita
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-slate-900">
                    Artikel & Kajian Dakwah
                </h2>
                <p class="text-slate-500 mt-2">
                    Tingkatkan wawasan keislaman melalui artikel dakwah dan risalah Jumat.
                </p>
            </div>

            <a href="{{ route('dakwah.public') }}" class="text-sm font-semibold text-blue-600 hover:underline">
                Lihat Semua Artikel →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($latestArticles ?? [] as $article)
                <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden flex flex-col justify-between hover:shadow-lg transition">
                    <div>
                        @if($article->cover_image)
                            <img src="{{ asset('storage/' . $article->cover_image) }}"
                                 alt="{{ $article->title }}"
                                 class="h-44 w-full object-cover">
                        @else
                            <div class="h-44 bg-slate-900 flex items-center justify-center text-4xl text-white">
                                📚
                            </div>
                        @endif

                        <div class="p-6">
                            <span class="text-xs text-slate-400">
                                {{ $article->published_at?->format('d M Y') }}
                            </span>
                            <h3 class="font-bold text-lg text-slate-900 mt-1 hover:text-blue-600">
                                <a href="{{ route('dakwah.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-slate-600 mt-2 line-clamp-2">
                                {{ $article->excerpt ?: Str::limit(strip_tags($article->content), 100) }}
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-2">
                        <a href="{{ route('dakwah.show', $article->slug) }}"
                           class="text-xs font-semibold text-blue-600 hover:underline">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400 bg-slate-50 rounded-2xl border border-slate-200">
                    Belum ada artikel dakwah.
                </div>
            @endforelse
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
                class="w-8 h-8 text-blue-700"
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
                class="px-7 py-3.5 bg-blue-700 text-white rounded-xl font-semibold hover:bg-blue-800 transition"
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
