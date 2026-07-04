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
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id
            ? ProductVariant::findOrFail($request->variant_id)
            : null;

        // Stock validation
        $availableStock = $variant
            ? $variant->stock_quantity
            : $product->stock_quantity;

        if ($request->quantity > $availableStock) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        $this->cartService->addItem($product, $variant, $request->quantity);

        return redirect()->back()->with('success', 'Product added to cart!');
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
