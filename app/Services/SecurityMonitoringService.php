<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SecurityMonitoringService
{
    /**
     * Track failed login attempts
     */
    public function trackFailedLogin(string $ip, string $email = null): void
    {
        $key = "failed_login:{$ip}";
        $attempts = Cache::get($key, 0);
        
        Cache::put($key, $attempts + 1, now()->addMinutes(30));
        
        // Bloquer après 10 tentatives en 30 minutes
        if ($attempts >= 10) {
            $this->blockIp($ip, 'excessive_login_attempts');
            Log::warning("IP blocked for excessive login attempts", [
                'ip' => $ip,
                'email' => $email,
                'attempts' => $attempts + 1,
                'blocked_until' => now()->addMinutes(30),
            ]);
        }
        
        // Alerte après 5 tentatives
        if ($attempts >= 5) {
            Log::notice("Multiple failed login attempts detected", [
                'ip' => $ip,
                'email' => $email,
                'attempts' => $attempts + 1,
            ]);
        }
    }
    
    /**
     * Track suspicious API activity
     */
    public function trackSuspiciousActivity(string $ip, string $activity, array $context = []): void
    {
        $key = "suspicious_activity:{$ip}";
        $activities = Cache::get($key, []);
        $activities[] = [
            'activity' => $activity,
            'timestamp' => now(),
            'context' => $context,
        ];
        
        Cache::put($key, $activities, now()->addHour());
        
        // Bloquer après 20 activités suspectes en 1 heure
        if (count($activities) >= 20) {
            $this->blockIp($ip, 'suspicious_activity');
            Log::alert("IP blocked for suspicious activity", [
                'ip' => $ip,
                'activity' => $activity,
                'total_activities' => count($activities),
                'blocked_until' => now()->addHour(),
            ]);
        }
    }
    
    /**
     * Track rapid file uploads
     */
    public function trackRapidUploads(string $userId): void
    {
        $key = "rapid_uploads:{$userId}";
        $uploads = Cache::get($key, []);
        $uploads[] = now();
        
        // Garder seulement les uploads des 5 dernières minutes
        $recentUploads = array_filter($uploads, function ($timestamp) {
            return $timestamp > now()->subMinutes(5);
        });
        
        Cache::put($key, $recentUploads, now()->addMinutes(5));
        
        // Bloquer après 10 uploads en 5 minutes
        if (count($recentUploads) >= 10) {
            Log::warning("User blocked for rapid uploads", [
                'user_id' => $userId,
                'uploads_count' => count($recentUploads),
                'timeframe' => '5 minutes',
            ]);
            
            // Bloquer l'utilisateur temporairement
            Cache::put("blocked_user:{$userId}", true, now()->addMinutes(30));
        }
    }
    
    /**
     * Track payment attempts
     */
    public function trackPaymentAttempt(string $userId, string $result, array $context = []): void
    {
        $key = "payment_attempts:{$userId}";
        $attempts = Cache::get($key, []);
        $attempts[] = [
            'result' => $result,
            'timestamp' => now(),
            'context' => $context,
        ];
        
        Cache::put($key, $attempts, now()->addHour());
        
        // Compter les échecs
        $failures = array_filter($attempts, function ($attempt) {
            return $attempt['result'] === 'failed';
        });
        
        // Bloquer après 5 échecs de paiement en 1 heure
        if (count($failures) >= 5) {
            Log::alert("User blocked for payment failures", [
                'user_id' => $userId,
                'failed_attempts' => count($failures),
                'blocked_until' => now()->addHour(),
            ]);
            
            Cache::put("blocked_user:{$userId}", true, now()->addHour());
        }
    }
    
    /**
     * Block an IP address
     */
    public function blockIp(string $ip, string $reason, int $durationMinutes = 60): void
    {
        Cache::put("blocked_ip:{$ip}", [
            'reason' => $reason,
            'blocked_at' => now(),
            'blocked_until' => now()->addMinutes($durationMinutes),
        ], now()->addMinutes($durationMinutes));
        
        Log::warning("IP blocked", [
            'ip' => $ip,
            'reason' => $reason,
            'duration_minutes' => $durationMinutes,
            'blocked_until' => now()->addMinutes($durationMinutes),
        ]);
    }
    
    /**
     * Check if IP is blocked
     */
    public function isIpBlocked(string $ip): bool
    {
        return Cache::has("blocked_ip:{$ip}");
    }
    
    /**
     * Check if user is blocked
     */
    public function isUserBlocked(string $userId): bool
    {
        return Cache::has("blocked_user:{$userId}");
    }
    
    /**
     * Get block information for IP
     */
    public function getIpBlockInfo(string $ip): ?array
    {
        return Cache::get("blocked_ip:{$ip}");
    }
    
    /**
     * Get block information for user
     */
    public function getUserBlockInfo(string $userId): ?array
    {
        return Cache::get("blocked_user:{$userId}");
    }
    
    /**
     * Clear IP block
     */
    public function clearIpBlock(string $ip): void
    {
        Cache::forget("blocked_ip:{$ip}");
        Log::info("IP block cleared", ['ip' => $ip]);
    }
    
    /**
     * Clear user block
     */
    public function clearUserBlock(string $userId): void
    {
        Cache::forget("blocked_user:{$userId}");
        Log::info("User block cleared", ['user_id' => $userId]);
    }
    
    /**
     * Get security statistics
     */
    public function getSecurityStats(): array
    {
        $stats = [
            'blocked_ips' => 0,
            'blocked_users' => 0,
            'recent_failed_logins' => 0,
            'recent_suspicious_activities' => 0,
        ];
        
        // Compter les IPs bloquées
        for ($i = 0; $i < 100; $i++) {
            $key = "blocked_ip:" . md5($i);
            if (Cache::has($key)) {
                $stats['blocked_ips']++;
            }
        }
        
        // Compter les utilisateurs bloqués
        for ($i = 1; $i < 10000; $i++) {
            if (Cache::has("blocked_user:{$i}")) {
                $stats['blocked_users']++;
            }
        }
        
        return $stats;
    }
    
    /**
     * Detect brute force patterns
     */
    public function detectBruteForcePattern(string $ip): array
    {
        $key = "login_pattern:{$ip}";
        $attempts = Cache::get($key, []);
        
        // Analyser les tentatives des dernières 24 heures
        $recentAttempts = array_filter($attempts, function ($attempt) {
            return $attempt['timestamp'] > now()->subHours(24);
        });
        
        if (empty($recentAttempts)) {
            return ['detected' => false];
        }
        
        // Détecter les patterns suspects
        $timeIntervals = [];
        for ($i = 1; $i < count($recentAttempts); $i++) {
            $interval = $recentAttempts[$i]['timestamp']->diffInSeconds($recentAttempts[$i-1]['timestamp']);
            $timeIntervals[] = $interval;
        }
        
        // Si les tentatives sont très régulières (bot)
        $avgInterval = array_sum($timeIntervals) / count($timeIntervals);
        $isBotPattern = $avgInterval < 5; // Moins de 5 secondes entre tentatives
        
        return [
            'detected' => $isBotPattern,
            'attempts_count' => count($recentAttempts),
            'avg_interval' => $avgInterval,
            'timeframe' => '24 hours',
        ];
    }
}
