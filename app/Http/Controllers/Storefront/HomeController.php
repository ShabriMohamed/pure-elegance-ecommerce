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

        // Shop by Category tiles. Products live on leaf categories, so each tile's
        // count is aggregated across its whole subtree, and anything whose subtree is
        // empty is hidden (a tile must never lead to an empty listing).
        // Two small queries + a PHP walk — no per-tile queries.
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

        /*
         * The catalogue's top level is only Men / Women / Sale — the same three the
         * collections strip higher up the page already shows. So the tiles are drawn
         * one level down (Clothing, Footwear, Watches, Accessories, Beauty …), which
         * is the layer customers actually browse by, and each tile carries its parent
         * as a label so the two "Clothing" departments stay distinguishable.
         *
         * A top-level category with no stocked children falls back to being its own
         * tile, so a flat or single-level catalogue still renders correctly.
         */
        $topCategories = collect();

        foreach ($allCategories->whereNull('parent_id')->sortBy('sort_order') as $root) {
            // Sale is price-driven and never has children or assigned products.
            if ($root->slug === 'sale') {
                $root->products_count = $onSaleCount;
                $root->parent_label = null;
                if ($onSaleCount > 0) {
                    $topCategories->push($root);
                }
                continue;
            }

            $stocked = $childrenByParent->get($root->id, collect())
                ->sortBy('sort_order')
                ->map(function ($child) use ($subtreeCount, $root) {
                    $child->products_count = $subtreeCount($child->id);
                    $child->parent_label = $root->name;
                    return $child;
                })
                ->filter(fn ($child) => $child->products_count > 0);

            if ($stocked->isNotEmpty()) {
                $topCategories = $topCategories->concat($stocked);
                continue;
            }

            $root->products_count = $subtreeCount($root->id);
            $root->parent_label = null;
            if ($root->products_count > 0) {
                $topCategories->push($root);
            }
        }

        $topCategories = $topCategories->values();

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
