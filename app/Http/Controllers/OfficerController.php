<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function index()
    {
        $officers = Officer::latest()->paginate(10);

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