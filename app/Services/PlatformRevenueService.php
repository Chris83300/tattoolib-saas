<?php

namespace App\Services;

use App\Models\BookingRequest;
use App\Models\BookingTransaction;
use Laravel\Cashier\Subscription;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlatformRevenueService
{
    /**
     * Identifiants des plans Stripe depuis la config.
     */
    protected function priceIds(): array
    {
        return [
            'starter' => env('STRIPE_PRICE_ID_STARTER'),
            'pro'     => env('STRIPE_PRICE_ID_PRO'),
            'studio'  => env('STRIPE_PRICE_ID_STUDIO'),
            'studio_extra' => env('STRIPE_PRICE_ID_STUDIO_EXTRA'),
        ];
    }

    // ══════════════════════════════════════════════
    // TRANSACTIONS (volume qui transite sur la plateforme)
    // ══════════════════════════════════════════════

    /**
     * Volume total des transactions (paiements clients → artistes).
     * C'est indicatif — pas le CA de la plateforme.
     */
    public function getTransactionVolume(?Carbon $from = null, ?Carbon $to = null): array
    {
        $query = BookingTransaction::where('status', 'completed');

        if ($from) $query->where('created_at', '>=', $from);
        if ($to) $query->where('created_at', '<=', $to);

        $total = (clone $query)->sum('amount');
        $count = (clone $query)->count();
        $avg = $count > 0 ? round((float) $total / $count, 2) : 0;

        return [
            'volume_total' => round((float) $total, 2),
            'transaction_count' => $count,
            'average_transaction' => $avg,
        ];
    }

    // ══════════════════════════════════════════════
    // COMMISSIONS 7% (revenu plateforme)
    // ══════════════════════════════════════════════

    /**
     * Total des commissions prélevées via calcul 7% sur les STARTER.
     */
    public function getCommissions(?Carbon $from = null, ?Carbon $to = null): array
    {
        $query = BookingTransaction::where('status', 'completed')
            ->whereHas('bookingRequest.bookable', function ($q) {
                $q->where('current_plan', 'starter');
            });

        if ($from) $query->where('created_at', '>=', $from);
        if ($to) $query->where('created_at', '<=', $to);

        $totalTransactions = (clone $query)->sum('amount');
        $total = round((float) $totalTransactions * 0.07, 2);
        $count = (clone $query)->count();
        $avg = $count > 0 ? round((float) $total / $count, 2) : 0;

        return [
            'total' => round((float) $total, 2),
            'count' => $count,
            'avg' => $avg,
        ];
    }

    /**
     * Commissions ce mois vs mois dernier.
     */
    public function getCommissionsTrend(): array
    {
        $thisMonth = $this->getCommissions(now()->startOfMonth(), now());
        $lastMonth = $this->getCommissions(
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        );

        $changePct = $lastMonth['total'] > 0
            ? round(($thisMonth['total'] - $lastMonth['total']) / $lastMonth['total'] * 100, 1)
            : ($thisMonth['total'] > 0 ? 100 : 0);

        return [
            'this_month' => $thisMonth['total'],
            'last_month' => $lastMonth['total'],
            'change_pct' => $changePct,
        ];
    }

    // ══════════════════════════════════════════════
    // ABONNEMENTS (revenu plateforme)
    // ══════════════════════════════════════════════

    /**
     * Détail des abonnements actifs par plan.
     */
    public function getSubscriptionStats(): array
    {
        $prices = $this->priceIds();

        $activeSubs = Subscription::where('stripe_status', 'active')
            ->with('items')
            ->get();

        $starterCount = 0;
        $proCount = 0;
        $studioCount = 0;
        $studioExtraCount = 0;

        foreach ($activeSubs as $sub) {
            foreach ($sub->items as $item) {
                if ($item->stripe_price === $prices['starter']) {
                    $starterCount += $item->quantity;
                } elseif ($item->stripe_price === $prices['pro']) {
                    $proCount += $item->quantity;
                } elseif ($item->stripe_price === $prices['studio']) {
                    $studioCount += $item->quantity;
                } elseif ($item->stripe_price === $prices['studio_extra']) {
                    $studioExtraCount += $item->quantity;
                }
            }
        }

        // MRR (Monthly Recurring Revenue)
        $mrr = ($starterCount * 9.99)
             + ($proCount * 29.99)
             + ($studioCount * 59.99)
             + ($studioExtraCount * 24.99);

        return [
            'starter_count' => $starterCount,
            'pro_count' => $proCount,
            'studio_count' => $studioCount,
            'studio_extra_count' => $studioExtraCount,
            'total_active' => $starterCount + $proCount + $studioCount,
            'mrr' => round($mrr, 2),
            'arr' => round($mrr * 12, 2),  // Annual Recurring Revenue
        ];
    }

    // ══════════════════════════════════════════════
    // CA TOTAL PLATEFORME
    // ══════════════════════════════════════════════

    /**
     * CA total plateforme = commissions + abonnements.
     */
    public function getPlatformRevenue(): array
    {
        $commissions = $this->getCommissions();
        $commissionsTrend = $this->getCommissionsTrend();
        $subs = $this->getSubscriptionStats();
        $transactions = $this->getTransactionVolume();

        // CA total plateforme = commissions + MRR abonnements
        $totalPlatformRevenue = $commissions['total'] + $subs['mrr'];

        return [
            'transactions' => $transactions,
            'commissions' => $commissions,
            'commissions_trend' => $commissionsTrend,
            'subscriptions' => $subs,
            'platform_total' => round($totalPlatformRevenue, 2),
            'mrr' => $subs['mrr'],
        ];
    }
}
