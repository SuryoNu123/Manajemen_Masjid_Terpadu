@extends('layouts.public')

@section('title', 'Profil Masjid - Masjid Terpadu')

@section('content')
{{-- HERO --}}
<section class="relative overflow-hidden bg-slate-900 py-16 text-white sm:py-20">
    <div class="relative mx-auto max-w-7xl px-6 text-center">
        <span class="mb-4 inline-block rounded-full border border-blue-300/20 bg-blue-700/20 px-4 py-1.5 text-xs font-semibold text-blue-200">
            Profil & Kelembagaan
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">
            Masjid Terpadu
        </h1>
        <p class="mt-4 text-lg md:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
            Pusat Ibadah, Pembinaan Umat, dan Pemberdayaan Sosial-Ekonomi Masyarakat yang Berlandaskan Al-Qur'an dan Sunnah.
        </p>
    </div>
</section>

{{-- VISI MISI --}}
<section class="py-16 bg-white">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-sm font-semibold uppercase tracking-wider text-blue-700">Tentang Kami</span>
                <h2 class="text-3xl font-bold text-gray-900 mt-2">
                    Mewujudkan Masjid yang Makmur dan Memakmurkan
                </h2>
                <p class="mt-4 text-gray-600 leading-relaxed">
                    Masjid Terpadu didirikan sebagai episentrum kegiatan keagamaan, pendidikan anak melalui TPQ/TPA, wadah kreasi pemuda remaja masjid, serta transparansi pengelolaan donasi dan zakat untuk kemaslahatan umat.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 font-bold text-blue-600">
                            ✓
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Visi Utama</h3>
                            <p class="text-sm text-gray-600 mt-0.5">
                                Menjadi masjid percontohan yang modern, akuntabel, dan berdaya guna tinggi bagi kesejahteraan jamaah lahir dan batin.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 font-bold">
                            ✓
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Misi Utama</h3>
                            <p class="text-sm text-gray-600 mt-0.5">
                                Melayani peribadatan sholat 5 waktu, memfasilitasi kajian ilmiah Islam, mengasuh generasi Qur'ani, dan mengelola amanah dana umat secara transparan 100%.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-blue-700 bg-slate-900 p-6 text-white shadow-sm sm:p-8">
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <span>📍</span> Informasi & Alamat
                </h3>
                <div class="space-y-4 text-sm text-slate-300">
                    <div>
                        <p class="text-xs text-slate-400">Lokasi Koordinat GPS</p>
                        <p class="font-semibold text-white mt-1">{{ config('app.mosque_latitude', '-7.000000') }}, {{ config('app.mosque_longitude', '110.000000') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Fasilitas Masjid</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="rounded-lg bg-white/10 px-3 py-1 text-xs">Ruang Sholat Utama AC</span>
                            <span class="rounded-lg bg-white/10 px-3 py-1 text-xs">Perpustakaan Kitab</span>
                            <span class="rounded-lg bg-white/10 px-3 py-1 text-xs">Ruang Belajar TPQ</span>
                            <span class="rounded-lg bg-white/10 px-3 py-1 text-xs">Unit Usaha Mandiri</span>
                            <span class="rounded-lg bg-white/10 px-3 py-1 text-xs">Digital Signage TV</span>
                            <span class="rounded-lg bg-white/10 px-3 py-1 text-xs">Area Parkir Luas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STRUKTUR DEWAN PENGURUS --}}
<section class="py-16 bg-slate-50">
    <div class="mx-auto max-w-7xl px-6">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-sm font-semibold uppercase tracking-wider text-blue-700">Amanah & Tata Kelola</span>
            <h2 class="text-3xl font-bold text-gray-900 mt-2">Struktur Pengurus DKM</h2>
            <p class="text-gray-500 mt-2 text-sm">
                Dewan Kemakmuran Masjid yang bertugas mengelola peribadatan, administrasi, dan keuangan masjid.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($boardMembers as $member)
                <div class="bg-white rounded-2xl border border-gray-200 p-6 text-center shadow-sm hover:shadow-md transition">
                    <div class="w-20 h-20 mx-auto rounded-full bg-slate-900 text-white flex items-center justify-center text-2xl font-bold mb-4">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg">{{ $member->name }}</h3>
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide mt-1">
                        {{ $member->role?->display_name ?? 'Pengurus' }}
                    </p>
                    <p class="text-xs text-gray-400 mt-2">{{ $member->email }}</p>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-400">
                    Data pengurus sedang diperbarui.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- GALERI FOTO --}}
<section class="py-16 bg-white">
    <div class="mx-auto max-w-7xl px-6">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-sm font-semibold uppercase tracking-wider text-blue-700">Dokumentasi</span>
            <h2 class="text-3xl font-bold text-gray-900 mt-2">Galeri Masjid & Kegiatan</h2>
            <p class="text-gray-500 mt-2 text-sm">
                Suasana peribadatan dan sarana prasarana Masjid Terpadu.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex h-56 flex-col justify-end rounded-xl border border-blue-600 bg-blue-700 p-5 text-white">
                <span class="text-2xl mb-1">🕌</span>
                <p class="font-bold text-sm">Ruang Utama Sholat</p>
                <p class="text-xs text-slate-300">Kapasitas 1.000 Jamaah</p>
            </div>
            <div class="flex h-56 flex-col justify-end rounded-xl border border-blue-600 bg-blue-600 p-5 text-white">
                <span class="text-2xl mb-1">📚</span>
                <p class="font-bold text-sm">Perpustakaan Masjid</p>
                <p class="text-xs text-slate-300">Koleksi Kitab & Buku</p>
            </div>
            <div class="flex h-56 flex-col justify-end rounded-xl border border-blue-600 bg-blue-600 p-5 text-white">
                <span class="text-2xl mb-1">📖</span>
                <p class="font-bold text-sm">Kelas Santri TPQ</p>
                <p class="text-xs text-slate-300">Pendidikan Al-Qur'an</p>
            </div>
            <div class="flex h-56 flex-col justify-end rounded-xl border border-blue-600 bg-blue-600 p-5 text-white">
                <span class="text-2xl mb-1">🏪</span>
                <p class="font-bold text-sm">Unit Usaha Masjid</p>
                <p class="text-xs text-slate-300">Koperasi Jamaah</p>
            </div>
        </div>
    </div>
</section>
@endsection

