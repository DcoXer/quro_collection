<?php

namespace App\Support;

use App\Models\FlashSaleItem;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SchemaOrg
{
    /**
     * Global site-level JSON-LD (Organization + WebSite).
     * Used in the main layout head.
     */
    public static function site(): string
    {
        return self::encode([
            '@context' => 'https://schema.org',
            '@graph'   => [
                self::organization(),
                self::website(),
            ],
        ]);
    }

    /**
     * Product page JSON-LD (Product + BreadcrumbList).
     * Called from ShopController::show(), passed to view as $jsonLd.
     */
    public static function productPage(
        Product $product,
        int $reviewCount,
        float $avgRating,
        ?FlashSaleItem $flashItem = null
    ): string {
        return self::encode([
            '@context' => 'https://schema.org',
            '@graph'   => [
                self::product($product, $reviewCount, $avgRating, $flashItem),
                self::breadcrumbList($product),
            ],
        ]);
    }

    // ─── Private builders ───────────────────────────────────────────────────

    private static function organization(): array
    {
        return [
            '@type' => 'Organization',
            '@id'   => url('/') . '/#organization',
            'name'  => 'Quro Collection',
            'url'   => url('/'),
            'logo'  => [
                '@type' => 'ImageObject',
                'url'   => asset('images/logo/apple-touch-icon.png'),
            ],
            'contactPoint' => [
                '@type'             => 'ContactPoint',
                'telephone'         => '+62-831-0826-7397',
                'contactType'       => 'customer service',
                'availableLanguage' => 'Indonesian',
            ],
            'sameAs' => [],
        ];
    }

    private static function website(): array
    {
        return [
            '@type'     => 'WebSite',
            '@id'       => url('/') . '/#website',
            'url'       => url('/'),
            'name'      => 'Quro Collection',
            'publisher' => ['@id' => url('/') . '/#organization'],
            'potentialAction' => [
                '@type'  => 'SearchAction',
                'target' => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => route('shop.index') . '?search={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    private static function product(
        Product $product,
        int $reviewCount,
        float $avgRating,
        ?FlashSaleItem $flashItem = null
    ): array {
        $totalStock   = $product->variants->sum('stock');
        $availability = $totalStock > 0
            ? 'https://schema.org/InStock'
            : 'https://schema.org/OutOfStock';
        $imageUrl     = $product->image
            ? Storage::url($product->image)
            : asset('images/logo/apple-touch-icon.png');
        $price        = $flashItem ? $flashItem->flash_price : $product->price;

        $schema = [
            '@type'       => 'Product',
            '@id'         => route('shop.show', $product) . '/#product',
            'name'        => $product->name,
            'description' => Str::limit($product->description, 500),
            'image'       => $imageUrl,
            'url'         => route('shop.show', $product),
            'brand'       => ['@type' => 'Brand', 'name' => 'Quro Collection'],
            'category'    => $product->category?->name,
            'offers'      => [
                '@type'         => 'Offer',
                'url'           => route('shop.show', $product),
                'priceCurrency' => 'IDR',
                'price'         => (string) $price,
                'availability'  => $availability,
                'itemCondition' => 'https://schema.org/NewCondition',
                'seller'        => ['@type' => 'Organization', 'name' => 'Quro Collection'],
            ],
        ];

        if ($reviewCount > 0) {
            $schema['aggregateRating'] = [
                '@type'       => 'AggregateRating',
                'ratingValue' => (string) $avgRating,
                'reviewCount' => (string) $reviewCount,
                'bestRating'  => '5',
                'worstRating' => '1',
            ];
        }

        return $schema;
    }

    private static function breadcrumbList(Product $product): array
    {
        $items = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Shop', 'item' => route('shop.index')],
        ];

        if ($product->category) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => 3,
                'name'     => $product->category->name,
                'item'     => route('shop.category', $product->category),
            ];
            $items[] = [
                '@type'    => 'ListItem',
                'position' => 4,
                'name'     => $product->name,
                'item'     => route('shop.show', $product),
            ];
        } else {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => 3,
                'name'     => $product->name,
                'item'     => route('shop.show', $product),
            ];
        }

        return [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    private static function encode(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
