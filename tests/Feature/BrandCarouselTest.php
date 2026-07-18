<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandCarouselTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_shop_by_brand_carousel_with_real_brands(): void
    {
        $cat = Category::factory()->create();
        Product::factory()->count(2)->create(['brand' => 'Maison Atelier', 'category_id' => $cat->id]);
        Product::factory()->create(['brand' => 'Oud Noir', 'category_id' => $cat->id]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Shop by Brand');
        $response->assertSee('Maison Atelier');
        $response->assertSee('Oud Noir');
    }

    public function test_carousel_is_absent_when_no_products_have_brands(): void
    {
        $cat = Category::factory()->create();
        Product::factory()->create(['brand' => null, 'category_id' => $cat->id]);

        $this->get('/')->assertOk()->assertDontSee('Shop by Brand');
    }

    public function test_brand_filter_shows_only_that_brands_products(): void
    {
        $cat = Category::factory()->create();
        Product::factory()->create(['brand' => 'Maison', 'category_id' => $cat->id, 'name' => 'Maison Trench Coat']);
        Product::factory()->create(['brand' => 'Rival Co', 'category_id' => $cat->id, 'name' => 'Rival Sneaker']);

        $response = $this->get('/categories?brand=Maison');

        $response->assertOk();
        $response->assertSee('Maison Trench Coat');
        $response->assertDontSee('Rival Sneaker');
        $response->assertSee('Maison'); // page title reflects the brand
    }

    public function test_brand_filter_pagination_preserves_the_brand(): void
    {
        $cat = Category::factory()->create();
        Product::factory()->count(15)->create(['brand' => 'BulkBrand', 'category_id' => $cat->id]);
        Product::factory()->count(3)->create(['brand' => 'OtherBrand', 'category_id' => $cat->id]);

        $response = $this->get('/categories?brand=BulkBrand');

        $response->assertOk();
        // 15 BulkBrand products paginate at 12/page → a page 2 link that keeps ?brand=
        $response->assertSee('brand=BulkBrand', false);
    }
}
