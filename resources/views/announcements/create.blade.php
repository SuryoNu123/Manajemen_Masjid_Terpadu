@extends('layouts.dashboard')

@section('content')
<div class="space-y-6 p-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Pengumuman
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Buat informasi untuk jamaah masjid.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6">
        @include('announcements._form', [
            'announcement' => null,
            'action' => route('announcements.store'),
            'method' => 'POST',
        ])
    </div>
</div>
@endsection
