<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Officer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:scheduled,ongoing,completed,cancelled'],
        ]);

        $query = Activity::query()
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('speaker', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($filters['type'] ?? null, fn (Builder $query, string $type): Builder => $query->where('type', $type))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status): Builder => $query->where('status', $status));

        $activities = $query->latest('activity_date')
            ->latest('start_time')
            ->paginate(10)
            ->withQueryString();

        $activityTypes = Activity::query()
            ->select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        return view('activities.index', compact('activities', 'activityTypes'));
    }

    public function create()
    {
        $officers = Officer::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('activities.create', compact('officers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'activity_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable|after:start_time',
            'location' => 'nullable|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'is_public' => 'nullable|boolean',

            'officers' => 'nullable|array',
            'officers.*' => 'exists:officers,id',

            'assignments' => 'nullable|array',
        ]);

        $selectedOfficerIds = array_unique(
            $request->input('officers', [])
        );

        /*
        |--------------------------------------------------------------------------
        | Jika memilih petugas, waktu selesai wajib diisi
        |--------------------------------------------------------------------------
        */

        if (! empty($selectedOfficerIds) && empty($validated['end_time'])) {

            return back()
                ->withErrors([
                    'end_time' => 'Waktu selesai wajib diisi jika kegiatan memiliki petugas.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan semua petugas yang dipilih masih aktif
        |--------------------------------------------------------------------------
        */

        if (! empty($selectedOfficerIds)) {

            $activeOfficerCount = Officer::where('is_active', true)
                ->whereIn('id', $selectedOfficerIds)
                ->count();

            if ($activeOfficerCount !== count($selectedOfficerIds)) {

                return back()
                    ->withErrors([
                        'officers' => 'Petugas yang dipilih harus dalam status aktif.',
                    ])
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Cek bentrok jadwal petugas
        |--------------------------------------------------------------------------
        */

        if (! empty($selectedOfficerIds)) {

            $conflicts = Activity::whereDate(
                'activity_date',
                $validated['activity_date']
            )
                ->where(function ($query) use ($validated) {

                    $query->whereTime(
                        'start_time',
                        '<',
                        $validated['end_time']
                    )
                        ->where(function ($query) use ($validated) {

                            $query->whereNull('end_time')
                                ->orWhere(
                                    'end_time',
                                    '>',
                                    $validated['start_time']
                                );

                        });

                })
                ->whereHas('officers', function ($query) use ($selectedOfficerIds) {

                    $query->whereIn(
                        'officers.id',
                        $selectedOfficerIds
                    );

                })
                ->with('officers')
                ->get();

            if ($conflicts->isNotEmpty()) {

                $names = $conflicts
                    ->flatMap(function ($activity) {
                        return $activity->officers->pluck('name');
                    })
                    ->unique()
                    ->implode(', ');

                return back()
                    ->withErrors([
                        'officers' => "Jadwal petugas bentrok dengan kegiatan lain: {$names}",
                    ])
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan kegiatan
        |--------------------------------------------------------------------------
        */

        $validated['is_public'] = $request->boolean('is_public');

        unset(
            $validated['officers'],
            $validated['assignments']
        );

        DB::transaction(function () use (
            $validated,
            $selectedOfficerIds,
            $request
        ) {

            $activity = Activity::create($validated);

            $pivotData = [];

            foreach ($selectedOfficerIds as $officerId) {

                $pivotData[$officerId] = [
                    'assignment' => $request->input(
                        "assignments.$officerId",
                        'lainnya'
                    ),
                ];

            }

            $activity->officers()->sync($pivotData);
        });

        return redirect()
            ->route('activities.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Activity $activity)
    {
        $activity->load('officers');

        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $officers = Officer::where('is_active', true)
            ->orderBy('name')
            ->get();

        $assignedOfficers = $activity->officers()
            ->get()
            ->keyBy('id');

        return view(
            'activities.edit',
            compact(
                'activity',
                'officers',
                'assignedOfficers'
            )
        );
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'activity_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable|after:start_time',
            'location' => 'nullable|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'is_public' => 'nullable|boolean',

            'officers' => 'nullable|array',
            'officers.*' => 'exists:officers,id',

            'assignments' => 'nullable|array',
        ]);

        $selectedOfficerIds = array_unique(
            $request->input('officers', [])
        );

        /*
        |--------------------------------------------------------------------------
        | Jika memilih petugas, waktu selesai wajib diisi
        |--------------------------------------------------------------------------
        */

        if (! empty($selectedOfficerIds) && empty($validated['end_time'])) {

            return back()
                ->withErrors([
                    'end_time' => 'Waktu selesai wajib diisi jika kegiatan memiliki petugas.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan semua petugas aktif
        |--------------------------------------------------------------------------
        */

        if (! empty($selectedOfficerIds)) {

            $activeOfficerCount = Officer::where('is_active', true)
                ->whereIn('id', $selectedOfficerIds)
                ->count();

            if ($activeOfficerCount !== count($selectedOfficerIds)) {

                return back()
                    ->withErrors([
                        'officers' => 'Petugas yang dipilih harus dalam status aktif.',
                    ])
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Cek bentrok dengan kegiatan lain
        |--------------------------------------------------------------------------
        */

        if (! empty($selectedOfficerIds)) {

            $conflicts = Activity::where('id', '!=', $activity->id)
                ->whereDate(
                    'activity_date',
                    $validated['activity_date']
                )
                ->where(function ($query) use ($validated) {

                    $query->whereTime(
                        'start_time',
                        '<',
                        $validated['end_time']
                    )
                        ->where(function ($query) use ($validated) {

                            $query->whereNull('end_time')
                                ->orWhere(
                                    'end_time',
                                    '>',
                                    $validated['start_time']
                                );

                        });

                })
                ->whereHas('officers', function ($query) use ($selectedOfficerIds) {

                    $query->whereIn(
                        'officers.id',
                        $selectedOfficerIds
                    );

                })
                ->with('officers')
                ->get();

            if ($conflicts->isNotEmpty()) {

                $names = $conflicts
                    ->flatMap(function ($item) {
                        return $item->officers->pluck('name');
                    })
                    ->unique()
                    ->implode(', ');

                return back()
                    ->withErrors([
                        'officers' => "Jadwal petugas bentrok dengan kegiatan lain: {$names}",
                    ])
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update kegiatan dan petugas
        |--------------------------------------------------------------------------
        */

        $validated['is_public'] = $request->boolean('is_public');

        unset(
            $validated['officers'],
            $validated['assignments']
        );

        DB::transaction(function () use (
            $activity,
            $validated,
            $selectedOfficerIds,
            $request
        ) {

            $activity->update($validated);

            $pivotData = [];

            foreach ($selectedOfficerIds as $officerId) {

                $pivotData[$officerId] = [
                    'assignment' => $request->input(
                        "assignments.$officerId",
                        'lainnya'
                    ),
                ];

            }

            $activity->officers()->sync($pivotData);
        });

        return redirect()
            ->route('activities.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()
            ->route('activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function publicIndex()
    {
        $activities = Activity::where('is_public', true)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->whereDate(
                'activity_date',
                '>=',
                now()->toDateString()
            )
            ->orderBy('activity_date')
            ->orderBy('start_time')
            ->get();

        return view(
            'activities.public',
            compact('activities')
        );
    }
}
