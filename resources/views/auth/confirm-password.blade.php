<x-guest-layout>
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-2xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-9 h-9 text-white"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm2-7V7a4 4 0 118 0v5"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-2xl font-bold text-slate-800">
                Konfirmasi Password
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Silakan konfirmasi password Anda sebelum melanjutkan.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div>
                    <label for="password"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        autofocus
                        placeholder="Masukkan password Anda"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:bg-white transition"
                    >

                    <x-input-error
                        :messages="$errors->get('password')"
                        class="mt-2"
                    />
                </div>

                <button
                    type="submit"
                    class="w-full mt-6 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                >
                    Konfirmasi
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            © {{ date('Y') }} Sistem Manajemen Masjid Terpadu
        </p>
    </div>
</x-guest-layout>