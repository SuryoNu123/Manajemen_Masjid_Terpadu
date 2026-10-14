@extends('layouts.dashboard')

@section('title', 'Presensi Jamaah')
@section('page-title', 'Presensi Jamaah')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Periksa kembali data presensi yang dikirim.
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <h2 class="text-xl font-bold text-slate-800">
            Pencatatan Presensi Salat
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Catat kehadiran jamaah berdasarkan tanggal dan waktu salat.
        </p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET"
              action="{{ route('prayer-attendances.index') }}"
              class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:items-end">

            <div>
                <label for="date"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Tanggal Presensi
                </label>

                <input id="date"
                       name="date"
                       type="date"
                       required
                       value="{{ $date }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none">
            </div>

            <div>
                <label for="prayer_name"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Waktu Salat
                </label>

                <select id="prayer_name"
                        name="prayer_name"
                        required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none">
                    @foreach ($prayers as $prayer)
                        <option value="{{ $prayer }}"
                            @selected($prayerName === $prayer)>
                            {{ ucfirst($prayer) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Tampilkan Jamaah
                </button>
            </div>
        </form>
    </div>

    <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
        <div>
            <h3 class="font-semibold text-slate-800">
                Daftar Kehadiran
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                &middot;
                Salat {{ ucfirst($prayerName) }}
            </p>
        </div>

        <p class="text-sm text-slate-500">
            {{ $jamaahs->count() }} jamaah aktif
        </p>
    </div>

    <form method="POST" action="{{ route('prayer-attendances.store') }}">
        @csrf

        <input type="hidden" name="attendance_date" value="{{ $date }}">
        <input type="hidden" name="prayer_name" value="{{ $prayerName }}">

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                No.
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Nama Jamaah
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Domisili
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Status Kehadiran
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($jamaahs as $jamaah)
                            @php
                                $currentStatus = old(
                                    'attendance.' . $jamaah->id,
                                    $attendanceMap[$jamaah->id] ?? ''
                                );
                            @endphp

                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-800">
                                    {{ $jamaah->name }}
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    {{ $jamaah->domicile }}
                                </td>

                                <td class="min-w-48 px-5 py-4">
                                    <select
                                        name="attendance[{{ $jamaah->id }}]"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-500 focus:outline-none">

                                        <option value=""
                                            @selected($currentStatus === '')>
                                            Belum dicatat
                                        </option>

                                        <option value="hadir"
                                            @selected($currentStatus === 'hadir')>
                                            Hadir
                                        </option>

                                        <option value="izin"
                                            @selected($currentStatus === 'izin')>
                                            Izin
                                        </option>

                                        <option value="sakit"
                                            @selected($currentStatus === 'sakit')>
                                            Sakit
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-5 py-10 text-center text-sm text-slate-500">
                                    Belum ada jamaah aktif.
                                    Tambahkan data jamaah terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col justify-between gap-3 border-t border-slate-200 bg-white px-5 py-4 sm:flex-row sm:items-center">
                <p class="text-xs text-slate-500">
                    Pilih status untuk setiap jamaah, lalu simpan.
                    Status "Belum dicatat" tidak menyimpan presensi.
                </p>

                @if ($jamaahs->isNotEmpty())
                    <button type="submit"
                            class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                        Simpan Presensi
                    </button>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection
