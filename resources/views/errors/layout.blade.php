<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} — {{ $title }} | Quro Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #09090b;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Background image */
        .bg-image {
            position: fixed;
            inset: 0;
            background: url('/images/produk.jpeg') center/cover no-repeat;
            opacity: 0.08;
            z-index: 0;
        }

        /* Main layout */
        .wrapper {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
        }

        /* Brand */
        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: #71717a;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 3rem;
            text-decoration: none;
            transition: color 0.2s;
        }
        .brand:hover { color: #a1a1aa; }

        /* Error code */
        .code {
            font-family: 'Playfair Display', serif;
            font-size: clamp(6rem, 20vw, 12rem);
            font-weight: 600;
            line-height: 1;
            color: transparent;
            -webkit-text-stroke: 1px #3f3f46;
            margin-bottom: 1.5rem;
            animation: fadeUp 0.8s cubic-bezier(0.16,1,0.3,1) forwards;
            opacity: 0;
        }

        /* Title */
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.5rem, 4vw, 2.25rem);
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1rem;
            animation: fadeUp 0.8s 0.1s cubic-bezier(0.16,1,0.3,1) forwards;
            opacity: 0;
        }

        /* Message */
        .message {
            font-size: 0.9rem;
            color: #71717a;
            line-height: 1.7;
            max-width: 380px;
            margin: 0 auto 2.5rem;
            animation: fadeUp 0.8s 0.2s cubic-bezier(0.16,1,0.3,1) forwards;
            opacity: 0;
        }

        /* Actions */
        .actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeUp 0.8s 0.3s cubic-bezier(0.16,1,0.3,1) forwards;
            opacity: 0;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #ffffff;
            color: #09090b;
            padding: 0.75rem 1.75rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-primary:hover { background: #e4e4e7; }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #3f3f46;
            color: #a1a1aa;
            padding: 0.75rem 1.75rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
        }
        .btn-secondary:hover { border-color: #71717a; color: #ffffff; }

        /* Divider line */
        .line {
            width: 40px;
            height: 1px;
            background: #3f3f46;
            margin: 2.5rem auto;
        }

        /* Footer */
        footer {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 1.5rem;
            font-size: 0.75rem;
            color: #3f3f46;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .actions { flex-direction: column; align-items: center; }
            .btn-primary, .btn-secondary { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

    <div class="bg-image"></div>

    <div class="wrapper">

        <a href="/" class="brand">Quro Collection</a>

        <div class="code">{{ $code }}</div>

        <h1>{{ $title }}</h1>

        <p class="message">{{ $message }}</p>

        <div class="actions">
            <a href="/" class="btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Kembali ke Beranda
            </a>
            <a href="/shop" class="btn-secondary">
                Lihat Koleksi
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="line"></div>

    </div>

    <footer>© {{ date('Y') }} Quro Collection · Premium Muslim Fashion</footer>

</body>
</html>
