<div class="space-y-2 pt-1">
    <details class="group" @if(request()->routeIs('transaksi.*', 'business-unit-sales.*', 'business-unit-items.*', 'laporan-keuangan.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9h18M5 9V6l7-3 7 3v3m-14 0v10m4-10v10m6-10v10m4-10v10M3 21h18" /></svg>
            <span class="min-w-0 flex-1">Keuangan & Usaha</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('transaksi.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('transaksi.index'), 'text-slate-300' => ! request()->routeIs('transaksi.index')])>Daftar Transaksi Kas</a>
            <a href="{{ route('transaksi.create', ['type' => 'income']) }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->is('*transaksi*create*') && request('type') === 'income', 'text-slate-300' => ! (request()->is('*transaksi*create*') && request('type') === 'income')])>Catat Pemasukan</a>
            <a href="{{ route('transaksi.create', ['type' => 'expense']) }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->is('*transaksi*create*') && request('type') === 'expense', 'text-slate-300' => ! (request()->is('*transaksi*create*') && request('type') === 'expense')])>Catat Pengeluaran</a>
            <a href="{{ route('business-unit-sales.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('business-unit-sales.*', 'business-unit-items.*'), 'text-slate-300' => ! request()->routeIs('business-unit-sales.*', 'business-unit-items.*')])>Unit Usaha</a>
            <a href="{{ route('laporan-keuangan.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('laporan-keuangan.*'), 'text-slate-300' => ! request()->routeIs('laporan-keuangan.*')])>Laporan Keuangan</a>
            <a href="{{ route('transparansi.index') }}" target="_blank" rel="noopener" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Transparansi Publik <span aria-hidden="true">↗</span></a>
        </div>
    </details>

    <details class="group" @if(request()->routeIs('donasi.*', 'mustahik.*', 'zakat.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m-5-3h7a3 3 0 0 0 0-6h-4a3 3 0 0 1 0-6h7" /></svg>
            <span class="min-w-0 flex-1">Donasi & Zakat</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('donasi.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('donasi.*'), 'text-slate-300' => ! request()->routeIs('donasi.*')])>Kelola Donasi Masuk</a>
            <a href="{{ route('mustahik.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('mustahik.*'), 'text-slate-300' => ! request()->routeIs('mustahik.*')])>Penyaluran Mustahik</a>
            <a href="{{ route('zakat.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('zakat.*'), 'text-slate-300' => ! request()->routeIs('zakat.*')])>Kalkulator Zakat</a>
        </div>
    </details>
</div>
