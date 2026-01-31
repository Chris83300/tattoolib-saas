<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use App\Models\Subscription;
use App\Models\Tattooer;
use App\Models\Pierceur;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = '💰 Statistiques Plateforme';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Données de base qui existent
        $totalTattooers = Tattooer::count();
        $totalPierceurs = Pierceur::count();
        $totalAppointments = Appointment::whereMonth('appointment_date', $currentMonth)
            ->whereYear('appointment_date', $currentYear)
            ->count();

        $activeSubscriptions = Subscription::where('status', 'active')
            ->whereMonth('current_period_start', '<=', $currentMonth)
            ->whereMonth('current_period_end', '>=', $currentMonth)
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total Artistes',
                    'data' => [$totalTattooers, $totalPierceurs],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                    ],
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Activité Mensuelle',
                    'data' => [$totalAppointments, $activeSubscriptions],
                    'backgroundColor' => [
                        'rgba(251, 146, 60, 0.8)',
                        'rgba(147, 51, 234, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(251, 146, 60, 1)',
                        'rgba(147, 51, 234, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Tatoueurs', 'Pierceurs'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
