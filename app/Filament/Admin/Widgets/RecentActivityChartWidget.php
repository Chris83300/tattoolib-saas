<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use App\Models\BookingRequest;
use App\Models\User;
use App\Models\Complaint;
use Laravel\Cashier\Subscription as CashierSubscription;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RecentActivityChartWidget extends ChartWidget
{
    protected ?string $heading = '📈 Activité Récente (30 jours)';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // Données des 30 derniers jours
        $days = collect(range(29, 0))->map(function($daysAgo) {
            return Carbon::now()->subDays($daysAgo)->format('Y-m-d');
        });

        $dailyActivity = $days->mapWithKeys(function($date) {
            $payments = Payment::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->count();

            $bookings = BookingRequest::whereDate('created_at', $date)->count();
            $newUsers = User::whereDate('created_at', $date)->count();
            $complaints = Complaint::whereDate('created_at', $date)->count();

            return [$date => [
                'payments' => $payments,
                'bookings' => $bookings,
                'users' => $newUsers,
                'complaints' => $complaints,
            ]];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Paiements',
                    'data' => $dailyActivity->pluck('payments')->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Demandes',
                    'data' => $dailyActivity->pluck('bookings')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Nouveaux utilisateurs',
                    'data' => $dailyActivity->pluck('users')->toArray(),
                    'backgroundColor' => 'rgba(168, 85, 247, 0.2)',
                    'borderColor' => 'rgba(168, 85, 247, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Réclamations',
                    'data' => $dailyActivity->pluck('complaints')->toArray(),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor' => 'rgba(239, 68, 68, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $days->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFooter(): ?string
    {
        $totalPayments = Payment::whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 'completed')
            ->count();

        $totalBookings = BookingRequest::whereDate('created_at', '>=', Carbon::now()->subDays(30))->count();
        $totalUsers = User::whereDate('created_at', '>=', Carbon::now()->subDays(30))->count();
        $totalComplaints = Complaint::whereDate('created_at', '>=', Carbon::now()->subDays(30))->count();

        return "30 jours: {$totalPayments} paiements | {$totalBookings} demandes | {$totalUsers} utilisateurs | {$totalComplaints} réclamations";
    }
}
