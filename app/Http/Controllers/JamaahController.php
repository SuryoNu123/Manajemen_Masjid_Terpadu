<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use Illuminate\Http\Request;

class JamaahController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $jamaahs = Jamaah::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('domicile', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('jamaahs.index', compact(
            'jamaahs',
            'search'
        ));
    }

    public function create()
    {
        return view('jamaahs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domicile' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'notes' => ['nullable', 'string'],
        ]);

        Jamaah::create($validated);

        return redirect()
            ->route('jamaahs.index')
            ->with('success', 'Data jamaah berhasil ditambahkan.');
    }

    public function edit(Jamaah $jamaah)
    {
        return view('jamaahs.edit', compact('jamaah'));
    }

    public function update(Request $request, Jamaah $jamaah)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domicile' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'notes' => ['nullable', 'string'],
        ]);

        $jamaah->update($validated);

        return redirect()
            ->route('jamaahs.index')
            ->with('success', 'Data jamaah berhasil diperbarui.');
    }

    public function destroy(Jamaah $jamaah)
    {
        $jamaah->delete();

        return redirect()
            ->route('jamaahs.index')
            ->with('success', 'Data jamaah berhasil dihapus.');
    }
}
