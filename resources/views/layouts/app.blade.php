<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cart-add-url" content="{{ route('cart.add') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

    {{-- Per-page SEO overrides (title, meta desc, OG) — rendered FIRST so they take precedence --}}
    @stack('seo')

    {{-- Default fallback title & OG — only shown if the page doesn't push its own --}}
    @php
        $siteName   = \App\Models\SiteSetting::get('site_name', 'Quro Collection');
        $ogDesc     = \App\Models\SiteSetting::get('seo_og_description', 'Temukan koleksi baju koko premium di Quro Collection. Kualitas premium, harga terjangkau.');
    @endphp
    <title>{{ $siteName }}</title>
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $siteName }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $siteName }}">
    <meta name="twitter:description" content="{{ $ogDesc }}">
    <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,300;0,600;1,300;1,600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- JSON-LD: Organization + WebSite (global) --}}
    <script type="application/ld+json">@json(\App\Support\SchemaOrg::site())</script>
    @stack('jsonld')
</head>
{{-- Page Loader --}}
<div id="quro-loader" style="
    position:fixed;inset:0;z-index:99999;
    background:#fff;
    display:flex;flex-direction:column;align-items:center;justify-content:center;gap:20px;
    transition:opacity .4s ease, visibility .4s ease;
">
    <img src="{{ asset('images/logo.png') }}" alt="Quro Collection"
        style="height:52px;width:auto;animation:quroLogoIn .5s cubic-bezier(.34,1.56,.64,1) both;">
    <div style="display:flex;gap:7px;align-items:center;">
        <span style="width:6px;height:6px;border-radius:50%;background:#111827;animation:quroDot .9s ease-in-out infinite;animation-delay:0s;"></span>
        <span style="width:6px;height:6px;border-radius:50%;background:#111827;animation:quroDot .9s ease-in-out infinite;animation-delay:.18s;"></span>
        <span style="width:6px;height:6px;border-radius:50%;background:#111827;animation:quroDot .9s ease-in-out infinite;animation-delay:.36s;"></span>
    </div>
</div>
<style>
@keyframes quroLogoIn {
    from { opacity:0; transform:scale(.82) translateY(10px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
@keyframes quroDot {
    0%,80%,100% { transform:scale(.6); opacity:.3; }
    40%          { transform:scale(1.1); opacity:1; }
}
@keyframes quroSpin {
    to { transform:rotate(360deg); }
}
</style>

<body class="bg-white text-gray-900 antialiased" style="font-family: 'Inter', sans-serif;">
<script>
(function(){
    var loader = document.getElementById('quro-loader');

    function hideLoader() {
        loader.style.opacity = '0';
        loader.style.visibility = 'hidden';
    }

    // Hide on load
    if (document.readyState === 'complete') {
        setTimeout(hideLoader, 120);
    } else {
        window.addEventListener('load', function(){ setTimeout(hideLoader, 120); });
        // Fallback: paksa hide setelah 4 detik supaya tidak stuck
        setTimeout(hideLoader, 4000);
    }

    // Show on navigation
    document.addEventListener('click', function(e){
        var a = e.target.closest('a');
        if (!a || !a.href) return;
        var url = a.href;
        if (a.target === '_blank' || a.download) return;
        if (url.startsWith('javascript') || url.startsWith('mailto') || url.startsWith('tel')) return;
        var isHashOnly = (a.getAttribute('href') || '').startsWith('#');
        var isSameOrigin = url.startsWith(location.origin) || url.startsWith('/');
        if (!isSameOrigin || isHashOnly) return;

        loader.style.opacity = '1';
        loader.style.visibility = 'visible';
    });

    // Track which submit button was clicked
    var _lastSubmitBtn = null;
    document.addEventListener('click', function(e){
        var btn = e.target.closest('button[type="submit"], input[type="submit"]');
        if (btn) _lastSubmitBtn = btn;
    }, true);

    // Show on form submit
    document.addEventListener('submit', function(e){
        var form = e.target;
        if (form.method && form.method.toLowerCase() === 'get') return;
        loader.style.opacity = '1';
        loader.style.visibility = 'visible';

        // Animate the clicked submit button
        var target = _lastSubmitBtn || form.querySelector('button[type="submit"]');
        if (target && !target.dataset.loading) {
            target.dataset.loading = '1';
            target.disabled = true;
            var original = target.innerHTML;
            target.dataset.originalHtml = original;
            target.innerHTML =
                '<span style="display:inline-flex;align-items:center;gap:8px;">' +
                    '<span style="width:14px;height:14px;border:2px solid currentColor;border-top-color:transparent;border-radius:50%;display:inline-block;animation:quroSpin .6s linear infinite;flex-shrink:0;opacity:.8;"></span>' +
                    'Memproses...' +
                '</span>';
        }
        _lastSubmitBtn = null;
    });

    // Hide on back/forward (bfcache)
    window.addEventListener('pageshow', function(e){
        if (e.persisted) hideLoader();
    });
})();
</script>
    {{-- Navigation --}}
    @include('layouts.navigation')
    {{-- Flash Sale Banner --}}
    <x-flash-sale-banner />
    {{-- Page Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-footer />

    {{-- WhatsApp CS Button --}}
    <a href="https://wa.me/6283108267397?text=Halo%20Quro%20Collection%2C%20saya%20ingin%20bertanya%20tentang%20produk"
        target="_blank"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-2 bg-transparent hover:text-green-400 text-gray-800 px-4 py-3 rounded-full shadow-lg transition group">
        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.855L.057 24l6.302-1.651A11.934 11.934 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.006-1.371l-.36-.214-3.732.978.995-3.636-.235-.374A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
        </svg>
    </a>

    {{-- Modals & Notifications --}}
    <x-notification />
    <x-confirm-modal />
    <x-scripts />
    @stack('scripts')
</body>
</html>