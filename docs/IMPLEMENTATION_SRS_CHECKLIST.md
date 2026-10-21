# Checklist Implementasi SRS — Sistem Manajemen Masjid Terpadu

Dokumen ini berisi pemetaan seluruh kebutuhan fungsional dan non-fungsional dari dokumen **SRS - Sistem Manajemen Masjid Terpadu** terhadap implementasi aktual kode program, basis data, pengujian, dan tindak lanjut.

---

## 1. MODUL AUTENTIKASI & MANAJEMEN AKSES

| Kode SRS | Kebutuhan Fungsi | Modul Terkait | File Implementasi | Status Implementasi | Hasil Pengujian | Catatan & Tindak Lanjut |
|---|---|---|---|---|---|---|
| **AUT-F-001** | Login Multi-Role | Autentikasi | `app/Http/Controllers/Auth/AuthenticatedSessionController.php`, `app/Http/Controllers/DashboardController.php`, `app/Http/Middleware/RoleMiddleware.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Login mengarahkan masing-masing role (ketua, bendahara, sekretaris, marbot, jamaah) ke dashboard masing-masing. Jamaah & publik dapat akses publik tanpa login. |
| **AUT-F-002** | Hak Akses Berjenjang (RBAC) | Autentikasi | `app/Http/Middleware/RoleMiddleware.php`, `app/Http/Controllers/UserAdministrationController.php`, `routes/web.php` | SUDAH SESUAI | Diuji pada `UserAdministrationTest` | Ketua dapat mengelola akun/role; akun pengurus hanya dapat dibuat lewat alur Ketua; perubahan role sendiri dan penonaktifan Ketua aktif terakhir ditolak. |
| **AUT-F-003** | Reset Password | Autentikasi | `app/Http/Controllers/Auth/PasswordResetLinkController.php`, `app/Http/Controllers/Auth/NewPasswordController.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Fitur reset password Laravel Breeze via email/token. |
| **AUT-F-004** | Log Aktivitas Pengurus | Autentikasi & Audit | `app/Models/ActivityLog.php`, `app/Services/ActivityLogger.php`, `app/Listeners/LogSuccessfulLogin.php`, `resources/views/activity-logs/index.blade.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Mencakup log yang sudah ada dan tindakan administratif akun sensitif. |

---

## 2. MODUL A — KEUANGAN, DONASI DAN ZAKAT

| Kode SRS | Kebutuhan Fungsi | Modul Terkait | File Implementasi | Status Implementasi | Hasil Pengujian | Catatan & Tindak Lanjut |
|---|---|---|---|---|---|---|
| **KEU-F-001** | Kelola Kas Masuk/Keluar | Modul A | `app/Http/Controllers/FinancialTransactionController.php`, `app/Models/FinancialTransaction.php`, `app/Models/FinancialCategory.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Transaksi per kategori (operasional, sosial, perawatan, infaq, dll). Hanya bendahara yang dapat mengubah/membuat. |
| **KEU-F-002** | Donasi Digital (QRIS/PG) | Modul A | `app/Http/Controllers/DonationController.php`, `app/Http/Controllers/PaymentWebhookController.php`, `app/Services/Payments/` | MENUNGGU KONFIGURASI EKSTERNAL | Kontrak, webhook palsu, idempotensi diuji di `PaymentIntegrationTest`; provider riil belum diuji | Alur pending, verifikasi webhook melalui adapter, pencatatan kas satu kali setelah pembayaran sah. Belum ada provider atau kredensial terpilih; QRIS belum dapat menerima pembayaran nyata. |
| **KEU-F-003** | Perhitungan Zakat | Modul A | `app/Http/Controllers/ZakatController.php`, `app/Http/Requests/CalculateZakatRequest.php`, `resources/views/zakat/index.blade.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Kalkulator zakat fitrah & zakat maal dengan konfigurasi nisab dan pengurang harta, dapat diakses publik tanpa login. |
| **KEU-F-004** | Pencatatan Penyaluran Mustahik | Modul A | `app/Http/Controllers/MustahikController.php`, `app/Models/Mustahik.php`, `app/Http/Requests/UpdateMustahikRequest.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Penyaluran 8 asnaf (fakir, miskin, amil, muallaf, riqab, gharim, fisabilillah, ibnu sabil) dengan nominal dan status distribusi. |
| **KEU-F-005** | Laporan Keuangan Otomatis | Modul A | `app/Http/Controllers/FinancialReportController.php`, `app/Models/FinancialReport.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Agregasi otomatis transaksi bulanan/tahunan (pemasukan, pengeluaran, saldo) tanpa duplikasi periode. |
| **KEU-F-006** | Approval Laporan | Modul A | `app/Http/Controllers/FinancialReportController.php` (submit, approve, reject, publish) | SUDAH SESUAI | Berhasil diuji (Pest) | Workflow berjenjang: Draft (Bendahara) -> Submitted (Bendahara) -> Approved/Rejected (Ketua DKM) -> Published (Ketua DKM). |

---

## 3. MODUL B — JADWAL SHOLAT DAN KEGIATAN IBADAH

| Kode SRS | Kebutuhan Fungsi | Modul Terkait | File Implementasi | Status Implementasi | Hasil Pengujian | Catatan & Tindak Lanjut |
|---|---|---|---|---|---|---|
| **JSK-F-001** | Jadwal Sholat Otomatis | Modul B | `app/Http/Controllers/PrayerScheduleController.php`, `app/Services/PrayerScheduleService.php`, `app/Models/PrayerSchedule.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Sinkronisasi API jadwal sholat berbasis koordinat GPS, manual edit/override, kompatibel MySQL dan SQLite. |
| **JSK-F-002** | Jadwal Kajian & Khutbah | Modul B | `app/Http/Controllers/ActivityController.php`, `app/Models/Activity.php`, `resources/views/activities/` | SUDAH SESUAI | Berhasil diuji (Pest) | Penjadwalan kajian rutin, khutbah Jumat, deskripsi, penceramah, tanggal dan waktu kegiatan. |
| **JSK-F-003** | Penjadwalan Petugas Jumat & Deteksi Bentrok | Modul B | `app/Http/Controllers/ActivityController.php`, `app/Http/Controllers/OfficerController.php`, `app/Models/Officer.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Manajemen petugas dan validasi otomatis pencegahan bentrok jadwal petugas/imam pada tanggal/jam yang sama. |
| **JSK-F-004** | Notifikasi Jadwal Kegiatan | Modul B | `app/Notifications/ActivityReminderNotification.php`, `app/Http/Controllers/NotificationController.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Notifikasi in-app untuk pengingat kegiatan masjid kepada pengurus/jamaah. |

