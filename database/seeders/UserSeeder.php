<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Ketua DKM',
                'email' => 'ketua@masjid.test',
                'role' => 'ketua',
            ],
            [
                'name' => 'Bendahara',
                'email' => 'bendahara@masjid.test',
                'role' => 'bendahara',
            ],
            [
                'name' => 'Sekretaris',
                'email' => 'sekretaris@masjid.test',
                'role' => 'sekretaris',
            ],
            [
                'name' => 'Marbot',
                'email' => 'marbot@masjid.test',
                'role' => 'marbot',
            ],
        ];

        foreach ($users as $data) {

            $role = Role::where('name', $data['role'])->first();

            User::updateOrCreate(
                [
                    'email' => $data['email'],
                ],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'role_id' => $role->id,
                ]
            );
        }
    }
}
