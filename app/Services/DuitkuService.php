<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DuitkuService
{
    private string $merchantCode;
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->merchantCode = config('services.duitku.merchant_code');
        $this->apiKey       = config('services.duitku.api_key');
        $this->baseUrl      = config('services.duitku.base_url');
    }

    public function createTransaction(array $params): array
    {
        $signature = md5($this->merchantCode . $params['merchantOrderId'] . $params['paymentAmount'] . $this->apiKey);

        $payload = [
            'merchantCode'        => $this->merchantCode,
            'paymentAmount'       => $params['paymentAmount'],
            'paymentMethod'       => $params['paymentMethod'] ?? 'VC',
            'merchantOrderId'     => $params['merchantOrderId'],
            'productDetails'      => $params['productDetails'],
            'customerVaName'      => $params['customerName'],
            'email'               => $params['email'],
            'phoneNumber'         => $params['phone'] ?? '',
            'itemDetails'         => $params['itemDetails'],
            'customerDetail'      => [
                'firstName' => $params['customerName'],
                'email'     => $params['email'],
                'phoneNumber' => $params['phone'] ?? '',
            ],
            'callbackUrl'         => route('webhook.duitku'),
            'returnUrl'           => route('checkout.success', $params['merchantOrderId']),
            'signature'           => $signature,
            'expiryPeriod'        => 60,
        ];

        $response = Http::post($this->baseUrl . '/merchant/createInvoice', $payload);

        return $response->json();
    }

    public function verifyCallback(array $data): bool
    {
        $signature = md5($this->merchantCode . $data['amount'] . $data['merchantOrderId'] . $this->apiKey);
        return $signature === $data['signature'];
    }
}