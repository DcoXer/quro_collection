<x-app-layout>
    <div class="max-w-lg mx-auto px-4 py-16 text-center">
        <div class="text-5xl mb-4">✅</div>
        <h1 class="text-2xl font-bold mb-2">Pesanan Berhasil!</h1>
        <p class="text-gray-500 mb-4">Nomor invoice: <span class="font-semibold">{{ $order->invoice_number }}</span></p>
        <p class="text-gray-500 mb-8">Total: <span class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
        <a href="{{ route('shop.index') }}" class="bg-black text-white px-8 py-3 rounded-lg">
            Lanjut Belanja
        </a>
    </div>
</x-app-layout>