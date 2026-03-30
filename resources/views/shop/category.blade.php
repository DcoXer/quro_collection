<x-app-layout>
    {{-- Header --}}
    <section class="border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-6 py-12">
            <a href="{{ route('shop.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 border border-gray-100 hover:border-gray-200 px-3 py-1.5 rounded-xl transition mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Semua Koleksi
            </a>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-4xl font-semibold text-gray-900">
                {{ $category->name }}
            </h1>
            <p class="text-gray-400 text-sm mt-2">{{ $products->total() }} produk</p>
        </div>
    </section>

    {{-- Search --}}
    <section class="max-w-6xl mx-auto px-6 py-6">
        <div class="flex gap-3 items-center">
            <input type="text" id="search-input" value="{{ request('search') }}"
                placeholder="Cari di {{ $category->name }}..."
                class="border border-gray-200 rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:border-gray-400">
            <button id="reset-filter" class="text-sm text-gray-400 hover:text-gray-600 {{ request('search') ? '' : 'hidden' }}">
                Reset
            </button>
        </div>
    </section>

    {{-- Grid --}}
    <section class="max-w-6xl mx-auto px-6 pb-20">
        <div id="product-grid" data-search-url="{{ route('shop.category', $category) }}">
            @include('shop.partials.category-grid', ['products' => $products])
        </div>
    </section>
@include('shop.partials.quick-view-modal')
@push('scripts')
@vite(['resources/js/pages/shop-category.js', 'resources/js/pages/quick-view.js'])
@endpush

</x-app-layout>