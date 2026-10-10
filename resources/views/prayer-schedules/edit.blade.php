@extends('layouts.dashboard')

@section('title', 'Edit Jadwal Sholat')
@section('page-title', 'Edit Jadwal Sholat')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Edit Jadwal Sholat
        </h1>

        <p class="text-gray-500 mt-1">
            Perbarui waktu sholat.
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
        action="{{ route(
            'jadwal-sholat.update',
            $jadwalSholat
        ) }}"
        class="bg-white rounded-xl shadow-sm p-6 space-y-6"
    >

        @csrf

        @method('PUT')


        <div>

            <label class="block font-medium mb-2">
                Tanggal
            </label>

            <input
                type="date"
                name="schedule_date"
                value="{{ old(
                    'schedule_date',
                    $jadwalSholat
                        ->schedule_date
                        ->format('Y-m-d')
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

                @foreach([
                    'subuh' => 'Subuh',
                    'dzuhur' => 'Dzuhur',
                    'ashar' => 'Ashar',
                    'maghrib' => 'Maghrib',
                    'isya' => 'Isya',
                ] as $value => $label)

                    <option
                        value="{{ $value }}"
                        @selected(
                            old(
                                'prayer_name',
                                $jadwalSholat->prayer_name
                            ) === $value
                        )
                    >
                        {{ $label }}
                    </option>

                @endforeach

            </select>

        </div>


        <div>

            <label class="block font-medium mb-2">
                Waktu
            </label>

            <input
                type="time"
                name="prayer_time"
                value="{{ old(
                    'prayer_time',
                    substr(
                        $jadwalSholat->prayer_time,
                        0,
                        5
                    )
                ) }}"
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
                    value="{{ old(
                        'latitude',
                        $jadwalSholat->latitude
                    ) }}"
                    step="0.0000001"
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
                    value="{{ old(
                        'longitude',
                        $jadwalSholat->longitude
                    ) }}"
                    step="0.0000001"
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
                value="{{ old(
                    'source',
                    $jadwalSholat->source
                ) }}"
                class="w-full rounded-lg border-gray-300"
            >

        </div>


        <label class="flex items-center gap-3">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(
                    old(
                        'is_active',
                        $jadwalSholat->is_active
                    )
                )
            >

            <span>
                Jadwal aktif
            </span>

        </label>


        <div class="flex justify-end gap-3 pt-4 border-t">

            <a
                href="{{ route(
                    'jadwal-sholat.index',
                    [
                        'date' =>
                            $jadwalSholat
                                ->schedule_date
                                ->format('Y-m-d')
                    ]
                ) }}"
                class="px-5 py-2.5 bg-gray-200 rounded-lg"
            >
                Batal
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg"
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection