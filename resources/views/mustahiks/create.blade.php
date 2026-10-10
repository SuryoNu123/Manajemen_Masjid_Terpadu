@extends('layouts.dashboard')

@section('title', 'Tambah Mustahik')
@section('page-title', 'Tambah Mustahik')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Tambah Mustahik
        </h1>

        <p class="text-gray-500 mt-1">
            Tambahkan data penerima penyaluran zakat.
        </p>

    </div>


    @if($errors->any())

        <div class="mb-6 bg-red-100 border border-red-200 text-red-700 p-4 rounded-lg">

            <ul class="list-disc ml-5">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('mustahik.store') }}"
        class="bg-white rounded-xl shadow-sm p-6 space-y-6">

        @csrf


        {{-- NAMA --}}

        <div>

            <label class="block font-medium mb-2">
                Nama Mustahik
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                placeholder="Nama penerima zakat"
                class="w-full rounded-lg border-gray-300">

        </div>


        {{-- ASN --}}

        <div>

            <label class="block font-medium mb-2">
                Kategori Asnaf
            </label>

            <select
                name="asnaf"
                required
                class="w-full rounded-lg border-gray-300">

                <option value="">
                    Pilih kategori asnaf
                </option>

                <option value="fakir"
                    @selected(old('asnaf') === 'fakir')>
                    Fakir
                </option>

                <option value="miskin"
                    @selected(old('asnaf') === 'miskin')>
                    Miskin
                </option>

                <option value="amil"
                    @selected(old('asnaf') === 'amil')>
                    Amil
                </option>

                <option value="muallaf"
                    @selected(old('asnaf') === 'muallaf')>
                    Muallaf
                </option>

                <option value="riqab"
                    @selected(old('asnaf') === 'riqab')>
                    Riqab
                </option>

                <option value="gharim"
                    @selected(old('asnaf') === 'gharim')>
                    Gharim
                </option>

                <option value="fisabilillah"
                    @selected(old('asnaf') === 'fisabilillah')>
                    Fisabilillah
                </option>

                <option value="ibnu_sabil"
                    @selected(old('asnaf') === 'ibnu_sabil')>
                    Ibnu Sabil
                </option>

            </select>

        </div>


        {{-- ALAMAT --}}

        <div>

            <label class="block font-medium mb-2">
                Alamat
            </label>

            <textarea
                name="address"
                rows="3"
                placeholder="Alamat mustahik..."
                class="w-full rounded-lg border-gray-300">{{ old('address') }}</textarea>

        </div>


        {{-- TELEPON --}}

        <div>

            <label class="block font-medium mb-2">
                Nomor HP
            </label>

            <input
                type="text"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="08xxxxxxxxxx"
                class="w-full rounded-lg border-gray-300">

        </div>


        {{-- NOMINAL --}}

        <div>

            <label class="block font-medium mb-2">
                Nominal Penyaluran
            </label>

            <div class="relative">

                <span class="absolute left-4 top-1/2 -translate-y-1/2">
                    Rp
                </span>

                <input
                    type="number"
                    name="amount"
                    value="{{ old('amount') }}"
                    min="0"
                    required
                    class="w-full rounded-lg border-gray-300 pl-12">

            </div>

        </div>


        {{-- STATUS --}}

        <div>

            <label class="block font-medium mb-2">
                Status Penyaluran
            </label>

            <select
                name="status"
                id="status"
                required
                class="w-full rounded-lg border-gray-300">

                <option value="pending"
                    @selected(old('status', 'pending') === 'pending')>
                    Pending
                </option>

                <option value="distributed"
                    @selected(old('status') === 'distributed')>
                    Sudah Disalurkan
                </option>

            </select>

        </div>


        {{-- TANGGAL --}}

        <div id="distributedDate">

            <label class="block font-medium mb-2">
                Tanggal Penyaluran
            </label>

            <input
                type="date"
                name="distributed_at"
                value="{{ old('distributed_at') }}"
                class="w-full rounded-lg border-gray-300">

            <p class="text-xs text-gray-500 mt-1">
                Isi apabila zakat sudah disalurkan.
            </p>

        </div>


        {{-- CATATAN --}}

        <div>

            <label class="block font-medium mb-2">
                Catatan
            </label>

            <textarea
                name="notes"
                rows="3"
                placeholder="Catatan tambahan..."
                class="w-full rounded-lg border-gray-300">{{ old('notes') }}</textarea>

        </div>


        {{-- BUTTON --}}

        <div class="flex justify-end gap-3 pt-4 border-t">

            <a
                href="{{ route('mustahik.index') }}"
                class="px-5 py-2.5 bg-gray-200 rounded-lg">
                Batal
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 bg-slate-900 text-white rounded-lg">
                Simpan
            </button>

        </div>

    </form>

</div>


@push('scripts')

<script>

    const statusSelect =
        document.getElementById('status');

    const distributedDate =
        document.getElementById('distributedDate');

    function toggleDistributedDate() {

        if (statusSelect.value === 'distributed') {

            distributedDate.style.display = 'block';

        } else {

            distributedDate.style.display = 'none';

        }

    }

    statusSelect.addEventListener(
        'change',
        toggleDistributedDate
    );

    toggleDistributedDate();

</script>

@endpush

@endsection