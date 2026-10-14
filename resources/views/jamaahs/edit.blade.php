@extends('layouts.dashboard')

@section('title', 'Edit Jamaah')
@section('page-title', 'Edit Jamaah')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-800">Edit Data Jamaah</h2>
        <p class="mt-1 text-sm text-slate-500">
            Perbarui informasi {{ $jamaah->name }}.
        </p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('jamaahs.update', $jamaah) }}" method="POST">
            @csrf
            @method('PUT')

            @include('jamaahs._form', ['jamaah' => $jamaah])

            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('jamaahs.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-medium text-slate-600 hover:bg-slate-50">
                    Batal
                </a>

                <button type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
