<?php

namespace App\Services;

use App\Models\FlashSaleItem;
use App\Models\Product;
use App\Models\ProductVariant;

class CartService
{
    /**
     * Resolve cart items from session against DB (validate stock + get real prices).
     * Returns ['items' => [...], 'total' => int] or throws \Exception on error.
     *
     * @throws \Exception
     */
    public function resolveItems(array $cart): array
    {
        $items = [];
        $total = 0;

        // Load active flash sale items once
        $flashItems = FlashSaleItem::whereHas('flashSale', fn($q) => $q->active())
            ->get()
            ->keyBy('product_id');

        foreach ($cart as $item) {
            $product = Product::with('variants')->find($item['product_id']);

            if (!$product || !$product->is_active) {
                throw new \Exception("{$item['name']} tidak tersedia.");
            }

            [$basePrice, $stock] = $this->getPriceAndStock($product, $item['size']);

            if ($stock < $item['quantity']) {
                throw new \Exception("Stok {$product->name} size {$item['size']} tidak mencukupi.");
            }

            // Apply flash sale discount if active
            $price = $this->applyFlashDiscount($basePrice, $flashItems->get($product->id));

            $items[] = [
                'product'    => $product,
                'product_id' => $product->id,
                'size'       => $item['size'],
                'quantity'   => $item['quantity'],
                'price'      => $price,
            ];

            $total += $price * $item['quantity'];
        }

        return compact('items', 'total');
    }

    /**
     * Decrement stock for an item. Returns number of affected rows.
     * Always uses variant stock if variant exists, falls back to product stock.
     */
    public function decrementStock(int $productId, string $size, int $quantity): int
    {
        $variant = ProductVariant::where('product_id', $productId)
            ->where('size', $size)
            ->first();

        if ($variant) {
            return ProductVariant::where('product_id', $productId)
                ->where('size', $size)
                ->where('stock', '>=', $quantity)
                ->decrement('stock', $quantity);
        }

        return Product::where('id', $productId)
            ->where('stock', '>=', $quantity)
            ->decrement('stock', $quantity);
    }

    private function getPriceAndStock(Product $product, string $size): array
    {
        $variant = $product->variants->where('size', $size)->first();

        if ($variant) {
            return [$variant->effective_price, $variant->stock];
        }

        // Simple product without variants
        return [$product->price, $product->stock];
    }

    private function applyFlashDiscount(int $price, ?FlashSaleItem $flashItem): int
    {
        if (!$flashItem) {
            return $price;
        }

        $discounted = $flashItem->discount_type === 'percent'
            ? $price - ($price * $flashItem->discount_value / 100)
            : $price - $flashItem->discount_value;

        return max(0, (int) $discounted);
    }
}
