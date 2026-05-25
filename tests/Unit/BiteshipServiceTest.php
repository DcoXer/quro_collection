<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\BiteshipService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BiteshipServiceTest extends TestCase
{
    private function makeOrder(array $overrides = []): Order
    {
        $order = new Order(array_merge([
            'id'               => 1,
            'invoice_number'   => 'INV-TEST001',
            'courier'          => 'jne',
            'courier_service'  => 'REG',
            'shipping_name'    => 'Budi Santoso',
            'shipping_phone'   => '08123456789',
            'shipping_address' => 'Jl. Merdeka No. 1',
            'district_name'    => 'Ciputat',
            'city_name'        => 'Tangerang Selatan',
            'total_amount'     => 200000,
        ], $overrides));

        $product = new Product(['name' => 'Kokoh Putih']);
        $item = new OrderItem([
            'size'     => 'XL',
            'price'    => 200000,
            'quantity' => 1,
        ]);
        $item->setRelation('product', $product);
        $order->setRelation('items', collect([$item]));

        return $order;
    }

    // ── SEARCH AREA ──────────────────────────────────────────────────────────

    #[Test]
    public function it_returns_area_id_when_biteship_area_search_succeeds(): void
    {
        Http::fake([
            '*/maps/areas*' => Http::response([
                'areas' => [['id' => 'AREA123', 'name' => 'Ciputat']],
            ], 200),
        ]);

        $result = app(BiteshipService::class)->searchArea('Ciputat Tangerang Selatan');

        $this->assertSame('AREA123', $result);
    }

    #[Test]
    public function it_returns_null_when_biteship_area_search_fails(): void
    {
        Http::fake([
            '*/maps/areas*' => Http::response(['error' => 'not found'], 422),
        ]);

        $result = app(BiteshipService::class)->searchArea('Unknown Place');

        $this->assertNull($result);
    }

    #[Test]
    public function it_returns_null_when_area_results_are_empty(): void
    {
        Http::fake([
            '*/maps/areas*' => Http::response(['areas' => []], 200),
        ]);

        $result = app(BiteshipService::class)->searchArea('Nowhere');

        $this->assertNull($result);
    }

    // ── CREATE ORDER — SUCCESS PATHS ─────────────────────────────────────────

    #[Test]
    public function it_creates_order_directly_without_rates_api_on_first_try(): void
    {
        Http::fake([
            '*/maps/areas*' => Http::response([
                'areas' => [['id' => 'DEST001']],
            ], 200),
            '*/orders' => Http::response([
                'courier' => ['tracking_id' => 'JNE123456789', 'company' => 'jne'],
            ], 200),
        ]);

        $result = app(BiteshipService::class)->createOrder($this->makeOrder());

        $this->assertSame('JNE123456789', $result['tracking_number']);
        $this->assertSame('jne', $result['courier']);

        // Rates API must NOT have been called
        Http::assertNotSent(fn ($req) => str_contains($req->url(), '/rates/couriers'));
    }

    #[Test]
    public function it_falls_back_to_rates_flow_when_direct_order_creation_fails(): void
    {
        Http::fake([
            '*/maps/areas*' => Http::response([
                'areas' => [['id' => 'DEST001']],
            ], 200),
            '*/rates/couriers' => Http::response([
                'pricing' => [[
                    'id'                   => 'RATE-XYZ',
                    'courier_company'      => 'jne',
                    'courier_service_code' => 'reg',
                    'courier_service_name' => 'REG',
                    'price'                => 15000,
                ]],
            ], 200),
            '*/orders' => Http::sequence()
                ->push(['error' => 'Bad Request'], 400)
                ->push([
                    'courier' => ['tracking_id' => 'JNE987654321', 'company' => 'jne'],
                ], 200),
        ]);

        $result = app(BiteshipService::class)->createOrder($this->makeOrder());

        $this->assertSame('JNE987654321', $result['tracking_number']);
        Http::assertSent(fn ($req) => str_contains($req->url(), '/rates/couriers'));
    }

    // ── CREATE ORDER — FAILURE: INSUFFICIENT BALANCE ON RATES API ────────────

    #[Test]
    public function it_throws_when_rates_api_returns_insufficient_balance(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Biteship rate check failed/');

        Http::fake([
            '*/maps/areas*' => Http::response([
                'areas' => [['id' => 'DEST001']],
            ], 200),
            '*/rates/couriers' => Http::response([
                'success' => false,
                'error'   => 'No sufficient balance to call rates API. Please top up your balance',
            ], 402),
            '*/orders' => Http::response(['error' => 'Bad Request'], 400),
        ]);

        app(BiteshipService::class)->createOrder($this->makeOrder());
    }

    // ── CREATE ORDER — FAILURE: AREA NOT FOUND ───────────────────────────────

    #[Test]
    public function it_throws_when_destination_area_is_not_found(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/destination area not found/');

        Http::fake([
            '*/maps/areas*' => Http::response(['areas' => []], 200),
        ]);

        app(BiteshipService::class)->createOrder($this->makeOrder());
    }

    // ── CREATE ORDER — FAILURE: BOTH FLOWS FAIL ──────────────────────────────

    #[Test]
    public function it_throws_when_both_direct_and_rates_order_creation_fail(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Biteship create order failed/');

        Http::fake([
            '*/maps/areas*' => Http::response([
                'areas' => [['id' => 'DEST001']],
            ], 200),
            '*/rates/couriers' => Http::response([
                'pricing' => [[
                    'id'                   => 'RATE-XYZ',
                    'courier_company'      => 'jne',
                    'courier_service_code' => 'reg',
                    'price'                => 15000,
                ]],
            ], 200),
            '*/orders' => Http::response(['error' => 'Internal Server Error'], 500),
        ]);

        app(BiteshipService::class)->createOrder($this->makeOrder());
    }

    #[Test]
    public function it_throws_when_response_has_no_tracking_id(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/no tracking_id/');

        Http::fake([
            '*/maps/areas*' => Http::response([
                'areas' => [['id' => 'DEST001']],
            ], 200),
            '*/orders' => Http::response([
                'courier' => ['company' => 'jne'],
            ], 200),
        ]);

        app(BiteshipService::class)->createOrder($this->makeOrder());
    }

    // ── COURIER MAPPING ──────────────────────────────────────────────────────

    #[Test]
    public function it_maps_jnt_courier_code_correctly(): void
    {
        Http::fake([
            '*/maps/areas*' => Http::response([
                'areas' => [['id' => 'DEST001']],
            ], 200),
            '*/orders' => Http::response([
                'courier' => ['tracking_id' => 'JNT123', 'company' => 'j&t'],
            ], 200),
        ]);

        $result = app(BiteshipService::class)->createOrder($this->makeOrder(['courier' => 'jnt']));

        $this->assertSame('JNT123', $result['tracking_number']);

        Http::assertSent(function ($req) {
            return str_contains($req->url(), '/orders')
                && $req->data()['courier_company'] === 'j&t';
        });
    }

    #[Test]
    public function it_falls_back_to_jne_for_unknown_courier_code(): void
    {
        Http::fake([
            '*/maps/areas*' => Http::response([
                'areas' => [['id' => 'DEST001']],
            ], 200),
            '*/orders' => Http::response([
                'courier' => ['tracking_id' => 'JNE000', 'company' => 'jne'],
            ], 200),
        ]);

        app(BiteshipService::class)->createOrder($this->makeOrder(['courier' => 'sicepat']));

        Http::assertSent(function ($req) {
            return str_contains($req->url(), '/orders')
                && $req->data()['courier_company'] === 'jne';
        });
    }
}
