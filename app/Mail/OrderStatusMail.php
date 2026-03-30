<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public string $previousStatus) {}

    public function envelope(): Envelope
    {
        $subjects = [
            'paid'       => 'Pembayaran Dikonfirmasi — ' . $this->order->invoice_number,
            'processing' => 'Pesanan Sedang Diproses — ' . $this->order->invoice_number,
            'shipped'    => 'Pesanan Sudah Dikirim — ' . $this->order->invoice_number,
            'delivered'  => 'Pesanan Telah Diterima — ' . $this->order->invoice_number,
            'cancelled'  => 'Pesanan Dibatalkan — ' . $this->order->invoice_number,
        ];

        return new Envelope(
            subject: $subjects[$this->order->status] ?? 'Update Pesanan — ' . $this->order->invoice_number,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-status');
    }
}
