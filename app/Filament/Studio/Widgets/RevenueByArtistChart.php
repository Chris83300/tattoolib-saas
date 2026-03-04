<?php

namespace App\Filament\Studio\Widgets;

use App\Models\BookingRequest;
use App\Models\Tattooer;
use App\Models\Piercer;
use Filament\Widgets\ChartWidget;

class RevenueByArtistChart extends ChartWidget
{
    protected ?string $heading = 'Revenus par artiste (acomptes)';
    protected static ?int $sort = 2;
    public ?string $dataChecksum = '';

    protected function getData(): array
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return ['datasets' => [], 'labels' => []];
        }

        $artists = $studio->studioArtists()
            ->where('is_active', true)
            ->with('user')
            ->get();

        $labels   = [];
        $revenues = [];
        $colors   = ['#8B7355', '#6B9E78', '#7B8FA1', '#A07850', '#9E6B6B', '#6B7F9E'];

        foreach ($artists as $i => $sa) {
            if (!$sa->user_id) continue;

            $tattooerIds = Tattooer::where('user_id', $sa->user_id)->pluck('id');
            $piercerIds  = Piercer::where('user_id', $sa->user_id)->pluck('id');

            $revenue = BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
                $q->where(function ($q2) use ($tattooerIds) {
                    $q2->where('bookable_type', 'App\\Models\\Tattooer')
                       ->whereIn('bookable_id', $tattooerIds);
                })->orWhere(function ($q2) use ($piercerIds) {
                    $q2->where('bookable_type', 'App\\Models\\Piercer')
                       ->whereIn('bookable_id', $piercerIds);
                });
            })
            ->whereNotNull('deposit_paid_at')
            ->whereMonth('deposit_paid_at', now()->month)
            ->whereYear('deposit_paid_at', now()->year)
            ->sum('total_deposit_amount');

            $labels[]   = $sa->artist_name ?: $sa->user?->name ?? 'Artiste';
            $revenues[] = round($revenue, 2);
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Revenu ce mois (€)',
                    'data'            => $revenues,
                    'backgroundColor' => array_slice($colors, 0, count($revenues)),
                    'borderColor'     => array_slice($colors, 0, count($revenues)),
                    'borderWidth'     => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
