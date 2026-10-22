<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfficerController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'in:imam,khatib,muadzin,petugas,lainnya'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $officers = Officer::query()
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($filters['position'] ?? null, fn (Builder $query, string $position): Builder => $query->where('position', $position))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status): Builder => $query->where('is_active', $status === 'active'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('officers.index', compact('officers'));
    }

    public function create()
    {
        return view('officers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:imam,khatib,muadzin,petugas,lainnya',
            'phone' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Officer::create($validated);

        return redirect()
            ->route('officers.index')
            ->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit(Officer $officer)
    {
        return view('officers.edit', compact('officer'));
    }

    public function update(Request $request, Officer $officer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:imam,khatib,muadzin,petugas,lainnya',
            'phone' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $officer->update($validated);

        return redirect()
            ->route('officers.index')
            ->with('success', 'Petugas berhasil diperbarui.');
    }

    public function destroy(Officer $officer)
    {
        $officer->delete();

        return redirect()
            ->route('officers.index')
            ->with('success', 'Petugas berhasil dihapus.');
    }
}
