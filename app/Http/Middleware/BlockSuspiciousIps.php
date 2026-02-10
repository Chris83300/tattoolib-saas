<?php

namespace App\Http\Middleware;

use App\Services\SecurityMonitoringService;
use Closure;
use Illuminate\Http\Request;

class BlockSuspiciousIps
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $monitor = app(SecurityMonitoringService::class);
        $ip = $request->ip();
        
        // Vérifier si l'IP est bloquée
        if ($monitor->isIpBlocked($ip)) {
            $blockInfo = $monitor->getIpBlockInfo($ip);
            
            return response()->json([
                'error' => 'Votre adresse IP a été temporairement bloquée.',
                'reason' => $blockInfo['reason'] ?? 'Security violation',
                'blocked_until' => $blockInfo['blocked_until'],
                'retry_after' => $blockInfo['blocked_until']->diffForHumans(),
            ], 403);
        }
        
        // Vérifier si l'utilisateur est bloqué
        if ($request->user() && $monitor->isUserBlocked($request->user()->id)) {
            $blockInfo = $monitor->getUserBlockInfo($request->user()->id);
            
            return response()->json([
                'error' => 'Votre compte a été temporairement suspendu.',
                'reason' => $blockInfo['reason'] ?? 'Security violation',
                'blocked_until' => $blockInfo['blocked_until'],
                'retry_after' => $blockInfo['blocked_until']->diffForHumans(),
            ], 403);
        }
        
        // Tracker l'activité suspecte
        $this->trackSuspiciousPatterns($request, $monitor);
        
        return $next($request);
    }
    
    /**
     * Track suspicious patterns in requests
     */
    private function trackSuspiciousPatterns(Request $request, SecurityMonitoringService $monitor): void
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $uri = $request->path();
        
        // Détecter les user agents suspects
        $suspiciousAgents = [
            'sqlmap', 'nikto', 'dirb', 'nmap', 'burp', 'owasp', 'zap',
            'python-requests', 'curl', 'wget', 'powershell', 'bash'
        ];
        
        foreach ($suspiciousAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                $monitor->trackSuspiciousActivity($ip, 'suspicious_user_agent', [
                    'user_agent' => $userAgent,
                    'uri' => $uri,
                ]);
                break;
            }
        }
        
        // Détecter les patterns d'injection SQL dans l'URI
        $sqlPatterns = [
            '/(\%27)|(\')|(\-\-)|(\%23)|(#)/i',
            '/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i',
            '/\w*((\%27)|(\'))\w*((\%6F)|o|(\%4F))\w*((\%72)|r|(\%52))/i',
            '/((\%27)|(\')).*((\%6F)|o|(\%4F)).*((\%72)|r|(\%52))/i',
        ];
        
        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $uri)) {
                $monitor->trackSuspiciousActivity($ip, 'sql_injection_attempt', [
                    'uri' => $uri,
                    'pattern_matched' => $pattern,
                ]);
                break;
            }
        }
        
        // Détecter les tentatives de path traversal
        $pathTraversalPatterns = [
            '/\.\.\//',
            '/(\.\.%2f)/i',
            '/(%2e%2e%2f)/i',
            '/(\.\.%5c)/i',
            '/(%2e%2e%5c)/i',
        ];
        
        foreach ($pathTraversalPatterns as $pattern) {
            if (preg_match($pattern, $uri)) {
                $monitor->trackSuspiciousActivity($ip, 'path_traversal_attempt', [
                    'uri' => $uri,
                    'pattern_matched' => $pattern,
                ]);
                break;
            }
        }
        
        // Détecter les tentatives XSS dans l'URI
        $xssPatterns = [
            '/<script[^>]*>.*?<\/script>/i',
            '/<iframe[^>]*>.*?<\/iframe>/i',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<img[^>]*onerror[^>]*>/i',
        ];
        
        foreach ($xssPatterns as $pattern) {
            if (preg_match($pattern, $uri)) {
                $monitor->trackSuspiciousActivity($ip, 'xss_attempt_in_uri', [
                    'uri' => $uri,
                    'pattern_matched' => $pattern,
                ]);
                break;
            }
        }
    }
}
