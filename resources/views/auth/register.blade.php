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
    <title>Daftar — Quro Collection</title>
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
        .delay-7 { animation-delay: 0.35s; opacity: 0; }
    </style>
</head>
<body class="antialiased" style="font-family: 'Inter', sans-serif; background: #09090b;">

<div class="min-h-screen flex">

    {{-- Kiri — Branding Panel --}}
    <div class="hidden lg:flex lg:w-[52%] relative overflow-hidden">

        {{-- Background image --}}
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('images/produk1.JPEG') }}');"></div>

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
                    Bergabung dengan<br>Komunitas Kami.
                </h2>
                <p class="text-white/50 text-sm leading-relaxed max-w-xs">
                    Dapatkan akses ke koleksi terbaru dan penawaran eksklusif hanya untuk member.
                </p>

                {{-- Divider --}}
                <div class="w-10 h-px bg-white/20 mt-8 mb-6"></div>

                {{-- Benefit list --}}
                <ul class="space-y-3">
                    @foreach([
                        'Akses koleksi terbaru lebih awal',
                        'Notifikasi pesanan real-time',
                        'Wishlist & riwayat pembelian',
                    ] as $benefit)
                    <li class="flex items-center gap-3 text-sm text-white/50">
                        <span class="w-1.5 h-1.5 rounded-full bg-white/30 shrink-0"></span>
                        {{ $benefit }}
                    </li>
                    @endforeach
                </ul>
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
                <p class="text-xs tracking-[3px] uppercase text-zinc-600 mb-3">Bergabung</p>
                <h1 style="font-family: 'Playfair Display', serif;"
                    class="text-3xl font-semibold text-white mb-1.5">Buat Akun</h1>
                <p class="text-sm text-zinc-500">Daftar dan mulai berbelanja sekarang</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4"
                x-data="{ showPass: false, showConfirm: false, agreed: false }">
                @csrf

                {{-- Nama --}}
                <div class="fade-up delay-2">
                    <label class="block text-xs font-medium text-zinc-400 mb-1.5 tracking-wide uppercase">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                        placeholder="Nama kamu"
                        class="input-dark">
                    @error('name')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div class="fade-up delay-3">
                    <label class="block text-xs font-medium text-zinc-400 mb-1.5 tracking-wide uppercase">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="email@example.com"
                        class="input-dark">
                    @error('email')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div class="fade-up delay-4">
                    <label class="block text-xs font-medium text-zinc-400 mb-1.5 tracking-wide uppercase">Password</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" required
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
                <div class="fade-up delay-5">
                    <label class="block text-xs font-medium text-zinc-400 mb-1.5 tracking-wide uppercase">Konfirmasi Password</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                            placeholder="Ulangi password"
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

                {{-- Privacy Agreement --}}
                <div class="fade-up delay-6">
                    <label class="flex items-start gap-3 cursor-pointer select-none">
                        <div class="relative mt-0.5 shrink-0">
                            <input type="checkbox" x-model="agreed" class="sr-only peer">
                            <div class="w-5 h-5 rounded-md border-2 border-zinc-700 peer-checked:border-white peer-checked:bg-white transition flex items-center justify-center">
                                <svg x-show="agreed" x-cloak class="w-3 h-3 text-gray-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <span class="text-sm text-zinc-500 leading-snug">
                            Saya telah membaca dan menyetujui
                            <a href="{{ route('privacy') }}" target="_blank"
                                class="text-zinc-300 font-medium underline underline-offset-2 hover:text-white transition">
                                Kebijakan Privasi
                            </a>
                            Quro Collection.
                        </span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="fade-up delay-7 pt-1">
                    <button type="submit" :disabled="!agreed"
                        :class="agreed
                            ? 'bg-white text-gray-950 hover:bg-zinc-100 cursor-pointer'
                            : 'bg-zinc-800 text-zinc-600 cursor-not-allowed'"
                        class="w-full py-3.5 rounded-xl text-sm font-medium transition">
                        Daftar Sekarang
                    </button>
                </div>

                {{-- Divider --}}
                <div class="relative fade-up delay-7">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-zinc-800"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-3 text-xs text-zinc-600 bg-[#09090b]">atau daftar dengan</span>
                    </div>
                </div>

                {{-- Google --}}
                <div class="fade-up delay-7">
                    <a href="{{ route('auth.google') }}"
                        class="flex items-center justify-center gap-3 w-full border border-zinc-800 py-3 rounded-xl text-sm text-zinc-400 hover:border-zinc-600 hover:text-white transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" class="shrink-0">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Daftar dengan Google
                    </a>
                </div>

                {{-- Login link --}}
                <div class="fade-up delay-7">
                    <a href="{{ route('login') }}"
                        class="block w-full text-center border border-zinc-800 text-zinc-400 py-3.5 rounded-xl text-sm hover:border-zinc-600 hover:text-white transition">
                        Sudah punya akun? <span class="font-medium text-zinc-300">Masuk sekarang</span>
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
