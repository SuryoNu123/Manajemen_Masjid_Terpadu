<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="300"> {{-- Refresh otomatis tiap 5 menit untuk update jadwal/pengumuman --}}
        <link rel="icon" type="image/png" href="{{ asset('masjid-mark.png') }}?v=2">
    <title>Digital Signage TV - Masjid Terpadu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes ticker {
            0% { transform: translate3d(100%, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
        }
        .ticker-wrap {
            overflow: hidden;
            white-space: nowrap;
        }
        .ticker-move {
            display: inline-block;
            animation: ticker 35s linear infinite;
        }
        .ticker-move:hover {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="flex min-h-screen min-w-0 flex-col justify-between overflow-x-hidden bg-slate-950 text-white select-none lg:overflow-hidden">

    {{-- HEADER DIGITAL SIGNAGE --}}
    <header class="flex flex-col gap-4 border-b border-slate-800 bg-slate-900/90 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8 lg:py-5">
        <div class="flex min-w-0 items-center gap-3 sm:gap-4">
            <x-application-logo class="h-11 w-11 shrink-0 rounded-2xl shadow-lg shadow-blue-500/20 sm:h-14 sm:w-14" />
            <div>
                <h1 class="text-xl font-extrabold tracking-tight sm:text-2xl lg:text-3xl">MASJID TERPADU</h1>
                <p class="text-xs text-slate-400 sm:text-sm">Sistem Informasi Operasional & Kemakmuran Jamaah</p>
            </div>
        </div>

        {{-- JAM & TANGGAL BESAR --}}
        <div class="min-w-0 text-left lg:text-right">
            <div id="liveClock" class="font-mono text-3xl font-bold tracking-wide text-white sm:text-4xl md:text-5xl">
                --:--:--
            </div>
            <div id="liveDate" class="mt-1 break-words text-xs font-medium text-blue-400 sm:text-sm md:text-base">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT AREA (SLIDES / DUAL PANEL) --}}
    <main class="grid flex-1 min-w-0 grid-cols-1 items-stretch gap-4 p-4 sm:gap-8 sm:p-6 lg:grid-cols-12 lg:p-8">

        {{-- LEFT PANEL: JADWAL SHOLAT 5 WAKTU (5 COLS) --}}
        <div class="flex min-w-0 flex-col justify-between rounded-3xl border border-slate-800 bg-slate-900/80 p-4 shadow-2xl sm:p-6 lg:col-span-5">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Waktu Sholat Hari Ini</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        GPS Terkalibrasi
                    </span>
                </div>

                {{-- NEXT PRAYER HIGHLIGHT BANNER --}}
                @if($nextSchedule)
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-5 mb-5 shadow-xl text-white">
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-100">Menuju Waktu Sholat</p>
                        <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                            <span class="text-2xl font-extrabold sm:text-3xl">
                                {{ $prayerNames[$nextSchedule->prayer_name] ?? ucfirst($nextSchedule->prayer_name) }}
                            </span>
                            <span class="whitespace-nowrap font-mono text-2xl font-bold sm:text-3xl">
                                {{ \Carbon\Carbon::parse($nextSchedule->prayer_time)->format('H:i') }} WIB
                            </span>
                        </div>
                    </div>
                @endif

                {{-- 5 PRAYERS LIST --}}
                <div class="space-y-3">
                    @foreach($schedules as $sched)
                        @php
                            $isNext = $nextSchedule && $nextSchedule->prayer_name === $sched->prayer_name;
                        @endphp
                        <div class="flex items-center justify-between px-5 py-4 rounded-xl {{ $isNext ? 'bg-blue-600/30 border border-blue-500/50 text-white' : 'bg-slate-800/60 text-slate-300' }}">
                            <div class="flex items-center">
                                <span class="text-xl font-bold tracking-wide">
                                    {{ $prayerNames[$sched->prayer_name] ?? ucfirst($sched->prayer_name) }}
                                </span>
                            </div>
                            <span class="text-2xl font-mono font-extrabold tracking-wider {{ $isNext ? 'text-blue-300' : 'text-white' }}">
                                {{ \Carbon\Carbon::parse($sched->prayer_time)->format('H:i') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-800/80 text-center">
                <p class="text-xs text-slate-500">Kalkulasi hisab Kementerian Agama RI • Wilayah Koordinat Masjid</p>
            </div>
        </div>

        {{-- RIGHT PANEL: ROTATING CAROUSEL (KEGIATAN & TRANSPARANSI KEUANGAN) (7 COLS) --}}
        <div class="relative flex min-w-0 flex-col justify-between rounded-3xl border border-slate-800 bg-slate-900/80 p-4 shadow-2xl sm:p-6 lg:col-span-7">

            {{-- SLIDE 1: AGENDA KAJIAN & KEGIATAN --}}
            <div id="slideKegiatan" class="transition-opacity duration-700">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-6">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">Tanggal: </span>
                        <h2 class="text-xl font-bold text-white">Agenda Ibadah & Kajian Rutin</h2>
                    </div>
                    <span class="text-xs text-slate-400">Pekan Ini</span>
                </div>

                <div class="space-y-4">
                    @forelse($activities as $act)
                        <div class="rounded-2xl border border-slate-800 bg-slate-800/40 p-5 hover:bg-slate-800/60 transition">
                            <div class="flex items-center justify-between text-xs text-blue-400 mb-2">
                                <span class="font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-blue-500/10">
                                    {{ str_replace('_', ' ', $act->type) }}
                                </span>
                                <span class="font-semibold text-slate-300">
                                    {{ $act->activity_date?->format('d M Y') }} • {{ $act->start_time }} WIB
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-white">{{ $act->title }}</h3>
                            @if($act->speaker)
                                <p class="text-sm text-slate-300 mt-1">Pemateri/Imam: <strong>{{ $act->speaker }}</strong></p>
                            @endif
                        </div>
                    @empty
                        <div class="py-20 text-center text-slate-500">
                            Belum ada jadwal kajian khusus dalam waktu dekat.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- SLIDE 2: TRANSPARANSI KEUANGAN KAS --}}
            <div id="slideKeuangan" class="hidden transition-opacity duration-700">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-6">
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-bold text-white">Transparansi Laporan Kas Masjid</h2>
                    </div>
                    <span class="text-xs text-emerald-400 font-semibold">Akuntabel & Terverifikasi</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-6">
                        <p class="text-xs font-semibold text-slate-400 uppercase">Pemasukan Kas</p>
                        <p class="text-2xl md:text-3xl font-extrabold text-emerald-400 mt-2 font-mono">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-slate-500 mt-2">Infaq, Sedekah & Zakat</p>
                    </div>

                    <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-6">
                        <p class="text-xs font-semibold text-slate-400 uppercase">Pengeluaran Kas</p>
                        <p class="text-2xl md:text-3xl font-extrabold text-rose-400 mt-2 font-mono">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-slate-500 mt-2">Operasional & Sosial</p>
                    </div>

                    <div class="bg-blue-600 rounded-2xl p-6 shadow-xl shadow-blue-600/30">
                        <p class="text-xs font-semibold text-blue-100 uppercase">Saldo Kas Saat Ini</p>
                        <p class="text-2xl md:text-3xl font-extrabold text-white mt-2 font-mono">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-blue-200 mt-2">Siap Disalurkan</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-800/40 p-6 text-sm text-slate-300 leading-relaxed">
                    <p class="font-bold text-white mb-1">Jazakumullahu Khairan Katsiran</p>
                    <p>Terima kasih atas segala infaq, sedekah, dan donasi dari segenap jamaah. Salurkan infaq digital melalui scan QRIS resmi masjid yang tersedia di pintu masuk atau melalui portal website.</p>
                </div>
            </div>

            {{-- ROTATION INDICATOR --}}
            <div class="mt-6 flex items-center justify-between text-xs text-slate-500 border-t border-slate-800 pt-4">
                <span>Layar TV Signage Informasi Masjid</span>
                <div class="flex items-center gap-2">
                    <span id="dot1" class="w-2.5 h-2.5 rounded-full bg-blue-500 transition"></span>
                    <span id="dot2" class="w-2.5 h-2.5 rounded-full bg-slate-700 transition"></span>
                </div>
            </div>
        </div>
    </main>

    {{-- RUNNING TEXT TICKER (FOOTER MARQUEE) --}}
    <footer class="bg-blue-900/90 border-t border-blue-700 py-3.5 px-6 flex items-center gap-4">
        <div class="shrink-0 flex items-center gap-2 bg-red-600 px-3 py-1 rounded-lg text-xs font-bold text-white tracking-wide uppercase">
            <span>INFORMASI</span>
        </div>

        <div class="ticker-wrap flex-1">
            <div class="ticker-move text-sm font-semibold tracking-wide text-white">
                @foreach($runningTexts as $text)
                    <span class="mx-8">{{ $text }}</span>
                    <span class="text-blue-300">•</span>
                @endforeach
            </div>
        </div>
    </footer>

    {{-- SCRIPT: LIVE CLOCK & AUTO SLIDE ROTATION --}}
    <script>
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            const clockEl = document.getElementById('liveClock');
            if (clockEl) clockEl.textContent = `${h}:${m}:${s}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Slide rotator between Kegiatan and Keuangan every 12 seconds
        let currentSlide = 0;
        const slideKegiatan = document.getElementById('slideKegiatan');
        const slideKeuangan = document.getElementById('slideKeuangan');
        const dot1 = document.getElementById('dot1');
        const dot2 = document.getElementById('dot2');

        setInterval(() => {
            if (currentSlide === 0) {
                slideKegiatan.classList.add('hidden');
                slideKeuangan.classList.remove('hidden');
                dot1.classList.replace('bg-blue-500', 'bg-slate-700');
                dot2.classList.replace('bg-slate-700', 'bg-emerald-500');
                currentSlide = 1;
            } else {
                slideKeuangan.classList.add('hidden');
                slideKegiatan.classList.remove('hidden');
                dot2.classList.replace('bg-emerald-500', 'bg-slate-700');
                dot1.classList.replace('bg-slate-700', 'bg-blue-500');
                currentSlide = 0;
            }
        }, 12000);
    </script>
</body>
</html>

