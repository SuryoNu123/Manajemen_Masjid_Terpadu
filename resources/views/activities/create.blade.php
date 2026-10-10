@extends('layouts.dashboard')

@section('title', 'Tambah Kegiatan')

@section('content')

<div class="max-w-3xl">

    {{-- Header --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-900">
            Tambah Kegiatan
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Tambahkan jadwal kegiatan ibadah.
        </p>

    </div>


    {{-- Error Validation --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3">

            <div class="text-sm font-semibold text-red-700 mb-2">
                Terdapat kesalahan:
            </div>

            <ul class="text-sm text-red-600 list-disc list-inside space-y-1">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <form action="{{ route('activities.store') }}"
          method="POST"
          class="bg-white border border-slate-200 rounded-2xl p-6 space-y-6">

        @csrf


        {{-- ====================================================== --}}
        {{-- NAMA KEGIATAN --}}
        {{-- ====================================================== --}}

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Nama Kegiatan
            </label>

            <input
                type="text"
                name="title"
                value="{{ old('title') }}"
                placeholder="Contoh: Kajian Ahad Pagi"
                class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                required
            >

        </div>


        {{-- ====================================================== --}}
        {{-- JENIS KEGIATAN --}}
        {{-- ====================================================== --}}

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Jenis Kegiatan
            </label>

            <select
                name="type"
                class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                required
            >

                <option value="">
                    Pilih jenis kegiatan
                </option>

                <option
                    value="kajian"
                    {{ old('type') === 'kajian' ? 'selected' : '' }}
                >
                    Kajian
                </option>

                <option
                    value="khutbah_jumat"
                    {{ old('type') === 'khutbah_jumat' ? 'selected' : '' }}
                >
                    Khutbah Jumat
                </option>

                <option
                    value="pengajian"
                    {{ old('type') === 'pengajian' ? 'selected' : '' }}
                >
                    Pengajian
                </option>

                <option
                    value="kegiatan_lain"
                    {{ old('type') === 'kegiatan_lain' ? 'selected' : '' }}
                >
                    Kegiatan Lain
                </option>

            </select>

        </div>


        {{-- ====================================================== --}}
        {{-- DESKRIPSI --}}
        {{-- ====================================================== --}}

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Deskripsi
            </label>

            <textarea
                name="description"
                rows="4"
                placeholder="Deskripsi kegiatan..."
                class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
            >{{ old('description') }}</textarea>

        </div>


        {{-- ====================================================== --}}
        {{-- TANGGAL DAN WAKTU MULAI --}}
        {{-- ====================================================== --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Tanggal --}}

            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Tanggal
                </label>

                <input
                    type="date"
                    name="activity_date"
                    value="{{ old('activity_date') }}"
                    class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                    required
                >

            </div>


            {{-- Waktu Mulai --}}

            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Waktu Mulai
                </label>

                <input
                    type="time"
                    name="start_time"
                    value="{{ old('start_time') }}"
                    class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                    required
                >

            </div>

        </div>


        {{-- ====================================================== --}}
        {{-- WAKTU SELESAI --}}
        {{-- ====================================================== --}}

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Waktu Selesai
            </label>

            <input
                type="time"
                name="end_time"
                value="{{ old('end_time') }}"
                class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
            >

            <p class="text-xs text-slate-500 mt-2">
                Wajib diisi jika kegiatan memiliki petugas.
            </p>

        </div>


        {{-- ====================================================== --}}
        {{-- LOKASI --}}
        {{-- ====================================================== --}}

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Lokasi
            </label>

            <input
                type="text"
                name="location"
                value="{{ old('location') }}"
                placeholder="Contoh: Masjid Utama"
                class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
            >

        </div>


        {{-- ====================================================== --}}
        {{-- PEMBICARA --}}
        {{-- ====================================================== --}}

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Pembicara / Khatib
            </label>

            <input
                type="text"
                name="speaker"
                value="{{ old('speaker') }}"
                placeholder="Nama pembicara"
                class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
            >

        </div>


        {{-- ====================================================== --}}
        {{-- PETUGAS KEGIATAN --}}
        {{-- ====================================================== --}}

        <div>

            <div class="mb-3">

                <label class="block text-sm font-medium text-slate-700">
                    Petugas Kegiatan
                </label>

                <p class="text-xs text-slate-500 mt-1">
                    Pilih petugas yang akan ditugaskan pada kegiatan ini.
                </p>

            </div>


            @if(isset($officers) && $officers->count() > 0)

                <div class="space-y-3">

                    @foreach($officers as $officer)

                        @php

                            $oldOfficers = old('officers', []);

                            $isChecked = in_array(
                                $officer->id,
                                $oldOfficers
                            );

                            $oldAssignment = old(
                                "assignments.{$officer->id}",
                                $officer->position
                            );

                        @endphp


                        <div class="border border-slate-200 rounded-xl p-4">

                            <div class="flex flex-col md:flex-row md:items-center gap-4">

                                {{-- Checkbox --}}

                                <div class="flex items-center gap-3 flex-1">

                                    <input
                                        type="checkbox"
                                        name="officers[]"
                                        value="{{ $officer->id }}"
                                        id="officer_{{ $officer->id }}"
                                        class="officer-checkbox rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                                        data-officer-id="{{ $officer->id }}"
                                        {{ $isChecked ? 'checked' : '' }}
                                    >

                                    <label
                                        for="officer_{{ $officer->id }}"
                                        class="cursor-pointer"
                                    >

                                        <div class="font-medium text-slate-800">
                                            {{ $officer->name }}
                                        </div>

                                        <div class="text-xs text-slate-500 mt-1">
                                            Posisi terdaftar:
                                            {{ ucfirst($officer->position) }}
                                        </div>

                                    </label>

                                </div>


                                {{-- Assignment --}}

                                <div>

                                    <label
                                        for="assignment_{{ $officer->id }}"
                                        class="block text-xs text-slate-500 mb-1"
                                    >
                                        Ditugaskan sebagai
                                    </label>

                                    <select
                                        name="assignments[{{ $officer->id }}]"
                                        id="assignment_{{ $officer->id }}"
                                        class="assignment-select w-full md:w-auto rounded-lg border-slate-300 text-sm focus:border-slate-900 focus:ring-slate-900"
                                        data-officer-id="{{ $officer->id }}"
                                    >

                                        <option
                                            value="imam"
                                            {{ $oldAssignment === 'imam' ? 'selected' : '' }}
                                        >
                                            Imam
                                        </option>

                                        <option
                                            value="khatib"
                                            {{ $oldAssignment === 'khatib' ? 'selected' : '' }}
                                        >
                                            Khatib
                                        </option>

                                        <option
                                            value="muadzin"
                                            {{ $oldAssignment === 'muadzin' ? 'selected' : '' }}
                                        >
                                            Muadzin
                                        </option>

                                        <option
                                            value="petugas"
                                            {{ $oldAssignment === 'petugas' ? 'selected' : '' }}
                                        >
                                            Petugas
                                        </option>

                                        <option
                                            value="lainnya"
                                            {{ $oldAssignment === 'lainnya' ? 'selected' : '' }}
                                        >
                                            Lainnya
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-4">

                    <p class="text-sm font-medium text-amber-800">
                        Belum ada petugas aktif.
                    </p>

                    <p class="text-xs text-amber-700 mt-1">
                        Silakan tambahkan petugas terlebih dahulu melalui menu Petugas.
                    </p>

                </div>

            @endif

        </div>


        {{-- ====================================================== --}}
        {{-- STATUS --}}
        {{-- ====================================================== --}}

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Status
            </label>

            <select
                name="status"
                class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                required
            >

                <option
                    value="scheduled"
                    {{ old('status', 'scheduled') === 'scheduled' ? 'selected' : '' }}
                >
                    Terjadwal
                </option>

                <option
                    value="ongoing"
                    {{ old('status') === 'ongoing' ? 'selected' : '' }}
                >
                    Berlangsung
                </option>

                <option
                    value="completed"
                    {{ old('status') === 'completed' ? 'selected' : '' }}
                >
                    Selesai
                </option>

                <option
                    value="cancelled"
                    {{ old('status') === 'cancelled' ? 'selected' : '' }}
                >
                    Dibatalkan
                </option>

            </select>

        </div>


        {{-- ====================================================== --}}
        {{-- TAMPILKAN DI PUBLIK --}}
        {{-- ====================================================== --}}

        <label class="flex items-center gap-3">

            <input
                type="checkbox"
                name="is_public"
                value="1"
                class="rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                {{ old('is_public', true) ? 'checked' : '' }}
            >

            <span class="text-sm text-slate-700">
                Tampilkan kegiatan di halaman publik
            </span>

        </label>


        {{-- ====================================================== --}}
        {{-- BUTTON --}}
        {{-- ====================================================== --}}

        <div class="flex justify-end gap-3 pt-5 border-t border-slate-100">

            <a
                href="{{ route('activities.index') }}"
                class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 transition"
            >
                Batal
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition"
            >
                Simpan Kegiatan
            </button>

        </div>

    </form>

</div>


{{-- ====================================================== --}}
{{-- JAVASCRIPT --}}
{{-- ====================================================== --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = document.querySelectorAll('.officer-checkbox');

    checkboxes.forEach(function (checkbox) {

        const officerId = checkbox.dataset.officerId;

        const select = document.getElementById(
            'assignment_' + officerId
        );

        if (!select) {
            return;
        }

        function updateSelectState() {

            select.disabled = !checkbox.checked;

            if (checkbox.checked) {

                select.classList.remove(
                    'bg-slate-100',
                    'text-slate-400'
                );

            } else {

                select.classList.add(
                    'bg-slate-100',
                    'text-slate-400'
                );

            }

        }

        checkbox.addEventListener(
            'change',
            updateSelectState
        );

        updateSelectState();

    });

});
</script>

@endsection