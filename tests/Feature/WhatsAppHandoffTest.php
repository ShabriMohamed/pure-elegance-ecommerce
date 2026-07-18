<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppHandoffTest extends TestCase
{
    use RefreshDatabase;

    private function placeOrder(User $user): Order
    {
        $product = Product::factory()->create([
            'stock_quantity' => 10,
            'price' => 5000,
            'category_id' => Category::factory(),
        ]);

        // Add through the real cart endpoint so it lands in the user's resolved cart
        // (works correctly even for a second order in the same session).
        $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 2]);

        $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Nimal Perera', 'email' => 'nimal@example.com',
            'phone' => '0771234567', 'address' => '12 Temple Rd', 'city' => 'Kandy',
        ]);

        return Order::where('user_id', $user->id)->latest('id')->firstOrFail();
    }

    public function test_confirmation_page_auto_opens_whatsapp_via_csp_safe_anchor(): void
    {
        SiteSetting::setValue('whatsapp_number', '94770000001');
        $user = User::factory()->create();
        $order = $this->placeOrder($user);

        $page = $this->actingAs($user)->get(route('checkout.confirmation', $order));

        $page->assertOk();
        $page->assertSee('SEND ORDER ON WHATSAPP');
        // Navigation is a plain anchor to wa.me (NOT a form) — this is what keeps it
        // clear of the CSP `form-action 'self'` directive.
        $page->assertSee('data-wa-auto', false);                          // timed auto-open
        $page->assertSee('href="https://wa.me/94770000001', false);       // anchor target
        $page->assertSee('data-wa-mark="' . route('checkout.whatsapp', $order, false) . '"', false); // same-origin fetch mark endpoint (relative)
        $page->assertDontSee('<form id="whatsapp-handoff-form"', false);  // no off-site form
    }

    public function test_confirmation_get_marks_handoff_server_side_and_stops_reopen_loop(): void
    {
        SiteSetting::setValue('whatsapp_number', '94770000001');
        $user = User::factory()->create();
        $order = $this->placeOrder($user);

        $this->assertNull($order->whatsapp_sent_at);

        // First view auto-opens AND records the handoff server-side, so a dropped client
        // keepalive POST (mobile webviews) can't leave the order un-marked.
        $first = $this->actingAs($user)->get(route('checkout.confirmation', $order));
        $first->assertOk();
        $first->assertSee('data-wa-auto', false);              // still auto-opens exactly once
        $this->assertNotNull($order->fresh()->whatsapp_sent_at);
        $this->assertSame('whatsapp_sent', $order->fresh()->status);

        // Second view (e.g. pressing Back) no longer auto-opens → no re-open loop.
        $second = $this->actingAs($user)->get(route('checkout.confirmation', $order));
        $second->assertOk();
        $second->assertDontSee('data-wa-auto', false);
    }

    public function test_handoff_marks_order_sent_and_returns_business_chat_url(): void
    {
        SiteSetting::setValue('whatsapp_number', '+94 77 000 0001'); // messy input on purpose
        $user = User::factory()->create();
        $order = $this->placeOrder($user);

        $this->assertSame('pending', $order->status); // not marked before follow-through

        $handoff = $this->actingAs($user)->post(route("checkout.whatsapp", $order));

        // The endpoint returns the wa.me URL as JSON; the browser navigates to it.
        $handoff->assertOk();
        $this->assertStringContainsString('wa.me/94770000001', (string) $handoff->json('url'));

        $order->refresh();
        $this->assertSame('whatsapp_sent', $order->status);
        $this->assertNotNull($order->whatsapp_sent_at);
    }

    public function test_handoff_returns_error_json_when_not_configured(): void
    {
        // No whatsapp_number configured.
        $user = User::factory()->create();
        $order = $this->placeOrder($user);

        $this->actingAs($user)
            ->post(route('checkout.whatsapp', $order))
            ->assertStatus(409)
            ->assertJsonStructure(['error']);

        $this->assertNull($order->fresh()->whatsapp_sent_at);
    }

    public function test_resend_never_downgrades_a_vendor_advanced_status(): void
    {
        SiteSetting::setValue('whatsapp_number', '94770000001');
        $user = User::factory()->create();
        $order = $this->placeOrder($user);

        // Vendor already confirmed the order before the customer re-sends.
        $order->update(['status' => Order::STATUS_CONFIRMED]);

        $this->actingAs($user)->post(route("checkout.whatsapp", $order))->assertOk();

        $this->assertSame(Order::STATUS_CONFIRMED, $order->fresh()->status);
    }

    public function test_handoff_is_session_guarded_against_other_users(): void
    {
        SiteSetting::setValue('whatsapp_number', '94770000001');
        $order = $this->placeOrder(User::factory()->create());

        // A different session (no placed_order_id) must not access the handoff.
        $this->flushSession();
        $this->actingAs(User::factory()->create())
            ->post(route("checkout.whatsapp", $order))
            ->assertForbidden();
    }

    public function test_local_format_number_is_normalised_to_international(): void
    {
        // Vendors commonly enter local format (leading 0) — wa.me needs country code.
        SiteSetting::setValue('whatsapp_number', '077 055 1190');

        $this->assertSame('94770551190', app(WhatsAppNotificationService::class)->number());
    }

    public function test_message_is_link_forward_with_order_link_and_no_admin_link(): void
    {
        SiteSetting::setValue('whatsapp_number', '94770000001');

        $order = Order::create([
            'order_number' => 'PE-WA000001', 'status' => 'pending',
            'subtotal' => 10000, 'discount_amount' => 1000, 'delivery_fee' => 0, 'total' => 9000,
            'customer_name' => 'Nimal Perera', 'customer_email' => 'nimal@example.com',
            'customer_phone' => '0771234567', 'delivery_address' => '12 Temple Rd',
            'city' => 'Kandy', 'postal_code' => '20000', 'promo_code' => 'SAVE10', 'notes' => 'Gift wrap please',
        ]);
        OrderItem::create([
            'order_id' => $order->id, 'product_name' => 'Silk Wrap Dress', 'variant_info' => 'M - Rose',
            'quantity' => 2, 'unit_price' => 5000, 'total_price' => 10000,
        ]);

        $message = app(WhatsAppNotificationService::class)->buildMessage($order);

        // Link-forward: a concise summary + the public order link (which renders the
        // rich preview card). The itemised detail lives on the linked page, not typed text.
        $this->assertStringContainsString('PE-WA000001', $message);      // order number
        $this->assertStringContainsString('2 items', $message);          // total item count
        $this->assertStringContainsString(money($order->total), $message); // formatted total
        $this->assertStringContainsString($order->track_url, $message);  // the rich card link

        // Security: never leak the admin panel URL into the customer-composed message.
        $this->assertStringNotContainsString('/admin/', $message);
    }

    public function test_public_order_track_page_renders_with_preview_card_tags(): void
    {
        $order = Order::create([
            'order_number' => 'PE-TRACK01', 'status' => 'whatsapp_sent',
            'subtotal' => 10000, 'discount_amount' => 0, 'delivery_fee' => 350, 'total' => 10350,
            'customer_name' => 'Nimal Perera', 'customer_email' => 'nimal@example.com',
            'customer_phone' => '0771234567', 'delivery_address' => '12 Temple Rd',
            'city' => 'Kandy', 'postal_code' => '20000',
        ]);
        OrderItem::create([
            'order_id' => $order->id, 'product_name' => 'Silk Wrap Dress', 'variant_info' => 'M - Rose',
            'quantity' => 2, 'unit_price' => 5000, 'total_price' => 10000,
        ]);

        $page = $this->get($order->track_url);

        $page->assertOk();
        $page->assertSee('PE-TRACK01');
        $page->assertSee('Silk Wrap Dress');
        $page->assertSee('og:image', false);              // preview card image tag present
        $page->assertSee($order->order_number, false);    // og:title carries the order number
        // The card image must be a RASTER — link-preview crawlers don't render SVG.
        $page->assertSee('hero-banner.jpg', false);       // raster fallback (item has no product image)
        $page->assertDontSee('placeholder.svg', false);   // never an SVG in og:image
    }

    public function test_order_track_page_404s_on_unknown_token(): void
    {
        $this->get(route('order.track', 'this-token-does-not-exist'))->assertNotFound();
    }

    public function test_second_order_in_same_session_does_not_revoke_first(): void
    {
        SiteSetting::setValue('whatsapp_number', '94770000001');
        $user = User::factory()->create();

        $orderA = $this->placeOrder($user);
        $orderB = $this->placeOrder($user); // second order, same session

        $this->assertNotSame($orderA->id, $orderB->id);

        // Both remain accessible (session tracks an array of placed ids).
        $this->actingAs($user)->get(route('checkout.confirmation', $orderA))->assertOk();
        $this->actingAs($user)->get(route('checkout.confirmation', $orderB))->assertOk();
    }

    public function test_owner_can_access_confirmation_after_session_lost(): void
    {
        SiteSetting::setValue('whatsapp_number', '94770000001');
        $user = User::factory()->create();
        $order = $this->placeOrder($user);

        // Simulate a lost session (expiry / new browser) — owner must still get in.
        $this->flushSession();

        $this->actingAs($user)->get(route('checkout.confirmation', $order))->assertOk();
    }
}
