@php
    $flashProductIds = \App\Models\FlashSaleItem::whereHas('flashSale', fn($q) => $q->active())
        ->pluck('product_id')
        ->toArray();
@endphp
@if($categories->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p class="text-lg">Produk tidak ditemukan.</p>
    </div>
@else
    <div class="space-y-16">
        @foreach($categories as $category)
            <div>
                {{-- Category Header --}}
                <div class="flex items-center gap-4 mb-6">
                    <h2 style="font-family: 'Playfair Display', serif;"
                        class="text-2xl font-semibold text-gray-900">
                        {{ $category->name }}
                    </h2>
                    <div class="flex-1 h-px bg-gray-100"></div>
                    <span class="text-xs text-gray-400">{{ $category->products->count() }} produk</span>
                </div>

                {{-- Product Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($category->products as $product)
                        <div class="group cursor-pointer"
                            onclick="openQuickView('{{ route('shop.quick-view', $product) }}', '{{ route('shop.show', $product) }}')">

                            {{-- Media Slider --}}
                            <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-3 relative"
                                data-slider="{{ $product->id }}">

                                {{-- Slides --}}
                                <div class="slides-track flex h-full transition-transform duration-500"
                                    id="track-{{ $product->id }}"
                                    @if($product->media->count() > 0) data-auto-slide="{{ $product->id }}" @endif>

                                    {{-- Foto utama --}}
                                    @if($product->image)
                                        <div class="slide-item w-full h-full shrink-0">
                                            <img src="{{ Storage::url($product->image) }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                    @endif

                                    {{-- Media tambahan --}}
                                    @foreach($product->media as $media)
                                        <div class="slide-item w-full h-full shrink-0">
                                            @if($media->type === 'video')
                                                <video class="w-full h-full object-cover" muted loop playsinline>
                                                    <source src="{{ Storage::url($media->path) }}" type="video/mp4">
                                                </video>
                                            @else
                                                <img src="{{ Storage::url($media->path) }}"
                                                    class="w-full h-full object-cover">
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
                                        class="absolute left-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition z-10">
                                        ‹
                                    </button>
                                    <button onclick="event.stopPropagation(); slideCard({{ $product->id }}, 1)"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition z-10">
                                        ›
                                    </button>
                                @endif

                                {{-- Quick View overlay --}}
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300 flex items-end justify-center pb-3">
                                    <span class="opacity-0 group-hover:opacity-100 transition bg-white text-gray-900 text-xs px-3 py-1.5 rounded-full font-medium">
                                        Quick View
                                    </span>
                                </div>

                            </div>

                            <p class="text-xs text-gray-400 mb-0.5">{{ $category->name }}</p>
                            <p class="text-sm font-medium text-gray-900 group-hover:text-gray-600 transition">{{ $product->name }}</p>
                            <div class="flex items-center justify-between mt-0.5">
                                <p class="text-sm text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                @auth
                                    @php $wishlisted = in_array($product->id, $wishlistedIds ?? []); @endphp
                                    <button type="button"
                                        x-data="{ on: {{ $wishlisted ? 'true' : 'false' }} }"
                                        @click.stop="
                                            fetch('{{ route('wishlist.toggle', $product->id) }}', {
                                                method: 'POST',
                                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                            }).then(r => r.json()).then(d => on = d.in_wishlist)
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
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 {{ $i <= $avg ? 'text-yellow-400' : 'text-gray-200' }}"
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
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('shop.category', $category) }}"
                        class="group inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 transition">
                        <span class="relative">
                            Lihat semua Koleksi
                            <span class="absolute left-0 -bottom-px h-px w-0 bg-gray-900 group-hover:w-full transition-all duration-300"></span>
                        </span>
                        <svg class="w-3.5 h-3.5 -translate-x-1 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif