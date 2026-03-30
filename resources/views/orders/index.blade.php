<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8 md:py-12">

        <div class="mb-8">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-2">Akun Saya</p>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-3xl font-semibold text-gray-900">Riwayat Pesanan</h1>
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
                            ])>{{ ucfirst($order->status) }}</span>
                        </div>

                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>