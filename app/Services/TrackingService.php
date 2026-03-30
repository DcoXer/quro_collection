<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TrackingService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.binderbyte.api_key');
        $this->baseUrl = config('services.binderbyte.base_url');
    }

    public function track(string $courier, string $trackingNumber): array
    {
        $cacheKey = "tracking_{$courier}_{$trackingNumber}";

        // Cache 15 menit biar tidak spam API
        return Cache::remember($cacheKey, 900, function () use ($courier, $trackingNumber) {
            try {
                $response = Http::get($this->baseUrl . '/track', [
                    'api_key' => $this->apiKey,
                    'courier' => $courier,
                    'awb'     => $trackingNumber,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return ['status' => 'error', 'message' => 'Gagal mengambil data tracking.'];

            } catch (\Exception $e) {
                return ['status' => 'error', 'message' => 'Layanan tracking tidak tersedia.'];
            }
        });
    }
}