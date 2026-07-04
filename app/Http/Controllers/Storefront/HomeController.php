<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the storefront homepage.
     */
    public function index()
    {
        // Get new arrivals (e.g., latest 8 active products)
        $newArrivals = Product::where('is_active', true)
                              ->latest()
                              ->take(8)
                              ->get();
                              
        // Pre-fetch wishlist IDs if user is logged in
        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = \App\Models\Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('storefront.home', compact('newArrivals', 'wishlistIds'));
    }
}
