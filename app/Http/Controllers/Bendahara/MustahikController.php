<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMustahikRequest;
use App\Http\Requests\UpdateMustahikRequest;
use App\Models\Mustahik;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MustahikController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'asnaf' => ['nullable', 'in:fakir,miskin,amil,muallaf,riqab,gharim,fisabilillah,ibnu_sabil'],
            'status' => ['nullable', 'in:pending,distributed'],
        ]);

        $mustahiks = Mustahik::query()
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($filters['asnaf'] ?? null, fn (Builder $query, string $asnaf): Builder => $query->where('asnaf', $asnaf))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status): Builder => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalRecipients = Mustahik::count();

        $totalDistributed = Mustahik::where(
            'status',
            'distributed'
        )->sum('amount');

        $totalPending = Mustahik::where(
            'status',
            'pending'
        )->sum('amount');

        return view(
            'mustahiks.index',
            compact(
                'mustahiks',
                'totalRecipients',
                'totalDistributed',
                'totalPending'
            )
        );
    }

    public function create(): View
    {
        return view('mustahiks.create');
    }

    public function store(
        StoreMustahikRequest $request
    ): RedirectResponse {

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Jika status distributed tetapi tanggal kosong,
        | gunakan tanggal hari ini.
        |--------------------------------------------------------------------------
        */

        if (
            $data['status'] === 'distributed'
            && empty($data['distributed_at'])
        ) {
            $data['distributed_at'] = now()->toDateString();
        }

        /*
        |--------------------------------------------------------------------------
        | Jika status pending, tanggal penyaluran harus kosong.
        |--------------------------------------------------------------------------
        */

        if ($data['status'] === 'pending') {
            $data['distributed_at'] = null;
        }

        Mustahik::create($data);

        return redirect()
            ->route('mustahik.index')
            ->with(
                'success',
                'Data mustahik berhasil ditambahkan.'
            );
    }

    public function edit(
        Mustahik $mustahik
    ): View {
        return view(
            'mustahiks.edit',
            compact('mustahik')
        );
    }

    public function update(
        UpdateMustahikRequest $request,
        Mustahik $mustahik
    ): RedirectResponse {

        $data = $request->validated();

        if (
            $data['status'] === 'distributed'
            && empty($data['distributed_at'])
        ) {
            $data['distributed_at'] = now()->toDateString();
        }

        if ($data['status'] === 'pending') {
            $data['distributed_at'] = null;
        }

        $mustahik->update($data);

        return redirect()
            ->route('mustahik.index')
            ->with(
                'success',
                'Data mustahik berhasil diperbarui.'
            );
    }

    public function destroy(
        Mustahik $mustahik
    ): RedirectResponse {

        $mustahik->delete();

        return redirect()
            ->route('mustahik.index')
            ->with(
                'success',
                'Data mustahik berhasil dihapus.'
            );
    }
}
