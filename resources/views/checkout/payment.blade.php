<x-app-layout>

@push('seo')
<title>Pembayaran — Quro Collection</title>
@endpush

<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-1">Langkah Terakhir</p>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-3xl font-semibold text-gray-900">
                Selesaikan Pembayaran
            </h1>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm">

            {{-- Order Info --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs tracking-widest uppercase text-gray-400">Detail Pesanan</p>
                    <span class="text-xs bg-yellow-50 text-yellow-600 px-2.5 py-1 rounded-full font-medium">
                        Menunggu Pembayaran
                    </span>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Invoice</span>
                        <span class="font-medium text-gray-900">{{ $order->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Penerima</span>
                        <span class="font-medium text-gray-900">{{ $order->shipping_name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Tanggal</span>
                        <span class="font-medium text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Item Pesanan</p>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">
                                {{ $item->product->name ?? 'Produk' }}
                                @if($item->size) <span class="text-gray-400">({{ $item->size }})</span> @endif
                                × {{ $item->quantity }}
                            </span>
                            <span class="font-medium text-gray-900">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                    @if($order->shipping_cost > 0)
                        <div class="border-t border-gray-200 pt-2 mt-2 flex justify-between text-sm">
                            <span class="text-gray-400">Ongkos Kirim</span>
                            <span class="font-medium text-gray-900">
                                Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Total --}}
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Pembayaran</span>
                    <span style="font-family: 'Playfair Display', serif;"
                        class="text-2xl font-semibold text-gray-900">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Action --}}
            <div class="p-6">
                <button id="pay-button"
                    data-token="{{ $order->payment_token }}"
                    data-success-url="{{ route('checkout.success', $order->invoice_number) }}"
                    data-pending-url="{{ route('orders.index') }}"
                    class="w-full bg-gray-900 text-white py-4 rounded-2xl text-sm font-medium hover:bg-gray-700 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Bayar Sekarang
                </button>

                <a href="{{ route('orders.index') }}"
                    class="block w-full text-center text-sm text-gray-400 hover:text-gray-600 transition mt-3">
                    Bayar Nanti
                </a>

                <p class="text-center text-xs text-gray-300 mt-4">
                    Pembayaran diproses secara aman oleh Midtrans
                </p>
            </div>

        </div>

        {{-- Security Badge --}}
        <div class="flex items-center justify-center gap-2 mt-6 text-xs text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            SSL Encrypted · Powered by Midtrans
        </div>

    </div>
</div>

@push('scripts')
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ $clientKey }}"></script>
@vite(['resources/js/pages/payment.js'])
@endpush

</x-app-layout>