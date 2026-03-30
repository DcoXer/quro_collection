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

        return Cache::remember($cacheKey, 3600, function () use ($originVillageCode, $destinationVillageCode, $weight) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders(['x-api-co-id' => $this->apiKey])
                    ->get($this->baseUrl . '/expedition/shipping-cost', [
                        'origin_village_code'      => $originVillageCode,
                        'destination_village_code' => $destinationVillageCode,
                        'weight'                   => $weight,
                    ]);

                \Log::info('Ongkir response: ' . $response->body());

                if ($response->successful()) {
                    return $response->json();
                }

                return ['status' => 'error', 'result' => []];
            } catch (\Exception $e) {
                \Log::error('Ongkir error: ' . $e->getMessage());
                return ['status' => 'error', 'result' => []];
            }
        });
    }
}