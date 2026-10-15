@extends('layouts.dashboard')

@section('title', 'Log Aktivitas Pengurus')
@section('page-title', 'Log Aktivitas Pengurus')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Audit Log & Aktivitas
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Pencatatan aktivitas penting pengurus meliputi login, transaksi, dan perubahan data.
            </p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="flex flex-wrap gap-3">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari deskripsi, IP, atau nama pengguna..."
                   class="min-w-[240px] flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">

            <select name="action" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                <option value="">Semua Tindakan</option>
                @foreach($actions as $act)
                    <option value="{{ $act }}" @selected(request('action') === $act)>
                        {{ ucfirst(str_replace('_', ' ', $act)) }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Filter
            </button>

            @if(request()->hasAny(['search', 'action']))
                <a href="{{ route('activity-logs.index') }}"
                   class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-5 py-3">Waktu</th>
                        <th class="px-5 py-3">Pengguna</th>
                        <th class="px-5 py-3">Aksi</th>
                        <th class="px-5 py-3">Deskripsi</th>
                        <th class="px-5 py-3">Alamat IP</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-5 py-4 text-xs text-gray-500">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-gray-800">
                                    {{ $log->user?->name ?? 'Sistem / Tamu' }}
                                </div>
                                @if($log->user?->role)
                                    <div class="text-xs text-gray-500">
                                        {{ $log->user->role->display_name }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-block rounded-full px-2.5 py-1 text-xs font-semibold
                                    @if(str_contains($log->action, 'delete') || str_contains($log->action, 'reject'))
                                        bg-red-100 text-red-700
                                    @elseif(str_contains($log->action, 'create') || str_contains($log->action, 'approve') || str_contains($log->action, 'publish'))
                                        bg-green-100 text-green-700
                                    @elseif($log->action === 'login')
                                        bg-blue-100 text-blue-700
                                    @else
                                        bg-slate-100 text-slate-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-700">
                                {{ $log->description }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-xs text-gray-500 font-mono">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                                Belum ada aktivitas yang dicatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 p-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection

