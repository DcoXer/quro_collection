<x-app-layout>
    <div class="max-w-xl mx-auto px-4 py-8 md:py-12">

        <a href="{{ url()->previous(route('home')) }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 border border-gray-100 hover:border-gray-200 px-3 py-1.5 rounded-xl transition mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>

        <div class="mb-6">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-1">Notifikasi</p>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-xl font-semibold text-gray-900">Semua Notifikasi</h1>
        </div>

        {{-- Banner: Pesanan Menunggu Pembayaran --}}
        @if($pendingOrders->isNotEmpty())
            <div class="mb-6 space-y-3">
                @foreach($pendingOrders as $pending)
                    <a href="{{ route('checkout.payment', $pending->invoice_number) }}"
                        class="flex items-start gap-3 p-4 rounded-2xl border border-amber-200 bg-amber-50 hover:bg-amber-100 transition">
                        <div class="w-9 h-9 rounded-full bg-amber-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-amber-800">Menunggu Pembayaran</p>
                            <p class="text-xs text-amber-700 mt-0.5">
                                Pesanan <span class="font-medium">{{ $pending->invoice_number }}</span>
                                belum dibayar. Selesaikan sekarang sebelum kehabisan stok!
                            </p>
                            <p class="text-xs text-amber-600 mt-1">
                                Total: Rp {{ number_format($pending->total_amount, 0, ',', '.') }}
                                · {{ $pending->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-amber-500 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Notifikasi List --}}
        @if($notifications->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">

                {{-- Icon area --}}
                <div class="relative mb-8">
                    <div class="w-24 h-24 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    {{-- Decorative ring --}}
                    <div class="absolute inset-0 w-24 h-24 rounded-full border border-dashed border-gray-200 scale-125"></div>
                </div>

                <p class="text-xs tracking-widest uppercase text-gray-300 mb-3">Notifikasi</p>
                <h2 style="font-family: 'Playfair Display', serif;"
                    class="text-2xl font-semibold text-gray-900 mb-3">Belum Ada Notifikasi</h2>
                <p class="text-sm text-gray-400 leading-relaxed max-w-xs mb-8">
                    Kami akan memberitahu kamu tentang status pesanan, penawaran eksklusif, dan produk terbaru.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <a href="{{ route('shop.index') }}"
                        class="bg-gray-900 text-white px-8 py-3 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                        Lihat Koleksi
                    </a>
                    <a href="{{ route('orders.index') }}"
                        class="text-sm text-gray-400 hover:text-gray-700 transition">
                        Cek Pesanan Saya
                    </a>
                </div>

            </div>
        @else
            <div class="space-y-2">
                @foreach($notifications as $notif)
                    <a href="{{ route('notifications.read', $notif->id) }}"
                        class="flex items-start gap-3 p-4 rounded-2xl border transition
                            {{ $notif->read_at ? 'border-gray-100 bg-white' : 'border-gray-200 bg-gray-50' }}
                            hover:border-gray-300 hover:shadow-sm">

                        {{-- Icon per tipe --}}
                        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0
                            {{ $notif->read_at ? 'bg-gray-100' : 'bg-gray-900' }}">

                            @if($notif->type === 'cart')
                                <svg class="w-4 h-4 {{ $notif->read_at ? 'text-gray-400' : 'text-white' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>

                            @elseif($notif->type === 'order')
                                <svg class="w-4 h-4 {{ $notif->read_at ? 'text-gray-400' : 'text-white' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>

                            @elseif($notif->type === 'order_status')
                                <svg class="w-4 h-4 {{ $notif->read_at ? 'text-gray-400' : 'text-white' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>

                            @elseif($notif->type === 'new_product')
                                <svg class="w-4 h-4 {{ $notif->read_at ? 'text-gray-400' : 'text-white' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>

                            @else
                                <svg class="w-4 h-4 {{ $notif->read_at ? 'text-gray-400' : 'text-white' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            @endif

                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $notif->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>

                        @if(!$notif->read_at)
                            <div class="w-2 h-2 bg-gray-900 rounded-full mt-1.5 shrink-0"></div>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
