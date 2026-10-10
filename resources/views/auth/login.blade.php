<x-guest-layout>

    <div
        style="
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
        "
    >

        {{-- Header --}}
        <div
            style="
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
                Sistem Manajemen Masjid
            </h1>

            <p
                style="
                    margin: 8px 0 0;
                    color: #64748b;
                    font-size: 15px;
                    line-height: 22px;
                "
            >
                Masuk ke akun Anda untuk melanjutkan
            </p>

        </div>


        {{-- Card --}}
        <div
            style="
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
                action="{{ route('login') }}"
            >
                @csrf


                {{-- Email --}}
                <div>

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
                        autocomplete="username"
                        placeholder="Masukkan email Anda"
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


                {{-- Password --}}
                <div style="margin-top: 18px;">

                    <div
                        style="
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin-bottom: 8px;
                        "
                    >

                        <label
                            for="password"
                            style="
                                color: #1e293b;
                                font-size: 14px;
                                font-weight: 600;
                            "
                        >
                            Password
                        </label>

                        @if (Route::has('password.request'))

                            <a
                                href="{{ route('password.request') }}"
                                style="
                                    color: #0f172b;
                                    font-size: 13px;
                                    font-weight: 600;
                                    text-decoration: none;
                                "
                            >
                                Lupa password?
                            </a>

                        @endif

                    </div>


                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password Anda"
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
                        :messages="$errors->get('password')"
                        class="mt-2"
                    />

                </div>


                {{-- Remember Me --}}
                <div style="margin-top: 18px;">

                    <label
                        for="remember_me"
                        style="
                            display: flex;
                            align-items: center;
                            cursor: pointer;
                        "
                    >

                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            style="
                                width: 16px;
                                height: 16px;
                                margin: 0;
                                accent-color: #0f172b;
                            "
                        >

                        <span
                            style="
                                margin-left: 8px;
                                color: #475569;
                                font-size: 14px;
                            "
                        >
                            Ingat saya
                        </span>

                    </label>

                </div>


                {{-- Login Button --}}
                <button
                    type="submit"
                    style="
                        display: block;
                        width: 100%;
                        height: 46px;
                        margin-top: 22px;
                        border: none;
                        border-radius: 8px;
                        background: #0f172b;
                        color: #ffffff;
                        font-size: 14px;
                        font-weight: 600;
                        cursor: pointer;
                    "
                >
                    Masuk
                </button>

            </form>


            {{-- Register --}}
            @if (Route::has('register'))

                <div
                    style="
                        margin-top: 22px;
                        padding-top: 20px;
                        border-top: 1px solid #e2e8f0;
                        text-align: center;
                    "
                >

                    <span
                        style="
                            color: #64748b;
                            font-size: 14px;
                        "
                    >
                        Belum memiliki akun?
                    </span>

                    <a
                        href="{{ route('register') }}"
                        style="
                            color: #0f172b;
                            font-size: 14px;
                            font-weight: 600;
                            text-decoration: none;
                            margin-left: 4px;
                        "
                    >
                        Daftar sekarang
                    </a>

                </div>

            @endif

        </div>


        {{-- Footer --}}
        <p
            style="
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