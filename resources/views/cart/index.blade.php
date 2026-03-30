<x-app-layout>

@push('seo')
<title>Keranjang — Quro Collection</title>
@endpush

<div class="max-w-5xl mx-auto px-4 py-8 md:py-12">

    <div class="mb-8">
        <p class="text-xs tracking-widest uppercase text-gray-400 mb-2">Belanja</p>
        <h1 style="font-family: 'Playfair Display', serif;"
            class="text-3xl font-semibold text-gray-900">Keranjang</h1>
    </div>

    @if(empty($cart))
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 style="font-family: 'Playfair Display', serif;"
                class="text-xl font-semibold text-gray-900 mb-2">Keranjang Kosong</h2>
            <p class="text-sm text-gray-400 mb-8 max-w-xs">Belum ada produk di keranjang kamu. Yuk mulai belanja!</p>
            <a href="{{ route('shop.index') }}"
                class="bg-gray-900 text-white px-8 py-3 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                Mulai Belanja
            </a>
        </div>

    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Item List --}}
            <div class="lg:col-span-2 space-y-3">

                {{-- Header --}}
                <div class="hidden md:grid grid-cols-12 gap-4 px-4 pb-2 text-xs uppercase tracking-widest text-gray-400">
                    <div class="col-span-6">Produk</div>
                    <div class="col-span-2 text-center">Size</div>
                    <div class="col-span-2 text-center">Jumlah</div>
                    <div class="col-span-2 text-right">Subtotal</div>
                </div>

                @foreach($cart as $key => $item)
                    <div class="item-row grid grid-cols-12 gap-4 items-center p-4 bg-white border border-gray-100 rounded-2xl hover:border-gray-200 hover:shadow-sm transition">

                        {{-- Image + Name --}}
                        <div class="col-span-12 md:col-span-6 flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden shrink-0">
                                @if($item['image'])
                                    <img src="{{ Storage::url($item['image']) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5" data-price="{{ $item['price'] }}">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </p>
                                {{-- Remove button mobile --}}
                                <form method="POST" action="{{ route('cart.remove', $key) }}" class="md:hidden mt-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Size --}}
                        <div class="col-span-4 md:col-span-2 flex justify-start md:justify-center">
                            @php
                                $cartProduct = \App\Models\Product::with('variants')->find($item['product_id']);
                            @endphp
                            @if($cartProduct)
                                <form method="POST" action="{{ route('cart.change-size', $key) }}">
                                    @csrf @method('PATCH')
                                    <select name="size" onchange="this.form.submit()"
                                        class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-gray-400 bg-white text-gray-700">
                                        @foreach(['S','M','L','XL'] as $size)
                                            @if($cartProduct->stock > 0)
                                                <option value="{{ $size }}" {{ $item['size'] === $size ? 'selected' : '' }}>{{ $size }}</option>
                                            @endif
                                        @endforeach
                                        @foreach($cartProduct->variants as $variant)
                                            @if($variant->stock > 0)
                                                <option value="{{ $variant->size }}" {{ $item['size'] === $variant->size ? 'selected' : '' }}>{{ $variant->size }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">{{ $item['size'] ?? '-' }}</span>
                            @endif
                        </div>

                        {{-- Qty --}}
                        <div class="col-span-4 md:col-span-2 flex justify-center">
                            <form method="POST" action="{{ route('cart.update', $key) }}"
                                class="flex items-center gap-1.5 bg-gray-50 rounded-xl p-1">
                                @csrf @method('PATCH')
                                <button type="button" onclick="changeQty(this, -1)"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-500 hover:bg-white hover:shadow-sm transition text-lg leading-none">−</button>
                                <span class="qty-display text-sm font-medium w-7 text-center text-gray-900">{{ $item['quantity'] }}</span>
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] }}">
                                <button type="button" onclick="changeQty(this, 1)"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-500 hover:bg-white hover:shadow-sm transition text-lg leading-none">+</button>
                            </form>
                        </div>

                        {{-- Subtotal + Remove --}}
                        <div class="col-span-4 md:col-span-2 flex items-center justify-end gap-3">
                            <p class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </p>
                            <form method="POST" action="{{ route('cart.remove', $key) }}" class="hidden md:block">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-400 hover:bg-red-50 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach

                {{-- Continue Shopping --}}
                <a href="{{ route('shop.index') }}"
                    class="flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition mt-2 px-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Lanjut Belanja
                </a>
            </div>

            {{-- Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 sticky top-24">

                    <p style="font-family: 'Playfair Display', serif;"
                        class="text-lg font-semibold text-gray-900 mb-5">Ringkasan</p>

                    {{-- Items --}}
                    <div class="space-y-2 mb-5">
                        @foreach($cart as $item)
                            <div class="flex justify-between text-xs text-gray-400">
                                <span class="truncate max-w-[140px]">
                                    {{ $item['name'] }}
                                    <span class="text-gray-300">({{ $item['size'] ?? '-' }})</span>
                                    ×{{ $item['quantity'] }}
                                </span>
                                <span id="cart-total" class="shrink-0 ml-2 font-medium text-gray-600">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-gray-50 pt-4 mb-5">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Total</span>
                            <span style="font-family: 'Playfair Display', serif;"
                                id="final-total"
                                class="text-2xl font-semibold text-gray-900">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mt-4 font-extralight">Ingin dapat potongan harga? Gunakan voucher saat checkout.</p>
                    </div>

                    {{-- CTA --}}
                    <a href="{{ route('checkout.index') }}"
                        class="flex items-center justify-center gap-2 w-full bg-gray-900 text-white py-3.5 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Checkout
                    </a>

                    {{-- Trust badges --}}
                    <div class="mt-4 pt-4 border-t border-gray-50 space-y-2">
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Pembayaran aman & terenkripsi
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Gratis pengembalian 7 hari
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <svg class="w-3.5 h-3.5 text-yellow-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Garansi kualitas produk
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <svg class="w-3.5 h-3.5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M3.597 7.348a3 3 0 0 0 0 5.304 3 3 0 0 0 3.75 3.751 3 3 0 0 0 5.305 0 3 3 0 0 0 3.751-3.75 3 3 0 0 0 0-5.305 3 3 0 0 0-3.75-3.751 3 3 0 0 0-5.305 0 3 3 0 0 0-3.751 3.75Zm9.933.182a.75.75 0 0 0-1.06-1.06l-6 6a.75.75 0 1 0 1.06 1.06l6-6Zm.47 5.22a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0ZM7.25 8.5a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5Z" clip-rule="evenodd" />
                            </svg>
                            Diskon dan kode promo? Pantengin terus media sosial kami !
                        </div>
                    </div>

                </div>
            </div>

        </div>
    @endif
</div>

@push('scripts')
@vite(['resources/js/pages/cart.js'])
@endpush

</x-app-layout>