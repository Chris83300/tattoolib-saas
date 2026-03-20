<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Models\Studio;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class ArtistRevenueChartWidget extends ChartWidget
{
    protected ?string $heading = '💰 Revenus par Type d\'Artiste';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        return Cache::remember('admin.widget.artist_revenue.data', 300, fn () => $this->buildChartData());
    }

    private function buildChartData(): array
    {
        // Revenus des 30 derniers jours par type d'artiste
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $revenueByType = [
            'Tatoueurs' => 0,
            'Piercers' => 0,
            'Studios' => 0,
            'Autres' => 0,
        ];

        // Récupérer les paiements avec toutes les relations nécessaires
        $payments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with(['bookingRequest.user', 'bookingRequest.tattooer', 'bookingRequest.piercer'])
            ->get();

        foreach ($payments as $payment) {
            $artistType = null;

            // Méthode 1: Via bookingRequest.tattooer
            if ($payment->bookingRequest && $payment->bookingRequest->tattooer) {
                $artistType = 'Tatoueurs';
            }
            // Méthode 2: Via bookingRequest.piercer
            elseif ($payment->bookingRequest && $payment->bookingRequest->piercer) {
                $artistType = 'Piercers';
            }
            // Méthode 3: Via l'utilisateur de la bookingRequest
            elseif ($payment->bookingRequest && $payment->bookingRequest->user) {
                $user = $payment->bookingRequest->user;
                if ($user->tattooer) {
                    $artistType = 'Tatoueurs';
                } elseif ($user->piercer) {
                    $artistType = 'Piercers';
                } elseif ($user->studio) {
                    $artistType = 'Studios';
                } elseif ($user->role === 'studio') {
                    $artistType = 'Studios';
                }
            }
            // Méthode 4: Via recipient_name et recipient_type
            else {
                $recipientName = strtolower($payment->recipient_name ?? '');
                $recipientType = strtolower($payment->recipient_type ?? '');

                if (str_contains($recipientName, 'studio') || str_contains($recipientType, 'studio')) {
                    $artistType = 'Studios';
                } elseif (str_contains($recipientName, 'piercer') || str_contains($recipientType, 'piercer')) {
                    $artistType = 'Piercers';
                } elseif (str_contains($recipientName, 'tattoo') || str_contains($recipientType, 'tattooer')) {
                    $artistType = 'Tatoueurs';
                } else {
                    $artistType = 'Autres';
                }
            }

            if ($artistType && isset($revenueByType[$artistType])) {
                $revenueByType[$artistType] += $payment->amount;
            }
        }

        // Arrondir les montants réels à 2 décimales
        $revenueByType = array_map(fn ($v) => round($v, 2), $revenueByType);

        return [
            'datasets' => [
                [
                    'label' => 'Revenus (€)',
                    'data' => array_values($revenueByType),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',  // Bleu pour Tatoueurs
                        'rgba(168, 85, 247, 0.8)',  // Violet pour Piercers
                        'rgba(251, 146, 60, 0.8)',  // Orange pour Studios
                        'rgba(107, 114, 128, 0.8)', // Gris pour Autres
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(168, 85, 247, 1)',
                        'rgba(251, 146, 60, 1)',
                        'rgba(107, 114, 128, 1)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => array_keys($revenueByType),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getFooter(): ?string
    {
        return Cache::remember('admin.widget.artist_revenue.footer', 300, function () {
        $totalRevenue = Payment::whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 'completed')
            ->sum('amount');

        $totalTransactions = Payment::whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 'completed')
            ->count();

            return "Total 30 jours: " . number_format($totalRevenue, 2) . "€ | Transactions: " . $totalTransactions;
        });
    }
}
