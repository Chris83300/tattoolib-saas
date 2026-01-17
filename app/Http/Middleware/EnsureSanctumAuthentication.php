<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class EnsureSanctumAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Vérifier si le token existe et n'est pas expiré
        $accessToken = PersonalAccessToken::where('token', hash('sha256', $token))
            ->where('expires_at', '>', now())
            ->first();

        if (!$accessToken) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Attacher l'utilisateur à la requête
        $user = $accessToken->tokenable;
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
