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
    @php
        $slides = $heroSlides->isNotEmpty() ? $heroSlides : collect([
            (object)['image' => null, 'type' => 'image', '_static' => asset('images/produk.jpeg')],
            (object)['image' => null, 'type' => 'image', '_static' => asset('images/produk1.JPEG')],
            (object)['image' => null, 'type' => 'image', '_static' => asset('images/produk2.JPEG')],
            (object)['image' => null, 'type' => 'image', '_static' => asset('images/produk3.JPEG')],
        ]);
        $totalProducts = $categories->sum(fn($c) => $c->products_count ?? 0);
    @endphp

    <section class="relative w-full overflow-hidden" style="height: 92vh; min-height: 560px;">

        {{-- Slides --}}
        <div class="slides-wrapper absolute inset-0">
            @foreach($slides as $i => $slide)
                @php
                    $fileUrl = $slide->image
                        ? \Illuminate\Support\Facades\Storage::url($slide->image)
                        : ($slide->_static ?? asset('images/produk.jpeg'));
                    $isVideo = ($slide->type ?? 'image') === 'video';
                @endphp
                @if($isVideo)
                    <div class="slide absolute inset-0 transition-opacity duration-1000 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}">
                        <video class="absolute inset-0 w-full h-full object-cover" autoplay muted loop playsinline>
                            <source src="{{ $fileUrl }}" type="video/mp4">
                        </video>
                    </div>
                @else
                    <div class="slide absolute inset-0 transition-opacity duration-1000 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}"
                        style="background: url('{{ $fileUrl }}') center/cover no-repeat;"></div>
                @endif
            @endforeach
        </div>

        {{-- Gradient overlay — left dark, right clear --}}
        <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/40 to-black/10 z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent z-10"></div>

        {{-- Content --}}
        <div class="absolute inset-0 z-20 flex flex-col justify-end pb-20 px-6 md:px-16">
        <div class="max-w-6xl mx-auto w-full">

            {{-- Label --}}
            <div class="flex items-center gap-2 mb-5">
                <span class="w-6 h-px bg-white/60"></span>
                <p class="text-xs tracking-[0.25em] uppercase text-white/70 font-light">New Collection 2026</p>
            </div>

            {{-- Headline --}}
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-5xl md:text-7xl font-semibold text-white leading-[1.1] mb-5 max-w-xl">
                Koleksi<br>Muslim<br>
                <em class="font-normal not-italic text-white/80">Terbaik.</em>
            </h1>

            {{-- Sub --}}
            <p class="text-white/60 text-sm md:text-base max-w-sm mb-8 leading-relaxed">
                Fashion muslim modern yang memadukan elegasi dan kenyamanan untuk setiap momen.
            </p>

            {{-- CTA --}}
            <div class="flex items-center gap-3 mb-12">
                <a href="#products"
                    class="inline-flex items-center gap-2.5 bg-white text-gray-900 text-sm font-medium px-7 py-3 rounded-xl hover:bg-gray-100 transition">
                    Lihat Koleksi
                    <svg class="w-3.5 h-3.5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
                <a href="{{ route('about') }}"
                    class="border border-white/30 text-white text-sm px-7 py-3 rounded-xl hover:border-white hover:bg-white/10 transition">
                    Tentang Kami
                </a>
            </div>

            {{-- Stats --}}
            <div class="flex items-center gap-6">
                <div>
                    <p class="text-white text-xl font-semibold">{{ $categories->count() }}+</p>
                    <p class="text-white/50 text-xs tracking-wide">Kategori</p>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div>
                    <p class="text-white text-xl font-semibold">Premium</p>
                    <p class="text-white/50 text-xs tracking-wide">Kualitas Bahan</p>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div>
                    <p class="text-white text-xl font-semibold">Cepat</p>
                    <p class="text-white/50 text-xs tracking-wide">Pengiriman</p>
                </div>
            </div>

        </div>{{-- end max-w wrapper --}}
        </div>{{-- end absolute content --}}

        {{-- Slide counter + dots --}}
        <div class="absolute bottom-6 right-6 md:right-16 z-20 flex items-center gap-3">
            <div class="flex gap-1.5">
                @foreach($slides as $i => $_)
                    <button class="dot h-1 rounded-full transition-all {{ $i === 0 ? 'bg-white w-6' : 'bg-white/40 w-2' }}"
                        onclick="goToSlide({{ $i }})"></button>
                @endforeach
            </div>
        </div>

        {{-- Scroll hint --}}
        <a href="#products"
            class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-2 group">
            {{-- <p class="text-white/50 text-[10px] tracking-[0.2em] uppercase group-hover:text-white/80 transition">Scroll</p> --}}
            <div class="flex flex-col items-center gap-0.5">
                <svg class="w-5 h-5 text-white/60 group-hover:text-white transition animate-[bounce_1.5s_ease-in-out_infinite]"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                </svg>
                <svg class="w-5 h-5 text-white/30 group-hover:text-white/60 transition animate-[bounce_1.5s_ease-in-out_0.2s_infinite]"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </a>

    </section>

    {{-- Anchor untuk scroll --}}
    <div id="products"></div>

    {{-- Filter --}}
    <section class="max-w-6xl mx-auto px-6 py-8">
        <div class="flex flex-wrap gap-3 items-center">
            <input type="text" id="search-input"
                placeholder="Cari produk..."
                class="border border-gray-200 rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:border-gray-400">
            <button id="reset-filter" class="text-sm text-gray-400 hover:text-gray-600 hidden">Reset</button>
        </div>
    </section>

    {{-- Products by Category --}}
    <section class="max-w-6xl mx-auto px-6 pb-20">
        <div id="product-grid">
            @include('shop.partials.product-grid', ['categories' => $categories])
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

                            {{-- Cart Success --}}
                            <div id="qv-success" class="hidden text-center py-4">
                                <p class="text-2xl mb-2">✓</p>
                                <p class="font-medium text-gray-900 mb-4">Ditambahkan ke keranjang</p>
                                <div class="flex gap-3">
                                    <button onclick="closeQuickView()"
                                        class="flex-1 border border-gray-200 py-2 rounded-lg text-sm hover:border-gray-900 transition">
                                        Lanjut Belanja
                                    </button>
                                    <a href="{{ route('cart.index') }}"
                                        class="flex-1 bg-gray-900 text-white py-2 rounded-lg text-sm text-center hover:bg-gray-700 transition">
                                        Lihat Keranjang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @push('scripts')
    @vite(['resources/js/pages/shop.js', 'resources/js/pages/quick-view.js'])
    @endpush
</x-app-layout>