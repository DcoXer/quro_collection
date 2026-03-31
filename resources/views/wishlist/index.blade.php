<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 md:py-12">

        <a href="{{ route('shop.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 border border-gray-100 hover:border-gray-200 px-3 py-1.5 rounded-xl transition mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Semua Koleksi
        </a>

        <div class="mb-6">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-1">{{ auth()->user()->name }}</p>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-xl font-semibold text-gray-900">My Favorites</h1>
        </div>

        @if(session('success'))
            <div class="mb-4 text-xs text-green-600 bg-green-50 border border-green-100 rounded-xl px-3 py-2">
                {{ session('success') }}
            </div>
        @endif

        @if($wishlists->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">

                {{-- Icon area --}}
                <div class="relative mb-8">
                    <div class="w-24 h-24 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    {{-- Decorative ring --}}
                    <div class="absolute inset-0 w-24 h-24 rounded-full border border-dashed border-gray-200 scale-125"></div>
                </div>

                <p class="text-xs tracking-widest uppercase text-gray-300 mb-3">Wishlist</p>
                <h2 style="font-family: 'Playfair Display', serif;"
                    class="text-2xl font-semibold text-gray-900 mb-3">Wishlist Masih Kosong</h2>
                <p class="text-sm text-gray-400 leading-relaxed max-w-xs mb-8">
                    Simpan produk favoritmu di sini agar mudah ditemukan nanti. Ketuk ikon hati di halaman produk untuk menambahkan.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <a href="{{ route('shop.index') }}"
                        class="bg-gray-900 text-white px-8 py-3 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                        Jelajahi Koleksi
                    </a>
                    <a href="{{ route('home') }}"
                        class="text-sm text-gray-400 hover:text-gray-700 transition">
                        Kembali ke Beranda
                    </a>
                </div>

            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($wishlists as $item)
                    @if($item->product)
                        <div class="group relative">
                            <a href="{{ route('shop.show', $item->product) }}" class="block">
                                <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-3">
                                    @if($item->product->image)
                                        <img src="{{ Storage::url($item->product->image) }}"
                                            alt="{{ $item->product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-200 text-xs">No Image</div>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mb-0.5">{{ $item->product->category?->name }}</p>
                                <p class="text-sm font-medium text-gray-900 group-hover:text-gray-600 transition">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-700 mt-0.5">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                            </a>

                            {{-- Hapus dari wishlist --}}
                            <form method="POST" action="{{ route('wishlist.destroy', $item->id) }}"
                                class="absolute top-2 right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 bg-white rounded-full shadow flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-red-50">
                                    <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
