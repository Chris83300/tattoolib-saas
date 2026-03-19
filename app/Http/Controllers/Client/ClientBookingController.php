<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Enums\BookingRequestStatus;
use App\Notifications\AppointmentConfirmedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientBookingController extends Controller
{
    /**
     * Liste des demandes du client
     */
    public function bookingRequests(Request $request)
    {
        $client = Auth::user()->client;

        if (!$client) {
            abort(403, 'Profil client non trouvé');
        }

        $query = BookingRequest::where('client_id', $client->id)
            ->with('bookable', 'conversation.messages', 'reviews');

        // Filtrer par statut si spécifié
        if ($request->filled('status')) {
            // Convertir le string en Enum si valide
            try {
                $statusEnum = BookingRequestStatus::from($request->status);
                $query->where('status', $statusEnum->value);
            } catch (\ValueError $e) {
                // Statut invalide, ignorer le filtre
            }
        }

        $bookingRequests = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('client.booking-requests', compact('bookingRequests'));
    }

    /**
     * Détails d'une demande
     */
    public function bookingRequestShow(BookingRequest $bookingRequest)
    {
        $client = Auth::user()->client;

        if (!$client || $bookingRequest->client_id !== $client->id) {
            abort(403, 'Non autorisé');
        }

        $bookingRequest->load('bookable', 'conversation.messages.sender');

        return view('client.booking-request-show', compact('bookingRequest'));
    }

    /**
     * Supprimer une demande de réservation (expirée, refusée ou annulée)
     */
    public function bookingRequestDelete(BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;

        if ($bookingRequest->client_id !== $client->id) {
            abort(403, 'Cette demande ne vous appartient pas.');
        }

        $deletableStatuses = ['expired', 'rejected', 'cancelled'];
        if (!in_array($bookingRequest->status->value, $deletableStatuses)) {
            return redirect()->back()
                ->with('error', 'Seules les demandes expirées, refusées ou annulées peuvent être supprimées.');
        }

        $bookingRequest->conversation?->messages()->forceDelete();
        $bookingRequest->conversation?->forceDelete();
        $bookingRequest->forceDelete();

        return redirect()->route('client.booking-requests')
            ->with('success', 'Demande supprimée.');
    }

    /**
     * Annuler une demande de réservation
     */
    public function bookingRequestCancel(BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;

        // Debug
        Log::info('Tentative annulation demande', [
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $client->id,
            'booking_client_id' => $bookingRequest->client_id,
            'status' => $bookingRequest->status->value,
        ]);

        // Vérifier que la demande appartient au client
        if ($bookingRequest->client_id !== $client->id) {
            Log::error('Tentative annulation demande non autorisée', [
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $client->id,
                'booking_client_id' => $bookingRequest->client_id,
            ]);
            abort(403, 'Cette demande ne vous appartient pas.');
        }

        // Vérifier que la demande peut être annulée
        $cancellableStatuses = [
            'pending', 'accepted', 'awaiting_deposit', 'deposit_paid',
            'date_confirmed', 'design_pending', 'design_sent', 'awaiting_balance',
        ];
        if (!in_array($bookingRequest->status->value, $cancellableStatuses)) {
            Log::error('Tentative annulation demande statut non autorisé', [
                'booking_request_id' => $bookingRequest->id,
                'status' => $bookingRequest->status->value,
            ]);
            return redirect()->back()
                ->with('error', 'Cette demande ne peut plus être annulée.');
        }

        // Mettre à jour le statut
        $bookingRequest->update([
            'status' => \App\Enums\BookingRequestStatus::CANCELLED->value,
            'cancelled_by' => 'client',
            'cancelled_at' => now(),
            'cancellation_reason' => 'Annulation par le client',
        ]);

        // Fermer la conversation associée
        if ($bookingRequest->conversation) {
            $bookingRequest->conversation->update(['status' => 'archived']);
        }

        return redirect()->route('client.booking-requests')
            ->with('success', 'Votre demande a été annulée avec succès.');
    }

    /**
     * Annuler une demande avec remboursement conditionnel (nouvelle route)
     */
    public function cancelRequest(Request $request, BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;
        abort_unless($client && $bookingRequest->client_id === $client->id, 403);
        abort_unless(
            !in_array($bookingRequest->status->value, ['completed', 'cancelled']),
            422,
            'Cette demande ne peut plus être annulée.'
        );

        $refundInfo = app(\App\Services\CancellationService::class)->processCancellation(
            $bookingRequest,
            'client',
            $request->input('cancellation_message', '')
        );

        // Notifier l'artiste
        try {
            $bookingRequest->bookable?->user?->notify(
                new \App\Notifications\BookingCancelledNotification($bookingRequest)
            );
        } catch (\Exception $e) {
            Log::warning('Notification annulation client échouée: ' . $e->getMessage());
        }

        $msg = 'Votre demande a été annulée.';
        if ($refundInfo['refund_amount'] > 0) {
            $msg .= ' Remboursement de ' . number_format($refundInfo['refund_amount'], 2, ',', ' ') . '€ en cours (5-10 jours ouvrés).';
        }

        return redirect()->route('client.booking-requests')->with('success', $msg);
    }

    /**
     * Sélectionner une date proposée par le tattooer
     */
    public function selectProposedDate(Request $request, BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;

        if (!$client || $bookingRequest->client_id !== $client->id) {
            abort(403, 'Non autorisé');
        }

        $validated = $request->validate([
            'index' => 'required|integer|min:0',
        ]);

        $proposedDates = $bookingRequest->proposed_dates;

        if (!isset($proposedDates[$validated['index']])) {
            return redirect()->back()
                ->with('error', 'Date invalide.');
        }

        // Vérifier que l'acompte est payé
        if ($bookingRequest->status->value !== 'deposit_paid') {
            return redirect()->back()
                ->with('error', 'Vous devez payer l\'acompte avant de choisir une date.');
        }

        $selectedDate = $proposedDates[$validated['index']];

        $bookingRequest->update([
            'confirmed_date'            => $selectedDate['date'],
            'confirmed_period'          => $selectedDate['period'] ?? null,
            'client_selected_dates'     => [$selectedDate],
            'client_dates_selected_at'  => now(),
        ]);

        // Message système dans le chat
        $conversation = $bookingRequest->conversation;
        if ($conversation) {
            $dateFr = \Carbon\Carbon::parse($selectedDate['date'])->translatedFormat('l d F Y');
            $period = match($selectedDate['period'] ?? '') {
                'morning'   => 'matin',
                'afternoon' => 'après-midi',
                'evening'   => 'soirée',
                default     => 'horaire flexible',
            };

            $conversation->messages()->create([
                'sender_type' => 'system',
                'sender_id'   => null,
                'content'     => "📅 Le client a choisi la date du {$dateFr} ({$period}).",
            ]);

            // Envoyer le formulaire de consentement dans le chat
            $conversation->messages()->create([
                'sender_type' => 'system',
                'sender_id'   => null,
                'content'     => '[CONSENT_FORM:' . $bookingRequest->id . ']',
            ]);
        }

        // Notifier l'artiste que le client a sélectionné une date
        if ($bookingRequest->bookable?->user) {
            $bookingRequest->bookable->user->notify(new AppointmentConfirmedNotification($bookingRequest));
        }

        return redirect()->back()
            ->with('success', 'Date sélectionnée ! L\'artiste va fixer l\'horaire.');
    }

    /**
     * Demander des dates alternatives
     */
    public function requestAlternativeDates(Request $request, BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;

        if (!$client || $bookingRequest->client_id !== $client->id) {
            abort(403, 'Non autorisé');
        }

        $conversation = $bookingRequest->conversation;
        if ($conversation) {
            $conversation->messages()->create([
                'sender_type' => 'system',
                'sender_id'   => null,
                'content'     => "⚠️ Le client ne peut à aucune des dates proposées et demande d'autres alternatives.",
            ]);
        }

        return redirect()->back()
            ->with('info', 'Votre demande a été envoyée à l\'artiste.');
    }
}
