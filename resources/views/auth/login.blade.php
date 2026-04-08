<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">
    <title>Masuk — Quro Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }

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
        .delay-5 { animation-delay: 0.25s; opacity: 0; }
        .delay-6 { animation-delay: 0.30s; opacity: 0; }
    </style>
</head>
<body class="antialiased" style="font-family: 'Inter', sans-serif; background: #09090b;">

<div class="min-h-screen flex">

    {{-- Kiri — Branding Panel --}}
    <div class="hidden lg:flex lg:w-[52%] relative overflow-hidden">

        {{-- Background image --}}
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('images/produk.jpeg') }}');"></div>

        {{-- Gradient overlay --}}
        <div class="absolute inset-0"
            style="background: linear-gradient(to right, rgba(9,9,11,0.75) 0%, rgba(9,9,11,0.3) 60%, rgba(9,9,11,0.0) 100%);"></div>
        <div class="absolute inset-0"
            style="background: linear-gradient(to top, rgba(9,9,11,0.8) 0%, transparent 50%);"></div>

        <div class="relative z-10 flex flex-col justify-between p-12 w-full">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 w-fit group">
                <img src="{{ asset('images/logo.png') }}" alt="Quro Collection"
                    class="h-9 w-auto brightness-0 invert opacity-90 group-hover:opacity-100 transition">
                <span style="font-family: 'Playfair Display', serif;"
                    class="text-white text-lg font-semibold tracking-wide">
                    Quro Collection
                </span>
            </a>

            {{-- Quote --}}
            <div>
                <p class="text-xs tracking-[4px] uppercase text-white/40 mb-4">Premium Muslim Fashion</p>
                <h2 style="font-family: 'Playfair Display', serif;"
                    class="text-white text-4xl font-semibold leading-snug mb-4">
                    Elegansi Muslim<br>untuk Setiap Momen.
                </h2>
                <p class="text-white/50 text-sm leading-relaxed max-w-xs">
                    Koleksi baju koko premium dengan desain modern dan bahan berkualitas tinggi.
                </p>

                {{-- Divider --}}
                <div class="w-10 h-px bg-white/20 mt-8 mb-6"></div>

                {{-- Mini stats --}}
                <div class="flex items-center gap-6">
                    <div>
                        <p style="font-family: 'Playfair Display', serif;" class="text-white text-xl font-semibold">500+</p>
                        <p class="text-white/40 text-xs mt-0.5">Produk Tersedia</p>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                    <div>
                        <p style="font-family: 'Playfair Display', serif;" class="text-white text-xl font-semibold">1000+</p>
                        <p class="text-white/40 text-xs mt-0.5">Pelanggan Puas</p>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                    <div>
                        <p style="font-family: 'Playfair Display', serif;" class="text-white text-xl font-semibold">4.9★</p>
                        <p class="text-white/40 text-xs mt-0.5">Rating</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Kanan — Form Panel --}}
    <div class="w-full lg:w-[48%] flex items-center justify-center px-6 py-12"
        style="background: #09090b;">

        <div class="w-full max-w-sm">

            {{-- Mobile Logo --}}
            <div class="flex justify-center mb-10 lg:hidden fade-up">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="Quro Collection"
                        class="h-9 w-auto brightness-0 invert opacity-90">
                    <span style="font-family: 'Playfair Display', serif;"
                        class="text-white text-lg font-semibold tracking-wide">
                        Quro Collection
                    </span>
                </a>
            </div>

            {{-- Heading --}}
            <div class="mb-8 fade-up delay-1">
                <p class="text-xs tracking-[3px] uppercase text-zinc-600 mb-3">Akun Saya</p>
                <h1 style="font-family: 'Playfair Display', serif;"
                    class="text-3xl font-semibold text-white mb-1.5">Selamat Datang</h1>
                <p class="text-sm text-zinc-500">Masuk ke akun Quro Collection kamu</p>
            </div>

            {{-- Session Status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4"
                x-data="{ showPass: false }">
                @csrf

                {{-- Email --}}
                <div class="fade-up delay-2">
                    <label class="block text-xs font-medium text-zinc-400 mb-1.5 tracking-wide uppercase">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="email@example.com"
                        class="input-dark">
                    @error('email')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div class="fade-up delay-3">
                    <label class="block text-xs font-medium text-zinc-400 mb-1.5 tracking-wide uppercase">Password</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" required
                            placeholder="••••••••"
                            class="input-dark pr-11">
                        <button type="button" @click="showPass = !showPass"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition">
                            <svg x-show="!showPass" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPass" x-cloak class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between fade-up delay-4">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-zinc-700 bg-zinc-900 text-white focus:ring-0 focus:ring-offset-0">
                        <span class="text-xs text-zinc-500">Ingat saya</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs text-zinc-500 hover:text-white transition">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <div class="fade-up delay-5 pt-1">
                    <button type="submit"
                        class="w-full bg-white text-gray-950 py-3.5 rounded-xl text-sm font-medium hover:bg-zinc-100 transition">
                        Masuk
                    </button>
                </div>

                {{-- Divider --}}
                <div class="relative fade-up delay-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-zinc-800"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-3 text-xs text-zinc-600 bg-[#09090b]">atau masuk dengan</span>
                    </div>
                </div>

                {{-- Google --}}
                <div class="fade-up delay-6">
                    <a href="{{ route('auth.google') }}"
                        class="flex items-center justify-center gap-3 w-full border border-zinc-800 py-3 rounded-xl text-sm text-zinc-400 hover:border-zinc-600 hover:text-white transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" class="shrink-0">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Masuk dengan Google
                    </a>
                </div>

                {{-- Register link --}}
                <div class="fade-up delay-6">
                    <a href="{{ route('register') }}"
                        class="block w-full text-center border border-zinc-800 text-zinc-400 py-3.5 rounded-xl text-sm hover:border-zinc-600 hover:text-white transition">
                        Belum punya akun? <span class="font-medium text-zinc-300">Daftar sekarang</span>
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
