<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Submit (or update) the current customer's review for a product.
     * One review per customer per product; held for admin moderation (is_approved=false).
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'is_approved' => false, // resets to pending on edit — re-moderated
            ]
        );

        return back()
            ->with('success', 'Thank you! Your review has been submitted and will appear once approved.')
            ->withFragment('reviews');
    }
}
