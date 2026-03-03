<?php

namespace App\Filament\Studio\Widgets;

use App\Models\BookingRequest;
use App\Models\Tattooer;
use App\Models\Piercer;
use Filament\Widgets\ChartWidget;

class MonthlyRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenus mensuels (6 derniers mois)';
    protected static ?int $sort = 3;
    public ?string $dataChecksum = '';

    protected function getData(): array
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return ['datasets' => [], 'labels' => []];
        }

        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        $base = BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        })->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline']);

        $labels   = [];
        $revenues = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[]   = $month->format('M Y');
            $revenues[] = round(
                (clone $base)
                    ->whereMonth('updated_at', $month->month)
                    ->whereYear('updated_at', $month->year)
                    ->sum('deposit_amount') / 100,
                2
            );
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Revenu (€)',
                    'data'            => $revenues,
                    'borderColor'     => '#8B7355',
                    'backgroundColor' => 'rgba(139,115,85,0.15)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
