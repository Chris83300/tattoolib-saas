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
        $response = $next($request);

        // Headers de sécurité
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // CSP adaptée à l'environnement
        $csp = $this->buildCspHeader($request);
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

        return $response;
    }

    /**
     * Construit le header CSP adapté au contexte
     */
    private function buildCspHeader(Request $request): string
    {
        // ENVIRONNEMENT LOCAL/TESTING : CSP permissif pour Vite (IPv4 uniquement)
        if (app()->environment(['local', 'testing'])) {
            return implode('; ', [
                "default-src 'self'",
                // Scripts - Vite HMR + Livewire/Alpine + CDN
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:* wss://localhost:* wss://127.0.0.1:* https://js.stripe.com https://cdn.jsdelivr.net",
                // Styles - Vite + Google Fonts + CDN
                "style-src 'self' 'unsafe-inline' http://localhost:* http://127.0.0.1:* https://fonts.googleapis.com https://cdn.jsdelivr.net",
                // Images
                "img-src 'self' data: https: http://localhost:* http://127.0.0.1:*",
                // Fonts
                "font-src 'self' data: https://fonts.gstatic.com",
                // Connections - WebSocket Vite + API
                "connect-src 'self' ws://localhost:* ws://127.0.0.1:* wss://localhost:* wss://127.0.0.1:* http://localhost:* http://127.0.0.1:* https://api.stripe.com",
                // Frames
                "frame-src 'self' https://js.stripe.com https://hooks.stripe.com",
                // Media
                "media-src 'self'",
                // Objects
                "object-src 'none'",
                // Base
                "base-uri 'self'",
                // Forms
                "form-action 'self'",
                // Frame ancestors
                "frame-ancestors 'none'",
                // Manifest PWA
                "manifest-src 'self'",
            ]);
        }

        // ENVIRONNEMENT PRODUCTION : CSP strict
        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com https://www.googletagmanager.com https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "img-src 'self' data: https:",
            "font-src 'self' data: https://fonts.gstatic.com",
            "connect-src 'self' https://api.stripe.com https://www.google-analytics.com wss://" . $request->getHost(),
            "frame-src 'self' https://js.stripe.com https://hooks.stripe.com",
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "manifest-src 'self'",
        ]);
    }
}
