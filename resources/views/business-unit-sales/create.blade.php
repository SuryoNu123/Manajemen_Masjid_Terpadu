
@extends('layouts.dashboard')

@section('title', 'Catat Penjualan')
@section('page-title', 'Catat Penjualan')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-800">
            Catat Penjualan Unit Usaha
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Masukkan data penjualan untuk mencatat omzet unit usaha masjid.
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Data belum dapat disimpan.</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @if ($businessUnits->isEmpty())
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                Belum ada unit usaha aktif. Tambahkan atau aktifkan unit usaha terlebih dahulu.
            </div>

            <a href="{{ route('business-unit-sales.index') }}"
               class="mt-5 inline-flex rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-600">
                Kembali
            </a>
        @else
            <form action="{{ route('business-unit-sales.store') }}" method="POST">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="business_unit_id"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Unit Usaha <span class="text-red-500">*</span>
                        </label>
                        <select id="business_unit_id" name="business_unit_id" required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                            <option value="">-- Pilih Unit Usaha --</option>
                            @foreach ($businessUnits as $unit)
                                <option value="{{ $unit->id }}"
                                    @selected((string) old('business_unit_id') === (string) $unit->id)>
                                    {{ $unit->code ? $unit->code . ' - ' : '' }}{{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('business_unit_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sale_date"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Tanggal Penjualan <span class="text-red-500">*</span>
                        </label>
                        <input id="sale_date" name="sale_date" type="date"
                               max="{{ today()->toDateString() }}"
                               value="{{ old('sale_date', today()->toDateString()) }}"
                               required
                               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                        @error('sale_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reference_number"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Nomor Referensi
                        </label>
                        <input id="reference_number" name="reference_number" type="text"
                               value="{{ old('reference_number') }}"
                               placeholder="Opsional: nomor nota atau bukti penjualan"
                               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                        @error('reference_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="quantity"
                                   class="mb-2 block text-sm font-medium text-slate-700">
                                Kuantitas <span class="text-red-500">*</span>
                            </label>
                            <input id="quantity" name="quantity" type="number"
                                   min="1" step="1" value="{{ old('quantity', 1) }}"
                                   required
                                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="amount"
                                   class="mb-2 block text-sm font-medium text-slate-700">
                                Total Penjualan (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input id="amount" name="amount" type="number"
                                   min="0.01" step="0.01"
                                   value="{{ old('amount') }}"
                                   placeholder="Contoh: 250000"
                                   required
                                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="payment_method"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_method" name="payment_method" required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                            <option value="tunai" @selected(old('payment_method', 'tunai') === 'tunai')>
                                Tunai
                            </option>
                            <option value="qris" @selected(old('payment_method') === 'qris')>
                                QRIS
                            </option>
                            <option value="transfer" @selected(old('payment_method') === 'transfer')>
                                Transfer
                            </option>
                            <option value="lainnya" @selected(old('payment_method') === 'lainnya')>
                                Lainnya
                            </option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Keterangan
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  placeholder="Keterangan tambahan penjualan"
                                  class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('business-unit-sales.index') }}"
                       class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-medium text-slate-600">
                        Batal
                    </a>

                    <button type="submit"
                            class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                        Simpan Penjualan
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
