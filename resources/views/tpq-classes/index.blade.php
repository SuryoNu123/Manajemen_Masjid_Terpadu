@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Data Kelas TPQ/TPA
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola kelas, jadwal belajar, dan pengajar TPQ/TPA.
            </p>
        </div>

        <a href="{{ route('tpq-classes.create') }}"
           class="rounded-lg bg-slate-900 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-slate-700">
            + Tambah Kelas
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
        <form method="GET" action="{{ route('tpq-classes.index') }}"
              class="flex flex-col gap-3 sm:flex-row">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama kelas, tingkat, atau pengajar..."
                   class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-slate-500 focus:outline-none">

            <select name="status"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
                <option value="">Semua status</option>
                <option value="1" @selected(request('status') === '1')>
                    Aktif
                </option>
                <option value="0" @selected(request('status') === '0')>
                    Tidak aktif
                </option>
            </select>

            <button type="submit"
                    class="rounded-lg bg-slate-800 px-5 py-2 text-sm font-semibold text-white">
                Cari
            </button>

            <a href="{{ route('tpq-classes.index') }}"
               class="rounded-lg border border-slate-300 px-5 py-2 text-center text-sm">
                Reset
            </a>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Nama Kelas</th>
                        <th class="px-5 py-4">Tingkat</th>
                        <th class="px-5 py-4">Jadwal</th>
                        <th class="px-5 py-4">Pengajar</th>
                        <th class="px-5 py-4">Jumlah Santri</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($classes as $class)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                {{ $classes->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ $class->name }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $class->level ?: '-' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $class->schedule ?: '-' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $class->teacher_name ?: '-' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $class->santris_count }} santri
                            </td>

                            <td class="px-5 py-4">
                                @if($class->is_active)
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
                                    <a href="{{ route('tpq-classes.edit', $class) }}"
                                       class="font-medium text-blue-600 hover:underline">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('tpq-classes.destroy', $class) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
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
                            <td colspan="8"
                                class="px-5 py-10 text-center text-slate-500">
                                Belum ada data kelas TPQ/TPA.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $classes->links() }}
        </div>
    </div>

</div>
@endsection
