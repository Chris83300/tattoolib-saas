<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Enums\BookingRequestStatus;
use App\Notifications\BalancePaidNotification;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Log;

class BalancePaymentController extends Controller
{
    public function __construct(
        protected \App\Services\StripeService $stripeService,
    ) {}

    /**
     * Page de paiement du solde (client)
     */
    public function show(BookingRequest $bookingRequest)
    {
        // Vérifier propriété (client.id ≠ user.id — comparer via la relation)
        $client = auth()->user()->client;
        abort_unless($client && $bookingRequest->client_id === $client->id, 403);

        // Vérifier que l'acompte est versé
        abort_unless(
            $bookingRequest->status === BookingRequestStatus::COMPLETED,
            403,
            'Le rendez-vous doit être terminé avant de payer le solde.'
        );

        $balanceRemaining = $bookingRequest->balance_remaining;
        abort_if($balanceRemaining <= 0, 403, 'Aucun solde restant.');

        return view('client.balance-payment', [
            'bookingRequest' => $bookingRequest,
            'balanceRemaining' => $balanceRemaining,
        ]);
    }

    /**
     * Créer la session Stripe pour le solde
     */
    public function checkout(BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;
        abort_unless($client && $bookingRequest->client_id === $client->id, 403);
        abort_unless($bookingRequest->status === BookingRequestStatus::COMPLETED, 403);

        $balanceRemaining = $bookingRequest->balance_remaining;
        abort_if($balanceRemaining <= 0, 403);

        Stripe::setApiKey(config('services.stripe.secret'));

        $tattooer = $bookingRequest->bookable;
        $rawAccountId = $tattooer?->getStripeAccountId();

        // Vérifier que l'onboarding Connect est complet avant d'utiliser le compte
        $stripeAccountId = null;
        if ($rawAccountId && $tattooer) {
            $isReady = false;
            if ($tattooer->studio_id && $tattooer->studio?->payment_mode === 'studio_managed') {
                $isReady = (bool) $tattooer->studio->stripe_onboarding_complete;
            } else {
                $isReady = $tattooer->hasCompletedStripeOnboarding();
            }
            $stripeAccountId = $isReady ? $rawAccountId : null;
        }

        if (!$stripeAccountId) {
            return redirect()->route('client.balance.show', $bookingRequest)
                ->with('error', 'Le paiement en ligne n\'est pas disponible : l\'artiste n\'a pas encore configuré son compte Stripe. Contactez-le directement pour convenir d\'un mode de règlement.');
        }

        // Calculer la commission basée sur le plan (STARTER=7%, PRO/STUDIO=0%)
        $amountCents    = (int) round($balanceRemaining * 100);
        $studio         = method_exists($tattooer, 'studio') ? $tattooer->studio : null;
        $applicationFee = $this->stripeService
            ->calculateApplicationFee($amountCents, $tattooer, $studio);

        $sessionParams = [
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $amountCents,
                    'product_data' => [
                        'name' => 'Solde — ' . ($bookingRequest->description ?? 'Prestation'),
                        'description' => sprintf(
                            'Total: %s€ — Acompte déjà réglé: %s€ — Solde restant: %s€',
                            number_format($bookingRequest->total_price, 2, ',', ' '),
                            number_format($bookingRequest->total_deposit_amount, 2, ',', ' '),
                            number_format($balanceRemaining, 2, ',', ' ')
                        ),
                    ],
                ],
                'quantity' => 1,
            ]],
            'payment_intent_data' => [
                'on_behalf_of'           => $stripeAccountId,
                'transfer_data'          => ['destination' => $stripeAccountId],
                'application_fee_amount' => $applicationFee,
                'metadata' => [
                    'booking_request_id' => $bookingRequest->id,
                    'payment_type' => 'balance',
                ],
            ],
            'success_url' => route('client.balance-payment.success', $bookingRequest) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('client.balance.show', $bookingRequest),
            'customer_email' => auth()->user()->email,
            'metadata' => [
                'booking_request_id' => $bookingRequest->id,
                'payment_type' => 'balance',
            ],
        ];

        // TODO: Si Klarna disponible, ajouter 'klarna' aux payment_method_types
        // Vérifier d'abord l'éligibilité du compte Connect

        try {
            $session = StripeSession::create($sessionParams);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Illuminate\Support\Facades\Log::error('Stripe balance session error', [
                'booking_request_id' => $bookingRequest->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('client.balance-payment.show', $bookingRequest)
                ->with('error', 'Une erreur est survenue lors de la création de la session de paiement. Veuillez réessayer.');
        }

        // Sauvegarder l'ID session
        $bookingRequest->update(['balance_stripe_session_id' => $session->id]);

        return redirect($session->url);
    }

    /**
     * Retour après paiement réussi
     */
    public function success(Request $request, BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;
        abort_unless($client && $bookingRequest->client_id === $client->id, 403);

        // Filet de sécurité : si le webhook n'a pas encore traité le paiement,
        // vérifier directement la session Stripe et mettre à jour la DB
        if (!$bookingRequest->balance_paid_at && $request->filled('session_id')) {
            try {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $session = \Stripe\Checkout\Session::retrieve($request->query('session_id'));

                if ($session->payment_status === 'paid'
                    && ($session->metadata['booking_request_id'] ?? null) == $bookingRequest->id
                    && ($session->metadata['payment_type'] ?? null) === 'balance'
                ) {
                    $balanceAmount = $session->amount_total / 100;

                    $bookingRequest->update([
                        'balance_amount'         => $balanceAmount,
                        'balance_paid_at'        => now(),
                        'balance_payment_method' => 'stripe',
                        'status'                 => \App\Enums\BookingRequestStatus::FULLY_COMPLETED,
                    ]);

                    // Créer la transaction de solde
                    \App\Models\BookingTransaction::create([
                        'booking_request_id' => $bookingRequest->id,
                        'user_id'           => $bookingRequest->client->user_id,
                        'type'              => 'final_payment',
                        'amount'            => $balanceAmount,
                        'currency'          => 'eur',
                        'status'            => 'completed',
                        'payment_method'    => 'stripe',
                        'stripe_session_id' => $session->id,
                        'processed_at'      => now(),
                    ]);

                    // Invalider les cache widgets financiers
                    \Illuminate\Support\Facades\Cache::forget('admin.platform.revenue');
                    \Illuminate\Support\Facades\Cache::forget('admin.platform.revenue.detail');

                    Log::info('[Balance] success() fallback — balance_paid_at mis à jour', [
                        'booking_request_id' => $bookingRequest->id,
                        'session_id'         => $session->id,
                        'balance_amount'     => $balanceAmount,
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('[Balance] success() fallback Stripe error', ['error' => $e->getMessage()]);
            }
        }

        return view('client.balance-payment-success', [
            'bookingRequest' => $bookingRequest->fresh(),
        ]);
    }

    /**
     * Tattooer confirme paiement hors plateforme
     */
    public function confirmOffline(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que l'artisan est le propriétaire
        $user = auth()->user();
        $artisan = $user->artisan();
        abort_unless(
            $artisan &&
            $bookingRequest->bookable_type === get_class($artisan) &&
            $bookingRequest->bookable_id === $artisan->id,
            403
        );

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card_direct,transfer,other',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $bookingRequest->update([
            'balance_amount' => $validated['amount'],
            'balance_paid_at' => now(),
            'balance_payment_method' => $validated['payment_method'],
            'status' => BookingRequestStatus::FULLY_COMPLETED,
        ]);

        // Message système dans le chat
        $conversation = $bookingRequest->conversation;
        if ($conversation) {
            $methodLabels = [
                'cash' => 'espèces',
                'card_direct' => 'carte bancaire (direct)',
                'transfer' => 'virement',
                'other' => 'autre',
            ];
            $method = $methodLabels[$validated['payment_method']] ?? $validated['payment_method'];

            $conversation->messages()->create([
                'sender_id' => null,
                'sender_type' => 'system',
                'booking_request_id' => $bookingRequest->id,
                'content' => "Solde de {$validated['amount']} € confirmé (paiement par {$method}). Prestation complète !",
            ]);
        }

        return back()->with('success', 'Paiement du solde confirmé !');
    }
}
