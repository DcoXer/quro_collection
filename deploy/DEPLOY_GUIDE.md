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
curl -o setup.sh https://raw.githubusercontent.com/DcoXer/quro_collection/main/deploy/setup-vps.sh
bash setup.sh
```

Atau copy-paste isi file `deploy/setup-vps.sh` langsung.

**Simpan output DB_PASSWORD yang muncul di akhir!**

---

## Step 2 — Clone Repository

```bash
cd /var/www/qurocollection
git clone https://github.com/DcoXer/quro_collection.git .
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
- `MIDTRANS_SERVER_KEY` & `MIDTRANS_CLIENT_KEY` → dashboard Midtrans (mode Production)
- `BITESHIP_API_KEY` → live key dari dashboard Biteship (bukan `biteship_test.*`)
- `BITESHIP_SHIPPER_*` → data toko/pengirim
- `FONNTE_TOKEN` & `FONNTE_ADMIN_PHONE` → dashboard Fonnte
- `APICOIID_KEY`, `BINDERBYTE_API_KEY` → key masing-masing provider
- `GOOGLE_CLIENT_ID` & `GOOGLE_CLIENT_SECRET` → Google Cloud Console
- `MAIL_*` → SMTP provider (Mailgun, Resend, SES, dll)

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
php artisan event:cache

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

## Step 6 — Setup Queue Worker (Supervisor)

Queue worker diperlukan untuk **email konfirmasi order, email status shipped, dan email lainnya**.

Install Supervisor:
```bash
apt install -y supervisor
```

Buat config:
```bash
nano /etc/supervisor/conf.d/qurocollection-worker.conf
```

Isi dengan:
```ini
[program:qurocollection-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/qurocollection/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/qurocollection/storage/logs/worker.log
stopwaitsecs=3600
```

Aktifkan:
```bash
supervisorctl reread
supervisorctl update
supervisorctl start qurocollection-worker:*
```

Cek status:
```bash
supervisorctl status
```

---

## Step 7 — Setup Laravel Scheduler (Cron)

Scheduler diperlukan untuk **auto-update status order ke `delivered`** setelah beberapa hari shipped.

```bash
crontab -e -u www-data
```

Tambahkan baris ini:
```
* * * * * cd /var/www/qurocollection && php artisan schedule:run >> /dev/null 2>&1
```

---

## Step 8 — Setup GitHub Actions CI/CD

Di repository GitHub, masuk ke **Settings → Secrets and variables → Actions**, tambah:

| Secret | Nilai |
|--------|-------|
| `VPS_HOST` | IP VPS kamu |
| `VPS_USER` | `root` atau user lain |
| `VPS_SSH_KEY` | Private key SSH (isi dari `cat ~/.ssh/id_ed25519`) |
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
- [ ] `MIDTRANS_IS_PRODUCTION=true` + key production (bukan SB-)
- [ ] `BITESHIP_API_KEY` pakai live key (bukan `biteship_test.*`)
- [ ] `FONNTE_TOKEN` & `FONNTE_ADMIN_PHONE` sudah diisi
- [ ] `MAIL_HOST` bukan Mailtrap sandbox
- [ ] SSL aktif (HTTPS)
- [ ] `php artisan storage:link` sudah dijalankan
- [ ] Queue worker running (`supervisorctl status`)
- [ ] Laravel Scheduler aktif (`crontab -l -u www-data`)
- [ ] Midtrans Notification URL sudah diset: `https://qurocollection.com/webhook/midtrans`
- [ ] Test payment dengan kartu uji Midtrans
- [ ] Test kirim email (register, order confirmation, shipped dengan resi)
- [ ] Test notifikasi WhatsApp admin saat order masuk
- [ ] Cek semua halaman di mobile
- [ ] Daftar Google Search Console + submit sitemap

---

## Monitoring & Maintenance

```bash
# Cek log error Laravel
tail -f /var/www/qurocollection/storage/logs/laravel.log

# Cek log queue worker
tail -f /var/www/qurocollection/storage/logs/worker.log

# Cek status Supervisor
supervisorctl status

# Restart queue worker (setelah deploy)
php artisan queue:restart

# Cek status Nginx
systemctl status nginx

# Cek status PHP-FPM
systemctl status php8.4-fpm

# Restart Nginx
systemctl restart nginx
```
