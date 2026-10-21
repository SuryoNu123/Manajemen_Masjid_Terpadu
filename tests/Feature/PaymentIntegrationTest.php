<?php

use App\Models\Donation;
use App\Models\FinancialCategory;
use App\Models\Role;
use App\Models\User;
use App\Services\Payments\PaymentGatewayProvider;
use App\Services\Payments\PaymentIntent;
use App\Services\Payments\VerifiedPaymentEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FakePaymentGatewayProvider implements PaymentGatewayProvider
{
    public static ?VerifiedPaymentEvent $event = null;

    public static int $createCalls = 0;

    public function createPayment(Donation $donation): PaymentIntent
    {
        self::$createCalls++;

        return new PaymentIntent('TEST-REF-'.$donation->donation_code, 'https://sandbox.example.test/pay', 'https://sandbox.example.test/qr');
    }

    public function verifyWebhook(Request $request): VerifiedPaymentEvent
    {
        abort_unless($request->header('X-Fake-Signature') === 'valid-test-signature', 401);

        return self::$event ?? throw new RuntimeException('No fake payment event was configured.');
    }
}

function prepareFakePaymentGateway(): void
{
    config(['services.payment_gateway.adapter' => FakePaymentGatewayProvider::class]);
    FakePaymentGatewayProvider::$event = null;
    FakePaymentGatewayProvider::$createCalls = 0;
}

test('qris donations create a provider payment intent and a secret status link', function () {
    prepareFakePaymentGateway();

    $response = $this->post(route('donasi.store'), [
        'donor_name' => 'Aminah',
        'type' => 'infaq',
        'amount' => 25000,
        'payment_method' => 'qris',
    ]);

    $donation = Donation::firstOrFail();
    $response->assertRedirect(route('donasi.success', ['donasi' => $donation, 'token' => $donation->status_token]));
    expect(FakePaymentGatewayProvider::$createCalls)->toBe(1);
    expect($donation->gateway_reference)->toBe('TEST-REF-'.$donation->donation_code);
    expect($donation->gateway_payment_url)->toBe('https://sandbox.example.test/pay');
    expect($donation->payment_status)->toBe('pending');
    $this->assertDatabaseCount('financial_transactions', 0);
});

test('qris donation stays pending with a visible unconfigured state when no provider is selected', function () {
    config(['services.payment_gateway.adapter' => null]);

    $response = $this->post(route('donasi.store'), [
        'type' => 'zakat',
        'amount' => 30000,
        'payment_method' => 'qris',
    ]);

    $donation = Donation::firstOrFail();
    expect($donation->payment_status)->toBe('pending');
    expect($donation->gateway_reference)->toBeNull();
    expect($donation->financial_transaction_id)->toBeNull();
    $response->assertRedirect(route('donasi.success', ['donasi' => $donation, 'token' => $donation->status_token]));
    $this->get(route('donasi.success', ['donasi' => $donation, 'token' => $donation->status_token]))
        ->assertOk()
        ->assertSee('belum berhasil disiapkan');
    $this->assertDatabaseCount('financial_transactions', 0);
});

test('qris donation status requires the secret token', function () {
    $donation = Donation::create([
        'donation_code' => 'DON-STATUS-'.Str::upper(Str::random(8)),
        'status_token' => (string) Str::uuid(),
        'type' => 'infaq',
        'amount' => 10000,
        'payment_method' => 'qris',
        'payment_status' => 'pending',
    ]);

    $this->get(route('donasi.success', $donation))->assertNotFound();
    $this->get(route('donasi.success', ['donasi' => $donation, 'token' => $donation->status_token]))->assertOk();
});

test('valid payment callback records one financial transaction and repeated callback is idempotent', function () {
    prepareFakePaymentGateway();
    $donationCode = 'DON-WEBHOOK-'.Str::upper(Str::random(8));
    $donation = Donation::create([
        'donation_code' => $donationCode,
        'status_token' => (string) Str::uuid(),
        'type' => 'infaq',
        'amount' => 50000,
        'payment_method' => 'qris',
        'payment_status' => 'pending',
        'gateway_reference' => 'GW-REF-'.Str::random(8),
    ]);
    FinancialCategory::create(['name' => 'Infaq', 'type' => 'income']);
    FakePaymentGatewayProvider::$event = new VerifiedPaymentEvent(
        'event-001', $donation->gateway_reference, $donationCode, '50000.00', 'IDR', 'paid', 'payment.succeeded'
    );

    $this->call('POST', route('webhooks.payments'), [], [], [], ['HTTP_X_FAKE_SIGNATURE' => 'valid-test-signature'], '{"fake":"event"}')->assertNoContent();
    $this->call('POST', route('webhooks.payments'), [], [], [], ['HTTP_X_FAKE_SIGNATURE' => 'valid-test-signature'], '{"fake":"event"}')->assertNoContent();

    expect($donation->fresh()->payment_status)->toBe('paid');
    expect($donation->fresh()->financial_transaction_id)->not->toBeNull();
    $this->assertDatabaseCount('financial_transactions', 1);
    $this->assertDatabaseCount('payment_webhook_events', 1);
});

test('late verified payment success supersedes an earlier failed state', function () {
    prepareFakePaymentGateway();
    $donation = Donation::create([
        'donation_code' => 'DON-LATE-'.Str::upper(Str::random(8)),
        'status_token' => (string) Str::uuid(),
        'type' => 'sedekah',
        'amount' => 12000,
        'payment_method' => 'qris',
        'payment_status' => 'failed',
        'gateway_reference' => 'GW-LATE-'.Str::random(8),
    ]);
    FinancialCategory::create(['name' => 'Sedekah', 'type' => 'income']);
    FakePaymentGatewayProvider::$event = new VerifiedPaymentEvent(
        'late-paid-event', $donation->gateway_reference, $donation->donation_code, '12000.00', 'IDR', 'paid', 'payment.succeeded'
    );

    $this->call('POST', route('webhooks.payments'), [], [], [], ['HTTP_X_FAKE_SIGNATURE' => 'valid-test-signature'], '{"event":"late-paid"}')->assertNoContent();

    expect($donation->fresh()->payment_status)->toBe('paid');
    $this->assertDatabaseCount('financial_transactions', 1);
});

