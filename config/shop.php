<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shop defaults
    |--------------------------------------------------------------------------
    | Code-level defaults for commercial values. Admin-editable SiteSettings
    | (delivery_fee, free_delivery_threshold, currency_symbol, whatsapp_number)
    | override these at runtime; these are the single source of the fallback
    | values so no magic numbers live in controllers/actions/views.
    */

    'currency_symbol' => env('SHOP_CURRENCY', 'LKR'),
    'currency_decimals' => 2,

    'delivery_fee' => (float) env('SHOP_DELIVERY_FEE', 350),
    'free_delivery_threshold' => (float) env('SHOP_FREE_DELIVERY_THRESHOLD', 10000),

    // Maximum units of a single line a customer may add (enforced server-side).
    'max_qty_per_line' => (int) env('SHOP_MAX_QTY_PER_LINE', 10),

    // Default country calling code used to normalise local-format phone numbers
    // (e.g. 0770551190 -> 94770551190) for wa.me links, which require
    // international format. Sri Lanka by default; override via env.
    'phone_country_code' => env('SHOP_PHONE_COUNTRY_CODE', '94'),

];
