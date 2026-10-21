<?php

use App\Jobs\SendWhatsAppDelivery;
use App\Models\Announcement;
use App\Models\Jamaah;
use App\Models\Role;
use App\Models\User;
use App\Models\WhatsAppDelivery;
use App\Services\WhatsApp\WhatsAppProvider;
use App\Services\WhatsApp\WhatsAppProviderManager;
use App\Services\WhatsApp\WhatsAppSendResult;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;

class FakeWhatsAppProviderForTest implements WhatsAppProvider
{
    public static array $messages = [];

    public static bool $shouldFail = false;

    public function send(string $recipient, string $message, string $idempotencyKey): WhatsAppSendResult
    {
        if (self::$shouldFail) {
            throw new RuntimeException('Fake provider timeout.');
        }

        self::$messages[] = compact('recipient', 'message', 'idempotencyKey');

        return new WhatsAppSendResult('fake-message-001');
    }
}

function createJamaahForWhatsApp(string $phone, bool $optIn): Jamaah
{
    return Jamaah::create([
        'name' => 'Jamaah Test',
        'domicile' => 'Kota Bandung',
        'phone' => $phone,
        'status' => 'aktif',
        'whatsapp_opt_in' => $optIn,
    ]);
}

test('published announcement records non-delivered status when WhatsApp is not configured', function () {
    config(['services.whatsapp.mode' => 'disabled', 'services.whatsapp.adapter' => null]);
    Queue::fake();
    Notification::fake();
    createJamaahForWhatsApp('081234567890', true);
    createJamaahForWhatsApp('081234567891', false);
    $role = Role::firstOrCreate(['name' => 'sekretaris'], ['display_name' => 'Sekretaris']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user)->post(route('announcements.store'), [
        'title' => 'Jadwal Kerja Bakti',
        'content' => 'Kerja bakti hari Ahad.',
        'type' => 'normal',
        'status' => 'published',
    ])->assertRedirect(route('announcements.index'));

    $this->assertDatabaseCount('whatsapp_deliveries', 1);
    $this->assertDatabaseHas('whatsapp_deliveries', ['status' => 'not_configured']);
    Queue::assertNothingPushed();
});

test('WhatsApp opt-in with invalid number is recorded without dispatching a message', function () {
    config(['services.whatsapp.mode' => 'provider', 'services.whatsapp.adapter' => FakeWhatsAppProviderForTest::class]);
    Queue::fake();
    $jamaah = createJamaahForWhatsApp('not-a-phone', true);
    $announcement = Announcement::create([
        'title' => 'Tes nomor', 'content' => 'Isi', 'type' => 'normal', 'status' => 'published', 'published_at' => now(),
    ]);

    app(WhatsAppNotificationService::class)->sendAnnouncement($announcement, app(WhatsAppProviderManager::class));

    $this->assertDatabaseHas('whatsapp_deliveries', ['jamaah_id' => $jamaah->id, 'status' => 'invalid_number']);
    Queue::assertNothingPushed();
});

test('configured adapter sends a consented message and records its provider reference', function () {
    config(['services.whatsapp.mode' => 'provider', 'services.whatsapp.adapter' => FakeWhatsAppProviderForTest::class]);
    FakeWhatsAppProviderForTest::$messages = [];
    FakeWhatsAppProviderForTest::$shouldFail = false;
    $jamaah = createJamaahForWhatsApp('081234567890', true);
    $announcement = Announcement::create([
        'title' => 'Info Kajian', 'content' => 'Kajian pekan ini.', 'type' => 'normal', 'status' => 'published', 'published_at' => now(),
    ]);
    $delivery = WhatsAppDelivery::create([
        'announcement_id' => $announcement->id,
        'jamaah_id' => $jamaah->id,
        'recipient_phone' => '+6281234567890',
        'status' => 'pending',
    ]);

    (new SendWhatsAppDelivery($delivery->id))->handle(app(WhatsAppProviderManager::class), app(WhatsAppNotificationService::class));

    expect($delivery->fresh()->status)->toBe('sent');
    expect($delivery->fresh()->provider_reference)->toBe('fake-message-001');
    expect(FakeWhatsAppProviderForTest::$messages[0]['recipient'])->toBe('+6281234567890');
    expect(FakeWhatsAppProviderForTest::$messages[0]['message'])->toContain('Info Kajian');
});

