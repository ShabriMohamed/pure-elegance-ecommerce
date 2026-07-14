<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        
        $products = Product::with('primaryImage')
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('brand', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->paginate(12);
            
        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }
            
        return view('storefront.search', compact('products', 'query', 'wishlistIds'));
    }

    /**
     * Real-time search suggestions endpoint.
     * Returns JSON with products and categories matching the query.
     */
    public function suggestions(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['products' => [], 'categories' => []]);
        }

        $products = Product::with('primaryImage', 'category')
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('brand', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->orderBy('is_featured', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($p) {
                return [
                    'name'         => $p->name,
                    'slug'         => $p->slug,
                    'url'          => route('product.show', $p->slug),
                    'image'        => $p->primary_image_url,
                    'price'        => number_format($p->price, 2),
                    'sale_price'   => $p->sale_price ? number_format($p->sale_price, 2) : null,
                    'category'     => $p->category->name ?? null,
                    'brand'        => $p->brand,
                ];
            });

        $categories = Category::where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get()
            ->map(function ($c) {
                return [
                    'name' => $c->name,
                    'url'  => route('category.show', $c->slug),
                ];
            });

        return response()->json([
            'products'   => $products,
            'categories' => $categories,
            'query'      => $query,
        ]);
    }
}
