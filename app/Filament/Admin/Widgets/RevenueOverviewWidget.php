<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use App\Models\BookingTransaction;
use Laravel\Cashier\Subscription as CashierSubscription;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueOverviewWidget extends ChartWidget
{
    protected ?string $heading = '💰 Chiffre d\'Affaires (30 derniers jours)';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Données des 30 derniers jours
        $days = collect(range(29, 0))->map(function($daysAgo) {
            return Carbon::now()->subDays($daysAgo)->format('Y-m-d');
        });

        $dailyRevenue = $days->mapWithKeys(function($date) {
            // Revenus des paiements
            $paymentRevenue = Payment::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('amount');

            // Revenus des abonnements (estimation basée sur les prix)
            $subscriptionRevenue = CashierSubscription::where('stripe_status', 'active')
                ->whereDate('created_at', $date)
                ->get()
                ->sum(function($subscription) {
                    // Estimation simple : 9.99€ pour starter, 29.99€ pour pro
                    return str_contains($subscription->stripe_price ?? '', 'starter') ? 999 : 2999;
                });

            $totalRevenue = $paymentRevenue + $subscriptionRevenue;

            return [$date => $totalRevenue / 100]; // Conversion en euros si en centimes
        });

        // Calcul du CA total avec commission STARTER 7% (approximatif — taux réel par transaction)
        $totalRevenue = $dailyRevenue->sum();
        $platformCommission = $totalRevenue * 0.07;
        $netRevenue = $totalRevenue - $platformCommission;

        return [
            'datasets' => [
                [
                    'label' => 'CA Journalier (€)',
                    'data' => $dailyRevenue->values()->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
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
        $totalRevenue = Payment::whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 'completed')
            ->sum('amount') / 100;

        $platformCommission = $totalRevenue * 0.07;

        return "CA Total 30 jours: " . number_format($totalRevenue, 2) . "€ | Commission plateforme (7%): " . number_format($platformCommission, 2) . "€";
    }
}
