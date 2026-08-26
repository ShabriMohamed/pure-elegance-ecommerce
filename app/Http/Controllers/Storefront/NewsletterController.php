<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        // Validated by hand rather than via $request->validate() so a failure can
        // also carry the #newsletter fragment — otherwise the error message
        // renders in the newsletter section while the browser sits at the top of
        // the page, showing nothing.
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email:rfc', 'max:255'],
            // Honeypot: real users never fill this hidden field.
            'website' => ['nullable', 'size:0'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->withFragment('newsletter');
        }

        $validated = $validator->validated();

        // Silently accept bots (that filled the honeypot) without storing.
        if ($request->filled('website')) {
            return $this->done('Thank you for subscribing!');
        }

        NewsletterSubscriber::firstOrCreate(
            ['email' => strtolower($validated['email'])],
            ['subscribed_at' => now(), 'ip_address' => $request->ip()]
        );

        return $this->done('Thank you for subscribing to Pure Elegance!');
    }

    /**
     * Send the subscriber back to the form they just used.
     *
     * The flash uses its own key (not 'success') so the layout's page-top alert
     * stays quiet and the newsletter section renders the confirmation inline;
     * the fragment scrolls that section into view. Landing at the top of a
     * ~6000px homepage left people with no visible sign it had worked.
     */
    private function done(string $message): RedirectResponse
    {
        return back()
            ->with('newsletter_status', $message)
            ->withFragment('newsletter');
    }
}
