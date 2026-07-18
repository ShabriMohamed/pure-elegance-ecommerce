<?php

namespace App\Actions;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Exceptions\CheckoutException;
use App\Services\PromotionService;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;

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
            $cart->load(['items.product', 'items.variant']);

            if ($cart->items->isEmpty()) {
                throw new CheckoutException('Your cart is empty.');
            }

            // ── Pass 1: lock rows, validate integrity + stock, compute AUTHORITATIVE
            // prices from live data (never trust the cart's price snapshot) ──────────
            $lines = [];
            $subtotal = 0.0;

            foreach ($cart->items as $cartItem) {
                $product = Product::whereKey($cartItem->product_id)->lockForUpdate()->first();
                if (! $product) {
                    throw new CheckoutException('A product in your cart is no longer available.');
                }

                $variant = null;
                if ($cartItem->variant_id) {
                    $variant = ProductVariant::whereKey($cartItem->variant_id)->lockForUpdate()->first();
                    // Integrity: the variant must belong to this product.
                    if (! $variant || (int) $variant->product_id !== (int) $product->id) {
                        throw new CheckoutException("A selected option is no longer valid for {$product->name}.");
                    }
                }

                $available = (int) ($variant ? $variant->stock_quantity : $product->stock_quantity);
                if ($available < $cartItem->quantity) {
                    $label = $product->name . ($variant && $variant->size ? " ({$variant->size})" : '');
                    throw new CheckoutException("Not enough stock for: {$label}");
                }

                // Authoritative unit price recomputed from current product/variant data.
                $unitPrice = (float) $product->effective_price + (float) ($variant->price_adjustment ?? 0);
                $lineTotal = $unitPrice * $cartItem->quantity;
                $subtotal += $lineTotal;

                $variantInfo = null;
                if ($variant) {
                    $parts = array_filter([$variant->size, $variant->color]);
                    $variantInfo = $parts ? implode(' - ', $parts) : null;
                }

                $lines[] = [
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => (int) $cartItem->quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                    'product_name' => $product->name,
                    'variant_info' => $variantInfo,
                ];
            }

            // ── Promo: lock the row so the usage-limit check + used_count increment
            // are atomic under concurrency ──────────────────────────────────────────
            $discountAmount = 0;
            if ($promoCode) {
                $promotion = Promotion::where('code', strtoupper($promoCode))->lockForUpdate()->first();
                if ($promotion && $promotion->isValid($subtotal)) {
                    $discountAmount = $this->promotionService->calculateDiscount($promotion, $subtotal);
                    $promotion->increment('used_count');
                }
            }

            $deliveryFee = \App\Support\DeliveryFee::for((float) $subtotal);
            $total = ($subtotal - $discountAmount) + $deliveryFee;

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

            // ── Pass 2: decrement the already-locked rows + write order-item snapshots ─
            foreach ($lines as $line) {
                if ($line['variant']) {
                    $line['variant']->decrement('stock_quantity', $line['quantity']);
                } else {
                    $line['product']->decrement('stock_quantity', $line['quantity']);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $line['product']->id,
                    'variant_id' => $line['variant']?->id,
                    'product_name' => $line['product_name'],
                    'variant_info' => $line['variant_info'],
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'total_price' => $line['line_total'],
                ]);
            }

            $cart->items()->delete();

            return $order;
        });
    }
}
