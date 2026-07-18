<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\User;
use App\Observers\AdminAuditObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Audit trail: log admin-initiated changes to key domain models.
        foreach ([Product::class, Category::class, Banner::class, Promotion::class, SiteSetting::class, Order::class, Review::class, User::class] as $model) {
            $model::observe(AdminAuditObserver::class);
        }

        // Admin sidebar badge counts — computed once per admin page in a composer
        // instead of inline queries in the Blade layout.
        View::composer('layouts.admin', function ($view) {
            $view->with('pendingOrdersCount', Order::whereIn('status', [Order::STATUS_PENDING, Order::STATUS_WHATSAPP_SENT])->count());
            $view->with('pendingReviewsCount', Review::where('is_approved', false)->count());
        });
    }
}
