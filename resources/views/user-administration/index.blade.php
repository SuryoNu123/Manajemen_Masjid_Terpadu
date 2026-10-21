@extends('layouts.dashboard')

@section('title', 'Administrasi Akun')
@section('page-title', 'Administrasi Akun')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div><h2 class="text-xl font-bold text-slate-800">Akun pengguna</h2><p class="mt-1 text-sm text-slate-500">Kelola akun, role, status, dan tautan data Jamaah.</p></div>
        <a href="{{ route('user-administration.create') }}" class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Tambah akun</a>
    </div>
    @if (session('success'))<p class="rounded-lg bg-green-50 p-3 text-sm text-green-800">{{ session('success') }}</p>@endif
    @if (session('error'))<p class="rounded-lg bg-amber-50 p-3 text-sm text-amber-800">{{ session('error') }}</p>@endif
    <form method="GET" class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-4">
        <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari nama atau email" class="rounded-lg border-slate-300 text-sm">
        <select name="role_id" class="rounded-lg border-slate-300 text-sm"><option value="">Semua role</option>@foreach ($roles as $role)<option value="{{ $role->id }}" @selected(($filters['role_id'] ?? '') == $role->id)>{{ $role->display_name }}</option>@endforeach</select>
        <select name="status" class="rounded-lg border-slate-300 text-sm"><option value="">Semua status</option><option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option><option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Nonaktif</option></select>
        <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Terapkan filter</button>
    </form>
    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm"><thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Nama</th><th class="px-4 py-3 text-left">Email</th><th class="px-4 py-3 text-left">Role</th><th class="px-4 py-3 text-left">Status</th><th class="px-4 py-3 text-left">Profil Jamaah</th><th class="px-4 py-3"></th></tr></thead><tbody class="divide-y divide-slate-100">
        @forelse ($users as $user)<tr><td class="px-4 py-3 font-medium">{{ $user->name }}</td><td class="px-4 py-3">{{ $user->email }}</td><td class="px-4 py-3">{{ $user->roleDisplayName() }}</td><td class="px-4 py-3">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td><td class="px-4 py-3">{{ $user->jamaah?->name ?? '—' }}</td><td class="px-4 py-3 text-right"><a class="font-semibold text-blue-700" href="{{ route('user-administration.show', $user) }}">Detail</a></td></tr>
        @empty<tr><td colspan="6" class="p-8 text-center text-slate-500">Belum ada akun.</td></tr>@endforelse
        </tbody></table>
        <div class="border-t p-4">{{ $users->links() }}</div>
    </div>
</div>
@endsection
