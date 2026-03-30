<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8 md:py-12">

        <div class="mb-8">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-2">Akun Saya</p>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-3xl font-semibold text-gray-900">Riwayat Pesanan</h1>
        </div>

        @if($orders->isEmpty())
            <div class="text-center py-20">
                <p class="text-gray-300 text-5xl mb-4">🛍</p>
                <p class="text-gray-500 mb-2">Belum ada pesanan</p>
                <a href="{{ route('shop.index') }}"
                    class="text-sm text-gray-900 underline underline-offset-2">Mulai belanja</a>
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