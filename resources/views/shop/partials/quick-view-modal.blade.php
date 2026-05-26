{{-- Quick View Modal --}}
<div id="quick-view-modal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-end md:items-center justify-center p-4"
    onclick="if(event.target===this) closeQuickView()">

    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto modal-scroll">

        <div id="qv-loading" class="flex items-center justify-center py-20">
            <div class="w-8 h-8 border-2 border-gray-900 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <div id="qv-content" class="hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden">
                    <img id="qv-image" src="" class="w-full h-full object-cover">
                    <div id="qv-no-image" class="hidden w-full h-full flex items-center justify-center text-gray-300 text-sm">Tidak ada gambar</div>
                </div>

                <div class="flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <p id="qv-category" class="text-xs tracking-widest uppercase text-gray-400"></p>
                        <button onclick="closeQuickView()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
                    </div>

                    <h2 id="qv-name" style="font-family: 'Playfair Display', serif;"
                        class="text-2xl font-semibold text-gray-900 mb-2"></h2>
                    <div id="qv-price-wrap" class="mb-3 flex items-center gap-2">
                        <p id="qv-price" class="text-xl text-gray-900"></p>
                        <p id="qv-price-original" class="text-sm text-gray-400 line-through hidden"></p>
                    </div>
                    <p id="qv-desc" class="text-gray-500 text-sm leading-relaxed mb-4"></p>

                    @auth
                    <div class="mb-4">
                        <p class="text-xs text-gray-400 uppercase tracking-widest mb-2">Size</p>
                        <div id="qv-sizes" class="flex flex-wrap gap-2"></div>
                        <input type="hidden" id="qv-selected-size">
                    </div>

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

                    <div class="flex justify-between items-center py-3 border-t border-gray-100 mb-4">
                        <span class="text-sm text-gray-500">Total</span>
                        <span id="qv-total" class="text-lg font-semibold text-gray-900">Pilih size</span>
                    </div>

                    <div class="flex gap-2 mb-3">
                        <button id="qv-add-btn" onclick="qvSubmitCart()"
                            class="flex-1 bg-gray-900 text-white py-3 rounded-lg text-sm font-medium hover:bg-gray-700 transition flex items-center justify-center gap-2">
                            <svg id="qv-btn-spinner" class="hidden animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            <svg id="qv-btn-check" class="hidden w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span id="qv-btn-text">Tambah ke Keranjang</span>
                        </button>

                        {{-- Wishlist toggle --}}
                        <button id="qv-wishlist-btn" onclick="qvToggleWishlist()"
                            class="w-12 flex items-center justify-center border border-gray-200 rounded-lg hover:border-gray-900 transition shrink-0">
                            <svg id="qv-wishlist-icon" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>
                    @else
                    <a href="{{ route('login') }}"
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

{{-- Quick-view JS loaded by the parent page (shop/index or shop/category) via @vite quick-view.js --}}