<?php

namespace App\Traits;

use App\Models\BookingRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait CalculatesStats
{
    /**
     * Obtenir stats booking requests
     */
    public function getBookingStats(): array
    {
        $stats = BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->selectRaw('
                COUNT(CASE WHEN status = "confirmed" THEN 1 END) as completed_projects,
                COUNT(CASE WHEN status IN ("pending", "accepted", "awaiting_deposit") THEN 1 END) as active_projects,
                COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_requests,
                COUNT(DISTINCT client_id) as total_clients,
                COALESCE(SUM(CASE WHEN status = "confirmed" THEN total_deposit_amount ELSE 0 END), 0) as total_earnings,
                COALESCE(SUM(CASE WHEN status IN ("confirmed", "deposit_paid") THEN total_deposit_amount ELSE 0 END), 0) as total_deposits
            ')
            ->first();

        return [
            'completed_projects' => (int) ($stats->completed_projects ?? 0),
            'active_projects' => (int) ($stats->active_projects ?? 0),
            'pending_requests' => (int) ($stats->pending_requests ?? 0),
            'total_clients' => (int) ($stats->total_clients ?? 0),
            'total_earnings' => (float) ($stats->total_earnings ?? 0),
            'total_deposits' => (float) ($stats->total_deposits ?? 0),
        ];
    }

    /**
     * Obtenir revenus mensuels
     */
    public function getMonthlyEarnings(int $year, int $month): float
    {
        return (float) BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->where('status', 'confirmed')
            ->whereYear('confirmed_at', $year)
            ->whereMonth('confirmed_at', $month)
            ->sum('total_deposit_amount');
    }

    /**
     * Obtenir revenus annuels
     */
    public function getYearlyEarnings(int $year): float
    {
        return (float) BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->where('status', 'confirmed')
            ->whereYear('confirmed_at', $year)
            ->sum('total_deposit_amount');
    }

    /**
     * Obtenir taux d'acceptation
     */
    public function getAcceptanceRate(): float
    {
        $total = BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->whereIn('status', ['accepted', 'rejected', 'confirmed', 'cancelled'])
            ->count();

        if ($total === 0) {
            return 0.0;
        }

        $accepted = BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->whereIn('status', ['accepted', 'confirmed'])
            ->count();

        return round(($accepted / $total) * 100, 1);
    }

    /**
     * Obtenir temps de réponse moyen (en heures)
     */
    public function getAverageResponseTime(): float
    {
        $avgSeconds = BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->whereNotNull('accepted_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, accepted_at)) as avg_seconds')
            ->value('avg_seconds');

        return $avgSeconds ? round($avgSeconds / 3600, 1) : 0.0; // Convertir en heures
    }

    /**
     * Obtenir statistiques mensuelles détaillées
     */
    public function getMonthlyStats(int $year = null): array
    {
        $year = $year ?? now()->year;
        $stats = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyEarnings = $this->getMonthlyEarnings($year, $month);
            $monthlyBookings = BookingRequest::where('bookable_type', get_class($this))
                ->where('bookable_id', $this->id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $stats[$month] = [
                'month' => $month,
                'month_name' => Carbon::create($year, $month, 1)->format('F'),
                'earnings' => $monthlyEarnings,
                'bookings_count' => $monthlyBookings,
            ];
        }

        return $stats;
    }

    /**
     * Obtenir top clients par revenus
     */
    public function getTopClients(int $limit = 5): array
    {
        return BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->where('status', 'confirmed')
            ->join('clients', 'clients.id', '=', 'booking_requests.client_id')
            ->join('users', 'users.id', '=', 'clients.user_id')
            ->selectRaw('
                clients.id,
                users.name,
                users.email,
                COUNT(*) as bookings_count,
                SUM(booking_requests.total_deposit_amount) as total_spent
            ')
            ->groupBy('clients.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Obtenir taux de conversion
     */
    public function getConversionRate(): float
    {
        $totalRequests = BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->count();

        if ($totalRequests === 0) {
            return 0.0;
        }

        $confirmedRequests = BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->where('status', 'confirmed')
            ->count();

        return round(($confirmedRequests / $totalRequests) * 100, 1);
    }

    /**
     * Obtenir statistiques des services
     */
    public function getServiceStats(): array
    {
        return BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->where('status', 'confirmed')
            ->selectRaw('
                tattoo_style,
                COUNT(*) as count,
                AVG(estimated_total_price) as avg_price,
                SUM(estimated_total_price) as total_revenue
            ')
            ->groupBy('tattoo_style')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();
    }
}
