<div class="space-y-3 pt-1">

    {{-- KEUANGAN & BISNIS --}}
    <details class="group" @if(request()->routeIs('transaksi.*', 'laporan-keuangan.*', 'business-units.*', 'business-unit-items.*', 'business-unit-sales.*')) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400 hover:bg-slate-800 hover:text-slate-200">
            Keuangan & Bisnis
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('transaksi.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('transaksi.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Transaksi Keuangan
            </a>
            <a href="{{ route('laporan-keuangan.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('laporan-keuangan.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Keuangan
            </a>
            <a href="{{ route('business-units.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-units.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Unit Usaha Masjid
            </a>
            <a href="{{ route('business-unit-items.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-unit-items.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Stok Produk Toko
            </a>
            <a href="{{ route('business-unit-sales.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-unit-sales.index') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Pencatatan Omzet
            </a>
            <a href="{{ route('business-unit-sales.report') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-unit-sales.report') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Omzet
            </a>
        </div>
    </details>

    {{-- IBADAH & JAMAAH --}}
    <details class="group" @if(request()->routeIs('jamaahs.*', 'jadwal-sholat.*', 'prayer-attendances.*', 'activities.*', 'officers.*')) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400 hover:bg-slate-800 hover:text-slate-200">
            Ibadah & Jamaah
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('jamaahs.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('jamaahs.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Database Jamaah
            </a>
            <a href="{{ route('jadwal-sholat.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('jadwal-sholat.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Jadwal Sholat
            </a>
            <a href="{{ route('prayer-attendances.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('prayer-attendances.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Presensi Sholat
            </a>
            <a href="{{ route('activities.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('activities.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kegiatan Ibadah & Kajian
            </a>
            <a href="{{ route('officers.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('officers.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Petugas & Imam Sholat
            </a>
        </div>
    </details>

    {{-- REMAJA & TPQ --}}
    <details class="group" @if(request()->routeIs('youth-members.*', 'youth-programs.*', 'santris.*', 'tpq-classes.*', 'santri-attendances.*')) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400 hover:bg-slate-800 hover:text-slate-200">
            Pendidikan & Remaja
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
                Santri TPQ/TPA
            </a>
            <a href="{{ route('tpq-classes.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('tpq-classes.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kelas TPQ
            </a>
            <a href="{{ route('santri-attendances.report') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('santri-attendances.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Absensi TPQ
            </a>
        </div>
    </details>

    {{-- ASET & PERPUSTAKAAN --}}
    <details class="group" @if(request()->routeIs('assets.*', 'asset-maintenances.*', 'library-books.*', 'book-loans.*', 'book-loan-requests.*')) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400 hover:bg-slate-800 hover:text-slate-200">
            Aset & Perpustakaan
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('assets.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('assets.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Inventaris Aset
            </a>
            <a href="{{ route('asset-maintenances.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('asset-maintenances.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Perawatan Aset
            </a>
            <a href="{{ route('library-books.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('library-books.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Katalog Buku
            </a>
            <a href="{{ route('book-loans.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('book-loans.index') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Peminjaman Buku
            </a>
            <a href="{{ route('book-loan-requests.index') }}" class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('book-loan-requests.index') ? 'bg-slate-800 text-white font-medium' : '' }}">Permintaan Pinjam Jamaah</a>
            <a href="{{ route('book-loans.report') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('book-loans.report') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Perpustakaan
            </a>
        </div>
    </details>

    {{-- PUBLIKASI & AUDIT --}}
    <details class="group" @if(request()->routeIs('announcements.*', 'dakwah-contents.*', 'activity-logs.*', 'user-administration.*', 'home', 'digital-signage.*')) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400 hover:bg-slate-800 hover:text-slate-200">
            Publikasi & Sistem
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('announcements.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('announcements.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Pengumuman Masjid
            </a>
            <a href="{{ route('dakwah-contents.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('dakwah-contents.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Konten & Artikel Dakwah
            </a>
            <a href="{{ route('activity-logs.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('activity-logs.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Log Aktivitas Sistem
            </a>
            <a href="{{ route('user-administration.index') }}" class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('user-administration.*') ? 'bg-slate-800 text-white font-medium' : '' }}">Administrasi Akun</a>
            <a href="{{ route('home') }}" target="_blank"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800">
                Website Publik ↗
            </a>
            <a href="{{ route('digital-signage.index') }}" target="_blank"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800">
                Digital Signage TV ↗
            </a>
        </div>
    </details>

</div>
