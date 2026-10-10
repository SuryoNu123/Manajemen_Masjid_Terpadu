<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }

    
}

$this->call([
    RoleSeeder::class,
    UserSeeder::class,
    FinancialCategorySeeder::class,
]);

$this->call([
    RoleSeeder::class,
    UserSeeder::class,
    FinancialCategorySeeder::class,
    PrayerScheduleSeeder::class,
]);

