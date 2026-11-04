<div class="space-y-2 pt-1">
    <details class="group" @if(request()->routeIs('jadwal-sholat.*', 'prayer-attendances.*', 'prayer-attendance-reports.*', 'santri-attendances.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v3m0 12v3M4.93 4.93l2.12 2.12m9.9 9.9 2.12 2.12M3 12h3m12 0h3M4.93 19.07l2.12-2.12m9.9-9.9 2.12-2.12M8 12a4 4 0 1 0 8 0 4 4 0 0 0-8 0Z" /></svg>
            <span class="min-w-0 flex-1">Ibadah & Pendidikan</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('jadwal-sholat.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('jadwal-sholat.*'), 'text-slate-300' => ! request()->routeIs('jadwal-sholat.*')])>Jadwal Sholat 5 Waktu</a>
            <a href="{{ route('prayer-attendances.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('prayer-attendances.*'), 'text-slate-300' => ! request()->routeIs('prayer-attendances.*')])>Presensi Jamaah Sholat</a>
            <a href="{{ route('prayer-attendance-reports.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('prayer-attendance-reports.*'), 'text-slate-300' => ! request()->routeIs('prayer-attendance-reports.*')])>Laporan Rekap Presensi</a>
            <a href="{{ route('santri-attendances.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('santri-attendances.*'), 'text-slate-300' => ! request()->routeIs('santri-attendances.*')])>Absensi Santri TPQ</a>
        </div>
    </details>

    <details class="group" @if(request()->routeIs('assets.*', 'asset-maintenances.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5v9L12 21l-9-4.5v-9ZM3 7.5l9 4.5m0 0 9-4.5m-9 4.5V21m-4.5-15 9 4.5" /></svg>
            <span class="min-w-0 flex-1">Aset & Operasional</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('assets.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('assets.*'), 'text-slate-300' => ! request()->routeIs('assets.*')])>Inventaris Aset Masjid</a>
            <a href="{{ route('asset-maintenances.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('asset-maintenances.*'), 'text-slate-300' => ! request()->routeIs('asset-maintenances.*')])>Jadwal & Catatan Perawatan</a>
        </div>
    </details>

    <details class="group">
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Zm0 0V21m4-14h8m-8 4h8" /></svg>
            <span class="min-w-0 flex-1">Informasi Masjid</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('digital-signage.index') }}" target="_blank" rel="noopener" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Buka Digital Signage TV <span aria-hidden="true">↗</span></a>
            <a href="{{ route('activities.public') }}" target="_blank" rel="noopener" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Jadwal Kegiatan Masjid <span aria-hidden="true">↗</span></a>
        </div>
    </details>
</div>
