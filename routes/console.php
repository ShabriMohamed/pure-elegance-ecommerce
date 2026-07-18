<?php

use App\Models\Cart;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Prune abandoned guest carts (and their items via FK cascade) older than 7 days,
// so bot/crawler traffic can't grow the carts table unboundedly.
Schedule::call(function () {
    Cart::whereNull('user_id')
        ->where('updated_at', '<', now()->subDays(7))
        ->get()
        ->each->delete();
})->daily()->name('prune-guest-carts')->withoutOverlapping();
