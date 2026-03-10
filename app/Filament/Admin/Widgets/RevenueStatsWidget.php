<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use App\Models\BookingRequest;
use App\Models\User;
use App\Models\Complaint;
use Laravel\Cashier\Subscription as CashierSubscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RevenueStatsWidget extends BaseWidget
{
    protected static ?int $sort = 0;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Période actuelle (30 derniers jours)
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        // Revenus des paiements (brut)
        $currentRevenue = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');

        // Calcul des commissions sur les transactions (7% sur plans starter)
        $commissionAmount = 0;
        $payments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with(['bookingRequest.user'])
            ->get();

        foreach ($payments as $payment) {
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
                $commissionAmount += $payment->amount * 0.07;
            }
        }

        // Revenus des abonnements
        $subscriptionRevenue = CashierSubscription::where('stripe_status', 'active')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->sum(function($subscription) {
                $priceStarter = env('STRIPE_PRICE_STARTER', 9.99);
                $pricePro = env('STRIPE_PRICE_PRO', 29.99);
                $priceStudio = env('STRIPE_PRICE_STUDIO', 59.99);
                $priceStudioExtra = env('STRIPE_PRICE_STUDIO_EXTRA', 24.99);

                $price = $subscription->stripe_price ?? '';

                if (str_contains($price, '1T7E4D') || str_contains($price, 'starter')) {
                    return $priceStarter;
                } elseif (str_contains($price, '1T8zRR') || str_contains($price, 'pro')) {
                    return $pricePro;
                } elseif (str_contains($price, '1T8zPp') || str_contains($price, 'studio')) {
                    $user = $subscription->user;
                    if ($user && $user->role === 'studio' && $user->studio_id) {
                        $artistCount = DB::table('tattooers')
                            ->where('studio_id', $user->studio_id)
                            ->count();
                        return $priceStudio + ($artistCount * $priceStudioExtra);
                    }
                    return $priceStudio;
                }

                return 0;
            });

        $totalGrossRevenue = $currentRevenue + $subscriptionRevenue;
        $totalNetRevenue = $totalGrossRevenue - $commissionAmount;

        // Période précédente pour comparaison
        $previousStartDate = Carbon::now()->subDays(60);
        $previousEndDate = Carbon::now()->subDays(30);

        $previousRevenue = Payment::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', 'completed')
            ->sum('amount');

        // Calcul de la croissance (basé sur le net)
        $revenueGrowth = $previousRevenue > 0 ? (($totalNetRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

        // Autres statistiques
        $activeSubscriptions = CashierSubscription::where('stripe_status', 'active')->count();
        $successfulPayments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->count();

        $averageTransaction = $successfulPayments > 0 ? $totalGrossRevenue / $successfulPayments : 0;

        return [
            Stat::make('💰 CA Total (Brut)', number_format($totalGrossRevenue, 2) . '€')
                ->description('Revenus avant commission')
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('primary'),

            Stat::make('� Commission (7%)', number_format($commissionAmount, 2) . '€')
                ->description('Prélevée sur Starter')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('warning'),

            Stat::make('� CA Net', number_format($totalNetRevenue, 2) . '€')
                ->description($revenueGrowth >= 0 ? "+{$revenueGrowth}%" : "{$revenueGrowth}%")
                ->descriptionIcon($revenueGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueGrowth >= 0 ? 'success' : 'danger'),

            Stat::make('� Abonnements Actifs', $activeSubscriptions)
                ->description('Revenus récurrents')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('info'),
        ];
    }
}
