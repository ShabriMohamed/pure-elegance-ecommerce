<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Add security headers to every response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        // Modern guidance: disable the legacy XSS auditor rather than enable it.
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=()');

        // Content-Security-Policy. 'unsafe-inline' is required because the storefront/admin
        // layouts use inline <script>/<style> blocks; combined with server-side output
        // escaping this still meaningfully hardens the app (blocks plugins, foreign framing,
        // base-tag hijacking and non-allowlisted hosts). The font hosts match the <link>
        // tags in the layouts (Google Fonts + Bunny Fonts).
        $scriptSrc  = "script-src 'self' 'unsafe-inline'";
        $styleSrc   = "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net";
        $connectSrc = "connect-src 'self'";

        // Local dev only: when the Vite dev server is running (`npm run dev`), it serves the
        // HMR client + assets from a cross-origin host and opens an HMR WebSocket — both of
        // which the strict 'self' policy would block, leaving a blank dev page. public/hot
        // exists only while the dev server runs, never in a built deployment, so production
        // keeps the strict policy untouched.
        if (app()->environment('local') && is_file(public_path('hot'))) {
            $devHost = trim((string) file_get_contents(public_path('hot')));
            if ($devHost !== '') {
                $wsHost = preg_replace('#^http#i', 'ws', $devHost);
                $scriptSrc  .= " {$devHost}";
                $styleSrc   .= " {$devHost}";
                $connectSrc .= " {$devHost} {$wsHost}";
            }
        }

        $csp = implode('; ', [
            "default-src 'self'",
            $scriptSrc,
            $styleSrc,
            "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net",
            "img-src 'self' data: https:",
            $connectSrc,
            "object-src 'none'",
            "base-uri 'self'",
            "frame-ancestors 'self'",
            "form-action 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        // HSTS only over HTTPS so local http development is unaffected.
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
