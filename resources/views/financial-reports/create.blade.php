@extends('layouts.dashboard')

@section('title', 'Buat Laporan')
@section('page-title', 'Buat Laporan Keuangan')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Buat Laporan Keuangan
        </h1>

        <p class="text-gray-500 mt-1">
            Sistem akan menghitung laporan berdasarkan transaksi
            yang tercatat pada periode yang dipilih.
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
        action="{{ route('laporan-keuangan.generate') }}"
        class="bg-white rounded-xl shadow-sm p-6 space-y-6"
    >

        @csrf


        {{-- PERIODE --}}

        <div>

            <label class="block font-medium mb-2">
                Jenis Periode
            </label>

            <select
                name="period_type"
                id="period_type"
                required
                class="w-full rounded-lg border-gray-300"
            >

                <option
                    value="monthly"
                    @selected(old('period_type', 'monthly') === 'monthly')
                >
                    Bulanan
                </option>

                <option
                    value="yearly"
                    @selected(old('period_type') === 'yearly')
                >
                    Tahunan
                </option>

            </select>

        </div>


        {{-- BULAN --}}

        <div id="monthField">

            <label class="block font-medium mb-2">
                Bulan
            </label>

            <select
                name="month"
                class="w-full rounded-lg border-gray-300"
            >

                @foreach([
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ] as $number => $name)

                    <option
                        value="{{ $number }}"
                        @selected(
                            old(
                                'month',
                                now()->month
                            ) == $number
                        )
                    >
                        {{ $name }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- TAHUN --}}

        <div>

            <label class="block font-medium mb-2">
                Tahun
            </label>

            <input
                type="number"
                name="year"
                value="{{ old('year', now()->year) }}"
                min="2020"
                max="2100"
                required
                class="w-full rounded-lg border-gray-300"
            >

        </div>


        <div class="bg-blue-50 text-blue-700 p-4 rounded-lg text-sm">

            Laporan akan dihitung secara otomatis dari transaksi
            keuangan yang sudah tercatat.

        </div>


        <div class="flex justify-end gap-3 pt-4 border-t">

            <a
                href="{{ route('laporan-keuangan.index') }}"
                class="px-5 py-2.5 bg-gray-200 rounded-lg"
            >
                Batal
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 bg-slate-900 text-white rounded-lg"
            >
                Generate Laporan
            </button>

        </div>

    </form>

</div>


@push('scripts')

<script>

    const periodType =
        document.getElementById('period_type');

    const monthField =
        document.getElementById('monthField');

    function toggleMonth() {

        if (periodType.value === 'monthly') {

            monthField.style.display = 'block';

        } else {

            monthField.style.display = 'none';

        }

    }

    periodType.addEventListener(
        'change',
        toggleMonth
    );

    toggleMonth();

</script>

@endpush

@endsection