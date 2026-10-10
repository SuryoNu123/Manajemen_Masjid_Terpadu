@extends('layouts.dashboard')

@section('title', 'Edit Petugas')

@section('content')

<div class="max-w-2xl">

    <h1 class="text-2xl font-bold text-slate-900">
        Edit Petugas
    </h1>

    <p class="text-sm text-slate-500 mt-1 mb-6">
        Perbarui data petugas.
    </p>

    <form action="{{ route('officers.update', $officer) }}"
          method="POST"
          class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5">

        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-2">
                Nama
            </label>

            <input type="text"
                   name="name"
                   value="{{ old('name', $officer->name) }}"
                   class="w-full rounded-xl border-slate-300"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">
                Posisi
            </label>

            <select name="position"
                    class="w-full rounded-xl border-slate-300"
                    required>

                <option value="imam" @selected($officer->position === 'imam')}>
                    Imam
                </option>

                <option value="khatib" @selected($officer->position === 'khatib')}>
                    Khatib
                </option>

                <option value="muadzin" @selected($officer->position === 'muadzin')}>
                    Muadzin
                </option>

                <option value="petugas" @selected($officer->position === 'petugas')}>
                    Petugas
                </option>

                <option value="lainnya" @selected($officer->position === 'lainnya')}>
                    Lainnya
                </option>

            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">
                No. Telepon
            </label>

            <input type="text"
                   name="phone"
                   value="{{ old('phone', $officer->phone) }}"
                   class="w-full rounded-xl border-slate-300">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">
                Catatan
            </label>

            <textarea name="notes"
                      rows="4"
                      class="w-full rounded-xl border-slate-300">{{ old('notes', $officer->notes) }}</textarea>
        </div>

        <label class="flex items-center gap-3">

            <input type="checkbox"
                   name="is_active"
                   value="1"
                   @checked($officer->is_active)>

            <span class="text-sm">
                Petugas aktif
            </span>

        </label>

        <div class="flex justify-end gap-3">

            <a href="{{ route('officers.index') }}"
               class="px-5 py-2.5 rounded-xl border">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-semibold">
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection