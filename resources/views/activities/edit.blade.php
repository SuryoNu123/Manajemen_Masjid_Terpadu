@extends('layouts.dashboard')

@section('title', 'Edit Kegiatan')

@section('content')

<div class="max-w-3xl">

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-900">
            Edit Kegiatan
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Perbarui informasi kegiatan ibadah.
        </p>

    </div>

    @if($errors->any())

        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3">

            <ul class="text-sm text-red-600 list-disc list-inside">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('activities.update', $activity) }}"
          method="POST"
          class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5">

        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Nama Kegiatan
            </label>

            <input type="text"
                   name="title"
                   value="{{ old('title', $activity->title) }}"
                   class="w-full rounded-xl border-slate-300"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Jenis Kegiatan
            </label>

            <select name="type"
                    class="w-full rounded-xl border-slate-300"
                    required>

                <option value="kajian" @selected(old('type', $activity->type) == 'kajian')}>
                    Kajian
                </option>

                <option value="khutbah_jumat" @selected(old('type', $activity->type) == 'khutbah_jumat')}>
                    Khutbah Jumat
                </option>

                <option value="pengajian" @selected(old('type', $activity->type) == 'pengajian')}>
                    Pengajian
                </option>

                <option value="kegiatan_lain" @selected(old('type', $activity->type) == 'kegiatan_lain')}>
                    Kegiatan Lain
                </option>

            </select>
        </div>

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Deskripsi
            </label>

            <textarea name="description"
                      rows="4"
                      class="w-full rounded-xl border-slate-300">{{ old('description', $activity->description) }}</textarea>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Tanggal
                </label>

                <input type="date"
                       name="activity_date"
                       value="{{ old('activity_date', $activity->activity_date?->format('Y-m-d')) }}"
                       class="w-full rounded-xl border-slate-300"
                       required>

            </div>

            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Waktu Mulai
                </label>

                <input type="time"
                       name="start_time"
                       value="{{ old('start_time', $activity->start_time) }}"
                       class="w-full rounded-xl border-slate-300"
                       required>

            </div>

        </div>

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Waktu Selesai
            </label>

            <input type="time"
                   name="end_time"
                   value="{{ old('end_time', $activity->end_time) }}"
                   class="w-full rounded-xl border-slate-300">

        </div>

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Lokasi
            </label>

            <input type="text"
                   name="location"
                   value="{{ old('location', $activity->location) }}"
                   class="w-full rounded-xl border-slate-300">

        </div>

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Pembicara / Khatib
            </label>

            <input type="text"
                   name="speaker"
                   value="{{ old('speaker', $activity->speaker) }}"
                   class="w-full rounded-xl border-slate-300">

        </div>

        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Status
            </label>

            <select name="status"
                    class="w-full rounded-xl border-slate-300"
                    required>

                <option value="scheduled" @selected(old('status', $activity->status) == 'scheduled')}>
                    Terjadwal
                </option>

                <option value="ongoing" @selected(old('status', $activity->status) == 'ongoing')}>
                    Berlangsung
                </option>

                <option value="completed" @selected(old('status', $activity->status) == 'completed')}>
                    Selesai
                </option>

                <option value="cancelled" @selected(old('status', $activity->status) == 'cancelled')}>
                    Dibatalkan
                </option>

            </select>

        </div>

        <label class="flex items-center gap-3">

            <input type="checkbox"
                   name="is_public"
                   value="1"
                   @checked(old('is_public', $activity->is_public))
                   class="rounded border-slate-300">

            <span class="text-sm text-slate-700">
                Tampilkan di halaman publik
            </span>

        </label>

        <div class="flex justify-end gap-3 pt-4">

            <a href="{{ route('activities.index') }}"
               class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700">
                Batal
            </a>

            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-slate-900 text-white">
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection