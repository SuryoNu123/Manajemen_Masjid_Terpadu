<x-guest-layout>

    <div
        style="
            display: block;
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
        "
    >

        {{-- Header --}}
        <div
            style="
                display: block;
                width: 100%;
                text-align: center;
                margin-bottom: 24px;
            "
        >

            <h1
                style="
                    margin: 0;
                    color: #0f172b;
                    font-size: 28px;
                    line-height: 36px;
                    font-weight: 700;
                "
            >
                Lupa Password?
            </h1>

            <p
                style="
                    margin: 8px 0 0;
                    color: #64748b;
                    font-size: 15px;
                    line-height: 22px;
                "
            >
                Jangan khawatir, kami akan membantu Anda mengatur ulang password.
            </p>

        </div>


        {{-- Card --}}
        <div
            style="
                display: block;
                width: 100%;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                padding: 28px;
                box-sizing: border-box;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            "
        >

            {{-- Session Status --}}
            <x-auth-session-status
                class="mb-4"
                :status="session('status')"
            />


            <form
                method="POST"
                action="{{ route('password.email') }}"
            >
                @csrf


                {{-- Email --}}
                <div
                    style="
                        display: block;
                        width: 100%;
                    "
                >

                    <label
                        for="email"
                        style="
                            display: block;
                            margin-bottom: 8px;
                            color: #1e293b;
                            font-size: 14px;
                            font-weight: 600;
                        "
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="Masukkan email yang terdaftar"
                        style="
                            display: block;
                            width: 100%;
                            height: 46px;
                            padding: 0 14px;
                            box-sizing: border-box;
                            border: 1px solid #cbd5e1;
                            border-radius: 8px;
                            background: #ffffff;
                            color: #0f172a;
                            font-size: 14px;
                            outline: none;
                        "
                    >

                    <x-input-error
                        :messages="$errors->get('email')"
                        class="mt-2"
                    />

                </div>


                {{-- Button --}}
                <button
                    type="submit"
                    style="
                        display: block;
                        width: 100%;
                        height: 46px;
                        margin-top: 22px;
                        padding: 0;
                        border: none;
                        border-radius: 8px;
                        background: #0f172b;
                        color: #ffffff;
                        font-size: 14px;
                        font-weight: 600;
                        cursor: pointer;
                    "
                >
                    Kirim Link Reset Password
                </button>

            </form>


            {{-- Back to Login --}}
            <div
                style="
                    display: block;
                    width: 100%;
                    margin-top: 22px;
                    padding-top: 20px;
                    border-top: 1px solid #e2e8f0;
                    text-align: center;
                "
            >

                <a
                    href="{{ route('login') }}"
                    style="
                        color: #0f172b;
                        font-size: 14px;
                        font-weight: 600;
                        text-decoration: none;
                    "
                >
                    ← Kembali ke halaman login
                </a>

            </div>

        </div>


        {{-- Footer --}}
        <p
            style="
                display: block;
                width: 100%;
                margin: 18px 0 0;
                text-align: center;
                color: #94a3b8;
                font-size: 12px;
            "
        >
            © {{ date('Y') }} Sistem Manajemen Masjid Terpadu
        </p>

    </div>

</x-guest-layout>