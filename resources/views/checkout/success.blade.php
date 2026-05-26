<x-app-layout>

@push('seo')
<title>Pesanan Berhasil — Quro Collection</title>
<meta name="robots" content="noindex, nofollow">
@endpush

<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-1">Pembayaran Diterima</p>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-3xl font-semibold text-gray-900">
                Pesanan Berhasil!
            </h1>
            <p class="text-sm text-gray-400 mt-2">Terima kasih sudah berbelanja di Quro Collection</p>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm">

            {{-- Order Info --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs tracking-widest uppercase text-gray-400">Detail Pesanan</p>
                    <span @class([
                        'text-xs px-2.5 py-1 rounded-full font-medium',
                        'bg-yellow-50 text-yellow-600' => $order->status === 'pending',
                        'bg-purple-50 text-purple-600' => $order->status === 'processing',
                        'bg-indigo-50 text-indigo-600' => $order->status === 'shipped',
                        'bg-green-50 text-green-600'   => $order->status === 'delivered',
                        'bg-red-50 text-red-500'       => $order->status === 'cancelled',
                    ])>
                        {{ match($order->status) {
                            'pending'    => 'Menunggu Pembayaran',
                            'processing' => 'Sedang Diproses',
                            'shipped'    => 'Dikirim',
                            'delivered'  => 'Selesai',
                            'cancelled'  => 'Dibatalkan',
                            default      => ucfirst($order->status),
                        } }}
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
                        <span class="text-gray-400">Alamat</span>
                        <span class="font-medium text-gray-900 text-right max-w-[60%]">{{ $order->shipping_address }}</span>
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
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-green-600">
                                Diskon Voucher
                                @if($order->voucher_code)
                                    <span class="text-xs bg-green-100 text-green-700 px-1.5 py-0.5 rounded font-mono ml-1">{{ $order->voucher_code }}</span>
                                @endif
                            </span>
                            <span class="font-medium text-green-600">− Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
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

            {{-- Actions --}}
            <div class="p-6 space-y-3">
                <a href="{{ route('orders.show', $order->invoice_number) }}"
                    class="w-full bg-gray-900 text-white py-4 rounded-2xl text-sm font-medium hover:bg-gray-700 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Lihat Detail Pesanan
                </a>
                <a href="{{ route('shop.index') }}"
                    class="block w-full text-center text-sm text-gray-400 hover:text-gray-600 transition">
                    Lanjut Belanja
                </a>
            </div>

        </div>

        {{-- Info --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            Konfirmasi pesanan telah dikirim ke email kamu
        </p>

    </div>
</div>

</x-app-layout>