test('verified callback with a mismatched amount is rejected without changing records', function () {
    prepareFakePaymentGateway();
    $donation = Donation::create([
        'donation_code' => 'DON-AMOUNT-'.Str::upper(Str::random(8)),
        'status_token' => (string) Str::uuid(),
        'type' => 'infaq',
        'amount' => 50000,
        'payment_method' => 'qris',
        'payment_status' => 'pending',
        'gateway_reference' => 'GW-AMOUNT-'.Str::random(8),
    ]);
    FakePaymentGatewayProvider::$event = new VerifiedPaymentEvent(
        'wrong-amount-event', $donation->gateway_reference, $donation->donation_code, '500000.00', 'IDR', 'paid', 'payment.succeeded'
    );

    $this->call('POST', route('webhooks.payments'), [], [], [], ['HTTP_X_FAKE_SIGNATURE' => 'valid-test-signature'], '{"event":"wrong-amount"}')->assertSessionHasErrors('payment');

    expect($donation->fresh()->payment_status)->toBe('pending');
    $this->assertDatabaseCount('financial_transactions', 0);
    $this->assertDatabaseCount('payment_webhook_events', 0);
});

test('verified expired callback updates status without creating cash income', function () {
    prepareFakePaymentGateway();
    $donation = Donation::create([
        'donation_code' => 'DON-EXPIRED-'.Str::upper(Str::random(8)),
        'status_token' => (string) Str::uuid(),
        'type' => 'qurban',
        'amount' => 100000,
        'payment_method' => 'qris',
        'payment_status' => 'pending',
        'gateway_reference' => 'GW-EXPIRED-'.Str::random(8),
    ]);
    FakePaymentGatewayProvider::$event = new VerifiedPaymentEvent(
        'expired-event', $donation->gateway_reference, $donation->donation_code, '100000.00', 'IDR', 'expired', 'payment.expired'
    );

    $this->call('POST', route('webhooks.payments'), [], [], [], ['HTTP_X_FAKE_SIGNATURE' => 'valid-test-signature'], '{"event":"expired"}')->assertNoContent();

    expect($donation->fresh()->payment_status)->toBe('expired');
    expect($donation->fresh()->financial_transaction_id)->toBeNull();
    $this->assertDatabaseCount('financial_transactions', 0);
});

test('invalid callback signature cannot change donation status or record income', function () {
    prepareFakePaymentGateway();
    $donation = Donation::create([
        'donation_code' => 'DON-BAD-SIGNATURE-'.Str::upper(Str::random(8)),
        'status_token' => (string) Str::uuid(),
        'type' => 'infaq',
        'amount' => 50000,
        'payment_method' => 'qris',
        'payment_status' => 'pending',
        'gateway_reference' => 'GW-BAD-'.Str::random(8),
    ]);
    FakePaymentGatewayProvider::$event = new VerifiedPaymentEvent(
        'event-bad', $donation->gateway_reference, $donation->donation_code, '50000.00', 'IDR', 'paid', 'payment.succeeded'
    );

    $this->call('POST', route('webhooks.payments'), [], [], [], ['HTTP_X_FAKE_SIGNATURE' => 'invalid'], '{"fake":"event"}')->assertUnauthorized();

    expect($donation->fresh()->payment_status)->toBe('pending');
    $this->assertDatabaseCount('financial_transactions', 0);
    $this->assertDatabaseCount('payment_webhook_events', 0);
});

test('qris donations cannot be marked paid by a manual bendahara request', function () {
    $role = Role::firstOrCreate(['name' => 'bendahara'], ['display_name' => 'Bendahara']);
    $user = User::factory()->create(['role_id' => $role->id]);
    $donation = Donation::create([
        'donation_code' => 'DON-MANUAL-'.Str::upper(Str::random(8)),
        'status_token' => (string) Str::uuid(),
        'type' => 'infaq',
        'amount' => 25000,
        'payment_method' => 'qris',
        'payment_status' => 'pending',
    ]);

    $this->actingAs($user)->patch(route('donasi.mark-paid', $donation))->assertForbidden();
    expect($donation->fresh()->payment_status)->toBe('pending');
    $this->assertDatabaseCount('financial_transactions', 0);
});

test('offline donation confirmation is idempotent and links one income transaction', function () {
    $role = Role::firstOrCreate(['name' => 'bendahara'], ['display_name' => 'Bendahara']);
    $user = User::factory()->create(['role_id' => $role->id]);
    $category = FinancialCategory::create(['name' => 'Infaq', 'type' => 'income']);
    $donation = Donation::create([
        'donation_code' => 'DON-OFFLINE-'.Str::upper(Str::random(8)),
        'status_token' => (string) Str::uuid(),
        'type' => 'infaq',
        'amount' => 25000,
        'payment_method' => 'cash',
        'payment_status' => 'pending',
    ]);

    $this->actingAs($user)->patch(route('donasi.mark-paid', $donation))->assertRedirect();
    expect($donation->fresh()->payment_status)->toBe('paid');
    expect($donation->fresh()->financialTransaction->category_id)->toBe($category->id);
    $this->assertDatabaseCount('financial_transactions', 1);
});
