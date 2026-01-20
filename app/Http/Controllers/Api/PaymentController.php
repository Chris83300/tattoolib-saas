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
        $depositAmount = $this->calculateDepositAmount($bookingRequest);

        try {
            // 7. Créer Payment Intent DIRECT au tatoueur
            $paymentIntent = PaymentIntent::create([
                'amount' => $depositAmount, // En centimes
                'currency' => 'eur',
                'payment_method_types' => ['card'],

                // CRITIQUE : Paiement DIRECT au tatoueur
                'on_behalf_of' => $stripeAccountId,
                'transfer_data' => [
                    'destination' => $stripeAccountId,
                ],

                // Metadata pour webhook
                'metadata' => [
                    'booking_request_id' => $bookingRequest->id,
                    'client_id' => $bookingRequest->client_id,
                    'artist_type' => get_class($artist),
                    'artist_id' => $artist->id,
                    'payment_type' => 'deposit',
                ],
            ]);

            // 8. Créer enregistrement Payment
            $payment = Payment::create([
                'booking_request_id' => $bookingRequest->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'amount' => $depositAmount / 100, // Convertir en euros
                'currency' => 'EUR',
                'status' => 'pending',
                'payment_type' => 'deposit',
            ]);

            // 9. Retourner client_secret pour frontend
            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_id' => $payment->id,
                'amount' => $depositAmount / 100,
                'currency' => 'EUR',
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
        // Si prix total défini → pourcentage
        if ($bookingRequest->estimated_price) {
            $percentage = config('services.stripe.default_deposit_percentage');
            return (int) ($bookingRequest->estimated_price * 100 * $percentage / 100);
        }

        // Sinon → montant fixe par défaut
        return config('services.stripe.default_deposit_amount');
    }
}
