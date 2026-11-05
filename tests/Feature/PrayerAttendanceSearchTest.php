<?php

use App\Models\Jamaah;
use App\Models\Role;
use App\Models\User;

it('filters active prayer attendance rows by jamaah name', function () {
    $role = Role::firstOrCreate(['name' => 'marbot'], ['display_name' => 'Marbot']);
    $marbot = User::factory()->create(['role_id' => $role->id]);

    Jamaah::create([
        'name' => 'Aisyah Putri',
        'domicile' => 'Bandung',
        'status' => 'aktif',
    ]);
    Jamaah::create([
        'name' => 'Fatimah Zahra',
        'domicile' => 'Bandung',
        'status' => 'aktif',
    ]);
    Jamaah::create([
        'name' => 'Aisyah Nonaktif',
        'domicile' => 'Bandung',
        'status' => 'nonaktif',
    ]);

    $response = $this->actingAs($marbot)->get(route('prayer-attendances.index', [
        'date' => today()->toDateString(),
        'prayer_name' => 'maghrib',
        'search' => 'Aisyah',
    ]));

    $response->assertOk()
        ->assertSee('Aisyah Putri')
        ->assertDontSee('Fatimah Zahra')
        ->assertDontSee('Aisyah Nonaktif')
        ->assertSee('value="Aisyah"', false);
});

it('keeps the jamaah search after saving attendance', function () {
    $role = Role::firstOrCreate(['name' => 'marbot'], ['display_name' => 'Marbot']);
    $marbot = User::factory()->create(['role_id' => $role->id]);
    $date = today()->toDateString();

    $this->actingAs($marbot)
        ->post(route('prayer-attendances.store'), [
            'attendance_date' => $date,
            'prayer_name' => 'maghrib',
            'search' => 'Aisyah',
        ])
        ->assertRedirect(route('prayer-attendances.index', [
            'date' => $date,
            'prayer_name' => 'maghrib',
            'search' => 'Aisyah',
        ]));
});
