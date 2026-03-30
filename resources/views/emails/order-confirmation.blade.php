<x-mail::message>

# Pesanan Berhasil Dibuat! ✓

Halo **{{ $order->user->name }}**,

Terima kasih telah berbelanja di **Quro Collection**. Pesanan kamu telah kami terima dan kami akan proses setelah pembayaran selesai.

Thank You for shopping in **Quro Collection**. Your order has been received and will be processed once payment is completed.

---

## Detail Pesanan

| | |
|:--|:--|
| **Invoice** | {{ $order->invoice_number }} |
| **Tanggal** | {{ $order->created_at->format('d M Y, H:i') }} |
| **Status** | {{ ucfirst($order->status) }} |
| **Metode Bayar** | {{ ucfirst($order->payment_method ?? '-') }} |

---

## Item Pesanan

<x-mail::table>
| Produk | Size | Qty | Subtotal |
|:-------|:-----|:----|:---------|
@foreach($order->items as $item)
| {{ $item->product->name ?? 'Produk dihapus' }} | {{ $item->size ?? '-' }} | {{ $item->quantity }} | Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }} |
@endforeach
| | | **Total** | **Rp {{ number_format($order->total_amount, 0, ',', '.') }}** |
</x-mail::table>

---

## Alamat Pengiriman

**{{ $order->shipping_name }}**
{{ $order->shipping_phone }}
{{ $order->shipping_address }}

---

<x-mail::button :url="route('orders.show', $order->invoice_number)" color="dark">
Lihat Detail Pesanan
</x-mail::button>

Ada pertanyaan? Hubungi kami via WhatsApp di **+62 812 3456 7890**

© {{ date('Y') }} Quro Collection · All rights reserved.

</x-mail::message>