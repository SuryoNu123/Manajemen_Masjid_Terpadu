<div class="space-y-4 pt-2">

    {{-- KAS & TRANSAKSI --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Kas & Transaksi
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('transaksi.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('transaksi.index') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Daftar Transaksi Kas
            </a>
            <a href="{{ route('transaksi.create', ['type' => 'income']) }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->is('*transaksi*create*') && request('type') === 'income' ? 'bg-slate-800 text-white font-medium' : '' }}">
                + Catat Pemasukan
            </a>
            <a href="{{ route('transaksi.create', ['type' => 'expense']) }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->is('*transaksi*create*') && request('type') === 'expense' ? 'bg-slate-800 text-white font-medium' : '' }}">
                - Catat Pengeluaran
            </a>
        </div>
    </div>

    {{-- DONASI & ZAKAT --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Donasi & Zakat
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('donasi.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('donasi.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kelola Donasi Masuk
            </a>
            <a href="{{ route('mustahik.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('mustahik.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Penyaluran Mustahik
            </a>
            <a href="{{ route('zakat.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('zakat.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Kalkulator Zakat
            </a>
        </div>
    </div>

    {{-- BISNIS & UNIT USAHA --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Unit Usaha Masjid
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('business-unit-sales.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-unit-sales.index') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Pencatatan Omzet
            </a>
            <a href="{{ route('business-unit-items.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-unit-items.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Stok Produk Toko
            </a>
            <a href="{{ route('business-unit-sales.report') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('business-unit-sales.report') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Omzet
            </a>
        </div>
    </div>

    {{-- LAPORAN & TRANSPARANSI --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Laporan
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('laporan-keuangan.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('laporan-keuangan.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Keuangan
            </a>
            <a href="{{ route('transparansi.index') }}" target="_blank"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Transparansi Publik ↗
            </a>
        </div>
    </div>

</div>