<div class="space-y-2 pt-1">
    <details class="group" @if(request()->routeIs('home', 'activities.public', 'announcements.public', 'dakwah.public')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Zm0 0V21m4-14h8m-8 4h8" /></svg>
            <span class="min-w-0 flex-1">Informasi & Kegiatan</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('home') }}" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Jadwal Sholat & Beranda</a>
            <a href="{{ route('activities.public') }}" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Jadwal Kajian & Kegiatan</a>
            <a href="{{ route('announcements.public') }}" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Pengumuman Masjid</a>
            <a href="{{ route('dakwah.public') }}" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Artikel Dakwah</a>
        </div>
    </details>

    <details class="group" @if(request()->routeIs('library.public', 'book-loan-requests.mine', 'whatsapp-preferences.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Zm0 0V21m0-15 12 0m-12 4h12" /></svg>
            <span class="min-w-0 flex-1">Perpustakaan & Layanan</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('library.public') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('library.public'), 'text-slate-300' => ! request()->routeIs('library.public')])>Katalog Perpustakaan</a>
            <a href="{{ route('book-loan-requests.mine') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('book-loan-requests.mine'), 'text-slate-300' => ! request()->routeIs('book-loan-requests.mine')])>Peminjaman Saya</a>
            <a href="{{ route('whatsapp-preferences.edit') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('whatsapp-preferences.*'), 'text-slate-300' => ! request()->routeIs('whatsapp-preferences.*')])>Preferensi Notifikasi</a>
        </div>
    </details>

    <details class="group" @if(request()->routeIs('donasi.create', 'zakat.*', 'transparansi.*')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m-5-3h7a3 3 0 0 0 0-6h-4a3 3 0 0 1 0-6h7" /></svg>
            <span class="min-w-0 flex-1">Donasi & Zakat</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('donasi.create') }}" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Infaq & Donasi Digital</a>
            <a href="{{ route('zakat.index') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('zakat.*'), 'text-slate-300' => ! request()->routeIs('zakat.*')])>Kalkulator Zakat</a>
            <a href="{{ route('transparansi.index') }}" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Transparansi Kas Masjid</a>
        </div>
    </details>

    <details class="group" @if(request()->routeIs('profile.public', 'profile.edit')) open @endif>
        <summary class="flex min-h-11 cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5 shrink-0 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm8-3a8 8 0 0 0-.1-1.3l1.5-1.2-1.5-2.6-1.8.7a8 8 0 0 0-2.2-1.3L15.6 4h-3.1l-.3 2.3A8 8 0 0 0 10 7.6l-1.8-.7-1.5 2.6 1.5 1.2a8 8 0 0 0 0 2.6l-1.5 1.2 1.5 2.6 1.8-.7a8 8 0 0 0 2.2 1.3l.3 2.3h3.1l.3-2.3a8 8 0 0 0 2.2-1.3l1.8.7 1.5-2.6-1.5-1.2A8 8 0 0 0 20 12Z" /></svg>
            <span class="min-w-0 flex-1">Akun & Profil</span>
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4 shrink-0 text-white transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="ml-6 mt-1 space-y-0.5 border-l border-slate-700 pl-3">
            <a href="{{ route('profile.public') }}" class="block rounded-md px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800">Profil & DKM Masjid</a>
            <a href="{{ route('profile.edit') }}" @class(['block rounded-md px-3 py-2 text-sm transition hover:bg-slate-800', 'bg-slate-800 font-medium text-white' => request()->routeIs('profile.edit'), 'text-slate-300' => ! request()->routeIs('profile.edit')])>Pengaturan Akun Saya</a>
        </div>
    </details>
</div>
