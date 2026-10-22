@extends('layouts.dashboard')

@section('title', 'Transaksi Keuangan')
@section('page-title', 'Transaksi Keuangan')

@section('content')

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Transaksi Keuangan
        </h1>

        <p class="text-gray-500 mt-1">
            Kelola pemasukan dan pengeluaran masjid.
        </p>
    </div>

    @if(auth()->user()->hasRole('bendahara'))
        <div class="flex flex-wrap gap-2">

            <a href="{{ route('transaksi.create', ['type' => 'income']) }}"
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                + Pemasukan
            </a>

            <a href="{{ route('transaksi.create', ['type' => 'expense']) }}"
               class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                + Pengeluaran
            </a>

        </div>
    @endif

</div>


{{-- ALERT --}}

@if(session('success'))

    <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>

@endif


{{-- SUMMARY --}}

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-sm text-gray-500">
            Total Pemasukan
        </p>

        <p class="text-2xl font-bold text-green-600 mt-2">
            Rp {{ number_format($totalIncome, 0, ',', '.') }}
        </p>
    </div>


    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-sm text-gray-500">
            Total Pengeluaran
        </p>

        <p class="text-2xl font-bold text-red-600 mt-2">
            Rp {{ number_format($totalExpense, 0, ',', '.') }}
        </p>
    </div>


    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-sm text-gray-500">
            Saldo
        </p>

        <p class="text-2xl font-bold text-gray-900 mt-2">
            Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
        </p>
    </div>

</div>


{{-- FILTER --}}

<div class="bg-white rounded-xl shadow-sm p-5 mb-6">

    <form method="GET"
          action="{{ route('transaksi.index') }}"
          class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4">

        <div>
            <label class="block text-sm font-medium mb-1" for="transaction-search">
                Pencarian
            </label>
            <input id="transaction-search" type="search" name="search"
                   value="{{ request('search') }}"
                   placeholder="Kode, kategori, atau keterangan"
                   class="w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Jenis
            </label>

            <select name="type"
                    class="w-full rounded-lg border-gray-300">

                <option value="">
                    Semua
                </option>

                <option value="income"
                    @selected(request('type') === 'income')>
                    Pemasukan
                </option>

                <option value="expense"
                    @selected(request('type') === 'expense')>
                    Pengeluaran
                </option>

            </select>
        </div>


        <div>
            <label class="block text-sm font-medium mb-1">
                Kategori
            </label>

            <select name="category_id"
                    class="w-full rounded-lg border-gray-300">

                <option value="">
                    Semua kategori
                </option>

                @foreach($categories as $category)

                    <option value="{{ $category->id }}"
                        @selected(
                            (string) request('category_id')
                            === (string) $category->id
                        )>

                        {{ $category->name }}
                        -
                        {{ $category->type === 'income'
                            ? 'Pemasukan'
                            : 'Pengeluaran'
                        }}

                    </option>

                @endforeach

            </select>
        </div>


        <div>
            <label class="block text-sm font-medium mb-1">
                Dari
            </label>

            <input
                type="date"
                name="start_date"
                value="{{ request('start_date') }}"
                class="w-full rounded-lg border-gray-300">
        </div>


        <div>
            <label class="block text-sm font-medium mb-1">
                Sampai
            </label>

            <input
                type="date"
                name="end_date"
                value="{{ request('end_date') }}"
                class="w-full rounded-lg border-gray-300">
        </div>


        <div class="flex items-end gap-2">

            <button
                type="submit"
                class="px-4 py-2 bg-slate-900 text-white rounded-lg">
                Filter
            </button>

            <a
                href="{{ route('transaksi.index') }}"
                class="px-4 py-2 bg-gray-200 rounded-lg">
                Reset
            </a>

        </div>

    </form>

</div>


{{-- TABLE --}}

<div class="bg-white rounded-xl shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 border-b">

                <tr>

                    <th class="text-left px-6 py-4">
                        Kode
                    </th>

                    <th class="text-left px-6 py-4">
                        Tanggal
                    </th>

                    <th class="text-left px-6 py-4">
                        Kategori
                    </th>

                    <th class="text-left px-6 py-4">
                        Jenis
                    </th>

                    <th class="text-right px-6 py-4">
                        Nominal
                    </th>

                    <th class="text-left px-6 py-4">
                        Status
                    </th>

                    @if(auth()->user()->hasRole('bendahara'))
                        <th class="text-right px-6 py-4">
                            Aksi
                        </th>
                    @endif

                </tr>

            </thead>


            <tbody class="divide-y">

                @forelse($transactions as $transaction)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 font-medium">
                            {{ $transaction->transaction_code }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $transaction->transaction_date->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $transaction->category->name }}
                        </td>

                        <td class="px-6 py-4">

                            @if($transaction->type === 'income')

                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                    Pemasukan
                                </span>

                            @else

                                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                    Pengeluaran
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-right font-semibold">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4">

                            @php

                                $statusClass = match($transaction->status) {
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default => 'bg-yellow-100 text-yellow-700',
                                };

                            @endphp

                            <span class="px-2 py-1 rounded-full text-xs {{ $statusClass }}">
                                {{ ucfirst($transaction->status) }}
                            </span>

                        </td>


                        @if(auth()->user()->hasRole('bendahara'))

                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('transaksi.edit', $transaction) }}"
                                        class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg">
                                        Edit
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('transaksi.destroy', $transaction) }}"
                                        onsubmit="return confirm('Hapus transaksi ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        @endif

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="px-6 py-12 text-center text-gray-400">

                            Belum ada transaksi.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="px-6 py-4 border-t">
        {{ $transactions->links() }}
    </div>

</div>

@endsection
