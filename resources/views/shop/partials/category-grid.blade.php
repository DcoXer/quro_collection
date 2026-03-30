@if($products->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p>Produk tidak ditemukan.</p>
    </div>
@else
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="group cursor-pointer"
                onclick="openQuickView('{{ route('shop.quick-view', $product) }}', '{{ route('shop.show', $product) }}')">
                <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-3 relative">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs">No Image</div>
                    @endif
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300 flex items-center justify-center">
                        <span class="opacity-0 group-hover:opacity-100 transition bg-white text-gray-900 text-xs px-3 py-1.5 rounded-full font-medium">
                            Quick View
                        </span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mb-0.5">{{ $product->category?->name }}</p>
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

    <div class="mt-12">
        {{ $products->withQueryString()->links() }}
    </div>
@endif