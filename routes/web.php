<?php

use App\Http\Controllers\Bendahara\MustahikController;
use App\Http\Controllers\Jamaah\WhatsAppPreferenceController;
use App\Http\Controllers\Ketua\ActivityLogController;
use App\Http\Controllers\Ketua\BusinessUnitController;
use App\Http\Controllers\Ketua\UserAdministrationController;
use App\Http\Controllers\Public\DigitalSignageController;
use App\Http\Controllers\Public\PublicHomeController;
use App\Http\Controllers\Public\PublicLibraryController;
use App\Http\Controllers\Public\PublicProfileController;
use App\Http\Controllers\Public\TransparencyController;
use App\Http\Controllers\Public\ZakatController;
use App\Http\Controllers\Shared\ActivityController;
use App\Http\Controllers\Shared\AnnouncementController;
use App\Http\Controllers\Shared\AssetController;
use App\Http\Controllers\Shared\AssetMaintenanceController;
use App\Http\Controllers\Shared\BookLoanController;
use App\Http\Controllers\Shared\BookLoanRequestController;
use App\Http\Controllers\Shared\BusinessUnitItemController;
use App\Http\Controllers\Shared\BusinessUnitSaleController;
use App\Http\Controllers\Shared\DakwahContentController;
use App\Http\Controllers\Shared\DashboardController;
use App\Http\Controllers\Shared\DonationController;
use App\Http\Controllers\Shared\FinancialReportController;
use App\Http\Controllers\Shared\FinancialTransactionController;
use App\Http\Controllers\Shared\JamaahController;
use App\Http\Controllers\Shared\LibraryBookController;
use App\Http\Controllers\Shared\NotificationController;
use App\Http\Controllers\Shared\OfficerController;
use App\Http\Controllers\Shared\PaymentWebhookController;
use App\Http\Controllers\Shared\PrayerAttendanceController;
use App\Http\Controllers\Shared\PrayerAttendanceReportController;
use App\Http\Controllers\Shared\PrayerScheduleController;
use App\Http\Controllers\Shared\ProfileController;
use App\Http\Controllers\Shared\SantriAttendanceController;
use App\Http\Controllers\Shared\SantriController;
use App\Http\Controllers\Shared\TpqClassController;
use App\Http\Controllers\Shared\YouthMemberController;
use App\Http\Controllers\Shared\YouthProgramController;
use App\Http\Middleware\EnsureActiveUser;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicHomeController::class, 'index'])->name('home');
Route::get('/jadwal-sholat-harian', [PublicHomeController::class, 'schedules'])->name('prayer-schedules.daily');
Route::get('/profil', [PublicProfileController::class, 'index'])->name('profile.public');
Route::get('/digital-signage', [DigitalSignageController::class, 'index'])->name('digital-signage.index');
Route::get('/pengumuman', [AnnouncementController::class, 'publicIndex'])->name('announcements.public');
Route::get('/pengumuman/{announcement}', [AnnouncementController::class, 'publicShow'])->name('announcements.show');
Route::get('/artikel', [DakwahContentController::class, 'publicIndex'])->name('dakwah.public');
Route::get('/artikel/{slug}', [DakwahContentController::class, 'publicShow'])->name('dakwah.show');
Route::get('/perpustakaan', [PublicLibraryController::class, 'index'])->name('library.public');
Route::get('/perpustakaan/{book}', [PublicLibraryController::class, 'show'])->name('library.public.show');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [
        ProfileController::class,
        'edit',
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update',
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy',
    ])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Transaksi Keuangan
    |--------------------------------------------------------------------------
    |
    | Ketua dan Bendahara dapat melihat transaksi.
    |
    */

    Route::middleware('role:ketua,bendahara')->group(function () {

        Route::get('/transaksi-keuangan', [
            FinancialTransactionController::class,
            'index',
        ])->name('transaksi.index');

    });

    /*
    |--------------------------------------------------------------------------
    | Kelola Transaksi Keuangan
    |--------------------------------------------------------------------------
    |
    | Hanya Bendahara yang dapat membuat, mengubah,
    | dan menghapus transaksi.
    |
    */

    Route::middleware('role:bendahara')->group(function () {

        Route::get('/transaksi-keuangan/create', [
            FinancialTransactionController::class,
            'create',
        ])->name('transaksi.create');

        Route::post('/transaksi-keuangan', [
            FinancialTransactionController::class,
            'store',
        ])->name('transaksi.store');

        Route::get('/transaksi-keuangan/{transaksi}/edit', [
            FinancialTransactionController::class,
            'edit',
        ])->name('transaksi.edit');

        Route::put('/transaksi-keuangan/{transaksi}', [
            FinancialTransactionController::class,
            'update',
        ])->name('transaksi.update');

        Route::delete('/transaksi-keuangan/{transaksi}', [
            FinancialTransactionController::class,
            'destroy',
        ])->name('transaksi.destroy');

    });

});

