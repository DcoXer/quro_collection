<?php

namespace App\Console\Commands;

use App\Mail\OrderStatusMail;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessShippedOrders extends Command
{
    protected $signature = 'orders:process-shipped';
    protected $description = 'Auto-deliver orders shipped >14 days ago, and send reminder at day 3';

    public function handle(): void
    {
        $this->autoDeliver();
        $this->sendReminders();
    }

    private function autoDeliver(): void
    {
        $orders = Order::where('status', 'shipped')
            ->whereNotNull('shipped_at')
            ->where('shipped_at', '<=', now()->subDays(14))
            ->with('user')
            ->get();

        foreach ($orders as $order) {
            $order->update(['status' => 'delivered']);

            Notification::send(
                $order->user_id,
                'order_status',
                'Pesanan Selesai',
                'Pesanan ' . $order->invoice_number . ' telah otomatis diselesaikan. Jangan lupa beri ulasan!',
                route('orders.show', $order->invoice_number)
            );

            Mail::to($order->user->email)
                ->queue(new OrderStatusMail($order, 'shipped'));

            Log::info('Auto-delivered order', ['invoice' => $order->invoice_number]);
            $this->line("Auto-delivered: {$order->invoice_number}");
        }

        $this->info("Auto-delivered {$orders->count()} order(s).");
    }

    private function sendReminders(): void
    {
        $orders = Order::where('status', 'shipped')
            ->whereNotNull('shipped_at')
            ->whereBetween('shipped_at', [now()->subDays(3)->startOfDay(), now()->subDays(3)->endOfDay()])
            ->with('user')
            ->get();

        foreach ($orders as $order) {
            Notification::send(
                $order->user_id,
                'order_status',
                'Sudah terima paketmu?',
                'Pesanan ' . $order->invoice_number . ' sudah dikirim 3 hari lalu. Jika sudah diterima, konfirmasi di halaman pesanan.',
                route('orders.show', $order->invoice_number)
            );

            Log::info('Sent delivery reminder', ['invoice' => $order->invoice_number]);
            $this->line("Reminder sent: {$order->invoice_number}");
        }

        $this->info("Sent reminder to {$orders->count()} order(s).");
    }
}
