<?php

namespace App\Services;

use App\Contracts\ArtisanInterface;
use App\Models\BookingRequest;
use App\Models\Review;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TattooerStatsService
{
    /**
     * Get all dashboard statistics in a single query
     */
    public function getDashboardStats(ArtisanInterface $artisan): array
    {
        return Cache::remember(
            "tattooer.{$artisan->id}.dashboard_stats",
            now()->addHour(),
            function () use ($artisan) {
                // UNE SEULE requête pour toutes les stats booking
                $bookingStats = BookingRequest::where('bookable_type', get_class($artisan))
                    ->where('bookable_id', $artisan->id)
                    ->selectRaw('
                        COUNT(CASE WHEN status = "confirmed" THEN 1 END) as completed_projects,
                        COUNT(CASE WHEN status = "pending" THEN 1 END) as active_projects,
                        COUNT(CASE WHEN status IN ("accepted", "awaiting_deposit", "deposit_paid", "design_sent", "date_confirmed") THEN 1 END) as accepted_projects,
                        COUNT(DISTINCT client_id) as total_clients,
                        COALESCE(SUM(CASE WHEN status IN ("confirmed", "date_confirmed") AND total_deposit_amount IS NOT NULL THEN total_deposit_amount ELSE 0 END), 0) as total_earnings
                    ')
                    ->first();

                // UNE SEULE requête pour les reviews (si table existe)
                $reviewStats = $this->getReviewStats($artisan);

                return [
                    'completed_projects' => $bookingStats->completed_projects ?? 0,
                    'active_projects' => $bookingStats->active_projects ?? 0,
                    'accepted_projects' => $bookingStats->accepted_projects ?? 0,
                    'total_clients' => $bookingStats->total_clients ?? 0,
                    'total_earnings' => $bookingStats->total_earnings ?? 0,
                    'average_rating' => $reviewStats['average_rating'] ?? 0,
                    'total_reviews' => $reviewStats['total_reviews'] ?? 0,
                    'portfolio_count' => method_exists($artisan, 'getMedia') ? $artisan->getMedia('portfolio')->count() : 0,
                ];
            }
        );
    }

    /**
     * Get review statistics efficiently
     */
    private function getReviewStats(ArtisanInterface $artisan): array
    {
        // Vérifier si la table reviews existe
        if (!DB::getSchemaBuilder()->hasTable('reviews')) {
            return ['average_rating' => 0, 'total_reviews' => 0];
        }

        return Cache::remember(
            "tattooer.{$artisan->id}.review_stats",
            now()->addDay(),
            function () use ($artisan) {
                $stats = Review::where('reviewable_type', get_class($artisan))
                    ->where('reviewable_id', $artisan->id)
                    ->selectRaw('
                        AVG(rating) as average_rating,
                        COUNT(*) as total_reviews
                    ')
                    ->first();

                return [
                    'average_rating' => (float) ($stats->average_rating ?? 0),
                    'total_reviews' => (int) ($stats->total_reviews ?? 0)
                ];
            }
        );
    }

    /**
     * Get statistics for requests page (with counts)
     */
    public function getRequestsStats(ArtisanInterface $artisan): array
    {
        return Cache::remember(
            "tattooer.{$artisan->id}.requests_stats",
            now()->addMinutes(30),
            function () use ($artisan) {
                $stats = BookingRequest::where('bookable_type', get_class($artisan))
                    ->where('bookable_id', $artisan->id)
                    ->selectRaw('
                        COUNT(CASE WHEN status = "pending" THEN 1 END) as pending,
                        COUNT(CASE WHEN status = "accepted" THEN 1 END) as accepted,
                        COUNT(CASE WHEN status = "confirmed" THEN 1 END) as confirmed,
                        COUNT(CASE WHEN status IN ("cancelled", "rejected", "expired") THEN 1 END) as cancelled,
                        COUNT(CASE WHEN status IN ("awaiting_deposit", "deposit_paid", "design_sent") THEN 1 END) as in_progress,
                        COUNT(*) as total
                    ')
                    ->first();

                return [
                    'pending' => (int) ($stats->pending ?? 0),
                    'accepted' => (int) ($stats->accepted ?? 0),
                    'confirmed' => (int) ($stats->confirmed ?? 0),
                    'cancelled' => (int) ($stats->cancelled ?? 0),
                    'in_progress' => (int) ($stats->in_progress ?? 0),
                    'total' => (int) ($stats->total ?? 0)
                ];
            }
        );
    }

    /**
     * Get monthly earnings data
     */
    public function getMonthlyEarnings(ArtisanInterface $artisan, int $year, int $month): float
    {
        return Cache::remember(
            "tattooer.{$artisan->id}.earnings.{$year}.{$month}",
            now()->addDay(),
            function () use ($artisan, $year, $month) {
                return BookingRequest::where('bookable_type', get_class($artisan))
                    ->where('bookable_id', $artisan->id)
                    ->where('status', 'confirmed')
                    ->whereYear('confirmed_at', $year)
                    ->whereMonth('confirmed_at', $month)
                    ->sum('deposit_amount');
            }
        );
    }

    /**
     * Get client statistics efficiently
     */
    public function getClientStats(ArtisanInterface $artisan): array
    {
        return Cache::remember(
            "tattooer.{$artisan->id}.client_stats",
            now()->addHour(),
            function () use ($artisan) {
                return BookingRequest::where('bookable_type', get_class($artisan))
                    ->where('bookable_id', $artisan->id)
                    ->selectRaw('
                        COUNT(DISTINCT client_id) as total_clients,
                        COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as new_clients_30d,
                        COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as new_clients_7d,
                        AVG(TIMESTAMPDIFF(DAY, created_at, NOW())) as avg_days_to_first_booking
                    ')
                    ->first();
            }
        );
    }

    /**
     * Invalidate all caches for an artisan
     */
    public function invalidateAllCaches(ArtisanInterface $artisan): void
    {
        $patterns = [
            "tattooer.{$artisan->id}.dashboard_stats",
            "tattooer.{$artisan->id}.review_stats",
            "tattooer.{$artisan->id}.requests_stats",
            "tattooer.{$artisan->id}.client_stats",
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        return [
            'cache_hit_rate' => $this->getCacheHitRate(),
            'avg_query_time' => $this->getAverageQueryTime(),
            'slow_queries_count' => $this->getSlowQueriesCount(),
        ];
    }

    /**
     * Calculate cache hit rate (simplified)
     */
    private function getCacheHitRate(): float
    {
        // Implementation simplifiée - en production, utiliser Redis stats
        return 0.85; // 85% hit rate (exemple)
    }

    /**
     * Get average query time (simplified)
     */
    private function getAverageQueryTime(): float
    {
        // En production, utiliser un système de monitoring
        return 25.5; // 25.5ms average (exemple)
    }

    /**
     * Get slow queries count (simplified)
     */
    private function getSlowQueriesCount(): int
    {
        // En production, utiliser un système de logging
        return 2; // 2 slow queries (exemple)
    }
}
