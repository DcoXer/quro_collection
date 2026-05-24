<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    private const BASE_URL = 'https://api.biteship.com/v1';

    // Mapping courier codes → Biteship courier_company + courier_service_code
    private const COURIER_MAP = [
        'jne' => ['company' => 'jne', 'service' => 'reg'],
        'jnt' => ['company' => 'j&t', 'service' => 'ez'],
        'j&t' => ['company' => 'j&t', 'service' => 'ez'],
    ];

    private function headers(): array
    {
        return [
            'Authorization' => config('services.biteship.api_key'),
            'Content-Type'  => 'application/json',
        ];
    }

    /**
     * Search Biteship area_id by district + city name.
     * Returns null if not found.
     */
    public function searchArea(string $query): ?string
    {
        $response = Http::withHeaders($this->headers())
            ->get(self::BASE_URL . '/maps/areas', [
                'input' => $query,
                'type'  => 'single',
            ]);

        if ($response->failed()) {
            Log::warning('Biteship area search failed', [
                'query'  => $query,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return null;
        }

        $areas = $response->json('areas', []);

        return $areas[0]['id'] ?? null;
    }

    /**
     * Get a rates_id for the specified courier route.
     * Biteship requires a rates_id for order creation with regular couriers (JNE/J&T).
     *
     * @throws \Exception
     */
    private function getRatesId(Order $order, array $courier, string $destAreaId): string
    {
        $response = Http::withHeaders($this->headers())
            ->post(self::BASE_URL . '/rates/couriers', [
                'origin_area_id'      => config('services.biteship.shipper_area_id'),
                'destination_area_id' => $destAreaId,
                'couriers'            => $courier['company'],
                'items'               => $this->buildItems($order),
            ]);

        if ($response->failed()) {
            Log::error('Biteship rate check failed', [
                'invoice' => $order->invoice_number,
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);
            throw new \Exception('Biteship rate check failed: ' . $response->body());
        }

        $pricing = $response->json('pricing', []);

        // Find the matching service (e.g. jne+reg or j&t+ez)
        $rate = collect($pricing)->first(function ($r) use ($courier) {
            return strtolower($r['courier_company'] ?? '') === $courier['company']
                && strtolower($r['courier_service_code'] ?? '') === $courier['service'];
        });

        // If exact service not found, fall back to first available rate for the courier
        if (!$rate && count($pricing) > 0) {
            $rate = collect($pricing)->first(function ($r) use ($courier) {
                return strtolower($r['courier_company'] ?? '') === $courier['company'];
            });
        }

        if (!$rate) {
            throw new \Exception(
                'Biteship: no rate available for courier "' . $courier['company'] . '" to this destination.'
            );
        }

        Log::info('Biteship rate selected', [
            'invoice'  => $order->invoice_number,
            'courier'  => $rate['courier_company'] ?? '',
            'service'  => $rate['courier_service_name'] ?? '',
            'price'    => $rate['price'] ?? '',
            'rate_id'  => $rate['id'] ?? '',
        ]);

        return $rate['id'];
    }

    /**
     * Create a shipment order on Biteship and return the AWB (tracking number) + courier company.
     *
     * @throws \Exception
     */
    public function createOrder(Order $order): array
    {
        $courierCode = strtolower($order->courier ?? 'jne');
        $courier     = self::COURIER_MAP[$courierCode] ?? self::COURIER_MAP['jne'];

        // Search destination area using district + city name stored on order
        $areaQuery  = trim(($order->district_name ?? '') . ' ' . ($order->city_name ?? ''));
        $destAreaId = $areaQuery ? $this->searchArea($areaQuery) : null;

        if (!$destAreaId) {
            throw new \Exception(
                'Biteship: destination area not found for "' . $areaQuery . '" (order: ' . $order->invoice_number . ')'
            );
        }

        // Get rates_id first — required for regular couriers (JNE/J&T)
        $ratesId = $this->getRatesId($order, $courier, $destAreaId);

        $payload = [
            'rates_id' => $ratesId,

            'shipper_contact_name'  => config('services.biteship.shipper_name'),
            'shipper_contact_phone' => config('services.biteship.shipper_phone'),
            'shipper_organization'  => config('services.biteship.shipper_name'),

            'origin_contact_name'   => config('services.biteship.shipper_name'),
            'origin_contact_phone'  => config('services.biteship.shipper_phone'),
            'origin_address'        => config('services.biteship.shipper_address'),
            'origin_area_id'        => config('services.biteship.shipper_area_id'),
            'origin_note'           => '',

            'destination_contact_name'  => $order->shipping_name,
            'destination_contact_phone' => $order->shipping_phone,
            'destination_address'       => $order->shipping_address,
            'destination_area_id'       => $destAreaId,
            'destination_note'          => '',

            'order_note' => 'Invoice: ' . $order->invoice_number,

            'items' => $this->buildItems($order),
        ];

        $response = Http::withHeaders($this->headers())
            ->post(self::BASE_URL . '/orders', $payload);

        if ($response->failed()) {
            Log::error('Biteship create order failed', [
                'invoice' => $order->invoice_number,
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);
            throw new \Exception('Biteship create order failed: ' . $response->body());
        }

        $data       = $response->json();
        $trackingId = $data['courier']['tracking_id'] ?? null;
        $company    = $data['courier']['company'] ?? $courierCode;

        if (!$trackingId) {
            throw new \Exception('Biteship returned no tracking_id for order: ' . $order->invoice_number);
        }

        Log::info('Biteship order created', [
            'invoice'     => $order->invoice_number,
            'tracking_id' => $trackingId,
            'courier'     => $company,
        ]);

        return [
            'tracking_number' => $trackingId,
            'courier'         => $company,
        ];
    }

    private function buildItems(Order $order): array
    {
        return $order->items->map(fn($item) => [
            'name'        => mb_substr(
                ($item->product->name ?? 'Produk') . ' (' . ($item->size ?? '-') . ')',
                0, 50
            ),
            'description' => $item->size ?? '',
            'value'       => (int) $item->price,
            'weight'      => 500, // default 500g per item
            'quantity'    => (int) $item->quantity,
        ])->toArray();
    }
}
