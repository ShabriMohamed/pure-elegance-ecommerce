<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with(['images', 'variants'])
            ->withRatings()
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // View counter — don't let it bump the "last updated" timestamp.
        $product->timestamps = false;
        $product->increment('view_count');
        $product->timestamps = true;

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->with('primaryImage')
            ->withRatings()
            ->latest()
            ->limit(4)
            ->get();

        // Approved reviews for display + summary.
        $reviews = $product->approvedReviews()->with('user')->latest()->paginate(5);
        $reviewCount = (int) ($product->reviews_count ?? $product->approvedReviews()->count());
        $averageRating = (float) ($product->reviews_avg ?? 0);

        // Has the current customer already reviewed this product?
        $userReview = auth()->check()
            ? $product->reviews()->where('user_id', auth()->id())->first()
            : null;

        return view('storefront.product', compact(
            'product', 'relatedProducts', 'reviews', 'reviewCount', 'averageRating', 'userReview'
        ));
    }
}
