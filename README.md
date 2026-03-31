# Quro Collection

Platform e-commerce fashion muslim modern — dibangun di atas Laravel 12 dengan Filament v5 sebagai admin panel.

## Tech Stack

- **Backend** — Laravel 12, PHP 8.4
- **Admin Panel** — Filament v5
- **Frontend** — Blade, Tailwind CSS, Alpine.js, Vite
- **Database** — MySQL
- **Payment** — Midtrans
- **Storage** — Local / Laravel Storage

## Fitur Utama

- Katalog produk dengan kategori & galeri media (foto + video)
- Varian produk per ukuran (S/M/L/XL/XXL) dengan stok masing-masing
- Flash Sale dengan countdown timer & banner otomatis
- Keranjang belanja & checkout dengan Midtrans
- Wishlist & recently viewed products
- Review & rating produk
- Notifikasi pesanan real-time
- SEO — meta tags, Open Graph, Twitter Card, JSON-LD structured data
- Hero slider & footer CTA yang bisa dikonfigurasi dari admin

## Instalasi

```bash
git clone https://github.com/DcoXer/quro_collection.git
cd quro_collection

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Konfigurasi `.env` — database, Midtrans key, storage, dll — lalu:

```bash
php artisan migrate --seed
php artisan storage:link

npm run build
php artisan serve
```

Admin panel tersedia di `/admin`.

## Struktur Penting

```
app/
  Http/Controllers/     — ShopController, CartController, OrderController, dll
  Models/               — Product, ProductVariant, FlashSale, Order, dll
  Support/SchemaOrg.php — JSON-LD structured data builder
  Filament/             — Admin panel resources

resources/
  views/
    shop/               — Halaman produk (index, show, category)
    layouts/            — app.blade.php, navigation.blade.php
    components/         — flash-sale-banner, footer, notification, dll
  js/pages/             — shop.js, product-show.js, quick-view.js
  css/                  — app.css, pages/product-show.css
```

## Lisensi

Private — © 2026 Quro Collection. All rights reserved.
