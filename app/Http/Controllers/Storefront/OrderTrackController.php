<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\WhatsAppNotificationService;

class OrderTrackController extends Controller
{
    /**
     * Public order summary / tracking page, authenticated by the order's unguessable
     * token. Server-rendered with Open Graph tags so WhatsApp (and other chat apps)
     * render a rich preview card — product image, order number, and total.
     */
    public function show(string $token)
    {
        $order = Order::with(['items.product.primaryImage'])
            ->where('public_token', $token)
            ->firstOrFail();

        // Preview image for the link card: the first line item's actual product photo,
        // resolved from the stored path so we serve a RASTER (JPG/PNG/WebP) — link-preview
        // crawlers (WhatsApp/Facebook/X) don't render SVG, and primary_image_url returns
        // the SVG placeholder as a non-null string, so we bypass it here. Falls back to the
        // site's raster hero image. Absolute URL is required by scrapers.
        $firstImagePath = $order->items->first()?->product?->primaryImage?->image_path;
        $ogImage = $firstImagePath
            ? asset('storage/' . $firstImagePath)
            : asset('images/hero-banner.jpg');

        $contactUrl = app(WhatsAppNotificationService::class)->contactUrl($order);

        return view('storefront.order-track', compact('order', 'ogImage', 'contactUrl'));
    }
}
