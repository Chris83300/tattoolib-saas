<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TotalTransactionsWidgetFixed extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // Calculer le montant total des transactions
        $totalAmount = Payment::where('status', 'completed')
            ->sum('amount');

        // Calculer le montant du mois en cours
        $currentMonthAmount = Payment::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        // Calculer le nombre de transactions
        $totalTransactions = Payment::where('status', 'completed')->count();

        // Calculer le nombre de transactions ce mois
        $currentMonthTransactions = Payment::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return [
            Stat::make('💰 Montant Total des Transactions', number_format($totalAmount, 2, ',', ' ') . '€')
                ->description('Montant total de toutes les transactions complétées')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('📈 Transactions Ce Mois', number_format($currentMonthAmount, 2, ',', ' ') . '€')
                ->description('Montant des transactions complétées ce mois')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('🔢 Nombre Total de Transactions', $totalTransactions)
                ->description('Nombre total de transactions complétées')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('warning'),

            Stat::make('📊 Transactions Ce Mois', $currentMonthTransactions)
                ->description('Transactions complétées ce mois')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
}
