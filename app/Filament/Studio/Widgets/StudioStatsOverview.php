<?php

namespace App\Filament\Studio\Widgets;

use App\Models\BookingRequest;
use App\Models\Piercer;
use App\Models\Tattooer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudioStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return [];
        }

        $artistCount = $studio->studioArtists()->where('is_active', true)->count();

        $artistUserIds = $studio->studioArtists()->where('is_active', true)->pluck('user_id')->filter();
        $tattooerIds   = Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds    = Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        $scopeQuery = function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        };

        $pendingRequests = BookingRequest::where($scopeQuery)
            ->where('status', 'pending')
            ->count();

        $completedThisMonth = BookingRequest::where($scopeQuery)
            ->where('status', 'completed')
            ->whereMonth('updated_at', now()->month)
            ->count();

        return [
            Stat::make('Artistes actifs', $artistCount)
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Demandes en attente', $pendingRequests)
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Complétées ce mois', $completedThisMonth)
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Facturation', number_format($studio->monthlyPrice(), 2, ',', ' ') . ' €/mois')
                ->icon('heroicon-o-currency-euro')
                ->color('primary'),
        ];
    }
}
