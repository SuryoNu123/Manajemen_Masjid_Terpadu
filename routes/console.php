<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('activities:send-reminders')
    ->hourly();

Schedule::command('prayers:sync')
    ->dailyAt('00:05')
    ->timezone('Asia/Jakarta');
