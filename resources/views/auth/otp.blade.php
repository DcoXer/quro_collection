<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo/favicon-16x16.png') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('images/logo/favicon.ico') }}">
        <link rel="manifest" href="{{ asset('images/logo/site.webmanifest') }}">
        <title>Verifikasi OTP — Quro Collection</title>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/pages/otp.js'])
    </head>
    <body class="bg-gray-50 antialiased" style="font-family: 'Inter', sans-serif;">

        <div class="min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-sm">

                {{-- Logo --}}
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-flex justify-center mb-6">
                        <img src="{{ asset('images/logo.png') }}" alt="Quro Collection" class="h-12 w-auto">
                    </a>
                    <h1 style="font-family: 'Playfair Display', serif;"
                        class="text-2xl font-semibold text-gray-900 mb-1">
                        Verifikasi Email
                    </h1>
                    <p class="text-sm text-gray-400">
                        Kode OTP telah dikirim ke email kamu.<br>
                        Berlaku selama <span class="font-medium text-gray-600" id="countdown" data-seconds="{{ $secondsLeft }}">5:00</span>
                    </p>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('otp.verify') }}">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-xs font-medium text-gray-500 mb-3 uppercase tracking-wider text-center">
                            Masukkan 6 Digit Kode OTP
                        </label>

                        {{-- OTP Input Boxes --}}
                        <div class="flex gap-3 justify-center" id="otp-inputs">
                            @for($i = 0; $i < 6; $i++)
                                <input type="text"
                                    maxlength="1"
                                    class="otp-box w-12 h-14 text-center text-xl font-semibold border border-gray-200 rounded-xl focus:outline-none focus:border-gray-900 transition bg-white"
                                    inputmode="numeric"
                                    pattern="[0-9]">
                            @endfor
                        </div>

                        <input type="hidden" name="otp" id="otp-hidden">

                        @error('otp')
                            <p class="text-red-500 text-xs mt-3 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gray-900 text-white py-3.5 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                        Verifikasi
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="text-sm text-gray-400">Tidak menerima kode?</p>
                    <form method="POST" action="{{ route('otp.resend') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-sm text-gray-900 font-medium hover:underline mt-1">
                            Kirim Ulang Kode
                        </button>
                    </form>
                </div>

                <p class="text-center text-xs text-gray-300 mt-6">
                    © {{ date('Y') }} Quro Collection
                </p>
            </div>
        </div>


    </body>
</html>