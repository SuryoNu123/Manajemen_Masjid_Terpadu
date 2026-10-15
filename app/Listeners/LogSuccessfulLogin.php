<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Database\Eloquent\Model;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;
        $roleName = $user->role?->display_name ?? 'Pengguna';

        ActivityLogger::log(
            'login',
            "Pengguna {$user->name} ({$roleName}) berhasil masuk ke sistem.",
            $user instanceof Model ? $user : null,
            (int) $user->getAuthIdentifier()
        );
    }
}
