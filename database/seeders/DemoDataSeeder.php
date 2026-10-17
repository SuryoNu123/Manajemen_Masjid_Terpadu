<?php

namespace Database\Seeders;

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
use App\Models\FinancialCategory;
use App\Models\FinancialReport;
use App\Models\FinancialTransaction;
use App\Models\Jamaah;
use App\Models\LibraryBook;
use App\Models\Mustahik;
use App\Models\Officer;
use App\Models\PrayerAttendance;
use App\Models\PrayerSchedule;
use App\Models\Santri;
use App\Models\SantriAttendance;
use App\Models\TpqClass;
use App\Models\User;
use App\Models\YouthMember;
use App\Models\YouthProgram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->assertSafeDatabaseTarget();

        DB::transaction(function (): void {
            $this->call([
                RoleSeeder::class,
                UserSeeder::class,
                FinancialCategorySeeder::class,
                PrayerScheduleSeeder::class,
            ]);

            $users = User::query()->whereIn('email', [
                'ketua@masjid.test',
                'bendahara@masjid.test',
                'sekretaris@masjid.test',
                'marbot@masjid.test',
            ])->get()->keyBy('email');

            $this->seedJamaah($users['sekretaris@masjid.test']);
            $this->seedPrayerSchedules();
            $this->seedFinance($users['bendahara@masjid.test']);
            $this->seedActivities();
            $this->seedAssets();
            $this->seedBusinessUnits($users['bendahara@masjid.test']);
            $this->seedPublishedFinancialReport($users['ketua@masjid.test']);
            $this->seedLibrary($users['marbot@masjid.test']);
            $this->seedYouthAndEducation($users['sekretaris@masjid.test']);
            $this->seedPublicContent($users['sekretaris@masjid.test']);
        });
    }

    private function assertSafeDatabaseTarget(): void
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $database = (string) $connection->getDatabaseName();
        $isIsolatedTestDatabase = app()->environment('testing')
            && $driver === 'sqlite'
            && ($database === ':memory:' || preg_match('/(?:test|testing|playwright|demo|dev)/i', basename($database)) === 1);
        $isExplicitDevelopmentDatabase = app()->environment('local')
            && config('app.demo_data_seeding') === true
            && $driver === 'sqlite'
            && preg_match('/(?:demo|dev|test)/i', basename($database)) === 1;
        $isExplicitLocalMySqlDatabase = app()->environment('local')
            && config('app.demo_data_seeding') === true
            && config('app.demo_data_database') === 'masjid_terpadu'
            && $database === 'masjid_terpadu'
            && in_array(strtolower((string) config('database.connections.mysql.host')), ['127.0.0.1', 'localhost', '::1'], true)
            && $driver === 'mysql';

        if (! $isIsolatedTestDatabase && ! $isExplicitDevelopmentDatabase && ! $isExplicitLocalMySqlDatabase) {
            throw new RuntimeException(
                'DemoDataSeeder hanya dapat dijalankan pada SQLite testing/development terisolasi, atau MySQL lokal masjid_terpadu dengan DEMO_DATA_SEEDING=true dan DEMO_DATA_DATABASE=masjid_terpadu.'
            );
        }
    }

    private function seedJamaah(User $recorder): void
    {
        $people = [
            ['Aulia Rahman', 'Cendana', '1990-04-12'], ['Bima Pratama', 'Melati', '1986-11-03'],
            ['Citra Lestari', 'Anggrek', '1995-02-18'], ['Dimas Saputra', 'Cendana', '1981-08-25'],
            ['Eka Wulandari', 'Kenanga', '1992-07-09'], ['Fajar Nugroho', 'Melati', '1978-03-16'],
            ['Gita Permata', 'Anggrek', '2000-12-21'], ['Hadi Kurniawan', 'Kenanga', '1988-05-30'],
            ['Intan Safitri', 'Cendana', '1997-09-14'], ['Joko Santoso', 'Melati', '1975-01-07'],
            ['Kirana Putri', 'Anggrek', '2002-06-11'], ['Lukman Hakim', 'Kenanga', '1984-10-02'],
            ['Maya Puspita', 'Cendana', '1993-03-28'], ['Nanda Firmansyah', 'Melati', '1999-08-05'],
            ['Oki Setiawan', 'Anggrek', '1983-12-19'], ['Putri Maharani', 'Kenanga', '1996-04-23'],
            ['Qori Ananda', 'Cendana', '2001-07-17'], ['Rizky Maulana', 'Melati', '1991-02-04'],
            ['Sari Handayani', 'Anggrek', '1979-11-26'], ['Taufik Hidayat', 'Kenanga', '1987-06-08'],
            ['Umar Faruq', 'Cendana', '1994-01-15'], ['Vina Amelia', 'Melati', '2003-05-20'],
            ['Wahyu Ramadhan', 'Anggrek', '1980-09-01'], ['Yuni Kartika', 'Kenanga', '1998-10-13'],
        ];
        $jamaahs = [];

        foreach ($people as $index => [$name, $domicile, $birthDate]) {
            $jamaahs[] = Jamaah::query()->firstOrCreate(
                ['name' => "Demo: {$name}"],
                [
                    'domicile' => $domicile,
                    'birth_date' => $birthDate,
                    'phone' => sprintf('0812%08d', 70000000 + $index),
                    'status' => $index === 23 ? 'nonaktif' : 'aktif',
                    'notes' => 'Data fiktif untuk pengujian aplikasi.',
                    'user_id' => null,
                ]
            );
        }

        $prayers = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
        foreach (range(1, 8) as $dayOffset) {
            $attendanceDate = now()->subDays($dayOffset)->toDateString();
            foreach (array_slice($jamaahs, 0, 18) as $jamaahIndex => $jamaah) {
                $prayer = $prayers[($jamaahIndex + $dayOffset) % count($prayers)];
                $attendanceExists = PrayerAttendance::query()
                    ->where('jamaah_id', $jamaah->id)
                    ->whereDate('attendance_date', $attendanceDate)
                    ->where('prayer_name', $prayer)
                    ->exists();

                if (! $attendanceExists) {
                    PrayerAttendance::query()->create([
                        'jamaah_id' => $jamaah->id,
                        'attendance_date' => $attendanceDate,
                        'prayer_name' => $prayer,
                        'status' => $jamaahIndex % 9 === 0 ? 'izin' : 'hadir',
                        'recorded_by' => $recorder->id,
                    ]);
                }
            }
        }
    }

    private function seedPrayerSchedules(): void
    {
        $times = [
            'subuh' => '04:32:00', 'dzuhur' => '12:04:00', 'ashar' => '15:18:00',
            'maghrib' => '18:02:00', 'isya' => '19:16:00',
        ];

        foreach (range(-3, 10) as $dayOffset) {
            $date = now()->addDays($dayOffset)->toDateString();
            foreach ($times as $name => $time) {
                $scheduleExists = PrayerSchedule::query()
                    ->whereDate('schedule_date', $date)
                    ->where('prayer_name', $name)
                    ->exists();

                if (! $scheduleExists) {
                    PrayerSchedule::query()->create([
                        'schedule_date' => $date,
                        'prayer_name' => $name,
                        'prayer_time' => $time,
                        'latitude' => -6.2000000,
                        'longitude' => 106.8166667,
                        'source' => 'demo-manual',
                        'is_active' => true,
                    ]);
                }
            }
        }
    }

    private function seedFinance(User $treasurer): void
    {
        $categories = FinancialCategory::query()->get()->keyBy(fn (FinancialCategory $category) => $category->name);
        $transactions = [];
        $donationDefinitions = [
            ['infaq', 150000, 'paid'], ['sedekah', 250000, 'paid'], ['zakat', 750000, 'paid'],
            ['qurban', 1200000, 'paid'], ['infaq', 100000, 'paid'], ['sedekah', 350000, 'pending'],
            ['zakat', 500000, 'paid'], ['infaq', 200000, 'paid'], ['qurban', 900000, 'pending'],
            ['sedekah', 175000, 'paid'], ['zakat', 650000, 'paid'], ['infaq', 125000, 'pending'],
            ['sedekah', 300000, 'paid'], ['qurban', 1500000, 'paid'], ['zakat', 400000, 'pending'],
        ];

        foreach ($donationDefinitions as $index => [$type, $amount, $paymentStatus]) {
            $date = now()->subMonths(intdiv($index, 3))->subDays(($index * 3) % 25)->toDateString();
            $transaction = null;
            if ($paymentStatus === 'paid') {
                $transaction = $this->demoTransaction(
                    "DON-DEMO-{$index}",
                    $categories[$type === 'infaq' || $type === 'qurban' ? ucfirst($type) : ucfirst($type)]->id,
                    'income',
                    $amount,
                    $date,
                    'approved',
                    $treasurer,
                    "Penerimaan donasi demo {$type}."
                );
                $transactions[] = $transaction;
            }

            Donation::query()->firstOrCreate(
                ['donation_code' => sprintf('DEMO-DON-%03d', $index + 1)],
                [
                    'donor_name' => 'Donatur Demo '.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'donor_email' => "donatur.demo.{$index}@example.test",
                    'donor_phone' => sprintf('0813%08d', 71000000 + $index),
                    'type' => $type,
                    'amount' => $amount,
                    'payment_method' => ['qris', 'transfer', 'cash'][$index % 3],
                    'payment_status' => $paymentStatus,
                    'gateway_reference' => null,
                    'paid_at' => $paymentStatus === 'paid' ? now()->subMonths(intdiv($index, 3))->subDays(($index * 3) % 25) : null,
                    'financial_transaction_id' => $transaction?->id,
                ]
            );
        }

        $expenses = [
            ['Operasional', 1850000, 'Tagihan listrik dan air masjid.'],
            ['Pemeliharaan', 675000, 'Perawatan pompa dan instalasi air.'],
            ['Kegiatan', 950000, 'Konsumsi kajian bulanan.'],
            ['Sosial', 1250000, 'Paket kebutuhan pokok warga.'],
            ['Operasional', 420000, 'Perlengkapan kebersihan.'],
            ['Pemeliharaan', 1100000, 'Perbaikan kipas ruang utama.'],
            ['Kegiatan', 780000, 'Perlengkapan kelas TPQ.'],
            ['Sosial', 600000, 'Bantuan pendidikan warga.'],
        ];
        foreach ($expenses as $index => [$category, $amount, $description]) {
            $transactions[] = $this->demoTransaction(
                sprintf('EXP-DEMO-%03d', $index + 1),
                $categories[$category]->id,
                'expense',
                $amount,
                now()->subMonths(intdiv($index, 2))->subDays($index + 2)->toDateString(),
                $index < 6 ? 'approved' : 'draft',
                $treasurer,
                $description
            );
        }

        $mustahikRows = [
            ['Keluarga Demo A', 'fakir', 500000], ['Keluarga Demo B', 'miskin', 450000],
            ['Relawan Demo C', 'amil', 300000], ['Warga Demo D', 'muallaf', 350000],
            ['Penerima Demo E', 'riqab', 250000], ['Keluarga Demo F', 'gharim', 600000],
            ['Program Demo G', 'fisabilillah', 800000], ['Musafir Demo H', 'ibnu_sabil', 200000],
        ];
        foreach ($mustahikRows as $index => [$name, $asnaf, $amount]) {
            Mustahik::query()->firstOrCreate(
                ['name' => "Demo: {$name}"],
                [
                    'asnaf' => $asnaf,
                    'address' => 'Wilayah Demo, Kota Contoh',
                    'phone' => sprintf('0814%08d', 72000000 + $index),
                    'amount' => $amount,
                    'status' => $index < 6 ? 'distributed' : 'pending',
                    'distributed_at' => $index < 6 ? now()->subDays($index + 2)->toDateString() : null,
                    'notes' => 'Data fiktif untuk pengujian zakat.',
                ]
            );
        }

    }

    private function demoTransaction(
        string $code,
        int $categoryId,
        string $type,
        int $amount,
        string $date,
        string $status,
        User $recorder,
        string $description
    ): FinancialTransaction {
        return FinancialTransaction::query()->firstOrCreate(
            ['transaction_code' => "DEMO-{$code}"],
            [
                'category_id' => $categoryId,
                'user_id' => $recorder->id,
                'type' => $type,
                'amount' => $amount,
                'transaction_date' => $date,
                'source' => 'Data simulasi',
                'description' => $description,
                'status' => $status,
            ]
        );
    }

    private function seedPublishedFinancialReport(User $chair): void
    {
        if (FinancialReport::query()->where('notes', 'like', 'Laporan simulasi untuk pengujian transparansi.')->exists()) {
            return;
        }

        foreach (range(1, 6) as $monthOffset) {
            $period = now()->subMonths($monthOffset);
            $month = (int) $period->month;
            $year = (int) $period->year;
            $existing = FinancialReport::query()->where('period_type', 'monthly')
                ->where('month', $month)->where('year', $year)->exists();
            if ($existing) {
                continue;
            }

            $periodTransactions = FinancialTransaction::query()
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->where('transaction_code', 'like', 'DEMO-%')
                ->where('status', '!=', 'rejected');
            $income = (clone $periodTransactions)->where('type', 'income')->sum('amount');
            $expense = (clone $periodTransactions)->where('type', 'expense')->sum('amount');

            FinancialReport::query()->create([
                'period_type' => 'monthly',
                'month' => $month,
                'year' => $year,
                'total_income' => $income,
                'total_expense' => $expense,
                'balance' => $income - $expense,
                'status' => 'published',
                'submitted_by' => $chair->id,
                'reviewed_by' => $chair->id,
                'reviewed_at' => now(),
                'notes' => 'Laporan simulasi untuk pengujian transparansi.',
            ]);

            return;
        }
    }

    private function seedActivities(): void
    {
        $officers = [];
        foreach ([['Ustaz Demo Ahmad', 'imam'], ['Petugas Demo Jumat', 'khatib'], ['Relawan Demo Kajian', 'lainnya']] as [$name, $position]) {
            $officers[] = Officer::query()->firstOrCreate(
                ['name' => "Demo: {$name}"],
                ['position' => $position, 'phone' => null, 'notes' => 'Data fiktif.', 'is_active' => true]
            );
        }

        foreach (range(0, 11) as $index) {
            $date = now()->addDays($index - 4)->toDateString();
            $activity = Activity::query()->firstOrCreate(
                ['title' => sprintf('Demo: Kegiatan Masjid %02d', $index + 1)],
                [
                    'type' => ['kajian', 'ibadah', 'sosial', 'remaja'][$index % 4],
                    'description' => 'Kegiatan simulasi untuk pengujian kalender dan daftar kegiatan.',
                    'activity_date' => $date,
                    'start_time' => $index % 2 === 0 ? '19:30:00' : '08:00:00',
                    'end_time' => $index % 2 === 0 ? '21:00:00' : '10:00:00',
                    'location' => 'Aula Masjid Demo',
                    'speaker' => $index % 3 === 0 ? 'Ustaz Demo Ahmad' : null,
                    'status' => $date < now()->toDateString() ? 'completed' : 'scheduled',
                    'is_public' => $index !== 11,
                ]
            );
            if ($index < 3) {
                $activity->officers()->syncWithoutDetaching([
                    $officers[$index % count($officers)]->id => ['assignment' => $index === 1 ? 'Khatib' : 'Imam'],
                ]);
            }
        }
    }

    private function seedAssets(): void
    {
        $assets = [
            ['Sound system utama', 'Elektronik', 'baik', 8500000], ['Pompa air', 'Utilitas', 'perlu_perbaikan', 3200000],
            ['Karpet ruang utama', 'Perlengkapan', 'baik', 12000000], ['Pendingin ruangan aula', 'Elektronik', 'baik', 7800000],
            ['Lampu LED ruang utama', 'Elektronik', 'rusak', 1250000], ['Kamera CCTV', 'Keamanan', 'baik', 4500000],
            ['Rak sandal', 'Perlengkapan', 'baik', 950000], ['Mikrofon nirkabel', 'Elektronik', 'perlu_perbaikan', 2100000],
            ['Proyektor kajian', 'Elektronik', 'baik', 6200000], ['Tangki air', 'Utilitas', 'baik', 5400000],
            ['Kipas aula', 'Elektronik', 'baik', 1600000], ['Lemari arsip', 'Perlengkapan', 'baik', 2300000],
        ];
        foreach ($assets as $index => [$name, $category, $condition, $value]) {
            $asset = Asset::query()->firstOrCreate(
                ['asset_code' => sprintf('DEMO-AST-%03d', $index + 1)],
                [
                    'name' => "Demo: {$name}", 'category' => $category, 'condition' => $condition,
                    'location' => 'Area Masjid Demo', 'acquisition_date' => now()->subYears(($index % 4) + 1)->toDateString(),
                    'purchase_value' => $value, 'description' => 'Aset fiktif untuk pengujian inventaris.',
                    'is_active' => $index !== 11,
                ]
            );
            if ($index < 6) {
                AssetMaintenance::query()->firstOrCreate(
                    ['asset_id' => $asset->id, 'title' => sprintf('Demo: Pemeriksaan rutin %02d', $index + 1)],
                    [
                        'description' => 'Jadwal pemeliharaan simulasi.',
                        'scheduled_date' => now()->addDays($index - 2)->toDateString(),
                        'completed_date' => $index === 0 ? now()->subDay()->toDateString() : null,
                        'status' => $index === 0 ? 'selesai' : ($index === 1 ? 'dalam_proses' : 'terjadwal'),
                        'cost' => $index === 0 ? 275000 : 0,
                        'performed_by' => 'Teknisi Demo',
                        'notes' => 'Tidak terkait pembayaran sungguhan.',
                    ]
                );
            }
        }
    }

    private function seedBusinessUnits(User $recorder): void
    {
        $unitDefinitions = [
            ['Koperasi Jamaah', 'koperasi', 'DEMO-UNIT-01'],
            ['Kantin Berkah', 'kantin', 'DEMO-UNIT-02'],
        ];
        foreach ($unitDefinitions as $unitIndex => [$name, $type, $code]) {
            $unit = BusinessUnit::query()->firstOrCreate(
                ['code' => $code],
                [
                    'name' => "Demo: {$name}", 'type' => $type,
                    'description' => 'Unit usaha simulasi untuk pengujian.', 'location' => 'Kompleks Masjid Demo',
                    'manager_name' => 'Pengelola Demo', 'phone' => null,
                    'initial_capital' => 2500000, 'is_active' => true,
                ]
            );
            foreach ([['Air mineral', 4000, 6000, 80], ['Buku doa', 12000, 18000, 24], ['Kurma kemasan', 18000, 25000, 12]] as $itemIndex => [$itemName, $cost, $price, $stock]) {
                BusinessUnitItem::query()->firstOrCreate(
                    ['sku' => sprintf('DEMO-%02d-%02d', $unitIndex + 1, $itemIndex + 1)],
                    [
                        'business_unit_id' => $unit->id, 'name' => "Demo: {$itemName}",
                        'category' => 'Kebutuhan jamaah', 'cost_price' => $cost, 'selling_price' => $price,
                        'stock' => $stock, 'min_stock' => 5, 'unit' => 'pcs', 'is_active' => true,
                    ]
                );
            }
            foreach (range(0, 3) as $saleIndex) {
                $reference = sprintf('DEMO-SALE-%02d-%02d', $unitIndex + 1, $saleIndex + 1);
                $amount = [85000, 125000, 96000, 145000][$saleIndex];
                $transaction = $this->demoTransaction(
                    $reference,
                    FinancialCategory::query()->where('name', 'Unit Usaha')->value('id'),
                    'income',
                    $amount,
                    now()->subWeeks($saleIndex + $unitIndex)->toDateString(),
                    'approved',
                    $recorder,
                    "Omzet {$name} simulasi."
                );
                BusinessUnitSale::query()->firstOrCreate(
                    ['reference_number' => $reference],
                    [
                        'business_unit_id' => $unit->id,
                        'sale_date' => now()->subWeeks($saleIndex + $unitIndex)->toDateString(),
                        'payment_method' => 'tunai', 'quantity' => $saleIndex + 2, 'amount' => $amount,
                        'description' => 'Penjualan simulasi yang terhubung ke satu transaksi keuangan.',
                        'financial_transaction_id' => $transaction->id, 'recorded_by' => $recorder->id,
                    ]
                );
            }
        }
    }

    private function seedLibrary(User $handler): void
    {
        $jamaahs = Jamaah::query()->where('name', 'like', 'Demo:%')->take(8)->get();
        foreach (range(1, 12) as $index) {
            $book = LibraryBook::query()->firstOrCreate(
                ['book_code' => sprintf('DEMO-BUK-%03d', $index)],
                [
                    'title' => sprintf('Demo: Koleksi Masjid %02d', $index),
                    'author' => ['Tim Pustaka Demo', 'Penulis Fiktif A', 'Penulis Fiktif B'][$index % 3],
                    'publisher' => 'Penerbit Contoh', 'publication_year' => 2016 + ($index % 10),
                    'category' => ['Tafsir', 'Hadis', 'Fikih', 'Sirah'][$index % 4],
                    'total_copies' => 3, 'shelf_location' => 'Rak Demo '.chr(65 + ($index % 4)),
                    'description' => 'Katalog fiktif untuk pengujian perpustakaan.', 'is_active' => $index !== 12,
                ]
            );
            if ($index <= 5 && isset($jamaahs[$index - 1])) {
                $status = $index <= 3 ? 'borrowed' : 'returned';
                BookLoan::query()->firstOrCreate(
                    ['loan_code' => sprintf('DEMO-LOAN-%03d', $index)],
                    [
                        'library_book_id' => $book->id, 'jamaah_id' => $jamaahs[$index - 1]->id,
                        'borrowed_at' => now()->subDays(12 + $index)->toDateString(),
                        'due_date' => now()->subDays(5)->toDateString(),
                        'returned_at' => $status === 'returned' ? now()->subDays(2)->toDateString() : null,
                        'status' => $status, 'handled_by' => $handler->id,
                        'returned_by' => $status === 'returned' ? $handler->id : null,
                        'notes' => 'Peminjaman fiktif untuk pengujian.',
                    ]
                );
            }
        }
    }

    private function seedYouthAndEducation(User $recorder): void
    {
        $jamaahs = Jamaah::query()->where('name', 'like', 'Demo:%')->take(8)->get()->values();
        $members = [];
        foreach (range(1, 8) as $index) {
            $members[] = YouthMember::query()->firstOrCreate(
                ['name' => sprintf('Demo: Remaja Masjid %02d', $index)],
                [
                    'jamaah_id' => $jamaahs[$index - 1]->id,
                    'division' => ['Dakwah', 'Humas', 'Sosial', 'Olahraga'][$index % 4],
                    'position' => $index === 1 ? 'Ketua' : 'Anggota',
                    'phone' => sprintf('0815%08d', 73000000 + $index),
                    'birth_date' => now()->subYears(16 + ($index % 5))->toDateString(),
                    'status' => $index === 8 ? 'alumni' : 'aktif',
                    'joined_year' => now()->year - ($index % 3), 'notes' => 'Data remaja fiktif.',
                ]
            );
        }
        foreach (range(1, 6) as $index) {
            YouthProgram::query()->firstOrCreate(
                ['name' => sprintf('Demo: Program Remaja %02d', $index)],
                [
                    'division' => $members[$index % count($members)]->division,
                    'description' => 'Program kerja simulasi organisasi remaja masjid.',
                    'start_date' => now()->addDays($index - 3)->toDateString(),
                    'end_date' => now()->addDays($index + 4)->toDateString(),
                    'youth_member_id' => $members[$index % count($members)]->id,
                    'budget' => 350000 + ($index * 50000),
                    'status' => $index < 3 ? 'selesai' : ($index === 3 ? 'berjalan' : 'rencana'),
                ]
            );
        }

        $classes = [];
        foreach ([['Iqra Pemula', 'Dasar'], ['Tahsin Remaja', 'Lanjutan'], ['Tahfiz Anak', 'Tahfiz']] as $index => [$name, $level]) {
            $classes[] = TpqClass::query()->firstOrCreate(
                ['name' => "Demo: {$name}"],
                [
                    'level' => $level, 'schedule' => ['Senin dan Rabu 16.00', 'Selasa dan Kamis 16.00', 'Sabtu 08.00'][$index],
                    'teacher_name' => 'Ustazah Demo '.chr(65 + $index), 'description' => 'Kelas TPQ fiktif.', 'is_active' => true,
                ]
            );
        }
        foreach (range(1, 15) as $index) {
            $santri = Santri::query()->firstOrCreate(
                ['registration_number' => sprintf('DEMO-TPQ-%03d', $index)],
                [
                    'name' => sprintf('Demo: Santri %02d', $index), 'gender' => $index % 2 === 0 ? 'Perempuan' : 'Laki-laki',
                    'birth_date' => now()->subYears(6 + ($index % 7))->toDateString(),
                    'parent_name' => sprintf('Wali Demo %02d', $index),
                    'parent_phone' => sprintf('0816%08d', 74000000 + $index),
                    'address' => 'Wilayah Demo, Kota Contoh', 'tpq_class_id' => $classes[($index - 1) % count($classes)]->id,
                    'enrollment_date' => now()->subMonths(2 + ($index % 10))->toDateString(),
                    'progress_hafalan' => ['Juz Amma', 'An-Naba', 'Al-Mulk'][$index % 3],
                    'progress_bacaan' => 'Iqra jilid '.(1 + ($index % 6)),
                    'learning_notes' => 'Progres pembelajaran simulasi.', 'status' => $index === 15 ? 'inactive' : 'active',
                ]
            );
            foreach (range(1, 4) as $dayOffset) {
                $attendanceDate = now()->subDays($dayOffset)->toDateString();
                $attendanceExists = SantriAttendance::query()
                    ->where('santri_id', $santri->id)
                    ->whereDate('attendance_date', $attendanceDate)
                    ->exists();

                if (! $attendanceExists) {
                    SantriAttendance::query()->create([
                        'santri_id' => $santri->id,
                        'attendance_date' => $attendanceDate,
                        'status' => $index % 7 === 0 ? 'permission' : ($index % 11 === 0 ? 'sick' : 'present'),
                        'notes' => 'Presensi simulasi.', 'recorded_by' => $recorder->id,
                    ]);
                }
            }
        }
    }

    private function seedPublicContent(User $author): void
    {
        foreach (range(1, 5) as $index) {
            DakwahContent::query()->firstOrCreate(
                ['slug' => sprintf('demo-renungan-masjid-%02d', $index)],
                [
                    'title' => sprintf('Demo: Renungan Masjid %02d', $index),
                    'excerpt' => 'Artikel contoh untuk pengujian konten dakwah publik.',
                    'content' => 'Konten fiktif untuk pengujian tampilan dan alur publikasi artikel dakwah.',
                    'cover_image' => null, 'status' => $index === 5 ? 'draft' : 'published',
                    'published_at' => $index === 5 ? null : now()->subDays($index)->startOfDay(),
                    'author_id' => $author->id,
                ]
            );
        }
        foreach (range(1, 5) as $index) {
            Announcement::query()->firstOrCreate(
                ['title' => sprintf('Demo: Pengumuman Masjid %02d', $index)],
                [
                    'content' => 'Informasi simulasi untuk pengujian pengumuman publik.',
                    'type' => $index === 1 ? 'emergency' : 'normal',
                    'status' => $index === 5 ? 'draft' : 'published',
                    'published_at' => $index === 5 ? null : now()->subDays($index)->startOfDay(),
                    'expires_at' => $index === 1 ? now()->addDays(2) : null,
                    'author_id' => $author->id,
                ]
            );
        }
    }
}