test('provider timeout is recorded as failed and does not claim successful delivery', function () {
    config(['services.whatsapp.mode' => 'provider', 'services.whatsapp.adapter' => FakeWhatsAppProviderForTest::class]);
    FakeWhatsAppProviderForTest::$shouldFail = true;
    $jamaah = createJamaahForWhatsApp('081234567890', true);
    $announcement = Announcement::create([
        'title' => 'Pengumuman Gagal', 'content' => 'Isi', 'type' => 'normal', 'status' => 'published', 'published_at' => now(),
    ]);
    $delivery = WhatsAppDelivery::create([
        'announcement_id' => $announcement->id,
        'jamaah_id' => $jamaah->id,
        'recipient_phone' => '+6281234567890',
        'status' => 'pending',
    ]);

    try {
        (new SendWhatsAppDelivery($delivery->id))->handle(app(WhatsAppProviderManager::class), app(WhatsAppNotificationService::class));
    } catch (RuntimeException) {
    }

    expect($delivery->fresh()->status)->toBe('retrying');
    (new SendWhatsAppDelivery($delivery->id))->failed(new RuntimeException('Fake provider timeout.'));
    expect($delivery->fresh()->status)->toBe('failed');
    expect($delivery->fresh()->provider_reference)->toBeNull();
});

test('queued WhatsApp delivery is cancelled when a jamaah withdraws consent', function () {
    config(['services.whatsapp.mode' => 'provider', 'services.whatsapp.adapter' => FakeWhatsAppProviderForTest::class]);
    FakeWhatsAppProviderForTest::$messages = [];
    FakeWhatsAppProviderForTest::$shouldFail = false;
    $jamaah = createJamaahForWhatsApp('081234567890', true);
    $announcement = Announcement::create([
        'title' => 'Privasi Jamaah', 'content' => 'Isi', 'type' => 'normal', 'status' => 'published', 'published_at' => now(),
    ]);
    $delivery = WhatsAppDelivery::create([
        'announcement_id' => $announcement->id,
        'jamaah_id' => $jamaah->id,
        'recipient_phone' => '+6281234567890',
        'status' => 'pending',
    ]);
    $jamaah->update(['whatsapp_opt_in' => false]);

    (new SendWhatsAppDelivery($delivery->id))->handle(app(WhatsAppProviderManager::class), app(WhatsAppNotificationService::class));

    expect($delivery->fresh()->status)->toBe('cancelled_opt_out');
    expect(FakeWhatsAppProviderForTest::$messages)->toBeEmpty();
});

test('simulated WhatsApp mode records a simulation but never queues delivery', function () {
    config(['services.whatsapp.mode' => 'simulated', 'services.whatsapp.adapter' => null]);
    Queue::fake();
    $jamaah = createJamaahForWhatsApp('081234567890', true);
    $announcement = Announcement::create([
        'title' => 'Simulasi', 'content' => 'Tidak dikirim.', 'type' => 'normal', 'status' => 'published', 'published_at' => now(),
    ]);

    app(WhatsAppNotificationService::class)->sendAnnouncement($announcement, app(WhatsAppProviderManager::class));

    $this->assertDatabaseHas('whatsapp_deliveries', ['jamaah_id' => $jamaah->id, 'status' => 'simulated']);
    Queue::assertNothingPushed();
});

test('jamaah can withdraw WhatsApp consent and the change is audited', function () {
    $role = Role::firstOrCreate(['name' => 'jamaah'], ['display_name' => 'Jamaah']);
    $user = User::factory()->create(['role_id' => $role->id]);
    $jamaah = Jamaah::create([
        'name' => $user->name,
        'domicile' => 'Bandung',
        'phone' => '081234567890',
        'status' => 'aktif',
        'user_id' => $user->id,
        'whatsapp_opt_in' => true,
        'whatsapp_opt_in_at' => now()->subDay(),
    ]);

    $this->actingAs($user)->put(route('whatsapp-preferences.update'), [])->assertRedirect();

    expect($jamaah->fresh()->whatsapp_opt_in)->toBeFalse();
    expect($jamaah->fresh()->whatsapp_opt_in_at)->toBeNull();
    $this->assertDatabaseHas('activity_logs', ['action' => 'jamaah_whatsapp_opt_out', 'subject_id' => $jamaah->id]);
});

test('jamaah without a valid phone cannot opt in to WhatsApp notices', function () {
    $role = Role::firstOrCreate(['name' => 'jamaah'], ['display_name' => 'Jamaah']);
    $user = User::factory()->create(['role_id' => $role->id]);
    $jamaah = Jamaah::create([
        'name' => $user->name,
        'domicile' => 'Bandung',
        'phone' => null,
        'status' => 'aktif',
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)->put(route('whatsapp-preferences.update'), ['whatsapp_opt_in' => '1'])
        ->assertSessionHasErrors('whatsapp_opt_in');

    expect($jamaah->fresh()->whatsapp_opt_in)->toBeFalse();
});
