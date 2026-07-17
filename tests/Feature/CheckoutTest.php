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

        $response->assertRedirect();
        $this->assertStringContainsString('wa.me', $response->headers->get('Location'));

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertSame($user->id, $order->user_id);
        $this->assertSame('whatsapp_sent', $order->status);
        $this->assertEquals(10000, (float) $order->subtotal);

        $this->assertSame(2, $order->items->first()->quantity);
        $this->assertSame(8, $product->fresh()->stock_quantity);
        $this->assertDatabaseCount('cart_items', 0);
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
