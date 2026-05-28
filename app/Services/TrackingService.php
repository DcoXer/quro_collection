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

        // Return dari cache jika ada data valid (bukan error)
        $cached = Cache::get($cacheKey);
        if ($cached && ($cached['status'] ?? '') !== 'error') {
            return $cached;
        }

        try {
            $response = Http::timeout(10)->get($this->baseUrl . '/track', [
                'api_key' => $this->apiKey,
                'courier' => $courier,
                'awb'     => $trackingNumber,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Cache 15 menit untuk data valid — error tidak di-cache agar bisa retry
                Cache::put($cacheKey, $data, 900);
                return $data;
            }

            return ['status' => 'error', 'message' => 'Gagal mengambil data tracking.'];

        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Layanan tracking tidak tersedia.'];
        }
    }
}