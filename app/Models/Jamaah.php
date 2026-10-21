<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jamaah extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domicile',
        'birth_date',
        'phone',
        'status',
        'notes',
        'user_id',
        'whatsapp_opt_in',
        'whatsapp_opt_in_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'whatsapp_opt_in' => 'boolean',
            'whatsapp_opt_in_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prayerAttendances(): HasMany
    {
        return $this->hasMany(PrayerAttendance::class);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date
            ? $this->birth_date->age
            : null;
    }
}
