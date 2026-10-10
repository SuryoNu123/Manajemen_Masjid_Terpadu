<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrayerScheduleRequest;
use App\Http\Requests\UpdatePrayerScheduleRequest;
use App\Models\PrayerSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\PrayerScheduleService;
use Carbon\Carbon;
use Illuminate\View\View;

class PrayerScheduleController extends Controller
{
    private function prayerOrder(): array
    {
        return [
            'subuh' => 1,
            'dzuhur' => 2,
            'ashar' => 3,
            'maghrib' => 4,
            'isya' => 5,
        ];
    }

    public function index(Request $request): View
    {
        $date = $request->get(
            'date',
            now()->toDateString()
        );

        $schedules = PrayerSchedule::query()
            ->whereDate('schedule_date', $date)
            ->orderByRaw("
                FIELD(
                    prayer_name,
                    'subuh',
                    'dzuhur',
                    'ashar',
                    'maghrib',
                    'isya'
                )
            ")
            ->get();

        return view(
            'prayer-schedules.index',
            compact('schedules', 'date')
        );
    }

    public function create(): View
    {
        return view('prayer-schedules.create');
    }

    public function store(
        StorePrayerScheduleRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        $data['source'] =
            $data['source'] ?? 'manual';

        $data['is_active'] =
            $request->boolean('is_active', true);

        PrayerSchedule::updateOrCreate(
            [
                'schedule_date' => $data['schedule_date'],
                'prayer_name' => $data['prayer_name'],
            ],
            [
                'prayer_time' => $data['prayer_time'],
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'source' => $data['source'],
                'is_active' => $data['is_active'],
            ]
        );

        return redirect()
            ->route('jadwal-sholat.index', [
                'date' => $data['schedule_date'],
            ])
            ->with(
                'success',
                'Jadwal sholat berhasil disimpan.'
            );
    }

    public function edit(
        PrayerSchedule $jadwalSholat
    ): View {
        return view(
            'prayer-schedules.edit',
            compact('jadwalSholat')
        );
    }

    public function update(
        UpdatePrayerScheduleRequest $request,
        PrayerSchedule $jadwalSholat
    ): RedirectResponse {
        $data = $request->validated();

        $data['source'] =
            $data['source'] ?? 'manual';

        $data['is_active'] =
            $request->boolean('is_active');

        $jadwalSholat->update([
            'schedule_date' => $data['schedule_date'],
            'prayer_name' => $data['prayer_name'],
            'prayer_time' => $data['prayer_time'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'source' => $data['source'],
            'is_active' => $data['is_active'],
        ]);

        return redirect()
            ->route('jadwal-sholat.index', [
                'date' => $data['schedule_date'],
            ])
            ->with(
                'success',
                'Jadwal sholat berhasil diperbarui.'
            );
    }

    public function destroy(
        PrayerSchedule $jadwalSholat
    ): RedirectResponse {
        $date = $jadwalSholat
            ->schedule_date
            ->format('Y-m-d');

        $jadwalSholat->delete();

        return redirect()
            ->route('jadwal-sholat.index', [
                'date' => $date,
            ])
            ->with(
                'success',
                'Jadwal sholat berhasil dihapus.'
            );
    }

    public function sync(PrayerScheduleService $service)
    {
        try {

            $count = $service->syncDate(Carbon::today());

            return redirect()
                ->route('prayer-schedules.index')
                ->with(
                    'success',
                    "Berhasil memperbarui {$count} jadwal sholat hari ini."
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('prayer-schedules.index')
                ->with(
                    'error',
                    'Gagal mengambil jadwal sholat: ' . $e->getMessage()
                );
        }
    }
}