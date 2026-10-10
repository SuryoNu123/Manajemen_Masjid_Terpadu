<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMustahikRequest;
use App\Http\Requests\UpdateMustahikRequest;
use App\Models\Mustahik;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MustahikController extends Controller
{
    public function index(): View
    {
        $mustahiks = Mustahik::latest()
            ->paginate(10);

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