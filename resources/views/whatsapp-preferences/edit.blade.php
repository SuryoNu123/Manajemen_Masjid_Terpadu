@extends('layouts.dashboard')

@section('title', 'Preferensi Notifikasi')
@section('page-title', 'Preferensi Notifikasi')

@section('content')
<div class="mx-auto max-w-2xl space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <div><h2 class="text-xl font-bold">Pengumuman WhatsApp</h2><p class="mt-1 text-sm text-slate-600">Anda mengendalikan apakah pengumuman masjid boleh dikirim ke nomor yang terhubung dengan data Jamaah Anda.</p></div>
    @if (session('success'))<p class="rounded-lg bg-green-50 p-3 text-sm text-green-800">{{ session('success') }}</p>@endif
    @if ($errors->any())<div class="rounded-lg bg-red-50 p-3 text-sm text-red-800">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    @if (! $jamaah)
        <p class="rounded-lg bg-amber-50 p-4 text-sm text-amber-800">Profil akun belum ditautkan ke data Jamaah. Hubungi pengurus untuk memperbarui nomor telepon atau menghubungkan profil.</p>
    @else
        <dl class="grid gap-3 rounded-lg bg-slate-50 p-4 text-sm sm:grid-cols-2"><div><dt class="text-xs uppercase text-slate-500">Nomor terhubung</dt><dd>{{ $jamaah->phone ?: 'Belum diisi' }}</dd></div><div><dt class="text-xs uppercase text-slate-500">Provider WhatsApp</dt><dd>{{ $configured ? 'Terkonfigurasi' : 'Belum dikonfigurasi — tidak ada pesan yang dikirim' }}</dd></div></dl>
        <form method="POST" action="{{ route('whatsapp-preferences.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <label class="flex items-start gap-3 rounded-lg border border-slate-200 p-4 text-sm text-slate-700"><input type="checkbox" name="whatsapp_opt_in" value="1" @checked(old('whatsapp_opt_in', $jamaah->whatsapp_opt_in)) @disabled(! $jamaah->phone) class="mt-0.5 rounded border-slate-300 text-emerald-700"><span>Saya setuju menerima pengumuman Masjid Terpadu melalui WhatsApp pada nomor terhubung ini. Saya dapat menarik persetujuan kapan saja dari halaman ini.</span></label>
            <p class="text-xs text-slate-500">Status: {{ $jamaah->whatsapp_opt_in ? 'Menyetujui' : 'Tidak menyetujui' }}{{ $jamaah->whatsapp_opt_in_at ? ' sejak '.$jamaah->whatsapp_opt_in_at->format('d/m/Y H:i') : '' }}. Persetujuan tidak mengirim pesan uji atau pesan lama.</p>
            <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Simpan preferensi</button>
        </form>
    @endif
</div>
@endsection
