<?php

namespace App\Filament\Studio\Widgets;

use App\Services\StudioStatsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StudioStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return [];
        }

        $stats = Cache::remember("studio.{$studio->id}.dashboard.stats", 300, function () use ($studio) {
            return (new StudioStatsService($studio))->getDashboardStats();
        });

        return [
            Stat::make('CA ce mois', number_format($stats['revenue_this_month'], 2, ',', ' ') . ' €')
                ->description($this->trendLabel($stats['revenue_change_pct'], 'vs mois dernier'))
                ->descriptionIcon($stats['revenue_change_pct'] >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($stats['revenue_change_pct'] >= 0 ? 'success' : 'danger')
                ->chart($this->miniSparkline($studio)),

            Stat::make('RDV ce mois', $stats['bookings_this_month'])
                ->description($this->trendLabel($stats['bookings_month_change_pct'], 'vs mois dernier'))
                ->descriptionIcon($stats['bookings_month_change_pct'] >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($stats['bookings_month_change_pct'] >= 0 ? 'success' : 'danger'),

            Stat::make('RDV cette semaine', $stats['bookings_this_week'])
                ->description($this->trendLabel($stats['bookings_week_change_pct'], 'vs semaine dernière'))
                ->descriptionIcon($stats['bookings_week_change_pct'] >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($stats['bookings_week_change_pct'] >= 0 ? 'success' : 'danger'),

            Stat::make('En attente', $stats['pending_bookings'])
                ->description('Demandes à traiter')
                ->descriptionIcon('heroicon-m-clock')
                ->color($stats['pending_bookings'] > 0 ? 'warning' : 'gray'),

            Stat::make('Artistes actifs', $stats['active_artists'] . ' / ' . $stats['total_artists'])
                ->description('Artistes dans le studio')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Clients', $stats['total_clients'])
                ->description('Clients uniques')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),
        ];
    }

    protected function trendLabel(float $pct, string $suffix): string
    {
        $sign = $pct >= 0 ? '+' : '';
        return "{$sign}{$pct}% {$suffix}";
    }

    protected function miniSparkline($studio): array
    {
        $service = new StudioStatsService($studio);
        $chart   = $service->getMonthlyRevenueByArtist(6);
        $totals  = array_fill(0, 6, 0);
        foreach ($chart['datasets'] as $ds) {
            foreach ($ds['data'] as $i => $val) {
                $totals[$i] += $val;
            }
        }
        return $totals;
    }
}
