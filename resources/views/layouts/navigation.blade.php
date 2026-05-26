@php
    $categories = \App\Models\Category::orderBy('name')->get();
@endphp

<div x-data="{ mobileOpen: false, catOpen: false }">

<nav class="border-b border-gray-100 sticky top-0 bg-white/95 backdrop-blur-sm z-50">

    <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between gap-4">

        {{-- Logo --}}
        <a href="{{ auth()->check() ? route('shop.index') : route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
            <img src="{{ asset('images/logo.png') }}" alt="Quro Collection" class="h-9 w-auto transition group-hover:opacity-80">
            <span style="font-family: 'Playfair Display', serif;"
                class="text-lg font-semibold text-gray-900 whitespace-nowrap tracking-wide">
                Quro Collection
            </span>
        </a>

        {{-- Desktop Nav --}}
        <div class="hidden md:flex items-center gap-1 text-sm flex-1 justify-center">

            {{-- Beranda (guest only) --}}
            @guest
            <a href="{{ route('home') }}"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl transition
                {{ request()->routeIs('home')
                    ? 'bg-gray-100 text-gray-900 font-medium'
                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            @endguest

            {{-- Shop --}}
            <a href="{{ route('shop.index') }}"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl transition
                {{ request()->routeIs('shop.index') || request()->routeIs('shop.show')
                    ? 'bg-gray-100 text-gray-900 font-medium'
                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Belanja
            </a>

            {{-- Category Dropdown --}}
            @if($categories->isNotEmpty())
            <div class="relative" x-data="{ catOpen: false }">
                <button @click="catOpen = !catOpen"
                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl transition
                    {{ request()->routeIs('shop.category') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                    </svg>
                    Kategori
                    <svg class="w-3 h-3 text-gray-400 transition-transform" :class="catOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="catOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in-out duration-150"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                    @click.away="catOpen = false"
                    class="absolute left-0 mt-2 w-52 bg-white border border-gray-100 rounded-2xl shadow-lg py-2 text-sm overflow-hidden origin-top-left">
                    <a href="{{ route('shop.index') }}"
                        class="flex items-center gap-2.5 px-4 py-2 text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition text-xs uppercase tracking-widest font-medium">
                        Semua Produk
                    </a>
                    <div class="border-t border-gray-50 my-1"></div>
                    @foreach($categories as $cat)
                        <a href="{{ route('shop.category', $cat) }}"
                            class="flex items-center gap-2.5 px-4 py-2 hover:bg-gray-50 transition
                            {{ request()->routeIs('shop.category') && request()->route('category')?->id === $cat->id
                                ? 'text-gray-900 font-medium'
                                : 'text-gray-600 hover:text-gray-900' }}"
                            @click="catOpen = false">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Tentang Kami (guest only) / Pesanan Saya (auth only) --}}
            @guest
            <a href="{{ route('about') }}"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl transition
                {{ request()->routeIs('about')
                    ? 'bg-gray-100 text-gray-900 font-medium'
                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tentang Kami
            </a>
            @endguest
            @auth
            <a href="{{ route('orders.index') }}"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl transition
                {{ request()->routeIs('orders.*')
                    ? 'bg-gray-100 text-gray-900 font-medium'
                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan
            </a>
            @endauth

        </div>

        {{-- Desktop Right: Auth Actions --}}
        <div class="hidden md:flex items-center gap-1 text-sm shrink-0">

            @auth

            {{-- Notifikasi --}}
            @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
            <a href="{{ route('notifications.index') }}"
                class="relative flex items-center px-3 py-2 rounded-xl transition
                {{ request()->routeIs('notifications.*')
                    ? 'bg-gray-100 text-gray-900'
                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </a>

            {{-- Wishlist --}}
            @php $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count(); @endphp
            <a href="{{ route('wishlist.index') }}"
                class="flex items-center gap-1.5 px-3 py-2 rounded-xl transition relative
                {{ request()->routeIs('wishlist.*')
                    ? 'bg-gray-100 text-gray-900 font-medium'
                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                @if($wishlistCount > 0)
                    <span class="bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center font-medium">
                        {{ $wishlistCount }}
                    </span>
                @endif
            </a>

            {{-- Cart --}}
            <a href="{{ route('cart.index') }}"
                class="flex items-center gap-1.5 px-3 py-2 rounded-xl transition relative
                {{ request()->routeIs('cart.*')
                    ? 'bg-gray-100 text-gray-900 font-medium'
                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                @if($cartCount > 0)
                    <span class="cart-badge bg-gray-900 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center font-medium">
                        {{ $cartCount }}
                    </span>
                @else
                    <span class="cart-badge hidden bg-gray-900 text-white text-xs rounded-full w-4 h-4 items-center justify-center font-medium"></span>
                @endif
            </a>

            {{-- User Dropdown --}}
            <div class="relative ml-1" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center gap-2 pl-3 pr-4 py-2 rounded-xl border border-gray-200 hover:border-gray-300 transition text-gray-700 hover:text-gray-900">
                    <div class="w-6 h-6 bg-gray-900 rounded-full flex items-center justify-center shrink-0">
                        <span class="text-white text-xs font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <span class="text-sm max-w-[80px] truncate">{{ auth()->user()->name }}</span>
                    <svg class="w-3 h-3 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in-out duration-150"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                    @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-2xl shadow-lg py-2 text-sm overflow-hidden origin-top-right">

                    <div class="px-4 py-2 border-b border-gray-50 mb-1">
                        <p class="font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-2.5 px-4 py-2 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil
                    </a>

                    <a href="{{ route('orders.index') }}"
                        class="flex items-center gap-2.5 px-4 py-2 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Pesanan Saya
                    </a>

                    <div class="border-t border-gray-50 mt-1 pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-2.5 w-full px-4 py-2 text-red-400 hover:bg-red-50 hover:text-red-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @endauth

            @guest
            <a href="{{ route('login') }}"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Masuk
            </a>
            <a href="{{ route('register') }}"
                class="flex items-center gap-1.5 bg-gray-900 text-white px-4 py-2 rounded-xl hover:bg-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Daftar
            </a>
            @endguest

        </div>

        {{-- Hamburger --}}
        <button @click="mobileOpen = !mobileOpen"
            class="md:hidden w-9 h-9 flex flex-col items-center justify-center gap-1.5 rounded-xl hover:bg-gray-50 transition-colors shrink-0">
            <span class="block w-5 h-0.5 bg-gray-700 rounded-full transition-all duration-300 ease-in-out"
                  :class="mobileOpen ? 'rotate-45 translate-y-2' : ''"></span>
            <span class="block w-5 h-0.5 bg-gray-700 rounded-full transition-all duration-300 ease-in-out"
                  :class="mobileOpen ? '-rotate-45 -translate-y-2' : ''"></span>
        </button>

    </div>

    {{-- close nav, drawer goes outside --}}
</nav>

    {{-- Mobile Menu --}}
    <div x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in-out duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden border-t border-gray-100 bg-white">

        @auth
        {{-- User strip --}}
        <div class="px-5 py-4 flex items-center gap-3 bg-gray-50 border-b border-gray-100">
            <div class="w-9 h-9 bg-gray-900 rounded-xl flex items-center justify-center shrink-0">
                <span class="text-white text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        @endauth

        <div class="px-4 py-3 space-y-0.5">

            @guest
            <a href="{{ route('home') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition
                {{ request()->routeIs('home') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                @click="mobileOpen = false">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            @endguest

            <a href="{{ route('shop.index') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition
                {{ request()->routeIs('shop.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                @click="mobileOpen = false">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Belanja
            </a>

            @if($categories->isNotEmpty())
            <div x-data="{ catOpen: false }">
                <button @click="catOpen = !catOpen"
                    class="flex items-center gap-3 w-full px-3 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                    </svg>
                    Kategori
                    <svg class="w-3.5 h-3.5 ml-auto text-gray-400 transition-transform duration-200" :class="catOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="catOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in-out duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="ml-10 space-y-0.5 pb-1">
                    @foreach($categories as $cat)
                        <a href="{{ route('shop.category', $cat) }}"
                            class="block px-3 py-2 rounded-xl text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition"
                            @click="mobileOpen = false">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            @guest
            <a href="{{ route('about') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition
                {{ request()->routeIs('about') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                @click="mobileOpen = false">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tentang Kami
            </a>
            @endguest

            @auth
            {{-- Divider setelah shopping links --}}
            <div class="h-px bg-gray-100 my-2"></div>

            {{-- Account & Activity --}}
            <a href="{{ route('cart.index') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition
                {{ request()->routeIs('cart.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                @click="mobileOpen = false">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Keranjang
                @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                @if($cartCount > 0)
                    <span class="ml-auto bg-gray-900 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-medium">{{ $cartCount }}</span>
                @endif
            </a>

            <a href="{{ route('orders.index') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition
                {{ request()->routeIs('orders.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                @click="mobileOpen = false">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan
            </a>

            <a href="{{ route('wishlist.index') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition
                {{ request()->routeIs('wishlist.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                @click="mobileOpen = false">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                Wishlist
                @if(($wishlistCount ?? 0) > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-medium">{{ $wishlistCount }}</span>
                @endif
            </a>

            <a href="{{ route('notifications.index') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition
                {{ request()->routeIs('notifications.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                @click="mobileOpen = false">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifikasi
                @if($unreadCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-medium">{{ $unreadCount }}</span>
                @endif
            </a>

            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition
                {{ request()->routeIs('profile.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                @click="mobileOpen = false">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil
            </a>

            <div class="h-px bg-gray-100 my-2"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 w-full px-3 py-3 rounded-xl text-sm font-medium text-red-400 hover:bg-red-50 hover:text-red-600 transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
            @endauth

            @guest
            <div class="h-px bg-gray-100 my-2"></div>
            <div class="flex gap-2 pb-1">
                <a href="{{ route('login') }}"
                    class="flex-1 text-center px-3 py-2.5 rounded-xl text-sm font-medium border border-gray-200 text-gray-700 hover:border-gray-900 hover:text-gray-900 transition"
                    @click="mobileOpen = false">Masuk</a>
                <a href="{{ route('register') }}"
                    class="flex-1 text-center px-3 py-2.5 rounded-xl text-sm font-semibold bg-gray-900 text-white hover:bg-gray-700 transition"
                    @click="mobileOpen = false">Daftar</a>
            </div>
            @endguest

        </div>
    </div>

</div> {{-- end x-data wrapper --}}
