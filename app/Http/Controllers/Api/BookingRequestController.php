<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequestRequest;
use App\Models\BookingRequest;
use App\Models\Availability;
use App\Models\Tattooer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingRequestController extends Controller
{
    /**
     * Liste des demandes de réservation
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = BookingRequest::query()
            ->with(['client.user', 'tattooer.user', 'conversation']);

        // Filtrer selon le type d'utilisateur
        if ($user->isClient()) {
            $query->where('client_id', $user->client->id);
        } elseif ($user->isTattooer()) {
            $query->where('tattooer_id', $user->tattooer->id);
        } else {
            return response()->json([
                'message' => 'Profil incomplet'
            ], 403);
        }

        // Filtres optionnels
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookingRequests = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($bookingRequests);
    }

    /**
     * Afficher une demande spécifique
     */
    public function show(Request $request, BookingRequest $bookingRequest)
    {
        Gate::authorize('view', $bookingRequest);

        $bookingRequest->load([
            'client.user',
            'tattooer.user',
            'conversation.lastMessage',
            'appointment'
        ]);

        return response()->json($bookingRequest);
    }

    /**
     * ⭐ Client demande un RDV (avec préférence date/horaire)
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->isClient()) {
            return response()->json(['message' => 'Accès réservé aux clients'], 403);
        }

        $validated = $request->validate([
            'tattooer_id' => 'required|exists:tattooers,id',
            'tattoo_size' => 'required|string',
            'body_zone' => 'required|string',
            'description' => 'required|string',

            // ⭐ NOUVEAU : Préférences date/horaire
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_time_slot' => 'nullable|in:morning,afternoon,evening,anytime',
            'preferred_time_notes' => 'nullable|string|max:500',

            'estimated_budget' => 'nullable|numeric|min:0',
        ]);

        $tattooer = Tattooer::findOrFail($validated['tattooer_id']);

        // Vérifier que le client n'est pas blacklisté
        if ($user->client->is_blacklisted) {
            return response()->json([
                'message' => 'Votre compte est suspendu',
                'reason' => $user->client->blacklist_reason,
            ], 403);
        }

        // Vérifier que le tatoueur peut accepter des réservations
        if (!$tattooer->canAcceptBookings()) {
            return response()->json([
                'message' => 'Ce tatoueur n\'accepte pas de nouvelles réservations pour le moment'
            ], 422);
        }

        // ⭐ Vérifier que la date demandée a de la disponibilité
        if (isset($validated['preferred_date'])) {
            $hasAvailability = Availability::hasAvailabilityOnDate(
                $validated['tattooer_id'],
                $validated['preferred_date']
            );

            if (!$hasAvailability) {
                return response()->json([
                    'message' => 'Aucune disponibilité pour cette date'
                ], 422);
            }
        }

        $bookingRequest = BookingRequest::create([
            'client_id' => $user->client->id,
            'tattooer_id' => $validated['tattooer_id'],
            'tattoo_size' => $validated['tattoo_size'],
            'body_zone' => $validated['body_zone'],
            'description' => $validated['description'],
            'preferred_date' => $validated['preferred_date'] ?? null,
            'preferred_time_slot' => $validated['preferred_time_slot'] ?? 'anytime',
            'preferred_time_notes' => $validated['preferred_time_notes'] ?? null,
            'estimated_budget' => $validated['estimated_budget'] ?? null,
            'status' => BookingRequest::STATUS_PENDING,

            // Valeurs par défaut du tatoueur
            'client_payment_deadline_days' => $tattooer->default_client_payment_deadline_days,
            'tattooer_design_deadline_days' => $tattooer->default_tattooer_design_deadline_days,
            'included_design_versions' => $tattooer->default_design_versions_included,
        ]);

        return response()->json([
            'message' => 'Demande envoyée avec succès',
            'booking_request' => $bookingRequest->load('tattooer.user'),
        ], 201);
    }

    /**
     * ⭐ Tatoueur accepte + fixe heure exacte
     */
    public function accept(Request $request, BookingRequest $bookingRequest)
    {
        Gate::authorize('accept', $bookingRequest);

        if ($bookingRequest->status !== BookingRequest::STATUS_PENDING) {
            return response()->json([
                'message' => 'Cette demande ne peut plus être acceptée'
            ], 422);
        }

        $validated = $request->validate([
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_start_time' => 'required|date_format:H:i',
            'scheduled_duration_minutes' => 'required|integer|min:30|max:480',
            'total_price' => 'required|numeric|min:0',
            'deposit_rate' => 'required|numeric|min:0|max:100',
            'deposit_deadline_hours' => 'required|integer|min:24|max:720', // 1j à 30j
        ]);

        $scheduledEndTime = \Carbon\Carbon::createFromFormat('H:i', $validated['scheduled_start_time'])
            ->addMinutes($validated['scheduled_duration_minutes'])
            ->format('H:i');

        // ⭐ Vérifier disponibilité créneau
        $slots = Availability::getAvailableSlotsForDay(
            $bookingRequest->tattooer_id,
            $validated['scheduled_date']
        );

        $isAvailable = false;
        foreach ($slots as $slot) {
            if ($slot['start_time'] <= $validated['scheduled_start_time'] &&
                $slot['end_time'] >= $scheduledEndTime) {
                $isAvailable = true;
                break;
            }
        }

        if (!$isAvailable) {
            return response()->json(['message' => 'Créneau non disponible'], 422);
        }

        $depositAmount = $validated['total_price'] * ($validated['deposit_rate'] / 100);
        $depositDeadline = now()->addHours($validated['deposit_deadline_hours']);

        $bookingRequest->update([
            'status' => BookingRequest::STATUS_ACCEPTED,
            'accepted_at' => now(),
            'scheduled_start_time' => $validated['scheduled_start_time'],
            'scheduled_end_time' => $scheduledEndTime,
            'scheduled_duration_minutes' => $validated['scheduled_duration_minutes'],
            'total_price' => $validated['total_price'],
            'total_deposit_amount' => $depositAmount,
            'deposit_deadline' => $depositDeadline,
        ]);

        return response()->json([
            'message' => 'Demande acceptée. En attente du paiement de l\'acompte.',
            'booking_request' => $bookingRequest->fresh(['conversation']),
        ]);
    }

    /**
     * Rejeter une demande (tatoueur)
     */
    public function reject(Request $request, BookingRequest $bookingRequest)
    {
        Gate::authorize('reject', $bookingRequest);

        if ($bookingRequest->status !== BookingRequest::STATUS_PENDING) {
            return response()->json([
                'message' => 'Cette demande ne peut plus être rejetée'
            ], 422);
        }

        $bookingRequest->reject();

        return response()->json([
            'message' => 'Demande rejetée',
        ]);
    }

    /**
     * ⭐ Client paie l'acompte → RDV confirmé
     */
    public function confirmDeposit(Request $request, BookingRequest $bookingRequest)
    {
        $user = $request->user();

        if (!$user->isClient() || $bookingRequest->client_id !== $user->client->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        if ($bookingRequest->status !== BookingRequest::STATUS_ACCEPTED) {
            return response()->json(['message' => 'Demande non acceptée'], 422);
        }

        if ($bookingRequest->deposit_deadline && now()->isAfter($bookingRequest->deposit_deadline)) {
            return response()->json(['message' => 'Délai de paiement dépassé'], 422);
        }

        // TODO: Intégration Stripe ici
        // Pour l'instant, on simule le paiement réussi
        $bookingRequest->update([
            'status' => BookingRequest::STATUS_DEPOSIT_PAID,
            'deposit_paid_at' => now(),
        ]);

        return response()->json([
            'message' => 'Acompte payé avec succès',
            'booking_request' => $bookingRequest->fresh(),
        ]);
    }

    /**
     * ⭐ Vérifier les demandes expirées (job cron)
     */
    public function checkExpiredRequests()
    {
        $expiredRequests = BookingRequest::where('status', BookingRequest::STATUS_ACCEPTED)
            ->where('deposit_deadline', '<', now())
            ->whereNull('deposit_paid_at')
            ->get();

        foreach ($expiredRequests as $request) {
            $request->update([
                'status' => BookingRequest::STATUS_EXPIRED,
                'expired_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Vérification terminée',
            'expired_count' => $expiredRequests->count(),
        ]);
    }

    /**
     * Marquer l'acompte comme payé (webhook Stripe)
     */
    public function markDepositPaid(Request $request, BookingRequest $bookingRequest)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        if ($bookingRequest->status !== BookingRequest::STATUS_AWAITING_DEPOSIT) {
            return response()->json([
                'message' => 'Statut invalide'
            ], 422);
        }

        $bookingRequest->markDepositPaid($request->payment_intent_id);

        return response()->json([
            'message' => 'Acompte confirmé',
            'booking_request' => $bookingRequest->fresh(),
        ]);
    }

    /**
     * Envoyer le design (tatoueur)
     */
    public function sendDesign(Request $request, BookingRequest $bookingRequest)
    {
        Gate::authorize('sendDesign', $bookingRequest);

        if ($bookingRequest->status !== BookingRequest::STATUS_DEPOSIT_PAID
            && $bookingRequest->status !== BookingRequest::STATUS_DESIGN_SENT) {
            return response()->json([
                'message' => 'L\'acompte doit être payé avant d\'envoyer le design'
            ], 422);
        }

        // Vérifier qu'il reste des versions disponibles
        if (!$bookingRequest->canSendNewDesignVersion()) {
            return response()->json([
                'message' => 'Nombre maximum de versions atteint',
                'used' => $bookingRequest->design_versions_used,
                'included' => $bookingRequest->included_design_versions,
            ], 422);
        }

        $bookingRequest->sendDesign();

        return response()->json([
            'message' => 'Design envoyé',
            'booking_request' => $bookingRequest->fresh(),
        ]);
    }

    /**
     * Confirmer le rendez-vous final
     */
    public function confirmAppointment(Request $request, BookingRequest $bookingRequest)
    {
        Gate::authorize('confirmAppointment', $bookingRequest);

        $request->validate([
            'appointment_datetime' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:30|max:480',
        ]);

        if ($bookingRequest->status !== BookingRequest::STATUS_DESIGN_SENT) {
            return response()->json([
                'message' => 'Le design doit être validé avant de confirmer le RDV'
            ], 422);
        }

        $bookingRequest->confirm(
            new \DateTime($request->appointment_datetime),
            $request->duration_minutes
        );

        return response()->json([
            'message' => 'Rendez-vous confirmé',
            'booking_request' => $bookingRequest->fresh(['appointment']),
        ]);
    }

    /**
     * Annuler une demande
     */
    public function cancel(Request $request, BookingRequest $bookingRequest)
    {
        Gate::authorize('cancel', $bookingRequest);

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $bookingRequest->update([
            'status' => BookingRequest::STATUS_CANCELLED,
        ]);

        // TODO: Logique de remboursement si nécessaire

        return response()->json([
            'message' => 'Demande annulée',
        ]);
    }

    /**
     * Statistiques pour le tatoueur
     */
    public function statistics(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json([
                'message' => 'Réservé aux tatoueurs'
            ], 403);
        }

        $tattooerId = $user->tattooer->id;

        $stats = [
            'pending' => BookingRequest::where('tattooer_id', $tattooerId)
                ->where('status', BookingRequest::STATUS_PENDING)->count(),

            'accepted' => BookingRequest::where('tattooer_id', $tattooerId)
                ->where('status', BookingRequest::STATUS_ACCEPTED)->count(),

            'awaiting_deposit' => BookingRequest::where('tattooer_id', $tattooerId)
                ->where('status', BookingRequest::STATUS_AWAITING_DEPOSIT)->count(),

            'in_progress' => BookingRequest::where('tattooer_id', $tattooerId)
                ->whereIn('status', [
                    BookingRequest::STATUS_DEPOSIT_PAID,
                    BookingRequest::STATUS_DESIGN_SENT,
                ])->count(),

            'confirmed' => BookingRequest::where('tattooer_id', $tattooerId)
                ->where('status', BookingRequest::STATUS_CONFIRMED)->count(),

            'overdue_designs' => BookingRequest::where('tattooer_id', $tattooerId)
                ->where('status', BookingRequest::STATUS_DEPOSIT_PAID)
                ->where('tattooer_design_deadline', '<', now())
                ->whereNull('design_sent_at')
                ->count(),
        ];

        return response()->json($stats);
    }
}
