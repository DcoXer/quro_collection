# Jalankan script ini tiap mau demo: .\tunnel.ps1

$ErrorActionPreference = "Stop"
$AppDir = $PSScriptRoot

# Hapus file hot agar Laravel tidak pakai Vite dev server
$hotFile = Join-Path $PSScriptRoot "public\hot"
if (Test-Path $hotFile) {
    Remove-Item $hotFile -Force
    Write-Host "Vite dev server dinonaktifkan (file hot dihapus)." -ForegroundColor Yellow
}

# Build asset untuk production
Write-Host "Building assets..." -ForegroundColor Cyan
Set-Location $PSScriptRoot
npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "Build gagal. Periksa error di atas." -ForegroundColor Red
    exit 1
}
Write-Host "Build selesai." -ForegroundColor Green
Write-Host ""

Write-Host "Memulai Cloudflare Tunnel..." -ForegroundColor Cyan

# Start cloudflared dan baca output sampai URL ketemu
$psi = New-Object System.Diagnostics.ProcessStartInfo
$psi.FileName = "C:\Program Files (x86)\cloudflared\cloudflared.exe"
$psi.Arguments = "tunnel --url http://localhost:80 --http-host-header qurocollection.test"
$psi.RedirectStandardOutput = $false
$psi.RedirectStandardError = $true
$psi.UseShellExecute = $false
$psi.CreateNoWindow = $false

$process = [System.Diagnostics.Process]::Start($psi)

$tunnelUrl = $null
$timeout = [System.DateTime]::Now.AddSeconds(30)

Write-Host "Menunggu URL tunnel..." -ForegroundColor Yellow

while ([System.DateTime]::Now -lt $timeout) {
    $line = $process.StandardError.ReadLine()
    if ($line -match "https://[a-z0-9\-]+\.trycloudflare\.com") {
        $tunnelUrl = $Matches[0]
        break
    }
}

if (-not $tunnelUrl) {
    Write-Host "Gagal mendapatkan URL tunnel." -ForegroundColor Red
    $process.Kill()
    exit 1
}

Write-Host ""
Write-Host "URL Tunnel ditemukan: $tunnelUrl" -ForegroundColor Green

# Update .env
$envPath = Join-Path $AppDir ".env"
$envContent = Get-Content $envPath -Raw
$envContent = $envContent -replace "(?m)^APP_URL=.*",   "APP_URL=$tunnelUrl"
$envContent = $envContent -replace "(?m)^ASSET_URL=.*", "ASSET_URL=$tunnelUrl"
Set-Content $envPath $envContent -NoNewline
Write-Host "APP_URL dan ASSET_URL diupdate." -ForegroundColor Green

# Cache Laravel
Set-Location $AppDir
php artisan config:cache | Out-Null
php artisan route:cache  | Out-Null
php artisan view:cache   | Out-Null
Write-Host "Cache Laravel di-rebuild." -ForegroundColor Green

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host " SIAP! Link sudah bisa di share:" -ForegroundColor Cyan
Write-Host " $tunnelUrl" -ForegroundColor White
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Tekan Ctrl+C untuk stop tunnel." -ForegroundColor Gray

# Teruskan output tunnel ke layar
$process.StandardError.ReadToEnd() | Write-Host
$process.WaitForExit()
