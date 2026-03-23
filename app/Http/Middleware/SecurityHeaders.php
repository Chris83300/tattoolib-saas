<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Générer un nonce unique par requête (avant le rendu des vues)
        $nonce = base64_encode(random_bytes(16));
        app()->instance('csp-nonce', $nonce);

        $response = $next($request);

        // Headers de sécurité
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // CSP active (bloque les violations)
        $csp = $this->buildCspHeader($request, $nonce);
        $response->headers->set('Content-Security-Policy', $csp);

        // Permissions Policy
        $permissionsPolicy = implode(', ', [
            'geolocation=()',
            'microphone=()',
            'camera=()',
            'payment=(self)',
            'usb=()',
            'magnetometer=()',
            'gyroscope=()',
            'accelerometer=()',
        ]);
        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        // HSTS en production uniquement
        if (app()->environment('production')) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Anti-indexation du panel admin
        if ($request->is('admin/*') || $request->is('admin')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }

    /**
     * Construit le header CSP adapté au contexte
     */
    private function buildCspHeader(Request $request, string $nonce): string
    {
        // ENVIRONNEMENT LOCAL/TESTING : CSP permissif pour Vite HMR et Livewire
        // unsafe-inline/eval nécessaires pour le développement local
        if (app()->environment(['local', 'testing'])) {
            return implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:* wss://localhost:* wss://127.0.0.1:* https://js.stripe.com https://cdn.jsdelivr.net",
                "style-src 'self' 'unsafe-inline' http://localhost:* http://127.0.0.1:* https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net",
                "img-src 'self' data: https: http://localhost:* http://127.0.0.1:*",
                "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net",
                "connect-src 'self' ws://localhost:* ws://127.0.0.1:* wss://localhost:* wss://127.0.0.1:* http://localhost:* http://127.0.0.1:* https://api.stripe.com",
                "frame-src 'self' https://js.stripe.com https://hooks.stripe.com",
                "media-src 'self'",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'none'",
                "manifest-src 'self'",
                "worker-src blob: 'self'",
            ]);
        }

        // ENVIRONNEMENT PRODUCTION : CSP strict avec nonce — sans unsafe-inline/eval sur script-src
        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}' 'unsafe-eval' https://js.stripe.com https://www.googletagmanager.com https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net",
            "img-src 'self' data: https:",
            "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net",
            "connect-src 'self' https://api.stripe.com https://www.google-analytics.com wss://" . $request->getHost(),
            "frame-src 'self' https://js.stripe.com https://hooks.stripe.com",
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self' https://checkout.stripe.com https://billing.stripe.com",
            "frame-ancestors 'none'",
            "manifest-src 'self'",
        ]);
    }
}
