<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);

        $middleware->web(prepend: [
            \App\Http\Middleware\BlockSuspiciousIps::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        // Exclure les routes webhook Stripe du CSRF (Cashier + custom)
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
            'stripe/*',
            'webhooks/stripe',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'secure.file.upload' => \App\Http\Middleware\SecureFileUpload::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'block.suspicious.ips' => \App\Http\Middleware\BlockSuspiciousIps::class,
            'custom.throttle' => \App\Http\Middleware\CustomThrottle::class,
            'pro' => \App\Http\Middleware\EnsureProPlan::class,
            'artisan.can.operate' => \App\Http\Middleware\EnsureArtisanCanOperate::class,
            'artist.2fa' => \App\Http\Middleware\EnsureArtistHas2FA::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
