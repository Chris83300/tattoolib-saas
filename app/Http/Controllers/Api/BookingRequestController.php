<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequestRequest;
use App\Models\BookingRequest;
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
     * Créer une nouvelle demande de réservation
     */
    public function store(StoreBookingRequestRequest $request)
    {
        $user = $request->user();

        if (!$user->isClient()) {
            return response()->json([
                'message' => 'Seuls les clients peuvent créer des demandes'
            ], 403);
        }

        $tattooer = Tattooer::findOrFail($request->tattooer_id);

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

        // Créer la demande
        $bookingRequest = BookingRequest::create([
            'client_id' => $user->client->id,
            'tattooer_id' => $tattooer->id,
            'tattoo_size' => $request->tattoo_size,
            'body_zone' => $request->body_zone,
            'description' => $request->description,
            'estimated_budget' => $request->estimated_budget,
            'preferred_timeframe' => $request->preferred_timeframe,
            'preferred_days' => $request->preferred_days,
            'date_notes' => $request->date_notes,
            'status' => BookingRequest::STATUS_PENDING,

            // Valeurs par défaut du tatoueur
            'client_payment_deadline_days' => $tattooer->default_client_payment_deadline_days,
            'tattooer_design_deadline_days' => $tattooer->default_tattooer_design_deadline_days,
            'included_design_versions' => $tattooer->default_design_versions_included,
        ]);

        // TODO: Event BookingRequestCreated pour notifier le tatoueur

        return response()->json([
            'message' => 'Demande envoyée avec succès',
            'booking_request' => $bookingRequest->load('tattooer.user'),
        ], 201);
    }

    /**
     * Accepter une demande (tatoueur)
     */
    public function accept(Request $request, BookingRequest $bookingRequest)
    {
        Gate::authorize('accept', $bookingRequest);

        if ($bookingRequest->status !== BookingRequest::STATUS_PENDING) {
            return response()->json([
                'message' => 'Cette demande ne peut plus être acceptée'
            ], 422);
        }

        $bookingRequest->accept();

        return response()->json([
            'message' => 'Demande acceptée',
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
     * Demander l'acompte (tatoueur)
     */
    public function requestDeposit(Request $request, BookingRequest $bookingRequest)
    {
        Gate::authorize('requestDeposit', $bookingRequest);

        $request->validate([
            'deposit_amount' => 'required|numeric|min:10',
            'estimated_total_price' => 'required|numeric|min:0',
            'deadline_days' => 'required|integer|min:1|max:30',
        ]);

        if ($bookingRequest->status !== BookingRequest::STATUS_ACCEPTED) {
            return response()->json([
                'message' => 'Vous devez d\'abord accepter la demande'
            ], 422);
        }

        $bookingRequest->update([
            'estimated_total_price' => $request->estimated_total_price,
        ]);

        $bookingRequest->requestDeposit(
            $request->deposit_amount,
            $request->deadline_days
        );

        return response()->json([
            'message' => 'Demande d\'acompte envoyée',
            'booking_request' => $bookingRequest->fresh(),
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
