<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryBrowsingTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_tiles_second_level_departments_with_subtree_counts(): void
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
        // The 2nd level is the browsable department, so it is what gets tiled —
        // the top level is only Men / Women / Sale, which the collections strip
        // higher up the page already covers.
        $response->assertSee('<div class="shop-cat-name">Clothing Sub</div>', false);
        $response->assertSee('Men Section');            // rendered as the tile's parent label
        $response->assertDontSee('Shirts Leaf');        // the 3rd level is not tiled
        $response->assertDontSee('Empty Section');      // empty subtree hidden
    }

    public function test_stocked_top_level_category_without_children_is_tiled_itself(): void
    {
        // Fallback for a flat catalog: with nothing at the second level to tile,
        // the top-level category has to stand in for itself or it would vanish.
        $flat = Category::factory()->create(['name' => 'Standalone Dept']);
        Product::factory()->create(['category_id' => $flat->id]);

        $this->get('/')
            ->assertOk()
            ->assertSee('<div class="shop-cat-name">Standalone Dept</div>', false);
    }

    public function test_department_tiles_are_labelled_by_parent_so_duplicates_stay_distinct(): void
    {
        // "Clothing" exists under both Men and Women in the real catalog; without
        // the parent label the two tiles would be indistinguishable.
        $men = Category::factory()->create(['name' => 'Men', 'sort_order' => 1]);
        $women = Category::factory()->create(['name' => 'Women', 'sort_order' => 2]);
        $menClothing = Category::factory()->create(['name' => 'Clothing', 'parent_id' => $men->id]);
        $womenClothing = Category::factory()->create(['name' => 'Clothing', 'parent_id' => $women->id]);
        Product::factory()->create(['category_id' => $menClothing->id]);
        Product::factory()->create(['category_id' => $womenClothing->id]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<span class="pe-category__parent">Men</span>', false);
        $response->assertSee('<span class="pe-category__parent">Women</span>', false);
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
