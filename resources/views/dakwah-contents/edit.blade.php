@extends('layouts.dashboard')

@section('content')
<div class="space-y-6 p-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Konten Dakwah
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi artikel atau berita masjid.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6">
        @include('dakwah-contents._form', [
            'content' => $content,
            'action' => route('dakwah-contents.update', $content),
            'method' => 'PUT',
        ])
    </div>
</div>
@endsection