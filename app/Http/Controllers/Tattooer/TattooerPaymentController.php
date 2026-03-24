<?php

namespace App\Http\Controllers\Tattooer;

use App\Models\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TattooerPaymentController extends ArtisanBaseController
{
    /**
     * Paiements du tattooer
     */
    public function payments()
    {
        $tattooer = $this->artisan();

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Récupérer les paiements : demandes avec acompte payé OU solde payé
        $payments = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', get_class($tattooer))
            ->where(function($query) {
                $query->whereNotNull('deposit_paid_at')
                      ->orWhereNotNull('balance_paid_at');
            })
            ->with(['client', 'bookingTransactions'])
            ->orderByRaw('COALESCE(balance_paid_at, deposit_paid_at) DESC')
            ->paginate(10);

        // Statistiques depuis les vraies transactions comptables
        $transactionsQuery = \App\Models\BookingTransaction::whereHas('bookingRequest', function ($q) use ($tattooer) {
            $q->where('bookable_id', $tattooer->id)
              ->where('bookable_type', get_class($tattooer));
        })->where('status', 'completed');

        $totalEarned = (clone $transactionsQuery)->sum('amount');
        $thisMonthEarned = (clone $transactionsQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $paymentStats = [
            'total_earned'     => $totalEarned,
            'this_month'       => $thisMonthEarned,
            'total_commissions'=> $totalEarned * 0.07, // 7% de commission
            'pending_deposits' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', get_class($tattooer))
                ->whereIn('status', ['accepted', 'deposit_requested', 'awaiting_deposit'])
                ->sum('total_deposit_amount'),
        ];

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        $stats = [
            'total_revenue' => $paymentStats['total_earned'],
            'total_payments' => $payments->count(),
            'this_month' => $paymentStats['this_month'],
        ];

        return view('tattooer.payments', compact('tattooer', 'payments', 'paymentStats', 'stats', 'pendingCount', 'unreadCount'));
    }

    /**
     * Connecter Stripe Connect — initie ou reprend l'onboarding Stripe.
     */
    public function connectStripe(Request $request)
    {
        $tattooer = $this->artisan();

        if (!$tattooer) {
            return redirect()->back()->with('error', 'Profil artiste introuvable.');
        }

        // Studio centralisé : l'artiste n'a pas besoin de son propre Connect
        if (!$tattooer->needsOwnStripeConnect()) {
            return redirect()->route($this->routePrefix() . '.payments')
                ->with('info', 'Les paiements sont gérés par votre studio.');
        }

        try {
            $connectLink = $tattooer->generateStripeConnectLink();
            return redirect($connectLink);
        } catch (\Exception $e) {
            Log::error('Erreur génération lien Stripe Connect', [
                'tattooer_id' => $tattooer->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Impossible de générer le lien Stripe Connect. Veuillez réessayer.');
        }
    }
}
