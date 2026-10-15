<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YouthProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'division',
        'description',
        'start_date',
        'end_date',
        'youth_member_id',
        'budget',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'budget' => 'decimal:2',
        ];
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(YouthMember::class, 'youth_member_id');
    }
}

