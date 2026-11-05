<?php

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;

it('searches activity logs by user action or description and keeps the keyword in pagination links', function () {
    $role = Role::firstOrCreate(['name' => 'ketua'], ['display_name' => 'Ketua']);
    $ketua = User::factory()->create([
        'name' => 'Kunci Pengguna',
        'role_id' => $role->id,
    ]);

    foreach (range(1, 21) as $index) {
        ActivityLog::create([
            'action' => 'audit',
            'description' => "Jejak kunci pagination {$index}",
            'created_at' => now()->addSeconds($index),
        ]);
    }

    ActivityLog::create([
        'user_id' => $ketua->id,
        'action' => 'login',
        'description' => 'Aktivitas pengguna',
        'created_at' => now(),
    ]);
    ActivityLog::create([
        'action' => 'kunci_arsip',
        'description' => 'Aktivitas sistem',
        'created_at' => now(),
    ]);
    ActivityLog::create([
        'action' => 'update_profile',
        'description' => 'Kunci deskripsi log',
        'created_at' => now(),
    ]);

    ActivityLog::create([
        'action' => 'unrelated',
        'description' => 'Aktivitas tanpa kecocokan',
        'created_at' => now(),
    ]);

    $response = $this->actingAs($ketua)->get(route('activity-logs.index', [
        'search' => 'kunci',
    ]));

    $response->assertOk()
        ->assertSee('Kunci Pengguna')
        ->assertSee('Kunci arsip')
        ->assertSee('Kunci deskripsi log')
        ->assertDontSee('Aktivitas tanpa kecocokan')
        ->assertSee('search=kunci', false)
        ->assertSee('page=2', false);
});
