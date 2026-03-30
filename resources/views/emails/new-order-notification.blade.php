<x-mail::message>
# Ada Order Baru Masuk! 🎉

**Invoice:** {{ $order->invoice_number }}
**Tanggal:** {{ $order->created_at->format('d M Y, H:i') }}
**Status:** {{ ucfirst($order->status) }}

---

## Data Customer

**Nama:** {{ $order->user->name }}
**Email:** {{ $order->user->email }}

---

## Alamat Pengiriman

**{{ $order->shipping_name }}**
{{ $order->shipping_phone }}
{{ $order->shipping_address }}

---

## Item Pesanan

<x-mail::table>
| Produk | Size | Qty | Subtotal |
|:-------|:-----|:----|:---------|
@foreach($order->items as $item)
| {{ $item->product->name ?? 'Produk dihapus' }} | {{ $item->size ?? '-' }} | {{ $item->quantity }} | Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }} |
@endforeach
</x-mail::table>

**Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}**

<x-mail::button :url="url('/admin/orders')" color="dark">
Lihat di Admin Panel
</x-mail::button>

© {{ date('Y') }} Quro Collection
</x-mail::message>