@extends('layouts.dashboard')

@section('title', 'Permintaan Peminjaman')
@section('page-title', 'Permintaan Peminjaman')

@section('content')
    @php($hasFilters = collect($filters)->contains(fn ($filter) => $filter !== null && $filter !== ''))

    <div class="space-y-5">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Permintaan peminjaman dari Jamaah</h1>
            <p class="mt-1 text-sm text-slate-500">Cari permintaan berdasarkan nama jamaah atau judul buku.</p>
        </div>

        @if (session('success'))
            <p class="rounded-lg bg-green-50 p-3 text-sm text-green-800">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 p-3 text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="GET" action="{{ route('book-loan-requests.index') }}" class="grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-2 xl:grid-cols-6">
            <div class="sm:col-span-2">
                <label for="loan-request-search" class="mb-1 block text-sm font-medium text-slate-700">Cari permintaan</label>
                <input id="loan-request-search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" placeholder="Nama jamaah atau judul buku" class="min-h-11 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label for="loan-request-status" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                <select id="loan-request-status" name="status" class="min-h-11 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                    <option value="">Semua status</option>
                    @foreach (['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="loan-request-start-date" class="mb-1 block text-sm font-medium text-slate-700">Dari tanggal</label>
                <input id="loan-request-start-date" name="start_date" type="date" value="{{ $filters['start_date'] ?? '' }}" class="min-h-11 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label for="loan-request-end-date" class="mb-1 block text-sm font-medium text-slate-700">Sampai tanggal</label>
                <input id="loan-request-end-date" name="end_date" type="date" value="{{ $filters['end_date'] ?? '' }}" class="min-h-11 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div class="flex flex-wrap items-end gap-2 sm:col-span-2 xl:col-span-6">
                <button type="submit" class="min-h-11 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Terapkan filter</button>
                <a href="{{ route('book-loan-requests.index') }}" class="inline-flex min-h-11 items-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
            </div>
        </form>

        <div class="space-y-4">
            @forelse ($requests as $item)
                <article class="rounded-xl border border-slate-200 bg-white p-5">
                    <div class="flex flex-wrap justify-between gap-2">
                        <div class="min-w-0">
                            <h2 class="break-words font-bold text-slate-900">{{ $item->book->title }}</h2>
                            <p class="mt-1 text-sm text-slate-600">Pemohon: {{ $item->jamaah->name }} · {{ $item->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="text-sm font-semibold">{{ ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'][$item->status] ?? $item->status }}</span>
                    </div>

                    @if ($item->status === 'pending')
                        <div class="mt-4 flex flex-wrap items-end gap-3">
                            <form method="POST" action="{{ route('book-loan-requests.approve', $item) }}" class="flex flex-wrap items-end gap-3">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="mb-1 block text-xs font-medium" for="due-{{ $item->id }}">Tanggal jatuh tempo</label>
                                    <input id="due-{{ $item->id }}" type="date" name="due_date" min="{{ today()->toDateString() }}" required class="min-h-11 rounded-lg border-slate-300 text-sm">
                                </div>
                                <button class="min-h-11 rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('book-loan-requests.reject', $item) }}">
                                @csrf
                                @method('PATCH')
                                <button class="min-h-11 rounded-lg border border-red-300 px-4 py-2 text-sm font-semibold text-red-700">Tolak</button>
                            </form>
                        </div>
                    @elseif ($item->notes)
                        <p class="mt-3 break-words text-sm text-slate-600">Catatan: {{ $item->notes }}</p>
                    @endif
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600 sm:p-10">
                    <p class="font-semibold text-slate-800">{{ $hasFilters ? 'Tidak ada permintaan yang cocok dengan filter.' : 'Belum ada permintaan.' }}</p>
                    @if ($hasFilters)
                        <a href="{{ route('book-loan-requests.index') }}" class="mt-3 inline-flex min-h-11 items-center font-semibold text-blue-700 hover:text-blue-800">Hapus filter</a>
                    @endif
                </div>
            @endforelse
        </div>

        @if ($requests->hasPages())
            <div>{{ $requests->links() }}</div>
        @endif
    </div>
@endsection
