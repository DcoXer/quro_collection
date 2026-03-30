#!/bin/bash
# ============================================================
# Quro Collection — VPS Setup Script
# Ubuntu 22.04 LTS
# Jalankan sebagai root: bash setup-vps.sh
# ============================================================

set -e

DOMAIN="qurocollection.com"
APP_DIR="/var/www/qurocollection"
DB_NAME="quro_collection"
DB_USER="quro_user"
DB_PASS=$(openssl rand -base64 16)
PHP_VERSION="8.4"

echo "=================================================="
echo "  Quro Collection — VPS Setup"
echo "=================================================="

# ── 1. Update system ──────────────────────────────────
echo ""
echo "[1/9] Update system..."
apt update && apt upgrade -y

# ── 2. Install Nginx ──────────────────────────────────
echo ""
echo "[2/9] Install Nginx..."
apt install -y nginx
systemctl enable nginx
systemctl start nginx

# ── 3. Install PHP 8.4 ───────────────────────────────
echo ""
echo "[3/9] Install PHP ${PHP_VERSION}..."
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-redis \
    php${PHP_VERSION}-opcache \
    php${PHP_VERSION}-tokenizer

systemctl enable php${PHP_VERSION}-fpm
systemctl start php${PHP_VERSION}-fpm

# ── 4. Install MySQL ─────────────────────────────────
echo ""
echo "[4/9] Install MySQL..."
apt install -y mysql-server
systemctl enable mysql
systemctl start mysql

mysql -u root <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL

echo ""
echo "  ✅ Database: ${DB_NAME}"
echo "  ✅ DB User : ${DB_USER}"
echo "  ✅ DB Pass : ${DB_PASS}  ← SIMPAN INI!"

# ── 5. Install Composer ──────────────────────────────
echo ""
echo "[5/9] Install Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# ── 6. Install Node.js ───────────────────────────────
echo ""
echo "[6/9] Install Node.js 20..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# ── 7. Setup direktori aplikasi ──────────────────────
echo ""
echo "[7/9] Setup direktori aplikasi..."
mkdir -p ${APP_DIR}
chown -R www-data:www-data ${APP_DIR}
chmod -R 755 ${APP_DIR}

# ── 8. Nginx config ──────────────────────────────────
echo ""
echo "[8/9] Setup Nginx config..."
cat > /etc/nginx/sites-available/qurocollection <<NGINX
server {
    listen 80;
    server_name ${DOMAIN} www.${DOMAIN};
    root ${APP_DIR}/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;
    charset utf-8;

    client_max_body_size 50M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINX

ln -sf /etc/nginx/sites-available/qurocollection /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

# ── 9. Install Certbot SSL ───────────────────────────
echo ""
echo "[9/9] Install Certbot (SSL)..."
apt install -y certbot python3-certbot-nginx
echo ""
echo "  Jalankan ini setelah domain sudah pointing ke VPS:"
echo "  certbot --nginx -d ${DOMAIN} -d www.${DOMAIN}"

# ── Done ─────────────────────────────────────────────
echo ""
echo "=================================================="
echo "  ✅ Setup selesai!"
echo "=================================================="
echo ""
echo "  DB_DATABASE=${DB_NAME}"
echo "  DB_USERNAME=${DB_USER}"
echo "  DB_PASSWORD=${DB_PASS}"
echo ""
echo "  Langkah selanjutnya:"
echo "  1. Clone repo ke ${APP_DIR}"
echo "  2. Copy .env dan isi kredensial di atas"
echo "  3. php artisan key:generate"
echo "  4. php artisan migrate --force"
echo "  5. php artisan storage:link"
echo "  6. certbot --nginx -d ${DOMAIN} -d www.${DOMAIN}"
echo "=================================================="
