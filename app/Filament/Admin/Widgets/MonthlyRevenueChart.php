<?php

namespace App\Filament\Admin\Widgets;

use Laravel\Cashier\Subscription as CashierSubscription;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyRevenueChart extends ChartWidget
{
    protected ?string $heading = '📈 Graphique des Revenus Mensuels';

    protected function getData(): array
    {
        // Données des 12 derniers mois
        $months = collect(range(11, 0))->map(function($monthsAgo) {
            return Carbon::now()->subMonths($monthsAgo);
        });

        $monthlyRevenue = $months->mapWithKeys(function($month) {
            // Revenus des abonnements actifs ce mois-ci
            $subscriptions = CashierSubscription::where('stripe_status', 'active')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->get();

            $revenue = $subscriptions->sum(function($subscription) {
                // Estimation simple : 9.99€ pour starter, 29.99€ pour pro
                return str_contains($subscription->stripe_price ?? '', 'starter') ? 999 : 2999;
            });

            return [$month->format('Y-m') => $revenue / 100]; // Conversion en euros
        });

        return [
            'datasets' => [
                [
                    'label' => 'Revenus Mensuels (€)',
                    'data' => $monthlyRevenue->values()->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months->map(function($month) {
                return $month->format('M Y');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
