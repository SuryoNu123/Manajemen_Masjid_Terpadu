<?php

namespace App\Console\Commands;

use App\Services\PrayerScheduleService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

class SyncPrayerSchedules extends Command
{
    protected $signature = 'prayers:sync {date?}';

    protected $description = 'Mengambil jadwal sholat dari API dan menyimpannya ke database';

    public function handle(PrayerScheduleService $service): int
    {
        try {

            $dateArgument = $this->argument('date');

            $date = $dateArgument
                ? Carbon::createFromFormat('Y-m-d', $dateArgument)
                : now();

            $count = $service->syncDate($date);

            $this->info(
                "Berhasil menyimpan {$count} jadwal sholat untuk "
                .$date->format('Y-m-d')
            );

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error(
                'Gagal melakukan sinkronisasi: '.$e->getMessage()
            );

            return self::FAILURE;
        }
    }
}
