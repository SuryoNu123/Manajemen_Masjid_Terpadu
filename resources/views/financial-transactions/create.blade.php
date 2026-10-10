@extends('layouts.dashboard')

@section('title', 'Tambah Transaksi')
@section('page-title', 'Tambah Transaksi')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Tambah Transaksi
        </h1>

        <p class="text-gray-500 mt-1">
            Catat pemasukan atau pengeluaran masjid.
        </p>

    </div>


    @if($errors->any())

        <div class="bg-red-100 border border-red-200 text-red-700 p-4 rounded-lg mb-6">

            <ul class="list-disc ml-5">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('transaksi.store') }}"
        enctype="multipart/form-data"
        class="bg-white rounded-xl shadow-sm p-6 space-y-6">

        @csrf


        {{-- JENIS --}}

        <div>

            <label class="block font-medium mb-2">
                Jenis Transaksi
            </label>

            <div class="grid grid-cols-2 gap-3">

                <label class="cursor-pointer">

                    <input
                        type="radio"
                        name="type"
                        value="income"
                        class="hidden peer"
                        @checked(old('type', $type) === 'income')>

                    <div class="border rounded-lg p-4 peer-checked:border-green-600 peer-checked:bg-green-50">

                        <p class="font-semibold text-green-700">
                            Pemasukan
                        </p>

                        <p class="text-xs text-gray-500">
                            Kas masuk ke masjid
                        </p>

                    </div>

                </label>


                <label class="cursor-pointer">

                    <input
                        type="radio"
                        name="type"
                        value="expense"
                        class="hidden peer"
                        @checked(old('type', $type) === 'expense')>

                    <div class="border rounded-lg p-4 peer-checked:border-red-600 peer-checked:bg-red-50">

                        <p class="font-semibold text-red-700">
                            Pengeluaran
                        </p>

                        <p class="text-xs text-gray-500">
                            Kas keluar dari masjid
                        </p>

                    </div>

                </label>

            </div>

        </div>


        {{-- KATEGORI --}}

        <div>

            <label class="block font-medium mb-2">
                Kategori
            </label>

            <select
                name="category_id"
                id="category_id"
                required
                class="w-full rounded-lg border-gray-300">

                <option value="">
                    Pilih kategori
                </option>

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        data-type="{{ $category->type }}"
                        @selected(
                            old('category_id') == $category->id
                        )>

                        {{ $category->name }}

                    </option>

                @endforeach

            </select>

        </div>


        {{-- NOMINAL --}}

        <div>

            <label class="block font-medium mb-2">
                Nominal
            </label>

            <input
                type="number"
                name="amount"
                value="{{ old('amount') }}"
                min="1"
                required
                placeholder="Contoh: 500000"
                class="w-full rounded-lg border-gray-300">

        </div>


        {{-- TANGGAL --}}

        <div>

            <label class="block font-medium mb-2">
                Tanggal Transaksi
            </label>

            <input
                type="date"
                name="transaction_date"
                value="{{ old('transaction_date', date('Y-m-d')) }}"
                required
                class="w-full rounded-lg border-gray-300">

        </div>


        {{-- SUMBER --}}

        <div>

            <label class="block font-medium mb-2">
                Sumber / Penerima
            </label>

            <input
                type="text"
                name="source"
                value="{{ old('source') }}"
                placeholder="Contoh: Kotak Infaq / Toko Masjid"
                class="w-full rounded-lg border-gray-300">

        </div>


        {{-- KETERANGAN --}}

        <div>

            <label class="block font-medium mb-2">
                Keterangan
            </label>

            <textarea
                name="description"
                rows="4"
                placeholder="Keterangan transaksi..."
                class="w-full rounded-lg border-gray-300">{{ old('description') }}</textarea>

        </div>


        {{-- BUKTI --}}

        <div>

            <label class="block font-medium mb-2">
                Bukti Transaksi
            </label>

            <input
                type="file"
                name="evidence"
                accept=".jpg,.jpeg,.png,.pdf"
                class="w-full">

            <p class="text-xs text-gray-500 mt-1">
                JPG, PNG, PDF. Maksimal 2 MB.
            </p>

        </div>


        {{-- BUTTON --}}

        <div class="flex justify-end gap-3 pt-4 border-t">

            <a
                href="{{ route('transaksi.index') }}"
                class="px-5 py-2.5 bg-gray-200 rounded-lg">
                Batal
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 bg-slate-900 text-white rounded-lg hover:bg-slate-800">
                Simpan Transaksi
            </button>

        </div>

    </form>

</div>


@push('scripts')

<script>

    const typeInputs = document.querySelectorAll('input[name="type"]');
    const categorySelect = document.getElementById('category_id');

    function filterCategories() {

        const selectedType =
            document.querySelector(
                'input[name="type"]:checked'
            )?.value;

        Array.from(categorySelect.options).forEach(option => {

            if (!option.value) {
                return;
            }

            option.hidden =
                option.dataset.type !== selectedType;

        });

        const selectedOption =
            categorySelect.options[
                categorySelect.selectedIndex
            ];

        if (
            selectedOption &&
            selectedOption.dataset.type !== selectedType
        ) {
            categorySelect.value = '';
        }
    }

    typeInputs.forEach(input => {
        input.addEventListener(
            'change',
            filterCategories
        );
    });

    filterCategories();

</script>

@endpush

@endsection