<x-app-layout>
    @push('seo')
    <title>Shop — Quro Collection</title>
    <meta name="description" content="Koleksi baju koko premium dengan desain modern dan bahan berkualitas tinggi.">
    <meta property="og:title" content="Shop — Quro Collection">
    <meta property="og:description" content="Koleksi baju koko premium dengan desain modern dan bahan berkualitas tinggi.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('shop.index') }}">
    @endpush

    {{-- Hero Slider --}}
    @auth
    @include('shop.partials.hero-logined')
    @endauth

    @guest
    {{-- ── Hero Guest (split layout) ── --}}
    @push('styles')
    <style>
        @keyframes heroMarquee {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .hero-marquee { animation: heroMarquee 28s linear infinite; }
        .pills-scroll::-webkit-scrollbar { display: none; }

        @keyframes kenBurns {
            from { transform: scale(1);    }
            to   { transform: scale(1.08); }
        }
        .featured-slide-img {
            transition: none;
        }
        .featured-slide-img.is-active {
            animation: kenBurns 5s ease forwards;
        }
        .hero-split-height { min-height: 500px; }
        @media (min-width: 768px) {
            .hero-split-height { min-height: 320px; }
        }
    </style>
    @endpush

    <section class="relative overflow-hidden border-b border-white/10"
        x-data="{
            cur: 0,
            total: {{ $featuredProducts->count() }},
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
        }"
        x-effect="
            $refs.slider.querySelectorAll('.featured-slide-img').forEach((img, i) => {
                img.classList.remove('is-active');
                void img.offsetWidth;
                if (i === cur) img.classList.add('is-active');
            });
        ">

        {{-- ── Background photos (sync dengan slider) --}}
        <div class="absolute inset-0">
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
            {{-- Desktop: kiri gelap → kanan transparan --}}
            <div class="absolute inset-0 hidden md:block" style="background: linear-gradient(to right, rgba(0,0,0,0.90) 0%, rgba(0,0,0,0.72) 40%, rgba(0,0,0,0.15) 75%, rgba(0,0,0,0) 100%)"></div>
            {{-- Mobile: full vignette — bawah + atas gelap, tengah sedikit terang --}}
            <div class="absolute inset-0 md:hidden" style="background: linear-gradient(to top, rgba(0,0,0,0.96) 0%, rgba(0,0,0,0.65) 40%, rgba(0,0,0,0.35) 100%)"></div>
            <div class="absolute inset-0 md:hidden" style="background: linear-gradient(to bottom, rgba(0,0,0,0.60) 0%, rgba(0,0,0,0) 40%)"></div>
        </div>

        {{-- ── Split: Left text + Right photo ── --}}
        <div class="relative z-10 flex flex-col md:flex-row hero-split-height">

            {{-- Left panel: mobile = full hero, desktop = 60% kiri --}}
            <div class="flex-1 md:flex-none md:w-3/5 flex flex-col justify-start md:justify-center px-6 md:px-16 pt-12 pb-4 md:py-10">

                {{-- NEW ARRIVAL --}}
                <div class="flex items-center gap-2 mb-7">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    <span class="text-[11px] tracking-[0.2em] uppercase text-white font-medium">New Arrival Quro Collection</span>
                </div>

                {{-- Headline: desktop only --}}
                <h1 style="font-family: 'Cormorant Garamond', serif;"
                    class="hidden md:block text-4xl md:text-6xl leading-[1.15] mb-3 md:mb-5">
                    <span class="font-light text-white/70">Koleksi </span><span class="font-semibold text-white">Koko Premium,</span><br>
                    <span class="font-light text-white/70">Tampil </span><span class="font-semibold text-white">Elegan Setiap Hari.</span>
                </h1>

                <p class="hidden md:block text-gray-300 text-sm md:text-base leading-relaxed md:mb-8 max-w-xs">
                    Fashion muslim modern yang memadukan elegasi dan kenyamanan untuk setiap momen.
                </p>

                {{-- CTA: desktop only (mobile dipindah ke bawah split wrapper) --}}
                <div class="hidden md:flex items-center gap-4">
                    <a href="#products"
                        class="inline-flex items-center gap-2 bg-white text-gray-900 text-sm font-medium px-6 py-3 rounded-xl hover:bg-gray-100 transition">
                        Mulai Belanja
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                    <span class="text-xs text-white/50">{{ $totalProductsCount }}+ produk tersedia</span>
                </div>

            </div>

            {{-- Right panel: hidden di mobile, tampil desktop --}}
            <div class="hidden md:flex md:flex-col md:w-2/5 relative overflow-hidden border-l border-white/10"
                x-ref="slider">

                @if($featuredProducts->isNotEmpty())
                    @foreach($featuredProducts as $i => $fp)
                    @php
                        $fpUrl = $fp->image
                            ? \Illuminate\Support\Facades\Storage::url($fp->image)
                            : asset('images/produk.jpeg');
                        $fpMinPrice = $fp->variants->where('stock', '>', 0)->min('effective_price');
                    @endphp
                    <div class="absolute inset-0"
                        :style="{{ $i }} === cur
                            ? 'opacity:1;z-index:10;transition:opacity 900ms cubic-bezier(0.4,0,0.2,1)'
                            : 'opacity:0;z-index:0;transition:opacity 900ms cubic-bezier(0.4,0,0.2,1)'"
                        style="{{ $i === 0 ? 'opacity:1;z-index:10' : 'opacity:0;z-index:0' }}">
                        <img src="{{ $fpUrl }}" alt="{{ $fp->name }}"
                            class="featured-slide-img w-full h-full object-cover object-center {{ $i === 0 ? 'is-active' : '' }}"
                            :class="{{ $i }} === cur ? 'is-active' : ''">
                        <div class="absolute inset-0 bg-black/50"></div>
                        @if($fpMinPrice)
                        <div class="absolute bottom-14 left-5 z-10 bg-gray-900 text-white px-4 py-2.5 rounded-xl">
                            <p class="text-[9px] tracking-[0.15em] uppercase text-gray-400 mb-0.5">Mulai dari</p>
                            <p class="text-base font-bold leading-tight">Rp {{ number_format($fpMinPrice, 0, ',', '.') }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="absolute inset-0">
                        <img src="{{ asset('images/produk.jpeg') }}" alt="Quro Collection"
                            class="w-full h-full object-cover object-center">
                        <div class="absolute inset-0 bg-black/50"></div>
                    </div>
                @endif

                {{-- "Featured ♦" pill top-right --}}
                <div class="absolute top-4 right-4 z-20">
                    <span class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-800 text-[11px] font-medium px-4 py-2 rounded-full">
                        Featured ♦
                    </span>
                </div>

                {{-- Bottom bar: dots + product name --}}
                <div class="absolute inset-x-0 bottom-0 z-20 px-5 py-3 flex items-center justify-between gap-3"
                    style="background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0) 100%)">
                    <div class="flex items-center gap-1.5 shrink-0">
                        @if($featuredProducts->count() > 1)
                            @foreach($featuredProducts as $i => $_)
                            <button @click="cur = {{ $i }}"
                                class="rounded-full transition-all duration-300"
                                :class="{{ $i }} === cur ? 'bg-white w-5 h-[2px]' : 'bg-white/40 w-1.5 h-1.5'">
                            </button>
                            @endforeach
                        @else
                            <span class="w-5 h-0.5 bg-white rounded-full"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-white/40"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-white/40"></span>
                        @endif
                    </div>
                    <div class="relative overflow-hidden flex-1 text-right" style="height: 1.25rem;">
                        @foreach($featuredProducts as $i => $fp)
                        <p class="absolute inset-0 text-xs text-white/80 truncate transition-[opacity] duration-500"
                            :style="{{ $i }} === cur ? 'opacity:1' : 'opacity:0'"
                            style="opacity: {{ $i === 0 ? '1' : '0' }}">
                            {{ $fp->name }}
                        </p>
                        @endforeach
                        @if($featuredProducts->isEmpty())
                        <p class="text-xs text-white/80 truncate">Quro Collection</p>
                        @endif
                    </div>
                </div>

            </div>

        </div>

        {{-- Mobile: CTA row --}}
        <div class="relative z-10 flex md:hidden items-center justify-between gap-3 px-5 py-4">
            <a href="#products"
                class="inline-flex items-center gap-2 bg-white text-gray-900 text-sm font-medium px-6 py-3 rounded-xl hover:bg-gray-100 transition">
                Mulai Belanja
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </a>
            <span class="text-xs text-white/50 text-right">{{ $totalProductsCount }}+ produk tersedia</span>
        </div>

        {{-- Mobile: dots (kiri) + nama produk (kanan) — di atas category pills --}}
        @if($featuredProducts->isNotEmpty())
        <div class="relative z-10 flex md:hidden items-center justify-between gap-3 px-5 py-2.5 border-t border-white/10">
            <div class="flex items-center gap-1.5 shrink-0">
                @foreach($featuredProducts as $i => $_)
                <button @click="cur = {{ $i }}"
                    class="rounded-full transition-all duration-300"
                    :class="{{ $i }} === cur ? 'bg-white w-5 h-[2px]' : 'bg-white/40 w-1.5 h-1.5'">
                </button>
                @endforeach
            </div>
            <div class="relative overflow-hidden flex-1 text-right" style="height: 1.1rem;">
                @foreach($featuredProducts as $i => $fp)
                <p class="absolute inset-0 text-[11px] text-white/70 truncate transition-[opacity] duration-500"
                    :style="{{ $i }} === cur ? 'opacity:1' : 'opacity:0'"
                    style="opacity: {{ $i === 0 ? '1' : '0' }}">
                    {{ $fp->name }}
                </p>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Bottom bar: ticker + category pills ── --}}
        <div class="relative z-10 border-t border-white/10 md:flex md:items-stretch md:divide-x md:divide-white/10" style="background: rgba(0,0,0,0.45)">

            {{-- Ticker (hidden on mobile) --}}
            <div class="hidden md:flex flex-1 overflow-hidden py-3 items-center">
                <div class="hero-marquee inline-flex gap-8 whitespace-nowrap">
                    @php
                        $tickerItems = $categories->flatMap(fn($cat) =>
                            $cat->products->take(3)->map(fn($p) => $cat->name . ' · ' . $p->name)
                        );
                        $tickerStr = $tickerItems->implode('  /  ');
                    @endphp
                    <span class="text-[11px] text-white/40 tracking-wide pl-6">{{ $tickerStr }}</span>
                    <span class="text-[11px] text-white/40 tracking-wide">{{ $tickerStr }}</span>
                </div>
            </div>

            {{-- Category pills --}}
            <div class="pills-scroll flex items-center gap-2 px-4 py-3 overflow-x-auto md:overflow-visible md:shrink-0 md:flex-wrap whitespace-nowrap" data-pill-mode="dark" style="-webkit-overflow-scrolling: touch; scrollbar-width: none;" onwheel="this.scrollLeft+=event.deltaY">
                <button class="category-pill shrink-0 text-[11px] font-medium px-3.5 py-1.5 rounded-full border transition bg-white text-gray-900 border-white"
                    data-slug="semua" onclick="filterCategory('semua')">
                    Semua
                </button>
                @foreach($categories as $cat)
                <button class="category-pill shrink-0 text-[11px] font-medium px-3.5 py-1.5 rounded-full border transition bg-transparent text-white/70 border-white/30 hover:bg-white/10 hover:border-white/60"
                    data-slug="{{ $cat->slug }}" onclick="filterCategory('{{ $cat->slug }}')">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>

        </div>

    </section>
    @endguest

    {{-- Anchor untuk scroll --}}
    <div id="products"></div>

    {{-- Filter (guest only — auth gets search in hero bottom strip) --}}
    @guest
    <section class="max-w-6xl mx-auto px-6 py-8">
        <div class="flex flex-wrap gap-3 items-center">
            <input type="text" id="search-input"
                placeholder="Cari produk..."
                class="border border-gray-200 rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:border-gray-400">
            <button id="reset-filter" class="text-sm text-gray-400 hover:text-gray-600 hidden">Reset</button>
        </div>
    </section>
    @endguest

    {{-- Products --}}
    <section class="max-w-6xl mx-auto px-6 pb-20 pt-4">
        <div id="product-grid">
            @include('shop.partials.product-grid', ['products' => $products])
        </div>
    </section>

        {{-- Modal Quick View --}}
        <div id="quick-view-modal"
            class="hidden fixed inset-0 bg-black/50 z-50 flex items-end md:items-center justify-center p-4"
            onclick="if(event.target===this) closeQuickView()">

            <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto modal-scroll">

                {{-- Loading --}}
                <div id="qv-loading" class="flex items-center justify-center py-20">
                    <div class="w-8 h-8 border-2 border-gray-900 border-t-transparent rounded-full animate-spin"></div>
                </div>

                {{-- Content --}}
                <div id="qv-content" class="hidden p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Image --}}
                        <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden">
                            <img id="qv-image" src="" class="w-full h-full object-cover">
                            <div id="qv-no-image" class="hidden w-full h-full flex items-center justify-center text-gray-300 text-sm">No Image</div>
                        </div>

                        {{-- Detail --}}
                        <div class="flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <p id="qv-category" class="text-xs tracking-widest uppercase text-gray-400"></p>
                                <button onclick="closeQuickView()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
                            </div>

                            <h2 id="qv-name" style="font-family: 'Playfair Display', serif;"
                                class="text-2xl font-semibold text-gray-900 mb-2"></h2>
                            <p id="qv-price" class="text-xl text-gray-900 mb-3"></p>
                            <p id="qv-desc" class="text-gray-500 text-sm leading-relaxed mb-4"></p>

                            @auth
                            {{-- Size --}}
                            <div class="mb-4">
                                <p class="text-xs text-gray-400 uppercase tracking-widest mb-2">Size</p>
                                <div id="qv-sizes" class="flex flex-wrap gap-2"></div>
                                <p id="qv-stock-info" class="text-xs text-gray-400 mt-2 hidden"></p>
                                <input type="hidden" id="qv-selected-size">
                            </div>

                            {{-- Qty --}}
                            <div class="mb-4">
                                <p class="text-xs text-gray-400 uppercase tracking-widest mb-2">Jumlah</p>
                                <div class="flex items-center gap-4">
                                    <button type="button" onclick="qvChangeQty(-1)"
                                        class="w-9 h-9 border border-gray-200 rounded-lg flex items-center justify-center text-lg hover:border-gray-900 transition">−</button>
                                    <span id="qv-qty-display" class="text-lg font-medium w-6 text-center">1</span>
                                    <button type="button" onclick="qvChangeQty(1)"
                                        class="w-9 h-9 border border-gray-200 rounded-lg flex items-center justify-center text-lg hover:border-gray-900 transition">+</button>
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="flex justify-between items-center py-3 border-t border-gray-100 mb-4">
                                <span class="text-sm text-gray-500">Total</span>
                                <span id="qv-total" class="text-lg font-semibold text-gray-900">Pilih size</span>
                            </div>

                            <button onclick="qvSubmitCart()"
                                class="w-full bg-gray-900 text-white py-3 rounded-lg text-sm font-medium hover:bg-gray-700 transition mb-3">
                                Tambah ke Keranjang
                            </button>
                            @else
                            <a id="qv-login-btn" href="{{ route('login') }}"
                                class="block text-center bg-gray-900 text-white py-3 rounded-lg text-sm font-medium hover:bg-gray-700 transition mb-3">
                                Login untuk Membeli
                            </a>
                            @endauth

                            <a id="qv-detail-link" href="#"
                                class="block text-center border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm hover:border-gray-900 transition">
                                Lihat Detail Produk →
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @push('scripts')
    @vite(['resources/js/pages/shop.js', 'resources/js/pages/quick-view.js'])
    @endpush
</x-app-layout>