<div class="space-y-2 pt-1">

    <details class="group" @if(request()->routeIs('transaksi.*', 'laporan-keuangan.*', 'business-units.*', 'business-unit-items.*', 'business-unit-sales.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9h18M5 9V6l7-3 7 3v3m-14 0v10m4-10v10m6-10v10m4-10v10M3 21h18M2 19h20" /></svg>
            <span class="min-w-0 flex-1">Keuangan & Usaha</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('transaksi.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('transaksi.*'), 'text-slate-300' => ! request()->routeIs('transaksi.*')])>Transaksi Keuangan</a>
            <a href="{{ route('laporan-keuangan.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('laporan-keuangan.*'), 'text-slate-300' => ! request()->routeIs('laporan-keuangan.*')])>Laporan Keuangan</a>
            <a href="{{ route('business-units.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('business-units.*', 'business-unit-items.*', 'business-unit-sales.*'), 'text-slate-300' => ! request()->routeIs('business-units.*', 'business-unit-items.*', 'business-unit-sales.*')])>Unit Usaha</a>
        </div>
    </details>

    <details class="group" @if(request()->routeIs('jamaahs.*', 'jadwal-sholat.*', 'prayer-attendances.*', 'activities.*', 'officers.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" /></svg>
            <span class="min-w-0 flex-1">Ibadah & Jamaah</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('jamaahs.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('jamaahs.*'), 'text-slate-300' => ! request()->routeIs('jamaahs.*')])>Database Jamaah</a>
            <a href="{{ route('jadwal-sholat.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('jadwal-sholat.*'), 'text-slate-300' => ! request()->routeIs('jadwal-sholat.*')])>Jadwal Sholat</a>
            <a href="{{ route('prayer-attendances.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('prayer-attendances.*'), 'text-slate-300' => ! request()->routeIs('prayer-attendances.*')])>Presensi Sholat</a>
            <a href="{{ route('activities.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('activities.*'), 'text-slate-300' => ! request()->routeIs('activities.*')])>Kegiatan Ibadah & Kajian</a>
            <a href="{{ route('officers.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('officers.*'), 'text-slate-300' => ! request()->routeIs('officers.*')])>Petugas & Imam Sholat</a>
        </div>
    </details>

    <details class="group" @if(request()->routeIs('youth-members.*', 'youth-programs.*', 'santris.*', 'tpq-classes.*', 'santri-attendances.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14 2 9l10-5 10 5-10 5Zm0 0v6m-7-8v5c4.67 3.33 9.33 3.33 14 0v-5" /></svg>
            <span class="min-w-0 flex-1">Pendidikan (TPQ) & Remaja</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('youth-members.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('youth-members.*', 'youth-programs.*'), 'text-slate-300' => ! request()->routeIs('youth-members.*', 'youth-programs.*')])>Remaja Masjid</a>
            <a href="{{ route('santris.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('santris.*'), 'text-slate-300' => ! request()->routeIs('santris.*')])>Santri TPQ/TPA</a>
            <a href="{{ route('tpq-classes.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('tpq-classes.*'), 'text-slate-300' => ! request()->routeIs('tpq-classes.*')])>Kelas TPQ</a>
            <a href="{{ route('santri-attendances.report') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('santri-attendances.*'), 'text-slate-300' => ! request()->routeIs('santri-attendances.*')])>Laporan Absensi TPQ</a>
        </div>
    </details>

    <details class="group" @if(request()->routeIs('assets.*', 'asset-maintenances.*', 'library-books.*', 'book-loans.*', 'book-loan-requests.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Zm0 0V21m4-14h8m-8 4h8" /></svg>
            <span class="min-w-0 flex-1">Aset & Perpustakaan</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('assets.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('assets.*'), 'text-slate-300' => ! request()->routeIs('assets.*')])>Inventaris Aset</a>
            <a href="{{ route('asset-maintenances.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('asset-maintenances.*'), 'text-slate-300' => ! request()->routeIs('asset-maintenances.*')])>Perawatan Aset</a>
            <a href="{{ route('library-books.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('library-books.*', 'book-loans.*', 'book-loan-requests.*'), 'text-slate-300' => ! request()->routeIs('library-books.*', 'book-loans.*', 'book-loan-requests.*')])>Katalog Buku</a>
        </div>
    </details>

    <details class="group" @if(request()->routeIs('announcements.*', 'dakwah-contents.*', 'activity-logs.*', 'user-administration.*', 'notifications.*', 'home', 'digital-signage.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm8-3a8 8 0 0 0-.1-1.3l1.5-1.2-1.5-2.6-1.8.7a8 8 0 0 0-2.2-1.3L15.6 4h-3.1l-.3 2.3A8 8 0 0 0 10 7.6l-1.8-.7-1.5 2.6 1.5 1.2a8 8 0 0 0 0 2.6l-1.5 1.2 1.5 2.6 1.8-.7a8 8 0 0 0 2.2 1.3l.3 2.3h3.1l.3-2.3a8 8 0 0 0 2.2-1.3l1.8.7 1.5-2.6-1.5-1.2A8 8 0 0 0 20 12Z" /></svg>
            <span class="min-w-0 flex-1">Pengaturan & Pengguna</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('notifications.index') }}" @class(['flex items-center justify-between rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('notifications.*'), 'text-slate-300' => ! request()->routeIs('notifications.*')]) @if(request()->routeIs('notifications.*')) aria-current="page" @endif>
                <span>Notifikasi</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-xs font-semibold text-white">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <a href="{{ route('user-administration.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('user-administration.*'), 'text-slate-300' => ! request()->routeIs('user-administration.*')])>Administrasi Akun</a>
            <a href="{{ route('announcements.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('announcements.*'), 'text-slate-300' => ! request()->routeIs('announcements.*')])>Pengumuman Masjid</a>
            <a href="{{ route('dakwah-contents.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('dakwah-contents.*'), 'text-slate-300' => ! request()->routeIs('dakwah-contents.*')])>Konten & Artikel Dakwah</a>
            <a href="{{ route('activity-logs.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('activity-logs.*'), 'text-slate-300' => ! request()->routeIs('activity-logs.*')])>Log Aktivitas Sistem</a>
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Website Publik <span aria-hidden="true">↗</span></a>
            <a href="{{ route('digital-signage.index') }}" target="_blank" rel="noopener" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Digital Signage <span aria-hidden="true">↗</span></a>
        </div>
    </details>

</div>
