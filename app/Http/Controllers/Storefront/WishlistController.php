<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product.primaryImage')->get();
        return view('storefront.account.wishlist', compact('wishlistItems'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($exists) {
            $exists->delete();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'removed',
                    'message' => 'Item removed from wishlist.'
                ]);
            }
            return back()->with('success', 'Item removed from wishlist.');
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ]);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'added',
                    'message' => 'Item added to wishlist.'
                ]);
            }
            return back()->with('success', 'Item added to wishlist.');
        }
    }
}