Route::middleware(['auth', 'role:bendahara'])->group(function () {

    Route::get('/mustahik', [
        MustahikController::class,
        'index',
    ])->name('mustahik.index');

    Route::get('/mustahik/create', [
        MustahikController::class,
        'create',
    ])->name('mustahik.create');

    Route::post('/mustahik', [
        MustahikController::class,
        'store',
    ])->name('mustahik.store');

    Route::get('/mustahik/{mustahik}/edit', [
        MustahikController::class,
        'edit',
    ])->name('mustahik.edit');

    Route::put('/mustahik/{mustahik}', [
        MustahikController::class,
        'update',
    ])->name('mustahik.update');

    Route::delete('/mustahik/{mustahik}', [
        MustahikController::class,
        'destroy',
    ])->name('mustahik.destroy');

});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Donasi Publik
|--------------------------------------------------------------------------
*/

Route::get('/donasi', [
    DonationController::class,
    'create',
])->name('donasi.create');

Route::post('/donasi', [
    DonationController::class,
    'store',
])->name('donasi.store');

Route::post('/webhooks/payments', PaymentWebhookController::class)
    ->name('webhooks.payments')
    ->withoutMiddleware(EnsureActiveUser::class);

Route::get('/donasi/{donasi}/success', [
    DonationController::class,
    'success',
])->name('donasi.success');

/*
|--------------------------------------------------------------------------
| Kelola Donasi - Bendahara
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:bendahara',
])->group(function () {

    Route::get('/kelola-donasi', [
        DonationController::class,
        'index',
    ])->name('donasi.index');

    Route::patch('/kelola-donasi/{donasi}/paid', [
        DonationController::class,
        'markPaid',
    ])->name('donasi.mark-paid');

    Route::patch('/kelola-donasi/{donasi}/failed', [
        DonationController::class,
        'markFailed',
    ])->name('donasi.mark-failed');

});

/*
|--------------------------------------------------------------------------
| Kalkulator Zakat
|--------------------------------------------------------------------------
|
| Kalkulator dapat digunakan publik/jamaah tanpa login.
|
*/

Route::get('/zakat', [
    ZakatController::class,
    'index',
])->name('zakat.index');

Route::post('/zakat/calculate', [
    ZakatController::class,
    'calculate',
])->name('zakat.calculate');

/*
|--------------------------------------------------------------------------
| LAPORAN KEUANGAN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:ketua,bendahara',
])->group(function () {

    Route::get('/laporan-keuangan', [
        FinancialReportController::class,
        'index',
    ])->name('laporan-keuangan.index');

});

Route::middleware([
    'auth',
    'role:bendahara',
])->group(function () {

    Route::get('/laporan-keuangan/create', [
        FinancialReportController::class,
        'create',
    ])->name('laporan-keuangan.create');

    Route::post('/laporan-keuangan/generate', [
        FinancialReportController::class,
        'generate',
    ])->name('laporan-keuangan.generate');

    Route::patch('/laporan-keuangan/{laporan}/submit', [
        FinancialReportController::class,
        'submit',
    ])->name('laporan-keuangan.submit');

});

Route::middleware([
    'auth',
    'role:ketua',
])->group(function () {

    Route::patch('/laporan-keuangan/{laporan}/approve', [
        FinancialReportController::class,
        'approve',
    ])->name('laporan-keuangan.approve');

    Route::patch('/laporan-keuangan/{laporan}/reject', [
        FinancialReportController::class,
        'reject',
    ])->name('laporan-keuangan.reject');

    Route::patch('/laporan-keuangan/{laporan}/publish', [
        FinancialReportController::class,
        'publish',
    ])->name('laporan-keuangan.publish');

});

/*
|--------------------------------------------------------------------------
| Transparansi Keuangan Publik
|--------------------------------------------------------------------------
*/

