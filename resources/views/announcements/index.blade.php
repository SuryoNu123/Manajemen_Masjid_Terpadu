@extends('layouts.dashboard')

@section('content')
<div class="space-y-6 p-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Pengumuman Masjid
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola informasi kegiatan dan pengumuman darurat untuk jamaah.
            </p>
        </div>

        <a href="{{ route('announcements.create') }}"
           class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
            + Tambah Pengumuman
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-4">
        <form method="GET"
              action="{{ route('announcements.index') }}"
              class="flex flex-wrap gap-3">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari pengumuman..."
                   class="min-w-[200px] flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm">

            <select name="type"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua Jenis</option>
                <option value="normal" @selected(request('type') === 'normal')>
                    Pengumuman Biasa
                </option>
                <option value="emergency" @selected(request('type') === 'emergency')>
                    Informasi Darurat
                </option>
            </select>

            <select name="status"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="draft" @selected(request('status') === 'draft')>
                    Draf
                </option>
                <option value="published" @selected(request('status') === 'published')>
                    Dipublikasikan
                </option>
            </select>

            <button type="submit"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm hover:bg-gray-50">
                Cari
            </button>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-5 py-3">No.</th>
                        <th class="px-5 py-3">Judul</th>
                        <th class="px-5 py-3">Jenis</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Kedaluwarsa</th>
                        <th class="px-5 py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($announcements as $announcement)
                        <tr>
                            <td class="px-5 py-4">
                                {{ $announcements->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-semibold text-gray-800">
                                    {{ $announcement->title }}
                                </div>
                                <div class="mt-1 max-w-xs truncate text-xs text-gray-500">
                                    {{ $announcement->content }}
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                @if($announcement->type === 'emergency')
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        Darurat
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                        Biasa
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                @if($announcement->status === 'published')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Dipublikasikan
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                        Draf
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $announcement->expires_at?->format('d/m/Y H:i') ?? 'Tidak ada' }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('announcements.edit', $announcement) }}"
                                       class="font-medium text-blue-600 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('announcements.destroy', $announcement) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus pengumuman ini?')">
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
                            <td colspan="6"
                                class="px-5 py-10 text-center text-gray-500">
                                Belum ada pengumuman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 p-4">
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection
