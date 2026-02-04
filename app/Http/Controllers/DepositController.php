<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class DepositController extends Controller
{
    /**
     * Afficher la page de paiement de l'acompte
     */
    public function payment(BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;
        
        // Vérifier que la demande appartient au client
        if ($bookingRequest->client_id !== $client->id) {
            abort(403, 'Cette demande ne vous appartient pas.');
        }
        
        // Vérifier le statut
        if (!in_array($bookingRequest->status, ['accepted', 'awaiting_deposit'])) {
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
        
        return view('client.deposit-payment', compact('bookingRequest'));
    }
    
    /**
     * Créer la session de paiement Stripe
     */
    public function process(BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;
        
        // Vérifications
        if ($bookingRequest->client_id !== $client->id) {
            abort(403, 'Cette demande ne vous appartient pas.');
        }
        
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
        
        // Créer Stripe Checkout
        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Acompte - ' . $bookingRequest->bookable->user->name,
                            'description' => 'Acompte pour réservation tattoo - Demande #' . $bookingRequest->id,
                        ],
                        'unit_amount' => $bookingRequest->total_deposit_amount * 100, // En centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('deposit.success', $bookingRequest),
                'cancel_url' => route('deposit.cancel', $bookingRequest),
                'client_reference_id' => $bookingRequest->id,
                'metadata' => [
                    'booking_request_id' => $bookingRequest->id,
                    'client_id' => $client->id,
                    'type' => 'deposit',
                ],
                'expires_at' => now()->addHours(24)->timestamp, // Expire après 24h
            ]);
            
            return redirect($session->url);
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du paiement: ' . $e->getMessage());
        }
    }
    
    /**
     * Page de succès après paiement
     */
    public function success(BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;
        
        // Vérifier propriété
        if ($bookingRequest->client_id !== $client->id) {
            abort(403);
        }
        
        // La confirmation se fait via webhook
        // On affiche juste un message de confirmation
        return view('client.deposit-success', compact('bookingRequest'));
    }
    
    /**
     * Page d'annulation de paiement
     */
    public function cancel(BookingRequest $bookingRequest)
    {
        return redirect()->route('client.booking-request.show', $bookingRequest)
            ->with('warning', 'Paiement annulé. Vous pouvez réessayer.');
    }
}
