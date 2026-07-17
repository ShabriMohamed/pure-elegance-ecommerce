<?php

namespace App\Actions;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Services\PromotionService;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Exception;

class ProcessCheckoutAction
{
    protected PromotionService $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    /**
     * Execute the checkout process within a database transaction.
     * Validates stock, calculates totals, creates order, decrements stock, clears cart.
     */
    public function execute(Cart $cart, array $customerData, ?string $promoCode = null): Order
    {
        return DB::transaction(function () use ($cart, $customerData, $promoCode) {
            // Reload items with relationships to get fresh data
            $cart->load(['items.product', 'items.variant']);

            $subtotal = $cart->subtotal;
            $discountAmount = 0;

            // Handle Promo Code. Lock the promotion row for the transaction so the
            // usage-limit check and the used_count increment are atomic — two concurrent
            // checkouts cannot both slip past a usage_limit boundary.
            if ($promoCode) {
                $promotion = Promotion::where('code', strtoupper($promoCode))->lockForUpdate()->first();
                if ($promotion && $promotion->isValid($subtotal)) {
                    $discountAmount = $this->promotionService->calculateDiscount($promotion, $subtotal);
                    $promotion->increment('used_count');
                }
            }

            // Calculate delivery fee from settings
            $deliveryFee = (float) SiteSetting::getValue('delivery_fee', 350);
            $freeThreshold = (float) SiteSetting::getValue('free_delivery_threshold', 10000);

            if ($subtotal >= $freeThreshold) {
                $deliveryFee = 0;
            }

            $total = ($subtotal - $discountAmount) + $deliveryFee;

            // Create the order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'status' => Order::STATUS_PENDING,
                'customer_name' => $customerData['name'],
                'customer_email' => $customerData['email'],
                'customer_phone' => $customerData['phone'],
                'delivery_address' => $customerData['address'],
                'city' => $customerData['city'] ?? null,
                'postal_code' => $customerData['postal_code'] ?? null,
                'notes' => $customerData['notes'] ?? null,
                'promo_code' => $promoCode,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
            ]);

            // Create order items and decrement stock
            foreach ($cart->items as $cartItem) {
                // Lock the stock row for the duration of the transaction and re-check
                // stock under the lock, so concurrent checkouts cannot oversell.
                if ($cartItem->variant_id) {
                    $variant = ProductVariant::whereKey($cartItem->variant_id)->lockForUpdate()->first();
                    if (!$variant || $variant->stock_quantity < $cartItem->quantity) {
                        $size = $cartItem->variant->size ?? null;
                        throw new Exception("Not enough stock for: {$cartItem->product->name}" . ($size ? " ({$size})" : ''));
                    }
                    $variant->decrement('stock_quantity', $cartItem->quantity);
                } else {
                    $product = Product::whereKey($cartItem->product_id)->lockForUpdate()->first();
                    if (!$product || $product->stock_quantity < $cartItem->quantity) {
                        throw new Exception("Not enough stock for: {$cartItem->product->name}");
                    }
                    $product->decrement('stock_quantity', $cartItem->quantity);
                }

                $variantInfo = null;
                if ($cartItem->variant) {
                    $parts = array_filter([$cartItem->variant->size, $cartItem->variant->color]);
                    $variantInfo = implode(' - ', $parts);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'variant_id' => $cartItem->variant_id,
                    'product_name' => $cartItem->product->name,
                    'variant_info' => $variantInfo,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->price,
                    'total_price' => $cartItem->price * $cartItem->quantity,
                ]);
            }

            // Clear Cart
            $cart->items()->delete();

            return $order;
        });
    }
}
