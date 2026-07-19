<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Register every brand name already present on active products so the storefront
     * strip has content before any logo is uploaded. Idempotent: existing rows are
     * left untouched, so re-running never clobbers uploaded logos or sort order.
     */
    public function run(): void
    {
        $names = Product::where('is_active', true)
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        $existing = Brand::pluck('name')->map(fn ($n) => mb_strtolower($n))->all();
        $order = (int) Brand::max('sort_order');

        foreach ($names as $name) {
            if (in_array(mb_strtolower($name), $existing, true)) {
                continue;
            }

            Brand::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'sort_order' => ++$order,
                'is_active' => true,
            ]);
        }
    }
}
