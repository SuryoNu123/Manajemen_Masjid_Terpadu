<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'ketua',
                'display_name' => 'Ketua DKM',
            ],
            [
                'name' => 'bendahara',
                'display_name' => 'Bendahara',
            ],
            [
                'name' => 'sekretaris',
                'display_name' => 'Sekretaris / Humas',
            ],
            [
                'name' => 'marbot',
                'display_name' => 'Marbot / Petugas',
            ],
            [
                'name' => 'jamaah',
                'display_name' => 'Jamaah',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['display_name' => $role['display_name']]
            );
        }
    }
}
