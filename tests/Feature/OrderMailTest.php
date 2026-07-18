<?php

namespace Tests\Feature;

use App\Mail\OrderPlacedCustomer;
use App\Mail\OrderPlacedVendor;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_placing_an_order_sends_customer_and_vendor_emails(): void
    {
        Mail::fake();
        SiteSetting::setValue('contact_email', 'shop@pureelegance.lk');

        $product = Product::factory()->create([
            'stock_quantity' => 10, 'price' => 5000, 'category_id' => Category::factory(),
        ]);
        $user = User::factory()->create();

        $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 2]);
        $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Nimal Perera', 'email' => 'nimal@example.com',
            'phone' => '0771234567', 'address' => '12 Temple Rd', 'city' => 'Kandy',
        ])->assertRedirect();

        Mail::assertSent(OrderPlacedCustomer::class, fn ($m) => $m->hasTo('nimal@example.com'));
        Mail::assertSent(OrderPlacedVendor::class, fn ($m) => $m->hasTo('shop@pureelegance.lk'));
    }

    public function test_customer_email_renders_order_details_and_track_link(): void
    {
        $order = $this->makeOrder();

        $rendered = (new OrderPlacedCustomer($order))->render();

        $this->assertStringContainsString($order->order_number, $rendered);
        $this->assertStringContainsString('Silk Wrap Dress', $rendered);
        $this->assertStringContainsString('M - Rose', $rendered);
        $this->assertStringContainsString($order->track_url, $rendered);   // CTA to tracking page
        $this->assertStringNotContainsString('/admin/', $rendered);        // never leak admin URL to customers
    }

    public function test_vendor_email_includes_customer_contact_and_one_click_whatsapp_reply(): void
    {
        $order = $this->makeOrder();

        $rendered = (new OrderPlacedVendor($order))->render();

        $this->assertStringContainsString($order->order_number, $rendered);
        $this->assertStringContainsString('Nimal Perera', $rendered);
        $this->assertStringContainsString('nimal@example.com', $rendered);
        // One-click WhatsApp reply to the customer's normalised number (0771234567 -> 94771234567).
        $this->assertStringContainsString('wa.me/94771234567', $rendered);
        // Vendor email links to the admin order screen.
        $this->assertStringContainsString(route('admin.orders.show', $order), $rendered);
    }

    public function test_order_without_valid_customer_email_still_alerts_vendor_only(): void
    {
        Mail::fake();
        SiteSetting::setValue('contact_email', 'shop@pureelegance.lk');

        $order = $this->makeOrder(['customer_email' => 'not-an-email']);
        app(\App\Services\OrderMailService::class)->sendPlacedNotifications($order);

        Mail::assertNotSent(OrderPlacedCustomer::class);
        Mail::assertSent(OrderPlacedVendor::class);
    }

    public function test_vendor_email_falls_through_invalid_contact_to_valid_fallback(): void
    {
        // 'test@localhost' passes Laravel's email rule (so it can be saved) but fails
        // filter_var — the resolver must skip it, not silently drop all vendor alerts.
        SiteSetting::setValue('contact_email', 'test@localhost');

        $resolved = app(\App\Services\OrderMailService::class)->vendorEmail();

        $this->assertNotNull($resolved);
        $this->assertNotSame('test@localhost', $resolved);
        $this->assertNotFalse(filter_var($resolved, FILTER_VALIDATE_EMAIL));
    }

    private function makeOrder(array $overrides = []): Order
    {
        $order = Order::create(array_merge([
            'order_number' => 'PE-MAIL0001', 'status' => 'pending',
            'subtotal' => 10000, 'discount_amount' => 0, 'delivery_fee' => 350, 'total' => 10350,
            'customer_name' => 'Nimal Perera', 'customer_email' => 'nimal@example.com',
            'customer_phone' => '0771234567', 'delivery_address' => '12 Temple Rd',
            'city' => 'Kandy', 'postal_code' => '20000',
        ], $overrides));

        OrderItem::create([
            'order_id' => $order->id, 'product_name' => 'Silk Wrap Dress', 'variant_info' => 'M - Rose',
            'quantity' => 2, 'unit_price' => 5000, 'total_price' => 10000,
        ]);

        return $order->load('items');
    }
}
