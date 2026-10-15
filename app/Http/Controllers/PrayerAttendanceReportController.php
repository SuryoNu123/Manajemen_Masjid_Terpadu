<?php

namespace App\Http\Controllers;

use App\Models\PrayerAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrayerAttendanceReportController extends Controller
{
    private const PRAYERS = [
        'subuh',
        'dzuhur',
        'ashar',
        'maghrib',
        'isya',
    ];

    private const STATUSES = [
        'hadir',
        'izin',
        'sakit',
    ];

    public function index(Request $request)
    {
        $validated = $request->validate([
            'period' => ['nullable', 'in:weekly,monthly'],
            'reference_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
        ]);

        $period = $validated['period'] ?? 'weekly';

        $referenceDate = Carbon::parse(
            $validated['reference_date'] ?? today()->toDateString()
        )->startOfDay();

        if ($period === 'monthly') {
            $startDate = $referenceDate->copy()->startOfMonth();
            $periodEnd = $referenceDate->copy()->endOfMonth();
        } else {
            $startDate = $referenceDate->copy()->startOfWeek(
                Carbon::MONDAY
            );

            $periodEnd = $referenceDate->copy()->endOfWeek(
                Carbon::SUNDAY
            );
        }

        // Jangan masukkan tanggal yang belum terjadi dalam laporan.
        $today = today()->startOfDay();

        $endDate = $periodEnd->greaterThan($today)
            ? $today->copy()
            : $periodEnd->copy();

        $records = PrayerAttendance::query()
            ->whereBetween('attendance_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->get();

        $summaryByPrayer = [];

        foreach (self::PRAYERS as $prayer) {
            $summaryByPrayer[$prayer] = [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'total' => 0,
            ];
        }

        $totalByStatus = [
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
        ];

        $dailySummary = [];

        foreach ($records as $record) {
            $prayer = $record->prayer_name;
            $status = $record->status;
            $date = $record->attendance_date->toDateString();

            if (
                ! isset($summaryByPrayer[$prayer]) ||
                ! isset($totalByStatus[$status])
            ) {
                continue;
            }

            $summaryByPrayer[$prayer][$status]++;
            $summaryByPrayer[$prayer]['total']++;
            $totalByStatus[$status]++;

            if (! isset($dailySummary[$date][$prayer])) {
                $dailySummary[$date][$prayer] = [
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'total' => 0,
                ];
            }

            $dailySummary[$date][$prayer][$status]++;
            $dailySummary[$date][$prayer]['total']++;
        }

        $dailyRows = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->toDateString();

            foreach (self::PRAYERS as $prayer) {
                $counts = $dailySummary[$dateString][$prayer] ?? [
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'total' => 0,
                ];

                // Tampilkan rincian tanggal yang memiliki catatan.
                if ($counts['total'] > 0) {
                    $dailyRows[] = [
                        'date' => $dateString,
                        'prayer_name' => $prayer,
                        ...$counts,
                    ];
                }
            }

            $currentDate->addDay();
        }

        return view('prayer-attendance-reports.index', [
            'period' => $period,
            'referenceDate' => $referenceDate,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summaryByPrayer' => $summaryByPrayer,
            'totalByStatus' => $totalByStatus,
            'totalRecords' => $records->count(),
            'dailyRows' => $dailyRows,
        ]);
    }
}