---

## 4. MODUL C — DATABASE DAN PRESENSI JAMAAH

| Kode SRS | Kebutuhan Fungsi | Modul Terkait | File Implementasi | Status Implementasi | Hasil Pengujian | Catatan & Tindak Lanjut |
|---|---|---|---|---|---|---|
| **JMH-F-001** | Kelola Data Jamaah | Modul C | `app/Http/Controllers/JamaahController.php`, `app/Models/Jamaah.php`, `resources/views/jamaahs/` | SUDAH SESUAI | Berhasil diuji (Pest) | CRUD data jamaah: nama, domisili, tanggal lahir/usia, nomor telepon, status, catatan. |
| **JMH-F-002** | Segmentasi Jamaah | Modul C | `app/Http/Controllers/JamaahController.php`, `resources/views/jamaahs/index.blade.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Filter & segmentasi berdasarkan domisili, status keaktifan, dan kelompok usia (anak, remaja, dewasa, lansia). |
| **JMH-F-003** | Presensi per Waktu Sholat | Modul C | `app/Http/Controllers/PrayerAttendanceController.php`, `app/Models/PrayerAttendance.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Pencatatan kehadiran jamaah (hadir, izin, sakit) per waktu sholat 5 waktu dengan proteksi anti presensi ganda. |
| **JMH-F-004** | Laporan Kehadiran | Modul C | `app/Http/Controllers/PrayerAttendanceReportController.php`, `resources/views/prayer-attendance-reports/` | SUDAH SESUAI | Berhasil diuji (Pest) | Rekap kehadiran mingguan dan bulanan per waktu sholat untuk evaluasi takmir masjid. |

---

## 5. MODUL D — ASET, UNIT USAHA DAN PERPUSTAKAAN

