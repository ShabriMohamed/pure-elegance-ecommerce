<?php

namespace App\Services;

use App\Mail\OrderPlacedCustomer;
use App\Mail\OrderPlacedVendor;
use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderMailService
{
    /**
     * Send the order-placed emails: a confirmation to the customer and a new-order
     * alert to the store. Runs AFTER the order is committed and is fully non-fatal —
     * a mail failure is logged, never surfaced to the customer or rolled back.
     */
    public function sendPlacedNotifications(Order $order): void
    {
        // The whole body is guarded: not just the sends, but the relation load and the
        // settings lookup too. A committed order must always reach its confirmation page,
        // even if a transient DB/cache error occurs while preparing the mail.
        try {
            $order->loadMissing('items.product.primaryImage');

            if (filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
                $this->safeSend($order->customer_email, new OrderPlacedCustomer($order), $order, 'customer');
            }

            $vendor = $this->vendorEmail();
            if ($vendor !== null) {
                $this->safeSend($vendor, new OrderPlacedVendor($order), $order, 'vendor');
            }
        } catch (\Throwable $e) {
            Log::error('Order placed-notification dispatch failed: ' . $e->getMessage(), ['order_id' => $order->id]);
        }
    }

    /**
     * Where store/vendor order alerts go. Tries each candidate in order — a dedicated
     * setting, then the public contact address, then the app's from-address — and
     * returns the first that is actually a valid email. Null if none is valid (so a
     * non-empty-but-invalid earlier candidate never masks a valid later one).
     */
    public function vendorEmail(): ?string
    {
        foreach ([site('order_notification_email'), site('contact_email'), config('mail.from.address')] as $candidate) {
            if (is_string($candidate) && filter_var(trim($candidate), FILTER_VALIDATE_EMAIL)) {
                return trim($candidate);
            }
        }

        return null;
    }

    private function safeSend(string $to, Mailable $mailable, Order $order, string $type): void
    {
        try {
            Mail::to($to)->send($mailable);
        } catch (\Throwable $e) {
            Log::error("Order {$type} email failed: " . $e->getMessage(), ['order_id' => $order->id]);
        }
    }
}
