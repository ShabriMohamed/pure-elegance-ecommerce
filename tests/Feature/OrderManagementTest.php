<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        return $user;
    }

    private function makeOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'order_number' => 'PE-TEST0001',
            'status' => 'whatsapp_sent',
            'subtotal' => 1000,
            'discount_amount' => 0,
            'delivery_fee' => 0,
            'total' => 1000,
            'customer_name' => 'Test Buyer',
            'customer_email' => 'buyer@example.com',
            'customer_phone' => '0771234567',
            'delivery_address' => '1 Main St',
        ], $overrides));
    }

    public function test_admin_can_update_order_status(): void
    {
        // Regression: updating status used to SQL-error by writing non-existent
        // payment_status / tracking_number columns.
        $order = $this->makeOrder();

        $this->actingAs($this->admin())
            ->patch("/admin/orders/{$order->id}/status", ['status' => 'confirmed'])
            ->assertRedirect();

        $this->assertSame('confirmed', $order->fresh()->status);
    }

    public function test_status_update_rejects_invalid_status(): void
    {
        $order = $this->makeOrder();

        $this->actingAs($this->admin())
            ->patch("/admin/orders/{$order->id}/status", ['status' => 'not-a-real-status'])
            ->assertSessionHasErrors('status');

        $this->assertSame('whatsapp_sent', $order->fresh()->status);
    }
}
