<nav aria-label="Navigasi Remaja Masjid" class="-mx-1 overflow-x-auto px-1">
    <div class="flex min-w-max gap-2 border-b border-slate-200 pb-2">
        <a href="{{ route('youth-members.index') }}" @class(['inline-flex min-h-11 items-center rounded-lg px-4 text-sm font-semibold transition', 'bg-blue-700 text-white' => request()->routeIs('youth-members.*'), 'bg-white text-slate-600 hover:bg-slate-100' => ! request()->routeIs('youth-members.*')]) @if(request()->routeIs('youth-members.*')) aria-current="page" @endif>Anggota Remaja</a>
        <a href="{{ route('youth-programs.index') }}" @class(['inline-flex min-h-11 items-center rounded-lg px-4 text-sm font-semibold transition', 'bg-blue-700 text-white' => request()->routeIs('youth-programs.*'), 'bg-white text-slate-600 hover:bg-slate-100' => ! request()->routeIs('youth-programs.*')]) @if(request()->routeIs('youth-programs.*')) aria-current="page" @endif>Program Kerja</a>
    </div>
</nav>
