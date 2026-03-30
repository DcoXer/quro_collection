<x-emails.layout>

    <p class="greeting">Ada <strong>order baru</strong> masuk! 🎉</p>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Invoice</span>
            <span class="info-value">{{ $order->invoice_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal</span>
            <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
        </div>
        <div class="info-row">
            <span class="info-label">Customer</span>
            <span class="info-value">{{ $order->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">{{ $order->user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total</span>
            <span class="info-value" style="color:#16a34a;font-size:15px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

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
        <tfoot>
            <tr class="total-row">
                <td colspan="2">Total</td>
                <td style="text-align:right;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <p class="section-title">Alamat Pengiriman</p>
    <div class="info-box">
        <div style="font-size:13px;color:#18181b;font-weight:500;">{{ $order->shipping_name }}</div>
        <div style="font-size:13px;color:#71717a;margin-top:4px;">{{ $order->shipping_phone }}</div>
        <div style="font-size:13px;color:#71717a;margin-top:4px;line-height:1.5;">{{ $order->shipping_address }}</div>
    </div>

    <a href="{{ url('/admin/orders') }}" class="cta-btn">
        Kelola di Admin Panel
    </a>

</x-emails.layout>
