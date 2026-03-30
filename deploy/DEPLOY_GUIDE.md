# Panduan Deploy Quro Collection ke VPS

## Persiapan Awal

### 1. Beli & Setup VPS
- Minimal spec: **1 vCPU, 1GB RAM, 20GB SSD**
- OS: **Ubuntu 22.04 LTS**
- Provider rekomendasi: DigitalOcean, Vultr, Hetzner, IDCloudHost

### 2. Pointing Domain ke VPS
Di DNS manager domain kamu, tambah record:
```
A    @              → IP_VPS_KAMU
A    www            → IP_VPS_KAMU
```
Tunggu propagasi DNS (bisa 5 menit - 24 jam)

---

## Step 1 — Setup VPS (jalankan sekali)

SSH ke VPS sebagai root:
```bash
ssh root@IP_VPS_KAMU
```

Upload dan jalankan setup script:
```bash
curl -o setup.sh https://raw.githubusercontent.com/USERNAME/qurocollection/main/deploy/setup-vps.sh
bash setup.sh
```

Atau copy-paste isi file `deploy/setup-vps.sh` langsung.

**Simpan output DB_PASSWORD yang muncul di akhir!**

---

## Step 2 — Clone Repository

```bash
cd /var/www/qurocollection
git clone https://github.com/USERNAME/qurocollection.git .
```

---

## Step 3 — Setup .env Production

```bash
cp deploy/env.production.example .env
nano .env
```

Isi semua field yang kosong:
- `APP_KEY` → generate dengan `php artisan key:generate`
- `DB_PASSWORD` → dari output setup script
- `MIDTRANS_SERVER_KEY` & `MIDTRANS_CLIENT_KEY` → dari dashboard Midtrans (mode Production)
- `MAIL_*` → SMTP provider kamu

---

## Step 4 — Install & Build

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## Step 5 — SSL Certificate

```bash
certbot --nginx -d qurocollection.com -d www.qurocollection.com
```

Ikuti instruksi, pilih redirect HTTP → HTTPS.

---

## Step 6 — Setup GitHub Actions CI/CD

Di repository GitHub, masuk ke **Settings → Secrets and variables → Actions**, tambah:

| Secret | Nilai |
|--------|-------|
| `VPS_HOST` | IP VPS kamu |
| `VPS_USER` | `root` atau user lain |
| `VPS_SSH_KEY` | Private key SSH (isi dari `cat ~/.ssh/id_rsa`) |
| `VPS_PORT` | `22` |

Cara buat SSH key di VPS:
```bash
ssh-keygen -t ed25519 -C "deploy@qurocollection"
cat ~/.ssh/id_ed25519      # → copy isi ini ke VPS_SSH_KEY di GitHub
cat ~/.ssh/id_ed25519.pub >> ~/.ssh/authorized_keys
```

Setelah ini, setiap **push ke branch `main`** akan otomatis deploy ke VPS.

---

## Deploy Manual (tanpa CI/CD)

```bash
ssh root@IP_VPS_KAMU
cd /var/www/qurocollection
bash deploy/deploy-manual.sh
```

---

## Checklist Final Sebelum Live

- [ ] `APP_DEBUG=false` di .env
- [ ] `MIDTRANS_IS_PRODUCTION=true` + key production
- [ ] SSL aktif (HTTPS)
- [ ] `php artisan storage:link` sudah dijalankan
- [ ] Test payment dengan kartu uji Midtrans
- [ ] Test kirim email (register, order confirmation)
- [ ] Cek semua halaman di mobile

---

## Monitoring & Maintenance

```bash
# Cek log error Laravel
tail -f /var/www/qurocollection/storage/logs/laravel.log

# Cek status Nginx
systemctl status nginx

# Cek status PHP-FPM
systemctl status php8.4-fpm

# Restart Nginx
systemctl restart nginx
```
