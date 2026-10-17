# Data Demo untuk Pengujian

## Batas keamanan dan cara menjalankan

`DemoDataSeeder` sengaja tidak dipanggil oleh `DatabaseSeeder`. Seeder berjalan pada SQLite testing/development terisolasi. Untuk database aplikasi lokal, seeding hanya diizinkan jika environment adalah `local`, koneksi MySQL memakai host loopback, database aktif dan `DEMO_DATA_DATABASE` keduanya tepat `masjid_terpadu`, serta `DEMO_DATA_SEEDING=true`. Seeder bekerja dalam transaksi dan hanya menambah/mengenali baris bertanda demo; tidak menghapus atau memperbarui data yang sudah ada. Jangan gunakan opsi ini pada database produksi atau database bersama.

Contoh PowerShell untuk membuat database demo terpisah di luar database aplikasi:

```powershell
$env:APP_ENV = 'local'
$env:DEMO_DATA_SEEDING = 'true'
$env:DB_CONNECTION = 'sqlite'
$env:DB_DATABASE = (Join-Path $PWD 'storage/app/private/masjid-demo.sqlite')
$env:DB_URL = ''
New-Item -ItemType File -Path $env:DB_DATABASE -ErrorAction SilentlyContinue
php artisan migrate --force --no-interaction
php artisan db:seed --class=DemoDataSeeder --force --no-interaction
```

Untuk melihat UI dengan database tersebut, jalankan `php artisan serve` pada terminal yang tetap memakai variabel lingkungan yang sama. Untuk mengulang seeding, jalankan hanya perintah `db:seed` tersebut lagi. Seeder memakai penanda `Demo:`/`DEMO-` dan tidak memperbarui akun, transaksi, atau baris yang sudah ada. File SQLite contoh disimpan di `storage/app/private/`, yang dikecualikan Git.

Untuk mengisi database MySQL lokal aplikasi yang sudah dipastikan bernama `masjid_terpadu`, aktifkan perlindungan hanya pada terminal seeding:

```powershell
$env:DEMO_DATA_SEEDING = 'true'
$env:DEMO_DATA_DATABASE = 'masjid_terpadu'
php artisan db:seed --class=DemoDataSeeder --force --no-interaction
Remove-Item Env:DEMO_DATA_SEEDING
Remove-Item Env:DEMO_DATA_DATABASE
```

Setelah seeding berhasil, data tersimpan di MySQL lokal dan dapat ditampilkan dengan `php artisan serve` tanpa mengganti koneksi `.env`. Sebagian angka dashboard dan daftar transaksi akan mencakup baris simulasi yang ditandai `Demo:`/`DEMO-`. Laporan simulasi hanya menghitung transaksi demo agar laporan keuangan yang sudah ada tidak tercampur. Akun simulasi memakai email `@masjid.test`; kata sandi hanya berada di file lokal `storage/app/private/DEMO_CREDENTIALS.txt`.

Sebelum menjalankan, verifikasi koneksi aktif dan nama database dengan `php artisan config:show database.default`, `php artisan config:show database.connections.mysql.database`, dan `php artisan db:show --counts`. Untuk SQLite, pastikan file target memang berkas demo khusus. Untuk MySQL, gunakan hanya database lokal `masjid_terpadu` dengan opt-in eksplisit di atas. Seeder akan menolak target lain.

## Seeder

| Seeder | Isi |
| --- | --- |
| `RoleSeeder` | Lima role aplikasi; hanya membuat role yang belum ada. |
| `UserSeeder` | Empat akun demo pengurus, hanya membuat akun bila email demo belum ada. Kata sandi akun yang sudah ada tidak diubah. Berjalan hanya pada SQLite testing atau SQLite demo lokal yang diaktifkan secara eksplisit. |
| `FinancialCategorySeeder` | Sembilan kategori pemasukan dan pengeluaran, dicari berdasarkan nama dan jenis sebelum dibuat. |
| `PrayerScheduleSeeder` | Jadwal lima waktu untuk hari ini hanya jika slot tersebut belum ada. |
| `DemoDataSeeder` | Orkestrator seluruh data demo aplikasi; khusus SQLite yang sudah diizinkan. |

Akun pengurus demo memakai alamat `@masjid.test`. Kredensial lokal disimpan pada `storage/app/private/DEMO_CREDENTIALS.txt`, folder tersebut dikecualikan Git; jangan salin kredensial itu ke database atau lingkungan produksi. Akun demo hanya dibuat pada database testing atau database demo SQLite yang diaktifkan secara eksplisit. `DatabaseSeeder` tidak akan membuat akun demo ketika memakai MySQL.

## Data dan relasi

