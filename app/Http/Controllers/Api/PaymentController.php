<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Créer un Payment Intent pour l'acompte
     *
     * POST /api/bookings/{bookingRequest}/payment/deposit
     */
    public function createDepositPayment(Request $request, BookingRequest $bookingRequest)
    {
        // 1. Vérifier autorisation (client propriétaire)
        if ($request->user()->client->id !== $bookingRequest->client_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 2. Vérifier statut booking (doit être 'accepted')
        if ($bookingRequest->status !== BookingRequest::STATUS_ACCEPTED) {
            return response()->json([
                'error' => 'Le booking doit être accepté avant paiement'
            ], 400);
        }

        // 3. Vérifier si acompte déjà payé
        if ($bookingRequest->deposit_paid_at) {
            return response()->json([
                'error' => 'Acompte déjà payé'
            ], 400);
        }

        // 4. Récupérer le tatoueur (polymorphic)
        $artist = $bookingRequest->bookable; // Tattooer OU StudioArtist

        // 5. Vérifier Stripe Connect Account
        $stripeAccountId = $this->getStripeAccountId($artist);

        if (!$stripeAccountId) {
            return response()->json([
                'error' => 'Le tatoueur n\'a pas configuré son compte Stripe'
            ], 400);
        }

        // 6. Calculer montant acompte
        $depositAmountCents = $this->calculateDepositAmount($bookingRequest);

        // =============================================
        // DÉTERMINER FLUX STRIPE SELON PLAN
        // =============================================

        $artistPlan = $artist->getCurrentPlan();
        $commissionRate = $artist->getCommissionRate();

        try {
            // Plan FREE → Application Fee (7%)
            if ($artistPlan === \App\Models\Subscription::PLAN_FREE) {
                $commissionAmount = $artist->calculateCommission($depositAmountCents);

                $paymentIntent = PaymentIntent::create([
                    'amount' => $depositAmountCents,
                    'currency' => 'eur',
                    'payment_method_types' => ['card'],
                    'on_behalf_of' => $stripeAccountId,
                    'application_fee_amount' => $commissionAmount, // 7% pour TattooLib
                    'transfer_data' => [
                        'destination' => $stripeAccountId, // 93% pour artiste
                    ],
                    'metadata' => [
                        'booking_request_id' => $bookingRequest->id,
                        'client_id' => $bookingRequest->client_id,
                        'artist_type' => get_class($artist),
                        'artist_id' => $artist->id,
                        'payment_type' => 'deposit',
                        'plan' => $artistPlan,
                        'commission_rate' => $commissionRate,
                        'commission_amount_cents' => $commissionAmount,
                    ],
                ]);
            }
            // Plan PRO/Studio → Direct Charges (0%)
            else {
                $paymentIntent = PaymentIntent::create([
                    'amount' => $depositAmountCents,
                    'currency' => 'eur',
                    'payment_method_types' => ['card'],
                    'on_behalf_of' => $stripeAccountId,
                    'transfer_data' => [
                        'destination' => $stripeAccountId, // 100% pour artiste
                    ],
                    'metadata' => [
                        'booking_request_id' => $bookingRequest->id,
                        'client_id' => $bookingRequest->client_id,
                        'artist_type' => get_class($artist),
                        'artist_id' => $artist->id,
                        'payment_type' => 'deposit',
                        'plan' => $artistPlan,
                        'commission_rate' => 0.00,
                        'commission_amount_cents' => 0,
                    ],
                ]);
            }

            // 8. Créer enregistrement Payment
            $payment = Payment::create([
                'booking_request_id' => $bookingRequest->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'amount' => $depositAmountCents / 100, // Convertir en euros
                'currency' => 'EUR',
                'status' => 'pending',
                'payment_type' => 'deposit',
            ]);

            // 9. Retourner client_secret pour frontend
            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_id' => $payment->id,
                'amount' => $depositAmountCents / 100,
                'currency' => 'EUR',
                'commission' => [
                    'rate' => $commissionRate,
                    'amount' => $artist->calculateCommission($depositAmountCents) / 100,
                ],
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json([
                'error' => 'Erreur Stripe : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer Stripe Account ID (polymorphic)
     */
    private function getStripeAccountId($artist): ?string
    {
        // Tattooer
        if ($artist instanceof \App\Models\Tattooer) {
            return $artist->stripe_connect_account_id;
        }

        // StudioArtist
        if ($artist instanceof \App\Models\StudioArtist) {
            return $artist->stripe_connect_account_id;
        }

        return null;
    }

    /**
     * Calculer montant acompte
     */
    private function calculateDepositAmount(BookingRequest $bookingRequest): int
    {
        // Priorité au prix total confirmé, sinon prix estimé
        $price = $bookingRequest->total_price ?? $bookingRequest->estimated_price;

        if ($price) {
            $percentage = config('services.stripe.default_deposit_percentage');
            return (int) ($price * 100 * $percentage / 100);
        }

        // Sinon → montant fixe par défaut
        return config('services.stripe.default_deposit_amount');
    }
}
