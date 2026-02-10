<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\BookingTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;

class DepositController extends Controller
{
    /**
     * Afficher la page de paiement de l'acompte
     */
    public function payment(BookingRequest $bookingRequest)
    {
        Log::info('Deposit payment attempt', [
            'booking_request_id' => $bookingRequest->id,
            'user_id' => auth()->id(),
            'client_id' => auth()->user()->client->id ?? null,
            'booking_client_id' => $bookingRequest->client_id
        ]);

        $client = auth()->user()->client;

        // Vérifier que la demande appartient au client
        if ($bookingRequest->client_id !== $client->id) {
            Log::warning('Deposit payment authorization failed', [
                'client_id' => $client->id,
                'booking_client_id' => $bookingRequest->client_id
            ]);
            abort(403, 'Cette demande ne vous appartient pas.');
        }

        // Vérifier le statut
        if (!in_array($bookingRequest->status->value, ['accepted', 'awaiting_deposit'])) {
            return redirect()->route('client.booking-requests')
                ->with('error', 'Cette demande ne nécessite pas de paiement d\'acompte.');
        }

        // Vérifier si déjà payé
        if ($bookingRequest->deposit_paid_at) {
            return redirect()->route('client.booking-request.show', $bookingRequest)
                ->with('info', 'L\'acompte a déjà été payé.');
        }

        // Vérifier délai
        if ($bookingRequest->client_payment_deadline &&
            $bookingRequest->client_payment_deadline->isPast()) {
            return redirect()->route('client.booking-requests')
                ->with('error', 'Le délai de paiement est expiré.');
        }

        // Préparer la clé Stripe pour la vue
        $stripeKey = config('services.stripe.key');
        Log::info('Stripe key loaded', [
            'stripe_key' => $stripeKey ? 'SET' : 'NOT_SET',
            'stripe_key_value' => substr($stripeKey, 0, 20) . '...'
        ]);

        return view('client.deposit-payment', compact('bookingRequest', 'stripeKey'));
    }

