@extends('layouts.public')

@section('title', $book->title)

@section('content')
<section class="mx-auto max-w-3xl space-y-6 px-4 py-10 sm:px-6">
    <a href="{{ route('library.public') }}" class="text-sm font-semibold text-emerald-800">Kembali ke katalog</a>
    @if (session('error'))<p class="rounded-lg bg-amber-50 p-4 text-sm text-amber-800">{{ session('error') }}</p>@endif
    @if ($errors->any())<div class="rounded-lg bg-red-50 p-4 text-sm text-red-800">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800">{{ $book->category }}</span>
        <h1 class="mt-4 text-3xl font-bold text-slate-900">{{ $book->title }}</h1>
        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div><dt class="text-xs uppercase text-slate-500">Penulis</dt><dd>{{ $book->author ?: '—' }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Penerbit</dt><dd>{{ $book->publisher ?: '—' }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Tahun terbit</dt><dd>{{ $book->publication_year ?: '—' }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Ketersediaan</dt><dd>{{ max(0, $book->total_copies - $book->borrowed_copies) }} dari {{ $book->total_copies }}</dd></div>
        </dl>
        <p class="mt-6 whitespace-pre-line text-slate-700">{{ $book->description ?: 'Deskripsi belum tersedia.' }}</p>
        @auth
            @if (auth()->user()->hasRole('jamaah'))
                <form method="POST" action="{{ route('book-loan-requests.store', $book) }}" class="mt-8">
                    @csrf
                    <button @disabled($book->total_copies <= $book->borrowed_copies) class="rounded-lg bg-slate-900 px-5 py-3 font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50">Ajukan peminjaman</button>
                    <a href="{{ route('book-loan-requests.mine') }}" class="ml-3 text-sm font-semibold text-emerald-800">Lihat permintaan saya</a>
                    <p class="mt-2 text-xs text-slate-500">Pengajuan menunggu persetujuan pengurus. Tanggal jatuh tempo ditentukan saat disetujui.</p>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" class="mt-8 inline-flex rounded-lg bg-slate-900 px-5 py-3 font-semibold text-white">Login untuk mengajukan pinjaman</a>
        @endauth
    </article>
</section>
@endsection
