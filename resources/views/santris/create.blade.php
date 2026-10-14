@extends('layouts.dashboard')

@section('content')
<div class="mx-auto max-w-4xl">
    <h1 class="text-2xl font-bold text-slate-800">Tambah Santri</h1>
    <p class="mb-6 mt-1 text-sm text-slate-500">
        Isi informasi santri dan orang tua atau wali.
    </p>

    <form action="{{ route('santris.store') }}" method="POST"
          class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @include('santris._form')
    </form>
</div>
@endsection
