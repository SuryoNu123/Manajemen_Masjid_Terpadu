@extends('layouts.dashboard')

@section('content')
<div class="mx-auto max-w-3xl">
    <h1 class="mb-2 text-2xl font-bold text-slate-800">Tambah Kelas TPQ/TPA</h1>
    <p class="mb-6 text-sm text-slate-500">Masukkan informasi kelas dan jadwal belajar.</p>

    <form action="{{ route('tpq-classes.store') }}" method="POST"
          class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @include('tpq-classes._form')
    </form>
</div>
@endsection
