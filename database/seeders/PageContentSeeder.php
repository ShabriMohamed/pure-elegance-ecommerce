<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about',
                'title' => 'About Pure Elegance',
                'content' => '<p>Pure Elegance is a premium fashion boutique bringing timeless, original branded pieces to discerning customers across Sri Lanka. Every item in our collection is handpicked for quality, authenticity, and enduring style.</p><p>From wardrobe essentials to statement pieces, we believe fashion should be effortless, elegant, and delivered right to your door.</p>',
            ],
            [
                'slug' => 'privacy',
                'title' => 'Privacy Policy',
                'content' => '<p>We respect your privacy. The personal information you provide — such as your name, contact details, and delivery address — is used solely to process and deliver your orders and to communicate with you about them.</p><p>We never sell your data. Order details may be shared over WhatsApp only for the purpose of confirming and fulfilling your purchase.</p>',
            ],
            [
                'slug' => 'terms',
                'title' => 'Terms & Conditions',
                'content' => '<p>By placing an order with Pure Elegance you agree to our terms of sale. Prices are in Sri Lankan Rupees (LKR) and include applicable taxes. Orders are confirmed once our team contacts you.</p><p>Please review our return policy: items may be returned within 7 days of delivery in their original condition.</p>',
            ],
        ];

        foreach ($pages as $page) {
            PageContent::firstOrCreate(
                ['slug' => $page['slug']],
                array_merge($page, ['is_active' => true]),
            );
        }
    }
}
