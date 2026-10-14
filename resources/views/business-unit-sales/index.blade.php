
@extends('layouts.dashboard')

@section('title', 'Pencatatan Omzet')
@section('page-title', 'Pencatatan Omzet')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Periksa kembali data yang dikirim.
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">
                Pencatatan Omzet Unit Usaha
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Pantau penjualan dan status pemasukan unit usaha masjid.
            </p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('business-unit-sales.report') }}"
               class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Laporan Omzet
            </a>

            <a href="{{ route('business-unit-sales.create') }}"
               class="rounded-lg bg-slate-900 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-slate-700">
                + Catat Penjualan
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET"
              action="{{ route('business-unit-sales.index') }}"
              class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:items-end">

            <div>
                <label for="reference_date"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Tanggal Acuan
                </label>
                <input id="reference_date" name="reference_date"
                       type="date" required
                       max="{{ today()->toDateString() }}"
                       value="{{ $referenceDate->toDateString() }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
            </div>

            <div>
                <label for="business_unit_id"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Unit Usaha
                </label>
                <select id="business_unit_id" name="business_unit_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                    <option value="">Semua Unit Usaha</option>
                    @foreach ($businessUnits as $unit)
                        <option value="{{ $unit->id }}"
                            @selected((string) ($filters['business_unit_id'] ?? '') === (string) $unit->id)>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">
                    Tampilkan
                </button>
                <a href="{{ route('business-unit-sales.index') }}"
                   class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-600">
                    Reset
                </a>
            </div>
        </form>

        <p class="mt-3 text-sm text-slate-500">
            Periode:
            <span class="font-medium text-slate-700">
                {{ $referenceDate->translatedFormat('F Y') }}
            </span>
        </p>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Omzet Tercatat</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">
                Rp {{ number_format((float) $totalAmount, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Jumlah Transaksi</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">
                {{ number_format($totalSales) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Kuantitas</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">
                {{ number_format($totalQuantity) }}
            </p>
        </div>
    </div>

    {{-- Tabel penjualan --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">Riwayat Penjualan</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">No.</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Unit Usaha</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Referensi</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-500">Kuantitas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Metode</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-500">Omzet</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status Keuangan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Pencatat</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($sales as $sale)
                        @php
                            $transactionStatus = $sale->financialTransaction?->status;

                            $statusLabels = [
                                'draft' => 'Menunggu Persetujuan',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ];

                            $statusClasses = [
                                'draft' => 'bg-amber-100 text-amber-700',
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                        @endphp

                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                {{ $sales->firstItem() + $loop->index }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $sale->sale_date->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4 text-sm font-medium text-slate-800">
                                {{ $sale->businessUnit?->name ?? 'Unit tidak tersedia' }}
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $sale->reference_number ?: '-' }}
                            </td>
                            <td class="px-5 py-4 text-right text-sm text-slate-600">
                                {{ number_format($sale->quantity) }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                @switch($sale->payment_method)
                                    @case('tunai')
                                        Tunai
                                        @break
                                    @case('qris')
                                        QRIS
                                        @break
                                    @case('transfer')
                                        Transfer
                                        @break
                                    @default
                                        Lainnya
                                @endswitch
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-semibold text-slate-800">
                                Rp {{ number_format((float) $sale->amount, 0, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                @if ($transactionStatus)
                                    <span class="rounded-full px-3 py-1 text-xs font-medium {{ $statusClasses[$transactionStatus] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $statusLabels[$transactionStatus] ?? $transactionStatus }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-500">
                                        Belum terhubung
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $sale->recorder?->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center text-sm text-slate-500">
                                Belum ada penjualan tercatat pada periode ini.
                                <a href="{{ route('business-unit-sales.create') }}"
                                   class="font-medium text-blue-700 hover:underline">
                                    Catat penjualan pertama.
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $sales->links() }}
        </div>
    </div>

</div>
@endsection
