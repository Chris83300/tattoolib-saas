<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Tattooer;
use App\Models\Piercer;
use Laravel\Cashier\Subscription as CashierSubscription;
use Filament\Widgets\ChartWidget;

class RevenueByArtistType extends ChartWidget
{
    protected ?string $heading = '💰 Revenus par Type d\'Artiste';

    protected function getData(): array
    {
        // Compter les abonnements par type d'artiste
        $tattooerSubscriptions = 0;
        $piercerSubscriptions = 0;

        CashierSubscription::where('stripe_status', 'active')
            ->with('user') // Charger la relation user
            ->get()
            ->each(function($subscription) use (&$tattooerSubscriptions, &$piercerSubscriptions) {
                $user = $subscription->user;
                if ($user && $user->tattooer) {
                    $tattooerSubscriptions++;
                } elseif ($user && $user->piercer) {
                    $piercerSubscriptions++;
                }
            });

        return [
            'datasets' => [
                [
                    'label' => 'Tattooers',
                    'data' => [$tattooerSubscriptions],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                ],
                [
                    'label' => 'Pierceurs',
                    'data' => [$piercerSubscriptions],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                ],
            ],
            'labels' => ['Types d\'artistes'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
