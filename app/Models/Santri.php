<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Santri extends Model
{
    protected $fillable = [
        'registration_number',
        'name',
        'gender',
        'birth_date',
        'parent_name',
        'parent_phone',
        'address',
        'tpq_class_id',
        'enrollment_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'enrollment_date' => 'date',
        ];
    }

    public function tpqClass(): BelongsTo
    {
        return $this->belongsTo(TpqClass::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(SantriAttendance::class);
    }
}
