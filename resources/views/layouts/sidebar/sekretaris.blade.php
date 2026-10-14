<div class="pt-4">

    <p class="px-4 text-xs font-semibold text-slate-500 uppercase mb-2">
        Sekretariat
    </p>

    {{-- Kegiatan --}}
    <a href="{{ route('activities.index') }}"
       class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Kegiatan
    </a>

    {{-- Pengumuman --}}
    <a href="#"
       class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Pengumuman
    </a>

    {{-- Artikel Dakwah --}}
    <a href="#"
       class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Artikel Dakwah
    </a>

    {{-- Data Pengurus --}}
    <a href="#"
       class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Data Pengurus
    </a>

    {{-- Petugas --}}
    <a href="{{ route('officers.index') }}"
       class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        Petugas
    </a>
        
    <a href="{{ route('library-books.index') }}"
    class="block px-4 py-2 rounded-lg hover:bg-slate-800">
        <span>Katalog Perpustakaan</span>
    </a>

    <a href="{{ route('book-loans.index') }}"
    class="block rounded-lg px-4 py-3 hover:bg-slate-800">
        Peminjaman Buku
    </a>

    <a href="{{ route('santri-attendances.index') }}"
   class="block rounded-lg px-4 py-3 hover:bg-slate-800">
    <span>Absensi Santri</span>
</a>

</div>