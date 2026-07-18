<?php

namespace Tests\Feature;

use App\Actions\ProcessCheckoutAction;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function cartFor(?User $user, Product $product, int $qty, float $price): Cart
    {
        $cart = Cart::create([
            'user_id' => $user?->id,
            'session_id' => 'test-session',
        ]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'variant_id' => null,
            'quantity' => $qty,
            'price' => $price,
        ]);

        return $cart;
    }

    public function test_checkout_creates_order_decrements_stock_and_clears_cart(): void
    {
        \App\Models\SiteSetting::setValue('whatsapp_number', '94771234567');

        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10, 'price' => 5000]);
        $this->cartFor($user, $product, 2, 5000);

        $response = $this->actingAs($user)->post('/checkout/process', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '0771234567',
            'address' => '123 Galle Road',
            'city' => 'Colombo',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);

        // Payment-gateway handoff pattern: land on the confirmation anchor first…
        $response->assertRedirect(route('checkout.confirmation', $order));

        // …which auto-opens the tracked WhatsApp handoff; hitting it marks the order and
        // returns the wa.me URL as JSON (the browser navigates via the anchor href).
        $handoff = $this->actingAs($user)->post(route("checkout.whatsapp", $order));
        $handoff->assertOk();
        $this->assertStringContainsString('wa.me', (string) $handoff->json('url'));

        $order->refresh();
        $this->assertSame($user->id, $order->user_id);
        $this->assertSame('whatsapp_sent', $order->status);
        $this->assertNotNull($order->whatsapp_sent_at);
        $this->assertEquals(10000, (float) $order->subtotal);

        $this->assertSame(2, $order->items->first()->quantity);
        $this->assertSame(8, $product->fresh()->stock_quantity);
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_checkout_without_whatsapp_configured_shows_confirmation_page(): void
    {
        // No whatsapp_number configured → must NOT redirect to a fallback number;
        // instead lands on the on-site confirmation page.
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 5, 'price' => 3000]);
        $this->cartFor($user, $product, 1, 3000);

        $response = $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0771234567',
            'address' => '5 Marine Drive',
            'city' => 'Colombo',
        ]);

        $order = Order::first();
        $response->assertRedirect(route('checkout.confirmation', $order));
        $this->assertStringNotContainsString('wa.me', (string) $response->headers->get('Location'));
    }

    public function test_checkout_rejects_invalid_phone(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 5, 'price' => 3000]);
        $this->cartFor($user, $product, 1, 3000);

        $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Jane', 'email' => 'jane@example.com',
            'phone' => 'not-a-phone', 'address' => 'X', 'city' => 'Colombo',
        ])->assertSessionHasErrors('phone');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_applies_promo_and_increments_usage_atomically(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5, 'price' => 2000]);
        $cart = $this->cartFor(null, $product, 2, 2000); // subtotal 4000
        $promo = Promotion::factory()->percentage(10)->create(['code' => 'SAVE10']);

        $order = app(ProcessCheckoutAction::class)->execute(
            $cart->fresh(),
            ['name' => 'Jane', 'email' => 'jane@example.com', 'phone' => '0770000000', 'address' => 'Kandy'],
            'SAVE10'
        );

        // subtotal 4000, discount 10% = 400, delivery 350 (< free threshold), total 3950
        $this->assertEquals(400, (float) $order->discount_amount);
        $this->assertEquals(3950, (float) $order->total);
        $this->assertSame(1, $promo->fresh()->used_count);
        $this->assertSame(3, $product->fresh()->stock_quantity);
    }

    public function test_checkout_rolls_back_when_stock_is_insufficient(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 1, 'price' => 1000]);
        $cart = $this->cartFor(null, $product, 5, 1000);

        try {
            app(ProcessCheckoutAction::class)->execute(
                $cart->fresh(),
                ['name' => 'X', 'email' => 'x@example.com', 'phone' => '077', 'address' => 'A'],
                null
            );
            $this->fail('Expected an exception for insufficient stock.');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Not enough stock', $e->getMessage());
        }

        // Transaction rolled back: no order, stock untouched, cart intact.
        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(1, $product->fresh()->stock_quantity);
        $this->assertDatabaseCount('cart_items', 1);
    }
}
