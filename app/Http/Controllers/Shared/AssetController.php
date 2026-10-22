<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'condition' => ['nullable', Rule::in(['baik', 'perlu_perbaikan', 'rusak'])],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        $assets = Asset::query()
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($filters['condition'] ?? null, fn ($query, string $condition) => $query->where('condition', $condition))
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('is_active', $status === 'active'))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('assets.index', [
            'assets' => $assets,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return view('assets.create', [
            'asset' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_code' => [
                'nullable',
                'string',
                'max:255',
                'unique:assets,asset_code',
            ],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'condition' => [
                'required',
                Rule::in(['baik', 'perlu_perbaikan', 'rusak']),
            ],
            'location' => ['nullable', 'string', 'max:255'],
            'acquisition_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'purchase_value' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Asset::create($validated);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'asset_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('assets', 'asset_code')->ignore($asset->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'condition' => [
                'required',
                Rule::in(['baik', 'perlu_perbaikan', 'rusak']),
            ],
            'location' => ['nullable', 'string', 'max:255'],
            'acquisition_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'purchase_value' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $asset->update($validated);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        if ($asset->maintenances()->exists()) {
            return redirect()
                ->route('assets.index')
                ->with(
                    'error',
                    'Aset tidak dapat dihapus karena memiliki riwayat perawatan. Nonaktifkan aset jika sudah tidak digunakan.'
                );
        }

        $asset->delete();

        return redirect()
            ->route('assets.index')
            ->with('success', 'Data aset berhasil dihapus.');
    }
}
