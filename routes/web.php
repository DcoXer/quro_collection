<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\ResiController;
use Illuminate\Support\Facades\Route;


// Public
Route::get('/', function () {
    $heroSlides = \App\Models\HeroSlide::active()->forPlacement('welcome')->orderBy('sort_order')->get()
        ->flatMap(
            fn($record) => collect($record->getPaths())
                ->map(fn($path) => (object) ['image' => $path, 'type' => $record->type])
        );
    $categories    = \App\Models\Category::withCount(['products' => fn($q) => $q->where('is_active', true)])->get();
    $totalSold     = \App\Models\OrderItem::sum('quantity');
    $totalCustomers = \App\Models\User::count();
    $totalProducts = \App\Models\Product::where('is_active', true)->count();

    $activeFlashSale = \App\Models\FlashSale::active()
        ->with(['items' => fn($q) => $q->with(['product' => fn($q) => $q->where('is_active', true)->with('variants')])])
        ->first();

    $latestProducts = \App\Models\Product::where('is_active', true)
        ->whereNotNull('image')
        ->withCount('reviews as review_count')
        ->withAvg('reviews as avg_rating', 'rating')
        ->latest()
        ->limit(6)
        ->get();

    $testimonials = \App\Models\ProductReview::where('is_approved', true)
        ->where('rating', '>=', 4)
        ->whereNotNull('comment')
        ->with(['user', 'product'])
        ->latest()
        ->limit(4)
        ->get();

    return view('welcome', compact('heroSlides', 'categories', 'totalSold', 'totalCustomers', 'totalProducts', 'activeFlashSale', 'latestProducts', 'testimonials'));
})->name('home');
Route::get('/about', function () {
    $totalSold = \App\Models\OrderItem::sum('quantity');
    $page = \App\Models\Page::findBySlug('about');
    return view('about', compact('totalSold', 'page'));
})->name('about');
Route::get('/privacy-policy', function () {
    $page = \App\Models\Page::findBySlug('privacy-policy');
    return view('privacy', compact('page'));
})->name('privacy');
Route::get('/terms-of-service', function () {
    $page = \App\Models\Page::findBySlug('terms-of-service');
    return view('terms', compact('page'));
})->name('terms');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/search', [ShopController::class, 'search'])->name('shop.search');
Route::get('/product/{product:slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/product/{product:slug}/quick-view', [ShopController::class, 'quickView'])->name('shop.quick-view');
Route::get('/category/{category:slug}', [ShopController::class, 'category'])->name('shop.category');

// Google OAuth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Wilayah + Ongkir Route
Route::prefix('wilayah')->group(function () {
    Route::get('/provinsi', [WilayahController::class, 'provinsi'])->name('wilayah.provinsi');
    Route::get('/kabupaten/{id}', [WilayahController::class, 'kabupaten'])->name('wilayah.kabupaten');
    Route::get('/kecamatan/{id}', [WilayahController::class, 'kecamatan'])->name('wilayah.kecamatan');
    Route::get('/kelurahan/{id}', [WilayahController::class, 'kelurahan'])->name('wilayah.kelurahan');
});

Route::post('/ongkir', [WilayahController::class, 'ongkir'])->middleware('auth')->name('ongkir.check');

// OTP Verification
Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
Route::post('/verify-otp', [OtpController::class, 'verify'])->middleware('throttle:otp-verify')->name('otp.verify');
Route::post('/verify-otp/resend', [OtpController::class, 'resend'])->middleware('throttle:otp-resend')->name('otp.resend');

// Webhook
Route::post('/webhook/midtrans', [MidtransController::class, 'webhook'])->middleware('throttle:midtrans-webhook')->name('webhook.midtrans');
Route::get('/checkout/payment/{invoice}', [CheckoutController::class, 'payment'])->name('checkout.payment');

// Admin routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/resi/{order}/download', [ResiController::class, 'download'])->name('admin.resi.download');
});

// Auth required for user dashboard, cart, checkout, orders, etc
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->middleware('throttle:cart')->name('cart.add');
    Route::patch('/cart/update/{productId}', [CartController::class, 'update'])->middleware('throttle:cart')->name('cart.update');
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/change-size/{key}', [CartController::class, 'changeSize'])->middleware('throttle:cart')->name('cart.change-size');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:checkout')->name('checkout.store');
    Route::get('/checkout/success/{invoice}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/payment/{invoice}', [CheckoutController::class, 'payment'])->name('checkout.payment');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{invoice}', [OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{invoice}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/{invoice}/track', [OrderController::class, 'track'])->name('orders.track');
    Route::post('/orders/{invoice}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::post('/orders/{invoice}/check-payment', [MidtransController::class, 'checkPayment'])->middleware('throttle:6,1')->name('orders.check-payment');

    // Voucher
    Route::post('/voucher/apply', [VoucherController::class, 'apply'])->middleware('throttle:voucher')->name('voucher.apply');
    Route::delete('/voucher/remove', [VoucherController::class, 'remove'])->name('voucher.remove');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}/toggle', [WishlistController::class, 'toggle'])->middleware('throttle:wishlist')->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->middleware('throttle:wishlist')->name('wishlist.destroy');

    // Activity counts (for hero real-time update)
    Route::get('/api/activity-counts', [ShopController::class, 'activityCounts'])->name('api.activity-counts');
});

require __DIR__ . '/auth.php';
