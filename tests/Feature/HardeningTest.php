<?php

namespace Tests\Feature;

use App\Actions\ProcessCheckoutAction;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PageContent;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HardeningTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $u = User::factory()->create();
        $u->is_admin = true;
        $u->save();
        return $u;
    }

    // ── Newsletter ──────────────────────────────────────────────
    public function test_newsletter_stores_subscriber(): void
    {
        $this->post('/newsletter', ['email' => 'shopper@example.com'])->assertRedirect();
        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'shopper@example.com']);
    }

    public function test_newsletter_honeypot_silently_drops_bots(): void
    {
        $this->post('/newsletter', ['email' => 'bot@example.com', 'website' => 'spam'])->assertRedirect();
        $this->assertDatabaseMissing('newsletter_subscribers', ['email' => 'bot@example.com']);
    }

    // ── Content pages ───────────────────────────────────────────
    public function test_known_page_renders_and_unknown_404s(): void
    {
        PageContent::create(['slug' => 'about', 'title' => 'About Us', 'content' => '<p>Hi</p>', 'is_active' => true]);

        $this->get('/page/about')->assertOk()->assertSee('About Us');
        $this->get('/page/does-not-exist')->assertNotFound();
    }

    // ── Reviews ─────────────────────────────────────────────────
    public function test_customer_review_is_created_pending_and_guests_cannot_review(): void
    {
        $product = Product::factory()->create(['category_id' => Category::factory()]);

        $this->post("/product/{$product->id}/reviews", ['rating' => 5, 'comment' => 'Great'])
            ->assertRedirect(); // guest -> redirected to login
        $this->assertDatabaseCount('reviews', 0);

        $this->actingAs(User::factory()->create())
            ->post("/product/{$product->id}/reviews", ['rating' => 5, 'comment' => 'Great']);

        $this->assertDatabaseHas('reviews', ['product_id' => $product->id, 'rating' => 5, 'is_approved' => false]);
    }

    // ── Order status state machine ──────────────────────────────
    public function test_illegal_status_transition_is_rejected(): void
    {
        $order = Order::create([
            'order_number' => 'PE-SM000001', 'status' => 'delivered',
            'subtotal' => 100, 'discount_amount' => 0, 'delivery_fee' => 0, 'total' => 100,
            'customer_name' => 'A', 'customer_email' => 'a@b.com', 'customer_phone' => '077', 'delivery_address' => 'X',
        ]);

        // delivered -> pending is not allowed
        $this->actingAs($this->admin())
            ->patch("/admin/orders/{$order->id}/status", ['status' => 'pending'])
            ->assertSessionHas('error');

        $this->assertSame('delivered', $order->fresh()->status);
    }

    public function test_cancel_restores_variant_stock_not_product_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10, 'category_id' => Category::factory()]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'stock_quantity' => 3]);

        $order = Order::create([
            'order_number' => 'PE-SM000002', 'status' => 'confirmed',
            'subtotal' => 100, 'discount_amount' => 0, 'delivery_fee' => 0, 'total' => 100,
            'customer_name' => 'A', 'customer_email' => 'a@b.com', 'customer_phone' => '077', 'delivery_address' => 'X',
        ]);
        OrderItem::create([
            'order_id' => $order->id, 'product_id' => $product->id, 'variant_id' => $variant->id,
            'product_name' => $product->name, 'quantity' => 2, 'unit_price' => 50, 'total_price' => 100,
        ]);

        $this->actingAs($this->admin())
            ->patch("/admin/orders/{$order->id}/status", ['status' => 'cancelled'])
            ->assertRedirect();

        $this->assertSame(5, $variant->fresh()->stock_quantity);  // 3 + 2 restored to variant
        $this->assertSame(10, $product->fresh()->stock_quantity); // product untouched
    }

    // ── Variant integrity at checkout ───────────────────────────
    public function test_checkout_rejects_variant_from_a_different_product(): void
    {
        $productA = Product::factory()->create(['stock_quantity' => 10, 'price' => 1000, 'category_id' => Category::factory()]);
        $productB = Product::factory()->create(['stock_quantity' => 10, 'category_id' => Category::factory()]);
        $variantB = ProductVariant::factory()->create(['product_id' => $productB->id, 'stock_quantity' => 10]);

        // Craft a tampered cart: product A paired with product B's variant.
        $cart = Cart::create(['session_id' => 'x']);
        CartItem::create([
            'cart_id' => $cart->id, 'product_id' => $productA->id, 'variant_id' => $variantB->id,
            'quantity' => 1, 'price' => 1000,
        ]);

        $this->expectException(\Throwable::class);
        app(ProcessCheckoutAction::class)->execute(
            $cart->fresh(),
            ['name' => 'A', 'email' => 'a@b.com', 'phone' => '077', 'address' => 'X'],
            null
        );
    }

    // ── Delivery fee single source ──────────────────────────────
    public function test_delivery_fee_is_free_over_threshold(): void
    {
        $this->assertSame(0.0, \App\Support\DeliveryFee::for(999999));
        $this->assertGreaterThan(0, \App\Support\DeliveryFee::for(1));
    }
}
