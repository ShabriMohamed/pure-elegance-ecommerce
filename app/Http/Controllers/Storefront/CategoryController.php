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
    /**
     * Product listing page with optional gender filter.
     * /categories           → all active products
     * /categories?gender=men   → men's products (by product.gender column)
     * /categories?gender=women → women's products
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('primaryImage');

        if ($request->has('gender') && in_array($request->gender, ['men', 'women'])) {
            $query->where('gender', $request->gender);
        }

        $products = $query->latest()->paginate(12);

        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }

        return view('storefront.category', compact('products', 'wishlistIds'));
    }

    /**
     * Show a single category by slug.
     * Includes products from child categories (recursive).
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Get all descendant category IDs (children + grandchildren)
        $categoryIds = $this->getDescendantIds($category);
        $categoryIds[] = $category->id;

        $products = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->with('primaryImage')
            ->latest()
            ->paginate(12);

        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }

        return view('storefront.category', compact('category', 'products', 'wishlistIds'));
    }

    /**
     * Sale page — products where sale_price < price.
     */
    public function sale()
    {
        $products = Product::where('is_active', true)
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->with('primaryImage')
            ->latest()
            ->paginate(12);

        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }

        return view('storefront.category', [
            'products' => $products,
            'wishlistIds' => $wishlistIds,
            'pageTitle' => 'Sale',
        ]);
    }

    /**
     * New Arrivals page.
     */
    public function newArrivals()
    {
        $products = Product::where('is_active', true)
            ->where('is_new_arrival', true)
            ->with('primaryImage')
            ->latest()
            ->paginate(12);

        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }

        return view('storefront.category', [
            'products' => $products,
            'wishlistIds' => $wishlistIds,
            'pageTitle' => 'New Arrivals',
        ]);
    }

    /**
     * Recursively get all descendant category IDs.
     */
    private function getDescendantIds(Category $category): array
    {
        $ids = [];
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }
        return $ids;
    }
}
