<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
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
            ->withRatings()
            ->take(8)
            ->get();

        // New arrivals (is_new_arrival = true, fallback to latest)
        $newArrivals = Product::where('is_active', true)
            ->where('is_new_arrival', true)
            ->with('primaryImage')
            ->withRatings()
            ->latest()
            ->take(8)
            ->get();

        // If no new arrivals flagged, show latest products
        if ($newArrivals->isEmpty()) {
            $newArrivals = Product::where('is_active', true)
                ->with('primaryImage')
                ->withRatings()
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

        // Shop by Category: top-level (parent) categories only. Products live on leaf
        // categories, so each tile's count is aggregated across the whole subtree.
        // Parents whose entire subtree is empty are hidden (a tile must never lead to
        // an empty listing). Two small queries + PHP walk — no per-tile queries.
        $allCategories = Category::where('is_active', true)
            ->get(['id', 'parent_id', 'name', 'slug', 'gender', 'icon', 'image', 'sort_order']);

        $directCounts = Product::where('is_active', true)
            ->whereIn('category_id', $allCategories->pluck('id'))
            ->selectRaw('category_id, COUNT(*) as cnt')
            ->groupBy('category_id')
            ->pluck('cnt', 'category_id');

        $childrenByParent = $allCategories->groupBy('parent_id');
        $subtreeCount = function ($categoryId) use (&$subtreeCount, $childrenByParent, $directCounts) {
            $total = (int) ($directCounts[$categoryId] ?? 0);
            foreach ($childrenByParent->get($categoryId, collect()) as $child) {
                $total += $subtreeCount($child->id);
            }
            return $total;
        };

        // "Sale" is price-driven, not membership-driven: the /sale page lists products
        // where sale_price < price, and no product is ever assigned to the Sale
        // category itself. Its tile therefore gets the discounted-product count and
        // (in the view) links to route('sale') instead of a category page.
        $onSaleCount = Product::where('is_active', true)
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->count();

        $topCategories = $allCategories
            ->whereNull('parent_id')
            ->sortBy('sort_order')
            ->map(function ($category) use ($subtreeCount, $onSaleCount) {
                $category->products_count = $category->slug === 'sale'
                    ? $onSaleCount
                    : $subtreeCount($category->id);
                return $category;
            })
            ->filter(fn ($category) => $category->products_count > 0)
            ->values();

        // Shop by Brand: distinct brands across active products, most-stocked first.
        // Single grouped query — the carousel links each brand to its filtered listing.
        $brands = Product::where('is_active', true)
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->selectRaw('brand, COUNT(*) as products_count')
            ->groupBy('brand')
            ->orderByDesc('products_count')
            ->orderBy('brand')
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
            'topCategories',
            'brands',
            'wishlistIds'
        ));
    }
}
