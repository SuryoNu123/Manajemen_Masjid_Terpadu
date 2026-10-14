@extends('layouts.dashboard')

@section('title', 'Tambah Unit Usaha')
@section('page-title', 'Tambah Unit Usaha')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-800">
            Tambah Unit Usaha
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Masukkan informasi unit usaha milik masjid.
        </p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('business-units.store') }}" method="POST">
            @csrf

            @include('business-units._form', [
                'businessUnit' => null,
            ])

            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('business-units.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-medium text-slate-600">
                    Batal
                </a>

                <button type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Simpan Unit Usaha
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
