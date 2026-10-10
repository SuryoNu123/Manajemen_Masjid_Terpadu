@extends('layouts.dashboard')

@section('title', 'Dashboard Jamaah')
@section('page-title', 'Dashboard Jamaah')

@section('content')

<h1 class="text-2xl font-bold mb-2">
    Selamat Datang, {{ auth()->user()->name }}
</h1>

<p class="text-gray-500">
    Akses informasi kegiatan dan layanan masjid.
</p>

@endsection