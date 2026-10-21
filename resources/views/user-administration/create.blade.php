@extends('layouts.dashboard')
@section('title', 'Tambah Akun')
@section('page-title', 'Tambah Akun')
@section('content')<div class="mx-auto max-w-2xl space-y-5 rounded-xl border border-slate-200 bg-white p-6"><h2 class="text-xl font-bold">Buat akun pengurus atau Jamaah</h2><form method="POST" action="{{ route('user-administration.store') }}" class="space-y-4">@csrf @include('user-administration._form')<div class="flex gap-3"><button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Simpan akun</button><a href="{{ route('user-administration.index') }}" class="rounded-lg border px-4 py-2.5 text-sm">Batal</a></div></form></div>@endsection
