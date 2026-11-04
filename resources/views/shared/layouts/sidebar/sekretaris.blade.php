<div class="space-y-2 pt-1">

    {{-- ADMINISTRASI & JAMAAH --}}
    <details class="group" @if(request()->routeIs('jamaahs.*', 'officers.*', 'activities.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" /></svg>
            <span class="min-w-0 flex-1">Administrasi & Jamaah</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('jamaahs.index') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('jamaahs.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Database Jamaah
            </a>
            <a href="{{ route('officers.index') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('officers.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Data Pengurus & Petugas
            </a>
            <a href="{{ route('activities.index') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('activities.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kegiatan & Kajian
            </a>
        </div>
    </details>

    {{-- KONTEN & PUBLIKASI --}}
    <details class="group" @if(request()->routeIs('announcements.*', 'dakwah-contents.*', 'home')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Zm0 0V21m4-14h8m-8 4h8" /></svg>
            <span class="min-w-0 flex-1">Konten & Informasi</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('announcements.index') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('announcements.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Pengumuman Masjid
            </a>
            <a href="{{ route('dakwah-contents.index') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('dakwah-contents.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Artikel Dakwah & Berita
            </a>
            <a href="{{ route('home') }}" target="_blank"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">
                Website Publik ↗
            </a>
        </div>
    </details>

    {{-- REMAJA & TPQ/TPA --}}
    <details class="group" @if(request()->routeIs('youth-members.*', 'youth-programs.*', 'santris.*', 'tpq-classes.*', 'santri-attendances.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14 2 9l10-5 10 5-10 5Zm0 0v6m-7-8v5c4.67 3.33 9.33 3.33 14 0v-5" /></svg>
            <span class="min-w-0 flex-1">Remaja Masjid & TPQ</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('youth-members.index') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('youth-members.*', 'youth-programs.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Remaja Masjid
            </a>
            <a href="{{ route('santris.index') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('santris.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Data Santri TPQ
            </a>
            <a href="{{ route('tpq-classes.index') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('tpq-classes.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kelas Mengaji TPQ
            </a>
            <a href="{{ route('santri-attendances.index') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('santri-attendances.index') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Absensi Santri
            </a>
            <a href="{{ route('santri-attendances.report') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('santri-attendances.report') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Absensi TPQ
            </a>
        </div>
    </details>

    {{-- PERPUSTAKAAN --}}
    <details class="group" @if(request()->routeIs('library-books.*', 'book-loans.*', 'book-loan-requests.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Zm0 0V21m4-14h8m-8 4h8" /></svg>
            <span class="min-w-0 flex-1">Perpustakaan Masjid</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('library-books.index') }}"
               class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 {{ request()->routeIs('library-books.*', 'book-loans.*', 'book-loan-requests.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Katalog Buku
            </a>
        </div>
    </details>

</div>
