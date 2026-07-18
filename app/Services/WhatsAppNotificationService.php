<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use RuntimeException;

class WhatsAppNotificationService
{
    /**
     * Whether WhatsApp checkout is enabled and correctly configured.
     */
    public function isConfigured(): bool
    {
        return site_bool('whatsapp_enabled', true) && $this->number() !== '';
    }

    /**
     * The store's WhatsApp number, normalised to the international digits-only
     * format wa.me requires.
     */
    public function number(): string
    {
        return $this->normalize(SiteSetting::getValue('whatsapp_number', ''));
    }

    /**
     * Normalise any phone number to the digits-only international format wa.me needs.
     * Local-format numbers (leading 0) are converted using the configurable country
     * calling code — e.g. 0770551190 -> 94770551190. Returns '' when unusable.
     */
    public function normalize(?string $raw): string
    {
        $digits = preg_replace('/\D+/', '', (string) $raw);

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            $digits = config('shop.phone_country_code') . ltrim($digits, '0');
        }

        return $digits;
    }

    /**
     * Build a wa.me deep link to an ARBITRARY number (e.g. the vendor messaging the
     * customer from the order email), with an optional prefilled message. Returns null
     * when the number can't be normalised.
     */
    public function chatLink(?string $phone, string $message = ''): ?string
    {
        $number = $this->normalize($phone);

        if ($number === '') {
            return null;
        }

        $url = "https://wa.me/{$number}";

        if ($message !== '') {
            $url .= '?text=' . urlencode($message);
        }

        return $url;
    }

    /**
     * Build the concise, LINK-FORWARD order message. Rather than dumping the whole
     * order as typed text, it carries a short summary + the public order link — which
     * WhatsApp renders as a rich preview card (product image, order number, total) via
     * the order page's Open Graph tags. The link opens the full itemised order.
     */
    public function buildMessage(Order $order): string
    {
        $order->loadMissing('items');

        $storeName = site('site_name', config('app.name'));
        $itemCount = (int) $order->items->sum('quantity');

        $lines = [];
        $lines[] = "Hi {$storeName}! I'd like to place this order:";
        $lines[] = '';
        $lines[] = "*{$order->order_number}* — {$itemCount} " . Str::plural('item', $itemCount);
        $lines[] = 'Total: ' . money($order->total);
        $lines[] = '';
        $lines[] = 'Full order details & items:';
        // First URL in the message → WhatsApp fetches its OG tags and shows the card.
        $lines[] = $order->track_url;

        return implode("\n", $lines);
    }

    /**
     * wa.me deep link that opens the business chat with the link-forward order
     * message prefilled.
     *
     * @throws RuntimeException if no WhatsApp number is configured (no fake fallback).
     */
    public function generateUrl(Order $order): string
    {
        $phoneNumber = $this->number();

        if ($phoneNumber === '') {
            throw new RuntimeException('WhatsApp number is not configured.');
        }

        return "https://wa.me/{$phoneNumber}?text=" . urlencode($this->buildMessage($order));
    }

    /**
     * A lightweight "ask about this order" wa.me link for the tracking page.
     * Returns null when WhatsApp isn't configured.
     */
    public function contactUrl(Order $order): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $message = "Hi! I have a question about my order {$order->order_number}: " . $order->track_url;

        return "https://wa.me/{$this->number()}?text=" . urlencode($message);
    }
}
