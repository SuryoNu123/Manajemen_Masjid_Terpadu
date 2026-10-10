@extends('layouts.dashboard')

@section('title', 'Mustahik')
@section('page-title', 'Data Mustahik')

@section('content')

<div class="flex justify-between items-center mb-6">

    <div>
        <h1 class="text-2xl font-bold">
            Data Mustahik
        </h1>

        <p class="text-gray-500 mt-1">
            Kelola penerima penyaluran zakat.
        </p>
    </div>

    <a
        href="{{ route('mustahik.create') }}"
        class="px-4 py-2 bg-slate-900 text-white rounded-lg">
        + Tambah Mustahik
    </a>

</div>


@if(session('success'))

<div class="mb-6 bg-green-100 text-green-700 p-4 rounded-lg">
    {{ session('success') }}
</div>

@endif


<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <p class="text-gray-500">
            Total Mustahik
        </p>

        <p class="text-2xl font-bold mt-2">
            {{ $totalRecipients }}
        </p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <p class="text-gray-500">
            Sudah Disalurkan
        </p>

        <p class="text-2xl font-bold text-green-600 mt-2">
            Rp {{ number_format(
                $totalDistributed,
                0,
                ',',
                '.'
            ) }}
        </p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <p class="text-gray-500">
            Menunggu
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


<div class="bg-white rounded-xl shadow-sm overflow-hidden">

<table class="w-full text-sm">

<thead class="bg-gray-50 border-b">

<tr>

    <th class="px-6 py-4 text-left">
        Nama
    </th>

    <th class="px-6 py-4 text-left">
        Asnaf
    </th>

    <th class="px-6 py-4 text-right">
        Nominal
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

@forelse($mustahiks as $mustahik)

<tr>

    <td class="px-6 py-4">
        {{ $mustahik->name }}
    </td>

    <td class="px-6 py-4 capitalize">
        {{ str_replace('_', ' ', $mustahik->asnaf) }}
    </td>

    <td class="px-6 py-4 text-right font-semibold">
        Rp {{ number_format(
            $mustahik->amount,
            0,
            ',',
            '.'
        ) }}
    </td>

    <td class="px-6 py-4">

        @if($mustahik->status === 'distributed')

            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                Disalurkan
            </span>

        @else

            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                Pending
            </span>

        @endif

    </td>

    <td class="px-6 py-4">

        <div class="flex justify-end gap-2">

            <a
                href="{{ route('mustahik.edit', $mustahik) }}"
                class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg">
                Edit
            </a>

            <form
                method="POST"
                action="{{ route('mustahik.destroy', $mustahik) }}"
                onsubmit="return confirm('Hapus data mustahik?')">

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

</tr>

@empty

<tr>

<td
    colspan="5"
    class="px-6 py-12 text-center text-gray-400">

    Belum ada data mustahik.

</td>

</tr>

@endforelse

</tbody>

</table>

<div class="px-6 py-4 border-t">
    {{ $mustahiks->links() }}
</div>

</div>

@endsection