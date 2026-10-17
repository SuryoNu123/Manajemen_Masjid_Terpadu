<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            FinancialCategorySeeder::class,
            PrayerScheduleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
