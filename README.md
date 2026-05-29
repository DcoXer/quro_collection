# Quro Collection

Platform e-commerce fashion muslim modern — dibangun di atas Laravel 12 dengan Filament v5 sebagai admin panel.

## Tech Stack

- **Backend** — Laravel 12, PHP 8.4
- **Admin Panel** — Filament v5
- **Frontend** — Blade, Tailwind CSS, Alpine.js, Vite
- **Database** — MySQL
- **Payment** — Midtrans (Snap)
- **Shipping** — Biteship (ongkir + auto resi)
- **Wilayah** — ApiCoiID + Binderbyte
- **Notifikasi WA** — Fonnte
- **Storage** — Laravel Storage (local/public disk)

## Fitur Utama

### Storefront
- Katalog produk dengan kategori, pencarian FULLTEXT, & filter
- Varian produk per ukuran (S/M/L/XL/XXL) dengan stok masing-masing
- Flash Sale dengan countdown timer & banner otomatis
- Quick view produk tanpa pindah halaman
- Wishlist produk
- Review & rating produk (satu review per produk per user)
- Keranjang belanja dengan cek stok kumulatif
- Checkout dengan kalkulasi ongkir real-time (Biteship)
- Voucher / kode kupon diskon
- Login dengan Google OAuth + OTP email verification
- Notifikasi in-app (order status, dll)

### Order & Payment
- Payment gateway Midtrans Snap (kartu kredit, transfer, e-wallet, dll)
- Webhook Midtrans otomatis update status order
- Status flow: `pending → processing → shipped → delivered` (bisa `cancelled` dari pending/processing)
- Tracking resi real-time dari halaman order
- Email konfirmasi order & update status (termasuk nomor resi saat shipped)
- Notifikasi WhatsApp ke admin saat order masuk (via Fonnte)

### Admin Panel (Filament)
- Dashboard analytics: revenue chart, order status chart, top products, low stock alert
- Manajemen produk (SoftDeletes, bulk action) & kategori
- Manajemen order + auto-generate resi Biteship saat status di-set ke `shipped`
- Cetak resi PDF langsung dari admin
- Manajemen Flash Sale (produk, durasi, harga diskon)
- Manajemen voucher (flat / persentase, minimal order, masa berlaku)
- Hero slider & featured products (dikonfigurasi dari admin)
- CMS halaman statis (about, terms, privacy, dll)
- Site settings (nama toko, logo, kontak, SEO meta)
- Manajemen review produk (approve/reject)

### SEO & Performance
- Canonical URL otomatis (strip query params)
- noindex pada semua halaman private (cart, checkout, order, profil)
- Sitemap dinamis `/sitemap.xml`
- `robots.txt` dinamis via route
- Open Graph, Twitter Card, JSON-LD structured data (Product + BreadcrumbList)
- Rate limiter pada endpoint search (30 req/menit per IP)
- Cache query Flash Sale & banner
- Composite indexes + FULLTEXT index pada `products.name`

## Instalasi (Lokal)

```bash
git clone https://github.com/DcoXer/quro_collection.git
cd quro_collection

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Konfigurasi `.env` (database, Midtrans, Biteship, Fonnte, Google OAuth), lalu:

```bash
php artisan migrate --seed
php artisan storage:link

npm run dev
php artisan serve
```

Admin panel tersedia di `/admin`.

## Struktur Penting

```
app/
  Http/Controllers/     — ShopController, CartController, CheckoutController, OrderController, dll
  Models/               — Product (SoftDeletes), Order, FlashSale, Voucher, dll
  Services/             — MidtransService, BiteshipService, TrackingService, FonnteService
  Support/SchemaOrg.php — JSON-LD structured data builder
  Filament/             — Admin panel resources, widgets, pages
  Observers/            — FlashSaleObserver, FlashSaleItemObserver (cache invalidation)
  Console/Commands/     — ProcessShippedOrders (auto-deliver via scheduler)

resources/
  views/
    shop/               — index, show, category + partials (product-grid, category-grid, hero)
    checkout/           — index, payment, success
    orders/             — index, show
    emails/             — order-confirmation, order-status (dengan resi), otp
    layouts/            — app.blade.php, navigation.blade.php
    components/         — flash-sale-banner, footer, notification, confirm-modal
  js/pages/             — shop.js, product-show.js, checkout.js, quick-view.js
  css/                  — app.css, pages/product-show.css

deploy/                 — Script setup VPS, deploy manual, env example, panduan deploy
```

## Lisensi

Private — © 2026 Quro Collection. All rights reserved.
