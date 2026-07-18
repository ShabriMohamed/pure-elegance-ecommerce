<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewFormTest extends TestCase
{
    use RefreshDatabase;

    private function product(): Product
    {
        return Product::factory()->create([
            'is_active' => true,
            'stock_quantity' => 5,
            'price' => 4500,
            'category_id' => Category::factory(),
        ]);
    }

    public function test_guest_sees_a_visible_login_prompt_not_a_muted_sentence(): void
    {
        $page = $this->get(route('product.show', $this->product()->slug));

        $page->assertOk();
        // Guests previously got one easy-to-miss muted line; now a full prompt card.
        $page->assertSee('review-login-card', false);
        $page->assertSee('Share your thoughts');
        $page->assertSee('Log in to review');
        $page->assertDontSee('star-input', false); // the form itself stays gated
    }

    public function test_authenticated_user_gets_the_star_rating_form(): void
    {
        $page = $this->actingAs(User::factory()->create())
            ->get(route('product.show', $this->product()->slug));

        $page->assertOk();
        $page->assertSee('star-input', false);        // interactive stars, not a <select>
        $page->assertSee('name="rating"', false);
        $page->assertSee('Write a review');
        $page->assertDontSee('review-login-card', false);
    }

    public function test_rating_select_was_replaced_by_accessible_radio_stars(): void
    {
        $page = $this->actingAs(User::factory()->create())
            ->get(route('product.show', $this->product()->slug));

        // Radio group is labelled for screen readers and keyboard-operable.
        $page->assertSee('role="radiogroup"', false);
        $page->assertSee('type="radio"', false);
        $page->assertDontSee('<select name="rating"', false);
    }
}
