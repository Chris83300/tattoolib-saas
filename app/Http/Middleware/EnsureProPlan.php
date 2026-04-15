<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProPlan
{
    public function handle(Request $request, Closure $next): Response
    {
        $artisan = auth()->user()?->artisan();
        $artisanType = auth()->user()?->artisanType() ?? 'tattooer';
        $routePrefix = $artisanType === 'piercer' ? 'pierceur' : 'tattooer';

        // Starter ET PRO ont accès — seuls les comptes sans abonnement actif sont bloqués
        if (!$artisan || !$artisan->canAccessStarterFeature()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Fonctionnalité réservée au plan Starter ou PRO.',
                    'upgrade_url' => route($routePrefix . '.subscription.plans'),
                ], 403);
            }

            return redirect()->route($routePrefix . '.subscription.plans')
                ->with('info', '🔒 Cette fonctionnalité est réservée au plan Starter ou PRO.');
        }

        return $next($request);
    }
}
