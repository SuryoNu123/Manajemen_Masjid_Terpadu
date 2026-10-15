<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mustahik extends Model
{
    protected $fillable = [
        'name',
        'asnaf',
        'address',
        'phone',
        'amount',
        'status',
        'distributed_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'distributed_at' => 'date',
    ];
}
