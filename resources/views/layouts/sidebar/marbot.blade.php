<div class="space-y-3 pt-1">

    {{-- OPERASIONAL IBADAH --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Operasional Ibadah
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('jadwal-sholat.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('jadwal-sholat.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Jadwal Sholat 5 Waktu
            </a>
            <a href="{{ route('prayer-attendances.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('prayer-attendances.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Presensi Jamaah Sholat
            </a>
            <a href="{{ route('prayer-attendance-reports.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('prayer-attendance-reports.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Laporan Rekap Presensi
            </a>
            <a href="{{ route('santri-attendances.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('santri-attendances.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Absensi Santri TPQ
            </a>
        </div>
    </div>

    {{-- PEMELIHARAAN ASET --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Pemeliharaan Aset
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('assets.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('assets.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Inventaris Aset Masjid
            </a>
            <a href="{{ route('asset-maintenances.index') }}"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('asset-maintenances.*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Jadwal & Catatan Perawatan
            </a>
        </div>
    </div>

    {{-- MEDIA & INFORMASI --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Media Masjid
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('digital-signage.index') }}" target="_blank"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800">
                📺 Buka Digital Signage TV ↗
            </a>
            <a href="{{ route('activities.public') }}" target="_blank"
               class="block rounded-md px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-800">
                Jadwal Kegiatan Masjid ↗
            </a>
        </div>
    </div>

</div>