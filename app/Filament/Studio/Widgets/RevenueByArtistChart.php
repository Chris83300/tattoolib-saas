<?php

namespace App\Filament\Studio\Widgets;

use App\Services\StudioStatsService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class RevenueByArtistChart extends ChartWidget
{
    protected ?string $heading = 'Revenus par artiste — 6 derniers mois';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';
    protected ?string $maxHeight = '320px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return ['datasets' => [], 'labels' => []];
        }

        $chart = Cache::remember("studio.{$studio->id}.revenue.chart", 300, function () use ($studio) {
            return (new StudioStatsService($studio))->getMonthlyRevenueByArtist(6);
        });

        $datasets = [];
        foreach ($chart['datasets'] as $ds) {
            $datasets[] = [
                'label'           => $ds['label'],
                'data'            => $ds['data'],
                'backgroundColor' => $ds['color'],
                'borderColor'     => $ds['color'],
                'borderWidth'     => 1,
                'borderRadius'    => 4,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels'   => $chart['labels'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'bottom',
                    'labels'   => ['usePointStyle' => true, 'pointStyle' => 'circle'],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
