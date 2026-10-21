<div class="space-y-4 pt-2">

    {{-- LAYANAN & INFORMASI --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Layanan Jamaah
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('home') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Jadwal Sholat & Beranda
            </a>
            <a href="{{ route('activities.public') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Jadwal Kajian & Kegiatan
            </a>
            <a href="{{ route('announcements.public') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Pengumuman Masjid
            </a>
            <a href="{{ route('dakwah.public') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Artikel Dakwah
            </a>
            <a href="{{ route('library.public') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">Katalog Perpustakaan</a>
            <a href="{{ route('book-loan-requests.mine') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">Peminjaman Saya</a>
            <a href="{{ route('whatsapp-preferences.edit') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">Preferensi Notifikasi</a>
        </div>
    </div>

    {{-- DONASI & ZAKAT --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Donasi & Transparansi
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('donasi.create') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Infaq & Donasi Digital
            </a>
            <a href="{{ route('zakat.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Kalkulator Zakat
            </a>
            <a href="{{ route('transparansi.index') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Transparansi Kas Masjid
            </a>
        </div>
    </div>

    {{-- PROFIL --}}
    <div>
        <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Akun & Profil
        </p>
        <div class="mt-1 space-y-0.5">
            <a href="{{ route('profile.public') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                Profil & DKM Masjid
            </a>
            <a href="{{ route('profile.edit') }}"
               class="block rounded-lg px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 {{ request()->routeIs('profile.edit') ? 'bg-slate-800 text-white font-medium' : '' }}">
                Pengaturan Akun Saya
            </a>
        </div>
    </div>

</div>
