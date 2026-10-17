<?php

use App\Models\Role;
use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertOk()
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertSee('name="_token"', false)
        ->assertSee('Daftar sekarang');
});

test('users can authenticate using the login screen and receive a fresh session', function () {
    $role = Role::query()->create(['name' => 'jamaah', 'display_name' => 'Jamaah']);
    $user = User::factory()->for($role)->create();

    $this->get('/login');
    $previousSessionId = session()->getId();

    $response = $this->post('/login', [
        'email' => strtoupper($user->email),
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard', absolute: false));
    expect(session()->getId())->not->toBe($previousSessionId);

    $this->get('/dashboard')->assertOk()->assertViewIs('dashboard.jamaah');
});

test('existing accounts with mixed-case emails can still sign in', function () {
    $user = User::factory()->create(['email' => 'Legacy.User@Example.com']);

    $this->post('/login', [
        'email' => 'legacy.user@example.com',
        'password' => 'password',
    ])->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);
});

test('legacy accounts without a role receive jamaah access only', function () {
    $user = User::factory()->create(['role_id' => null]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);
    $this->get('/dashboard')->assertOk()->assertViewIs('dashboard.jamaah');
    $this->get('/transaksi-keuangan')->assertForbidden();
    expect($user->fresh()->role_id)->toBeNull();
});

test('incorrect password and unknown email receive the same generic error', function () {
    $user = User::factory()->create();
    $message = 'Email atau kata sandi yang Anda masukkan tidak sesuai.';

    $knownAccountResponse = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);
    $knownAccountResponse->assertSessionHasErrors(['email' => $message]);

    $unknownAccountResponse = $this->from('/login')->post('/login', [
        'email' => 'unknown@example.com',
        'password' => 'wrong-password',
    ]);
    $unknownAccountResponse->assertSessionHasErrors(['email' => $message]);

    $this->assertGuest();
});

test('login validates empty required fields with clear messages', function () {
    $response = $this->from('/login')->post('/login', []);

    $response->assertRedirect('/login')->assertSessionHasErrors([
        'email' => 'Email wajib diisi.',
        'password' => 'Kata sandi wajib diisi.',
    ]);
    $this->assertGuest();
});

test('login rejects non-string credentials with validation errors', function () {
    $response = $this->from('/login')->post('/login', [
        'email' => ['admin@example.com'],
        'password' => ['not-a-password'],
    ]);

    $response->assertRedirect('/login')->assertSessionHasErrors(['email', 'password']);
    $this->assertGuest();
});

test('authenticated users are redirected away from login and registration screens', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/login')
        ->assertRedirect(route('dashboard'));

    $this->actingAs($user)
        ->get('/register')
        ->assertRedirect(route('dashboard'));
});

test('login attempts are rate limited after five failures', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $attempt) {
        $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
    }

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors(['email']);
    expect(session('errors')->first('email'))->toContain('Terlalu banyak percobaan masuk.');
    $this->assertGuest();
});

test('users can logout and cannot reopen protected pages with the ended session', function () {
    $user = User::factory()->create();
    $previousToken = session()->token();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/')->assertSessionHas('_token');
    expect(session()->token())->not->toBe($previousToken);
    $this->get('/dashboard')->assertRedirect(route('login'));
});
