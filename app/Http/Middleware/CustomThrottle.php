<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

class CustomThrottle
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ThrottleRequestsException $e) {
            $headers = $e->getHeaders();
            $retryAfter = $headers['Retry-After'] ?? 60;
            
            // Réponses personnalisées selon le type de limite
            $message = $this->getThrottleMessage($request, $retryAfter);
            
            return response()->json([
                'error' => $message,
                'retry_after' => (int) $retryAfter,
                'retry_after_human' => now()->addSeconds($retryAfter)->diffForHumans(),
                'limit_type' => $this->detectLimitType($request),
            ], 429)->withHeaders($headers);
        }
    }
    
    /**
     * Get user-friendly throttle message
     */
    private function getThrottleMessage(Request $request, int $retryAfter): string
    {
        $uri = $request->path();
        
        // Messages personnalisés selon le contexte
        if (str_contains($uri, 'login')) {
            return 'Trop de tentatives de connexion. Réessayez dans ' . $retryAfter . ' secondes.';
        }
        
        if (str_contains($uri, 'messages')) {
            return 'Trop de messages envoyés. Limite de 30 messages par minute.';
        }
        
        if (str_contains($uri, 'upload') || str_contains($uri, 'attachment')) {
            return 'Trop de fichiers uploadés. Limite de 10 fichiers par heure.';
        }
        
        if (str_contains($uri, 'payment')) {
            return 'Trop de tentatives de paiement. Limite de 3 paiements par heure.';
        }
        
        if (str_contains($uri, 'register')) {
            return 'Trop de tentatives d\'inscription. Réessayez dans ' . $retryAfter . ' secondes.';
        }
        
        // Message par défaut
        return 'Trop de requêtes. Réessayez dans ' . $retryAfter . ' secondes.';
    }
    
    /**
     * Detect what type of limit was triggered
     */
    private function detectLimitType(Request $request): string
    {
        $uri = $request->path();
        
        if (str_contains($uri, 'login') || str_contains($uri, 'register')) {
            return 'authentication';
        }
        
        if (str_contains($uri, 'messages')) {
            return 'messaging';
        }
        
        if (str_contains($uri, 'upload') || str_contains($uri, 'attachment')) {
            return 'uploads';
        }
        
        if (str_contains($uri, 'payment')) {
            return 'payments';
        }
        
        return 'api';
    }
}