    /**
     * Créer la session de paiement Stripe
     */
    public function process(BookingRequest $bookingRequest)
    {
        Log::info('Deposit process attempt', [
            'booking_request_id' => $bookingRequest->id,
            'user_id' => auth()->id(),
            'client_id' => auth()->user()->client->id ?? null,
            'booking_client_id' => $bookingRequest->client_id,
            'booking_status' => $bookingRequest->status->value,
            'deposit_paid_at' => $bookingRequest->deposit_paid_at,
            'total_deposit_amount' => $bookingRequest->total_deposit_amount,
        ]);

        $client = auth()->user()->client;

        // Vérifications (même code que payment())
        if ($bookingRequest->client_id !== $client->id) {
            Log::warning('Deposit process: Authorization failed', [
                'client_id' => $client->id,
                'booking_client_id' => $bookingRequest->client_id
            ]);
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        Log::info('Deposit process: Authorization passed');

        if (!in_array($bookingRequest->status->value, ['accepted', 'awaiting_deposit'])) {
            Log::warning('Deposit process: Invalid status', [
                'status' => $bookingRequest->status->value,
                'accepted_statuses' => ['accepted', 'awaiting_deposit']
            ]);
            return response()->json(['error' => 'Statut invalide'], 400);
        }

        Log::info('Deposit process: Status validation passed');

        if ($bookingRequest->deposit_paid_at) {
            Log::warning('Deposit process: Already paid', [
                'deposit_paid_at' => $bookingRequest->deposit_paid_at
            ]);
            return response()->json(['error' => 'Déjà payé'], 400);
        }

        Log::info('Deposit process: Deposit check passed');

        if ($bookingRequest->client_payment_deadline &&
            $bookingRequest->client_payment_deadline->isPast()) {
            Log::warning('Deposit process: Deadline expired', [
                'deadline' => $bookingRequest->client_payment_deadline,
                'now' => now()
            ]);
            return response()->json(['error' => 'Délai expiré'], 400);
        }

        Log::info('Deposit process: All validations passed, creating Stripe session');

        // Configuration Stripe
        Log::info('Stripe configuration', [
            'secret_key' => env('STRIPE_SECRET') ? 'SET' : 'NOT_SET',
            'publishable_key' => env('STRIPE_KEY') ? 'SET' : 'NOT_SET'
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $bookingRequest->total_deposit_amount * 100, // Convert to cents
                        'product_data' => [
                            'name' => 'Acompte - Réservation tattoo',
                            'description' => 'Acompte pour réservation avec ' . $bookingRequest->bookable->user->name,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('deposit.success', $bookingRequest) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('deposit.cancel', $bookingRequest),
                'metadata' => [
                    'booking_request_id' => $bookingRequest->id,
                    'client_id' => $client->id,
                ],
            ]);

            Log::info('Stripe session created successfully', [
                'session_id' => $session->id,
                'booking_request_id' => $bookingRequest->id
            ]);

            return response()->json(['sessionId' => $session->id]);

        } catch (\Exception $e) {
            Log::error('Stripe session creation failed', [
                'error' => $e->getMessage(),
                'booking_request_id' => $bookingRequest->id
            ]);

            return response()->json(['error' => 'Erreur de paiement'], 500);
        }
    }

    /**
     * Page de succès
     */
    public function success(BookingRequest $bookingRequest, Request $request)
    {
        Log::info('Deposit success method called', [
            'booking_request_id' => $bookingRequest->id,
            'session_id' => $request->get('session_id'),
            'deposit_paid_at' => $bookingRequest->deposit_paid_at,
            'current_status' => $bookingRequest->status->value,
        ]);

        // Vérifier si c'est un retour de paiement réussi
        $sessionId = $request->get('session_id');

        if ($sessionId && !$bookingRequest->deposit_paid_at) {
            try {
                // Configuration Stripe pour vérifier la session
                Stripe::setApiKey(env('STRIPE_SECRET'));

                $session = Session::retrieve($sessionId);

                Log::info('Stripe session retrieved', [
                    'session_id' => $sessionId,
                    'payment_status' => $session->payment_status,
                    'payment_intent' => $session->payment_intent,
                ]);

                if ($session->payment_status === 'succeeded' || $session->payment_status === 'paid') {
                    Log::info('Payment status succeeded, starting DB transaction');

                    // Transaction DB atomique
                    DB::transaction(function () use ($bookingRequest, $session) {
                        Log::info('DB transaction started', [
                            'booking_request_id' => $bookingRequest->id,
                            'old_status' => $bookingRequest->status->value,
                        ]);

                        // 1. Marquer le paiement
                        $bookingRequest->update([
                            'status' => \App\Enums\BookingRequestStatus::DEPOSIT_PAID,
                            'deposit_paid_at' => now(),
                        ]);

                        Log::info('Booking request updated', [
                            'new_status' => $bookingRequest->fresh()->status->value,
                            'deposit_paid_at' => $bookingRequest->fresh()->deposit_paid_at,
                        ]);

                        // 2. Créer la transaction de booking
                        $transaction = BookingTransaction::createDeposit(
                            $bookingRequest,
                            $session->id,
                            $session->payment_intent
                        );

                        // 3. Créer l'appointment SI datetime défini
                        if ($bookingRequest->appointment_datetime) {
                            $bookingRequest->createAppointment();
                        }

                        // 4. Synchroniser les deadlines de la conversation
                        if ($conversation = $bookingRequest->conversation) {
                            $conversation->update([
                                'status' => \App\Enums\ConversationStatus::ACTIVE,
                                'deposit_deadline_at' => null, // plus besoin, déjà payé
                                'expiry_type' => 'permanent', // plus d'expiration d'acompte
                                'expires_at' => $bookingRequest->appointment_datetime
                                    ? $bookingRequest->appointment_datetime->addDays(1)
                                    : now()->addMonths(6),
                            ]);
                        }

                        // 5. Récupérer et stocker l'URL du reçu Stripe
                        try {
                            $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
                            $charge = $paymentIntent->latest_charge;
                            if ($charge) {
                                $chargeObj = \Stripe\Charge::retrieve($charge);
                                $receiptUrl = $chargeObj->receipt_url;

                                if ($receiptUrl) {
                                    $transaction->update([
                                        'metadata' => array_merge($transaction->metadata ?? [], [
                                            'receipt_url' => $receiptUrl,
                                        ]),
                                    ]);
                                }
                            }
                        } catch (\Exception $e) {
                            Log::warning('Could not retrieve Stripe receipt', [
                                'error' => $e->getMessage(),
                                'session_id' => $session->id
                            ]);
                        }

                        Log::info('Deposit payment completed successfully', [
                            'booking_request_id' => $bookingRequest->id,
                            'session_id' => $session->id,
                            'transaction_id' => $transaction->id,
                            'appointment_created' => $bookingRequest->appointment_datetime ? true : false,
                        ]);
                    });
                }
            } catch (\Exception $e) {
                Log::error('Error verifying Stripe session', [
                    'error' => $e->getMessage(),
                    'session_id' => $sessionId ?? 'unknown'
                ]);
            }
        }

        return view('client.deposit-success', compact('bookingRequest'));
    }

    /**
     * Page d'annulation
     */
    public function cancel(BookingRequest $bookingRequest)
    {
        return view('client.deposit-cancel', compact('bookingRequest'));
    }
}
