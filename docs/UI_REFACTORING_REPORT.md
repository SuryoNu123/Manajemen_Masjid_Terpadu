# Laporan Audit dan Refactoring UI

Tanggal: 22 Oktober 2026

## Cakupan audit

- `php artisan route:list --except-vendor` mendaftarkan 186 route. Route publik, auth, dashboard, role, modul, dan webhook diperiksa pada inventaris route; source `routes/web.php` menjadi sumber route web.
- Menginventarisasi controller, model, middleware, request, policy, view, aset frontend, migration, seeder, dan test lewat struktur proyek. Pemeriksaan source mendalam pada turn ini difokuskan pada beranda, layout publik/dashboard, sidebar per role, controller beranda, serta controller daftar yang terkait filter.
- Harness browser yang sudah ada mencakup 10 halaman publik, autentikasi, dashboard lima role, administrasi pengguna, notifikasi, profil, dan pengaturan jamaah. Ini bukan inspeksi manual seluruh 186 route.

## Prioritas temuan awal

1. **Tinggi:** kartu waktu sholat pada hero mempertahankan nama sholat dan jam dalam satu baris pada lebar ponsel yang sempit.
2. **Sedang:** tautan “Lihat Jadwal Lengkap” sebelumnya `href="#"`, sehingga tidak membawa pengguna ke jadwal.
3. **Sedang:** sidebar memiliki daftar panjang per role dan scrollbar disembunyikan walau nav dapat di-scroll.
4. **Sedang:** controller dan view dikelompokkan utamanya per modul, sementara route memakai controller bersama untuk beberapa akses; pemindahan folder memerlukan pemetaan route dan view yang lebih luas.
5. **Sedang:** pencarian/filter belum tersedia merata. Filter query server-side ditemukan di beberapa modul, tetapi belum konsisten di semua daftar.

## Perubahan yang diterapkan

- Hero jadwal sholat beralih ke susunan vertikal pada layar kecil, dengan padding lebih hemat; susunan dua kolom kembali pada layar `sm` ke atas. Data dan integrasi jadwal tetap memakai schedule aktual.
- CTA “Lihat Jadwal Lengkap” diarahkan ke elemen daftar jadwal sholat yang sudah ada (`#prayer-schedule-cards`).
- Scrollbar sidebar tidak lagi disembunyikan oleh CSS inline layout dashboard; navigasi tetap memakai scroll vertikal mandiri.
- Kelompok menu sidebar Ketua dan Sekretaris kini dapat dilipat memakai elemen disclosure native; kelompok untuk route aktif terbuka otomatis. Menu dan pemeriksaan hak akses tidak berubah.
- Tabel mustahik dibungkus dengan area `overflow-x-auto`, sehingga kolom tetap dapat dibaca dan digeser pada ponsel.
- Empat daftar kini memiliki filter GET tervalidasi dan berjalan di database: kegiatan (judul/penceramah/lokasi, jenis, status), petugas (nama, posisi, status aktif), mustahik (nama, asnaf, status penyaluran), serta laporan keuangan (tahun, jenis periode, status). Filter dapat digabung, memiliki reset, dan query dipertahankan oleh pagination.
- Controller yang murni publik, Ketua, Bendahara, dan Jamaah dipindahkan ke namespace role masing-masing. Controller yang menangani beberapa role tetap berada di namespace bersama agar business logic tidak diduplikasi.
- Route, middleware, nama route, query bisnis, dan skema database dipertahankan. Import route diperbarui untuk namespace baru.

## Halaman yang diuji melalui browser

Pada masing-masing ukuran desktop 1440×900, laptop 1366×768, tablet 768×1024, ponsel 390×844, dan ponsel kecil 360×800, suite membuka:

- Beranda, Profil publik, Kegiatan, Pengumuman, Artikel, Perpustakaan, Donasi, Zakat, Transparansi, dan Digital Signage.
- Login, registrasi, alur daftar/logout/login, serta profil.
- Dashboard Ketua, Bendahara, Sekretaris, Marbot, dan Jamaah.
- Administrasi pengguna, notifikasi, peminjaman buku jamaah, preferensi notifikasi, dan modal konfirmasi profil.

Suite melakukan pemeriksaan overflow dokumen, clipping kontrol di luar container scroll, navigasi hamburger/sidebar, status HTTP halaman, dan error JavaScript/console. Screenshot beranda hasil run disimpan di `artifacts/ui-refactor/home-390x844.png` dan `artifacts/ui-refactor/home-360x800.png`. Beranda desktop juga ditinjau langsung.

## Search, filter, pagination

Kode controller menunjukkan query search/filter server-side dan pagination telah digunakan pada sejumlah daftar, antara lain jamaah, aset, perawatan aset, buku, pengumuman, artikel dakwah, unit usaha, item stok, program/anggota remaja, santri/kelas TPQ, log aktivitas, penjualan, dan administrasi akun. Empat filter baru ditambahkan untuk kegiatan, petugas, mustahik, dan laporan keuangan. Sebagian besar paginator yang diperiksa memakai `withQueryString()`.

