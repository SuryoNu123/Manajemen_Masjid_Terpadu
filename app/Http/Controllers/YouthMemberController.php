<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\YouthMember;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\View\View;

class YouthMemberController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $division = $request->query('division');
        $status = $request->query('status');

        $members = YouthMember::with('jamaah')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->when($division, fn ($q) => $q->where('division', $division))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('division')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $divisions = YouthMember::select('division')->distinct()->pluck('division');

        return view('youth.members.index', compact('members', 'search', 'division', 'status', 'divisions'));
    }

    public function create(): View
    {
        $jamaahs = Jamaah::where('status', 'aktif')->orderBy('name')->get();

        return view('youth.members.create', compact('jamaahs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jamaah_id' => ['nullable', 'exists:jamaahs,id'],
            'name' => ['required', 'string', 'max:255'],
            'division' => ['required', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'status' => ['required', 'in:aktif,alumni,nonaktif'],
            'joined_year' => ['nullable', 'integer', 'min:2000', 'max:'.(date('Y') + 1)],
            'notes' => ['nullable', 'string'],
        ]);

        $member = YouthMember::create($data);

        ActivityLogger::log('create_youth_member', "Menambahkan anggota remaja masjid: {$member->name} ({$member->division} - {$member->position}).", $member);

        return redirect()->route('youth-members.index')
            ->with('success', 'Data anggota remaja masjid berhasil ditambahkan.');
    }

    public function edit(YouthMember $youthMember): View
    {
        $jamaahs = Jamaah::where('status', 'aktif')->orderBy('name')->get();

        return view('youth.members.edit', compact('youthMember', 'jamaahs'));
    }

    public function update(Request $request, YouthMember $youthMember)
    {
        $data = $request->validate([
            'jamaah_id' => ['nullable', 'exists:jamaahs,id'],
            'name' => ['required', 'string', 'max:255'],
            'division' => ['required', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'status' => ['required', 'in:aktif,alumni,nonaktif'],
            'joined_year' => ['nullable', 'integer', 'min:2000', 'max:'.(date('Y') + 1)],
            'notes' => ['nullable', 'string'],
        ]);

        $youthMember->update($data);

        ActivityLogger::log('update_youth_member', "Memperbarui data anggota remaja masjid: {$youthMember->name}.", $youthMember);

        return redirect()->route('youth-members.index')
            ->with('success', 'Data anggota remaja masjid berhasil diperbarui.');
    }

    public function destroy(YouthMember $youthMember)
    {
        ActivityLogger::log('delete_youth_member', "Menghapus anggota remaja masjid: {$youthMember->name}.", $youthMember);

        $youthMember->delete();

        return redirect()->route('youth-members.index')
            ->with('success', 'Data anggota remaja masjid berhasil dihapus.');
    }
}
