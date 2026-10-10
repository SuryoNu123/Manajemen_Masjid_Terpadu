@extends('layouts.public')

@section('title', 'Kegiatan Ibadah - Masjid Terpadu')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-12">

    <div class="mb-10">

        <p class="text-sm font-medium text-slate-500">
            Informasi Masjid
        </p>

        <h1 class="text-3xl font-bold text-slate-900 mt-2">
            Kegiatan Ibadah
        </h1>

        <p class="text-slate-500 mt-2">
            Jadwal kajian dan kegiatan ibadah Masjid Terpadu.
        </p>

    </div>

    <div class="grid md:grid-cols-2 gap-6">

        @forelse($activities as $activity)

            <div class="bg-white border border-slate-200 rounded-2xl p-6">

                <div class="flex items-start justify-between gap-4">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            {{ ucfirst(str_replace('_', ' ', $activity->type)) }}
                        </p>

                        <h2 class="text-xl font-bold text-slate-900 mt-2">
                            {{ $activity->title }}
                        </h2>

                    </div>

                </div>

                <div class="space-y-2 mt-5 text-sm text-slate-600">

                    <div>
                        📅 {{ $activity->activity_date?->format('d M Y') }}
                    </div>

                    <div>
                        🕐 {{ $activity->start_time }}

                        @if($activity->end_time)
                            - {{ $activity->end_time }}
                        @endif
                    </div>

                    <div>
                        📍 {{ $activity->location ?? 'Masjid' }}
                    </div>

                    @if($activity->speaker)

                        <div>
                            🎤 {{ $activity->speaker }}
                        </div>

                    @endif

                </div>

                @if($activity->description)

                    <p class="text-sm text-slate-500 mt-5 leading-6">
                        {{ $activity->description }}
                    </p>

                @endif

            </div>

        @empty

            <div class="md:col-span-2 text-center py-16 text-slate-500">
                Belum ada kegiatan yang tersedia.
            </div>

        @endforelse

    </div>

</div>

@endsection