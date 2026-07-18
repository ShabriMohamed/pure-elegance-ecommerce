<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Seeds realistic, approved demo reviews so the storefront's star ratings render.
 * Idempotent: re-running will not duplicate (unique user_id + product_id).
 * Reviewer accounts use @demo.pureelegance.lk emails so they are easy to identify
 * and remove:  User::where('email','like','%@demo.pureelegance.lk')->delete();
 */
class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        if ($products->isEmpty()) {
            $this->command?->warn('ReviewSeeder: no products found — nothing to review.');
            return;
        }

        $reviewerNames = [
            'Amara Fernando', 'Dinesh Perera', 'Nethmi Jayawardena', 'Kasun Silva',
            'Tharushi Bandara', 'Ruwan Wickramasinghe', 'Ishara Gunawardena',
            'Sanduni Rajapaksa', 'Lahiru Weerasinghe', 'Menaka de Silva',
        ];

        $reviewers = collect($reviewerNames)->map(function ($name, $i) {
            [$first, $last] = array_pad(explode(' ', $name, 2), 2, '');

            return User::firstOrCreate(
                ['email' => 'reviewer' . ($i + 1) . '@demo.pureelegance.lk'],
                [
                    'name' => $name,
                    'first_name' => $first,
                    'last_name' => $last,
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                ]
            );
        });

        $comments = [
            5 => [
                'Absolutely love it — exceeded my expectations.',
                'Premium quality, worth every rupee.',
                'Perfect fit and a beautiful finish.',
                'My new favourite. Fast delivery too.',
                'Stunning piece, highly recommend.',
            ],
            4 => [
                'Great quality, very happy with it.',
                'Looks lovely; sizing runs slightly large.',
                'Good value — would buy again.',
                'Nice material and comfortable to wear.',
            ],
            3 => [
                'Decent, but not quite what I expected.',
                'It is okay for the price.',
                'Average — colour differs a little from the photos.',
            ],
        ];

        $created = 0;

        foreach ($products as $product) {
            $sample = $reviewers->shuffle()->take(random_int(4, 9));

            foreach ($sample as $reviewer) {
                // Weight toward positive ratings (realistic for a curated store).
                $roll = random_int(1, 10);
                $rating = $roll <= 6 ? 5 : ($roll <= 9 ? 4 : 3);
                $pool = $comments[$rating];

                $review = Review::firstOrCreate(
                    ['user_id' => $reviewer->id, 'product_id' => $product->id],
                    [
                        'rating' => $rating,
                        'comment' => $pool[array_rand($pool)],
                        'is_approved' => true,
                    ]
                );

                if ($review->wasRecentlyCreated) {
                    $created++;
                }
            }
        }

        $this->command?->info("ReviewSeeder: {$created} demo reviews created across {$products->count()} products.");
    }
}
