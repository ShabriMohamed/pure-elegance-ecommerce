<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
            // Honeypot: real users never fill this hidden field.
            'website' => ['nullable', 'size:0'],
        ]);

        // Silently accept bots (that filled the honeypot) without storing.
        if ($request->filled('website')) {
            return back()->with('success', 'Thank you for subscribing!');
        }

        NewsletterSubscriber::firstOrCreate(
            ['email' => strtolower($validated['email'])],
            ['subscribed_at' => now(), 'ip_address' => $request->ip()]
        );

        return back()->with('success', 'Thank you for subscribing to Pure Elegance!');
    }
}
