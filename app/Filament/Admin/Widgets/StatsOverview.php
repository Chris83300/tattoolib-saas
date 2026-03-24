<?php

namespace App\Filament\Admin\Widgets;

use App\Services\PlatformRevenueService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $data = Cache::remember('admin.platform.revenue', 300, function () {
            return app(PlatformRevenueService::class)->getPlatformRevenue();
        });

        $commTrend = $data['commissions_trend'];

        return [
            // 1. Volume transactions (indicatif)
            Stat::make('Volume transactions', number_format($data['transactions']['volume_total'], 2, ',', ' ') . ' €')
                ->description($data['transactions']['transaction_count'] . ' transactions')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('gray')
                ->extraAttributes(['title' => 'Volume total des paiements clients → artistes (indicatif, pas le CA plateforme)']),

            // 2. Commissions 7%
            Stat::make('Commissions 7%', number_format($data['commissions']['total'], 2, ',', ' ') . ' €')
                ->description(
                    ($commTrend['change_pct'] >= 0 ? '+' : '') . $commTrend['change_pct'] . '% vs mois dernier'
                    . ' · ' . $data['commissions']['count'] . ' tx'
                )
                ->descriptionIcon($commTrend['change_pct'] >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($commTrend['change_pct'] >= 0 ? 'success' : 'danger'),

            // 3. MRR Abonnements
            Stat::make('MRR Abonnements', number_format($data['subscriptions']['mrr'], 2, ',', ' ') . ' €/mois')
                ->description(
                    $data['subscriptions']['starter_count'] . ' Starter · '
                    . $data['subscriptions']['pro_count'] . ' Pro · '
                    . $data['subscriptions']['studio_count'] . ' Studio'
                )
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),

            // 4. CA Total Plateforme
            Stat::make('CA Plateforme', number_format($data['commissions']['total'] + $data['subscriptions']['mrr'], 2, ',', ' ') . ' €')
                ->description('Commissions + Abonnements')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
        ];
    }
}

