@extends('layouts.dashboard')

@section('content')
<div class="mx-auto max-w-4xl">
    <h1 class="text-2xl font-bold text-slate-800">Edit Data Santri</h1>
    <p class="mb-6 mt-1 text-sm text-slate-500">
        Perbarui informasi santri yang dipilih.
    </p>

    <form action="{{ route('santris.update', $santri) }}" method="POST"
          class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')
        @include('santris._form')
    </form>
</div>
@endsection
