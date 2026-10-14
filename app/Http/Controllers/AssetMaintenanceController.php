<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;

class AssetMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => [
                'nullable',
                'in:terjadwal,dalam_proses,selesai,dibatalkan',
            ],
        ]);

        $search = $filters['search'] ?? '';
        $status = $filters['status'] ?? '';

        $maintenances = AssetMaintenance::with('asset')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhereHas('asset', function ($assetQuery) use ($search) {
                            $assetQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('asset_code', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderByDesc('scheduled_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('asset-maintenances.index', compact(
            'maintenances',
            'search',
            'status'
        ));
    }

    public function create()
    {
        $assets = Asset::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('asset-maintenances.create', [
            'maintenance' => null,
            'assets' => $assets,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'scheduled_date' => ['required', 'date'],
            'completed_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'status' => [
                'required',
                'in:terjadwal,dalam_proses,selesai,dibatalkan',
            ],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'performed_by' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['cost'] = $validated['cost'] ?? 0;

        if (
            $validated['status'] === 'selesai'
            && empty($validated['completed_date'])
        ) {
            $validated['completed_date'] = today()->toDateString();
        }

        AssetMaintenance::create($validated);

        return redirect()
            ->route('asset-maintenances.index')
            ->with('success', 'Jadwal perawatan berhasil ditambahkan.');
    }

    public function edit(AssetMaintenance $assetMaintenance)
    {
        $assets = Asset::where('is_active', true)
            ->orWhere('id', $assetMaintenance->asset_id)
            ->orderBy('name')
            ->get();

        return view('asset-maintenances.edit', [
            'maintenance' => $assetMaintenance,
            'assets' => $assets,
        ]);
    }

    public function update(
        Request $request,
        AssetMaintenance $assetMaintenance
    ) {
        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'scheduled_date' => ['required', 'date'],
            'completed_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'status' => [
                'required',
                'in:terjadwal,dalam_proses,selesai,dibatalkan',
            ],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'performed_by' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['cost'] = $validated['cost'] ?? 0;

        if (
            $validated['status'] === 'selesai'
            && empty($validated['completed_date'])
        ) {
            $validated['completed_date'] = today()->toDateString();
        }

        $assetMaintenance->update($validated);

        return redirect()
            ->route('asset-maintenances.index')
            ->with('success', 'Data perawatan berhasil diperbarui.');
    }
}
