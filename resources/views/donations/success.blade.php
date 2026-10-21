<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Donasi Berhasil</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="bg-gray-100 min-h-screen">

<div class="max-w-xl mx-auto py-16 px-5">

    <div class="bg-white rounded-2xl shadow-sm p-8 text-center">

        <div class="text-5xl mb-5">
            ✓
        </div>

        <h1 class="text-2xl font-bold">
            Donasi Berhasil Dibuat
        </h1>

        <p class="text-gray-500 mt-2">
            Silakan lanjutkan pembayaran sesuai metode yang dipilih.
        </p>


        <div class="bg-gray-50 rounded-xl p-5 text-left mt-8">

            <div class="flex justify-between mb-3">

                <span class="text-gray-500">
                    Kode Donasi
                </span>

                <span class="font-semibold">
                    {{ $donasi->donation_code }}
                </span>

            </div>


            <div class="flex justify-between mb-3">

                <span class="text-gray-500">
                    Jenis
                </span>

                <span class="font-semibold capitalize">
                    {{ $donasi->type }}
                </span>

            </div>


            <div class="flex justify-between mb-3">

                <span class="text-gray-500">
                    Nominal
                </span>

                <span class="font-bold">
                    Rp {{ number_format(
                        $donasi->amount,
                        0,
                        ',',
                        '.'
                    ) }}
                </span>

            </div>


            <div class="flex justify-between">

                <span class="text-gray-500">
                    Status
                </span>

                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">
                    Pending
                </span>

            </div>

        </div>


        @if($donasi->payment_method === 'qris')

            <div class="mt-8 bg-blue-50 p-5 rounded-xl">

                <h2 class="font-semibold text-blue-900">
                    Pembayaran QRIS
                </h2>

                @if ($gatewayReady)
                    @if ($donasi->gateway_qr_code_url)
                        <img src="{{ $donasi->gateway_qr_code_url }}" alt="Kode QRIS pembayaran" class="mx-auto mt-4 max-h-72 rounded-lg">
                    @endif
                    @if ($donasi->gateway_payment_url)
                        <a href="{{ $donasi->gateway_payment_url }}" class="mt-4 inline-flex rounded-lg bg-blue-700 px-4 py-2 text-white" rel="noopener noreferrer">Lanjutkan pembayaran</a>
                    @endif
                    @if ($donasi->payment_expires_at)
                        <p class="mt-3 text-xs text-blue-700">Batas pembayaran: {{ $donasi->payment_expires_at->format('d/m/Y H:i') }}</p>
                    @endif
                @else
                    <p class="text-sm text-blue-700 mt-2">
                        Pembayaran QRIS belum berhasil disiapkan oleh provider payment gateway. Donasi ini belum dibayar; silakan hubungi pengurus atau coba lagi nanti.
                    </p>
                @endif

            </div>

        @elseif($donasi->payment_method === 'transfer')

            <div class="mt-8 bg-gray-50 p-5 rounded-xl">

                <h2 class="font-semibold">
                    Pembayaran Transfer
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Detail rekening masjid akan dikonfigurasi
                    pada tahap payment integration.
                </p>

            </div>

        @elseif($donasi->payment_method === 'cash')

            <div class="mt-8 bg-gray-50 p-5 rounded-xl">

                <h2 class="font-semibold">
                    Pembayaran Tunai
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Silakan menyerahkan donasi kepada pengurus masjid.
                </p>

            </div>

        @endif


        <a
            href="{{ route('donasi.create') }}"
            class="inline-block mt-8 px-5 py-3 rounded-lg bg-slate-900 text-white"
        >
            Donasi Lagi
        </a>

    </div>

</div>

</body>

</html>
