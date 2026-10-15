<?php

namespace App\Http\Controllers;

use App\Models\TpqClass;
use Illuminate\Http\Request;

class TpqClassController extends Controller
{
    public function index(Request $request)
    {
        $classes = TpqClass::withCount('santris')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('level', 'like', "%{$search}%")
                        ->orWhere('teacher_name', 'like', "%{$search}%");
                });
            })
            ->when($request->status !== null && $request->status !== '', function ($query) use ($request) {
                $query->where('is_active', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('tpq-classes.index', compact('classes'));
    }

    public function create()
    {
        return view('tpq-classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'level' => ['nullable', 'string', 'max:50'],
            'schedule' => ['nullable', 'string', 'max:150'],
            'teacher_name' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        TpqClass::create($validated);

        return redirect()
            ->route('tpq-classes.index')
            ->with('success', 'Kelas TPQ berhasil ditambahkan.');
    }

    public function edit(TpqClass $tpqClass)
    {
        return view('tpq-classes.edit', compact('tpqClass'));
    }

    public function update(Request $request, TpqClass $tpqClass)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'level' => ['nullable', 'string', 'max:50'],
            'schedule' => ['nullable', 'string', 'max:150'],
            'teacher_name' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $tpqClass->update($validated);

        return redirect()
            ->route('tpq-classes.index')
            ->with('success', 'Kelas TPQ berhasil diperbarui.');
    }

    public function destroy(TpqClass $tpqClass)
    {
        if ($tpqClass->santris()->exists()) {
            return back()->with(
                'error',
                'Kelas tidak dapat dihapus karena masih memiliki santri. Pindahkan santri terlebih dahulu.'
            );
        }

        $tpqClass->delete();

        return redirect()
            ->route('tpq-classes.index')
            ->with('success', 'Kelas TPQ berhasil dihapus.');
    }
}
