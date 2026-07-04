<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);
        
        if ($request->has('gender') && in_array($request->gender, ['men', 'women'])) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->gender)->orWhere('slug', 'like', $request->gender . '-%');
            });
        }
        
        $products = $query->paginate(12);
        
        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }
        
        return view('storefront.category', compact('products', 'wishlistIds'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)->where('is_active', true)->paginate(12);
        
        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }
        
        return view('storefront.category', compact('category', 'products', 'wishlistIds'));
    }

    public function sale()
    {
        $products = Product::where('is_active', true)->where('is_on_sale', true)->paginate(12);
        
        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }
        
        return view('storefront.category', compact('products', 'wishlistIds'));
    }

    public function newArrivals()
    {
        $products = Product::where('is_active', true)->where('is_new_arrival', true)->paginate(12);
        
        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }
        
        return view('storefront.category', compact('products', 'wishlistIds'));
    }
}
