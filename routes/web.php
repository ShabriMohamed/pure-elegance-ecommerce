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
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

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
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{itemId}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{itemId}', [CartController::class, 'remove'])->name('remove');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/apply-promo', [CheckoutController::class, 'applyPromo'])->name('apply-promo');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
});

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AccountController::class, 'showOrder'])->name('orders.show');
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::patch('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    });
});

Route::get('/page/{slug}', function ($slug) {
    return view('storefront.page', compact('slug'));
})->name('page.show');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('products', AdminProductController::class);
    
    Route::resource('categories', AdminCategoryController::class);
    
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
    
    Route::resource('customers', AdminCustomerController::class)->only(['index', 'show']);
    
    Route::resource('banners', AdminBannerController::class)->except(['show']);
    
    Route::resource('promotions', AdminPromotionController::class)->except(['show']);
    
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingController::class, 'store'])->name('settings.store');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});

require __DIR__.'/auth.php';
