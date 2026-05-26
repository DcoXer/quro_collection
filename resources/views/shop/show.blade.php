<x-app-layout>
    @push('seo')
    <title>{{ $product->meta_title ?? $product->name }} — Quro Collection</title>
    <meta name="description" content="{{ $product->meta_description ?? Str::limit($product->description, 160) }}">
    <meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
    <meta property="og:description" content="{{ $product->meta_description ?? Str::limit($product->description, 160) }}">
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ route('shop.show', $product) }}">
    @if($product->image)
    <meta property="og:image" content="{{ Storage::url($product->image) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->meta_title ?? $product->name }}">
    <meta name="twitter:description" content="{{ $product->meta_description ?? Str::limit($product->description, 160) }}">
    @if($product->image)
    <meta name="twitter:image" content="{{ Storage::url($product->image) }}">
    @endif
    @endpush

    @push('jsonld')
    <script type="application/ld+json">@json($jsonLd)</script>
    @endpush
    
    @vite(['resources/css/pages/product-show.css'])

    <div class="max-w-5xl mx-auto px-6 py-8 md:py-12">

        <a href="{{ route('shop.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 border border-gray-100 hover:border-gray-200 px-3 py-1.5 rounded-xl transition mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Semua Koleksi
        </a>

        @if($flashItem)
        <div class="bg-gray-950 rounded-2xl px-4 py-3 mb-8 flex items-center justify-between gap-4"
             x-data="flashCountdown({{ $flashItem->flashSale->ends_at->timestamp }})"
             x-init="start()">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-amber-400 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-gray-950" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xs font-semibold">{{ $flashItem->flashSale->name }}</p>
                    <p class="text-zinc-400 text-xs">
                        {{ $flashItem->discount_type === 'percent'
                            ? 'Diskon '.$flashItem->discount_value.'%'
                            : 'Hemat Rp '.number_format($flashItem->discount_value, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <template x-if="!expired">
                <div class="text-right shrink-0">
                    <p class="text-zinc-500 text-[10px] uppercase tracking-widest">Berakhir dalam</p>
                    <p class="text-amber-400 text-sm font-mono font-bold tabular-nums"
                       x-text="hours + ':' + minutes + ':' + seconds"></p>
                </div>
            </template>
            <template x-if="expired">
                <span class="text-red-400 text-xs font-medium shrink-0">Flash sale berakhir</span>
            </template>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16">

            {{-- ───── Gallery ───── --}}
            <div class="product-gallery">

                {{-- Main Image --}}
                <div class="product-gallery-main" id="gallery-main">
                    @php
                        $allMedia = collect();
                        if ($product->image) {
                            $allMedia->push(['type' => 'image', 'path' => $product->image, 'is_main' => true]);
                        }
                        foreach ($product->media as $m) {
                            $allMedia->push(['type' => $m->type, 'path' => $m->path, 'is_main' => false]);
                        }
                    @endphp

                    @if($allMedia->isNotEmpty())
                        @foreach($allMedia as $i => $media)
                            <div class="product-gallery-slide {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}">
                                @if($media['type'] === 'video')
                                    <video class="w-full h-full object-cover" muted loop playsinline autoplay>
                                        <source src="{{ Storage::url($media['path']) }}" type="video/mp4">
                                    </video>
                                @else
                                    <img src="{{ Storage::url($media['path']) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="product-gallery-empty">Tidak ada gambar</div>
                    @endif
                </div>

                {{-- Thumbnails — hanya tampil kalau ada lebih dari 1 media --}}
                @if($allMedia->count() > 1)
                <div class="product-gallery-thumbs" id="gallery-thumbs">
                    @foreach($allMedia as $i => $media)
                        <button type="button"
                            class="product-gallery-thumb {{ $i === 0 ? 'active' : '' }}"
                            data-index="{{ $i }}"
                            onclick="switchGallery({{ $i }})">
                            @if($media['type'] === 'video')
                                <div class="product-thumb-video-icon">▶</div>
                            @else
                                <img src="{{ Storage::url($media['path']) }}" alt="Thumbnail {{ $i + 1 }}">
                            @endif
                        </button>
                    @endforeach
                </div>
                @endif

            </div>

            {{-- ───── Detail ───── --}}
            <div class="flex flex-col justify-center">

                <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">
                    {{ $product->category?->name }}
                </p>

                <h1 style="font-family: 'Playfair Display', serif;"
                    class="text-3xl md:text-4xl font-semibold text-gray-900 leading-tight mb-4">
                    {{ $product->name }}
                </h1>

                @if($flashItem)
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 rounded-xl px-3 py-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                            </svg>
                            <span class="text-amber-700 text-xs font-semibold">Flash Sale!</span>
                        </div>
                        <span class="bg-red-100 text-red-600 text-xs font-bold px-2.5 py-1 rounded-lg">
                            {{ $flashItem->discount_type === 'percent' ? '-'.$flashItem->discount_value.'%' : 'Hemat Rp '.number_format($flashItem->discount_value, 0, ',', '.') }}
                        </span>
                    </div>
                    <p class="text-2xl text-gray-900 font-semibold" id="display-price">
                        Rp {{ number_format($flashItem->flash_price, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-gray-400 line-through mt-0.5">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>
                @else
                <p class="text-2xl text-gray-900 font-medium mb-4" id="display-price">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
                @endif

                @if($product->description)
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">
                        {{ $product->description }}
                    </p>
                @endif

                {{-- Size Selector --}}
                <div class="mb-2">
                    <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Pilih Size</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                            @if($variant->stock > 0)
                                @php
                                    $variantFlashPrice = null;
                                    if ($flashItem) {
                                        $variantFlashPrice = $flashItem->discount_type === 'percent'
                                            ? $variant->effective_price - ($variant->effective_price * $flashItem->discount_value / 100)
                                            : $variant->effective_price - $flashItem->discount_value;
                                        $variantFlashPrice = max(0, (int) $variantFlashPrice);
                                    }
                                @endphp
                                <button type="button"
                                    data-size="{{ $variant->size }}"
                                    data-price="{{ $variant->effective_price }}"
                                    data-flash-price="{{ $variantFlashPrice }}"
                                    data-stock="{{ $variant->stock }}"
                                    onclick="selectSizePage(this)"
                                    class="page-size-btn px-4 py-2 border border-gray-200 rounded-lg text-sm hover:border-gray-900 transition">
                                    {{ $variant->size }}
                                </button>
                            @else
                                <span class="px-4 py-2 border border-gray-100 rounded-lg text-sm text-gray-300 cursor-not-allowed"
                                    title="Stok habis">
                                    {{ $variant->size }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-300 mt-2">Size redup = stok habis</p>
                </div>

                <p class="text-xs text-gray-400 mb-6" id="stock-info">
                    Stok tersedia: {{ $product->variants->sum('stock') }} pcs
                </p>

                @if($product->variants->sum('stock') > 0)
                    @auth
                        <form id="page-cart-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="size" id="page-selected-size">
                            <input type="hidden" name="quantity" id="page-qty-input" value="1">
                        </form>

                        {{-- Qty --}}
                        <div class="flex items-center gap-4 mb-5">
                            <p class="text-xs tracking-widest uppercase text-gray-400">Jumlah</p>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="changePageQty(-1)"
                                    class="w-9 h-9 border border-gray-200 rounded-lg flex items-center justify-center text-lg hover:border-gray-900 transition">−</button>
                                <span id="page-qty-display" class="text-base font-medium w-6 text-center">1</span>
                                <button type="button" onclick="changePageQty(1)"
                                    class="w-9 h-9 border border-gray-200 rounded-lg flex items-center justify-center text-lg hover:border-gray-900 transition">+</button>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button id="page-add-btn" onclick="addToCartPage()"
                                class="flex-1 bg-gray-900 text-white py-3.5 rounded-xl text-sm font-medium hover:bg-gray-700 transition flex items-center justify-center gap-2">
                                <svg id="page-btn-spinner" class="hidden animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                </svg>
                                <span id="page-btn-text">Tambah ke Keranjang</span>
                            </button>
                            <button type="button"
                                x-data="{ on: {{ $inWishlist ? 'true' : 'false' }} }"
                                @click="
                                    fetch('{{ route('wishlist.toggle', $product) }}', {
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                    }).then(r => r.json()).then(d => on = d.in_wishlist)
                                "
                                :class="on ? 'border-red-200 bg-red-50 text-red-500 hover:bg-red-100' : 'border-gray-200 text-gray-400 hover:border-gray-400 hover:text-gray-700'"
                                class="w-12 h-12 border rounded-xl flex items-center justify-center transition">
                                <svg class="w-5 h-5" :fill="on ? 'currentColor' : 'none'"
                                    stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="block text-center w-full bg-gray-900 text-white py-3.5 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                            Login untuk Membeli
                        </a>
                    @endauth
                @else
                    <div class="w-full border border-red-100 bg-red-50 text-red-400 py-3 rounded-xl text-sm text-center">
                        Stok habis
                    </div>
                @endif

                {{-- Share --}}
                @php $shareUrl = route('shop.show', $product); $shareText = 'Cek produk ini di Quro Collection: ' . $product->name; @endphp
                <div x-data="{ copied: false }" class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                    <span class="text-xs text-gray-400 tracking-widest uppercase mr-1">Bagikan</span>

                    {{-- WhatsApp --}}
                    <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}"
                        target="_blank" rel="noopener"
                        class="w-9 h-9 border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:border-green-400 hover:text-green-500 hover:bg-green-50 transition"
                        title="Bagikan ke WhatsApp">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>

                    {{-- Copy Link --}}
                    <button type="button"
                        @click="
                            navigator.clipboard.writeText('{{ $shareUrl }}');
                            copied = true;
                            setTimeout(() => copied = false, 2000)
                        "
                        class="w-9 h-9 border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:border-gray-400 hover:text-gray-700 hover:bg-gray-50 transition"
                        title="Salin link">
                        <svg x-show="!copied" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <svg x-show="copied" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="text-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>

                    <span x-show="copied" x-transition class="text-xs text-green-500">Link disalin!</span>
                </div>

            </div>
        </div>
    </div>

    {{-- ───── Related Products ───── --}}
    @if($related->isNotEmpty())
    <div class="max-w-5xl mx-auto px-6 pb-16">
        <div class="flex items-center gap-4 mb-6">
            <h2 style="font-family: 'Playfair Display', serif;"
                class="text-xl font-semibold text-gray-900 whitespace-nowrap">
                Produk Lainnya
            </h2>
            <div class="flex-1 h-px bg-gray-100"></div>
            <a href="{{ route('shop.category', $product->category) }}"
                class="text-xs text-gray-400 hover:text-gray-700 transition underline underline-offset-2 whitespace-nowrap">
                Lihat semua →
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($related as $item)
            @php $slideCount = ($item->image ? 1 : 0) + $item->media->count(); $trackId = 'rel-'.$item->id; @endphp
            <div class="group cursor-pointer" onclick="window.location.href='{{ route('shop.show', $item) }}'">
                <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-3 relative"
                    data-slider="{{ $trackId }}"
                    @if($slideCount > 1) data-has-slides="true" @endif
                    style="touch-action: pan-y;">

                    <div class="slides-track flex h-full will-change-transform"
                        id="track-{{ $trackId }}"
                        style="transition: transform 400ms cubic-bezier(0.25, 0.46, 0.45, 0.94);">

                        @if($item->image)
                        <div class="slide-item w-full h-full shrink-0 overflow-hidden">
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-105">
                        </div>
                        @endif

                        @foreach($item->media as $media)
                        <div class="slide-item w-full h-full shrink-0 overflow-hidden">
                            @if($media->type === 'video')
                            <video class="w-full h-full object-cover" muted loop playsinline>
                                <source src="{{ Storage::url($media->path) }}" type="video/mp4">
                            </video>
                            @else
                            <img src="{{ Storage::url($media->path) }}"
                                class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-105">
                            @endif
                        </div>
                        @endforeach
                    </div>

                    @if($slideCount > 1)
                    <button onclick="event.stopPropagation(); slideCard('{{ $trackId }}', -1)"
                        class="absolute left-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 backdrop-blur-sm rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 ease-out hover:bg-white hover:scale-110 z-10">
                        ‹
                    </button>
                    <button onclick="event.stopPropagation(); slideCard('{{ $trackId }}', 1)"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 backdrop-blur-sm rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 ease-out hover:bg-white hover:scale-110 z-10">
                        ›
                    </button>
                    @endif
                </div>

                <p class="text-xs text-gray-400 mb-0.5">{{ $item->category?->name }}</p>
                <p class="text-sm font-medium text-gray-900 group-hover:text-gray-600 transition">{{ $item->name }}</p>
                <p class="text-sm text-gray-700 mt-0.5">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ───── Recently Viewed ───── --}}
    @if($recentlyViewed->isNotEmpty())
    <div class="max-w-5xl mx-auto px-6 pb-16">
        <div class="flex items-center gap-4 mb-6">
            <h2 style="font-family: 'Playfair Display', serif;"
                class="text-xl font-semibold text-gray-900 whitespace-nowrap">
                Terakhir Dilihat
            </h2>
            <div class="flex-1 h-px bg-gray-100"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($recentlyViewed as $item)
            @php $slideCount = ($item->image ? 1 : 0) + $item->media->count(); $trackId = 'rv-'.$item->id; @endphp
            <div class="group cursor-pointer" onclick="window.location.href='{{ route('shop.show', $item) }}'">
                <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-3 relative"
                    data-slider="{{ $trackId }}"
                    @if($slideCount > 1) data-has-slides="true" @endif
                    style="touch-action: pan-y;">

                    <div class="slides-track flex h-full will-change-transform"
                        id="track-{{ $trackId }}"
                        style="transition: transform 400ms cubic-bezier(0.25, 0.46, 0.45, 0.94);">

                        @if($item->image)
                        <div class="slide-item w-full h-full shrink-0 overflow-hidden">
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-105">
                        </div>
                        @endif

                        @foreach($item->media as $media)
                        <div class="slide-item w-full h-full shrink-0 overflow-hidden">
                            @if($media->type === 'video')
                            <video class="w-full h-full object-cover" muted loop playsinline>
                                <source src="{{ Storage::url($media->path) }}" type="video/mp4">
                            </video>
                            @else
                            <img src="{{ Storage::url($media->path) }}"
                                class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-105">
                            @endif
                        </div>
                        @endforeach
                    </div>

                    @if($slideCount > 1)
                    <button onclick="event.stopPropagation(); slideCard('{{ $trackId }}', -1)"
                        class="absolute left-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 backdrop-blur-sm rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 ease-out hover:bg-white hover:scale-110 z-10">
                        ‹
                    </button>
                    <button onclick="event.stopPropagation(); slideCard('{{ $trackId }}', 1)"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 backdrop-blur-sm rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 ease-out hover:bg-white hover:scale-110 z-10">
                        ›
                    </button>
                    @endif
                </div>

                <p class="text-xs text-gray-400 mb-0.5">{{ $item->category?->name }}</p>
                <p class="text-sm font-medium text-gray-900 group-hover:text-gray-600 transition">{{ $item->name }}</p>
                <p class="text-sm text-gray-700 mt-0.5">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ───── Ulasan ───── --}}
    <div class="mt-16 max-w-5xl mx-auto px-6 pb-16">

        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <h2 style="font-family: 'Playfair Display', serif;"
                class="text-2xl font-semibold text-gray-900">Ulasan</h2>
            <div class="flex-1 h-px bg-gray-100"></div>
            @if($reviewCount > 0)
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-200' }}"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $avgRating }}</span>
                    <span class="text-sm text-gray-400">· {{ $reviewCount }} ulasan</span>
                </div>
            @endif
        </div>

        @if($reviews->isEmpty())
            <div class="text-center py-14 bg-gray-50 rounded-2xl">
                <p class="text-2xl mb-2">💬</p>
                <p class="text-sm font-medium text-gray-500">Belum ada ulasan</p>
                <p class="text-xs text-gray-400 mt-1">Jadilah yang pertama mengulas produk ini</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl">
                @foreach($reviews as $review)
                    <div class="bg-gray-50 rounded-2xl p-5">

                        {{-- Top: Avatar + nama + tanggal --}}
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center text-white text-sm font-semibold shrink-0">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 leading-tight">{{ $review->user->name }}</p>
                                    <p class="text-xs text-gray-400 leading-tight">{{ $review->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            {{-- Rating badge --}}
                            <div class="flex items-center gap-1 bg-white rounded-xl px-2.5 py-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-xs font-semibold text-gray-800">{{ $review->rating }}.0</span>
                            </div>
                        </div>

                        {{-- Comment --}}
                        @if($review->comment)
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                        @else
                            <p class="text-xs text-gray-400 italic">Tidak ada komentar</p>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif

    </div>

    @push('scripts')
    @vite(['resources/js/pages/product-show.js'])
    @endpush

</x-app-layout>