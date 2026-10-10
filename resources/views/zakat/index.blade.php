@extends('layouts.public')

@section('title', 'Kalkulator Zakat')

@section('content')

<div class="max-w-5xl mx-auto py-10 px-5">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Kalkulator Zakat - Masjid Terpadu
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="bg-gray-100 min-h-screen">


<div class="max-w-5xl mx-auto py-10 px-5">


    {{-- HEADER --}}

    <div class="text-center mb-10">

        <h1 class="text-3xl font-bold text-gray-900">
            Kalkulator Zakat
        </h1>

        <p class="text-gray-500 mt-2">
            Hitung estimasi zakat fitrah atau zakat maal
            berdasarkan parameter yang Anda masukkan.
        </p>

    </div>



    {{-- ERROR --}}

    @if($errors->any())

        <div class="mb-6 bg-red-100 border border-red-200 text-red-700 rounded-xl p-5">

            <ul class="list-disc ml-5">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif



    {{-- CARD --}}

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">


        {{-- TYPE TABS --}}

        <div class="border-b px-6 pt-6">

            <div class="flex gap-2">

                <button
                    type="button"
                    id="btn-fitrah"
                    onclick="showType('fitrah')"
                    class="px-5 py-3 rounded-t-lg font-semibold bg-slate-900 text-white"
                >
                    Zakat Fitrah
                </button>

                <button
                    type="button"
                    id="btn-maal"
                    onclick="showType('maal')"
                    class="px-5 py-3 rounded-t-lg font-semibold bg-gray-100 text-gray-700"
                >
                    Zakat Maal
                </button>

            </div>

        </div>



        {{-- FORM FITRAH --}}

        <div
            id="form-fitrah"
            class="p-8"
        >

            <form
                action="{{ route('zakat.calculate') }}"
                method="POST"
                class="space-y-6"
            >

                @csrf

                <input
                    type="hidden"
                    name="zakat_type"
                    value="fitrah"
                >


                {{-- JUMLAH ORANG --}}

                <div>

                    <label class="block font-semibold mb-2">
                        Jumlah Orang
                    </label>

                    <input
                        type="number"
                        name="jumlah_orang"
                        min="1"
                        value="{{ old('jumlah_orang', $oldData['jumlah_orang'] ?? 1) }}"
                        required
                        class="w-full rounded-lg border-gray-300"
                        placeholder="Contoh: 4"
                    >

                </div>


                {{-- NILAI PER ORANG --}}

                <div>

                    <label class="block font-semibold mb-2">
                        Nilai Zakat per Orang
                    </label>

                    <div class="relative">

                        <span class="absolute left-4 top-1/2 -translate-y-1/2">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="nilai_per_orang"
                            min="0"
                            step="0.01"
                            value="{{ old('nilai_per_orang', $oldData['nilai_per_orang'] ?? '') }}"
                            required
                            class="w-full rounded-lg border-gray-300 pl-12"
                            placeholder="Masukkan nilai sesuai kebijakan"
                        >

                    </div>

                </div>


                <button
                    type="submit"
                    class="w-full py-3 bg-slate-900 text-white rounded-lg font-semibold hover:bg-slate-800"
                >
                    Hitung Zakat Fitrah
                </button>

            </form>

        </div>



        {{-- FORM MAAL --}}

        <div
            id="form-maal"
            class="p-8 hidden"
        >

            <form
                action="{{ route('zakat.calculate') }}"
                method="POST"
                class="space-y-6"
            >

                @csrf

                <input
                    type="hidden"
                    name="zakat_type"
                    value="maal"
                >


                {{-- HARTA --}}

                <div>

                    <label class="block font-semibold mb-2">
                        Total Harta
                    </label>

                    <div class="relative">

                        <span class="absolute left-4 top-1/2 -translate-y-1/2">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="total_harta"
                            min="0"
                            step="0.01"
                            value="{{ old('total_harta', $oldData['total_harta'] ?? '') }}"
                            required
                            class="w-full rounded-lg border-gray-300 pl-12"
                            placeholder="Contoh: 100000000"
                        >

                    </div>

                </div>


                {{-- PENGURANG --}}

                <div>

                    <label class="block font-semibold mb-2">
                        Pengurang / Hutang
                    </label>

                    <div class="relative">

                        <span class="absolute left-4 top-1/2 -translate-y-1/2">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="pengurang"
                            min="0"
                            step="0.01"
                            value="{{ old('pengurang', $oldData['pengurang'] ?? 0) }}"
                            class="w-full rounded-lg border-gray-300 pl-12"
                        >

                    </div>

                </div>


                {{-- NISAB --}}

                <div>

                    <label class="block font-semibold mb-2">
                        Nisab
                    </label>

                    <div class="relative">

                        <span class="absolute left-4 top-1/2 -translate-y-1/2">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="nisab"
                            min="0"
                            step="0.01"
                            value="{{ old('nisab', $oldData['nisab'] ?? 0) }}"
                            class="w-full rounded-lg border-gray-300 pl-12"
                            placeholder="0 jika tidak menggunakan pengecekan nisab"
                        >

                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        Masukkan nilai sesuai kebijakan yang digunakan DKM.
                    </p>

                </div>


                {{-- PERSENTASE --}}

                <div>

                    <label class="block font-semibold mb-2">
                        Persentase Zakat
                    </label>

                    <div class="relative">

                        <input
                            type="number"
                            name="persentase"
                            min="0"
                            max="100"
                            step="0.01"
                            value="{{ old('persentase', $oldData['persentase'] ?? '') }}"
                            required
                            class="w-full rounded-lg border-gray-300 pr-12"
                            placeholder="Masukkan persentase"
                        >

                        <span class="absolute right-4 top-1/2 -translate-y-1/2">
                            %
                        </span>

                    </div>

                </div>


                <button
                    type="submit"
                    class="w-full py-3 bg-slate-900 text-white rounded-lg font-semibold hover:bg-slate-800"
                >
                    Hitung Zakat Maal
                </button>

            </form>

        </div>

    </div>



    {{-- HASIL --}}

    @isset($result)

        <div class="mt-8 bg-white rounded-2xl shadow-sm p-8">

            <h2 class="text-xl font-bold mb-6">
                Hasil Perhitungan
            </h2>


            @if(($oldData['zakat_type'] ?? null) === 'fitrah')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="bg-gray-50 rounded-xl p-5">

                        <p class="text-sm text-gray-500">
                            Jumlah Orang
                        </p>

                        <p class="text-2xl font-bold mt-2">
                            {{ $oldData['jumlah_orang'] ?? 0 }}
                        </p>

                    </div>


                    <div class="bg-gray-50 rounded-xl p-5">

                        <p class="text-sm text-gray-500">
                            Nilai per Orang
                        </p>

                        <p class="text-2xl font-bold mt-2">
                            Rp {{ number_format(
                                $oldData['nilai_per_orang'] ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}
                        </p>

                    </div>

                </div>


                <div class="mt-6 bg-slate-900 text-white rounded-xl p-6">

                    <p class="text-sm opacity-80">
                        Estimasi Total Zakat Fitrah
                    </p>

                    <p class="text-3xl font-bold mt-2">
                        Rp {{ number_format(
                            $result,
                            0,
                            ',',
                            '.'
                        ) }}
                    </p>

                </div>

            @else

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div class="bg-gray-50 rounded-xl p-5">

                        <p class="text-sm text-gray-500">
                            Total Harta
                        </p>

                        <p class="text-xl font-bold mt-2">
                            Rp {{ number_format(
                                $oldData['total_harta'] ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}
                        </p>

                    </div>


                    <div class="bg-gray-50 rounded-xl p-5">

                        <p class="text-sm text-gray-500">
                            Pengurang
                        </p>

                        <p class="text-xl font-bold mt-2">
                            Rp {{ number_format(
                                $oldData['pengurang'] ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}
                        </p>

                    </div>


                    <div class="bg-gray-50 rounded-xl p-5">

                        <p class="text-sm text-gray-500">
                            Harta Bersih
                        </p>

                        <p class="text-xl font-bold mt-2">
                            Rp {{ number_format(
                                $netAmount,
                                0,
                                ',',
                                '.'
                            ) }}
                        </p>

                    </div>

                </div>


                <div class="mt-5 p-4 rounded-xl
                    {{ $meetsNisab
                        ? 'bg-green-50 text-green-700'
                        : 'bg-yellow-50 text-yellow-700'
                    }}"
                >

                    @if($meetsNisab)

                        Harta bersih memenuhi parameter nisab
                        yang dimasukkan.

                    @else

                        Harta bersih belum memenuhi parameter nisab
                        yang dimasukkan.

                    @endif

                </div>


                <div class="mt-5 bg-slate-900 text-white rounded-xl p-6">

                    <p class="text-sm opacity-80">
                        Estimasi Total Zakat Maal
                    </p>

                    <p class="text-3xl font-bold mt-2">
                        Rp {{ number_format(
                            $result,
                            0,
                            ',',
                            '.'
                        ) }}
                    </p>

                </div>

            @endif


            <div class="mt-6">

                <a
                    href="{{ route('zakat.index') }}"
                    class="inline-block px-5 py-2.5 bg-gray-200 rounded-lg"
                >
                    Hitung Lagi
                </a>

            </div>

        </div>

    @endisset



    {{-- KEMBALI --}}

    <div class="text-center mt-8">

        <a
            href="{{ url('/') }}"
            class="text-sm text-gray-500 hover:text-gray-800"
        >
            ← Kembali ke halaman utama
        </a>

    </div>

</div>


<script>

    function showType(type) {

        const fitrah =
            document.getElementById('form-fitrah');

        const maal =
            document.getElementById('form-maal');

        const btnFitrah =
            document.getElementById('btn-fitrah');

        const btnMaal =
            document.getElementById('btn-maal');


        if (type === 'fitrah') {

            fitrah.classList.remove('hidden');
            maal.classList.add('hidden');

            btnFitrah.classList.remove(
                'bg-gray-100',
                'text-gray-700'
            );

            btnFitrah.classList.add(
                'bg-slate-900',
                'text-white'
            );

            btnMaal.classList.remove(
                'bg-slate-900',
                'text-white'
            );

            btnMaal.classList.add(
                'bg-gray-100',
                'text-gray-700'
            );

        } else {

            maal.classList.remove('hidden');
            fitrah.classList.add('hidden');

            btnMaal.classList.remove(
                'bg-gray-100',
                'text-gray-700'
            );

            btnMaal.classList.add(
                'bg-slate-900',
                'text-white'
            );

            btnFitrah.classList.remove(
                'bg-slate-900',
                'text-white'
            );

            btnFitrah.classList.add(
                'bg-gray-100',
                'text-gray-700'
            );

        }

    }

</script>

</body>
@endsection