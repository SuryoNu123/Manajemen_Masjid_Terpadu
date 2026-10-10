<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Donasi - Masjid Terpadu</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="bg-gray-100 min-h-screen">

<div class="max-w-3xl mx-auto py-12 px-5">

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        {{-- HEADER --}}

        <div class="bg-slate-900 text-white p-8">

            <h1 class="text-3xl font-bold">
                Donasi Masjid
            </h1>

            <p class="text-slate-300 mt-2">
                Salurkan donasi untuk mendukung kegiatan masjid.
            </p>

        </div>


        {{-- FORM --}}

        <form
            method="POST"
            action="{{ route('donasi.store') }}"
            class="p-8 space-y-6"
        >

            @csrf


            @if($errors->any())

                <div class="bg-red-100 text-red-700 rounded-lg p-4">

                    <ul class="list-disc ml-5">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- JENIS DONASI --}}

            <div>

                <label class="block font-semibold mb-2">
                    Jenis Donasi
                </label>

                <select
                    name="type"
                    required
                    class="w-full rounded-lg border-gray-300"
                >

                    <option value="">
                        Pilih jenis donasi
                    </option>

                    <option value="infaq"
                        @selected(old('type') === 'infaq')>
                        Infaq
                    </option>

                    <option value="sedekah"
                        @selected(old('type') === 'sedekah')>
                        Sedekah
                    </option>

                    <option value="zakat"
                        @selected(old('type') === 'zakat')>
                        Zakat
                    </option>

                    <option value="qurban"
                        @selected(old('type') === 'qurban')>
                        Qurban
                    </option>

                </select>

            </div>


            {{-- NAMA --}}

            <div>

                <label class="block font-semibold mb-2">
                    Nama Donatur
                </label>

                <input
                    type="text"
                    name="donor_name"
                    value="{{ old('donor_name') }}"
                    placeholder="Nama Anda"
                    class="w-full rounded-lg border-gray-300"
                >

            </div>


            {{-- EMAIL --}}

            <div>

                <label class="block font-semibold mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="donor_email"
                    value="{{ old('donor_email') }}"
                    placeholder="email@example.com"
                    class="w-full rounded-lg border-gray-300"
                >

            </div>


            {{-- NOMOR HP --}}

            <div>

                <label class="block font-semibold mb-2">
                    Nomor HP
                </label>

                <input
                    type="text"
                    name="donor_phone"
                    value="{{ old('donor_phone') }}"
                    placeholder="08xxxxxxxxxx"
                    class="w-full rounded-lg border-gray-300"
                >

            </div>


            {{-- NOMINAL --}}

            <div>

                <label class="block font-semibold mb-2">
                    Nominal Donasi
                </label>

                <div class="relative">

                    <span class="absolute left-4 top-1/2 -translate-y-1/2">
                        Rp
                    </span>

                    <input
                        type="number"
                        name="amount"
                        value="{{ old('amount') }}"
                        min="1000"
                        required
                        placeholder="50000"
                        class="w-full rounded-lg border-gray-300 pl-12"
                    >

                </div>

            </div>


            {{-- METODE PEMBAYARAN --}}

            <div>

                <label class="block font-semibold mb-2">
                    Metode Pembayaran
                </label>

                <select
                    name="payment_method"
                    required
                    class="w-full rounded-lg border-gray-300"
                >

                    <option value="">
                        Pilih metode pembayaran
                    </option>

                    <option value="qris">
                        QRIS
                    </option>

                    <option value="transfer">
                        Transfer Bank
                    </option>

                    <option value="cash">
                        Tunai
                    </option>

                    <option value="other">
                        Lainnya
                    </option>

                </select>

            </div>


            {{-- BUTTON --}}

            <button
                type="submit"
                class="w-full py-3 rounded-lg bg-slate-900 text-white font-semibold hover:bg-slate-800"
            >
                Lanjutkan Donasi
            </button>

        </form>

    </div>


    <div class="text-center mt-5">

        <a
            href="{{ url('/') }}"
            class="text-sm text-gray-500 hover:text-gray-800"
        >
            ← Kembali ke halaman utama
        </a>

    </div>

</div>

</body>

</html>