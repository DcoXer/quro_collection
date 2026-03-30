<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Block clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // Control referrer info sent to other sites
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Prevent Adobe Flash/PDF from embedding the page
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // Force HTTPS for 1 year in production (browser will reject HTTP after first visit)
        if (app()->isProduction() || $request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        // Content Security Policy — hanya aktif di production
        // Di local/staging, Vite dev server dan tools lain butuh akses bebas
        if (app()->isProduction()) {
            $midtransHosts = 'https://app.midtrans.com https://app.sandbox.midtrans.com';
            $googleHosts   = 'https://accounts.google.com https://fonts.googleapis.com https://fonts.gstatic.com';

            $csp = implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$midtransHosts} https://accounts.google.com",
                "style-src 'self' 'unsafe-inline' {$googleHosts}",
                "img-src 'self' data: blob: https:",
                "font-src 'self' data: https://fonts.gstatic.com",
                "connect-src 'self' {$midtransHosts}",
                "frame-src {$midtransHosts} https://accounts.google.com",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
            ]);

            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }
}
