<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryBrowsingTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_parent_categories_with_subtree_counts(): void
    {
        // 3-level tree mirroring the real catalog: Men > Clothing > Shirts (product on leaf).
        $parent = Category::factory()->create(['name' => 'Men Section', 'sort_order' => 1]);
        $child = Category::factory()->create(['name' => 'Clothing Sub', 'parent_id' => $parent->id]);
        $leaf = Category::factory()->create(['name' => 'Shirts Leaf', 'parent_id' => $child->id]);
        Product::factory()->create(['category_id' => $leaf->id]);

        // A parent whose entire subtree is empty must not get a tile.
        Category::factory()->create(['name' => 'Empty Section', 'sort_order' => 2]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Men Section');          // parent tile shown
        $response->assertDontSee('Shirts Leaf');       // leaves are not tiles
        $response->assertDontSee('Empty Section');     // empty subtree hidden
    }

    public function test_sale_tile_uses_discounted_product_count_and_links_to_sale_page(): void
    {
        // The Sale category never has products assigned; its tile is price-driven.
        Category::factory()->create(['name' => 'Sale', 'slug' => 'sale', 'sort_order' => 3]);
        $leaf = Category::factory()->create(['name' => 'Some Leaf']);
        Product::factory()->onSale()->create(['category_id' => $leaf->id]);

        $response = $this->get('/');

        $response->assertOk();
        // The tile itself renders (nav links to /sale exist regardless, so assert on tile markup).
        $response->assertSee('<div class="shop-cat-name">Sale</div>', false);
        // And it must not link to the (empty) category page.
        $response->assertDontSee('/category/sale', false);
    }

    public function test_sale_tile_hidden_when_nothing_is_discounted(): void
    {
        Category::factory()->create(['name' => 'Sale', 'slug' => 'sale']);
        $leaf = Category::factory()->create(['name' => 'Some Leaf']);
        Product::factory()->create(['category_id' => $leaf->id]); // full price

        $this->get('/')->assertOk()
            ->assertDontSee('<div class="shop-cat-name">Sale</div>', false);
    }

    public function test_parent_category_page_aggregates_descendant_products(): void
    {
        $parent = Category::factory()->create(['slug' => 'men-section']);
        $child = Category::factory()->create(['parent_id' => $parent->id]);
        $leaf = Category::factory()->create(['parent_id' => $child->id]);
        $product = Product::factory()->create(['category_id' => $leaf->id, 'name' => 'Grandchild Polo']);

        $this->get('/category/men-section')
            ->assertOk()
            ->assertSee('Grandchild Polo'); // product 2 levels down is included

        // Leaf page still shows its own products.
        $this->get("/category/{$leaf->slug}")
            ->assertOk()
            ->assertSee('Grandchild Polo');
    }
}
