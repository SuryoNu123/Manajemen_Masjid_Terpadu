<x-guest-layout>
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-2xl bg-blue-600 flex items-center justify-center shadow-lg shadow-emerald-100">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-9 h-9 text-white"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-2xl font-bold text-slate-800">
                Verifikasi Email
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Satu langkah lagi untuk mengaktifkan akun Anda.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">

            <p class="text-sm leading-6 text-slate-600 text-center">
                Terima kasih telah mendaftar.
                Silakan buka email Anda dan klik link verifikasi
                yang telah kami kirimkan.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mt-5 rounded-xl bg-green-50 border border-green-200 p-4">
                    <p class="text-sm text-green-700 text-center">
                        Link verifikasi baru telah dikirim ke alamat email Anda.
                    </p>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('verification.send') }}"
                  class="mt-6">
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-100 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition"
                >
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="text-sm font-medium text-slate-500 hover:text-slate-700"
                    >
                        Keluar dari akun
                    </button>
                </form>

            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            © {{ date('Y') }} Sistem Manajemen Masjid Terpadu
        </p>
    </div>
</x-guest-layout>