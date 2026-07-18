<?php

use App\Models\SiteSetting;

if (! function_exists('site')) {
    /**
     * Read an admin-editable site setting (cached), falling back to a default.
     * Single accessor for the storefront so no view hardcodes business copy/values.
     */
    function site(string $key, $default = null)
    {
        return SiteSetting::getValue($key, $default);
    }
}

if (! function_exists('site_bool')) {
    /**
     * Read a site setting as a boolean. Treats '1','true','on','yes' as true.
     */
    function site_bool(string $key, bool $default = false): bool
    {
        $value = SiteSetting::getValue($key, null);

        if ($value === null) {
            return $default;
        }

        return in_array(strtolower((string) $value), ['1', 'true', 'on', 'yes'], true);
    }
}

if (! function_exists('money')) {
    /**
     * Format a monetary amount using the shop's currency symbol + precision.
     * The single money formatter for the whole app (views, WhatsApp message, etc.).
     */
    function money($amount): string
    {
        $symbol = SiteSetting::getValue('currency_symbol', config('shop.currency_symbol', 'LKR'));
        $decimals = (int) config('shop.currency_decimals', 2);

        return $symbol . ' ' . number_format((float) $amount, $decimals);
    }
}
