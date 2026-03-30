#!/bin/bash
# ============================================================
# Quro Collection — Manual Deploy Script
# Jalankan di VPS: bash deploy-manual.sh
# ============================================================

set -e

APP_DIR="/var/www/qurocollection"

echo "==> Masuk ke direktori aplikasi..."
cd ${APP_DIR}

echo "==> Pull kode terbaru..."
git pull origin main

echo "==> Install PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Install NPM & build assets..."
npm ci
npm run build

echo "==> Jalankan migrasi..."
php artisan migrate --force

echo "==> Clear & cache config..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> Restart queue worker..."
php artisan queue:restart

echo "==> Set permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo ""
echo "✅ Deploy manual selesai!"
