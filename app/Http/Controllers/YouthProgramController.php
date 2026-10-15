<?php

namespace App\Http\Controllers;

use App\Models\YouthMember;
use App\Models\YouthProgram;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\View\View;

class YouthProgramController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $division = $request->query('division');

        $programs = YouthProgram::with('leader')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($division, fn ($q) => $q->where('division', $division))
            ->latest('start_date')
            ->paginate(15)
            ->withQueryString();

        $divisions = YouthMember::select('division')->distinct()->pluck('division');

        return view('youth.programs.index', compact('programs', 'status', 'division', 'divisions'));
    }

    public function create(): View
    {
        $members = YouthMember::where('status', 'aktif')->orderBy('name')->get();

        return view('youth.programs.create', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'division' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'youth_member_id' => ['nullable', 'exists:youth_members,id'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:rencana,berjalan,selesai,dibatalkan'],
        ]);

        $program = YouthProgram::create($data);

        ActivityLogger::log('create_youth_program', "Membuat program kerja remaja masjid: {$program->name}.", $program);

        return redirect()->route('youth-programs.index')
            ->with('success', 'Program kerja remaja masjid berhasil ditambahkan.');
    }

    public function edit(YouthProgram $youthProgram): View
    {
        $members = YouthMember::where('status', 'aktif')->orderBy('name')->get();

        return view('youth.programs.edit', compact('youthProgram', 'members'));
    }

    public function update(Request $request, YouthProgram $youthProgram)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'division' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'youth_member_id' => ['nullable', 'exists:youth_members,id'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:rencana,berjalan,selesai,dibatalkan'],
        ]);

        $youthProgram->update($data);

        ActivityLogger::log('update_youth_program', "Memperbarui program kerja remaja masjid: {$youthProgram->name}.", $youthProgram);

        return redirect()->route('youth-programs.index')
            ->with('success', 'Program kerja remaja masjid berhasil diperbarui.');
    }

    public function destroy(YouthProgram $youthProgram)
    {
        ActivityLogger::log('delete_youth_program', "Menghapus program kerja remaja masjid: {$youthProgram->name}.", $youthProgram);

        $youthProgram->delete();

        return redirect()->route('youth-programs.index')
            ->with('success', 'Program kerja remaja masjid berhasil dihapus.');
    }
}