Route::get('/transparansi', [
    TransparencyController::class,
    'index',
])->name('transparansi.index');

/*
|--------------------------------------------------------------------------
| JADWAL SHOLAT
|--------------------------------------------------------------------------
|
| Ketua dapat memantau.
| Marbot dapat mengelola jadwal.
|
*/

Route::middleware([
    'auth',
    'role:ketua,marbot',
])->group(function () {

    Route::get('/jadwal-sholat', [
        PrayerScheduleController::class,
        'index',
    ])->name('jadwal-sholat.index');

    Route::get('/jadwal-sholat/create', [
        PrayerScheduleController::class,
        'create',
    ])->name('jadwal-sholat.create');

    Route::post('/jadwal-sholat', [
        PrayerScheduleController::class,
        'store',
    ])->name('jadwal-sholat.store');

    Route::get('/jadwal-sholat/{jadwalSholat}/edit', [
        PrayerScheduleController::class,
        'edit',
    ])->name('jadwal-sholat.edit');

    Route::put('/jadwal-sholat/{jadwalSholat}', [
        PrayerScheduleController::class,
        'update',
    ])->name('jadwal-sholat.update');

    Route::delete('/jadwal-sholat/{jadwalSholat}', [
        PrayerScheduleController::class,
        'destroy',
    ])->name('jadwal-sholat.destroy');

    Route::post('/jadwal-sholat/sync', [
        PrayerScheduleController::class,
        'sync',
    ])->name('prayer-schedules.sync');

});

Route::get('/kegiatan', [ActivityController::class, 'publicIndex'])
    ->name('activities.public');

