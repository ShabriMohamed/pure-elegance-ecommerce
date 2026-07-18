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
     * Find the current cart WITHOUT creating one (read-only paths like the header badge).
     */
    public function findCart(): ?Cart
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->first();
        }

        return Cart::whereNull('user_id')
            ->where('session_id', Session::getId())
            ->first();
    }

    /**
     * Total item count for the current cart, without ever creating a cart row.
     * Used by the header badge so merely rendering a page never writes to the DB.
     */
    public function getCartCount(): int
    {
        $cart = $this->findCart();

        return $cart ? (int) $cart->items()->sum('quantity') : 0;
    }

    /**
     * Quantity of a given product+variant already in the current cart (0 if none).
     */
    public function currentQuantity(Product $product, ?ProductVariant $variant): int
    {
        $cart = $this->findCart();

        if (! $cart) {
            return 0;
        }

        return (int) CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('variant_id', $variant?->id)
            ->sum('quantity');
    }

    /**
     * Update quantity for a specific cart item. Validates ownership and clamps to
     * available stock and the per-line cap so an impossible cart can't reach checkout.
     */
    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        $cartItem = CartItem::with(['product', 'variant'])->findOrFail($cartItemId);

        if ($cartItem->cart_id !== $this->getCart()->id) {
            abort(403, 'Unauthorized access to cart item.');
        }

        if ($quantity <= 0) {
            $cartItem->delete();
            return;
        }

        $available = $cartItem->variant
            ? (int) $cartItem->variant->stock_quantity
            : (int) ($cartItem->product->stock_quantity ?? 0);

        $cap = (int) config('shop.max_qty_per_line');
        $quantity = min($quantity, $available, $cap);

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

    /**
     * Merge a guest cart (identified by the pre-login session id) into the
     * authenticated user's cart. Call this right after login — the guest cart is
     * keyed by the session id that existed BEFORE the session was regenerated, so
     * that id must be captured and passed in by the caller.
     */
    public function mergeGuestCart(string $guestSessionId): void
    {
        if (!Auth::check()) {
            return;
        }

        $guestCart = Cart::whereNull('user_id')
            ->where('session_id', $guestSessionId)
            ->with('items')
            ->first();

        if (!$guestCart) {
            return;
        }

        if ($guestCart->items->isEmpty()) {
            $guestCart->delete();
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['session_id' => Session::getId()]
        );

        foreach ($guestCart->items as $item) {
            $existing = CartItem::where('cart_id', $userCart->id)
                ->where('product_id', $item->product_id)
                ->where('variant_id', $item->variant_id)
                ->first();

            if ($existing) {
                // Same product+variant already in the user cart: combine quantities
                // and keep the latest price snapshot.
                $existing->increment('quantity', $item->quantity);
                $existing->update(['price' => $item->price]);
            } else {
                // Move the guest line into the user cart.
                $item->update(['cart_id' => $userCart->id]);
            }
        }

        // Remove the (now-empty or fully-merged) guest cart and any leftover items.
        $guestCart->items()->delete();
        $guestCart->delete();
    }
}
