<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\MustahikController;
use App\Http\Controllers\ZakatController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\TransparencyController;
use App\Http\Controllers\PrayerScheduleController;
use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/


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
        'index'
    ])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
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
            'index'
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
            'create'
        ])->name('transaksi.create');

        Route::post('/transaksi-keuangan', [
            FinancialTransactionController::class,
            'store'
        ])->name('transaksi.store');

        Route::get('/transaksi-keuangan/{transaksi}/edit', [
            FinancialTransactionController::class,
            'edit'
        ])->name('transaksi.edit');

        Route::put('/transaksi-keuangan/{transaksi}', [
            FinancialTransactionController::class,
            'update'
        ])->name('transaksi.update');

        Route::delete('/transaksi-keuangan/{transaksi}', [
            FinancialTransactionController::class,
            'destroy'
        ])->name('transaksi.destroy');

    });

});

Route::middleware(['auth', 'role:bendahara'])->group(function () {

    Route::get('/mustahik', [
        MustahikController::class,
        'index'
    ])->name('mustahik.index');

    Route::get('/mustahik/create', [
        MustahikController::class,
        'create'
    ])->name('mustahik.create');

    Route::post('/mustahik', [
        MustahikController::class,
        'store'
    ])->name('mustahik.store');

    Route::get('/mustahik/{mustahik}/edit', [
        MustahikController::class,
        'edit'
    ])->name('mustahik.edit');

    Route::put('/mustahik/{mustahik}', [
        MustahikController::class,
        'update'
    ])->name('mustahik.update');

    Route::delete('/mustahik/{mustahik}', [
        MustahikController::class,
        'destroy'
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
    'create'
])->name('donasi.create');

Route::post('/donasi', [
    DonationController::class,
    'store'
])->name('donasi.store');

Route::get('/donasi/{donasi}/success', [
    DonationController::class,
    'success'
])->name('donasi.success');


/*
|--------------------------------------------------------------------------
| Kelola Donasi - Bendahara
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:bendahara'
])->group(function () {

    Route::get('/kelola-donasi', [
        DonationController::class,
        'index'
    ])->name('donasi.index');

    Route::patch('/kelola-donasi/{donasi}/paid', [
        DonationController::class,
        'markPaid'
    ])->name('donasi.mark-paid');

    Route::patch('/kelola-donasi/{donasi}/failed', [
        DonationController::class,
        'markFailed'
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
    'index'
])->name('zakat.index');

Route::post('/zakat/calculate', [
    ZakatController::class,
    'calculate'
])->name('zakat.calculate');

/*
|--------------------------------------------------------------------------
| LAPORAN KEUANGAN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:ketua,bendahara'
])->group(function () {

    Route::get('/laporan-keuangan', [
        FinancialReportController::class,
        'index'
    ])->name('laporan-keuangan.index');

});


Route::middleware([
    'auth',
    'role:bendahara'
])->group(function () {

    Route::get('/laporan-keuangan/create', [
        FinancialReportController::class,
        'create'
    ])->name('laporan-keuangan.create');

    Route::post('/laporan-keuangan/generate', [
        FinancialReportController::class,
        'generate'
    ])->name('laporan-keuangan.generate');

    Route::patch('/laporan-keuangan/{laporan}/submit', [
        FinancialReportController::class,
        'submit'
    ])->name('laporan-keuangan.submit');

});


Route::middleware([
    'auth',
    'role:ketua'
])->group(function () {

    Route::patch('/laporan-keuangan/{laporan}/approve', [
        FinancialReportController::class,
        'approve'
    ])->name('laporan-keuangan.approve');

    Route::patch('/laporan-keuangan/{laporan}/reject', [
        FinancialReportController::class,
        'reject'
    ])->name('laporan-keuangan.reject');

    Route::patch('/laporan-keuangan/{laporan}/publish', [
        FinancialReportController::class,
        'publish'
    ])->name('laporan-keuangan.publish');

});

/*
|--------------------------------------------------------------------------
| Transparansi Keuangan Publik
|--------------------------------------------------------------------------
*/

Route::get('/transparansi', [
    TransparencyController::class,
    'index'
])->name('transparansi.index');

/*
|--------------------------------------------------------------------------
| PUBLIC HOME
|--------------------------------------------------------------------------
*/

Route::get('/', [
    PublicHomeController::class,
    'index'
])->name('home');


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
    'role:ketua,marbot'
])->group(function () {

    Route::get('/jadwal-sholat', [
        PrayerScheduleController::class,
        'index'
    ])->name('jadwal-sholat.index');

    Route::get('/jadwal-sholat/create', [
        PrayerScheduleController::class,
        'create'
    ])->name('jadwal-sholat.create');

    Route::post('/jadwal-sholat', [
        PrayerScheduleController::class,
        'store'
    ])->name('jadwal-sholat.store');

    Route::get('/jadwal-sholat/{jadwalSholat}/edit', [
        PrayerScheduleController::class,
        'edit'
    ])->name('jadwal-sholat.edit');

    Route::put('/jadwal-sholat/{jadwalSholat}', [
        PrayerScheduleController::class,
        'update'
    ])->name('jadwal-sholat.update');

    Route::delete('/jadwal-sholat/{jadwalSholat}', [
        PrayerScheduleController::class,
        'destroy'
    ])->name('jadwal-sholat.destroy');

});

Route::middleware(['auth', 'role:ketua,sekretaris'])->group(function () {

    Route::resource('activities', ActivityController::class);

});

Route::get('/kegiatan', [ActivityController::class, 'publicIndex'])
    ->name('activities.public');

Route::middleware(['auth', 'role:ketua,sekretaris'])->group(function () {

    Route::resource('activities', ActivityController::class)
        ->except(['show']);

    Route::resource('officers', OfficerController::class)
        ->except(['show']);

});

Route::middleware('auth')->group(function () {

    Route::get('/notifikasi', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');

    Route::post('/notifikasi/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.readAll');

});

Route::post(
    '/jadwal-sholat/sync',
    [PrayerScheduleController::class, 'sync']
)->name('prayer-schedules.sync');

Route::middleware(['auth', 'role:ketua,marbot'])->group(function () {

    Route::resource('prayer-schedules', PrayerScheduleController::class)
        ->except(['show']);

    Route::post(
        '/jadwal-sholat/sync',
        [PrayerScheduleController::class, 'sync']
    )->name('prayer-schedules.sync');

});

require __DIR__ . '/auth.php';