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
    <title>Buat Password Baru — Quro Collection</title>
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
                    Hampir Selesai.<br>Buat Password Baru.
                </h2>
                <p class="text-white/50 text-sm leading-relaxed max-w-xs">
                    Identitasmu sudah terverifikasi. Buat password baru yang kuat untuk mengamankan akunmu.
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
                    class="text-3xl font-semibold text-white mb-1.5">Password Baru</h1>
                <p class="text-sm text-zinc-500">Buat password baru yang kuat untuk akunmu.</p>
            </div>

            {{-- Success --}}
            @if(session('success'))
                <div class="bg-zinc-900 border border-zinc-700 text-zinc-300 px-4 py-3 rounded-xl mb-6 text-sm fade-up delay-1">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4"
                x-data="{ showPass: false, showConfirm: false }">
                @csrf

                {{-- Password --}}
                <div class="fade-up delay-2">
                    <label class="block text-xs font-medium text-zinc-400 mb-1.5 tracking-wide uppercase">Password Baru</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" required autofocus
                            placeholder="Min. 8 karakter"
                            class="input-dark pr-11">
                        <button type="button" @click="showPass = !showPass"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition">
                            <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="fade-up delay-3">
                    <label class="block text-xs font-medium text-zinc-400 mb-1.5 tracking-wide uppercase">Konfirmasi Password</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                            placeholder="Ulangi password baru"
                            class="input-dark pr-11">
                        <button type="button" @click="showConfirm = !showConfirm"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition">
                            <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showConfirm" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Submit --}}
                <div class="fade-up delay-4 pt-1">
                    <button type="submit"
                        class="w-full bg-white text-gray-950 py-3.5 rounded-xl text-sm font-medium hover:bg-zinc-100 transition">
                        Simpan Password Baru
                    </button>
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
