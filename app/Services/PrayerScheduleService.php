<?php

namespace App\Services;

use App\Models\PrayerSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PrayerScheduleService
{
    public function syncDate(?Carbon $date = null): int
    {
        $date = $date?->copy() ?? now();

        $latitude = (float) config('services.prayer_api.latitude');
        $longitude = (float) config('services.prayer_api.longitude');
        $method = config('services.prayer_api.method', 20);
        $timezone = config('services.prayer_api.timezone', 'Asia/Jakarta');

        if (!$latitude || !$longitude) {
            throw new RuntimeException(
                'Koordinat masjid belum dikonfigurasi di file .env.'
            );
        }

        $formattedDate = $date->format('d-m-Y');

        $url = rtrim(
            config('services.prayer_api.base_url'),
            '/'
        ) . "/timings/{$formattedDate}";

        $response = Http::acceptJson()
            ->timeout(15)
            ->get($url, [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'method' => $method,
                'timezonestring' => $timezone,
            ]);

        if ($response->failed()) {

            throw new RuntimeException(
                'API gagal. HTTP ' .
                $response->status() .
                ' - ' .
                $response->body()
            );
        }

        $data = $response->json();

        if (
            !isset($data['code']) ||
            $data['code'] !== 200 ||
            !isset($data['data']['timings'])
        ) {
            throw new RuntimeException(
                'Format data dari API jadwal sholat tidak valid.'
            );
        }

        $timings = $data['data']['timings'];

        $prayers = [
            'subuh' => $timings['Fajr'] ?? null,
            'dzuhur' => $timings['Dhuhr'] ?? null,
            'ashar' => $timings['Asr'] ?? null,
            'maghrib' => $timings['Maghrib'] ?? null,
            'isya' => $timings['Isha'] ?? null,
        ];

        $saved = 0;

        foreach ($prayers as $prayerName => $prayerTime) {

            if (!$prayerTime) {
                continue;
            }

            PrayerSchedule::updateOrCreate(
                [
                    'schedule_date' => $date->toDateString(),
                    'prayer_name' => $prayerName,
                ],
                [
                    'prayer_time' => $prayerTime,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'source' => 'aladhan',
                    'is_active' => true,
                ]
            );

            $saved++;
        }

        return $saved;
    }
}