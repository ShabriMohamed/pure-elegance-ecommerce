<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        
        $products = Product::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate(12);
            
        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }
            
        return view('storefront.search', compact('products', 'query', 'wishlistIds'));
    }

    public function suggestions(Request $request)
    {
        $query = $request->input('q', '');
        if (empty($query)) {
            return response()->json([]);
        }
        
        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->take(5)
            ->get(['id', 'name', 'slug']);
            
        return response()->json($products);
    }
}
