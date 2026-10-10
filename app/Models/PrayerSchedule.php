<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerSchedule extends Model
{
    protected $fillable = [
        'schedule_date',
        'prayer_name',
        'prayer_time',
        'latitude',
        'longitude',
        'source',
        'is_active',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];
}