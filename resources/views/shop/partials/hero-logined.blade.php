@php
    $user      = auth()->user();
    $cartCount = collect(session('cart', []))->sum('quantity');
    $unread    = $user->unreadNotificationsCount();

    $nameParts = explode(' ', trim($user->name));
    $initials  = strtoupper(
        substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
    );
@endphp

<section class="border-b border-gray-100 overflow-hidden"
    x-data="{
        cur: 0,
        total: {{ $featuredProducts->count() ?: 1 }},
        dragX: 0,
        isDragging: false,
        touchStartX: 0,
        init() {
            if (this.total > 1) {
                setInterval(() => {
                    if (!this.isDragging) this.cur = (this.cur + 1) % this.total;
                }, 7000);
            }
            this.$el.addEventListener('touchstart', (e) => {
                this.touchStartX = e.touches[0].clientX;
                this.isDragging = true;
            }, { passive: true });
            this.$el.addEventListener('touchmove', (e) => {
                if (!this.isDragging) return;
                this.dragX = e.touches[0].clientX - this.touchStartX;
            }, { passive: true });
            this.$el.addEventListener('touchend', () => {
                this.isDragging = false;
                if (Math.abs(this.dragX) > 50) {
                    this.cur = this.dragX < 0
                        ? (this.cur + 1) % this.total
                        : (this.cur - 1 + this.total) % this.total;
                }
                this.dragX = 0;
            }, { passive: true });
        },
        slideStyle(i) {
            const offset = (i - this.cur) * 100;
            const t = this.isDragging ? 'none' : 'transform 600ms cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            return `transform: translateX(calc(${offset}% + ${this.dragX}px)); transition: ${t}`;
        }
    }">

    <div class="grid grid-cols-1 md:grid-cols-[70%_30%]">

        {{-- ── Left: Full Background Photo Panel ── --}}
        <div class="relative overflow-hidden flex flex-col justify-between min-h-[360px] md:min-h-[470px]">

            {{-- Background photos — sliding, sama persis dengan guest hero --}}
            <div class="absolute inset-0 overflow-hidden">
                @if($featuredProducts->isNotEmpty())
                    @foreach($featuredProducts as $i => $fp)
                    @php
                        $fpBgUrl = $fp->image
                            ? \Illuminate\Support\Facades\Storage::url($fp->image)
                            : asset('images/produk.jpeg');
                    @endphp
                    <div class="absolute inset-0"
                        :style="slideStyle({{ $i }})"
                        style="transform: translateX({{ $i * 100 }}%); transition: none;">
                        <img src="{{ $fpBgUrl }}" alt="" class="w-full h-full object-cover object-center" aria-hidden="true">
                    </div>
                    @endforeach
                @else
                    <div class="absolute inset-0">
                        <img src="{{ asset('images/produk.jpeg') }}" alt="" class="w-full h-full object-cover object-center" aria-hidden="true">
                    </div>
                @endif
                {{-- Dark overlay --}}
                <div class="absolute inset-0 bg-black/55"></div>
            </div>

            {{-- Greeting — top --}}
            <div class="relative z-10 p-8 md:p-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center shrink-0">
                    <span class="text-sm font-semibold text-white">{{ $initials }}</span>
                </div>
                <div>
                    <p class="text-white font-medium text-sm leading-tight">Halo, {{ $user->name }}!</p>
                    <p class="text-white/60 text-xs mt-0.5"
                        x-data="{
                            orders: {{ $activeOrdersCount }},
                            wishlist: {{ $wishlistCount }},
                            cart: {{ $cartCount }},
                            init() { window.addEventListener('activity-updated', async () => {
                                try {
                                    const r = await fetch('{{ route('api.activity-counts') }}', { headers: { 'Accept': 'application/json' } });
                                    if (!r.ok) return;
                                    const d = await r.json();
                                    this.orders = d.activeOrdersCount;
                                    this.wishlist = d.wishlistCount;
                                    this.cart = d.cartCount;
                                } catch {}
                            }); }
                        }"
                        x-text="orders + ' pesanan · ' + wishlist + ' wishlist · ' + cart + ' keranjang'">
                    </p>
                </div>
            </div>

            {{-- Bottom row — tagline + CTA --}}
            <div class="relative z-10 px-8 md:px-10 pb-8 md:pb-10 flex items-end justify-between gap-4">
                <p class="text-white/70 text-sm leading-snug">
                    Quro Collection<br>
                    <span class="text-[11px] tracking-widest uppercase">Elegansi Muslim</span>
                </p>
                <a href="#products"
                    class="inline-flex items-center gap-2 bg-white text-gray-900 text-sm font-medium px-5 py-2.5 rounded-xl hover:bg-gray-100 transition shrink-0">
                    Mulai Belanja
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
            </div>

        </div>

        {{-- ── Right: Activity Sidebar (desktop only) ── --}}
        <div class="hidden md:flex flex-col border-l border-gray-100 bg-white p-6"
            x-data="{
                cart: {{ $cartCount }},
                orders: {{ $activeOrdersCount }},
                wishlist: {{ $wishlistCount }},
                recent: {{ count(session('recently_viewed', [])) }},
                unread: {{ $unread }},
                async refresh() {
                    try {
                        const r = await fetch('{{ route('api.activity-counts') }}', { headers: { 'Accept': 'application/json' } });
                        if (!r.ok) return;
                        const d = await r.json();
                        this.cart    = d.cartCount;
                        this.orders  = d.activeOrdersCount;
                        this.wishlist = d.wishlistCount;
                        this.recent  = d.recentCount;
                        this.unread  = d.unread;
                    } catch {}
                },
                init() {
                    window.addEventListener('activity-updated', () => this.refresh());
                    setInterval(() => this.refresh(), 60000);
                }
            }">
            <p class="text-[10px] tracking-[0.15em] uppercase text-gray-400 mb-4">Aktivitas</p>

            <div class="flex flex-col flex-1 justify-between">

                {{-- Keranjang --}}
                <a href="{{ route('cart.index') }}" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 group-hover:bg-gray-100 border border-gray-100 flex items-center justify-center shrink-0 transition">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium text-gray-800 leading-tight">Keranjang</p>
                        <p class="text-[11px] text-gray-400 leading-tight" x-text="cart + ' item'"></p>
                    </div>
                    <span x-show="cart > 0" x-text="cart"
                        class="bg-gray-900 text-white text-[10px] font-semibold rounded-full w-5 h-5 flex items-center justify-center shrink-0"></span>
                </a>

                {{-- Pesanan --}}
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 group-hover:bg-gray-100 border border-gray-100 flex items-center justify-center shrink-0 transition">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium text-gray-800 leading-tight">Pesanan</p>
                        <p class="text-[11px] text-gray-400 leading-tight" x-text="orders + ' aktif'"></p>
                    </div>
                    <span x-show="orders > 0" x-text="orders"
                        class="bg-amber-500 text-white text-[10px] font-semibold rounded-full w-5 h-5 flex items-center justify-center shrink-0"></span>
                </a>

                {{-- Wishlist --}}
                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 group-hover:bg-gray-100 border border-gray-100 flex items-center justify-center shrink-0 transition">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium text-gray-800 leading-tight">Wishlist</p>
                        <p class="text-[11px] text-gray-400 leading-tight" x-text="wishlist + ' produk'"></p>
                    </div>
                    <span x-show="wishlist > 0" x-text="wishlist"
                        class="bg-red-500 text-white text-[10px] font-semibold rounded-full w-5 h-5 flex items-center justify-center shrink-0"></span>
                </a>

                {{-- Terakhir Dilihat --}}
                <a href="{{ route('shop.index') }}#products" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 group-hover:bg-gray-100 border border-gray-100 flex items-center justify-center shrink-0 transition">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium text-gray-800 leading-tight">Terakhir Dilihat</p>
                        <p class="recent-viewed-count text-[11px] text-gray-400 leading-tight" x-text="recent + ' produk'"></p>
                    </div>
                </a>

                {{-- Notifikasi --}}
                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 group-hover:bg-gray-100 border border-gray-100 flex items-center justify-center shrink-0 transition">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium text-gray-800 leading-tight">Notifikasi</p>
                        <p class="text-[11px] text-gray-400 leading-tight"
                            x-text="unread > 0 ? unread + ' belum dibaca' : 'Semua terbaca'"></p>
                    </div>
                    <span x-show="unread > 0" x-text="unread"
                        class="bg-blue-500 text-white text-[10px] font-semibold rounded-full w-5 h-5 flex items-center justify-center shrink-0"></span>
                </a>

            </div>
        </div>

        {{-- ── Mobile: Activity Cards (horizontal scroll) ── --}}
        <div class="md:hidden border-t border-gray-100 bg-white overflow-x-auto"
            style="-webkit-overflow-scrolling: touch; scrollbar-width: none;"
            ontouchstart="event.stopPropagation()"
            ontouchmove="event.stopPropagation()"
            ontouchend="event.stopPropagation()"
            x-data="{
                cart: {{ $cartCount }},
                orders: {{ $activeOrdersCount }},
                wishlist: {{ $wishlistCount }},
                unread: {{ $unread }},
                async refresh() {
                    try {
                        const r = await fetch('{{ route('api.activity-counts') }}', { headers: { 'Accept': 'application/json' } });
                        if (!r.ok) return;
                        const d = await r.json();
                        this.cart    = d.cartCount;
                        this.orders  = d.activeOrdersCount;
                        this.wishlist = d.wishlistCount;
                        this.unread  = d.unread;
                    } catch {}
                },
                init() {
                    window.addEventListener('activity-updated', () => this.refresh());
                    setInterval(() => this.refresh(), 60000);
                }
            }">
            <div class="flex gap-3 px-4 py-3 whitespace-nowrap">

                <a href="{{ route('cart.index') }}"
                    class="flex flex-col items-center gap-1.5 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 shrink-0 min-w-[72px]">
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span x-show="cart > 0" x-text="cart"
                            class="absolute -top-1 -right-1 bg-gray-900 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center"></span>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium">Keranjang</span>
                </a>

                <a href="{{ route('orders.index') }}"
                    class="flex flex-col items-center gap-1.5 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 shrink-0 min-w-[72px]">
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span x-show="orders > 0" x-text="orders"
                            class="absolute -top-1 -right-1 bg-amber-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center"></span>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium">Pesanan</span>
                </a>

                <a href="{{ route('wishlist.index') }}"
                    class="flex flex-col items-center gap-1.5 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 shrink-0 min-w-[72px]">
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span x-show="wishlist > 0" x-text="wishlist"
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center"></span>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium">Wishlist</span>
                </a>

                <a href="{{ route('shop.index') }}#products"
                    class="flex flex-col items-center gap-1.5 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 shrink-0 min-w-[72px]">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="text-[11px] text-gray-600 font-medium">Dilihat</span>
                </a>

                <a href="{{ route('notifications.index') }}"
                    class="flex flex-col items-center gap-1.5 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 shrink-0 min-w-[72px]">
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="unread > 0" x-text="unread"
                            class="absolute -top-1 -right-1 bg-blue-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center"></span>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium">Notifikasi</span>
                </a>

            </div>
        </div>

        {{-- ── Bottom Strip ── --}}
        <div class="md:col-span-2 border-t border-gray-100 bg-white flex flex-col md:flex-row md:items-stretch"
            ontouchstart="event.stopPropagation()"
            ontouchmove="event.stopPropagation()"
            ontouchend="event.stopPropagation()">

            {{-- Search — full width mobile, 40% desktop --}}
            <div class="flex items-center gap-2 px-5 py-3 border-b border-gray-100 md:border-b-0 md:border-r md:w-[40%]">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="search-input"
                    placeholder="Cari produk..."
                    class="text-sm text-gray-700 placeholder-gray-400 bg-transparent border-none focus:outline-none focus:ring-0 w-full">
                <button id="reset-filter"
                    class="text-xs text-gray-400 hover:text-gray-600 transition hidden shrink-0">
                    ✕
                </button>
            </div>

            {{-- Category pills --}}
            <div class="flex items-center md:flex-1 overflow-hidden">
                {{-- Filter label — di luar scroll area biar ga ikut geser --}}
                <div class="flex items-center gap-1 text-gray-400 shrink-0 py-3 pl-4 pr-3 border-r border-gray-200">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    <span class="text-[11px]">Filter</span>
                </div>
                <div class="pills-scroll flex items-center gap-2 px-3 py-3 overflow-x-auto whitespace-nowrap flex-1"
                    data-pill-mode="light"
                    style="-webkit-overflow-scrolling: touch; scrollbar-width: none;"
                    onwheel="this.scrollLeft+=event.deltaY">
                <button class="category-pill shrink-0 text-[11px] font-medium px-3.5 py-1.5 rounded-full border transition bg-gray-900 text-white border-gray-900"
                    data-slug="semua" onclick="filterCategory('semua')">
                    Semua
                </button>
                @foreach($categories as $cat)
                <button class="category-pill shrink-0 text-[11px] font-medium px-3.5 py-1.5 rounded-full border transition bg-white text-gray-600 border-gray-200 hover:text-gray-900 hover:border-gray-400"
                    data-slug="{{ $cat->slug }}" onclick="filterCategory('{{ $cat->slug }}')">
                    {{ $cat->name }}
                </button>
                @endforeach
                </div>{{-- end pills-scroll --}}
            </div>{{-- end pills wrapper --}}

        </div>

    </div>
</section>
