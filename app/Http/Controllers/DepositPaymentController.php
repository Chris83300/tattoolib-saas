<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class DepositPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('success');
    }

    /**
     * Afficher la page de paiement de l'acompte
     */
    public function show(BookingRequest $bookingRequest)
    {
        // Vérifier que l'utilisateur est le client du projet
        if ($bookingRequest->client->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        // Vérifier que le projet est en attente d'acompte
        if ($bookingRequest->status !== BookingRequest::STATUS_ACCEPTED || !$bookingRequest->deposit_amount) {
            return redirect()->route('client.booking-requests.show', $bookingRequest->id)
                ->with('error', 'Ce projet n\'attend pas d\'acompte.');
        }

        // Vérifier que l'acompte n'a pas déjà été payé
        if ($bookingRequest->isDepositPaid()) {
            return redirect()->route('client.booking-requests.show', $bookingRequest->id)
                ->with('info', 'L\'acompte a déjà été payé.');
        }

        return view('deposit.payment', compact('bookingRequest'));
    }

    /**
     * Créer la session de paiement Stripe
     */
    public function createCheckoutSession(BookingRequest $bookingRequest)
    {
        // Vérifications
        if ($bookingRequest->client->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        if ($bookingRequest->isDepositPaid()) {
            return response()->json(['error' => 'Acompte déjà payé'], 400);
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => "Acompte - {$bookingRequest->bookable->user->name}",
                            'description' => "Projet: {$bookingRequest->tattoo_description} ({$bookingRequest->tattoo_location})",
                            'images' => $bookingRequest->getMedia('reference_images')->map(fn($media) => $media->getUrl())->toArray(),
                        ],
                        'unit_amount' => $bookingRequest->deposit_amount * 100, // Convertir en centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('deposit.success', $bookingRequest->id) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('deposit.cancel', $bookingRequest->id),
                'metadata' => [
                    'booking_request_id' => $bookingRequest->id,
                    'client_id' => $bookingRequest->client->id,
                    'bookable_id' => $bookingRequest->bookable_id,
                    'bookable_type' => $bookingRequest->bookable_type,
                    'type' => 'deposit',
                ],
                'customer_email' => Auth::user()->email,
                'expires_at' => now()->addHours(48)->timestamp, // Expiration dans 48h
                'payment_intent_data' => [
                    'metadata' => [
                        'booking_request_id' => $bookingRequest->id,
                        'type' => 'deposit',
                    ],
                ],
            ]);

            return response()->json(['session_id' => $session->id]);

        } catch (\Exception $e) {
            Log::error('Stripe session creation error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la création de la session de paiement'], 500);
        }
    }

    /**
     * Page de succès après paiement
     */
    public function success(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que l'utilisateur est le client du projet
        if ($bookingRequest->client->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('client.booking-requests.show', $bookingRequest->id)
                ->with('error', 'Session de paiement invalide.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);

            // Vérifier que la session est bien pour cette demande
            if ($session->metadata['booking_request_id'] != $bookingRequest->id) {
                abort(400, 'Session invalide pour cette demande');
            }

            // Vérifier que le paiement est réussi
            if ($session->payment_status !== 'paid') {
                return redirect()->route('client.booking-requests.show', $bookingRequest->id)
                    ->with('error', 'Le paiement n\'a pas été complété.');
            }

            // Marquer l'acompte comme payé
            if (!$bookingRequest->isDepositPaid()) {
                $bookingRequest->markDepositPaid($session->payment_intent);

                // Confirmer le rendez-vous
                if ($bookingRequest->appointment_date) {
                    $bookingRequest->confirmAppointment(
                        $bookingRequest->appointment_date,
                        $bookingRequest->estimated_duration ?? 120
                    );
                }

                // Notification au tatoueur (à implémenter)
                // $bookingRequest->bookable->user->notify(new DepositPaidNotification($bookingRequest));
            }

            return redirect()->route('client.booking-requests.show', $bookingRequest->id)
                ->with('success', 'Acompte payé avec succès ! Votre rendez-vous est confirmé.');

        } catch (\Exception $e) {
            Log::error('Deposit payment success error: ' . $e->getMessage());
            return redirect()->route('client.booking-requests.show', $bookingRequest->id)
                ->with('error', 'Erreur lors de la validation du paiement.');
        }
    }

    /**
     * Page d'annulation
     */
    public function cancel(BookingRequest $bookingRequest)
    {
        return redirect()->route('client.booking-requests.show', $bookingRequest->id)
            ->with('info', 'Le paiement a été annulé. Vous pouvez réessayer plus tard.');
    }

    /**
     * Webhook Stripe pour les événements de paiement
     */
    public function webhook(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = $stripe->webhooks->constructEvent(
                $request->getContent(),
                $request->header('stripe-signature'),
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailed($paymentIntent);
                break;

            default:
                // Événement non géré
                break;
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Gérer la complétion d'une session de paiement
     */
    private function handleCheckoutSessionCompleted($session)
    {
        $bookingRequestId = $session->metadata['booking_request_id'] ?? null;

        if (!$bookingRequestId) {
            Log::error("Checkout session completed without booking_request_id");
            return;
        }

        $bookingRequest = BookingRequest::find($bookingRequestId);
        if (!$bookingRequest) {
            Log::error("BookingRequest {$bookingRequestId} not found for checkout session");
            return;
        }

        // Marquer l'acompte comme payé si ce n'est pas déjà fait
        if (!$bookingRequest->isDepositPaid()) {
            $bookingRequest->markDepositPaid($session->payment_intent);

            // Confirmer le rendez-vous
            if ($bookingRequest->appointment_date) {
                $bookingRequest->confirmAppointment(
                    $bookingRequest->appointment_date,
                    $bookingRequest->estimated_duration ?? 120
                );
            }

            Log::info("Deposit paid for booking request {$bookingRequestId}");
        }
    }

    /**
     * Gérer l'échec d'un paiement
     */
    private function handlePaymentFailed($paymentIntent)
    {
        $bookingRequestId = $paymentIntent->metadata['booking_request_id'] ?? null;

        if (!$bookingRequestId) {
            return;
        }

        $bookingRequest = BookingRequest::find($bookingRequestId);
        if (!$bookingRequest) {
            return;
        }

        // Notifier le client de l'échec (à implémenter)
        // $bookingRequest->client->user->notify(new PaymentFailedNotification($bookingRequest, $paymentIntent));

        Log::info("Payment failed for booking request {$bookingRequestId}: " . $paymentIntent->last_payment_error->message ?? 'Unknown error');
    }
}
