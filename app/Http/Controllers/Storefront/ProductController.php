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
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $product->increment('view_count');

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->with(['primaryImage', 'variants'])
            ->limit(4)
            ->get();

        return view('storefront.product', compact('product', 'relatedProducts'));
    }
}
