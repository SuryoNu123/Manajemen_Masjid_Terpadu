<?php

use App\Models\Jamaah;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

function userWithNamedRole(string $roleName): User
{
    $role = Role::firstOrCreate(['name' => $roleName], ['display_name' => ucfirst($roleName)]);

    return User::factory()->create(['role_id' => $role->id]);
}

test('ketua can create a hashed account with a valid jamaah link', function () {
    $chair = userWithNamedRole('ketua');
    $jamaahRole = Role::firstOrCreate(['name' => 'jamaah'], ['display_name' => 'Jamaah']);
    $jamaah = Jamaah::create(['name' => 'Siti', 'domicile' => 'Bandung', 'status' => 'aktif']);

    $response = $this->actingAs($chair)->post(route('user-administration.store'), [
        'name' => 'Siti Jamaah',
        'email' => 'siti@example.test',
        'password' => 'StrongPassword123!',
        'password_confirmation' => 'StrongPassword123!',
        'role_id' => $jamaahRole->id,
        'is_active' => '1',
        'jamaah_id' => $jamaah->id,
    ]);

    $user = User::where('email', 'siti@example.test')->firstOrFail();
    $response->assertRedirect(route('user-administration.show', $user));
    expect(Hash::check('StrongPassword123!', $user->password))->toBeTrue();
    expect($jamaah->fresh()->user_id)->toBe($user->id);
    $this->assertDatabaseHas('activity_logs', ['action' => 'create_user']);
});

test('non-chair roles cannot access user administration', function (string $roleName) {
    $user = userWithNamedRole($roleName);

    $this->actingAs($user)->get(route('user-administration.index'))->assertForbidden();
})->with(['bendahara', 'sekretaris', 'marbot', 'jamaah']);

test('chair cannot change their own role or deactivate their own account', function () {
    $chair = userWithNamedRole('ketua');
    $originalRoleId = $chair->role_id;
    $otherRole = Role::firstOrCreate(['name' => 'bendahara'], ['display_name' => 'Bendahara']);

    $this->actingAs($chair)->put(route('user-administration.update', $chair), [
        'name' => $chair->name,
        'email' => $chair->email,
        'role_id' => $otherRole->id,
        'is_active' => '1',
    ])->assertForbidden();

    $this->actingAs($chair)->put(route('user-administration.update', $chair), [
        'name' => $chair->name,
        'email' => $chair->email,
        'role_id' => $chair->role_id,
        'is_active' => '0',
    ])->assertSessionHasErrors('is_active');

    expect($chair->fresh()->is_active)->toBeTrue();
    expect($chair->fresh()->role_id)->toBe($originalRoleId);
});

test('disabled accounts cannot authenticate', function () {
    $role = Role::firstOrCreate(['name' => 'jamaah'], ['display_name' => 'Jamaah']);
    User::factory()->create([
        'email' => 'disabled@example.test',
        'password' => 'StrongPassword123!',
        'role_id' => $role->id,
        'is_active' => false,
    ]);

    $this->post(route('login'), [
        'email' => 'disabled@example.test',
        'password' => 'StrongPassword123!',
    ])->assertSessionHasErrors('email');
});

test('account detail page never exposes a password', function () {
    $chair = userWithNamedRole('ketua');
    $target = userWithNamedRole('bendahara');

    $this->actingAs($chair)->get(route('user-administration.show', $target))
        ->assertOk()
        ->assertDontSee($target->password)
        ->assertDontSee('password_confirmation');
});

test('chair sends an expiring password reset link instead of viewing or setting a user password', function () {
    Notification::fake();
    $chair = userWithNamedRole('ketua');
    $target = userWithNamedRole('bendahara');

    $this->actingAs($chair)->post(route('user-administration.password-reset', $target))
        ->assertRedirect()
        ->assertSessionHas('success');

    Notification::assertSentTo($target, ResetPassword::class);
});
