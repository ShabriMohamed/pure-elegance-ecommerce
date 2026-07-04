<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class HomeController extends Controller
{
    /**
     * Show the storefront homepage with dynamic data from the database.
     */
    public function index()
    {
        // Featured products (is_featured = true)
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('primaryImage')
            ->take(8)
            ->get();

        // New arrivals (is_new_arrival = true, fallback to latest)
        $newArrivals = Product::where('is_active', true)
            ->where('is_new_arrival', true)
            ->with('primaryImage')
            ->latest()
            ->take(8)
            ->get();

        // If no new arrivals flagged, show latest products
        if ($newArrivals->isEmpty()) {
            $newArrivals = Product::where('is_active', true)
                ->with('primaryImage')
                ->latest()
                ->take(8)
                ->get();
        }

        // Active hero banners
        $banners = Banner::where('is_active', true)
            ->where('position', 'hero')
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('sort_order')
            ->get();

        // Pre-fetch wishlist IDs (avoid N+1)
        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('storefront.home', compact(
            'featuredProducts',
            'newArrivals',
            'banners',
            'wishlistIds'
        ));
    }
}
