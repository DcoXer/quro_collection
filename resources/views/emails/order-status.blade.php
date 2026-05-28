@php
    $config = [
        'paid'       => ['badge' => 'badge-paid',       'icon' => '✓', 'title' => 'Pembayaran Dikonfirmasi!',   'msg' => 'Pembayaran kamu telah kami terima. Pesanan akan segera kami proses.'],
        'processing' => ['badge' => 'badge-processing',  'icon' => '⚙', 'title' => 'Pesanan Sedang Diproses',    'msg' => 'Tim kami sedang mempersiapkan pesananmu dengan teliti. Kami akan segera mengirimkannya.'],
        'shipped'    => ['badge' => 'badge-shipped',     'icon' => '🚚', 'title' => 'Pesanan Sudah Dikirim!',     'msg' => 'Pesananmu sudah dalam perjalanan. Pantau terus status pengirimannya ya.'],
        'delivered'  => ['badge' => 'badge-delivered',   'icon' => '✅', 'title' => 'Pesanan Telah Diterima!',    'msg' => 'Yeay! Pesananmu telah sampai. Jangan lupa berikan ulasan untuk produk yang kamu beli.'],
        'cancelled'  => ['badge' => 'badge-cancelled',   'icon' => '✕', 'title' => 'Pesanan Dibatalkan',         'msg' => 'Pesananmu telah dibatalkan. Jika ada pertanyaan, jangan ragu untuk menghubungi kami.'],
    ];
    $c = $config[$order->status] ?? ['badge' => 'badge-pending', 'icon' => '•', 'title' => 'Update Pesanan', 'msg' => 'Status pesananmu telah diperbarui.'];
@endphp

<x-emails.layout>

    <p class="greeting">Halo, <strong>{{ $order->user->name }}</strong> 👋</p>

    <div class="message-box">
        <strong>{{ $c['title'] }}</strong><br>
        {{ $c['msg'] }}
    </div>

    <span class="status-badge {{ $c['badge'] }}">{{ ucfirst($order->status) }}</span>

    {{-- Detail Pesanan --}}
    <p class="section-title">Detail Pesanan</p>
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Invoice</span>
            <span class="info-value">{{ $order->invoice_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Order</span>
            <span class="info-value">{{ $order->created_at->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status Terbaru</span>
            <span class="info-value">{{ ucfirst($order->status) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total</span>
            <span class="info-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Items ringkas --}}
    <p class="section-title">Item Pesanan</p>
    <table class="items-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th style="text-align:center;">Qty</th>
                <th style="text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <div class="product-name">{{ $item->product->name ?? 'Produk dihapus' }}</div>
                    @if($item->size)<div class="product-size">Size: {{ $item->size }}</div>@endif
                </td>
                <td style="text-align:center;color:#71717a;">{{ $item->quantity }}</td>
                <td style="text-align:right;font-weight:500;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Info resi jika status shipped --}}
    @if($order->status === 'shipped' && $order->tracking_number)
    <p class="section-title">Info Pengiriman</p>
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Kurir</span>
            <span class="info-value">{{ strtoupper($order->courier ?? '-') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nomor Resi</span>
            <span class="info-value" style="font-size:15px;font-weight:700;letter-spacing:1px;">{{ $order->tracking_number }}</span>
        </div>
    </div>
    @endif

    {{-- CTA --}}
    @if($order->status === 'delivered')
    <a href="{{ route('orders.show', $order->invoice_number) }}" class="cta-btn">
        Beri Ulasan Produk
    </a>
    @else
    <a href="{{ route('orders.show', $order->invoice_number) }}" class="cta-btn">
        Lacak Pesanan
    </a>
    @endif

</x-emails.layout>
