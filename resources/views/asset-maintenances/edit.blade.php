@extends('layouts.dashboard')

@section('title', 'Edit Perawatan')
@section('page-title', 'Edit Perawatan')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-800">
            Perbarui Data Perawatan
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Perbarui status dan catatan pekerjaan perawatan aset.
        </p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('asset-maintenances.update', $maintenance) }}"
              method="POST">
            @csrf
            @method('PUT')

            @include('asset-maintenances._form', [
                'maintenance' => $maintenance,
            ])

            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('asset-maintenances.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-medium text-slate-600">
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
