<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    private string $token;
    private string $adminPhone;

    public function __construct()
    {
        $this->token      = config('services.fonnte.token');
        $this->adminPhone = config('services.fonnte.admin_phone');
    }

    public function notifyAdminNewOrder(string $invoiceNumber, string $customerName, int $totalAmount): void
    {
        $total   = 'Rp ' . number_format($totalAmount, 0, ',', '.');
        $message = "🛍️ *Pesanan Baru Masuk!*\n\n"
            . "Invoice : {$invoiceNumber}\n"
            . "Customer: {$customerName}\n"
            . "Total   : {$total}\n\n"
            . "Status sudah *DIBAYAR* — segera proses pesanan.";

        $this->send($this->adminPhone, $message);
    }

    private function send(string $target, string $message): void
    {
        try {
            $response = Http::withHeaders(['Authorization' => $this->token])
                ->post('https://api.fonnte.com/send', [
                    'target'  => $target,
                    'message' => $message,
                ]);

            if (!$response->successful()) {
                Log::warning('Fonnte: failed to send WA notification', [
                    'target'   => $target,
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Fonnte: exception when sending WA notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
