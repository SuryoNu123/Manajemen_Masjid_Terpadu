<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\PrayerAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrayerAttendanceController extends Controller
{
    private const PRAYERS = [
        'subuh',
        'dzuhur',
        'ashar',
        'maghrib',
        'isya',
    ];

    public function index(Request $request)
    {
        $filters = $request->validate([
            'date' => ['nullable', 'date'],
            'prayer_name' => [
                'nullable',
                'in:subuh,dzuhur,ashar,maghrib,isya',
            ],
        ]);

        $date = $filters['date'] ?? now()->toDateString();
        $prayerName = $filters['prayer_name'] ?? 'subuh';

        $jamaahs = Jamaah::where('status', 'aktif')
            ->orderBy('name')
            ->get();

        $attendanceMap = PrayerAttendance::where(
            'attendance_date',
            $date
        )
            ->where('prayer_name', $prayerName)
            ->pluck('status', 'jamaah_id');

        return view('prayer-attendances.index', [
            'jamaahs' => $jamaahs,
            'attendanceMap' => $attendanceMap,
            'date' => $date,
            'prayerName' => $prayerName,
            'prayers' => self::PRAYERS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendance_date' => ['required', 'date'],
            'prayer_name' => [
                'required',
                'in:subuh,dzuhur,ashar,maghrib,isya',
            ],
            'attendance' => ['nullable', 'array'],
            'attendance.*' => [
                'nullable',
                'in:hadir,izin,sakit',
            ],
        ]);

        $date = $validated['attendance_date'];
        $prayerName = $validated['prayer_name'];
        $attendanceData = $validated['attendance'] ?? [];

        DB::transaction(function () use (
            $date,
            $prayerName,
            $attendanceData
        ) {
            foreach ($attendanceData as $jamaahId => $status) {
                if (! ctype_digit((string) $jamaahId)) {
                    continue;
                }

                $jamaah = Jamaah::whereKey((int) $jamaahId)
                    ->where('status', 'aktif')
                    ->exists();

                if (! $jamaah) {
                    continue;
                }

                $criteria = [
                    'jamaah_id' => (int) $jamaahId,
                    'attendance_date' => $date,
                    'prayer_name' => $prayerName,
                ];

                if ($status === null || $status === '') {
                    PrayerAttendance::where($criteria)->delete();

                    continue;
                }

                PrayerAttendance::updateOrCreate(
                    $criteria,
                    [
                        'status' => $status,
                        'recorded_by' => Auth::id(),
                    ]
                );
            }
        });

        return redirect()
            ->route('prayer-attendances.index', [
                'date' => $date,
                'prayer_name' => $prayerName,
            ])
            ->with('success', 'Presensi jamaah berhasil disimpan.');
    }
}
