<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon/favicon.ico') }}">
    <title>Lupa Password — Quro Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .input-dark {
            width: 100%;
            background: #18181b;
            border: 1px solid #3f3f46;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #fff;
            outline: none;
            transition: border-color 0.2s;
        }
        .input-dark::placeholder { color: #52525b; }
        .input-dark:focus { border-color: #71717a; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
        .delay-1 { animation-delay: 0.05s; opacity: 0; }
        .delay-2 { animation-delay: 0.10s; opacity: 0; }
        .delay-3 { animation-delay: 0.15s; opacity: 0; }
        .delay-4 { animation-delay: 0.20s; opacity: 0; }
    </style>
</head>
<body class="antialiased" style="font-family: 'Inter', sans-serif; background: #09090b;">

<div class="min-h-screen flex">

    {{-- Kiri — Branding --}}
    <div class="hidden lg:flex lg:w-[52%] relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('images/produk.jpeg') }}');"></div>
        <div class="absolute inset-0"
            style="background: linear-gradient(to right, rgba(9,9,11,0.75) 0%, rgba(9,9,11,0.3) 60%, rgba(9,9,11,0.0) 100%);"></div>
        <div class="absolute inset-0"
            style="background: linear-gradient(to top, rgba(9,9,11,0.8) 0%, transparent 50%);"></div>

        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            <a href="{{ route('home') }}" class="flex items-center gap-3 w-fit group">
                <img src="{{ asset('images/logo.png') }}" alt="Quro Collection"
                    class="h-9 w-auto brightness-0 invert opacity-90 group-hover:opacity-100 transition">
                <span style="font-family: 'Playfair Display', serif;"
                    class="text-white text-lg font-semibold tracking-wide">Quro Collection</span>
            </a>
            <div>
                <p class="text-xs tracking-[4px] uppercase text-white/40 mb-4">Keamanan Akun</p>
                <h2 style="font-family: 'Playfair Display', serif;"
                    class="text-white text-4xl font-semibold leading-snug mb-4">
                    Akun Kamu<br>Tetap Aman.
                </h2>
                <p class="text-white/50 text-sm leading-relaxed max-w-xs">
                    Kami akan mengirimkan kode OTP ke email kamu untuk memverifikasi identitasmu sebelum reset password.
                </p>
            </div>
        </div>
    </div>

    {{-- Kanan — Form --}}
    <div class="w-full lg:w-[48%] flex items-center justify-center px-6 py-12"
        style="background: #09090b;">

        <div class="w-full max-w-sm">

            {{-- Mobile Logo --}}
            <div class="flex justify-center mb-10 lg:hidden fade-up">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="Quro Collection"
                        class="h-9 w-auto brightness-0 invert opacity-90">
                    <span style="font-family: 'Playfair Display', serif;"
                        class="text-white text-lg font-semibold tracking-wide">Quro Collection</span>
                </a>
            </div>

            {{-- Heading --}}
            <div class="mb-8 fade-up delay-1">
                <p class="text-xs tracking-[3px] uppercase text-zinc-600 mb-3">Reset Password</p>
                <h1 style="font-family: 'Playfair Display', serif;"
                    class="text-3xl font-semibold text-white mb-1.5">Lupa Password?</h1>
                <p class="text-sm text-zinc-500">Masukkan email kamu dan kami kirimkan kode OTP untuk reset password.</p>
            </div>

            {{-- Status --}}
            @if(session('status'))
                <div class="bg-zinc-900 border border-zinc-700 text-zinc-300 px-4 py-3 rounded-xl mb-6 text-sm fade-up delay-1">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div class="fade-up delay-2">
                    <label class="block text-xs font-medium text-zinc-400 mb-1.5 tracking-wide uppercase">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="email@example.com"
                        class="input-dark">
                    @error('email')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="fade-up delay-3 pt-1">
                    <button type="submit"
                        class="w-full bg-white text-gray-950 py-3.5 rounded-xl text-sm font-medium hover:bg-zinc-100 transition">
                        Kirim Kode OTP
                    </button>
                </div>

                <div class="fade-up delay-4">
                    <a href="{{ route('login') }}"
                        class="flex items-center justify-center gap-2 w-full text-zinc-500 text-sm hover:text-zinc-300 transition py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke halaman masuk
                    </a>
                </div>

            </form>

            <p class="text-center text-xs text-zinc-800 mt-10">
                © {{ date('Y') }} Quro Collection
            </p>
        </div>
    </div>

</div>

</body>
</html>
