<?php

namespace App\Http\Controllers\Tattooer;

use App\Models\BookingRequest;
use App\Enums\BookingRequestStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TattooerBookingController extends ArtisanBaseController
{
    /**
     * Gestion demandes projet
     */
    public function requests(Request $request)
    {
        $tattooer = $this->artisan();
        $filter = $request->query('status', 'all'); // par défaut "all" pour tout afficher

        // Service pour stats (1 requête au lieu de 5)
        $statsService = app(\App\Services\TattooerStatsService::class);
        $counts = $statsService->getRequestsStats($tattooer);

        // UNE SEULE requête avec eager loading optimisé
        $query = BookingRequest::where('bookable_type', get_class($tattooer))
            ->where('bookable_id', $tattooer->id)
            ->with(['client.user', 'conversation' => function($query) {
                $query->withCount(['messages as unread_count' => function($q) {
                    $q->where('sender_type', 'client')
                          ->whereNull('read_by_tattooer_at');
                }]);
            }]);

        // Filtrer par statut selon l'onglet
        $query = match($filter) {
            'all'       => $query, // toutes les demandes
            'pending'   => $query->where('status', BookingRequestStatus::PENDING->value),
            'accepted'  => $query->whereIn('status', [
                BookingRequestStatus::ACCEPTED->value,
                BookingRequestStatus::DEPOSIT_REQUESTED->value,
                BookingRequestStatus::DEPOSIT_PAID->value,
            ]),
            'confirmed' => $query->where('status', BookingRequestStatus::DATE_CONFIRMED->value),
            'completed' => $query->where('status', BookingRequestStatus::COMPLETED->value),
            'rejected'  => $query->where('status', BookingRequestStatus::REJECTED->value),
            'cancelled' => $query->where('status', BookingRequestStatus::CANCELLED->value),
            'expired'  => $query->where('status', BookingRequestStatus::EXPIRED->value),
            default     => $query,
        };

        $requests = $query->orderBy('created_at', 'desc')->get();

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        // Utiliser la vue tattooer.requests pour tout le monde (tattooers et pierceurs)
        // La vue contient déjà les conditions pour adapter l'affichage selon le type d'artisan
        return view('tattooer.requests', compact('tattooer', 'requests', 'filter', 'counts', 'pendingCount', 'unreadCount'));
    }

    /**
     * Détail demande projet
     */
    public function requestShow(BookingRequest $bookingRequest)
    {
        $artisan = $this->artisan();

        // Vérifier que la demande appartient à l'artisan (tattooer ou piercer) via relation polymorphique
        if ($bookingRequest->bookable_id !== $artisan->id) {
            abort(403);
        }

        // Charger relations nécessaires
        $bookingRequest->load([
            'client.user',
            'conversation',
            'media'
        ]);

        // Compteurs pour le layout
        $pendingCount = \App\Models\BookingRequest::where('bookable_id', $artisan->id)
            ->where('bookable_type', get_class($artisan))
            ->where('status', 'pending')
            ->count();

        $unreadCount = \App\Models\Conversation::whereHas('messages', function ($query) {
                $query->where(function ($q) {
                    // Si l'utilisateur est un tattooer/piercer, vérifier read_by_tattooer_at
                    if (auth()->user()->isTattooer() || auth()->user()->isPiercer()) {
                        $q->whereNull('read_by_tattooer_at')
                          ->where('sender_type', 'client');
                    } else {
                        // Pour les clients, vérifier read_by_client_at
                        $q->whereNull('read_by_client_at')
                          ->where('sender_type', 'tattooer');
                    }
                });
            })
            ->whereHas('bookingRequest', function($query) use ($artisan) {
                $query->where('bookable_id', $artisan->id)
                    ->where('bookable_type', get_class($artisan));
            })
            ->count();

        return view('tattooer.request-show', [
            'bookingRequest' => $bookingRequest,
            'tattooer' => $artisan, // Passer l'artisan comme $tattooer pour la compatibilité avec la vue
            'pendingCount' => $pendingCount,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Accepter une demande de réservation
     */
    public function acceptRequest(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer connecté
        $tattooer = $this->artisan();

        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== get_class($tattooer)) {
            abort(403, 'Non autorisé');
        }

        // Valider les données du formulaire
        $validated = $request->validate([
            'price_estimate_min' => 'required|numeric|min:0',
            'price_estimate_max' => 'required|numeric|min:0',
            'proposed_dates' => 'nullable|array|max:3',
            'proposed_dates.*.date' => 'required|date|after:today',
            'proposed_dates.*.period' => 'nullable|in:morning,afternoon,evening',
            'included_design_versions' => 'required|integer|min:1',
            'modifications_per_design' => 'required|integer|min:0',
            'total_deposit_amount' => 'required|numeric|min:0',
            'client_payment_deadline_days' => 'required|integer|min:1',
        ]);

        // Mettre à jour le statut de la demande
        $bookingRequest->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Mettre à jour avec les données du formulaire d'acceptation
        $bookingRequest->update([
            'price_estimate_min' => $validated['price_estimate_min'],
            'price_estimate_max' => $validated['price_estimate_max'],
            'proposed_dates' => $validated['proposed_dates'] ?? [],
            'included_design_versions' => $validated['included_design_versions'],
            'modifications_per_design' => $validated['modifications_per_design'],
            'total_deposit_amount' => $validated['total_deposit_amount'],
            'client_payment_deadline_days' => $validated['client_payment_deadline_days'],
            'deposit_deadline' => now()->addDays((int)$validated['client_payment_deadline_days']),
            'date_selection_deadline' => now()->addHours(48), // 48h pour choisir les dates
        ]);

        // Créer une conversation si elle n'existe pas
        if (!$bookingRequest->conversation) {
            $conversation = \App\Models\Conversation::create([
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $bookingRequest->client_id,
                'tattooer_id' => $tattooer->id,
                'status' => 'active',
            ]);

            // Envoyer le message d'acceptation
            $conversation->messages()->create([
                'sender_type' => 'tattooer',
                'sender_id' => $tattooer->id,
                'content' => "Bonjour ! \n\n" .
                           "J'accepte votre demande de tattoo avec plaisir !\n\n" .
                           " Zone : {$bookingRequest->body_zone}\n" .
                           " Prix : {$validated['price_estimate_min']}€ - {$validated['price_estimate_max']}€\n" .
                           " Acompte : {$validated['total_deposit_amount']}€\n\n" .
                           "N'hésitez pas à me contacter si vous avez des questions !",
                'read_by_client_at' => null,
                'read_by_tattooer_at' => now(),
            ]);
        }

        // Envoyer une notification au client
        // TODO: Implémenter le système de notifications

        return redirect()->route($this->routePrefix() . '.request.show', $bookingRequest)
            ->with('success', 'Demande acceptée avec succès !');
    }

    /**
     * Refuser une demande de réservation
     */
    public function requestReject(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer connecté
        $tattooer = $this->artisan();

        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== get_class($tattooer)) {
            abort(403, 'Non autorisé');
        }

        // Valider le message optionnel
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $reason = $validated['rejection_reason'] ?? null;
        $defaultReason = 'Demande refusée par l\'artiste.';

        // Mettre à jour le statut de la demande
        $bookingRequest->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'cancellation_reason' => $reason ?: $defaultReason,
        ]);

        // Créer une conversation si elle n'existe pas + envoyer un message
        $conversation = $bookingRequest->conversation;

        if (!$conversation) {
            $conversation = \App\Models\Conversation::create([
                'booking_request_id' => $bookingRequest->id,
                'status' => 'archived', // Utiliser 'archived' qui existe dans la table
                'expiry_type' => 'archived',
                'archived_at' => now(),
            ]);
        } else {
            // Utiliser une requête directe pour éviter le problème d'enum
            DB::table('conversations')
                ->where('id', $conversation->id)
                ->update([
                    'status' => 'archived',
                    'expiry_type' => 'archived',
                    'archived_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        // Message système de rejet
        $messageContent = "❌ L'artiste a décliné votre demande.";
        if ($reason) {
            $messageContent .= "\n\n💬 Message de l'artiste :\n\"{$reason}\"";
        }

        $conversation->messages()->create([
            'sender_type' => 'system',
            'sender_id'   => null,
            'content'     => $messageContent,
        ]);

        // TODO: Envoyer notification au client

        return redirect()->route($this->routePrefix() . '.request.show', $bookingRequest)
            ->with('success', 'Demande refusée avec succès !');
    }

    /**
     * Re-proposer de nouvelles dates après refus client
     */
    public function reproposeDates(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer connecté
        $tattooer = $this->artisan();

        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== get_class($tattooer)) {
            abort(403, 'Non autorisé');
        }

        // Valider les dates proposées
        $validated = $request->validate([
            'proposed_dates' => 'required|json',
        ]);

        $datesData = json_decode($validated['proposed_dates'], true);

        // Gérer différents formats possibles
        if (isset($datesData['selectedDates'])) {
            // Format avec selectedDates (notre JavaScript)
            $dates = $datesData['selectedDates'];
        } else {
            // Format direct (Livewire standard)
            $dates = $datesData;
        }

        abort_unless(is_array($dates) && count($dates) >= 1 && count($dates) <= 3, 422, 'Sélectionnez 1 à 3 dates.');

        // Nettoyer et valider chaque date
        $cleanDates = [];
        foreach ($dates as $date) {
            if (isset($date['date'])) {
                $cleanDates[] = [
                    'date' => $date['date'],
                    'period' => !empty($date['period']) ? $date['period'] : null
                ];
            }
        }

        if (empty($cleanDates)) {
            abort(422, 'Sélectionnez au moins une date valide.');
        }

        // Mettre à jour la booking request avec les nouvelles dates
        $bookingRequest->update([
            'proposed_dates' => $cleanDates,
            'client_selected_dates' => null,
            'client_dates_selected_at' => null,
            'date_selection_deadline' => now()->addHours(48),
        ]);

        // Envoyer un message système dans le chat
        $conversation = $bookingRequest->conversation;
        if ($conversation) {
            $datesFormatted = collect($cleanDates)->map(function ($d) {
                $date = \Carbon\Carbon::parse($d['date'])->translatedFormat('l d F Y');
                $period = match ($d['period'] ?? '') {
                    'morning' => 'matin',
                    'afternoon' => 'après-midi',
                    '' => '',  // Période vide = journée entière
                    default => $d['period'] ?? '',
                };
                return $date . ($period ? " ($period)" : '');
            })->join(', ');

            $conversation->messages()->create([
                'sender_id' => auth()->id(),
                'sender_type' => 'App\\Models\\User',
                'content' => "📅 Nouvelles dates proposées : {$datesFormatted}. Merci de sélectionner votre préférence.",
                'read_by_tattooer_at' => now(),
            ]);

            $conversation->update([
                'last_message_at' => now(),
            ]);
        }

        // Notifier le client
        $client = $bookingRequest->client?->user;
        if ($client) {
            $client->notifications()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\\Notifications\\NewDatesProposedNotification',
                'data' => [
                    'title' => 'Nouvelles dates proposées',
                    'message' => "L'artiste vous propose de nouvelles dates pour votre projet.",
                    'booking_request_id' => $bookingRequest->id,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Nouvelles dates proposées au client !');
    }

    /**
     * Supprimer une demande expirée, refusée ou annulée
     */
    public function destroyRequest(Request $request, BookingRequest $bookingRequest)
    {
        $user   = $request->user();
        $artist = $user->tattooer ?? $user->piercer;

        abort_unless(
            $artist
            && $bookingRequest->bookable_id === $artist->id
            && $bookingRequest->bookable_type === get_class($artist),
            403,
            'Non autorisé'
        );

        $deletableStatuses = ['expired', 'rejected', 'cancelled'];
        abort_unless(
            in_array($bookingRequest->status->value, $deletableStatuses),
            422,
            'Seules les demandes expirées, refusées ou annulées peuvent être supprimées.'
        );

        $bookingRequest->conversation?->messages()->forceDelete();
        $bookingRequest->conversation?->forceDelete();
        $bookingRequest->forceDelete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Demande supprimée.');
    }

    /**
     * Annuler une demande de réservation (côté artiste)
     */
    public function cancelRequest(Request $request, BookingRequest $bookingRequest)
    {
        $artist = $this->artisan();
        abort_unless($artist, 403);
        abort_unless($bookingRequest->bookable_id === $artist->id, 403);
        abort_unless(
            !in_array($bookingRequest->status->value, ['completed', 'cancelled']),
            422,
            'Cette demande ne peut plus être annulée.'
        );

        $refundInfo = app(\App\Services\CancellationService::class)->processCancellation(
            $bookingRequest,
            'artist',
            $request->input('cancellation_message', '')
        );

        // Notifier le client
        try {
            $bookingRequest->client?->user?->notify(
                new \App\Notifications\BookingCancelledNotification($bookingRequest)
            );
        } catch (\Exception $e) {
            Log::warning('Notification annulation artiste échouée: ' . $e->getMessage());
        }

        $msg = 'Demande annulée.';
        if ($refundInfo['refund_amount'] > 0) {
            $msg .= ' Remboursement de ' . number_format($refundInfo['refund_amount'], 2, ',', ' ') . '€ en cours.';
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Marquer une demande comme terminée (RDV validé)
     */
    public function completeBooking(Request $request, BookingRequest $bookingRequest)
    {
        $tattooer = $this->artisan();

        // Vérifier que la demande appartient bien au tattooer
        if ($bookingRequest->bookable_id !== $tattooer->id || $bookingRequest->bookable_type !== get_class($tattooer)) {
            abort(403, 'Non autorisé');
        }

        // Vérifier que la transition est autorisée
        if (!$bookingRequest->status->canTransitionTo(BookingRequestStatus::COMPLETED)) {
            return redirect()->back()->with('error', 'Impossible de terminer cette demande.');
        }

        // Marquer comme terminé
        $bookingRequest->update(['completed_at' => now()]);
        $bookingRequest->transitionTo(BookingRequestStatus::COMPLETED);

        return redirect()->back()->with('success', 'RDV validé avec succès.');
    }

    /**
     * Déclarer un client comme absent (no-show)
     */
    public function markNoShow(Request $request, BookingRequest $bookingRequest)
    {
        $tattooer = $this->artisan();

        // Vérifier que la demande appartient bien au tattooer
        if ($bookingRequest->bookable_id !== $tattooer->id || $bookingRequest->bookable_type !== get_class($tattooer)) {
            abort(403, 'Non autorisé');
        }

        // Vérifier que la transition est autorisée
        if (!$bookingRequest->status->canTransitionTo(BookingRequestStatus::NO_SHOW)) {
            return redirect()->back()->with('error', 'Impossible de déclarer no-show pour cette demande.');
        }

        // Marquer comme no-show
        $bookingRequest->update(['no_show_at' => now()]);
        $bookingRequest->transitionTo(BookingRequestStatus::NO_SHOW);

        // Incrémenter le compteur no-show du client
        $bookingRequest->client->increment('no_show_count');

        return redirect()->back()->with('success', 'No-show déclaré avec succès.');
    }
}
