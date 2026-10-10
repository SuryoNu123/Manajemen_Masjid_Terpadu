@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal Sholat')
@section('page-title', 'Tambah Jadwal Sholat')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Tambah Jadwal Sholat
        </h1>

        <p class="text-gray-500 mt-1">
            Masukkan jadwal sholat untuk tanggal tertentu.
        </p>

    </div>


    @if($errors->any())

        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">

            <ul class="list-disc ml-5">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('jadwal-sholat.store') }}"
        class="bg-white rounded-xl shadow-sm p-6 space-y-6"
    >

        @csrf


        <div>

            <label class="block font-medium mb-2">
                Tanggal
            </label>

            <input
                type="date"
                name="schedule_date"
                value="{{ old(
                    'schedule_date',
                    now()->toDateString()
                ) }}"
                required
                class="w-full rounded-lg border-gray-300"
            >

        </div>


        <div>

            <label class="block font-medium mb-2">
                Sholat
            </label>

            <select
                name="prayer_name"
                required
                class="w-full rounded-lg border-gray-300"
            >

                <option value="">
                    Pilih sholat
                </option>

                <option value="subuh">
                    Subuh
                </option>

                <option value="dzuhur">
                    Dzuhur
                </option>

                <option value="ashar">
                    Ashar
                </option>

                <option value="maghrib">
                    Maghrib
                </option>

                <option value="isya">
                    Isya
                </option>

            </select>

        </div>


        <div>

            <label class="block font-medium mb-2">
                Waktu
            </label>

            <input
                type="time"
                name="prayer_time"
                value="{{ old('prayer_time') }}"
                required
                class="w-full rounded-lg border-gray-300"
            >

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>

                <label class="block font-medium mb-2">
                    Latitude
                </label>

                <input
                    type="number"
                    name="latitude"
                    value="{{ old('latitude') }}"
                    step="0.0000001"
                    placeholder="-7.0000000"
                    class="w-full rounded-lg border-gray-300"
                >

            </div>


            <div>

                <label class="block font-medium mb-2">
                    Longitude
                </label>

                <input
                    type="number"
                    name="longitude"
                    value="{{ old('longitude') }}"
                    step="0.0000001"
                    placeholder="110.0000000"
                    class="w-full rounded-lg border-gray-300"
                >

            </div>

        </div>


        <div>

            <label class="block font-medium mb-2">
                Sumber
            </label>

            <input
                type="text"
                name="source"
                value="{{ old('source', 'manual') }}"
                class="w-full rounded-lg border-gray-300"
            >

        </div>


        <label class="flex items-center gap-3">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                checked
            >

            <span>
                Jadwal aktif
            </span>

        </label>


        <div class="flex justify-end gap-3 pt-4 border-t">

            <a
                href="{{ route('jadwal-sholat.index') }}"
                class="px-5 py-2.5 bg-gray-200 rounded-lg"
            >
                Batal
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 bg-slate-900 text-white rounded-lg"
            >
                Simpan
            </button>

        </div>

    </form>

</div>

@endsection