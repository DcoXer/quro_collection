<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OngkirService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.apicoiid.key');
        $this->baseUrl = 'https://use.api.co.id';
    }

    public function check(string $originVillageCode, string $destinationVillageCode, float $weight): array
    {
        $cacheKey = "ongkir_{$originVillageCode}_{$destinationVillageCode}_{$weight}";

        // Return cached result only if it was a success — never cache errors
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders(['x-api-co-id' => $this->apiKey])
                ->get($this->baseUrl . '/expedition/shipping-cost', [
                    'origin_village_code'      => $originVillageCode,
                    'destination_village_code' => $destinationVillageCode,
                    'weight'                   => $weight,
                ]);

            \Log::info('Ongkir response: ' . $response->body());

            if ($response->successful() && ($response->json('is_success') === true)) {
                $result = $response->json();
                Cache::put($cacheKey, $result, 3600);
                return $result;
            }

            return ['is_success' => false, 'message' => $response->json('message', 'Gagal mengecek ongkos kirim.')];
        } catch (\Exception $e) {
            \Log::error('Ongkir error: ' . $e->getMessage());
            return ['is_success' => false, 'message' => 'Gagal mengecek ongkos kirim.'];
        }
    }
}