Filter pada setiap modul lain belum diaudit satu per satu di laporan ini. Filter tanggal/rentang, pencarian donasi, transaksi, presensi, dan laporan lain perlu pemeriksaan lanjutan terhadap kebutuhan yang tersedia di skema dan kontrak data modul terkait.

## Struktur Controller dan View

Controller yang dipindahkan:

- `Public/`: `PublicHomeController`, `PublicProfileController`, `PublicLibraryController`, `DigitalSignageController`, `TransparencyController`, `ZakatController`.
- `Ketua/`: `ActivityLogController`, `UserAdministrationController`, `BusinessUnitController`.
- `Bendahara/`: `MustahikController`.
- `Jamaah/`: `WhatsAppPreferenceController`.
- `Auth/` sudah menjadi kelompok sebelumnya.

Controller kegiatan, transaksi dan laporan keuangan, donasi, jadwal/presensi, aset, perpustakaan, peminjaman, serta konten tetap di namespace bersama karena route dan operasinya dipakai lebih dari satu role. Belum ada controller yang hanya dipakai Sekretaris atau Marbot: route mereka berbagi modul dengan Ketua. Tidak dibuat controller duplikat atau folder role kosong.

Views tetap dikelompokkan mengikuti modul yang digunakan saat ini, karena pemindahan view terpisah tidak diperlukan untuk perubahan namespace Controller ini.

## File yang diubah

- `resources/views/welcome.blade.php`
- `resources/views/layouts/dashboard.blade.php`
- `resources/views/layouts/sidebar/ketua.blade.php`
- `resources/views/layouts/sidebar/sekretaris.blade.php`
- `resources/views/activities/index.blade.php`
- `resources/views/officers/index.blade.php`
- `resources/views/mustahiks/index.blade.php`
- `resources/views/financial-reports/index.blade.php`
- Controller empat daftar tersebut
- Controller role-spesifik di `app/Http/Controllers/{Public,Ketua,Bendahara,Jamaah}/`
- `routes/web.php` (import controller)
- `tests/Feature/SearchAndFilteringTest.php`
- `tests/e2e/responsive-ui.spec.js`
- `docs/UI_REFACTORING_REPORT.md`
- Screenshot beranda baru di `artifacts/ui-refactor/`

## Hasil verifikasi

- `php artisan route:list --except-vendor`: berhasil, 186 route.
- `php artisan test --compact`: berhasil, 108 test dan 436 assertion.
- `php artisan test --compact`: berhasil setelah refactor namespace, 108 test dan 436 assertion.
- `php artisan view:cache`: berhasil.
- `npm.cmd run build`: berhasil dengan Vite 8.3.1 dan Tailwind CSS 4.3.3. (`npm` PowerShell shim awalnya diblokir execution policy; command yang sama berhasil lewat `npm.cmd`.)
- `node scripts/playwright-server.mjs`: 50 test lulus pada lima viewport sebelum skenario filter ditambahkan. Harness memakai SQLite sementara dan menghapus direktori temp saat selesai.
- Test browser sidebar Ketua: 5 test lulus pada lima viewport; memeriksa grup tertutup, buka/tutup, grup aktif, dan overflow.
- Test browser tambahan untuk mengirim/reset empat filter dan memeriksa overflow: 5 test lulus pada lima viewport. Run pertama menemukan tabel mustahik terpotong pada 390×844 dan 360×800; setelah dibungkus area gulir, run ulang lulus pada semua ukuran.
- Browser screenshot beranda desktop dan ponsel dibuka untuk pemeriksaan visual. Tidak seluruh screenshot halaman diperiksa satu per satu secara manual.

## Belum terselesaikan

- Pengelompokan Controller berbasis role sudah diterapkan untuk controller khusus; controller lintas role tetap bersama. Pemindahan View berbasis role tidak dilakukan karena view masih dikelompokkan menurut modul dan tidak diperlukan untuk mengubah namespace Controller.
- Search/filter belum merata. Filter yang sesuai skema masih perlu diaudit dan ditambahkan pada daftar lain yang memerlukannya, termasuk validasi date range dan filter pencarian pada transaksi/donasi/presensi/laporan.
- Pengelompokan menu Ketua dan Sekretaris kini dapat dilipat. Peninjauan apakah setiap item menu diperlukan untuk tiap role belum dilakukan seluruhnya.
- Audit manual UI dan perilaku untuk seluruh halaman dari 186 route belum tuntas; sebagian rute inventaris tidak punya skenario pada suite browser yang ada.
- Build menghasilkan artefak `public/build` lokal sesuai proses Vite. Screenshot yang sudah dilacak Git dipulihkan setelah run; hanya dua screenshot beranda baru yang disimpan sebagai artefak audit terpisah.
