<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Donasi - Masjid Terpadu</title>
    <link rel="icon" type="image/png" href="{{ asset('masjid-mark.png') }}?v=2">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 px-4 py-10 text-slate-900 sm:py-16">
    <main class="mx-auto max-w-2xl overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <header class="bg-slate-900 px-6 py-7 text-white sm:px-8">
            <p class="text-sm font-medium uppercase tracking-wider text-blue-200">Donasi Masjid Terpadu</p>
            <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ $donasi->payment_status === 'paid' ? 'Terima kasih atas donasi Anda' : 'Selesaikan pembayaran donasi' }}</h1>
            <p class="mt-2 text-slate-300">Informasi pembayaran dan status donasi Anda.</p>
        </header>

        <section class="space-y-6 p-6 sm:p-8">
            @if (session('error'))
                <div class="rounded-lg bg-amber-50 p-4 text-sm text-amber-900" role="alert">{{ session('error') }}</div>
            @endif

            <dl class="grid gap-4 rounded-xl bg-slate-50 p-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm text-slate-500">Nomor transaksi</dt>
                    <dd class="mt-1 break-all font-semibold">{{ $donasi->gateway_reference ?? $donasi->donation_code }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Nominal</dt>
                    <dd class="mt-1 text-lg font-bold">Rp {{ number_format($donasi->amount, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Nama donatur</dt>
                    <dd class="mt-1 font-medium">{{ $donasi->donor_name ?: 'Hamba Allah' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">Jenis donasi</dt>
                    <dd class="mt-1 font-medium capitalize">{{ $donasi->type }}</dd>
                </div>
                @if ($donasi->note)
                    <div class="sm:col-span-2">
                        <dt class="text-sm text-slate-500">Keterangan</dt>
                        <dd class="mt-1 font-medium">{{ $donasi->note }}</dd>
                    </div>
                @endif
                <div class="sm:col-span-2">
                    <dt class="text-sm text-slate-500">Status pembayaran</dt>
                    <dd class="mt-1">
                        @if ($donasi->payment_status === 'paid')
                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-800">Berhasil dibayar</span>
                            @if ($donasi->paid_at)
                                <span class="ml-2 text-sm text-slate-500">{{ $donasi->paid_at->format('d/m/Y H:i') }}</span>
                            @endif
                        @elseif ($donasi->payment_status === 'expired')
                            <span class="inline-flex rounded-full bg-slate-200 px-3 py-1 text-sm font-semibold text-slate-700">Kedaluwarsa</span>
                        @elseif ($donasi->payment_status === 'failed')
                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">Gagal</span>
                        @else
                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-800">Menunggu pembayaran</span>
                        @endif
                    </dd>
                </div>
            </dl>

            @if ($donasi->payment_method === 'qris' && $donasi->payment_status === 'pending')
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-5 text-center sm:p-6">
                    <h2 class="text-lg font-bold text-slate-900">Bayar dengan QRIS</h2>
                    @if ($gatewayReady && $donasi->gateway_qr_code_url)
                        <p class="mt-2 text-sm text-slate-600">Pindai kode QR dengan aplikasi pembayaran yang mendukung QRIS.</p>
                        <img src="{{ $donasi->gateway_qr_code_url }}" alt="QRIS Midtrans untuk transaksi {{ $donasi->donation_code }}"
                             class="mx-auto mt-5 h-64 w-64 rounded-lg bg-white object-contain p-2 ring-1 ring-slate-200 sm:h-72 sm:w-72">
                        <p class="mt-4 text-sm text-slate-600">Pastikan nominal pada aplikasi Anda sesuai: <strong>Rp {{ number_format($donasi->amount, 0, ',', '.') }}</strong>.</p>
                        @if ($donasi->payment_expires_at)
                            <p class="mt-2 text-sm font-medium text-slate-700">Berlaku sampai {{ $donasi->payment_expires_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
                        @endif
                        <ol class="mx-auto mt-5 max-w-md space-y-2 text-left text-sm text-slate-700">
                            <li>1. Buka aplikasi bank atau dompet digital yang mendukung QRIS.</li>
                            <li>2. Pilih menu pindai QR, lalu arahkan kamera ke kode di atas.</li>
                            <li>3. Periksa nama merchant dan nominal, kemudian konfirmasi pembayaran.</li>
                        </ol>
                    @elseif ($gatewayConfigured)
                        <p class="mt-3 text-sm text-amber-800">QRIS belum berhasil dibuat. Donasi belum dibayar. Silakan hubungi pengurus masjid dan sertakan kode {{ $donasi->donation_code }}.</p>
                    @else
                        <p class="mt-3 text-sm text-amber-800">Pembayaran QRIS belum berhasil disiapkan. Donasi ini belum dibayar.</p>
                    @endif
                </div>
            @elseif ($donasi->payment_status === 'paid')
                <div class="rounded-xl bg-blue-50 p-5 text-center text-blue-900">Pembayaran telah dikonfirmasi oleh Midtrans. Donasi Anda akan dicatat pada laporan keuangan masjid.</div>
            @elseif ($donasi->payment_status === 'expired' || $donasi->payment_status === 'failed')
                <div class="rounded-xl bg-slate-50 p-5 text-center text-slate-700">Transaksi ini tidak dapat dibayar lagi. Silakan buat donasi baru untuk mendapatkan QRIS yang berlaku.</div>
            @endif

            <div class="flex flex-col gap-3 sm:flex-row">
                @if ($donasi->payment_status === 'pending' && $donasi->gateway_reference)
                    <a href="{{ route('donasi.success', ['donasi' => $donasi, 'token' => $donasi->status_token]) }}" class="inline-flex flex-1 justify-center rounded-lg bg-blue-700 px-5 py-3 font-semibold text-white hover:bg-blue-800">Periksa status pembayaran</a>
                @endif
                <a href="{{ route('donasi.create') }}" class="inline-flex flex-1 justify-center rounded-lg border border-slate-300 px-5 py-3 font-semibold text-slate-800 hover:bg-slate-50">Donasi lagi</a>
            </div>
            <p class="text-center text-xs text-slate-500">Status pembayaran diperbarui otomatis setelah notifikasi terverifikasi dari Midtrans.</p>
        </section>
    </main>
</body>

</html>
