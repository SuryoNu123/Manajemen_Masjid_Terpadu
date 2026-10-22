<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusinessUnitController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:aktif,nonaktif'],
        ]);

        $search = $filters['search'] ?? '';
        $status = $filters['status'] ?? '';

        $businessUnits = BusinessUnit::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('manager_name', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where(
                    'is_active',
                    $status === 'aktif'
                );
            })
            ->withSum('sales', 'amount')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('business-units.index', compact(
            'businessUnits',
            'search',
            'status'
        ));
    }

    public function create()
    {
        return view('business-units.create', [
            'businessUnit' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'nullable',
                'string',
                'max:255',
                'unique:business_units,code',
            ],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'initial_capital' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        BusinessUnit::create($validated);

        return redirect()
            ->route('business-units.index')
            ->with('success', 'Unit usaha berhasil ditambahkan.');
    }

    public function edit(BusinessUnit $businessUnit)
    {
        return view('business-units.edit', compact('businessUnit'));
    }

    public function update(
        Request $request,
        BusinessUnit $businessUnit
    ) {
        $validated = $request->validate([
            'code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('business_units', 'code')
                    ->ignore($businessUnit->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'initial_capital' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $businessUnit->update($validated);

        return redirect()
            ->route('business-units.index')
            ->with('success', 'Data unit usaha berhasil diperbarui.');
    }
}
