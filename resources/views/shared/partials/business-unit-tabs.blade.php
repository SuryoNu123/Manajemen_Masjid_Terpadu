<nav aria-label="Navigasi unit usaha" class="-mx-1 overflow-x-auto px-1">
    <div class="flex min-w-max gap-2 border-b border-slate-200 pb-2">
        @if (auth()->user()->hasRole('ketua'))
            <a href="{{ route('business-units.index') }}" @class(['inline-flex min-h-11 items-center rounded-lg px-4 text-sm font-semibold transition', 'bg-blue-700 text-white' => request()->routeIs('business-units.*'), 'bg-white text-slate-600 hover:bg-slate-100' => ! request()->routeIs('business-units.*')]) @if(request()->routeIs('business-units.*')) aria-current="page" @endif>Unit Usaha</a>
        @endif
        <a href="{{ route('business-unit-sales.index') }}" @class(['inline-flex min-h-11 items-center rounded-lg px-4 text-sm font-semibold transition', 'bg-blue-700 text-white' => request()->routeIs('business-unit-sales.index', 'business-unit-sales.create'), 'bg-white text-slate-600 hover:bg-slate-100' => ! request()->routeIs('business-unit-sales.index', 'business-unit-sales.create')]) @if(request()->routeIs('business-unit-sales.index', 'business-unit-sales.create')) aria-current="page" @endif>Pencatatan Omzet</a>
        <a href="{{ route('business-unit-sales.report') }}" @class(['inline-flex min-h-11 items-center rounded-lg px-4 text-sm font-semibold transition', 'bg-blue-700 text-white' => request()->routeIs('business-unit-sales.report'), 'bg-white text-slate-600 hover:bg-slate-100' => ! request()->routeIs('business-unit-sales.report')]) @if(request()->routeIs('business-unit-sales.report')) aria-current="page" @endif>Laporan Omzet</a>
        <a href="{{ route('business-unit-items.index') }}" @class(['inline-flex min-h-11 items-center rounded-lg px-4 text-sm font-semibold transition', 'bg-blue-700 text-white' => request()->routeIs('business-unit-items.*'), 'bg-white text-slate-600 hover:bg-slate-100' => ! request()->routeIs('business-unit-items.*')]) @if(request()->routeIs('business-unit-items.*')) aria-current="page" @endif>Stok Produk</a>
    </div>
</nav>
