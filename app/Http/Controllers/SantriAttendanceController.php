<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\SantriAttendance;
use App\Models\TpqClass;
use App\Exports\SantriAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SantriAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', today()->toDateString());
        $classId = $request->input('class_id');

        $classes = TpqClass::orderBy('name')->get();

        $santris = collect();
        $attendanceRecords = collect();

        if ($classId) {
            $santris = Santri::where('tpq_class_id', $classId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            $attendanceRecords = SantriAttendance::whereIn(
                'santri_id',
                $santris->pluck('id')
            )
                ->whereDate('attendance_date', $date)
                ->get()
                ->keyBy('santri_id');
        }

        $report = SantriAttendance::query()
            ->whereDate('attendance_date', $date)
            ->when($classId, function ($query) use ($classId) {
                $query->whereHas('santri', function ($q) use ($classId) {
                    $q->where('tpq_class_id', $classId);
                });
            })
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('santri-attendances.index', compact(
            'date',
            'classId',
            'classes',
            'santris',
            'attendanceRecords',
            'report'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendance_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'class_id' => [
                'required',
                'exists:tpq_classes,id',
            ],
            'attendances' => ['required', 'array', 'min:1'],
            'attendances.*.santri_id' => [
                'required',
                'integer',
                'distinct',
                'exists:santris,id',
            ],
            'attendances.*.status' => [
                'required',
                Rule::in(['present', 'sick', 'permission', 'absent']),
            ],
            'attendances.*.notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $santriIds = collect($validated['attendances'])
            ->pluck('santri_id');

        $validIds = Santri::whereIn('id', $santriIds)
            ->where('tpq_class_id', $validated['class_id'])
            ->where('status', 'active')
            ->pluck('id');

        if ($validIds->count() !== $santriIds->count()) {
            return back()
                ->withInput()
                ->withErrors([
                    'attendances' => 'Daftar santri tidak sesuai dengan kelas yang dipilih.',
                ]);
        }

        DB::transaction(function () use ($validated) {
            foreach ($validated['attendances'] as $item) {
                SantriAttendance::updateOrCreate(
                    [
                        'santri_id' => $item['santri_id'],
                        'attendance_date' => $validated['attendance_date'],
                    ],
                    [
                        'status' => $item['status'],
                        'notes' => $item['notes'] ?? null,
                        'recorded_by' => Auth::id(),
                    ]
                );
            }
        });

        return redirect()
            ->route('santri-attendances.index', [
                'date' => $validated['attendance_date'],
                'class_id' => $validated['class_id'],
            ])
            ->with('success', 'Absensi santri berhasil disimpan.');
    }
   
    public function report(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date', 'before_or_equal:today'],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
                'before_or_equal:today',
            ],
            'class_id' => ['nullable', 'integer', 'exists:tpq_classes,id'],
        ]);

        $startDate = $validated['start_date'] ?? now()->startOfMonth()->toDateString();
        $endDate = $validated['end_date'] ?? today()->toDateString();
        $classId = $validated['class_id'] ?? null;

        $classes = \App\Models\TpqClass::orderBy('name')->get();

        $attendanceQuery = \App\Models\SantriAttendance::query()
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->when($classId, function ($query) use ($classId) {
                $query->whereHas('santri', function ($q) use ($classId) {
                    $q->where('tpq_class_id', $classId);
                });
            });

        $attendanceSummary = (clone $attendanceQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalRecords = (clone $attendanceQuery)->count();

        $totalPresent = (int) ($attendanceSummary['present'] ?? 0);
        $totalSick = (int) ($attendanceSummary['sick'] ?? 0);
        $totalPermission = (int) ($attendanceSummary['permission'] ?? 0);
        $totalAbsent = (int) ($attendanceSummary['absent'] ?? 0);

        $attendancePercentage = $totalRecords > 0
            ? round(($totalPresent / $totalRecords) * 100, 1)
            : 0;

        $classReports = \App\Models\TpqClass::query()
            ->withCount([
                'santris as active_santris_count' => function ($query) {
                    $query->where('status', 'active');
                },
            ])
            ->when($classId, function ($query) use ($classId) {
                $query->where('id', $classId);
            })
            ->with(['santris' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('attendances', function ($attendance) use ($startDate, $endDate) {
                    $attendance->whereBetween('attendance_date', [$startDate, $endDate]);
                });
            }])
            ->orderBy('name')
            ->get()
            ->map(function ($class) use ($startDate, $endDate) {
                $ids = $class->santris->pluck('id');

                $counts = \App\Models\SantriAttendance::query()
                    ->whereIn('santri_id', $ids)
                    ->whereBetween('attendance_date', [$startDate, $endDate])
                    ->selectRaw("
                        COUNT(*) as total,
                        SUM(status = 'present') as present,
                        SUM(status = 'sick') as sick,
                        SUM(status = 'permission') as permission,
                        SUM(status = 'absent') as absent
                    ")
                    ->first();

                $class->attendance_total = (int) ($counts->total ?? 0);
                $class->present_total = (int) ($counts->present ?? 0);
                $class->sick_total = (int) ($counts->sick ?? 0);
                $class->permission_total = (int) ($counts->permission ?? 0);
                $class->absent_total = (int) ($counts->absent ?? 0);

                $class->attendance_percentage = $class->attendance_total > 0
                    ? round(($class->present_total / $class->attendance_total) * 100, 1)
                    : 0;

                return $class;
            });

        $recentAttendances = (clone $attendanceQuery)
            ->with(['santri.tpqClass'])
            ->orderByDesc('attendance_date')
            ->orderBy('santri_id')
            ->paginate(15)
            ->withQueryString();

        return view('santri-attendances.report', compact(
            'classes',
            'startDate',
            'endDate',
            'classId',
            'totalRecords',
            'totalPresent',
            'totalSick',
            'totalPermission',
            'totalAbsent',
            'attendancePercentage',
            'classReports',
            'recentAttendances'
        ));
    }
    
    public function export(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date', 'before_or_equal:today'],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
                'before_or_equal:today',
            ],
            'class_id' => [
                'nullable',
                'integer',
                'exists:tpq_classes,id',
            ],
        ]);

        $classId = !empty($validated['class_id'])
            ? (int) $validated['class_id']
            : null;

        $filename = 'laporan-tpq-'
            . $validated['start_date']
            . '-sampai-'
            . $validated['end_date']
            . '.xlsx';

        return Excel::download(
            new SantriAttendanceExport(
                $validated['start_date'],
                $validated['end_date'],
                $classId
            ),
            $filename
        );
    }

}
