<?php

namespace Database\Seeders;

use App\Models\FinancialCategory;
use Illuminate\Database\Seeder;

class FinancialCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Infaq',
                'type' => 'income',
                'description' => 'Pemasukan dari infaq jamaah.',
            ],
            [
                'name' => 'Sedekah',
                'type' => 'income',
                'description' => 'Pemasukan dari sedekah.',
            ],
            [
                'name' => 'Zakat',
                'type' => 'income',
                'description' => 'Pemasukan dari zakat.',
            ],
            [
                'name' => 'Qurban',
                'type' => 'income',
                'description' => 'Pemasukan kegiatan qurban.',
            ],
            [
                'name' => 'Unit Usaha',
                'type' => 'income',
                'description' => 'Pemasukan unit usaha masjid.',
            ],
            [
                'name' => 'Operasional',
                'type' => 'expense',
                'description' => 'Pengeluaran operasional masjid.',
            ],
            [
                'name' => 'Sosial',
                'type' => 'expense',
                'description' => 'Pengeluaran kegiatan sosial.',
            ],
            [
                'name' => 'Pemeliharaan',
                'type' => 'expense',
                'description' => 'Pengeluaran pemeliharaan masjid.',
            ],
            [
                'name' => 'Kegiatan',
                'type' => 'expense',
                'description' => 'Pengeluaran kegiatan masjid.',
            ],
        ];

        foreach ($categories as $category) {
            FinancialCategory::create($category);
        }
    }
}