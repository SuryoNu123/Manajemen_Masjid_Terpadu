@extends('layouts.dashboard')

@section('title', 'Edit Transaksi')
@section('page-title', 'Edit Transaksi')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Edit Transaksi
        </h1>

        <p class="text-gray-500 mt-1">
            {{ $transaksi->transaction_code }}
        </p>

    </div>


    @if($errors->any())

        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">

            <ul class="list-disc ml-5">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('transaksi.update', $transaksi) }}"
        enctype="multipart/form-data"
        class="bg-white rounded-xl shadow-sm p-6 space-y-6">

        @csrf
        @method('PUT')


        <div>

            <label class="block font-medium mb-2">
                Jenis Transaksi
            </label>

            <select
                name="type"
                id="type"
                required
                class="w-full rounded-lg border-gray-300">

                <option value="income"
                    @selected(old('type', $transaksi->type) === 'income')>
                    Pemasukan
                </option>

                <option value="expense"
                    @selected(old('type', $transaksi->type) === 'expense')>
                    Pengeluaran
                </option>

            </select>

        </div>


        <div>

            <label class="block font-medium mb-2">
                Kategori
            </label>

            <select
                name="category_id"
                id="category_id"
                required
                class="w-full rounded-lg border-gray-300">

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        data-type="{{ $category->type }}"
                        @selected(
                            old(
                                'category_id',
                                $transaksi->category_id
                            ) == $category->id
                        )>

                        {{ $category->name }}

                    </option>

                @endforeach

            </select>

        </div>


        <div>

            <label class="block font-medium mb-2">
                Nominal
            </label>

            <input
                type="number"
                name="amount"
                min="1"
                required
                value="{{ old('amount', $transaksi->amount) }}"
                class="w-full rounded-lg border-gray-300">

        </div>


        <div>

            <label class="block font-medium mb-2">
                Tanggal Transaksi
            </label>

            <input
                type="date"
                name="transaction_date"
                required
                value="{{ old(
                    'transaction_date',
                    $transaksi->transaction_date->format('Y-m-d')
                ) }}"
                class="w-full rounded-lg border-gray-300">

        </div>


        <div>

            <label class="block font-medium mb-2">
                Sumber / Penerima
            </label>

            <input
                type="text"
                name="source"
                value="{{ old('source', $transaksi->source) }}"
                class="w-full rounded-lg border-gray-300">

        </div>


        <div>

            <label class="block font-medium mb-2">
                Keterangan
            </label>

            <textarea
                name="description"
                rows="4"
                class="w-full rounded-lg border-gray-300">{{ old('description', $transaksi->description) }}</textarea>

        </div>


        <div>

            <label class="block font-medium mb-2">
                Ganti Bukti Transaksi
            </label>

            <input
                type="file"
                name="evidence"
                accept=".jpg,.jpeg,.png,.pdf"
                class="w-full">

        </div>


        <div class="flex justify-end gap-3 pt-4 border-t">

            <a
                href="{{ route('transaksi.index') }}"
                class="px-5 py-2.5 bg-gray-200 rounded-lg">
                Batal
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg">
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>


@push('scripts')

<script>

    const typeSelect = document.getElementById('type');
    const categorySelect =
        document.getElementById('category_id');

    function filterCategories() {

        const selectedType = typeSelect.value;

        Array.from(categorySelect.options)
            .forEach(option => {

                option.hidden =
                    option.dataset.type !== selectedType;

            });

    }

    typeSelect.addEventListener(
        'change',
        filterCategories
    );

    filterCategories();

</script>

@endpush

@endsection