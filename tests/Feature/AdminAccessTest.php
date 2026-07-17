<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->is_admin = true;   // not mass-assignable — set explicitly
        $user->save();

        return $user;
    }

    public function test_guest_is_redirected_to_login_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_non_admin_user_is_forbidden_from_admin(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_user_can_reach_the_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin')
            ->assertOk();
    }

    public function test_admin_global_search_returns_results_without_error(): void
    {
        // Regression: this endpoint used to 500 by querying a non-existent `role` column.
        $customer = User::factory()->create(['name' => 'Searchable Shopper']);

        $response = $this->actingAs($this->admin())
            ->getJson('/admin/search?q=Searchable');

        $response->assertOk();
        $response->assertJsonFragment(['title' => 'Searchable Shopper']);
    }
}
