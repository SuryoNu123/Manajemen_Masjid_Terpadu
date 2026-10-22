@extends('layouts.dashboard')

@section('title', 'Kelola Donasi')
@section('page-title', 'Kelola Donasi')

@section('content')

<div class="mb-8">

    <h1 class="text-2xl font-bold">
        Kelola Donasi
    </h1>

    <p class="text-gray-500 mt-1">
        Pantau dan konfirmasi pembayaran donasi.
    </p>

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


{{-- SUMMARY --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

    <div class="bg-white p-6 rounded-xl shadow-sm">

        <p class="text-sm text-gray-500">
            Total Donasi Terbayar
        </p>

        <p class="text-2xl font-bold text-green-600 mt-2">
            Rp {{ number_format(
                $totalPaid,
                0,
                ',',
                '.'
            ) }}
        </p>

    </div>


    <div class="bg-white p-6 rounded-xl shadow-sm">

        <p class="text-sm text-gray-500">
            Donasi Pending
        </p>

        <p class="text-2xl font-bold text-yellow-600 mt-2">
            Rp {{ number_format(
                $totalPending,
                0,
                ',',
                '.'
            ) }}
        </p>

    </div>

</div>


<div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
    <form method="GET" action="{{ route('donasi.index') }}"
          class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-7">
        <div>
            <label class="mb-1 block text-sm font-medium" for="donation-search">Cari donasi</label>
            <input id="donation-search" type="search" name="search" value="{{ request('search') }}"
                   placeholder="Kode atau data donatur"
                   class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium" for="donation-type">Jenis</label>
            <select id="donation-type" name="type" class="w-full rounded-lg border-gray-300 text-sm">
                <option value="">Semua jenis</option>
                @foreach (['infaq' => 'Infaq', 'sedekah' => 'Sedekah', 'zakat' => 'Zakat', 'qurban' => 'Qurban'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium" for="donation-status">Status pembayaran</label>
            <select id="donation-status" name="payment_status" class="w-full rounded-lg border-gray-300 text-sm">
                <option value="">Semua status</option>
                @foreach (['pending' => 'Pending', 'paid' => 'Terbayar', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('payment_status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium" for="donation-method">Metode</label>
            <select id="donation-method" name="payment_method" class="w-full rounded-lg border-gray-300 text-sm">
                <option value="">Semua metode</option>
                @foreach (['qris' => 'QRIS', 'cash' => 'Tunai', 'transfer' => 'Transfer', 'other' => 'Lainnya'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('payment_method') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium" for="donation-start-date">Dari tanggal</label>
            <input id="donation-start-date" type="date" name="start_date" value="{{ request('start_date') }}"
                   class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium" for="donation-end-date">Sampai tanggal</label>
            <input id="donation-end-date" type="date" name="end_date" value="{{ request('end_date') }}"
                   class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white">Filter</button>
            <a href="{{ route('donasi.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm">Reset</a>
        </div>
    </form>
</div>

{{-- TABLE --}}

<div class="bg-white rounded-xl shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 border-b">

                <tr>

                    <th class="px-6 py-4 text-left">
                        Kode
                    </th>

                    <th class="px-6 py-4 text-left">
                        Donatur
                    </th>

                    <th class="px-6 py-4 text-left">
                        Jenis
                    </th>

                    <th class="px-6 py-4 text-right">
                        Nominal
                    </th>

                    <th class="px-6 py-4 text-left">
                        Pembayaran
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

                @forelse($donations as $donation)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 font-medium">
                            {{ $donation->donation_code }}
                        </td>


                        <td class="px-6 py-4">

                            {{ $donation->donor_name
                                ?: 'Anonim'
                            }}

                        </td>


                        <td class="px-6 py-4 capitalize">
                            {{ $donation->type }}
                        </td>


                        <td class="px-6 py-4 text-right font-semibold">

                            Rp {{ number_format(
                                $donation->amount,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        <td class="px-6 py-4 uppercase">
                            {{ $donation->payment_method }}
                        </td>


                        <td class="px-6 py-4">

                            @if($donation->payment_status === 'paid')

                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    Paid
                                </span>

                            @elseif($donation->payment_status === 'failed')

                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                    Failed
                                </span>

                            @elseif($donation->payment_status === 'expired')

                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                    Expired
                                </span>

                            @else

                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>

                            @endif

                        </td>


                        <td class="px-6 py-4">

                            @if($donation->payment_status === 'pending' && $donation->payment_method !== 'qris')

                                <div class="flex justify-end gap-2">

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'donasi.mark-paid',
                                            $donation
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Konfirmasi donasi sebagai PAID?')"
                                            class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg"
                                        >
                                            Paid
                                        </button>

                                    </form>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'donasi.mark-failed',
                                            $donation
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Tandai pembayaran sebagai gagal?')"
                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg"
                                        >
                                            Failed
                                        </button>

                                    </form>

                                </div>

                            @elseif($donation->payment_status === 'pending')
                                <span class="text-xs text-gray-500">Menunggu konfirmasi gateway</span>
                            @else

                                <span class="text-gray-400 text-xs">
                                    —
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="px-6 py-12 text-center text-gray-400"
                        >
                            Belum ada donasi.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="px-6 py-4 border-t">
        {{ $donations->links() }}
    </div>

</div>

@endsection
