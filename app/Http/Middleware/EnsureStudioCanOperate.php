<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureStudioCanOperate
{
    /**
     * Routes accessibles en lecture seule même si le trial est expiré.
     * Le studio peut voir mais pas agir.
     */
    private array $readOnlyRoutes = [
        'studio.dashboard',
        'studio.artists',
        'studio.billing',
        'studio.settings',
        'studio.planning',
        'studio.requests',
        'studio.stats',
        'studio.profile',
        'studio.subscribe',
        'studio.subscribe.process',
        'studio.public.show',
        // Routes studio-artist autorisées en lecture seule
        'studio-artist.dashboard',
        'studio-artist.profile',
        'studio-artist.upgrade',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $studio = null;

        // Récupérer le studio selon le type d'utilisateur
        if ($user->isStudio()) {
            $studio = $user->studio;
        } elseif ($user->isStudioArtist()) {
            $studio = $user->artistStudio();
        }

        if (!$studio) {
            return $next($request);
        }

        // Trial actif ou abonnement actif → laisser passer
        if ($studio->canOperate()) {
            return $next($request);
        }

        // Trial expiré — vérifier si la route est autorisée en lecture seule
        $currentRoute = $request->route()?->getName();

        if ($currentRoute && in_array($currentRoute, $this->readOnlyRoutes)) {
            return $next($request);
        }

        // Route non autorisée en lecture seule
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Votre essai est terminé. Activez votre abonnement pour continuer.',
            ], 403);
        }

        // Rediriger selon le type d'utilisateur
        $redirectRoute = $user->isStudio() ? 'studio.billing' : 'studio-artist.dashboard';

        return redirect()->route($redirectRoute)
            ->with('error', 'Votre essai est terminé. Activez votre abonnement pour continuer.');
    }
}
