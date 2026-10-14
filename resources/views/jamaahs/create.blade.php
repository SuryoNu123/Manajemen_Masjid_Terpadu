@extends('layouts.dashboard')

@section('title', 'Tambah Jamaah')
@section('page-title', 'Tambah Jamaah')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-800">Tambah Data Jamaah</h2>
        <p class="mt-1 text-sm text-slate-500">
            Masukkan informasi jamaah dengan benar.
        </p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('jamaahs.store') }}" method="POST">
            @csrf

            @include('jamaahs._form', ['jamaah' => null])

            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('jamaahs.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-medium text-slate-600 hover:bg-slate-50">
                    Batal
                </a>

                <button type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Simpan Jamaah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
