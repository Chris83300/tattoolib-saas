<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use App\Models\Tattooer;
use App\Models\Piercer;
use Laravel\Cashier\Subscription as CashierSubscription;
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
        $totalPierceurs = Piercer::count();
        $totalAppointments = Appointment::whereMonth('start_datetime', $currentMonth)
            ->whereYear('start_datetime', $currentYear)
            ->count();

        $activeSubscriptions = CashierSubscription::where('stripe_status', 'active')
            ->whereMonth('created_at', '<=', $currentMonth)
            ->whereMonth('created_at', '>=', $currentMonth)
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
