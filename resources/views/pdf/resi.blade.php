<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 10pt;
        color: #111;
        background: #fff;
        padding: 30px 36px;
    }

    /* ── HEADER ── */
    .header-table {
        width: 100%;
        border-bottom: 2px solid #111;
        padding-bottom: 14px;
        margin-bottom: 16px;
    }
    .store-name {
        font-size: 18pt;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    .store-tagline {
        font-size: 8pt;
        color: #666;
        margin-top: 3px;
    }
    .invoice-label {
        font-size: 7pt;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: right;
    }
    .invoice-value {
        font-size: 13pt;
        font-weight: 700;
        text-align: right;
    }

    /* ── KURIR BANNER ── */
    .courier-banner {
        background: #111;
        color: #fff;
        padding: 14px 16px;
        border-radius: 6px;
        margin-bottom: 16px;
        width: 100%;
    }
    .courier-name {
        font-size: 15pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .courier-service {
        font-size: 8pt;
        font-weight: 400;
        opacity: 0.7;
    }
    .resi-label {
        font-size: 7pt;
        opacity: 0.6;
        text-transform: uppercase;
        text-align: right;
    }
    .resi-number {
        font-size: 13pt;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-align: right;
    }

    /* ── ADDRESS BOXES ── */
    .address-table {
        width: 100%;
        margin-bottom: 16px;
        border-collapse: separate;
        border-spacing: 8px 0;
    }
    .address-box {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 14px 14px;
        width: 50%;
        vertical-align: top;
    }
    .box-label {
        font-size: 7pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #888;
        padding-bottom: 6px;
        margin-bottom: 6px;
        border-bottom: 1px solid #eee;
    }
    .addr-name {
        font-size: 12pt;
        font-weight: 700;
        margin-top: 4px;
        margin-bottom: 3px;
    }
    .addr-phone {
        font-size: 9pt;
        color: #444;
        margin-bottom: 3px;
    }
    .addr-text {
        font-size: 9pt;
        color: #555;
        line-height: 1.5;
    }

    /* ── ITEMS TABLE ── */
    .items-label {
        font-size: 6.5pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #888;
        margin-bottom: 4px;
    }
    table.items {
        width: 100%;
        border-collapse: collapse;
        font-size: 10pt;
        margin-bottom: 6px;
    }
    table.items thead tr {
        background: #f5f5f5;
    }
    table.items th {
        padding: 10px 10px;
        text-align: left;
        font-size: 8pt;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #666;
        font-weight: 700;
        border-bottom: 1px solid #ddd;
    }
    table.items td {
        padding: 11px 10px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: top;
    }
    table.items tr:last-child td {
        border-bottom: none;
    }
    .item-name { font-weight: 600; color: #111; }
    .item-size { font-size: 7pt; color: #888; }

    /* ── TOTAL ── */
    .total-table {
        width: 100%;
        border-top: 1.5px solid #111;
        padding-top: 10px;
        margin-top: 8px;
    }
    .total-label {
        font-size: 9pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .total-value {
        font-size: 14pt;
        font-weight: 700;
        text-align: right;
    }

    /* ── FOOTER ── */
    .footer-table {
        width: 100%;
        border-top: 1px dashed #ccc;
        margin-top: 20px;
        padding-top: 12px;
    }
    .footer-note {
        font-size: 8pt;
        color: #999;
        line-height: 1.6;
        vertical-align: bottom;
    }
    .footer-date {
        font-size: 8pt;
        color: #888;
        text-align: right;
        vertical-align: bottom;
    }
</style>
</head>
<body>

    {{-- HEADER --}}
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align:middle;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="vertical-align:middle;padding-right:8px;">
                            <img src="{{ public_path('images/logo.png') }}" style="height:80px;width:auto;" alt="logo">
                        </td>
                        <td style="vertical-align:middle;">
                            <div class="store-name">{{ $sender['name'] }}</div>
                            <div class="store-tagline">Fashion Muslim Berkualitas</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:40%;">
                <div class="invoice-label">No. Invoice</div>
                <div class="invoice-value">{{ $order->invoice_number }}</div>
            </td>
        </tr>
    </table>

    {{-- KURIR BANNER --}}
    <table class="courier-banner" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align:middle;">
                <span class="courier-name">{{ strtoupper($order->courier ?? 'Kurir') }}</span>
                @if($order->courier_service)
                    <span class="courier-service"> · {{ $order->courier_service }}</span>
                @endif
            </td>
            <td style="width:55%;vertical-align:middle;text-align:right;">
                @if($barcode)
                    <div style="display:inline-block;background:#fff;padding:4px 8px;border-radius:4px;margin-bottom:4px;">
                        <img src="{{ $barcode }}" style="height:48px;width:auto;display:block;">
                    </div><br>
                @endif
                <div class="resi-label">No. Resi</div>
                <div class="resi-number">{{ $order->tracking_number ?? 'Belum tersedia' }}</div>
            </td>
        </tr>
    </table>

    {{-- ALAMAT --}}
    <table class="address-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="address-box">
                <div class="box-label">Pengirim</div>
                <div class="addr-name">{{ $sender['name'] }}</div>
                <div class="addr-phone">{{ $sender['phone'] }}</div>
                <div class="addr-text">{{ $sender['address'] }}, {{ $sender['city'] }}</div>
            </td>
            <td class="address-box">
                <div class="box-label">Penerima</div>
                <div class="addr-name">{{ $order->shipping_name }}</div>
                <div class="addr-phone">{{ $order->shipping_phone }}</div>
                <div class="addr-text">
                    {{ $order->shipping_address }}@if($order->district_name), {{ $order->district_name }}@endif@if($order->city_name), {{ $order->city_name }}@endif
                </div>
            </td>
        </tr>
    </table>

    {{-- DAFTAR PRODUK --}}
    <div class="items-label">Detail Pesanan</div>
    <table class="items">
        <thead>
            <tr>
                <th style="width:55%">Produk</th>
                <th style="width:10%;text-align:center;">Uk.</th>
                <th style="width:10%;text-align:center;">Qty</th>
                <th style="width:25%;text-align:right;">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td class="item-name">{{ $item->product?->name ?? 'Produk' }}</td>
                <td style="text-align:center;" class="item-size">{{ $item->size ?? '-' }}</td>
                <td style="text-align:center;">{{ $item->quantity }}</td>
                <td style="text-align:right;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTAL --}}
    <table class="total-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="total-label">Total Pembayaran</td>
            <td class="total-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="footer-note">
                Terima kasih telah berbelanja di {{ $sender['name'] }}.<br>
                Barang yang sudah dibeli tidak dapat dikembalikan kecuali ada kerusakan.
            </td>
            <td class="footer-date">
                Dicetak: {{ now()->translatedFormat('d M Y, H:i') }}<br>
                Order: {{ $order->created_at->translatedFormat('d M Y') }}
            </td>
        </tr>
    </table>

</body>
</html>
