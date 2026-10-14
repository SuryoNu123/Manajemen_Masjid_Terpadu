<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\TpqClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $santris = Santri::with('tpqClass')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('registration_number', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('parent_name', 'like', "%{$search}%")
                        ->orWhere('parent_phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('class_id'), function ($query) use ($request) {
                $query->where('tpq_class_id', $request->class_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $classes = TpqClass::orderBy('name')->get();

        return view('santris.index', compact('santris', 'classes'));
    }

    public function create()
    {
        $classes = TpqClass::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('santris.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'parent_name' => ['nullable', 'string', 'max:100'],
            'parent_phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string'],
            'tpq_class_id' => [
                'nullable',
                'exists:tpq_classes,id',
            ],
            'enrollment_date' => ['nullable', 'date', 'before_or_equal:today'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $validated['registration_number'] =
            'SAN-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));

        Santri::create($validated);

        return redirect()
            ->route('santris.index')
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    public function show(Santri $santri)
    {
        $santri->load(['tpqClass', 'attendances' => function ($query) {
            $query->orderByDesc('attendance_date')->limit(10);
        }]);

        return view('santris.show', compact('santri'));
    }

    public function edit(Santri $santri)
    {
        $classes = TpqClass::orderBy('name')->get();

        return view('santris.edit', compact('santri', 'classes'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'parent_name' => ['nullable', 'string', 'max:100'],
            'parent_phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string'],
            'tpq_class_id' => [
                'nullable',
                'exists:tpq_classes,id',
            ],
            'enrollment_date' => ['nullable', 'date', 'before_or_equal:today'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $santri->update($validated);

        return redirect()
            ->route('santris.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        $santri->delete();

        return redirect()
            ->route('santris.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }
}
