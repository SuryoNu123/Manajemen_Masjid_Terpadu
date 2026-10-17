<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $database = (string) DB::connection()->getDatabaseName();
        $isIsolatedTestDatabase = app()->environment('testing')
            && DB::connection()->getDriverName() === 'sqlite'
            && ($database === ':memory:' || preg_match('/(?:test|testing|playwright|demo|dev)/i', basename($database)) === 1);
        $isExplicitDevelopmentDatabase = app()->environment('local')
            && config('app.demo_data_seeding') === true
            && DB::connection()->getDriverName() === 'sqlite'
            && preg_match('/(?:demo|dev|test)/i', basename($database)) === 1;

        if (! $isIsolatedTestDatabase && ! $isExplicitDevelopmentDatabase) {
            return;
        }

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

            User::firstOrCreate(
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
