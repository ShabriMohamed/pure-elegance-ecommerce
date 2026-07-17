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
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net",
            "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net",
            "img-src 'self' data: https:",
            "connect-src 'self'",
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
