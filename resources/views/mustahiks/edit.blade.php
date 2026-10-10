@extends('layouts.dashboard')

@section('title', 'Edit Mustahik')
@section('page-title', 'Edit Mustahik')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Edit Mustahik
        </h1>

        <p class="text-gray-500 mt-1">
            Perbarui data penerima zakat.
        </p>

    </div>


    @if($errors->any())

        <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-lg">

            <ul class="list-disc ml-5">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('mustahik.update', $mustahik) }}"
        class="bg-white rounded-xl shadow-sm p-6 space-y-6">

        @csrf
        @method('PUT')


        <div>

            <label class="block font-medium mb-2">
                Nama Mustahik
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $mustahik->name) }}"
                required
                class="w-full rounded-lg border-gray-300">

        </div>


        <div>

            <label class="block font-medium mb-2">
                Kategori Asnaf
            </label>

            <select
                name="asnaf"
                required
                class="w-full rounded-lg border-gray-300">

                @foreach([
                    'fakir' => 'Fakir',
                    'miskin' => 'Miskin',
                    'amil' => 'Amil',
                    'muallaf' => 'Muallaf',
                    'riqab' => 'Riqab',
                    'gharim' => 'Gharim',
                    'fisabilillah' => 'Fisabilillah',
                    'ibnu_sabil' => 'Ibnu Sabil',
                ] as $value => $label)

                    <option
                        value="{{ $value }}"
                        @selected(
                            old(
                                'asnaf',
                                $mustahik->asnaf
                            ) === $value
                        )>
                        {{ $label }}
                    </option>

                @endforeach

            </select>

        </div>


        <div>

            <label class="block font-medium mb-2">
                Alamat
            </label>

            <textarea
                name="address"
                rows="3"
                class="w-full rounded-lg border-gray-300">{{ old('address', $mustahik->address) }}</textarea>

        </div>


        <div>

            <label class="block font-medium mb-2">
                Nomor HP
            </label>

            <input
                type="text"
                name="phone"
                value="{{ old('phone', $mustahik->phone) }}"
                class="w-full rounded-lg border-gray-300">

        </div>


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
                    min="0"
                    required
                    value="{{ old('amount', $mustahik->amount) }}"
                    class="w-full rounded-lg border-gray-300 pl-12">

            </div>

        </div>


        <div>

            <label class="block font-medium mb-2">
                Status Penyaluran
            </label>

            <select
                name="status"
                id="status"
                required
                class="w-full rounded-lg border-gray-300">

                <option
                    value="pending"
                    @selected(
                        old(
                            'status',
                            $mustahik->status
                        ) === 'pending'
                    )>
                    Pending
                </option>

                <option
                    value="distributed"
                    @selected(
                        old(
                            'status',
                            $mustahik->status
                        ) === 'distributed'
                    )>
                    Sudah Disalurkan
                </option>

            </select>

        </div>


        <div id="distributedDate">

            <label class="block font-medium mb-2">
                Tanggal Penyaluran
            </label>

            <input
                type="date"
                name="distributed_at"
                value="{{ old(
                    'distributed_at',
                    optional($mustahik->distributed_at)
                        ? $mustahik->distributed_at->format('Y-m-d')
                        : ''
                ) }}"
                class="w-full rounded-lg border-gray-300">

        </div>


        <div>

            <label class="block font-medium mb-2">
                Catatan
            </label>

            <textarea
                name="notes"
                rows="3"
                class="w-full rounded-lg border-gray-300">{{ old('notes', $mustahik->notes) }}</textarea>

        </div>


        <div class="flex justify-end gap-3 pt-4 border-t">

            <a
                href="{{ route('mustahik.index') }}"
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

    const statusSelect =
        document.getElementById('status');

    const distributedDate =
        document.getElementById('distributedDate');

    function toggleDistributedDate() {

        distributedDate.style.display =
            statusSelect.value === 'distributed'
                ? 'block'
                : 'none';

    }

    statusSelect.addEventListener(
        'change',
        toggleDistributedDate
    );

    toggleDistributedDate();

</script>

@endpush

@endsection