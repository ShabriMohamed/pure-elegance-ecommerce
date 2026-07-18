<?php

namespace App\Support;

use App\Models\SiteSetting;

/**
 * Single source of truth for the delivery-fee rule. Used by both the checkout
 * quote (CheckoutController) and the charge (ProcessCheckoutAction) so the amount
 * displayed always equals the amount stored on the order.
 */
class DeliveryFee
{
    /**
     * Delivery fee for a given subtotal (0 once the free-delivery threshold is met).
     */
    public static function for(float $subtotal): float
    {
        return $subtotal >= self::threshold() ? 0.0 : self::baseFee();
    }

    public static function baseFee(): float
    {
        return (float) SiteSetting::getValue('delivery_fee', config('shop.delivery_fee'));
    }

    public static function threshold(): float
    {
        return (float) SiteSetting::getValue('free_delivery_threshold', config('shop.free_delivery_threshold'));
    }
}
