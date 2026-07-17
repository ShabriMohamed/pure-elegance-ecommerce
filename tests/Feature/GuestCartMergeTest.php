<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestCartMergeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cart_is_merged_into_user_cart_on_login(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 1000, 'stock_quantity' => 10]);

        // Guest cart keyed by a session id.
        $guestCart = Cart::create(['session_id' => 'guest-sess', 'user_id' => null]);
        CartItem::create([
            'cart_id' => $guestCart->id,
            'product_id' => $product->id,
            'variant_id' => null,
            'quantity' => 2,
            'price' => 1000,
        ]);

        $this->actingAs($user);
        app(CartService::class)->mergeGuestCart('guest-sess');

        $userCart = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($userCart);
        $this->assertSame(1, $userCart->items()->count());
        $this->assertSame(2, $userCart->items()->first()->quantity);

        // Guest cart is gone.
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);
    }

    public function test_quantities_combine_when_user_already_has_the_same_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 1000, 'stock_quantity' => 20]);

        $userCart = Cart::create(['user_id' => $user->id, 'session_id' => 'new-sess']);
        CartItem::create([
            'cart_id' => $userCart->id, 'product_id' => $product->id,
            'variant_id' => null, 'quantity' => 1, 'price' => 1000,
        ]);

        $guestCart = Cart::create(['session_id' => 'guest-sess', 'user_id' => null]);
        CartItem::create([
            'cart_id' => $guestCart->id, 'product_id' => $product->id,
            'variant_id' => null, 'quantity' => 3, 'price' => 1000,
        ]);

        $this->actingAs($user);
        app(CartService::class)->mergeGuestCart('guest-sess');

        $this->assertSame(1, $userCart->items()->count());
        $this->assertSame(4, $userCart->items()->first()->quantity); // 1 + 3
    }
}
