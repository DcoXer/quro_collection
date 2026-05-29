@php
    $flashItems = \Illuminate\Support\Facades\Cache::remember('active_flash_sale_items', 60, fn() =>
        \App\Models\FlashSaleItem::whereHas('flashSale', fn($q) => $q->active())->get()
    )->keyBy('product_id');
    $flashProductIds = $flashItems->keys()->toArray();
    $isAuth = auth()->check();
@endphp

@if($products->isEmpty())
    <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="relative mb-6">
            <div class="w-20 h-20 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center">
                <svg class="w-9 h-9 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div class="absolute inset-0 w-20 h-20 rounded-full border border-dashed border-gray-200 scale-125"></div>
        </div>
        <p class="text-xs tracking-widest uppercase text-gray-300 mb-2">Hasil Pencarian</p>
        <h3 style="font-family: 'Playfair Display', serif;" class="text-xl font-semibold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
        <p class="text-sm text-gray-400 max-w-xs">Coba kata kunci lain atau jelajahi semua koleksi kami.</p>
        <a href="{{ route('shop.index') }}" class="mt-6 text-sm bg-gray-900 text-white px-6 py-2.5 rounded-xl hover:bg-gray-700 transition">
            Lihat Semua Produk
        </a>
    </div>
@else
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach($products as $product)
        <div class="group cursor-pointer"
            onclick="{{ $isAuth ? "openQuickView('".route('shop.quick-view', $product)."', '".route('shop.show', $product)."')" : "window.location.href='".route('shop.show', $product)."'" }}">

            {{-- Media Slider --}}
            <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-3 relative"
                data-slider="{{ $product->id }}"
                @php $slideCount = ($product->image ? 1 : 0) + $product->media->count(); @endphp
                @if($slideCount > 1) data-has-slides="true" @endif
                style="touch-action: pan-y;">

                @php $slideCount = ($product->image ? 1 : 0) + $product->media->count(); @endphp
                <div class="slides-track flex h-full will-change-transform"
                    id="track-{{ $product->id }}"
                    style="transition: transform 400ms cubic-bezier(0.25, 0.46, 0.45, 0.94);">

                    @if($product->image)
                    <div class="slide-item w-full h-full shrink-0 overflow-hidden">
                        <img src="{{ Storage::url($product->image) }}"
                            loading="lazy"
                            class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-105">
                    </div>
                    @endif

                    @foreach($product->media as $media)
                    <div class="slide-item w-full h-full shrink-0 overflow-hidden">
                        @if($media->type === 'video')
                        <video class="w-full h-full object-cover" muted loop playsinline>
                            <source src="{{ Storage::url($media->path) }}" type="video/mp4">
                        </video>
                        @else
                        <img src="{{ Storage::url($media->path) }}"
                            loading="lazy"
                            class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-105">
                        @endif
                    </div>
                    @endforeach

                </div>

                {{-- Flash Sale Badge --}}
                @if(in_array($product->id, $flashProductIds))
                <div class="absolute top-2 left-2 z-10">
                    <span class="inline-flex items-center gap-1 bg-amber-400 text-gray-950 text-[10px] font-bold px-2 py-0.5 rounded-md">
                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                        Flash
                    </span>
                </div>
                @endif

                {{-- Prev/Next --}}
                @if($product->media->count() > 0 || $product->image)
                <button onclick="event.stopPropagation(); slideCard({{ $product->id }}, -1)"
                    class="absolute left-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 backdrop-blur-sm rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 ease-out hover:bg-white hover:scale-110 z-10">
                    ‹
                </button>
                <button onclick="event.stopPropagation(); slideCard({{ $product->id }}, 1)"
                    class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 backdrop-blur-sm rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 ease-out hover:bg-white hover:scale-110 z-10">
                    ›
                </button>
                @endif
            </div>

            <p class="text-xs text-gray-400 mb-0.5">{{ $product->category?->name }}</p>
            <p class="text-sm font-medium text-gray-900 group-hover:text-gray-600 transition">{{ $product->name }}</p>
            <div class="flex items-center justify-between mt-0.5">
                @php
                    $flashItem = $flashItems[$product->id] ?? null;
                    $flashPrice = null;
                    if ($flashItem) {
                        $flashPrice = $flashItem->discount_type === 'percent'
                            ? $product->price - ($product->price * $flashItem->discount_value / 100)
                            : $product->price - $flashItem->discount_value;
                        $flashPrice = max(0, (int) $flashPrice);
                    }
                @endphp
                @if($flashPrice !== null)
                <div class="flex items-center gap-1.5">
                    <p class="text-sm font-semibold text-amber-600">Rp {{ number_format($flashPrice, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
                @else
                <p class="text-sm text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                @endif
                @auth
                @php $wishlisted = in_array($product->id, $wishlistedIds ?? []); @endphp
                <button type="button"
                    x-data="{ on: {{ $wishlisted ? 'true' : 'false' }} }"
                    @click.stop="
                        fetch('{{ route('wishlist.toggle', $product->id) }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        }).then(r => r.json()).then(d => { on = d.in_wishlist; window.dispatchEvent(new CustomEvent('activity-updated')); })
                    "
                    :class="on ? 'text-red-500 hover:text-red-600' : 'text-gray-400 hover:text-red-500'"
                    class="w-6 h-6 flex items-center justify-center transition">
                    <svg class="w-4 h-4" :fill="on ? 'currentColor' : 'none'"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
                @endauth
            </div>
            @if($product->review_count > 0)
            <div class="flex items-center gap-1 mt-1">
                <div class="flex items-center gap-px">
                    @php $avg = round($product->avg_rating); @endphp
                    @for($s = 1; $s <= 5; $s++)
                    <svg class="w-3 h-3 {{ $s <= $avg ? 'text-yellow-400' : 'text-gray-200' }}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <span class="text-xs text-gray-400">({{ $product->review_count }})</span>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="mt-12 flex justify-center gap-1" id="pagination-links">
        @if($products->onFirstPage())
        <span class="px-3 py-2 text-sm text-gray-300 border border-gray-100 rounded-lg cursor-not-allowed">‹</span>
        @else
        <button class="pagination-link px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:border-gray-900 hover:text-gray-900 transition"
            data-page="{{ $products->currentPage() - 1 }}">‹</button>
        @endif

        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            @if($page == $products->currentPage())
            <span class="px-3 py-2 text-sm font-medium bg-gray-900 text-white border border-gray-900 rounded-lg">{{ $page }}</span>
            @else
            <button class="pagination-link px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:border-gray-900 hover:text-gray-900 transition"
                data-page="{{ $page }}">{{ $page }}</button>
            @endif
        @endforeach

        @if($products->hasMorePages())
        <button class="pagination-link px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:border-gray-900 hover:text-gray-900 transition"
            data-page="{{ $products->currentPage() + 1 }}">›</button>
        @else
        <span class="px-3 py-2 text-sm text-gray-300 border border-gray-100 rounded-lg cursor-not-allowed">›</span>
        @endif
    </div>
    @endif
@endif
