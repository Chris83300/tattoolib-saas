<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Laravel\Cashier\Subscription as CashierSubscription;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class MonthlyRevenueChartWidget extends ChartWidget
{
    protected ?string $heading = '📈 Graphique des Revenus Mensuels';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        return Cache::remember('admin.widget.monthly_revenue.data', 300, function () {
            return $this->buildChartData();
        });
    }

    private function buildChartData(): array
    {
        // Données des 12 derniers mois
        $months = collect(range(11, 0))->map(function($monthsAgo) {
            return Carbon::now()->subMonths($monthsAgo)->format('Y-m');
        });

        $monthlyRevenue = $months->mapWithKeys(function($month) {
            $startDate = Carbon::parse($month)->startOfMonth();
            $endDate = Carbon::parse($month)->endOfMonth();
            $date = Carbon::parse($month);

            // Revenus des paiements (brut)
            $paymentRevenue = Payment::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('amount');

            // Calcul des commissions sur les paiements de ce mois
            $commissionAmount = 0;
            $payments = Payment::whereDate('created_at', $date)
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

            $totalGrossRevenue = $paymentRevenue + $subscriptionRevenue;
            $totalNetRevenue = $totalGrossRevenue - $commissionAmount;

            return [$month => [
                'gross' => $totalGrossRevenue,
                'net' => $totalNetRevenue,
                'commission' => $commissionAmount,
            ]];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Revenus Bruts (€)',
                    'data' => $monthlyRevenue->pluck('gross')->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Revenus Nets (€)',
                    'data' => $monthlyRevenue->pluck('net')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $months->map(function($month) {
                return Carbon::parse($month)->format('M Y');
            })->toArray(),
        ];
    } // end buildChartData

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFooter(): ?string
    {
        return Cache::remember('admin.widget.monthly_revenue.footer', 300, function () {
            $totalGrossRevenue = Payment::whereDate('created_at', '>=', Carbon::now()->subMonths(12))
                ->where('status', 'completed')
                ->sum('amount');

            $totalCommission = 0;
            $payments = Payment::whereDate('created_at', '>=', Carbon::now()->subMonths(12))
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
                    $totalCommission += $payment->amount * 0.07;
                }
            }

            $totalNetRevenue = $totalGrossRevenue - $totalCommission;
            $averageMonthly = $totalNetRevenue / 12;

            return "Brut 12mo: " . number_format($totalGrossRevenue, 2) . "€ | Commission: " . number_format($totalCommission, 2) . "€ | Net mensuel moyen: " . number_format($averageMonthly, 2) . "€";
        });
    }
}
