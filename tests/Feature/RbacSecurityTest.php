<?php

use App\Models\Role;
use App\Models\User;

function createRoleUser(string $roleName): User
{
    $role = Role::firstOrCreate(['name' => $roleName], ['display_name' => ucfirst($roleName)]);

    return User::factory()->create(['role_id' => $role->id]);
}

test('unauthenticated guest is redirected to login when accessing internal routes', function () {
    $this->get('/dashboard')->assertRedirect(route('login'));
    $this->get('/transaksi-keuangan')->assertRedirect(route('login'));
    $this->get('/mustahik')->assertRedirect(route('login'));
    $this->get('/assets')->assertRedirect(route('login'));
});

test('jamaah role receives 403 on administrative internal routes', function () {
    $jamaah = createRoleUser('jamaah');

    $this->actingAs($jamaah)->get('/transaksi-keuangan')->assertStatus(403);
    $this->actingAs($jamaah)->get('/mustahik')->assertStatus(403);
    $this->actingAs($jamaah)->get('/announcements/create')->assertStatus(403);
    $this->actingAs($jamaah)->get('/assets/create')->assertStatus(403);
    $this->actingAs($jamaah)->get('/jadwal-sholat/create')->assertStatus(403);
});

test('bendahara role can access finance but receives 403 on assets and youth', function () {
    $bendahara = createRoleUser('bendahara');

    $this->actingAs($bendahara)->get('/transaksi-keuangan')->assertStatus(200);
    $this->actingAs($bendahara)->get('/mustahik')->assertStatus(200);
    $this->actingAs($bendahara)->get('/assets/create')->assertStatus(403);
    $this->actingAs($bendahara)->get('/youth-members/create')->assertStatus(403);
});

test('marbot role can access prayer schedules and assets but receives 403 on finance and mustahik', function () {
    $marbot = createRoleUser('marbot');

    $this->actingAs($marbot)->get('/jadwal-sholat')->assertStatus(200);
    $this->actingAs($marbot)->get('/assets')->assertStatus(200);
    $this->actingAs($marbot)->get('/transaksi-keuangan/create')->assertStatus(403);
    $this->actingAs($marbot)->get('/mustahik')->assertStatus(403);
});

test('sekretaris role can access announcements and youth but receives 403 on mustahik and asset creation', function () {
    $sekretaris = createRoleUser('sekretaris');

    $this->actingAs($sekretaris)->get('/announcements')->assertStatus(200);
    $this->actingAs($sekretaris)->get('/youth-members')->assertStatus(200);
    $this->actingAs($sekretaris)->get('/mustahik')->assertStatus(403);
    $this->actingAs($sekretaris)->get('/assets/create')->assertStatus(403);
});

test('ketua role can access audit logs and overview across modules', function () {
    $ketua = createRoleUser('ketua');

    $this->actingAs($ketua)->get('/activity-logs')->assertStatus(200);
    $this->actingAs($ketua)->get('/transaksi-keuangan')->assertStatus(200);
    $this->actingAs($ketua)->get('/jamaahs')->assertStatus(200);
    $this->actingAs($ketua)->get('/assets')->assertStatus(200);
});
