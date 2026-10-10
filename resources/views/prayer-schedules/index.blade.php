@extends('layouts.dashboard')

@section('title', 'Jadwal Sholat')
@section('page-title', 'Jadwal Sholat')

@section('content')

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

    <div>

        <h1 class="text-2xl font-bold">
            Jadwal Sholat
        </h1>

        <p class="text-gray-500 mt-1">
            Kelola jadwal sholat masjid.
        </p>

    </div>

    <a
        href="{{ route('jadwal-sholat.create') }}"
        class="px-4 py-2.5 bg-slate-900 text-white rounded-lg"
    >
        + Tambah Jadwal
    </a>

    <form action="{{ route('prayer-schedules.sync') }}"
      method="POST"
      class="inline">

        @csrf

        <button type="submit"
                class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
            Sinkronisasi API
        </button>

    </form>

</div>


@if(session('success'))

    <div class="mb-6 bg-green-100 text-green-700 p-4 rounded-lg">
        {{ session('success') }}
    </div>

@endif


{{-- FILTER TANGGAL --}}

<div class="bg-white rounded-xl shadow-sm p-5 mb-6">

    <form
        method="GET"
        action="{{ route('jadwal-sholat.index') }}"
        class="flex flex-col sm:flex-row gap-3 sm:items-end"
    >

        <div>

            <label class="block text-sm font-medium mb-2">
                Tanggal
            </label>

            <input
                type="date"
                name="date"
                value="{{ $date }}"
                class="rounded-lg border-gray-300"
            >

        </div>

        <button
            type="submit"
            class="px-5 py-2.5 bg-slate-900 text-white rounded-lg"
        >
            Tampilkan
        </button>

    </form>

</div>


{{-- TABLE --}}

<div class="bg-white rounded-xl shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 border-b">

                <tr>

                    <th class="px-6 py-4 text-left">
                        No
                    </th>

                    <th class="px-6 py-4 text-left">
                        Sholat
                    </th>

                    <th class="px-6 py-4 text-left">
                        Waktu
                    </th>

                    <th class="px-6 py-4 text-left">
                        Sumber
                    </th>

                    <th class="px-6 py-4 text-left">
                        Status
                    </th>

                    <th class="px-6 py-4 text-right">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y">

                @forelse($schedules as $schedule)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-semibold capitalize">
                            {{ $schedule->prayer_name }}
                        </td>

                        <td class="px-6 py-4 font-semibold">
                            {{ substr($schedule->prayer_time, 0, 5) }}
                        </td>

                        <td class="px-6 py-4 capitalize">
                            {{ $schedule->source ?? 'manual' }}
                        </td>

                        <td class="px-6 py-4">

                            @if($schedule->is_active)

                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                    Aktif
                                </span>

                            @else

                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                    Nonaktif
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <a
                                    href="{{ route(
                                        'jadwal-sholat.edit',
                                        $schedule
                                    ) }}"
                                    class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg"
                                >
                                    Edit
                                </a>


                                <form
                                    method="POST"
                                    action="{{ route(
                                        'jadwal-sholat.destroy',
                                        $schedule
                                    ) }}"
                                    onsubmit="return confirm('Hapus jadwal ini?')"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="px-6 py-12 text-center text-gray-400"
                        >
                            Belum ada jadwal untuk tanggal tersebut.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection