<?php

namespace App\Services;

use App\Models\Promotion;

class PromotionService
{
    /**
     * Validate and retrieve a promotion by code.
     */
    public function findByCode(string $code): ?Promotion
    {
        return Promotion::where('code', strtoupper($code))->first();
    }

    /**
     * Calculate discount amount for a given promotion and subtotal.
     */
    public function calculateDiscount(Promotion $promotion, float $subtotal): float
    {
        if (!$promotion->isValid($subtotal)) {
            return 0;
        }

        $discount = 0;

        if ($promotion->type === 'fixed') {
            $discount = (float) $promotion->value;
        } elseif ($promotion->type === 'percentage') {
            $discount = $subtotal * ((float) $promotion->value / 100);
        }

        // Cap at max_discount_amount if set
        if ($promotion->max_discount_amount && $discount > (float) $promotion->max_discount_amount) {
            $discount = (float) $promotion->max_discount_amount;
        }

        // Discount cannot exceed subtotal
        return min($discount, $subtotal);
    }
}
