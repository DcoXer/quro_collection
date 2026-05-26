<x-app-layout>

@push('seo')
<title>Pesanan Saya — Quro Collection</title>
<meta name="robots" content="noindex, nofollow">
@endpush

    <div class="max-w-2xl mx-auto px-4 py-8 md:py-12">

        <div class="mb-8">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-2">{{ auth()->user()->name }}</p>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-3xl font-semibold text-gray-900">Pesanan Saya</h1>
        </div>

        @if($orders->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">

                {{-- Icon area --}}
                <div class="relative mb-8">
                    <div class="w-24 h-24 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    {{-- Decorative ring --}}
                    <div class="absolute inset-0 w-24 h-24 rounded-full border border-dashed border-gray-200 scale-125"></div>
                </div>

                <p class="text-xs tracking-widest uppercase text-gray-300 mb-3">Riwayat Pesanan</p>
                <h2 style="font-family: 'Playfair Display', serif;"
                    class="text-2xl font-semibold text-gray-900 mb-3">Belum Ada Pesanan</h2>
                <p class="text-sm text-gray-400 leading-relaxed max-w-xs mb-8">
                    Kamu belum pernah melakukan pembelian. Temukan koleksi terbaik kami dan mulai belanja sekarang.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <a href="{{ route('shop.index') }}"
                        class="bg-gray-900 text-white px-8 py-3 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                        Mulai Belanja
                    </a>
                    <a href="{{ route('home') }}"
                        class="text-sm text-gray-400 hover:text-gray-700 transition">
                        Kembali ke Beranda
                    </a>
                </div>

            </div>
        @else
            <div class="space-y-3">
                @foreach($orders as $order)
                    <a href="{{ route('orders.show', $order->invoice_number) }}"
                        class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl hover:border-gray-300 hover:shadow-sm transition bg-white">

                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->invoice_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>

                        <div class="text-right flex flex-col items-end gap-1.5">
                            <p class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </p>
                            <span @class([
                                'text-xs px-2.5 py-1 rounded-full font-medium',
                                'bg-yellow-50 text-yellow-600'  => $order->status === 'pending',
                                'bg-blue-50 text-blue-600'      => $order->status === 'paid',
                                'bg-purple-50 text-purple-600'  => $order->status === 'processing',
                                'bg-indigo-50 text-indigo-600'  => $order->status === 'shipped',
                                'bg-green-50 text-green-600'    => $order->status === 'delivered',
                                'bg-red-50 text-red-500'        => $order->status === 'cancelled',
                            ])>{{ match($order->status) {
                                'pending'    => 'Menunggu Pembayaran',
                                'processing' => 'Diproses',
                                'shipped'    => 'Dikirim',
                                'delivered'  => 'Selesai',
                                'cancelled'  => 'Dibatalkan',
                                default      => ucfirst($order->status),
                            } }}</span>
                        </div>

                    </a>
                @endforeach
            </div>

            <div class="mt-8 flex items-center justify-between gap-3 flex-wrap">
                <p class="text-xs text-gray-400">
                    <span class="font-medium text-gray-700">{{ $orders->total() }}</span> pesanan ·
                    Halaman <span class="font-medium text-gray-700">{{ $orders->currentPage() }}</span>
                    dari <span class="font-medium text-gray-700">{{ $orders->lastPage() }}</span>
                </p>

                <form id="orders-perpage-form" method="GET" action="{{ route('orders.index') }}">
                    <input type="hidden" name="per_page" id="orders-perpage-input" value="{{ $perPage }}">
                </form>

                <div x-data="{
                        open: false,
                        val: {{ $perPage }},
                        options: [10, 25, 50],
                        pick(n) { this.val = n; this.open = false; document.getElementById('orders-perpage-input').value = n; document.getElementById('orders-perpage-form').submit(); }
                    }"
                    @click.outside="open = false"
                    class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-2 text-xs border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-600 hover:border-gray-400 transition-colors">
                        <span x-text="val + ' per halaman'"></span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                        class="absolute right-0 top-full mt-2 w-36 bg-white border border-gray-100 rounded-2xl shadow-xl shadow-gray-200/70 py-1.5 z-30 origin-top-right">
                        <template x-for="n in options" :key="n">
                            <button @click="pick(n)"
                                class="w-full flex items-center justify-between px-4 py-2 text-xs transition-colors hover:bg-gray-50 rounded-xl mx-auto"
                                style="width: calc(100% - 8px); margin-left: 4px;"
                                :class="val === n ? 'text-gray-900 font-semibold' : 'text-gray-500'">
                                <span x-text="n + ' per halaman'"></span>
                                <svg x-show="val === n" class="w-3 h-3 text-gray-900 shrink-0"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            @if($orders->hasPages())
            <div class="mt-4 flex justify-center gap-1">
                {{-- Prev --}}
                @if($orders->onFirstPage())
                    <span class="px-3 py-2 text-sm text-gray-300 border border-gray-100 rounded-lg cursor-not-allowed">‹</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}"
                        class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:border-gray-900 hover:text-gray-900 transition">‹</a>
                @endif

                {{-- Pages --}}
                @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if($page == $orders->currentPage())
                        <span class="px-3 py-2 text-sm font-medium bg-gray-900 text-white border border-gray-900 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:border-gray-900 hover:text-gray-900 transition">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}"
                        class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:border-gray-900 hover:text-gray-900 transition">›</a>
                @else
                    <span class="px-3 py-2 text-sm text-gray-300 border border-gray-100 rounded-lg cursor-not-allowed">›</span>
                @endif
            </div>
            @endif
        @endif
    </div>
</x-app-layout>