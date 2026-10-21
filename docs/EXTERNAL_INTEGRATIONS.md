# Integrasi Eksternal

## Status

Belum ada penyedia payment gateway atau WhatsApp yang dipilih di konfigurasi proyek. Implementasi memiliki kontrak adapter dan alur yang dapat diuji dengan fake provider, tetapi belum mengirim pembayaran atau pesan nyata. Jangan menganggap transaksi/pesan simulasi sebagai keberhasilan provider.

## Payment gateway / QRIS

Konfigurasi yang tersedia:

```dotenv
PAYMENT_GATEWAY_ADAPTER=
```

Nilai harus berupa nama kelas adapter PHP yang mengimplementasikan `App\Services\Payments\PaymentGatewayProvider`. Adapter wajib membuat instruksi pembayaran melalui SDK/API resmi provider dan mengimplementasikan verifikasi autentisitas callback sesuai dokumentasi resmi provider. Format signature, URL API, dan field provider tidak ditentukan sampai penyedia dipilih.

Webhook aplikasi: `POST /webhooks/payments`. Daftarkan URL HTTPS publik ini pada dashboard provider setelah memasang adapter dan konfigurasi rahasia provider di environment server. Callback tidak boleh dianggap sah tanpa verifikasi adapter. Pembayaran tercatat sebagai pemasukan hanya setelah event `paid` tervalidasi; callback duplikat diproses idempoten.

Langkah sandbox setelah provider disetujui:

1. Buat akun sandbox dan kredensial sesuai dokumentasi resmi provider.
2. Simpan kredensial hanya sebagai environment variables di server; jangan menaruhnya di repository atau frontend.
3. Implementasikan adapter terhadap kontrak proyek dengan SDK/API resmi versi yang dipilih provider.
4. Arahkan callback sandbox ke endpoint webhook HTTPS aplikasi dan verifikasi signature menggunakan mekanisme resmi provider.
5. Uji pembayaran sukses, gagal, kedaluwarsa, callback palsu, callback berulang, nominal/currency salah, dan callback terlambat.
6. Cocokkan tepat satu transaksi keuangan untuk setiap donasi berhasil sebelum berpindah ke produksi.

Belum ada nama environment variable provider-spesifik karena provider belum dipilih. Tambahkan nama yang diwajibkan provider hanya saat adapter tersebut dibuat.

## WhatsApp

Konfigurasi yang tersedia:

```dotenv
WHATSAPP_ADAPTER=
WHATSAPP_MODE=disabled
```

Mode:

- `disabled`: tidak mengirim; status menunjukkan provider belum dikonfigurasi.
- `simulated`: membentuk dan mencatat payload simulasi, tanpa mengirim pesan nyata.
- `provider`: mengantrekan pengiriman melalui kelas adapter yang mengimplementasikan `App\Services\WhatsApp\WhatsAppProvider`.

Pilih provider dan implementasikan adapter menggunakan API/SDK resmi sebelum mengaktifkan `provider`. Simpan token provider pada environment server, bukan source code atau log. Pengiriman hanya ditujukan ke jamaah aktif yang menyetujui komunikasi WhatsApp dan memiliki nomor valid. Perubahan opt-out diperiksa kembali oleh job sebelum pengiriman.

Worker untuk antrean database:

```bash
php artisan queue:work --tries=3
```

Di produksi, jalankan worker sebagai proses terkelola dan pantau kegagalan job. Sebelum UAT, uji payload dengan mode simulasi, lalu sandbox provider jika tersedia, termasuk timeout, error provider, retry, opt-out, dan deduplikasi.

## Reset password email

Fitur reset password memakai mailer Laravel yang telah dikonfigurasi. Pastikan mailer server aktif untuk mengirim tautan reset; jangan mengirim password melalui admin atau log.
