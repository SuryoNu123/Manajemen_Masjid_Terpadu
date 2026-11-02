<div class="space-y-3 pt-1">

    {{-- ADMINISTRASI & JAMAAH --}}
    <details class="group" @if(request()->routeIs('jamaahs.*', 'officers.*', 'activities.*')) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400 hover:bg-slate-800 hover:text-slate-200">
            Administrasi & Jamaah
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('jamaahs.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('jamaahs.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Database Jamaah
            </a>
            <a href="{{ route('officers.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('officers.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Data Pengurus & Petugas
            </a>
            <a href="{{ route('activities.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('activities.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kegiatan & Kajian
            </a>
        </div>
    </details>

    {{-- KONTEN & PUBLIKASI --}}
    <details class="group" @if(request()->routeIs('announcements.*', 'dakwah-contents.*', 'home')) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400 hover:bg-slate-800 hover:text-slate-200">
            Konten & Informasi
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('announcements.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('announcements.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Pengumuman Masjid
            </a>
            <a href="{{ route('dakwah-contents.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('dakwah-contents.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Artikel Dakwah & Berita
            </a>
            <a href="{{ route('home') }}" target="_blank"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800">
                Website Publik ↗
            </a>
        </div>
    </details>

    {{-- REMAJA & TPQ/TPA --}}
    <details class="group" @if(request()->routeIs('youth-members.*', 'youth-programs.*', 'santris.*', 'tpq-classes.*', 'santri-attendances.*')) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400 hover:bg-slate-800 hover:text-slate-200">
            Remaja Masjid & TPQ
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('youth-members.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('youth-members.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Anggota Remaja Masjid
            </a>
            <a href="{{ route('youth-programs.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('youth-programs.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Program Kerja Remaja
            </a>
            <a href="{{ route('santris.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('santris.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Data Santri TPQ
            </a>
            <a href="{{ route('tpq-classes.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('tpq-classes.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kelas Mengaji TPQ
            </a>
            <a href="{{ route('santri-attendances.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('santri-attendances.index') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Absensi Santri
            </a>
            <a href="{{ route('santri-attendances.report') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('santri-attendances.report') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Absensi TPQ
            </a>
        </div>
    </details>

    {{-- PERPUSTAKAAN --}}
    <details class="group" @if(request()->routeIs('library-books.*', 'book-loans.*', 'book-loan-requests.*')) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400 hover:bg-slate-800 hover:text-slate-200">
            Perpustakaan Masjid
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('library-books.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('library-books.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Katalog Buku
            </a>
            <a href="{{ route('book-loan-requests.index') }}" class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800">Permintaan Pinjam Jamaah</a>
            <a href="{{ route('book-loans.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('book-loans.index') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Peminjaman Buku
            </a>
            <a href="{{ route('book-loans.report') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('book-loans.report') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Perpustakaan
            </a>
        </div>
    </details>

</div>
