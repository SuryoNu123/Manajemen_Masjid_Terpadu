<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'title',
        'type',
        'description',
        'activity_date',
        'start_time',
        'end_time',
        'location',
        'speaker',
        'status',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'is_public' => 'boolean',
        ];
    }

    public function officers()
    {
        return $this->belongsToMany(
            Officer::class,
            'activity_officer'
        )->withPivot('assignment')->withTimestamps();
    }
}