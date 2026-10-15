<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YouthMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'name',
        'division',
        'position',
        'phone',
        'birth_date',
        'status',
        'joined_year',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'joined_year' => 'integer',
        ];
    }

    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(YouthProgram::class);
    }
}

