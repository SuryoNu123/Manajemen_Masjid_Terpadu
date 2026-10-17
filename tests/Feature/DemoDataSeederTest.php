<?php

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\BookLoan;
use App\Models\BusinessUnit;
use App\Models\BusinessUnitItem;
use App\Models\BusinessUnitSale;
use App\Models\DakwahContent;
use App\Models\Donation;
use App\Models\FinancialReport;
use App\Models\FinancialTransaction;
use App\Models\Jamaah;
use App\Models\LibraryBook;
use App\Models\Mustahik;
use App\Models\Officer;
use App\Models\PrayerAttendance;
use App\Models\PrayerSchedule;
use App\Models\Role;
use App\Models\Santri;
use App\Models\SantriAttendance;
use App\Models\TpqClass;
use App\Models\User;
use App\Models\YouthMember;
use App\Models\YouthProgram;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Support\Facades\Hash;

test('demo seeder creates connected records and remains idempotent', function () {
    $role = Role::query()->create(['name' => 'ketua', 'display_name' => 'Ketua Lama']);
    $existingUser = User::factory()->for($role)->create([
        'name' => 'Akun Lokal yang Ada',
        'email' => 'ketua@masjid.test',
        'password' => Hash::make('keep-this-password'),
    ]);

    $this->seed(DemoDataSeeder::class);

    expect($existingUser->fresh()->name)->toBe('Akun Lokal yang Ada');
    expect(Hash::check('keep-this-password', $existingUser->fresh()->password))->toBeTrue();
    expect(Jamaah::query()->where('name', 'like', 'Demo:%')->count())->toBe(24);
    expect(PrayerSchedule::query()->count())->toBe(70);
    expect(PrayerAttendance::query()->whereHas('jamaah', fn ($query) => $query->where('name', 'like', 'Demo:%'))->count())->toBe(144);
    expect(Donation::query()->where('donation_code', 'like', 'DEMO-DON-%')->count())->toBe(15);
    expect(Donation::query()->where('payment_status', 'paid')->whereNotNull('financial_transaction_id')->count())->toBe(11);
    expect(Donation::query()->where('payment_status', 'pending')->whereNull('financial_transaction_id')->count())->toBe(4);
    expect(Mustahik::query()->where('name', 'like', 'Demo:%')->count())->toBe(8);
    expect(Asset::query()->where('asset_code', 'like', 'DEMO-AST-%')->count())->toBe(12);
    expect(AssetMaintenance::query()->count())->toBe(6);
    expect(BusinessUnitItem::query()->where('sku', 'like', 'DEMO-%')->count())->toBe(6);
    expect(BusinessUnitSale::query()->where('reference_number', 'like', 'DEMO-SALE-%')->count())->toBe(8);
    expect(BookLoan::query()->where('loan_code', 'like', 'DEMO-LOAN-%')->count())->toBe(5);
    expect(YouthMember::query()->where('name', 'like', 'Demo:%')->count())->toBe(8);
    expect(YouthProgram::query()->where('name', 'like', 'Demo:%')->count())->toBe(6);
    expect(TpqClass::query()->where('name', 'like', 'Demo:%')->count())->toBe(3);
    expect(Santri::query()->where('registration_number', 'like', 'DEMO-TPQ-%')->count())->toBe(15);
    expect(SantriAttendance::query()->whereHas('santri', fn ($query) => $query->where('registration_number', 'like', 'DEMO-TPQ-%'))->count())->toBe(60);
    expect(DakwahContent::query()->where('slug', 'like', 'demo-%')->count())->toBe(5);
    expect(FinancialReport::query()->where('notes', 'like', 'Laporan simulasi%')->count())->toBe(1);

    $linkedSale = BusinessUnitSale::query()->where('reference_number', 'like', 'DEMO-SALE-%')->firstOrFail();
    expect($linkedSale->financialTransaction)->not->toBeNull();
    expect($linkedSale->financialTransaction->amount)->toEqual($linkedSale->amount);

    $publishedReport = FinancialReport::query()->where('notes', 'like', 'Laporan simulasi%')->firstOrFail();
    $reportTransactions = FinancialTransaction::query()
        ->whereYear('transaction_date', $publishedReport->year)
        ->whereMonth('transaction_date', $publishedReport->month)
        ->where('status', '!=', 'rejected')
        ->get();
    expect((float) $publishedReport->total_income)->toBe((float) $reportTransactions->where('type', 'income')->sum('amount'));
    expect((float) $publishedReport->total_expense)->toBe((float) $reportTransactions->where('type', 'expense')->sum('amount'));
    expect((float) $publishedReport->balance)->toBe((float) $publishedReport->total_income - (float) $publishedReport->total_expense);
    expect(LibraryBook::query()->where('book_code', 'like', 'DEMO-BUK-%')->count())->toBe(12);

    $snapshot = fn (): array => [
        'jamaahs' => Jamaah::query()->where('name', 'like', 'Demo:%')->count(),
        'schedules' => PrayerSchedule::query()->count(),
        'prayer_attendance' => PrayerAttendance::query()->whereHas('jamaah', fn ($query) => $query->where('name', 'like', 'Demo:%'))->count(),
        'transactions' => FinancialTransaction::query()->where('transaction_code', 'like', 'DEMO-%')->count(),
        'donations' => Donation::query()->where('donation_code', 'like', 'DEMO-DON-%')->count(),
        'mustahik' => Mustahik::query()->where('name', 'like', 'Demo:%')->count(),
        'reports' => FinancialReport::query()->where('notes', 'like', 'Laporan simulasi%')->count(),
        'activities' => Activity::query()->where('title', 'like', 'Demo:%')->count(),
        'officers' => Officer::query()->where('name', 'like', 'Demo:%')->count(),
        'assets' => Asset::query()->where('asset_code', 'like', 'DEMO-AST-%')->count(),
        'maintenance' => AssetMaintenance::query()->count(),
        'units' => BusinessUnit::query()->where('code', 'like', 'DEMO-UNIT-%')->count(),
        'items' => BusinessUnitItem::query()->where('sku', 'like', 'DEMO-%')->count(),
        'sales' => BusinessUnitSale::query()->where('reference_number', 'like', 'DEMO-SALE-%')->count(),
        'books' => LibraryBook::query()->where('book_code', 'like', 'DEMO-BUK-%')->count(),
        'loans' => BookLoan::query()->where('loan_code', 'like', 'DEMO-LOAN-%')->count(),
        'youth' => YouthMember::query()->where('name', 'like', 'Demo:%')->count(),
        'youth_programs' => YouthProgram::query()->where('name', 'like', 'Demo:%')->count(),
        'classes' => TpqClass::query()->where('name', 'like', 'Demo:%')->count(),
        'santri' => Santri::query()->where('registration_number', 'like', 'DEMO-TPQ-%')->count(),
        'santri_attendance' => SantriAttendance::query()->whereHas('santri', fn ($query) => $query->where('registration_number', 'like', 'DEMO-TPQ-%'))->count(),
        'articles' => DakwahContent::query()->where('slug', 'like', 'demo-%')->count(),
        'announcements' => Announcement::query()->where('title', 'like', 'Demo:%')->count(),
    ];
    $firstRunCounts = $snapshot();

    $this->seed(DemoDataSeeder::class);

    expect($snapshot())->toBe($firstRunCounts);
});

test('demo seeder refuses a non-test database unless an explicit named local SQLite target is enabled', function () {
    $this->app->detectEnvironment(fn (): string => 'production');
    config(['database.default' => 'sqlite']);

    expect(fn () => app(DemoDataSeeder::class)->run())
        ->toThrow(RuntimeException::class, 'DemoDataSeeder hanya dapat dijalankan');
});
