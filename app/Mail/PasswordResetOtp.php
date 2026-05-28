<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtp extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public string $name
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password — Quro Collection',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.otp-reset-password',
        );
    }
}
