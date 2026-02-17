<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tattooer = auth()->user()?->tattooer;

        if (!$tattooer || $tattooer->isFree()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Fonctionnalité réservée au plan PRO.',
                    'upgrade_url' => route('tattooer.subscription.plans'),
                ], 403);
            }

            return redirect()->route('tattooer.subscription.plans')
                ->with('info', '🔒 Cette fonctionnalité est réservée au plan PRO.');
        }

        return $next($request);
    }
}
