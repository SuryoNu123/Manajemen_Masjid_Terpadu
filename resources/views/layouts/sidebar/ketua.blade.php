<div class="space-y-4 pt-2">

    {{-- KEUANGAN & BISNIS --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Keuangan & Bisnis
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('transaksi.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('transaksi.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Transaksi Keuangan
            </a>
            <a href="{{ route('laporan-keuangan.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('laporan-keuangan.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Keuangan
            </a>
            <a href="{{ route('business-units.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-units.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Unit Usaha Masjid
            </a>
            <a href="{{ route('business-unit-items.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-unit-items.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Stok Produk Toko
            </a>
            <a href="{{ route('business-unit-sales.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-unit-sales.index') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Pencatatan Omzet
            </a>
            <a href="{{ route('business-unit-sales.report') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-unit-sales.report') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Omzet
            </a>
        </div>
    </div>

    {{-- IBADAH & JAMAAH --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Ibadah & Jamaah
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('jamaahs.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('jamaahs.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Database Jamaah
            </a>
            <a href="{{ route('jadwal-sholat.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('jadwal-sholat.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Jadwal Sholat
            </a>
            <a href="{{ route('activities.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('activities.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kegiatan Ibadah & Kajian
            </a>
            <a href="{{ route('officers.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('officers.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Petugas & Imam Sholat
            </a>
        </div>
    </div>

    {{-- REMAJA & TPQ --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Pendidikan & Remaja
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
                Santri TPQ/TPA
            </a>
            <a href="{{ route('santri-attendances.report') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('santri-attendances.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Absensi TPQ
            </a>
        </div>
    </div>

    {{-- ASET & PERPUSTAKAAN --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Aset & Perpustakaan
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('assets.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('assets.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Inventaris Aset
            </a>
            <a href="{{ route('asset-maintenances.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('asset-maintenances.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Perawatan Aset
            </a>
            <a href="{{ route('library-books.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('library-books.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Katalog Buku
            </a>
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

    {{-- PUBLIKASI & AUDIT --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Publikasi & Sistem
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('announcements.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('announcements.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Pengumuman Masjid
            </a>
            <a href="{{ route('dakwah-contents.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('dakwah-contents.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Konten & Artikel Dakwah
            </a>
            <a href="{{ route('activity-logs.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('activity-logs.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Log Aktivitas Sistem
            </a>
            <a href="{{ route('home') }}" target="_blank"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Website Publik ↗
            </a>
            <a href="{{ route('digital-signage.index') }}" target="_blank"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Digital Signage TV ↗
            </a>
        </div>
    </div>

</div>