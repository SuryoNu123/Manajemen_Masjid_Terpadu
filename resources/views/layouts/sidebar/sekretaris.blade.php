<div class="space-y-4 pt-2">

    {{-- ADMINISTRASI & JAMAAH --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Administrasi & Jamaah
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('jamaahs.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('jamaahs.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Database Jamaah
            </a>
            <a href="{{ route('officers.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('officers.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Data Pengurus & Petugas
            </a>
            <a href="{{ route('activities.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('activities.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kegiatan & Kajian
            </a>
        </div>
    </div>

    {{-- KONTEN & PUBLIKASI --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Konten & Informasi
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('announcements.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('announcements.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Pengumuman Masjid
            </a>
            <a href="{{ route('dakwah-contents.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('dakwah-contents.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Artikel Dakwah & Berita
            </a>
            <a href="{{ route('home') }}" target="_blank"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Website Publik ↗
            </a>
        </div>
    </div>

    {{-- REMAJA & TPQ/TPA --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Remaja Masjid & TPQ
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('youth-members.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('youth-members.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Anggota Remaja Masjid
            </a>
            <a href="{{ route('youth-programs.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('youth-programs.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Program Kerja Remaja
            </a>
            <a href="{{ route('santris.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('santris.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Data Santri TPQ
            </a>
            <a href="{{ route('tpq-classes.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('tpq-classes.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kelas Mengaji TPQ
            </a>
            <a href="{{ route('santri-attendances.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('santri-attendances.index') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Absensi Santri
            </a>
            <a href="{{ route('santri-attendances.report') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('santri-attendances.report') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Absensi TPQ
            </a>
        </div>
    </div>

    {{-- PERPUSTAKAAN --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Perpustakaan Masjid
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('library-books.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('library-books.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Katalog Buku
            </a>
            <a href="{{ route('book-loan-requests.index') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">Permintaan Pinjam Jamaah</a>
            <a href="{{ route('book-loans.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('book-loans.index') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Peminjaman Buku
            </a>
            <a href="{{ route('book-loans.report') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('book-loans.report') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Perpustakaan
            </a>
        </div>
    </div>

</div>