Route::middleware(['auth', 'role:ketua,sekretaris'])->group(function () {
    Route::resource('activities', ActivityController::class);
    Route::resource('officers', OfficerController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {

    Route::get('/notifikasi', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');

    Route::post('/notifikasi/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.readAll');

});

Route::middleware(['auth', 'role:ketua,sekretaris'])->group(function () {
    Route::resource('jamaahs', JamaahController::class)
        ->except(['show']);
});

Route::middleware(['auth', 'role:ketua,marbot'])->group(function () {
    Route::get('/presensi', [
        PrayerAttendanceController::class,
        'index',
    ])->name('prayer-attendances.index');

    Route::post('/presensi', [
        PrayerAttendanceController::class,
        'store',
    ])->name('prayer-attendances.store');
});

Route::middleware(['auth', 'role:ketua,marbot'])->group(function () {
    Route::get('/laporan-presensi', [
        PrayerAttendanceReportController::class,
        'index',
    ])->name('prayer-attendance-reports.index');
});

Route::middleware(['auth', 'role:ketua,marbot'])->group(function () {
    Route::resource('assets', AssetController::class)
        ->except(['show']);
});

Route::middleware(['auth', 'role:ketua,marbot'])->group(function () {
    Route::resource('asset-maintenances', AssetMaintenanceController::class)
        ->parameters([
            'asset-maintenances' => 'assetMaintenance',
        ])
        ->except(['show', 'destroy']);
});

Route::middleware(['auth', 'role:ketua'])->group(function () {
    Route::resource('business-units', BusinessUnitController::class)
        ->parameters([
            'business-units' => 'businessUnit',
        ])
        ->except(['show', 'destroy']);
});

Route::middleware(['auth', 'role:ketua,bendahara'])->group(function () {
    // Daftar penjualan dan rekap omzet
    Route::get('/business-unit-sales', [
        BusinessUnitSaleController::class,
        'index',
    ])->name('business-unit-sales.index');

    // Form tambah penjualan
    Route::get('/business-unit-sales/create', [
        BusinessUnitSaleController::class,
        'create',
    ])->name('business-unit-sales.create');

    // Simpan penjualan
    Route::post('/business-unit-sales', [
        BusinessUnitSaleController::class,
        'store',
    ])->name('business-unit-sales.store');

    // Laporan omzet
    Route::get('/business-unit-sales/report', [
        BusinessUnitSaleController::class,
        'report',
    ])->name('business-unit-sales.report');

    // Inventaris dan stok produk unit usaha
    Route::resource('business-unit-items', BusinessUnitItemController::class)
        ->parameters([
            'business-unit-items' => 'businessUnitItem',
        ])
        ->except(['show']);

    Route::post('/business-unit-items/{businessUnitItem}/adjust-stock', [
        BusinessUnitItemController::class,
        'adjustStock',
    ])->name('business-unit-items.adjust-stock');
});

Route::middleware(['auth', 'role:ketua,sekretaris'])->group(function () {
    Route::resource('library-books', LibraryBookController::class)
        ->parameters([
            'library-books' => 'libraryBook',
        ])
        ->except(['show', 'destroy']);
});

Route::middleware(['auth', 'role:ketua,sekretaris'])->group(function () {
    Route::get('/book-loans', [
        BookLoanController::class,
        'index',
    ])->name('book-loans.index');

    Route::get('/book-loans/create', [
        BookLoanController::class,
        'create',
    ])->name('book-loans.create');

    Route::post('/book-loans', [
        BookLoanController::class,
        'store',
    ])->name('book-loans.store');

    Route::patch('/book-loans/{bookLoan}/return', [
        BookLoanController::class,
        'returnBook',
    ])->name('book-loans.return');

    Route::patch('/book-loans/{bookLoan}/lost', [
        BookLoanController::class,
        'markLost',
    ])->name('book-loans.lost');

    Route::get('/book-loans/report', [
        BookLoanController::class,
        'report',
    ])->name('book-loans.report');

    Route::resource('tpq-classes', TpqClassController::class)
        ->except(['show']);

    Route::resource('santris', SantriController::class);

    // Remaja Masjid (IRMA / Risma)
    Route::resource('youth-members', YouthMemberController::class)
        ->except(['show']);

    Route::resource('youth-programs', YouthProgramController::class)
        ->except(['show']);
});

Route::middleware(['auth', 'role:ketua,sekretaris,marbot'])->group(function () {
    Route::get('/santri-attendances', [
        SantriAttendanceController::class,
        'index',
    ])->name('santri-attendances.index');

    Route::post('/santri-attendances', [
        SantriAttendanceController::class,
        'store',
    ])->name('santri-attendances.store');

    Route::get('/santri-attendances/report', [
        SantriAttendanceController::class,
        'report',
    ])->name('santri-attendances.report');

    Route::get('/santri-attendances/export', [
        SantriAttendanceController::class,
        'export',
    ])->name('santri-attendances.export');
});

Route::middleware(['auth', 'role:ketua,sekretaris'])->group(function () {
    Route::resource('dakwah-contents', DakwahContentController::class)
        ->except(['show']);

    Route::resource('announcements', AnnouncementController::class)
        ->except(['show']);

    Route::get('/announcements/{announcement}/detail', [AnnouncementController::class, 'show'])
        ->name('announcements.internal-show');
});

Route::middleware(['auth', 'role:ketua'])->group(function () {
    Route::resource('/admin/pengguna', UserAdministrationController::class)
        ->parameters(['pengguna' => 'user'])
        ->names('user-administration')
        ->except(['destroy']);

    Route::post('/admin/pengguna/{user}/kirim-tautan-reset-password', [UserAdministrationController::class, 'sendPasswordReset'])
        ->name('user-administration.password-reset');

    Route::get('/activity-logs', [
        ActivityLogController::class,
        'index',
    ])->name('activity-logs.index');
});

Route::middleware(['auth', 'role:ketua,sekretaris'])->group(function () {
    Route::get('/book-loan-requests', [BookLoanRequestController::class, 'index'])->name('book-loan-requests.index');
    Route::patch('/book-loan-requests/{bookLoanRequest}/approve', [BookLoanRequestController::class, 'approve'])->name('book-loan-requests.approve');
    Route::patch('/book-loan-requests/{bookLoanRequest}/reject', [BookLoanRequestController::class, 'reject'])->name('book-loan-requests.reject');
});

Route::middleware(['auth', 'role:jamaah'])->group(function () {
    Route::get('/akun/peminjaman', [BookLoanRequestController::class, 'mine'])->name('book-loan-requests.mine');
    Route::get('/akun/pengaturan-notifikasi', [WhatsAppPreferenceController::class, 'edit'])->name('whatsapp-preferences.edit');
    Route::put('/akun/pengaturan-notifikasi', [WhatsAppPreferenceController::class, 'update'])->name('whatsapp-preferences.update');
    Route::post('/perpustakaan/{book}/pinjam', [BookLoanRequestController::class, 'store'])->name('book-loan-requests.store');
});

require __DIR__.'/auth.php';
