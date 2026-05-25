<x-app-layout>
    <div class="max-w-xl mx-auto px-4 py-8 md:py-12">

        <a href="{{ route('orders.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 border border-gray-100 hover:border-gray-200 px-3 py-1.5 rounded-xl transition mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Pesanan Saya
        </a>

        {{-- Header --}}
        <div class="mb-6">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-1">Invoice</p>
            <div class="flex items-center justify-between">
                <h1 style="font-family: 'Playfair Display', serif;"
                    class="text-xl font-semibold text-gray-900">{{ $order->invoice_number }}</h1>
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
            <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>

        {{-- Shipping --}}
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Pengiriman</p>
            <p class="font-medium text-gray-900 text-sm">{{ $order->shipping_name }}</p>
            <p class="text-sm text-gray-500 mt-0.5">{{ $order->shipping_phone }}</p>
            <p class="text-sm text-gray-500 mt-0.5 leading-relaxed">{{ $order->shipping_address }}</p>
        </div>

        {{-- Timeline Status --}}
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-4">Status Pesanan</p>

            @php
                $statuses = [
                    'pending'    => ['label' => 'Pesanan Diterima',   'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    'processing' => ['label' => 'Pembayaran Dikonfirmasi & Diproses', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    'shipped'    => ['label' => 'Dalam Pengiriman',   'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                    'delivered'  => ['label' => 'Pesanan Diterima',   'icon' => 'M5 13l4 4L19 7'],
                    'cancelled'  => ['label' => 'Pesanan Dibatalkan', 'icon' => 'M6 18L18 6M6 6l12 12'],
                ];
                $order_flow    = ['pending', 'processing', 'shipped', 'delivered'];
                $current_index = array_search($order->status, $order_flow);
                $is_cancelled  = $order->status === 'cancelled';
            @endphp

            @if($is_cancelled)
                <div class="flex items-center gap-3 text-red-400">
                    <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-red-500">Pesanan Dibatalkan</p>
                        <p class="text-xs text-gray-400">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            @else
                <div class="relative">
                    @foreach($order_flow as $index => $status)
                        @php
                            $isDone    = $current_index !== false && $index <= $current_index;
                            $isCurrent = $current_index !== false && $index === $current_index;
                            $isLast    = $index === count($order_flow) - 1;
                        @endphp

                        <div class="flex gap-4 {{ !$isLast ? 'mb-4' : '' }}">

                            {{-- Icon + Line --}}
                            <div class="flex flex-col items-center">
                                <div @class([
                                    'w-9 h-9 rounded-full flex items-center justify-center text-sm shrink-0 transition',
                                    'bg-gray-900 text-white'  => $isDone,
                                    'bg-gray-100 text-gray-300' => !$isDone,
                                ])>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $statuses[$status]['icon'] }}"/>
                                    </svg>
                                </div>
                                @if(!$isLast)
                                    <div @class([
                                        'w-0.5 h-8 mt-1',
                                        'bg-gray-900' => $isDone && $current_index > $index,
                                        'bg-gray-100' => !($isDone && $current_index > $index),
                                    ])></div>
                                @endif
                            </div>

                            {{-- Label --}}
                            <div class="pt-1.5">
                                <p @class([
                                    'text-sm font-medium',
                                    'text-gray-900' => $isDone,
                                    'text-gray-300' => !$isDone,
                                ])>
                                    {{ $statuses[$status]['label'] }}
                                </p>
                                @if($isCurrent)
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $order->updated_at->format('d M Y, H:i') }}
                                    </p>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tracking Paket --}}
        @if($order->tracking_number && $order->courier)
        {{-- Resi + courier sudah ada → live tracking --}}
        <div id="tracking-section"
            data-url="{{ route('orders.track', $order->invoice_number) }}"
            class="border border-gray-100 rounded-2xl p-4 mb-4">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs tracking-widest uppercase text-gray-400">Lacak Paket</p>
                <div class="flex items-center gap-2">
                    <span class="text-xs bg-gray-50 text-gray-600 px-2 py-1 rounded-lg font-medium uppercase">
                        {{ $order->courier }}
                    </span>
                    <span class="text-xs text-gray-500 font-mono">{{ $order->tracking_number }}</span>
                </div>
            </div>

            {{-- Loading --}}
            <div id="tracking-loading" class="flex items-center justify-center py-8">
                <div class="w-6 h-6 border-2 border-gray-900 border-t-transparent rounded-full animate-spin"></div>
            </div>

            {{-- Result --}}
            <div id="tracking-result" class="hidden"></div>

            {{-- Error --}}
            <div id="tracking-error" class="hidden text-center py-6">
                <p class="text-sm text-gray-400">Gagal memuat data tracking.</p>
                <button onclick="loadTracking()" class="text-xs text-gray-900 underline mt-2">Coba lagi</button>
            </div>
        </div>
        @elseif($order->tracking_number)
        {{-- Resi sudah ada (auto-generated), tampilkan dengan pesan sesuai status --}}
        @php
            $resiStatusMsg = match($order->status) {
                'processing' => ['dot' => 'bg-amber-400', 'text' => 'Pesanan sedang disiapkan oleh penjual.'],
                'shipped'    => ['dot' => 'bg-blue-400',  'text' => 'Paket sedang dalam pengiriman.'],
                'delivered'  => ['dot' => 'bg-green-400', 'text' => 'Paket telah diterima.'],
                default      => ['dot' => 'bg-gray-300',  'text' => ''],
            };
        @endphp
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs tracking-widest uppercase text-gray-400">Nomor Resi</p>
                <div class="flex items-center gap-1.5 bg-gray-50 border border-gray-100 rounded-xl px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                    </svg>
                    <span class="text-sm font-mono font-semibold text-gray-800 tracking-wide">{{ $order->tracking_number }}</span>
                </div>
            </div>
            @if($resiStatusMsg['text'])
            <div class="flex items-center gap-2 mt-1">
                <div class="w-1.5 h-1.5 rounded-full {{ $resiStatusMsg['dot'] }} {{ $order->status !== 'delivered' ? 'animate-pulse' : '' }} shrink-0"></div>
                <p class="text-xs text-gray-400">{{ $resiStatusMsg['text'] }}</p>
            </div>
            @endif
        </div>
        @elseif($order->status === 'processing')
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-2">Nomor Resi</p>
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse shrink-0"></div>
                <p class="text-sm text-gray-400">Pesanan sedang disiapkan. Nomor resi akan muncul setelah paket dikirim.</p>
            </div>
        </div>
        @endif

        {{-- Items --}}
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Item Pesanan</p>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $item->product->name ?? 'Produk dihapus' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $item->size }} x{{ $item->quantity }}
                            </p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Ulasan Produk (hanya tampil jika delivered) --}}
        @if($order->status === 'delivered')
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-4">Ulasan Produk</p>

            @if(session('success'))
                <div class="mb-4 text-xs text-green-600 bg-green-50 border border-green-100 rounded-xl px-3 py-2">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-6">
                @foreach($order->items as $item)
                    @if($item->product)
                        @php $existing = $existingReviews[$item->product_id] ?? null; @endphp
                        <div class="pb-6 border-b border-gray-50 last:border-0 last:pb-0">

                            {{-- Info Produk --}}
                            <div class="flex items-center gap-3 mb-3">
                                @if($item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}"
                                        alt="{{ $item->product->name }}"
                                        class="w-10 h-10 rounded-xl object-cover shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 shrink-0"></div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $item->size }}</p>
                                </div>
                            </div>

                            @if($existing)
                                {{-- Sudah ada review — tampilkan --}}
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <div class="flex items-center gap-1 mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $existing->rating ? 'text-yellow-400' : 'text-gray-200' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="text-xs text-gray-400 ml-1">{{ $existing->created_at->format('d M Y') }}</span>
                                    </div>
                                    @if($existing->comment)
                                        <p class="text-sm text-gray-600">{{ $existing->comment }}</p>
                                    @endif
                                    <form method="POST" action="{{ route('reviews.destroy', $existing->id) }}" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs text-red-400 hover:text-red-600 transition">
                                            Hapus ulasan
                                        </button>
                                    </form>
                                </div>
                            @else
                                {{-- Form beri ulasan --}}
                                <form method="POST" action="{{ route('reviews.store') }}"
                                    x-data="{ rating: 0, hover: 0 }">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <input type="hidden" name="rating" x-model="rating">

                                    {{-- Bintang --}}
                                    <div class="flex items-center gap-1 mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                @mouseenter="hover = {{ $i }}"
                                                @mouseleave="hover = 0"
                                                @click="rating = {{ $i }}"
                                                class="focus:outline-none">
                                                <svg class="w-7 h-7 transition-colors"
                                                    :class="(hover || rating) >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        @endfor
                                        <span class="text-xs text-gray-400 ml-1" x-show="rating > 0">
                                            <span x-text="['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Bagus', 'Sangat Bagus'][rating]"></span>
                                        </span>
                                    </div>

                                    {{-- Komentar --}}
                                    <textarea name="comment" rows="2"
                                        placeholder="Tulis ulasanmu (opsional)..."
                                        class="w-full text-sm border border-gray-100 rounded-xl px-3 py-2 focus:outline-none focus:border-gray-300 resize-none placeholder-gray-300 transition"></textarea>

                                    @error('rating')
                                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                    @enderror

                                    <button type="submit"
                                        x-bind:disabled="rating === 0"
                                        class="mt-2 text-xs bg-gray-900 text-white px-4 py-2 rounded-xl disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-700 transition">
                                        Kirim Ulasan
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Total --}}
        <div class="flex justify-between items-center px-1">
            <span class="text-sm text-gray-500">Total Pembayaran</span>
            <span style="font-family: 'Playfair Display', serif;"
                class="text-xl font-semibold text-gray-900">
                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </span>
        </div>

        @if($order->status === 'shipped')
        <div class="border border-indigo-100 bg-indigo-50 rounded-2xl p-4 mb-4">
            <p class="text-sm font-medium text-indigo-800 mb-1">Sudah menerima paket?</p>
            <p class="text-xs text-indigo-500 mb-3">Konfirmasi jika paket sudah sampai di tanganmu. Jika tidak dikonfirmasi, pesanan otomatis selesai dalam 14 hari.</p>
            <form method="POST" action="{{ route('orders.confirm', $order->invoice_number) }}">
                @csrf
                <button type="button"
                    onclick="showConfirm(
                        'Konfirmasi Pesanan Diterima',
                        'Pastikan kamu sudah menerima paket sebelum konfirmasi.',
                        () => this.closest('form').submit()
                    )"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-xl transition">
                    Pesanan Sudah Diterima
                </button>
            </form>
        </div>
        @endif

        <div class="mt-8">
            @if($order->status === 'pending' && $order->payment_token)
                <a href="{{ route('checkout.payment', $order->invoice_number) }}"
                    class="block text-center bg-gray-900 text-white py-3 rounded-xl text-sm font-medium hover:bg-gray-700 transition mb-3">
                    Selesaikan Pembayaran
                </a>
            @endif
            @if($order->status === 'pending')
                <button id="check-payment-btn"
                    onclick="checkPaymentStatus()"
                    class="w-full text-center border border-gray-200 text-gray-600 py-3 rounded-xl text-sm hover:border-gray-900 transition mb-3">
                    Cek Status Pembayaran
                </button>
            @endif
            <a href="{{ route('shop.index') }}"
                class="block text-center border border-gray-200 text-gray-600 py-3 rounded-xl text-sm hover:border-gray-900 transition">
                Lanjut Belanja
            </a>
            @if($order->status === 'pending')
                <form id="delete-order-form" method="POST"
                    action="{{ route('orders.destroy', $order->invoice_number) }}"
                    class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        onclick="showConfirm(
                            'Batalkan Pesanan',
                            'Pesanan {{ $order->invoice_number }} akan dibatalkan dan stok akan dikembalikan. Lanjutkan?',
                            () => document.getElementById('delete-order-form').submit()
                        )"
                        class="w-full text-center border border-red-100 text-red-400 py-3 rounded-xl text-sm hover:bg-red-50 hover:border-red-300 transition">
                        Batalkan Pesanan
                    </button>
                </form>
            @endif
        </div>
    </div>
    @push('scripts')
    @if($order->tracking_number && $order->courier)
        @vite(['resources/js/pages/order-tracking.js'])
    @endif
    @if($order->status === 'pending')
    <script>
    async function checkPaymentStatus() {
        const btn = document.getElementById('check-payment-btn');
        if (!btn || btn.disabled) return;
        btn.disabled = true;
        btn.textContent = 'Mengecek...';
        btn.style.opacity = '0.6';

        try {
            const res = await fetch('{{ route('orders.check-payment', $order->invoice_number) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();

            if (data.status && data.status !== 'pending') {
                window.showToast('Pembayaran dikonfirmasi! Halaman akan diperbarui...', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                window.showToast('Pembayaran belum diterima. Coba beberapa saat lagi.', 'info');
                btn.disabled = false;
                btn.textContent = 'Cek Status Pembayaran';
                btn.style.opacity = '';
            }
        } catch (e) {
            window.showToast('Gagal menghubungi server. Coba lagi.', 'error');
            btn.disabled = false;
            btn.textContent = 'Cek Status Pembayaran';
            btn.style.opacity = '';
        }
    }
    </script>
    @endif
    @endpush
</x-app-layout>