<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;

class CartService
{
    const BASE_SIZES = ['S', 'M', 'L', 'XL'];

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

        foreach ($cart as $item) {
            $product = Product::with('variants')->find($item['product_id']);

            if (!$product || !$product->is_active) {
                throw new \Exception("{$item['name']} tidak tersedia.");
            }

            [$price, $stock] = $this->getPriceAndStock($product, $item['size']);

            if ($stock < $item['quantity']) {
                throw new \Exception("Stok {$product->name} size {$item['size']} tidak mencukupi.");
            }

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
     */
    public function decrementStock(int $productId, string $size, int $quantity): int
    {
        if (in_array($size, self::BASE_SIZES)) {
            return Product::where('id', $productId)
                ->where('stock', '>=', $quantity)
                ->decrement('stock', $quantity);
        }

        return ProductVariant::where('product_id', $productId)
            ->where('size', $size)
            ->where('stock', '>=', $quantity)
            ->decrement('stock', $quantity);
    }

    private function getPriceAndStock(Product $product, string $size): array
    {
        if (in_array($size, self::BASE_SIZES)) {
            return [$product->price, $product->stock];
        }

        $variant = $product->variants()->where('size', $size)->first();

        if (!$variant) {
            throw new \Exception("Varian size {$size} tidak ditemukan untuk {$product->name}.");
        }

        return [$variant->price, $variant->stock];
    }
}
