<x-mail::message>

# Reset Password ✉️

Halo **{{ $name }}**,

Kami menerima permintaan reset password untuk akun **Quro Collection** kamu.

Gunakan kode OTP berikut untuk melanjutkan proses reset password:

<div style="text-align: center; margin: 30px 0;">
    <div style="display: inline-block; background: #111112; color: #ffffff; font-size: 32px; font-weight: 700; letter-spacing: 12px; padding: 16px 32px; border-radius: 12px;">
        {{ $otp }}
    </div>
</div>

Kode ini berlaku selama **5 menit** dan hanya dapat digunakan sekali.

> Jika kamu tidak meminta reset password, abaikan email ini. Akun kamu tetap aman.

© {{ date('Y') }} Quro Collection · All rights reserved.

</x-mail::message>
