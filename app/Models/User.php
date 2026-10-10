<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi User -> Role
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Mengecek role user
     */
    public function hasRole(string $role): bool
    {
        return $this->role?->name === $role;
    }

    /**
     * Relasi User -> Transaksi Keuangan
     */
    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    /**
     * Laporan yang dibuat/dikirim user
     */
    public function submittedFinancialReports(): HasMany
    {
        return $this->hasMany(
            FinancialReport::class,
            'submitted_by'
        );
    }

    /**
     * Laporan yang direview user
     */
    public function reviewedFinancialReports(): HasMany
    {
        return $this->hasMany(
            FinancialReport::class,
            'reviewed_by'
        );
    }
}