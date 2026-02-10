<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;

class ExcludeWebhookFromCsrf extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {
        // Exclure la route webhook Stripe de la vérification CSRF
        if ($request->is('webhooks/stripe')) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
