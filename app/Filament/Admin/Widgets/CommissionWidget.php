<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Laravel\Cashier\Subscription as CashierSubscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CommissionWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Période actuelle (30 derniers jours)
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        // Calcul des commissions sur les transactions (7% sur plans starter)
        $commissionRevenue = 0;
        $totalTransactions = 0;
        $starterTransactions = 0;

        // Récupérer les paiements avec les bookingRequest pour déterminer si c'est un plan starter
        $payments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with(['bookingRequest.user'])
            ->get();

        foreach ($payments as $payment) {
            $totalTransactions++;
            $isStarterCommission = false;

            // Vérifier si l'utilisateur est sur un plan starter
            if ($payment->bookingRequest && $payment->bookingRequest->user) {
                $user = $payment->bookingRequest->user;
                
                // Vérifier l'abonnement actif de l'utilisateur
                $activeSubscription = CashierSubscription::where('user_id', $user->id)
                    ->where('stripe_status', 'active')
                    ->first();

                if ($activeSubscription) {
                    $price = $activeSubscription->stripe_price ?? '';
                    
                    // Vérifier si c'est un plan starter
                    if (str_contains($price, '1T7E4D') || str_contains($price, 'starter')) {
                        $isStarterCommission = true;
                        $starterTransactions++;
                    }
                }
            }

            // Appliquer 7% de commission si c'est un plan starter
            if ($isStarterCommission) {
                $commissionRevenue += $payment->amount * 0.07;
            }
        }

        // Période précédente pour comparaison
        $previousStartDate = Carbon::now()->subDays(60);
        $previousEndDate = Carbon::now()->subDays(30);

        $previousCommission = 0;
        $previousPayments = Payment::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', 'completed')
            ->with(['bookingRequest.user'])
            ->get();

        foreach ($previousPayments as $payment) {
            $isStarterCommission = false;

            if ($payment->bookingRequest && $payment->bookingRequest->user) {
                $user = $payment->bookingRequest->user;
                
                $activeSubscription = CashierSubscription::where('user_id', $user->id)
                    ->where('stripe_status', 'active')
                    ->first();

                if ($activeSubscription) {
                    $price = $activeSubscription->stripe_price ?? '';
                    
                    if (str_contains($price, '1T7E4D') || str_contains($price, 'starter')) {
                        $isStarterCommission = true;
                    }
                }
            }

            if ($isStarterCommission) {
                $previousCommission += $payment->amount * 0.07;
            }
        }

        // Calcul de la croissance
        $commissionGrowth = $previousCommission > 0 ? (($commissionRevenue - $previousCommission) / $previousCommission) * 100 : 0;

        // Calcul du taux moyen de commission
        $averageCommissionRate = $totalTransactions > 0 ? ($commissionRevenue / $payments->sum('amount')) * 100 : 0;

        return [
            Stat::make('💰 Commissions générées (30 jours)', number_format($commissionRevenue, 2) . '€')
                ->description($commissionGrowth >= 0 ? "+{$commissionGrowth}%" : "{$commissionGrowth}%")
                ->descriptionIcon($commissionGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($commissionGrowth >= 0 ? 'success' : 'danger'),

            Stat::make('📊 Transactions Starter', $starterTransactions)
                ->description('sur ' . $totalTransactions . ' totales')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('warning'),

            Stat::make('📈 Taux moyen de commission', number_format($averageCommissionRate, 1) . '%')
                ->description('Sur le CA total')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),

            Stat::make('💵 Commission par transaction', $starterTransactions > 0 ? number_format($commissionRevenue / $starterTransactions, 2) . '€' : '0.00€')
                ->description('Moyenne Starter')
                ->descriptionIcon('heroicon-m-banknote')
                ->color('primary'),
        ];
    }
}
