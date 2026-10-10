<x-guest-layout>

    {{-- Header --}}
    <div
        style="
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
            Buat Akun
        </h1>

        <p
            style="
                margin: 8px 0 0;
                color: #64748b;
                font-size: 15px;
                line-height: 22px;
            "
        >
            Daftarkan akun Anda ke sistem
        </p>

    </div>


    {{-- Register Card --}}
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

        <form method="POST" action="{{ route('register') }}">
            @csrf


            {{-- Nama Lengkap --}}
            <div>

                <label
                    for="name"
                    style="
                        display: block;
                        margin-bottom: 8px;
                        color: #1e293b;
                        font-size: 14px;
                        font-weight: 600;
                    "
                >
                    Nama Lengkap
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Masukkan nama lengkap"
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
                    :messages="$errors->get('name')"
                    class="mt-2"
                />

            </div>


            {{-- Email --}}
            <div style="margin-top: 18px;">

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
                    autocomplete="username"
                    placeholder="Masukkan email"
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

                <label
                    for="password"
                    style="
                        display: block;
                        margin-bottom: 8px;
                        color: #1e293b;
                        font-size: 14px;
                        font-weight: 600;
                    "
                >
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Masukkan password"
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


            {{-- Konfirmasi Password --}}
            <div style="margin-top: 18px;">

                <label
                    for="password_confirmation"
                    style="
                        display: block;
                        margin-bottom: 8px;
                        color: #1e293b;
                        font-size: 14px;
                        font-weight: 600;
                    "
                >
                    Konfirmasi Password
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi password"
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
                    :messages="$errors->get('password_confirmation')"
                    class="mt-2"
                />

            </div>


            {{-- Register Button --}}
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
                Daftar
            </button>

        </form>


        {{-- Login --}}
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
                Sudah memiliki akun?
            </span>

            <a
                href="{{ route('login') }}"
                style="
                    color: #0f172b;
                    font-size: 14px;
                    font-weight: 600;
                    text-decoration: none;
                    margin-left: 4px;
                "
            >
                Masuk
            </a>

        </div>

    </div>

</x-guest-layout>