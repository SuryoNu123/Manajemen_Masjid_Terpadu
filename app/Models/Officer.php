<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    protected $fillable = [
        'name',
        'position',
        'phone',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function activities()
    {
        return $this->belongsToMany(
            Activity::class,
            'activity_officer'
        )->withPivot('assignment')->withTimestamps();
    }
}