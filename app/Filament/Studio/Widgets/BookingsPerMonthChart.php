<?php

namespace App\Filament\Studio\Widgets;

use App\Services\StudioStatsService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class BookingsPerMonthChart extends ChartWidget
{
    protected ?string $heading = 'RDV par mois';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 1;
    protected ?string $maxHeight = '260px';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $studio = auth()->user()?->studio;
        if (!$studio) {
            return ['datasets' => [], 'labels' => []];
        }

        $data = Cache::remember("studio.{$studio->id}.bookings.chart", 300, function () use ($studio) {
            return (new StudioStatsService($studio))->getMonthlyBookings(6);
        });

        return [
            'datasets' => [
                [
                    'label'            => 'RDV',
                    'data'             => array_column($data, 'count'),
                    'backgroundColor'  => 'rgba(212, 181, 158, 0.2)',
                    'borderColor'      => '#D4B59E',
                    'borderWidth'      => 2,
                    'fill'             => true,
                    'tension'          => 0.3,
                    'pointBackgroundColor' => '#D4B59E',
                    'pointRadius'      => 4,
                ],
            ],
            'labels' => array_column($data, 'month'),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales'  => [
                'y' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1]],
            ],
        ];
    }
}
