<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Subscription;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RecentActivity extends ChartWidget
{
    protected ?string $heading = '📈 Activité Récente (30 jours)';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $days = [];
        $newUsers = [];
        $appointments = [];
        $activeSubscriptions = [];

        // Générer les 30 derniers jours
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('d/m');

            // Nouveaux utilisateurs par jour
            $newUsers[] = User::whereDate('created_at', $date->format('Y-m-d'))->count();

            // RDV par jour
            $appointments[] = Appointment::whereDate('start_datetime', $date->format('Y-m-d'))->count();

            // Abonnements actifs par jour
            $activeSubscriptions[] = Subscription::where('status', 'active')
                ->whereDate('current_period_start', '<=', $date->format('Y-m-d'))
                ->whereDate('current_period_end', '>=', $date->format('Y-m-d'))
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Nouveaux utilisateurs',
                    'data' => $newUsers,
                    'borderColor' => '#D4B59E',
                    'backgroundColor' => 'rgba(212, 181, 158, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'RDV',
                    'data' => $appointments,
                    'borderColor' => '#06D6A0',
                    'backgroundColor' => 'rgba(6, 214, 160, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Abonnements actifs',
                    'data' => $activeSubscriptions,
                    'borderColor' => '#F77F00',
                    'backgroundColor' => 'rgba(247, 127, 0, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'title' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(255, 255, 255, 0.1)',
                    ],
                    'ticks' => [
                        'color' => 'rgba(255, 255, 255, 0.7)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'color' => 'rgba(255, 255, 255, 0.1)',
                    ],
                    'ticks' => [
                        'color' => 'rgba(255, 255, 255, 0.7)',
                        'maxRotation' => 45,
                        'minRotation' => 45,
                    ],
                ],
            ],
            'elements' => [
                'line' => [
                    'tension' => 0.4,
                ],
                'point' => [
                    'radius' => 3,
                    'hoverRadius' => 6,
                ],
            ],
        ];
    }
}