| Kode SRS | Kebutuhan Fungsi | Modul Terkait | File Implementasi | Status Implementasi | Hasil Pengujian | Catatan & Tindak Lanjut |
|---|---|---|---|---|---|---|
| **AST-F-001** | Kelola Data Aset | Modul D | `app/Http/Controllers/AssetController.php`, `app/Models/Asset.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Pencatatan inventaris/aset: kode, nama, kategori, kondisi (baik, perlu perbaikan, rusak), lokasi, nilai. |
| **AST-F-002** | Jadwal & Riwayat Maintenance | Modul D | `app/Http/Controllers/AssetMaintenanceController.php`, `app/Models/AssetMaintenance.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Pencatatan jadwal, biaya, petugas, dan riwayat pemeliharaan aset masjid. |
| **USH-F-001** | Kelola Unit Usaha | Modul D | `app/Http/Controllers/BusinessUnitController.php`, `app/Models/BusinessUnit.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Pendaftaran unit usaha masjid (koperasi, kantin, dll) oleh Ketua DKM. |
| **USH-F-002** | Pencatatan Omset Terhubung ke Keuangan | Modul D | `app/Http/Controllers/BusinessUnitSaleController.php`, `app/Models/BusinessUnitSale.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Transaksi penjualan omset unit usaha otomatis tersambung ke `FinancialTransaction` kategori Unit Usaha dalam transaksi database aman. |
| **USH-F-003** | Manajemen Stok Sederhana | Modul D | `app/Models/BusinessUnitItem.php`, `app/Http/Controllers/BusinessUnitItemController.php`, `database/migrations/` | SUDAH SESUAI | Berhasil diuji (Pest) | Pencatatan stok barang toko/koperasi unit usaha (nama barang, SKU, harga, stok). |
| **PPS-F-001** | Katalog Buku Perpustakaan | Modul D | `app/Http/Controllers/PublicLibraryController.php`, `app/Models/LibraryBook.php` | SUDAH SESUAI | Diuji di `PublicBookLoanTest` | Katalog publik hanya menampilkan buku aktif, dengan pencarian, kategori, detail dan ketersediaan. |
| **PPS-F-002** | Peminjaman & Pengembalian Buku | Modul D | `app/Http/Controllers/BookLoanRequestController.php`, `app/Http/Controllers/BookLoanController.php`, `app/Models/BookLoanRequest.php` | SUDAH SESUAI | Diuji di `PublicBookLoanTest` | Jamaah terhubung dapat mengajukan dan melihat pengajuan sendiri; pengurus menyetujui/menolak; stok dikunci saat persetujuan dan pengembalian tetap melalui alur pinjaman yang ada. Aturan jatuh tempo dimasukkan pengurus saat menyetujui. |

---

## 6. MODUL E — REMAJA MASJID DAN TPQ/TPA

| Kode SRS | Kebutuhan Fungsi | Modul Terkait | File Implementasi | Status Implementasi | Hasil Pengujian | Catatan & Tindak Lanjut |
|---|---|---|---|---|---|---|
| **RMJ-F-001** | Struktur Organisasi Remaja Masjid | Modul E | `app/Models/YouthMember.php`, `app/Http/Controllers/YouthMemberController.php`, `database/migrations/` | SUDAH SESUAI | Berhasil diuji (Pest) | Pendataan pengurus & anggota remaja masjid (IRMA/Risma) dengan divisi, jabatan, nomor kontak, terhubung ke data jamaah. |
| **RMJ-F-002** | Program Kerja Remaja Masjid | Modul E | `app/Models/YouthProgram.php`, `app/Http/Controllers/YouthProgramController.php`, `database/migrations/` | SUDAH SESUAI | Berhasil diuji (Pest) | Pencatatan rencana dan program kerja remaja masjid terpisah dari kegiatan umum masjid. |
| **TPQ-F-001** | Kelola Data Santri & Kelas | Modul E | `app/Http/Controllers/SantriController.php`, `app/Http/Controllers/TpqClassController.php`, `app/Models/Santri.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Pendataan santri, nomor registrasi, orang tua, dan pembagian kelas mengaji. |
| **TPQ-F-002** | Progress Hafalan & Bacaan | Modul E | `app/Models/Santri.php`, `app/Http/Controllers/SantriController.php`, form views santri | SUDAH SESUAI | Berhasil diuji (Pest) | Pencatatan perkembangan capaian hafalan (juz/surat) dan jilid bacaan setiap santri. |
| **TPQ-F-003** | Absensi Santri | Modul E | `app/Http/Controllers/SantriAttendanceController.php`, `app/Models/SantriAttendance.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Pencatatan kehadiran santri pada jadwal belajar TPQ beserta rekapitulasi laporan. |

---

## 7. MODUL F — KONTEN DAKWAH DAN PENGUMUMAN

