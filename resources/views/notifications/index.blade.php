@extends('layouts.dashboard')

@section('title', 'Notifikasi')

@section('content')

<div class="max-w-4xl space-y-6">

    {{-- Header --}}

    <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-900">
                Notifikasi
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Informasi dan pengingat kegiatan masjid.
            </p>

        </div>

        @if(auth()->user()->unreadNotifications->count() > 0)

            <form action="{{ route('notifications.readAll') }}"
                  method="POST">

                @csrf

                <button
                    type="submit"
                    class="min-h-11 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 text-sm font-medium"
                >
                    Tandai Semua Dibaca
                </button>

            </form>

        @endif

    </div>


    <form method="GET" action="{{ route('notifications.index') }}" class="grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-[minmax(0,1fr)_200px_auto]">
        <div>
            <label for="notification-search" class="mb-1 block text-sm font-medium text-slate-700">Cari notifikasi</label>
            <input id="notification-search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" placeholder="Judul atau isi notifikasi" class="min-h-11 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
        </div>
        <div>
            <label for="notification-read" class="mb-1 block text-sm font-medium text-slate-700">Status baca</label>
            <select id="notification-read" name="read" class="min-h-11 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                <option value="">Semua</option>
                <option value="unread" @selected(($filters['read'] ?? '') === 'unread')>Belum dibaca</option>
                <option value="read" @selected(($filters['read'] ?? '') === 'read')>Sudah dibaca</option>
            </select>
        </div>
        <div class="flex flex-wrap items-end gap-2">
            <button type="submit" class="min-h-11 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Terapkan</button>
            <a href="{{ route('notifications.index') }}" class="inline-flex min-h-11 items-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
        </div>
    </form>

    {{-- Notifications --}}

    <div class="space-y-3">

        @forelse($notifications as $notification)

            <div
                class="bg-white border rounded-2xl p-5
                {{ $notification->read_at ? 'border-slate-200' : 'border-blue-300 bg-blue-50/30' }}"
            >

                <div class="flex items-start gap-4">

                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">

                        <span class="text-blue-600">
                            🔔
                        </span>

                    </div>


                    <div class="flex-1">

                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0">

                                <p class="font-semibold text-slate-900">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>

                            </div>

                            @if(!$notification->read_at)

                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600 flex-shrink-0 mt-2">
                                </span>

                            @endif

                        </div>


                        <div class="flex flex-wrap gap-4 mt-3 text-xs text-slate-500">

                            @if(isset($notification->data['activity_date']))

                                <span>
                                    📅
                                    {{ \Carbon\Carbon::parse($notification->data['activity_date'])->format('d M Y') }}
                                </span>

                            @endif

                            @if(isset($notification->data['start_time']))

                                <span>
                                    🕐
                                    {{ $notification->data['start_time'] }}
                                </span>

                            @endif

                            @if(isset($notification->data['location']))

                                <span>
                                    📍
                                    {{ $notification->data['location'] }}
                                </span>

                            @endif

                        </div>


                        @if(!$notification->read_at)

                            <form
                                action="{{ route('notifications.read', $notification->id) }}"
                                method="POST"
                                class="mt-4"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-800"
                                >
                                    Tandai sudah dibaca
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center sm:p-12">

                <div class="text-4xl mb-4">
                    🔔
                </div>

                <p class="font-semibold text-slate-800">
                    {{ ($filters['search'] ?? '') || ($filters['read'] ?? '') ? 'Tidak ada notifikasi yang cocok.' : 'Belum ada notifikasi' }}
                </p>

                <p class="text-sm text-slate-500 mt-1">
                    {{ ($filters['search'] ?? '') || ($filters['read'] ?? '') ? 'Coba ubah kata pencarian atau status baca.' : 'Notifikasi kegiatan akan muncul di sini.' }}
                </p>

            </div>

        @endforelse

    </div>


    {{-- Pagination --}}

    @if($notifications->hasPages())

        <div>
            {{ $notifications->links() }}
        </div>

    @endif

</div>

@endsection
