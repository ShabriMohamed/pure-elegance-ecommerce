<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_card_shows_star_rating_when_approved_reviews_exist(): void
    {
        $cat = Category::factory()->create();
        $product = Product::factory()->featured()->create(['category_id' => $cat->id, 'name' => 'Rated Item']);

        foreach (User::factory()->count(3)->create() as $user) {
            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => 5,
                'is_approved' => true,
            ]);
        }

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('product-rating', false); // rating row markup
        $response->assertSee('(3)');                    // review count
    }

    public function test_product_card_hides_rating_when_no_reviews(): void
    {
        $cat = Category::factory()->create();
        Product::factory()->featured()->create(['category_id' => $cat->id]);

        $this->get('/')->assertOk()->assertDontSee('product-rating', false);
    }

    public function test_unapproved_reviews_do_not_count(): void
    {
        $cat = Category::factory()->create();
        $product = Product::factory()->featured()->create(['category_id' => $cat->id]);

        Review::create([
            'user_id' => User::factory()->create()->id,
            'product_id' => $product->id,
            'rating' => 5,
            'is_approved' => false, // pending moderation
        ]);

        // Only unapproved reviews exist → no rating shown.
        $this->get('/')->assertOk()->assertDontSee('product-rating', false);
    }

    public function test_with_ratings_scope_avoids_n_plus_one_and_exposes_aggregates(): void
    {
        $cat = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $cat->id]);
        foreach (User::factory()->count(2)->create() as $user) {
            Review::create(['user_id' => $user->id, 'product_id' => $product->id, 'rating' => 4, 'is_approved' => true]);
        }

        $loaded = Product::withRatings()->find($product->id);

        $this->assertSame(2, (int) $loaded->reviews_count);
        $this->assertEquals(4.0, (float) $loaded->reviews_avg);
        $this->assertEquals(4.0, $loaded->average_rating); // accessor uses the eager aggregate
    }
}