| Kode SRS | Kebutuhan Fungsi | Modul Terkait | File Implementasi | Status Implementasi | Hasil Pengujian | Catatan & Tindak Lanjut |
|---|---|---|---|---|---|---|
| **KTN-F-001** | Publikasi Artikel Dakwah | Modul F | `app/Http/Controllers/DakwahContentController.php`, `resources/views/dakwah-contents/`, `resources/views/public/articles/` | SUDAH SESUAI | Berhasil diuji (Pest) | Publikasi artikel dakwah dan berita kegiatan masjid, slug otomatis, cover image, tampil di halaman publik. |
| **KTN-F-002** | Pengumuman & Info Darurat | Modul F | `app/Http/Controllers/AnnouncementController.php`, `app/Models/Announcement.php`, `resources/views/announcements/` | SUDAH SESUAI | Berhasil diuji (Pest) | CRUD pengumuman, jenis normal/darurat, status draft/published, tanggal kedaluwarsa, banner darurat publik. |
| **KTN-F-003** | Digital Signage (TV Masjid) | Modul F | `app/Http/Controllers/DigitalSignageController.php`, `resources/views/digital-signage/index.blade.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Tampilan layar TV masjid: jam digital, countdown sholat, jadwal 5 waktu, running text pengumuman darurat/normal, ringkasan kas, dan info kajian bergantian otomatis. |
| **KTN-F-004** | Notifikasi Pengumuman | Modul F | `app/Notifications/AnnouncementPublishedNotification.php`, `app/Services/WhatsAppNotificationService.php`, `app/Jobs/SendWhatsAppDelivery.php` | MENUNGGU KONFIGURASI EKSTERNAL | Alur simulasi, opt-in, antrian dan status diuji di `WhatsAppDeliveryTest`; provider riil belum diuji | Hanya jamaah aktif dengan persetujuan WhatsApp yang menjadi penerima. Tanpa adapter berstatus belum dikonfigurasi; mode simulasi tidak dilaporkan terkirim. |

---

## 8. MODUL G — WEBSITE PUBLIK DAN DASHBOARD TRANSPARANSI

| Kode SRS | Kebutuhan Fungsi | Modul Terkait | File Implementasi | Status Implementasi | Hasil Pengujian | Catatan & Tindak Lanjut |
|---|---|---|---|---|---|---|
| **WEB-F-001** | Profil Masjid Publik | Modul G | `app/Http/Controllers/PublicProfileController.php`, `resources/views/public/profile.blade.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Halaman profil masjid publik: sejarah, visi-misi, fasilitas, struktur dewan pengurus DKM, galeri foto. |
| **WEB-F-002** | Laporan Transparansi Publik | Modul G | `app/Http/Controllers/TransparencyController.php`, `resources/views/transparency/index.blade.php`, `resources/views/welcome.blade.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Ringkasan kas riil (pemasukan, pengeluaran, saldo) dari laporan terpublikasi, dapat diakses tanpa login. |
| **DSH-F-001** | Dashboard Bendahara | Modul G | `app/Http/Controllers/DashboardController.php`, `resources/views/dashboard/bendahara.blade.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Ringkasan saldo kas, pemasukan, pengeluaran, donasi masuk, status laporan pending, dan transaksi terkini. |
| **DSH-F-002** | Dashboard Ketua DKM | Modul G | `app/Http/Controllers/DashboardController.php`, `resources/views/dashboard/ketua.blade.php` | SUDAH SESUAI | Berhasil diuji (Pest) | Ringkasan lintas modul komprehensif: keuangan, jumlah jamaah terdaftar, aset & kebutuhan perawatan, santri TPQ, anggota remaja masjid, dan laporan menunggu persetujuan. |

---

## 9. RINGKASAN HASIL AUDIT DAN PENGUJIAN AKHIR

- **Cakupan checklist**: 38 kode kebutuhan fungsional pada dokumen ini.
- **Hasil implementasi dalam cakupan perubahan**: administrasi akun dan alur katalog/peminjaman jamaah selesai; payment gateway serta WhatsApp menunggu pemilihan provider dan kredensial. Rincian bukti dan batas audit tercatat di `docs/TESTING_REPORT.md`.
- **Hasil pengujian terbaru**: 103 tes lulus, 408 asersi; detail perintah dan batas verifikasi di `docs/TESTING_REPORT.md`.
- **Integritas data**: migrasi tambahan non-destruktif diterapkan pada database lokal. Tidak dijalankan `migrate:fresh` atau `db:wipe`.
- Checklist ini memetakan kebutuhan yang tercantum pada SRS, tetapi status modul lain tidak berarti telah dilakukan UAT ulang menyeluruh pada putaran ini.

