<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subject ?? 'Quro Collection' }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background-color: #f4f4f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #18181b; }
    .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .header { background-color: #09090b; padding: 28px 40px; text-align: center; }
    .header-brand { font-size: 22px; font-weight: 600; color: #ffffff; letter-spacing: 0.5px; }
    .header-tagline { font-size: 11px; color: #71717a; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
    .body { padding: 36px 40px; }
    .greeting { font-size: 15px; color: #52525b; margin-bottom: 20px; }
    .greeting strong { color: #18181b; }
    .status-badge { display: inline-block; padding: 6px 16px; border-radius: 999px; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 24px; }
    .badge-paid       { background: #dcfce7; color: #16a34a; }
    .badge-processing { background: #ede9fe; color: #7c3aed; }
    .badge-shipped    { background: #dbeafe; color: #1d4ed8; }
    .badge-delivered  { background: #dcfce7; color: #15803d; }
    .badge-cancelled  { background: #fee2e2; color: #dc2626; }
    .badge-pending    { background: #fef9c3; color: #a16207; }
    .section-title { font-size: 11px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: #a1a1aa; margin-bottom: 12px; }
    .info-box { background: #fafafa; border: 1px solid #e4e4e7; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
    .info-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #f4f4f5; font-size: 13px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #71717a; }
    .info-value { color: #18181b; font-weight: 500; text-align: right; }
    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 13px; }
    .items-table th { background: #fafafa; padding: 10px 12px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: #a1a1aa; border-bottom: 1px solid #e4e4e7; }
    .items-table td { padding: 12px; border-bottom: 1px solid #f4f4f5; color: #3f3f46; vertical-align: top; }
    .items-table tr:last-child td { border-bottom: none; }
    .items-table .product-name { font-weight: 500; color: #18181b; }
    .items-table .product-size { font-size: 11px; color: #a1a1aa; margin-top: 2px; }
    .total-row { background: #fafafa; }
    .total-row td { padding: 12px; font-weight: 700; color: #18181b; font-size: 14px; border-top: 2px solid #e4e4e7; }
    .cta-btn { display: block; text-align: center; background: #09090b; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-size: 14px; font-weight: 600; margin: 24px 0; }
    .divider { border: none; border-top: 1px solid #f4f4f5; margin: 24px 0; }
    .message-box { border-left: 3px solid #09090b; padding: 12px 16px; background: #fafafa; border-radius: 0 8px 8px 0; margin-bottom: 24px; font-size: 14px; color: #3f3f46; line-height: 1.6; }
    .footer { background: #fafafa; border-top: 1px solid #f4f4f5; padding: 24px 40px; text-align: center; }
    .footer p { font-size: 12px; color: #a1a1aa; line-height: 1.8; }
    .footer a { color: #71717a; text-decoration: none; }
    @media (max-width: 600px) {
        .wrapper { margin: 0; border-radius: 0; }
        .body, .header, .footer { padding: 24px 20px; }
    }
</style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="header-brand">Quro Collection</div>
        <div class="header-tagline">Premium Muslim Fashion</div>
    </div>

    {{-- Body --}}
    <div class="body">
        {{ $slot }}
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>
            © {{ date('Y') }} Quro Collection · All rights reserved.<br>
            Ada pertanyaan? Hubungi kami via
            <a href="https://wa.me/6283108267397">WhatsApp</a> atau
            <a href="mailto:admin@qurocollection.com">Email</a>
        </p>
    </div>

</div>
</body>
</html>
