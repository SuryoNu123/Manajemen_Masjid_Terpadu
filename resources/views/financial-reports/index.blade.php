@extends('layouts.dashboard')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')

@section('content')

<div class="flex items-center justify-between mb-8">

    <div>

        <h1 class="text-2xl font-bold">
            Laporan Keuangan
        </h1>

        <p class="text-gray-500 mt-1">
            Laporan keuangan bulanan dan tahunan masjid.
        </p>

    </div>


    @if(auth()->user()->hasRole('bendahara'))

        <a
            href="{{ route('laporan-keuangan.create') }}"
            class="px-4 py-2.5 bg-slate-900 text-white rounded-lg"
        >
            + Buat Laporan
        </a>

    @endif

</div>


@if(session('success'))

    <div class="mb-6 bg-green-100 text-green-700 p-4 rounded-lg">
        {{ session('success') }}
    </div>

@endif


@if(session('error'))

    <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-lg">
        {{ session('error') }}
    </div>

@endif


<form method="GET" action="{{ route('laporan-keuangan.index') }}" class="mb-6 grid gap-3 rounded-xl border border-gray-200 bg-white p-4 sm:grid-cols-2 lg:grid-cols-4">
    <div>
        <label for="report-year" class="mb-1 block text-sm font-medium text-gray-700">Tahun</label>
        <input id="report-year" name="year" type="number" min="1900" max="2200" value="{{ request('year') }}" placeholder="Semua tahun" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-600 focus:ring-blue-600">
    </div>
    <div>
        <label for="report-period-type" class="mb-1 block text-sm font-medium text-gray-700">Periode</label>
        <select id="report-period-type" name="period_type" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-600 focus:ring-blue-600">
            <option value="">Semua periode</option>
            <option value="monthly" @selected(request('period_type') === 'monthly')>Bulanan</option>
            <option value="yearly" @selected(request('period_type') === 'yearly')>Tahunan</option>
        </select>
    </div>
    <div>
        <label for="report-status" class="mb-1 block text-sm font-medium text-gray-700">Status</label>
        <select id="report-status" name="status" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-600 focus:ring-blue-600">
            <option value="">Semua status</option>
            @foreach(['draft' => 'Draft', 'submitted' => 'Menunggu Review', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'published' => 'Dipublikasikan'] as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-1">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Terapkan</button>
        <a href="{{ route('laporan-keuangan.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Reset</a>
    </div>
</form>


<div class="bg-white rounded-xl shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 border-b">

                <tr>

                    <th class="px-6 py-4 text-left">
                        Periode
                    </th>

                    <th class="px-6 py-4 text-right">
                        Pemasukan
                    </th>

                    <th class="px-6 py-4 text-right">
                        Pengeluaran
                    </th>

                    <th class="px-6 py-4 text-right">
                        Saldo
                    </th>

                    <th class="px-6 py-4 text-left">
                        Status
                    </th>

                    <th class="px-6 py-4 text-right">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y">

                @forelse($reports as $report)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 font-medium">

                            @if($report->period_type === 'monthly')

                                @php

                                    $months = [
                                        1 => 'Januari',
                                        2 => 'Februari',
                                        3 => 'Maret',
                                        4 => 'April',
                                        5 => 'Mei',
                                        6 => 'Juni',
                                        7 => 'Juli',
                                        8 => 'Agustus',
                                        9 => 'September',
                                        10 => 'Oktober',
                                        11 => 'November',
                                        12 => 'Desember',
                                    ];

                                @endphp

                                {{ $months[$report->month] }}
                                {{ $report->year }}

                            @else

                                Tahun {{ $report->year }}

                            @endif

                        </td>


                        <td class="px-6 py-4 text-right text-green-600 font-semibold">

                            Rp {{ number_format(
                                $report->total_income,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        <td class="px-6 py-4 text-right text-red-600 font-semibold">

                            Rp {{ number_format(
                                $report->total_expense,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        <td class="px-6 py-4 text-right font-semibold">

                            Rp {{ number_format(
                                $report->balance,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        <td class="px-6 py-4">

                            @php

                                $status = match($report->status) {

                                    'draft' => [
                                        'Draft',
                                        'bg-gray-100 text-gray-700'
                                    ],

                                    'submitted' => [
                                        'Menunggu Review',
                                        'bg-yellow-100 text-yellow-700'
                                    ],

                                    'approved' => [
                                        'Disetujui',
                                        'bg-green-100 text-green-700'
                                    ],

                                    'rejected' => [
                                        'Ditolak',
                                        'bg-red-100 text-red-700'
                                    ],

                                    'published' => [
                                        'Dipublikasikan',
                                        'bg-blue-100 text-blue-700'
                                    ],

                                    default => [
                                        ucfirst($report->status),
                                        'bg-gray-100 text-gray-700'
                                    ],
                                };

                            @endphp


                            <span
                                class="px-2 py-1 rounded-full text-xs {{ $status[1] }}"
                            >
                                {{ $status[0] }}
                            </span>

                        </td>


                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">


                                {{-- BENDAHARA SUBMIT --}}

                                @if(
                                    auth()->user()->hasRole('bendahara')
                                    && $report->status === 'draft'
                                )

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'laporan-keuangan.submit',
                                            $report
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg"
                                        >
                                            Submit
                                        </button>

                                    </form>

                                @endif


                                {{-- KETUA APPROVE --}}

                                @if(
                                    auth()->user()->hasRole('ketua')
                                    && $report->status === 'submitted'
                                )

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'laporan-keuangan.approve',
                                            $report
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Setujui laporan ini?')"
                                            class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg"
                                        >
                                            Approve
                                        </button>

                                    </form>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'laporan-keuangan.reject',
                                            $report
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Tolak laporan ini?')"
                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                @endif


                                {{-- KETUA PUBLISH --}}

                                @if(
                                    auth()->user()->hasRole('ketua')
                                    && $report->status === 'approved'
                                )

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'laporan-keuangan.publish',
                                            $report
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Publikasikan laporan ini?')"
                                            class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg"
                                        >
                                            Publish
                                        </button>

                                    </form>

                                @endif


                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="px-6 py-12 text-center text-gray-400"
                        >
                            Belum ada laporan keuangan.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="px-6 py-4 border-t">

        {{ $reports->links() }}

    </div>

</div>

@endsection
