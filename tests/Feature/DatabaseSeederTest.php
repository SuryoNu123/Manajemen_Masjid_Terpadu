<?php

use Illuminate\Support\Facades\Artisan;

test('database seeder creates the default roles users categories and prayer schedules', function () {
    Artisan::call('db:seed', ['--no-interaction' => true]);

    $this->assertDatabaseHas('roles', ['name' => 'jamaah']);
    $this->assertDatabaseHas('users', ['email' => 'ketua@masjid.test']);
    $this->assertDatabaseHas('financial_categories', ['name' => 'Infaq']);
    $this->assertDatabaseCount('prayer_schedules', 5);
});
