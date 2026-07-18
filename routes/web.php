<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\CategoryController;
use App\Http\Controllers\Storefront\ProductController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\WishlistController;
use App\Http\Controllers\Storefront\AccountController;
use App\Http\Controllers\Storefront\SearchController;
use App\Http\Controllers\Storefront\NewsletterController;
use App\Http\Controllers\Storefront\ReviewController;
use App\Http\Controllers\Storefront\PageController;
use App\Http\Controllers\Storefront\OrderTrackController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\SearchController as AdminSearchController;

// Storefront Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/sale', [CategoryController::class, 'sale'])->name('sale');
Route::get('/new-arrivals', [CategoryController::class, 'newArrivals'])->name('new-arrivals');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add')->middleware('throttle:30,1');
    Route::patch('/update/{itemId}', [CartController::class, 'update'])->name('update')->middleware('throttle:60,1');
    Route::delete('/remove/{itemId}', [CartController::class, 'remove'])->name('remove')->middleware('throttle:60,1');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/apply-promo', [CheckoutController::class, 'applyPromo'])->name('apply-promo')->middleware('throttle:10,1');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process')->middleware('throttle:10,1');
    Route::get('/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('confirmation');
    Route::post('/whatsapp/{order}', [CheckoutController::class, 'whatsapp'])->name('whatsapp')->middleware('throttle:15,1');
});

// Newsletter signup (real endpoint — stores subscribers, throttled)
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store')->middleware('throttle:5,1');

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Product reviews (one per customer per product; awaits admin moderation)
    Route::post('/product/{product}/reviews', [ReviewController::class, 'store'])
        ->name('product.reviews.store')->middleware('throttle:10,1');

    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AccountController::class, 'showOrder'])->name('orders.show');
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::patch('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    });
});

// Static content pages (About / Privacy / Terms …) — 404s on unknown/inactive slugs
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Public order tracking / summary link (token-authenticated; drives the WhatsApp
// order card via Open Graph and lets customers track their order status).
Route::get('/order/{token}', [OrderTrackController::class, 'show'])->name('order.track');

// Local-only: render the order emails in the browser for design/preview
// (e.g. /dev/mail/order/1/customer). Never registered outside local.
if (app()->environment('local')) {
    Route::get('/dev/mail/order/{order}/{type}', function (\App\Models\Order $order, string $type) {
        abort_unless(in_array($type, ['customer', 'vendor'], true), 404);
        $order->load('items.product.primaryImage');

        return $type === 'customer'
            ? new \App\Mail\OrderPlacedCustomer($order)
            : new \App\Mail\OrderPlacedVendor($order);
    })->name('dev.mail.order');
}

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('search', [AdminSearchController::class, 'search'])->name('search');
    
    Route::resource('products', AdminProductController::class)->except(['show']);
    
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
    
    Route::resource('customers', AdminCustomerController::class)->only(['index', 'show']);
    Route::patch('customers/{customer}/toggle-active', [AdminCustomerController::class, 'toggleActive'])->name('customers.toggle-active');
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.destroy');
    Route::patch('products/{product}/images/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.set-primary');
    
    Route::resource('banners', AdminBannerController::class)->except(['show']);

    Route::resource('brands', AdminBrandController::class)->except(['show']);
    
    Route::resource('promotions', AdminPromotionController::class)->except(['show']);
    
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingController::class, 'store'])->name('settings.store');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Review moderation
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Activity log (read-only audit trail)
    Route::get('activity', [AdminActivityLogController::class, 'index'])->name('activity.index');
});

require __DIR__.'/auth.php';
