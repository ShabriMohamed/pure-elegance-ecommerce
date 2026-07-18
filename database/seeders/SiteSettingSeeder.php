<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

/**
 * Ensures every setting the storefront actually reads exists with a sensible default.
 * Idempotent (firstOrCreate). Also migrates legacy site_email/site_phone values to the
 * contact_email/contact_phone keys the admin settings screen and footer now use.
 */
class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Carry legacy keys over to the canonical ones if present.
        $legacyEmail = SiteSetting::where('key', 'site_email')->value('value');
        $legacyPhone = SiteSetting::where('key', 'site_phone')->value('value');

        $defaults = [
            'currency_symbol' => 'LKR',
            'delivery_fee' => (string) config('shop.delivery_fee'),
            'free_delivery_threshold' => (string) config('shop.free_delivery_threshold'),
            'whatsapp_enabled' => '1',
            'contact_email' => $legacyEmail ?: 'info@pureelegance.lk',
            'contact_phone' => $legacyPhone ?: '+94 77 123 4567',
            'announcement_bar_enabled' => '1',
            'announcement_bar_text' => 'FREE DELIVERY ON ORDERS OVER',
            'newsletter_title' => 'Get the Latest Drops Straight to Your Inbox',
            'newsletter_subtitle' => 'Be the first to know about new collections, exclusive deals, and style inspiration.',
            'feature_1_title' => 'CASH ON DELIVERY', 'feature_1_subtitle' => 'Islandwide Delivery',
            'feature_2_title' => '100% ORIGINAL', 'feature_2_subtitle' => 'Branded Products',
            'feature_3_title' => 'EASY RETURNS', 'feature_3_subtitle' => '7 Days Return Policy',
            'feature_4_title' => 'SECURE PAYMENT', 'feature_4_subtitle' => 'Safe & Secure Checkout',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::firstOrCreate(['key' => $key], ['value' => $value]);
            cache()->forget('site_setting_' . $key);
        }
    }
}
