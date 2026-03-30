<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WilayahService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.apicoiid.key');
        $this->baseUrl = config('services.apicoiid.base_url');
    }

    private function fetch(string $endpoint, array $query = []): array
    {
        $cacheKey = 'wilayah_' . md5($endpoint . json_encode($query));

        return Cache::remember($cacheKey, 86400, function () use ($endpoint, $query) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders(['x-api-co-id' => $this->apiKey])
                    ->get(rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/'), $query);

                \Log::debug('Wilayah API call', ['endpoint' => $endpoint]);

                if ($response->successful()) {
                    return $response->json();
                }

                return ['is_success' => false, 'data' => []];
            } catch (\Exception $e) {
                \Log::error('Wilayah error: ' . $e->getMessage());
                return ['is_success' => false, 'data' => []];
            }
        });
    }

    public function provinsi(): array
    {
        return $this->fetch('/regional/indonesia/provinces');
    }

    public function kabupaten(string $provinceCode): array
    {
        return $this->fetch("/regional/indonesia/provinces/{$provinceCode}/regencies");
    }

    public function kecamatan(string $regencyCode): array
    {
        return $this->fetch("/regional/indonesia/regencies/{$regencyCode}/districts");
    }

    public function kelurahan(string $districtCode): array
    {
        return $this->fetch("/regional/indonesia/districts/{$districtCode}/villages");
    }
}
