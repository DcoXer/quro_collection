<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // 5 registrasi per 10 menit per IP — cegah bot spam akun
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinutes(10, 5)->by($request->ip());
        });

        // OTP verify: 10 percobaan per 15 menit per IP + user session
        // Penting: 6-digit OTP = 1 juta kombinasi, tanpa rate limit bisa brute-force
        RateLimiter::for('otp-verify', function (Request $request) {
            $key = $request->ip() . '|' . (session('otp_user_id') ?? 'unknown');
            return Limit::perMinutes(15, 10)->by($key);
        });

        // OTP resend: 3 kali per 5 menit — cegah spam email
        RateLimiter::for('otp-resend', function (Request $request) {
            $key = $request->ip() . '|' . (session('otp_user_id') ?? 'unknown');
            return Limit::perMinutes(5, 3)->by($key);
        });

        // Voucher apply: 20 per menit per user — cegah enumeration kode voucher
        RateLimiter::for('voucher', function (Request $request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
        });

        // Cart add: 60 per menit per user — reasonable untuk normal use
        RateLimiter::for('cart', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Checkout: 10 per menit per user — cegah double-submit & spam order
        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
    }
}
