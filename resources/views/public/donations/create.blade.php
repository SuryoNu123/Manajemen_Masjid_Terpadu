<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Donasi - Masjid Terpadu</title>
    <link rel="icon" type="image/png" href="{{ asset('masjid-mark.png') }}?v=2">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="min-h-screen bg-slate-100 text-slate-900">

<div class="max-w-3xl mx-auto py-12 px-5">

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

        {{-- HEADER --}}

        <div class="bg-slate-900 p-8 text-white">

            <h1 class="text-3xl font-bold">
                Donasi Masjid
            </h1>

            <p class="mt-2 text-slate-300">
                Salurkan donasi dengan aman melalui QRIS atau metode yang tersedia.
            </p>

        </div>


        {{-- FORM --}}

        <form
            method="POST"
            action="{{ route('donasi.store') }}"
            class="space-y-6 p-6 sm:p-8"
        >

            @csrf


            @if($errors->any())

                <div class="rounded-lg bg-red-50 p-4 text-red-700" role="alert">

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
                    class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600"
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
                    class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600"
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
                    class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600"
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
                    class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600"
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
                        class="w-full rounded-lg border-slate-300 pl-12 focus:border-blue-600 focus:ring-blue-600"
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
                    class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600"
                >

                    <option value="">
                        Pilih metode pembayaran
                    </option>

                    <option value="qris" @selected(old('payment_method', 'qris') === 'qris')>
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

            <div>
                <label for="note" class="mb-2 block font-semibold">Pesan atau keterangan <span class="font-normal text-slate-500">(opsional)</span></label>
                <textarea id="note" name="note" rows="3" maxlength="1000" placeholder="Contoh: untuk renovasi ruang wudu"
                    class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600">{{ old('note') }}</textarea>
            </div>


            {{-- BUTTON --}}

            <button
                type="submit"
                class="w-full rounded-lg bg-blue-700 py-3 font-semibold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Bayar Donasi
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
