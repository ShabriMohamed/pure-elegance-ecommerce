<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get or create the current cart based on session or authenticated user.
     */
    public function getCart(): Cart
    {
        $sessionId = Session::getId();

        if (Auth::check()) {
            // Try to find cart by user_id first
            $cart = Cart::where('user_id', Auth::id())->first();

            if (!$cart) {
                // Check if there's a guest cart for this session and claim it
                $cart = Cart::whereNull('user_id')
                    ->where('session_id', $sessionId)
                    ->first();

                if ($cart) {
                    $cart->update(['user_id' => Auth::id()]);
                } else {
                    $cart = Cart::create([
                        'session_id' => $sessionId,
                        'user_id' => Auth::id(),
                    ]);
                }
            }
        } else {
            $cart = Cart::where('session_id', $sessionId)->first();

            if (!$cart) {
                $cart = Cart::create([
                    'session_id' => $sessionId,
                    'user_id' => null,
                ]);
            }
        }

        return $cart;
    }

    /**
     * Add an item to the cart. If the same product+variant exists, increment quantity.
     */
    public function addItem(Product $product, ?ProductVariant $variant, int $quantity = 1): CartItem
    {
        $cart = $this->getCart();

        $price = $product->effective_price;
        if ($variant && $variant->price_adjustment) {
            $price += $variant->price_adjustment;
        }

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('variant_id', $variant?->id)
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
            $existingItem->update(['price' => $price]);
            return $existingItem;
        }

        return CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'quantity' => $quantity,
            'price' => $price,
        ]);
    }

    /**
     * Update quantity for a specific cart item. Validates ownership.
     */
    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        if ($cartItem->cart_id !== $this->getCart()->id) {
            abort(403, 'Unauthorized access to cart item.');
        }

        if ($quantity <= 0) {
            $cartItem->delete();
            return;
        }

        $cartItem->update(['quantity' => $quantity]);
    }

    /**
     * Remove an item from the cart. Validates ownership.
     */
    public function removeItem(int $cartItemId): void
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        if ($cartItem->cart_id !== $this->getCart()->id) {
            abort(403, 'Unauthorized access to cart item.');
        }

        $cartItem->delete();
    }

    /**
     * Clear all items from the current cart.
     */
    public function clearCart(): void
    {
        $this->getCart()->items()->delete();
    }
}
