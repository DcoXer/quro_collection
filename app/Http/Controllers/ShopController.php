<?php

namespace App\Http\Controllers;

use App\Models\FeaturedProduct;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\HeroSlide;
use App\Models\Order;
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

        $heroSlides = HeroSlide::active()->forPlacement('welcome')->orderBy('sort_order')->get()
            ->flatMap(fn ($record) => collect($record->getPaths())
                ->map(fn ($path) => (object) ['image' => $path, 'type' => $record->type])
            );

        $featuredProducts = FeaturedProduct::active()
            ->with(['product' => fn($q) => $q->with(['variants', 'category'])])
            ->orderBy('id')
            ->get()
            ->map(fn($fp) => $fp->product)
            ->filter()
            ->values();

        $wishlistedIds = $this->getWishlistedIds();

        $totalProductsCount = Product::where('is_active', true)->count();
        $activeFlashSale    = FlashSale::active()->first();
        $activeOrdersCount  = 0;
        $wishlistCount      = 0;

        $latestProducts = Product::where('is_active', true)
            ->whereNotNull('image')
            ->latest()
            ->limit(6)
            ->get();

        $sort = $request->input('sort', 'terbaru');

        $products = Product::with(['category', 'media'])
            ->where('is_active', true)
            ->withCount('reviews as review_count')
            ->withAvg('reviews as avg_rating', 'rating')
            ->when($sort === 'terbaru',     fn($q) => $q->latest())
            ->when($sort === 'harga-asc',   fn($q) => $q->orderBy('price', 'asc'))
            ->when($sort === 'harga-desc',  fn($q) => $q->orderBy('price', 'desc'))
            ->when($sort === 'terpopuler',  fn($q) => $q->orderByDesc('review_count'))
            ->when($request->search,        fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->paginate(12)
            ->withQueryString();

        if (auth()->check()) {
            $activeOrdersCount = Order::where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'processing', 'shipped'])
                ->count();
            $wishlistCount = Wishlist::where('user_id', auth()->id())->count();
        }

        return view('shop.index', compact(
            'categories', 'heroSlides', 'wishlistedIds',
            'featuredProducts', 'totalProductsCount',
            'activeFlashSale', 'activeOrdersCount', 'wishlistCount',
            'latestProducts', 'products'
        ));
    }

    public function activityCounts()
    {
        $user = auth()->user();

        return response()->json([
            'cartCount'          => collect(session('cart', []))->sum('quantity'),
            'wishlistCount'      => Wishlist::where('user_id', $user->id)->count(),
            'activeOrdersCount'  => Order::where('user_id', $user->id)
                                        ->whereIn('status', ['pending', 'processing', 'shipped'])
                                        ->count(),
            'unread'             => $user->unreadNotificationsCount(),
            'recentCount'        => count(session('recently_viewed', [])),
        ]);
    }

    public function show(Product $product)
    {
        abort_if(!$product->is_active, 404);

        // Track recently viewed (session, max 8, exclude current)
        $viewed = session()->get('recently_viewed', []);
        $viewed = array_filter($viewed, fn($id) => $id !== $product->id);
        array_unshift($viewed, $product->id);
        session()->put('recently_viewed', array_slice($viewed, 0, 8));

        $related = Product::with(['category', 'media'])
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
            ? Product::with(['category', 'media'])->whereIn('id', array_slice($recentIds, 0, 4))
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

        // Track recently viewed (same logic as show())
        $viewed = session()->get('recently_viewed', []);
        $viewed = array_filter($viewed, fn($id) => $id !== $product->id);
        array_unshift($viewed, $product->id);
        session()->put('recently_viewed', array_slice($viewed, 0, 8));

        $flashItem = FlashSaleItem::whereHas('flashSale', fn($q) => $q->active())
            ->where('product_id', $product->id)
            ->first();

        $flashPrice = null;
        if ($flashItem) {
            $flashPrice = $flashItem->discount_type === 'percent'
                ? $product->price - ($product->price * $flashItem->discount_value / 100)
                : $product->price - $flashItem->discount_value;
            $flashPrice = max(0, (int) $flashPrice);
        }

        $inWishlist = auth()->check()
            ? Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists()
            : false;

        return response()->json([
            'id'                    => $product->id,
            'name'                  => $product->name,
            'slug'                  => $product->slug,
            'price'                 => $product->price,
            'price_formatted'       => 'Rp ' . number_format($product->price, 0, ',', '.'),
            'flash_price'           => $flashPrice,
            'flash_price_formatted' => $flashPrice !== null ? 'Rp ' . number_format($flashPrice, 0, ',', '.') : null,
            'description'           => $product->description,
            'stock'                 => $product->stock,
            'category'              => $product->category?->name,
            'image'                 => $product->image ? Storage::url($product->image) : null,
            'media'                 => $product->media->map(fn($m) => [
                'type' => $m->type,
                'url'  => Storage::url($m->path),
            ]),
            'variants' => $product->variants->map(fn($v) => [
                'size'            => $v->size,
                'effective_price' => $v->effective_price,
                'price_formatted' => 'Rp ' . number_format($v->effective_price, 0, ',', '.'),
                'stock'           => $v->stock,
            ]),
            'recentCount'    => count(session('recently_viewed', [])),
            'in_wishlist'    => $inWishlist,
            'wishlist_url'   => route('wishlist.toggle', $product->id),
        ]);
    }

    public function search(Request $request)
    {
        $products = Product::with(['category', 'media'])
            ->where('is_active', true)
            ->withCount('reviews as review_count')
            ->withAvg('reviews as avg_rating', 'rating')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when(
                $request->category && $request->category !== 'semua',
                fn($q) => $q->whereHas('category', fn($qq) => $qq->where('slug', $request->category))
            )
            ->latest()
            ->paginate(12);

        $wishlistedIds = $this->getWishlistedIds();

        return view('shop.partials.product-grid', compact('products', 'wishlistedIds'));
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
