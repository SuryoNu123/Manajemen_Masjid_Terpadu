@if ($errors->any())
    <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
        @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
    </div>
@endif
<div class="grid gap-5 sm:grid-cols-2">
    <div><label for="name" class="mb-1 block text-sm font-medium">Nama lengkap</label><input id="name" name="name" value="{{ old('name', $jamaah?->name) }}" required maxlength="255" class="w-full rounded-lg border-slate-300"></div>
    <div><label for="domicile" class="mb-1 block text-sm font-medium">Domisili</label><input id="domicile" name="domicile" value="{{ old('domicile', $jamaah?->domicile) }}" required maxlength="255" class="w-full rounded-lg border-slate-300"></div>
    <div><label for="birth_date" class="mb-1 block text-sm font-medium">Tanggal lahir</label><input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date', $jamaah?->birth_date?->format('Y-m-d')) }}" max="{{ today()->toDateString() }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label for="phone" class="mb-1 block text-sm font-medium">Nomor telepon</label><input id="phone" name="phone" value="{{ old('phone', $jamaah?->phone) }}" maxlength="20" class="w-full rounded-lg border-slate-300"></div>
    <div><label for="status" class="mb-1 block text-sm font-medium">Status Jamaah</label><select id="status" name="status" required class="w-full rounded-lg border-slate-300"><option value="aktif" @selected(old('status', $jamaah?->status ?? 'aktif') === 'aktif')>Aktif</option><option value="nonaktif" @selected(old('status', $jamaah?->status) === 'nonaktif')>Nonaktif</option></select></div>
    <div class="sm:col-span-2"><label for="notes" class="mb-1 block text-sm font-medium">Catatan</label><textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border-slate-300">{{ old('notes', $jamaah?->notes) }}</textarea></div>
    <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700 sm:col-span-2"><input type="checkbox" name="whatsapp_opt_in" value="1" @checked(old('whatsapp_opt_in', $jamaah?->whatsapp_opt_in ?? false)) class="mt-0.5 rounded border-slate-300 text-emerald-700"><span><span class="font-medium">Setuju menerima pengumuman melalui WhatsApp</span><span class="mt-1 block text-xs text-slate-500">Pesan hanya dikirim ke nomor aktif yang menyetujui penerimaan pengumuman.</span></span></label>
</div>
