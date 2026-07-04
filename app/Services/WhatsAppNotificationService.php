<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;

class WhatsAppNotificationService
{
    /**
     * Generate a WhatsApp redirect URL containing formatted order details.
     */
    public function generateUrl(Order $order): string
    {
        $phoneNumber = SiteSetting::getValue('whatsapp_number', '94771234567');

        $order->load('items');

        $message = "--- New Order ---\n";
        $message .= "Order: {$order->order_number}\n";
        $message .= "Customer: {$order->customer_name}\n";
        $message .= "Phone: {$order->customer_phone}\n\n";

        $message .= "Items:\n";
        foreach ($order->items as $item) {
            $line = "- {$item->quantity}x {$item->product_name}";
            if ($item->variant_info) {
                $line .= " ({$item->variant_info})";
            }
            $line .= " - LKR " . number_format($item->total_price, 2);
            $message .= $line . "\n";
        }

        $message .= "\nSubtotal: LKR " . number_format($order->subtotal, 2);

        if ($order->discount_amount > 0) {
            $message .= "\nDiscount: -LKR " . number_format($order->discount_amount, 2);
        }

        $message .= "\nDelivery: LKR " . number_format($order->delivery_fee, 2);
        $message .= "\nTotal: LKR " . number_format($order->total, 2);

        $message .= "\n\nDelivery Address:\n{$order->delivery_address}";
        if ($order->city) {
            $message .= "\n{$order->city}";
        }

        if ($order->notes) {
            $message .= "\n\nNotes: {$order->notes}";
        }

        $message .= "\n\nPlease confirm this order.";

        return "https://wa.me/{$phoneNumber}?text=" . urlencode($message);
    }
}
