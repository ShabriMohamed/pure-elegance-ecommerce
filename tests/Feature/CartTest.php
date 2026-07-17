<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_add_update_and_remove_cart_items(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10, 'price' => 1500]);

        // Add
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertRedirect();

        $item = CartItem::first();
        $this->assertNotNull($item);
        $this->assertSame(2, $item->quantity);
        $this->assertEquals(1500, (float) $item->price); // server-set price snapshot

        // Update quantity
        $this->actingAs($user)->patch("/cart/update/{$item->id}", ['quantity' => 3])
            ->assertRedirect();
        $this->assertSame(3, $item->fresh()->quantity);

        // Remove
        $this->actingAs($user)->delete("/cart/remove/{$item->id}")->assertRedirect();
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_add_to_cart_rejects_quantity_above_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 2]);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 5,
        ])->assertSessionHas('error');

        $this->assertDatabaseCount('cart_items', 0);
    }
}
