@extends('layouts.dashboard')

@section('title', 'Detail Kegiatan')

@section('content')

<div class="max-w-5xl space-y-6">

    {{-- Header --}}

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <p class="text-sm text-slate-500">
                Detail Kegiatan
            </p>

            <h1 class="text-2xl font-bold text-slate-900 mt-1">
                {{ $activity->title }}
            </h1>

        </div>

        <div class="flex gap-3">

            <a href="{{ route('activities.edit', $activity) }}"
               class="px-4 py-2.5 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800">
                Edit
            </a>

            <a href="{{ route('activities.index') }}"
               class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50">
                Kembali
            </a>

        </div>

    </div>


    {{-- Informasi Kegiatan --}}

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Informasi utama --}}

        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6">

            <h2 class="text-lg font-bold text-slate-900">
                Informasi Kegiatan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                <div>

                    <p class="text-xs text-slate-500 uppercase font-semibold">
                        Jenis Kegiatan
                    </p>

                    <p class="mt-2 font-medium text-slate-800">
                        {{ ucfirst(str_replace('_', ' ', $activity->type)) }}
                    </p>

                </div>


                <div>

                    <p class="text-xs text-slate-500 uppercase font-semibold">
                        Status
                    </p>

                    @php

                        $statusClasses = [
                            'scheduled' => 'bg-blue-50 text-blue-700',
                            'ongoing' => 'bg-amber-50 text-amber-700',
                            'completed' => 'bg-emerald-50 text-emerald-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                        ];

                        $statusLabels = [
                            'scheduled' => 'Terjadwal',
                            'ongoing' => 'Berlangsung',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];

                    @endphp

                    <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$activity->status] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ $statusLabels[$activity->status] ?? ucfirst($activity->status) }}
                    </span>

                </div>


                <div>

                    <p class="text-xs text-slate-500 uppercase font-semibold">
                        Tanggal
                    </p>

                    <p class="mt-2 font-medium text-slate-800">
                        {{ $activity->activity_date?->format('d F Y') }}
                    </p>

                </div>


                <div>

                    <p class="text-xs text-slate-500 uppercase font-semibold">
                        Waktu
                    </p>

                    <p class="mt-2 font-medium text-slate-800">

                        {{ $activity->start_time }}

                        @if($activity->end_time)
                            - {{ $activity->end_time }}
                        @endif

                    </p>

                </div>


                <div>

                    <p class="text-xs text-slate-500 uppercase font-semibold">
                        Lokasi
                    </p>

                    <p class="mt-2 font-medium text-slate-800">
                        {{ $activity->location ?? '-' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs text-slate-500 uppercase font-semibold">
                        Pembicara / Khatib
                    </p>

                    <p class="mt-2 font-medium text-slate-800">
                        {{ $activity->speaker ?? '-' }}
                    </p>

                </div>

            </div>


            @if($activity->description)

                <div class="mt-8 pt-6 border-t border-slate-100">

                    <p class="text-xs text-slate-500 uppercase font-semibold">
                        Deskripsi
                    </p>

                    <p class="mt-2 text-sm text-slate-600 leading-7">
                        {{ $activity->description }}
                    </p>

                </div>

            @endif

        </div>


        {{-- Publikasi --}}

        <div class="bg-white border border-slate-200 rounded-2xl p-6">

            <h2 class="text-lg font-bold text-slate-900">
                Publikasi
            </h2>

            <div class="mt-6">

                @if($activity->is_public)

                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4">

                        <p class="font-semibold text-emerald-800">
                            Tampil di Publik
                        </p>

                        <p class="text-xs text-emerald-700 mt-1">
                            Kegiatan dapat dilihat oleh jamaah.
                        </p>

                    </div>

                @else

                    <div class="rounded-xl bg-slate-100 border border-slate-200 p-4">

                        <p class="font-semibold text-slate-700">
                            Tidak Dipublikasikan
                        </p>

                        <p class="text-xs text-slate-500 mt-1">
                            Kegiatan hanya dapat dilihat oleh pengelola.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- Petugas --}}

    <div class="bg-white border border-slate-200 rounded-2xl p-6">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-lg font-bold text-slate-900">
                    Petugas Kegiatan
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Petugas yang ditugaskan pada kegiatan ini.
                </p>

            </div>

            <span class="text-sm text-slate-500">
                {{ $activity->officers->count() }} petugas
            </span>

        </div>


        @if($activity->officers->count() > 0)

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">

                @foreach($activity->officers as $officer)

                    <div class="border border-slate-200 rounded-xl p-4">

                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-4">

                            <span class="font-bold text-slate-700">
                                {{ strtoupper(substr($officer->name, 0, 1)) }}
                            </span>

                        </div>

                        <p class="font-semibold text-slate-800">
                            {{ $officer->name }}
                        </p>

                        <p class="text-xs text-slate-500 mt-1">
                            {{ ucfirst($officer->position) }}
                        </p>

                        <div class="mt-4">

                            <span class="inline-flex px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">

                                @php

                                    $assignmentLabels = [
                                        'imam' => 'Imam',
                                        'khatib' => 'Khatib',
                                        'muadzin' => 'Muadzin',
                                        'petugas' => 'Petugas',
                                        'lainnya' => 'Lainnya',
                                    ];

                                @endphp

                                {{ $assignmentLabels[$officer->pivot->assignment] ?? ucfirst($officer->pivot->assignment) }}

                            </span>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="mt-6 rounded-xl bg-slate-50 border border-slate-200 p-6 text-center">

                <p class="text-sm text-slate-500">
                    Belum ada petugas yang ditugaskan.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection