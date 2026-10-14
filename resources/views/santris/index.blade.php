@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Santri TPQ/TPA</h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola data santri, kelas, dan informasi wali.
            </p>
        </div>

        <a href="{{ route('santris.create') }}"
           class="rounded-lg bg-slate-900 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-slate-700">
            + Tambah Santri
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('santris.index') }}"
              class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <input type="text" name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama, nomor, atau wali..."
                   class="rounded-lg border border-slate-300 px-4 py-2 text-sm">

            <select name="class_id"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
                <option value="">Semua kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}"
                        @selected((string) request('class_id') === (string) $class->id)>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>

            <select name="status"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
                <option value="">Semua status</option>
                <option value="active" @selected(request('status') === 'active')>
                    Aktif
                </option>
                <option value="inactive" @selected(request('status') === 'inactive')>
                    Tidak aktif
                </option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">
                    Cari
                </button>
                <a href="{{ route('santris.index') }}"
                   class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Nomor Santri</th>
                        <th class="px-5 py-4">Nama Santri</th>
                        <th class="px-5 py-4">Kelas</th>
                        <th class="px-5 py-4">Nama Wali</th>
                        <th class="px-5 py-4">Telepon Wali</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($santris as $santri)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                {{ $santris->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-700">
                                {{ $santri->registration_number }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ $santri->name }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $santri->tpqClass?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $santri->parent_name ?: '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $santri->parent_phone ?: '-' }}
                            </td>

                            <td class="px-5 py-4">
                                @if($santri->status === 'active')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                        Tidak aktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('santris.show', $santri) }}"
                                       class="font-medium text-slate-700 hover:underline">
                                        Detail
                                    </a>

                                    <a href="{{ route('santris.edit', $santri) }}"
                                       class="font-medium text-blue-600 hover:underline">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('santris.destroy', $santri) }}"
                                          onsubmit="return confirm('Hapus data santri ini beserta riwayat kehadirannya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="font-medium text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-slate-500">
                                Belum ada data santri.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $santris->links() }}
        </div>
    </div>
</div>
@endsection
