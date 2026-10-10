<div class="pt-4">

    <p class="px-4 text-xs font-semibold text-slate-500 uppercase mb-2">
        Keuangan
    </p>

    <a
        href="{{ route('transaksi.index') }}"
        class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Transaksi Keuangan
    </a>

    <a
        href="{{ route('transaksi.create', ['type' => 'income']) }}"
        class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Pemasukan
    </a>

    <a
        href="{{ route('transaksi.create', ['type' => 'expense']) }}"
        class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Pengeluaran
    </a>

    <a href="{{ route('donasi.index') }}"
        class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Donasi
    </a>

   <a
        href="{{ route('zakat.index') }}"
        class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Kalkulator Zakat
    </a>

    <a
        href="{{ route('mustahik.index') }}"
        class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Mustahik
    </a>

    <a
        href="{{ route('laporan-keuangan.index') }}"
        class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Laporan Keuangan
    </a>

</div>