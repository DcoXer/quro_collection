<x-mail::message>

# Verifikasi Akun Kamu ✉️

Halo **{{ $name }}**,

Terima kasih telah mendaftar di **Quro Collection**.

Gunakan kode OTP berikut untuk memverifikasi akun kamu:

<div style="text-align: center; margin: 30px 0;">
    <div style="display: inline-block; background: #111112; color: #ffffff; font-size: 32px; font-weight: 700; letter-spacing: 12px; padding: 16px 32px; border-radius: 12px;">
        {{ $otp }}
    </div>
</div>

Kode ini berlaku selama **5 menit** dan hanya dapat digunakan sekali.

> Jika kamu tidak mendaftar di Quro Collection, abaikan email ini.

© {{ date('Y') }} Quro Collection · All rights reserved.

</x-mail::message>