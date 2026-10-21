<?php

use App\Models\Announcement;
use App\Models\Role;
use App\Models\User;

function createUserWithRole(string $roleName): User
{
    $role = Role::firstOrCreate(['name' => $roleName], ['display_name' => ucfirst($roleName)]);

    return User::factory()->create(['role_id' => $role->id]);
}

test('sekretaris can view announcements list in dashboard', function () {
    $sekretaris = createUserWithRole('sekretaris');

    $response = $this->actingAs($sekretaris)->get(route('announcements.index'));

    $response->assertStatus(200);
});

test('sekretaris can create published announcement and dispatches audit log', function () {
    $sekretaris = createUserWithRole('sekretaris');

    $response = $this->actingAs($sekretaris)->post(route('announcements.store'), [
        'title' => 'Kajian Akbar Ramadhan',
        'content' => 'InsyaAllah akan diselenggarakan kajian akbar bersama Ustadz.',
        'type' => 'normal',
        'status' => 'published',
    ]);

    $response->assertRedirect(route('announcements.index'));
    $this->assertDatabaseHas('announcements', [
        'title' => 'Kajian Akbar Ramadhan',
        'status' => 'published',
        'type' => 'normal',
    ]);

    $this->assertDatabaseHas('activity_logs', [
        'action' => 'create_announcement',
    ]);
});

test('sekretaris can create emergency announcement', function () {
    $sekretaris = createUserWithRole('sekretaris');

    $response = $this->actingAs($sekretaris)->post(route('announcements.store'), [
        'title' => 'Pemberitahuan Cuaca Ekstrem',
        'content' => 'Sholat tarawih dipusatkan di lantai 1.',
        'type' => 'emergency',
        'status' => 'published',
    ]);

    $response->assertRedirect(route('announcements.index'));
    $this->assertDatabaseHas('announcements', [
        'title' => 'Pemberitahuan Cuaca Ekstrem',
        'type' => 'emergency',
        'status' => 'published',
    ]);
});

test('guest can access public announcements list and detail', function () {
    $announcement = Announcement::create([
        'title' => 'Pengumuman Kerja Bakti',
        'content' => 'Kerja bakti pembersihan masjid hari Ahad pagi.',
        'type' => 'normal',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->get(route('announcements.public'));
    $response->assertStatus(200);
    $response->assertSee('Pengumuman Kerja Bakti');

    $detailResponse = $this->get(route('announcements.show', $announcement));
    $detailResponse->assertStatus(200);
    $detailResponse->assertSee('Kerja bakti pembersihan masjid');
});

test('draft announcements are not shown on public index', function () {
    Announcement::create([
        'title' => 'Pengumuman Rahasia Draf',
        'content' => 'Masih dalam rancangan pengurus.',
        'type' => 'normal',
        'status' => 'draft',
    ]);

    $response = $this->get(route('announcements.public'));
    $response->assertStatus(200);
    $response->assertDontSee('Pengumuman Rahasia Draf');
});

test('guests cannot view unpublished announcements by direct URL', function () {
    $announcement = Announcement::create([
        'title' => 'Pengumuman Draf Rahasia',
        'content' => 'Belum disetujui untuk publikasi.',
        'type' => 'normal',
        'status' => 'draft',
    ]);

    $response = $this->get(route('announcements.show', $announcement));

    $response->assertNotFound();
    $response->assertDontSee('Belum disetujui untuk publikasi.');
});

test('guests cannot view expired announcements by direct URL', function () {
    $announcement = Announcement::create([
        'title' => 'Pengumuman Kedaluwarsa',
        'content' => 'Informasi yang sudah tidak berlaku.',
        'type' => 'normal',
        'status' => 'published',
        'published_at' => now()->subDay(),
        'expires_at' => now()->subMinute(),
    ]);

    $response = $this->get(route('announcements.show', $announcement));

    $response->assertNotFound();
    $response->assertDontSee('Informasi yang sudah tidak berlaku.');
});

test('jamaah cannot create announcements and receives forbidden 403', function () {
    $jamaah = createUserWithRole('jamaah');

    $response = $this->actingAs($jamaah)->post(route('announcements.store'), [
        'title' => 'Pengumuman Ilegal',
        'content' => 'Tidak boleh dibuat oleh jamaah.',
        'type' => 'normal',
        'status' => 'published',
    ]);

    $response->assertStatus(403);
});