- 24 jamaah fiktif, termasuk status aktif dan nonaktif; 144 presensi terhubung ke jamaah dan pencatat.
- 70 slot jadwal sholat untuk rentang tanggal lampau dan mendatang; slot hari ini yang telah ada dipertahankan.
- 27 transaksi: 11 penerimaan donasi terverifikasi, 8 pengeluaran, dan 8 omzet unit usaha. Status transaksi memuat approved dan draft.
- 15 donasi (11 paid dan tertaut ke satu transaksi keuangan masing-masing; 4 pending tanpa transaksi), 8 mustahik dengan delapan asnaf dan status distribusi berbeda.
- Laporan bulanan berstatus published dihitung setelah seluruh transaksi demo dibuat, memakai transaksi non-rejected untuk periode yang tersedia. Database demo baru mendapat satu laporan pada seeding pertama; seeder berikutnya mengenali laporan demo tersebut dan tidak menambah laporan baru. Periode yang sudah memiliki laporan tetap dipertahankan.
- 12 aset dengan kondisi berbeda dan 6 jadwal/riwayat pemeliharaan.
- 2 unit usaha, 6 barang stok, dan 8 penjualan; setiap penjualan terhubung ke transaksi pemasukan satu kali.
- 12 buku dan 5 peminjaman yang merujuk pada buku dan jamaah.
- 3 petugas, 12 kegiatan dengan tanggal lampau/mendatang dan penugasan petugas.
- 8 anggota remaja dan 6 program kerja yang mengacu pada anggota.
- 3 kelas TPQ, 15 santri, dan 60 presensi yang terhubung ke kelas/santri.
- 5 artikel dakwah dan 5 pengumuman; masing-masing memuat status draft serta publikasi. Tidak ada pengiriman notifikasi eksternal.

Database contoh terisolasi saat ini berisi 2 laporan bulanan pada periode yang berbeda. Keduanya terbentuk saat pengujian pengulangan awal sebelum guard laporan final ditambahkan; pada database baru seeder membuat satu laporan, dan pengulangan versi final tidak menambah laporan atau data lain.

Donasi paid demo tertaut ke transaksi kas pemasukan, sehingga nominalnya tidak perlu ditambahkan lagi dari tabel donasi saat menghitung saldo. Donasi pending tidak memiliki transaksi keuangan. Mustahik belum memiliki foreign key transaksi penyaluran di skema saat ini; jumlah penyaluran tetap dicatat pada modul mustahik dan tidak direpresentasikan sebagai transaksi kas kedua. QRIS/payment gateway nyata juga belum terintegrasi, sehingga status paid di sini hanya data simulasi.

## Modul yang belum memiliki penyimpanan khusus

Skema saat ini tidak memiliki tabel/model galeri foto/video maupun pengaturan profil masjid yang dapat diisi seeder. Profil publik memakai implementasi yang ada dan tidak dibuatkan tabel baru. Data kalkulator zakat dihitung dari input, bukan disimpan sebagai riwayat. Notifikasi demo tidak dibuat karena tidak dibutuhkan untuk menampilkan modul dan sistem tidak mengirim pesan eksternal.

## Verifikasi implementasi

- `php artisan test --compact`: 67 tes lulus, 277 assertion. Tes seeder membandingkan jumlah seluruh modul setelah seeding dua kali dan memeriksa relasi donasi/omzet serta total laporan.
- `vendor/bin/pint --dirty --format agent`: lulus.
- `php artisan route:list --except-vendor`: 168 route terdaftar.
- Database demo SQLite terpisah berhasil dimigrasikan; semua migration berstatus `Ran`. `DemoDataSeeder` dijalankan berulang tanpa menambah data baru setelah penanda laporannya tersedia.
- `npm.cmd run test:e2e`: 27 tes browser lulus pada desktop, tablet, dan ponsel. Setelah perbaikan tabel responsif, 18 tes halaman publik dan dashboard juga lulus, termasuk uji scroll kolom terakhir.
- `npm.cmd run build`: Vite berhasil membangun CSS/JS setelah penambahan lebar minimum dan petunjuk scroll tabel.
- Screenshot tersimpan di `artifacts/ui-audit/desktop/`, `artifacts/ui-audit/tablet/`, dan `artifacts/ui-audit/mobile/`. Screenshot dashboard, transparansi, halaman publik, dan tabel ponsel diperiksa secara visual.
- Database demo SQLite terisolasi tetap tersedia pada `storage/app/private/masjid-demo.sqlite`; uji browser otomatis sebelumnya menggunakan SQLite sementara.

## Pengisian database MySQL lokal untuk `php artisan serve`

Atas permintaan pengguna, seeder dijalankan pada koneksi lokal `127.0.0.1`, database `masjid_terpadu` (environment `local`) dengan kedua opt-in eksplisit. `.env` tidak dialihkan ke SQLite. Seeding dilakukan di dalam transaksi dan dijalankan dua kali; jumlah baris tidak bertambah pada pengulangan kedua.

Total baris sesudah pengisian (data lama tetap dipertahankan): 24 jamaah, 90 jadwal sholat, 144 presensi jamaah, 39 transaksi keuangan (27 bertanda demo), 20 donasi (15 demo), 11 mustahik (8 demo), 3 laporan keuangan (1 simulasi), 18 kegiatan, 7 petugas, 13 aset, 6 pemeliharaan, 2 unit usaha, 6 barang usaha, 8 penjualan, 13 buku, 5 peminjaman, 8 anggota remaja, 6 program remaja, 3 kelas TPQ, 15 santri, 60 presensi santri, 5 artikel, dan 5 pengumuman. Tabel role tetap 5 baris, kategori keuangan tetap 9, dan user tetap 4; seeder tidak mengganti akun yang telah ada.

Halaman `/`, `/kegiatan`, `/pengumuman`, `/artikel`, dan `/transparansi` merespons HTTP 200 setelah seeding. `/dashboard` merespons redirect 302 untuk pengunjung anonim, sehingga autentikasi tetap aktif. Laporan simulasi hanya menghitung transaksi bertanda `DEMO-`; data transaksi/laporan lama tidak ditimpa. Dashboard atau daftar transaksi yang mengagregasi semua transaksi dapat ikut menampilkan nominal simulasi yang memang ditambahkan untuk pengujian.
