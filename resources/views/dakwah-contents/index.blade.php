@extends('layouts.dashboard')

@section('content')
<div class="space-y-6 p-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Konten Dakwah
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola artikel dakwah dan berita kegiatan masjid.
            </p>
        </div>

        <a href="{{ route('dakwah-contents.create') }}"
           class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
            + Tambah Konten
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-4">
        <form method="GET"
              action="{{ route('dakwah-contents.index') }}"
              class="flex flex-wrap gap-3">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari judul konten..."
                   class="min-w-[200px] flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm">

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
                        <th class="px-5 py-3">Judul Konten</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Tanggal Dibuat</th>
                        <th class="px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($contents as $content)
                        <tr>
                            <td class="px-5 py-4">
                                {{ $contents->firstItem() + $loop->index }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-gray-800">
                                    {{ $content->title }}
                                </div>
                                <div class="mt-1 text-xs text-gray-500">
                                    {{ $content->slug }}
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if($content->status === 'published')
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
                                {{ $content->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('dakwah-contents.edit', $content) }}"
                                       class="font-medium text-blue-600 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('dakwah-contents.destroy', $content) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus konten ini?')">
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
                            <td colspan="5"
                                class="px-5 py-10 text-center text-gray-500">
                                Belum ada konten dakwah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 p-4">
            {{ $contents->links() }}
        </div>
    </div>
</div>
@endsection
