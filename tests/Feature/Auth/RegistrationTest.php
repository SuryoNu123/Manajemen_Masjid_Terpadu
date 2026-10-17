<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('registration screen can be rendered without exposing a role field', function () {
    $response = $this->get('/register');

    $response->assertOk()
        ->assertSee('Daftarkan akun Jamaah Anda ke sistem')
        ->assertSee('name="name"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="password_confirmation"', false)
        ->assertSee('name="_token"', false)
        ->assertDontSee('name="role_id"', false);
});

test('new accounts are created as Jamaah and cannot choose an administrative role', function () {
    $jamaahRole = Role::query()->create(['name' => 'jamaah', 'display_name' => 'Jamaah']);
    $ketuaRole = Role::query()->create(['name' => 'ketua', 'display_name' => 'Ketua DKM']);

    $this->get('/register');
    $previousSessionId = session()->getId();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => '  TEST@example.com ',
        'password' => 'SecurePassword123!',
        'password_confirmation' => 'SecurePassword123!',
        'role_id' => $ketuaRole->id,
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
    $user = User::query()->where('email', 'test@example.com')->firstOrFail();

    $this->assertAuthenticatedAs($user);
    $this->assertModelExists($user);
    expect($user->role->is($jamaahRole))->toBeTrue();
    expect(Hash::check('SecurePassword123!', $user->password))->toBeTrue();
    expect($user->password)->not->toBe('SecurePassword123!');
    expect(session()->getId())->not->toBe($previousSessionId);

    $this->get('/dashboard')->assertOk()->assertViewIs('dashboard.jamaah');
});

test('registration rejects a duplicate email without creating another account', function () {
    Role::query()->create(['name' => 'jamaah', 'display_name' => 'Jamaah']);
    User::factory()->create(['email' => 'Existing@example.com']);

    $response = $this->from('/register')->post('/register', [
        'name' => 'Another User',
        'email' => 'existing@example.com',
        'password' => 'SecurePassword123!',
        'password_confirmation' => 'SecurePassword123!',
    ]);

    $response->assertRedirect('/register')
        ->assertSessionHasErrors(['email' => 'Email sudah terdaftar.']);
    $this->assertDatabaseCount('users', 1);
    $this->assertGuest();
});

test('registration rejects missing required fields and does not flash passwords', function () {
    $response = $this->from('/register')->post('/register', [
        'role_id' => 1,
    ]);

    $response->assertRedirect('/register')
        ->assertSessionHasErrors(['name', 'email', 'password']);
    $response->assertSessionMissing('_old_input.password');
    $response->assertSessionMissing('_old_input.password_confirmation');
    $this->assertDatabaseCount('users', 0);
});

test('registration rejects malformed email and mismatched password confirmation', function () {
    Role::query()->create(['name' => 'jamaah', 'display_name' => 'Jamaah']);

    $response = $this->from('/register')->post('/register', [
        'name' => 'Test User',
        'email' => 'not-an-email',
        'password' => 'SecurePassword123!',
        'password_confirmation' => 'different-password',
    ]);

    $response->assertRedirect('/register')
        ->assertSessionHasErrors(['email', 'password']);
    $this->assertDatabaseCount('users', 0);
});

test('registration rejects passwords below the application security rule', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'weak',
        'password_confirmation' => 'weak',
    ]);

    $response->assertRedirect('/register')->assertSessionHasErrors(['password']);
    $this->assertDatabaseCount('users', 0);
});

test('registration rejects non-string email input without a server error', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => 'Test User',
        'email' => ['test@example.com'],
        'password' => 'SecurePassword123!',
        'password_confirmation' => 'SecurePassword123!',
    ]);

    $response->assertRedirect('/register')->assertSessionHasErrors(['email']);
    $this->assertDatabaseCount('users', 0);
});

test('public registration requests are rate limited', function () {
    foreach (range(1, 10) as $attempt) {
        $this->post('/register', []);
    }

    $this->post('/register', [])->assertTooManyRequests();
    $this->assertDatabaseCount('users', 0);
});

test('registration stays unavailable without the Jamaah role and creates no account', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'SecurePassword123!',
        'password_confirmation' => 'SecurePassword123!',
    ]);

    $response->assertServiceUnavailable();
    $this->assertDatabaseCount('users', 0);
});
