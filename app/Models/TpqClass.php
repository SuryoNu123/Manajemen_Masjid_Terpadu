<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TpqClass extends Model
{
    protected $fillable = [
        'name',
        'level',
        'schedule',
        'teacher_name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class);
    }
}
