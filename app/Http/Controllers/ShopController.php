<?php

namespace App\Http\Controllers;

use App\Models\FlashSaleItem;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Wishlist;
use App\Models\Category;
use App\Support\SchemaOrg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with(['products' => function ($query) use ($request) {
            $query->where('is_active', true)
                ->withCount('reviews as review_count')
                ->withAvg('reviews as avg_rating', 'rating')
                ->when($request->search, function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
        }])->get()->filter(fn($cat) => $cat->products->isNotEmpty());

        $heroSlides = HeroSlide::active()->forPlacement('shop')->orderBy('sort_order')->get()
            ->flatMap(fn ($record) => collect($record->getPaths())
                ->map(fn ($path) => (object) ['image' => $path, 'type' => $record->type])
            );

        $wishlistedIds = $this->getWishlistedIds();

        return view('shop.index', compact('categories', 'heroSlides', 'wishlistedIds'));
    }

    public function show(Product $product)
    {
        abort_if(!$product->is_active, 404);

        // Track recently viewed (session, max 8, exclude current)
        $viewed = session()->get('recently_viewed', []);
        $viewed = array_filter($viewed, fn($id) => $id !== $product->id);
        array_unshift($viewed, $product->id);
        session()->put('recently_viewed', array_slice($viewed, 0, 8));

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        // Recently viewed: exclude current product, max 4
        $recentIds = array_values(array_filter(
            session()->get('recently_viewed', []),
            fn($id) => $id !== $product->id
        ));
        $recentlyViewed = count($recentIds)
            ? Product::with('category')->whereIn('id', array_slice($recentIds, 0, 4))
                ->where('is_active', true)->get()
                ->sortBy(fn($p) => array_search($p->id, $recentIds))->values()
            : collect();

        $reviews      = $product->reviews()->with('user')->get();
        $avgRating    = $product->averageRating();
        $reviewCount  = $product->reviewCount();

        $flashItem = FlashSaleItem::whereHas('flashSale', fn($q) => $q->active())
            ->where('product_id', $product->id)
            ->with('flashSale')
            ->first();

        $jsonLd = SchemaOrg::productPage($product, $reviewCount, $avgRating, $flashItem);

        $inWishlist = auth()->check()
            ? Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists()
            : false;

        return view('shop.show', compact('product', 'related', 'recentlyViewed', 'reviews', 'avgRating', 'reviewCount', 'inWishlist', 'flashItem', 'jsonLd'));
    }

    public function quickView(Product $product)
    {
        abort_if(!$product->is_active, 404);

        return response()->json([
            'id'              => $product->id,
            'name'            => $product->name,
            'slug'            => $product->slug,
            'price'           => $product->price,
            'price_formatted' => 'Rp ' . number_format($product->price, 0, ',', '.'),
            'description'     => $product->description,
            'stock'           => $product->stock,
            'category'        => $product->category?->name,
            'image'           => $product->image ? Storage::url($product->image) : null,
            'media'           => $product->media->map(fn($m) => [
                'type' => $m->type,
                'url'  => Storage::url($m->path),
            ]),
            'variants' => $product->variants->map(fn($v) => [
                'size'            => $v->size,
                'effective_price' => $v->effective_price,
                'price_formatted' => 'Rp ' . number_format($v->effective_price, 0, ',', '.'),
                'stock'           => $v->stock,
            ]),
        ]);
    }

    public function search(Request $request)
    {
        $categories = Category::with(['products' => function ($query) use ($request) {
            $query->where('is_active', true)
                ->withCount('reviews as review_count')
                ->withAvg('reviews as avg_rating', 'rating')
                ->when($request->search, function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
        }])->get()->filter(fn($cat) => $cat->products->isNotEmpty());

        $wishlistedIds = $this->getWishlistedIds();

        return view('shop.partials.product-grid', compact('categories', 'wishlistedIds'));
    }

    public function category(Category $category, Request $request)
    {
        $products = Product::with('category')
            ->withCount('reviews as review_count')
            ->withAvg('reviews as avg_rating', 'rating')
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->paginate(12);

        $wishlistedIds = $this->getWishlistedIds();

        return view('shop.category', compact('category', 'products', 'wishlistedIds'));
    }

    private function getWishlistedIds(): array
    {
        if (!auth()->check()) return [];

        return Wishlist::where('user_id', auth()->id())
            ->pluck('product_id')
            ->toArray();
    }
}
