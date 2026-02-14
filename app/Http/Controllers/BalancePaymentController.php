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
    /**
     * Page de paiement du solde (client)
     */
    public function show(BookingRequest $bookingRequest)
    {
        // Vérifier propriété
        abort_unless($bookingRequest->client_id === auth()->id(), 403);

        // Vérifier que le RDV est terminé
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
        abort_unless($bookingRequest->client_id === auth()->id(), 403);
        abort_unless($bookingRequest->status === BookingRequestStatus::COMPLETED, 403);

        $balanceRemaining = $bookingRequest->balance_remaining;
        abort_if($balanceRemaining <= 0, 403);

        Stripe::setApiKey(config('services.stripe.secret'));

        $tattooer = $bookingRequest->bookable;
        $stripeAccountId = $tattooer?->user?->stripe_account_id;
        abort_unless($stripeAccountId, 500, 'Compte Stripe artiste non configuré.');

        // Calculer la commission (même logique que l'acompte)
        $isPro = $tattooer?->user?->subscribed('pro') ?? false;
        $applicationFee = $isPro ? 0 : (int) round($balanceRemaining * 100 * 0.07); // 7% FREE

        $sessionParams = [
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => (int) round($balanceRemaining * 100),
                    'product_data' => [
                        'name' => 'Solde tattoo - ' . ($bookingRequest->description ?? 'Prestation'),
                        'description' => 'Paiement du solde restant',
                    ],
                ],
                'quantity' => 1,
            ]],
            'payment_intent_data' => [
                'application_fee_amount' => $applicationFee,
                'transfer_data' => [
                    'destination' => $stripeAccountId,
                ],
                'metadata' => [
                    'booking_request_id' => $bookingRequest->id,
                    'payment_type' => 'balance',
                ],
            ],
            'success_url' => route('client.balance-payment.success', $bookingRequest) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('client.balance-payment.show', $bookingRequest),
            'customer_email' => auth()->user()->email,
            'metadata' => [
                'booking_request_id' => $bookingRequest->id,
                'payment_type' => 'balance',
            ],
        ];

        // TODO: Si Klarna disponible, ajouter 'klarna' aux payment_method_types
        // Vérifier d'abord l'éligibilité du compte Connect

        $session = StripeSession::create($sessionParams);

        // Sauvegarder l'ID session
        $bookingRequest->update(['balance_stripe_session_id' => $session->id]);

        return redirect($session->url);
    }

    /**
     * Retour après paiement réussi
     */
    public function success(Request $request, BookingRequest $bookingRequest)
    {
        abort_unless($bookingRequest->client_id === auth()->id(), 403);

        // Le webhook Stripe gère la vraie confirmation
        // Ici on affiche juste une page de confirmation optimiste

        return view('client.balance-payment-success', [
            'bookingRequest' => $bookingRequest,
        ]);
    }

    /**
     * Tattooer confirme paiement hors plateforme
     */
    public function confirmOffline(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que le tattooer est le propriétaire
        $user = auth()->user();
        abort_unless(
            $bookingRequest->bookable_type === get_class($user->tattooer) &&
            $bookingRequest->bookable_id === $user->tattooer?->id,
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
                'body' => "💰 Solde de {$validated['amount']}€ confirmé (paiement par {$method}). Prestation complète !",
                'is_system' => true,
                'metadata' => json_encode(['type' => 'balance_paid_offline']),
            ]);
        }

        return back()->with('success', 'Paiement du solde confirmé !');
    }
}
