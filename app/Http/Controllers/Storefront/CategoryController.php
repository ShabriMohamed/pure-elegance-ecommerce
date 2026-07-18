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
     * Product listing page with optional gender / brand filters.
     * /categories                → all active products
     * /categories?gender=men      → men's products (by product.gender column)
     * /categories?gender=women    → women's products
     * /categories?brand=Maison    → products of a given brand (Shop by Brand carousel)
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('primaryImage')->withRatings();

        if ($request->has('gender') && in_array($request->gender, ['men', 'women'])) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $wishlistIds = [];
        if (Auth::check()) {
            $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }

        return view('storefront.category', compact('products', 'wishlistIds'));
    }

    /**
     * Show a single category by slug. Products live on leaf categories, so a parent
     * category page (e.g. "Men" from the homepage tiles) aggregates its whole subtree;
     * a leaf category shows just its own products. Admin management remains flat —
     * the hierarchy is used here for browsing only.
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $categoryIds = $this->descendantIds($category);
        $categoryIds[] = $category->id;

        $products = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->with('primaryImage')
            ->withRatings()
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
            ->withRatings()
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
            ->withRatings()
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
     * All descendant category IDs (children, grandchildren, ...) via one query
     * over the category table — no per-level queries.
     */
    private function descendantIds(Category $category): array
    {
        $byParent = Category::where('is_active', true)
            ->whereNotNull('parent_id')
            ->get(['id', 'parent_id'])
            ->groupBy('parent_id');

        $ids = [];
        $queue = [$category->id];
        while ($queue) {
            $parentId = array_shift($queue);
            foreach ($byParent->get($parentId, collect()) as $child) {
                $ids[] = $child->id;
                $queue[] = $child->id;
            }
        }

        return $ids;
    }
}
