<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles & Permissions if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // 2. Create Admin User.
        // Password comes from the ADMIN_PASSWORD env var (never hardcoded). If unset,
        // a strong random one is generated and printed once so it is not baked into code.
        $adminPassword = env('ADMIN_PASSWORD');
        if (empty($adminPassword)) {
            $adminPassword = Str::password(16);
            $this->command?->warn("ADMIN_PASSWORD not set — generated admin password: {$adminPassword}");
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@pureelegance.com'],
            [
                'name' => 'Store Admin',
                'first_name' => 'Store',
                'last_name' => 'Admin',
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
            ]
        );
        // Privilege flags are not mass-assignable — set explicitly.
        $admin->is_admin = true;
        $admin->is_active = true;
        $admin->save();
        $admin->assignRole('admin');

        // Create a test customer (non-production convenience account).
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Test Customer',
                'first_name' => 'Test',
                'last_name' => 'Customer',
                'password' => Hash::make(env('CUSTOMER_PASSWORD', 'password')),
                'email_verified_at' => now(),
            ]
        );
        $customer->is_active = true;
        $customer->save();
        $customer->assignRole('customer');

        // 3. Create Basic Categories if empty
        if (Category::count() === 0) {
            $men = Category::create([
                'name' => 'Men',
                'slug' => 'men',
                'gender' => 'men',
                'is_active' => true,
                'sort_order' => 1,
            ]);

            $women = Category::create([
                'name' => 'Women',
                'slug' => 'women',
                'gender' => 'women',
                'is_active' => true,
                'sort_order' => 2,
            ]);

            $sale = Category::create([
                'name' => 'Sale',
                'slug' => 'sale',
                'gender' => 'unisex',
                'is_active' => true,
                'sort_order' => 3,
                'description' => 'Up to 60% off on selected items',
            ]);

            // Subcategories
            Category::create(['name' => 'Clothing', 'slug' => 'men-clothing', 'parent_id' => $men->id, 'gender' => 'men']);
            Category::create(['name' => 'Footwear', 'slug' => 'men-footwear', 'parent_id' => $men->id, 'gender' => 'men']);
            Category::create(['name' => 'Clothing', 'slug' => 'women-clothing', 'parent_id' => $women->id, 'gender' => 'women']);
            Category::create(['name' => 'Bags', 'slug' => 'women-bags', 'parent_id' => $women->id, 'gender' => 'women']);
        }

        // 4. Create sample products if empty
        if (Product::count() === 0) {
            $menClothingId = Category::where('slug', 'men-clothing')->first()->id ?? 1;
            $womenClothingId = Category::where('slug', 'women-clothing')->first()->id ?? 2;

            Product::create([
                'name' => 'Premium Cotton Polo',
                'slug' => 'premium-cotton-polo',
                'sku' => 'PE-MEN-POLO-01',
                'description' => 'Classic fit premium cotton polo shirt perfect for everyday wear.',
                'short_description' => 'Classic fit premium cotton polo',
                'price' => 4990.00,
                'category_id' => $menClothingId,
                'gender' => 'men',
                'brand' => 'Pure Elegance',
                'stock_quantity' => 50,
                'is_active' => true,
                'is_new_arrival' => true,
            ]);

            Product::create([
                'name' => 'Silk Wrap Dress',
                'slug' => 'silk-wrap-dress',
                'sku' => 'PE-WMN-DRS-01',
                'description' => 'Elegant silk wrap dress suitable for formal occasions.',
                'short_description' => 'Elegant silk wrap dress',
                'price' => 8990.00,
                'category_id' => $womenClothingId,
                'gender' => 'women',
                'brand' => 'Pure Elegance',
                'stock_quantity' => 30,
                'is_active' => true,
                'is_featured' => true,
            ]);
        }

        // 5. Site settings, content pages, and demo reviews.
        $this->call([
            SiteSettingSeeder::class,
            PageContentSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
