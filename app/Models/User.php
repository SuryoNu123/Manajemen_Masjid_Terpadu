<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $attributes = [
        'is_active' => true,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
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
            'is_active' => 'boolean',
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
        return $this->roleName() === $role;
    }

    public function roleName(): ?string
    {
        return $this->role?->name ?? ($this->role_id === null ? 'jamaah' : null);
    }

    public function roleDisplayName(): ?string
    {
        return $this->role?->display_name ?? ($this->role_id === null ? 'Jamaah' : null);
    }

    /**
     * Relasi User -> Transaksi Keuangan
     */
    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function jamaah(): HasOne
    {
        return $this->hasOne(Jamaah::class);
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
