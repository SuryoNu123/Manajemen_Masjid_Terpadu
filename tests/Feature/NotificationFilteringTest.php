<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

function createStoredNotification(User $user, string $title, string $message, ?string $readAt = null): void
{
    $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'App\\Notifications\\AnnouncementPublishedNotification',
        'data' => [
            'title' => $title,
            'message' => $message,
        ],
        'read_at' => $readAt,
    ]);
}

test('user can filter own notifications by read status and search title or message', function () {
    $role = Role::firstOrCreate(['name' => 'jamaah'], ['display_name' => 'Jamaah']);
    $user = User::factory()->create(['role_id' => $role->id]);
    $otherUser = User::factory()->create(['role_id' => $role->id]);

    createStoredNotification($user, 'Pengumuman baru', 'Jadwal kajian Tafsir malam ini.');
    createStoredNotification($user, 'Tafsir pekanan', 'Kegiatan sudah selesai.', now()->toDateTimeString());
    createStoredNotification($otherUser, 'Tafsir milik pengguna lain', 'Pesan privat.');

    $this->actingAs($user)->get(route('notifications.index', [
        'search' => 'Tafsir',
        'read' => 'unread',
    ]))
        ->assertOk()
        ->assertSee('Jadwal kajian Tafsir malam ini.')
        ->assertDontSee('Tafsir pekanan')
        ->assertDontSee('Tafsir milik pengguna lain')
        ->assertSee('value="Tafsir"', false)
        ->assertSee('value="unread" selected', false);
});

test('notification filters remain in pagination links and reset clears them', function () {
    $role = Role::firstOrCreate(['name' => 'jamaah'], ['display_name' => 'Jamaah']);
    $user = User::factory()->create(['role_id' => $role->id]);

    foreach (range(1, 16) as $index) {
        createStoredNotification($user, "Kajian {$index}", 'Informasi kajian mingguan.');
    }

    $response = $this->actingAs($user)->get(route('notifications.index', [
        'search' => 'Kajian',
        'read' => 'unread',
    ]));

    $response->assertOk()
        ->assertSee('search=Kajian', false)
        ->assertSee('read=unread', false)
        ->assertSee('page=2', false)
        ->assertSee(route('notifications.index'), false);
});

test('notification read filter rejects unsupported values', function () {
    $role = Role::firstOrCreate(['name' => 'jamaah'], ['display_name' => 'Jamaah']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user)->get(route('notifications.index', ['read' => 'archived']))
        ->assertSessionHasErrors('read');
});
