<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        return view('storefront.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $this->cartService->addItem(
            $request->product_id,
            $request->quantity,
            $request->size,
            $request->color
        );

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

        $this->cartService->updateItemQuantity($itemId, $request->quantity);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove($itemId)
    {
        $this->cartService->removeItem($itemId);
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
