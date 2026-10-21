@extends('layouts.dashboard')

@section('title', 'Peminjaman Saya')
@section('page-title', 'Peminjaman Saya')

@section('content')
<div class="space-y-5">
    @if (session('success'))<p class="rounded-lg bg-green-50 p-3 text-sm text-green-800">{{ session('success') }}</p>@endif
    @if (! $jamaah)<p class="rounded-lg bg-amber-50 p-4 text-sm text-amber-800">Akun ini belum ditautkan ke data Jamaah. Hubungi pengurus masjid agar profil Anda ditautkan sebelum mengajukan pinjaman.</p>@endif
    <h2 class="text-xl font-bold">Permintaan dan riwayat pinjam</h2>
    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Buku</th><th class="px-4 py-3 text-left">Diajukan</th><th class="px-4 py-3 text-left">Status</th><th class="px-4 py-3 text-left">Jatuh tempo</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($requests as $item)
                    <tr><td class="px-4 py-3">{{ $item->book->title }}</td><td class="px-4 py-3">{{ $item->created_at->format('d/m/Y') }}</td><td class="px-4 py-3">{{ ['pending' => 'Menunggu persetujuan', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'][$item->status] ?? $item->status }}</td><td class="px-4 py-3">{{ $item->loan?->due_date?->format('d/m/Y') ?? '—' }}</td></tr>
                @empty
                    <tr><td colspan="4" class="p-8 text-center text-slate-500">Belum ada permintaan pinjam.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t p-4">{{ $requests->links() }}</div>
    </div>
    <a href="{{ route('library.public') }}" class="font-semibold text-emerald-800">Jelajahi katalog</a>
</div>
@endsection
