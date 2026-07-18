<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $cart->load(['items.product.primaryImage', 'items.variant']);

        return view('storefront.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $cap = (int) config('shop.max_qty_per_line');

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:' . $cap,
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $product = Product::where('is_active', true)->findOrFail($validated['product_id']);

        $variant = null;
        if (! empty($validated['variant_id'])) {
            // The variant MUST belong to this product — prevents pairing product A
            // with product B's variant (price/stock tampering).
            $variant = $product->variants()->where('is_active', true)->find($validated['variant_id']);

            if (! $variant) {
                return back()->with('error', 'Please choose a valid option for this product.');
            }
        }

        $available = (int) ($variant ? $variant->stock_quantity : $product->stock_quantity);
        $alreadyInCart = $this->cartService->currentQuantity($product, $variant);
        $requestedTotal = $alreadyInCart + (int) $validated['quantity'];

        if ($requestedTotal > $available) {
            return back()->with('error', 'Not enough stock available for the requested quantity.');
        }

        if ($requestedTotal > $cap) {
            return back()->with('error', "You can add at most {$cap} of this item per order.");
        }

        $this->cartService->addItem($product, $variant, (int) $validated['quantity']);

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        if ($request->quantity == 0) {
            $this->cartService->removeItem($itemId);
            return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
        }

        $this->cartService->updateQuantity($itemId, $request->quantity);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove($itemId)
    {
        $this->cartService->removeItem($itemId);
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
