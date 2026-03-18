<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureArtistHas2FA
{
    /**
     * Bloque l'accès si l'artiste a Stripe Connect actif mais n'a pas activé le 2FA.
     */
    public function handle(Request $request, Closure $next)
    {
        $user   = $request->user();
        $artist = $user?->tattooer ?? $user?->piercer ?? null;

        if (!$artist) {
            return $next($request);
        }

        $hasActiveConnect = $artist->stripe_connect_charges_enabled ?? false;

        if ($hasActiveConnect && !$user->two_factor_confirmed_at) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message'      => 'La double authentification est requise pour accéder à cette fonctionnalité.',
                    'requires_2fa' => true,
                ], 403);
            }

            return redirect()->route('two-factor.setup')
                ->with('warning',
                    'La double authentification est obligatoire car vous avez un compte Stripe Connect actif. '
                    . 'Sécurisez votre compte pour continuer.'
                );
        }

        return $next($request);
    }
}
