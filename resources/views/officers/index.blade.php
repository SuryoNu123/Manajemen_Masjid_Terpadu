@extends('layouts.dashboard')

@section('title', 'Data Petugas')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Data Petugas
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Kelola imam, khatib, muadzin, dan petugas kegiatan masjid.
            </p>
        </div>

        <a href="{{ route('officers.create') }}"
           class="px-5 py-3 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800">
            + Tambah Petugas
        </a>

    </div>

    @if(session('success'))

        <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>

    @endif

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr>
                        <th class="px-6 py-4 text-left">Nama</th>
                        <th class="px-6 py-4 text-left">Posisi</th>
                        <th class="px-6 py-4 text-left">Telepon</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($officers as $officer)

                        <tr>

                            <td class="px-6 py-4 font-semibold">
                                {{ $officer->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ ucfirst($officer->position) }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $officer->phone ?? '-' }}
                            </td>

                            <td class="px-6 py-4">

                                @if($officer->is_active)

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        Aktif
                                    </span>

                                @else

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                        Tidak Aktif
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('officers.edit', $officer) }}"
                                       class="px-3 py-2 rounded-lg bg-slate-100">
                                        Edit
                                    </a>

                                    <form action="{{ route('officers.destroy', $officer) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus petugas ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="px-3 py-2 rounded-lg bg-red-50 text-red-600">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                Belum ada data petugas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($officers->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $officers->links() }}
            </div>
        @endif

    </div>

</div>

@endsection