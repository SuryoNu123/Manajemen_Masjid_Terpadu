@extends('layouts.dashboard')

@section('title', 'Laporan Omzet Unit Usaha')
@section('page-title', 'Laporan Omzet Unit Usaha')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">
                Ringkasan Omzet Unit Usaha
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Bandingkan penjualan tercatat dengan pemasukan yang telah disetujui.
            </p>
        </div>

        <a href="{{ route('business-unit-sales.index') }}"
           class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali ke Riwayat Omzet
        </a>
    </div>

    {{-- Filter periode --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET"
              action="{{ route('business-unit-sales.report') }}"
              class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:items-end">

            <div>
                <label for="reference_date"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Bulan Laporan
                </label>

                <input id="reference_date"
                       type="date"
                       name="reference_date"
                       max="{{ today()->toDateString() }}"
                       value="{{ $referenceDate->toDateString() }}"
                       required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
            </div>

            <div>
                <label for="business_unit_id"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Unit Usaha
                </label>

                <select id="business_unit_id"
                        name="business_unit_id"
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
                        class="flex-1 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Tampilkan
                </button>

                <a href="{{ route('business-unit-sales.report') }}"
                   class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>

        <p class="mt-3 text-sm text-slate-500">
            Periode laporan:
            <span class="font-medium text-slate-700">
                {{ $startDate->translatedFormat('d F Y') }}
                – {{ $endDate->translatedFormat('d F Y') }}
            </span>
        </p>
    </div>

    {{-- Ringkasan global --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Penjualan Tercatat</p>
            <p class="mt-2 text-xl font-bold text-slate-800">
                Rp {{ number_format($totals['sales'], 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
                {{ number_format($totals['transactions']) }} catatan penjualan
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Pemasukan Disetujui</p>
            <p class="mt-2 text-xl font-bold text-green-700">
                Rp {{ number_format($totals['approved'], 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
                Transaksi income berstatus approved
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Menunggu Persetujuan</p>
            <p class="mt-2 text-xl font-bold text-amber-600">
                Rp {{ number_format($totals['pending'], 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
                Transaksi income berstatus draft
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Transaksi Ditolak</p>
            <p class="mt-2 text-xl font-bold text-red-600">
                Rp {{ number_format($totals['rejected'], 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
                Tidak dihitung sebagai pemasukan disetujui
            </p>
        </div>
    </div>

    {{-- Rekonsiliasi --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h3 class="font-semibold text-slate-800">
            Pemeriksaan Keterhubungan Data
        </h3>
        <p class="mt-1 text-sm text-slate-500">
            Periksa catatan penjualan yang belum terhubung atau memiliki
            transaksi keuangan dengan jenis/status yang tidak sesuai.
        </p>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                <p class="text-sm text-amber-800">Penjualan Belum Terhubung</p>
                <p class="mt-1 text-lg font-bold text-amber-900">
                    Rp {{ number_format($totals['unlinked'], 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                <p class="text-sm text-red-800">Data Keuangan Perlu Diperiksa</p>
                <p class="mt-1 text-lg font-bold text-red-900">
                    Rp {{ number_format($totals['unmatched'], 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Rekap per unit --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">
                Rekap per Unit Usaha
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Nilai di bawah menggunakan tanggal penjualan pada periode yang dipilih.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">Unit Usaha</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Transaksi</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-500">Penjualan Tercatat</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-500">Disetujui</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-500">Draft</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-500">Ditolak</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-500">Belum Terhubung</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($units as $unit)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <p class="text-sm font-medium text-slate-800">
                                    {{ $unit->name }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $unit->code ?: 'Tanpa kode' }}
                                    · {{ $unit->is_active ? 'Aktif' : 'Nonaktif' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-center text-sm text-slate-600">
                                {{ number_format($unit->transaction_count) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium text-slate-800">
                                Rp {{ number_format((float) $unit->total_sales, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium text-green-700">
                                Rp {{ number_format((float) $unit->approved_amount, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-amber-700">
                                Rp {{ number_format((float) $unit->pending_amount, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-red-600">
                                Rp {{ number_format((float) $unit->rejected_amount, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-slate-600">
                                Rp {{ number_format((float) $unit->unlinked_amount, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                <a href="{{ route('business-unit-sales.index', [
                                        'reference_date' => $referenceDate->toDateString(),
                                        'business_unit_id' => $unit->id,
                                    ]) }}"
                                   class="text-sm font-medium text-blue-700 hover:underline">
                                    Lihat catatan
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-sm text-slate-500">
                                Belum ada unit usaha yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot class="bg-slate-50">
                    <tr>
                        <td class="px-5 py-4 text-sm font-bold text-slate-800">
                            Total
                        </td>
                        <td class="px-5 py-4 text-center text-sm font-bold text-slate-800">
                            {{ number_format($totals['transactions']) }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-bold text-slate-800">
                            Rp {{ number_format($totals['sales'], 0, ',', '.') }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-bold text-green-700">
                            Rp {{ number_format($totals['approved'], 0, ',', '.') }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-bold text-amber-700">
                            Rp {{ number_format($totals['pending'], 0, ',', '.') }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-bold text-red-600">
                            Rp {{ number_format($totals['rejected'], 0, ',', '.') }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-bold text-slate-800">
                            Rp {{ number_format($totals['unlinked'], 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection
