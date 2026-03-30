<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Quro Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f9fafb;
            color: #111827;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .container { text-align: center; max-width: 480px; }
        .code {
            font-family: 'Playfair Display', serif;
            font-size: 7rem;
            font-weight: 600;
            color: #e5e7eb;
            line-height: 1;
            margin-bottom: 1.5rem;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        p {
            font-size: 0.9rem;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        a {
            display: inline-block;
            background: #111827;
            color: #fff;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.15s;
        }
        a:hover { background: #374151; }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">{{ $code }}</div>
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <a href="/">Kembali ke Beranda</a>
    </div>
</body>
</html>
