<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AppointmentController extends Controller
{
    /**
     * Liste de tous les rendez-vous de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Appointment::query()
            ->with([
                'client.user:id,name,email,phone',
                'tattooer.user:id,name,email',
                'bookingRequest:id,tattoo_size,body_zone,description'
            ]);

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

        if ($request->has('date_from')) {
            $query->where('start_time', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('start_time', '<=', $request->date_to);
        }

        $appointments = $query
            ->orderBy('start_time', 'asc')
            ->paginate(20);

        return response()->json($appointments);
    }

    /**
     * Rendez-vous à venir (prochains RDV non terminés)
     */
    public function upcoming(Request $request)
    {
        $user = $request->user();

        $query = Appointment::query()
            ->with([
                'client.user:id,name,email,phone',
                'tattooer.user:id,name,email,phone',
                'tattooer:id,user_id,studio_name,address,city',
                'bookingRequest:id,tattoo_size,body_zone,description,estimated_total_price'
            ])
            ->upcoming(); // Utilise le scope défini dans le modèle

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

        $appointments = $query
            ->limit(10)
            ->get();

        return response()->json([
            'upcoming_appointments' => $appointments,
            'count' => $appointments->count(),
        ]);
    }

    /**
     * Rendez-vous passés
     */
    public function past(Request $request)
    {
        $user = $request->user();

        $query = Appointment::query()
            ->with([
                'client.user:id,name,email,phone',
                'tattooer.user:id,name,email',
                'bookingRequest:id,tattoo_size,body_zone'
            ])
            ->past(); // Utilise le scope défini dans le modèle

        if ($user->isClient()) {
            $query->where('client_id', $user->client->id);
        } elseif ($user->isTattooer()) {
            $query->where('tattooer_id', $user->tattooer->id);
        } else {
            return response()->json([
                'message' => 'Profil incomplet'
            ], 403);
        }

        $appointments = $query->paginate(20);

        return response()->json($appointments);
    }

    /**
     * Afficher un rendez-vous spécifique
     */
    public function show(Request $request, Appointment $appointment)
    {
        Gate::authorize('view', $appointment);

        $appointment->load([
            'client.user',
            'tattooer.user',
            'tattooer.media',
            'bookingRequest.conversation',
        ]);

        return response()->json($appointment);
    }

    /**
     * Confirmer la réalisation du RDV (tatoueur uniquement)
     */
    public function confirmCompletion(Request $request, Appointment $appointment)
    {
        Gate::authorize('confirm', $appointment);

        $request->validate([
            'note' => 'nullable|string|max:10000',
        ]);

        if ($appointment->tattooer_confirmation_status) {
            return response()->json([
                'message' => 'Ce rendez-vous a déjà été confirmé'
            ], 422);
        }

        $appointment->confirmCompletion($request->note);

        return response()->json([
            'message' => 'Rendez-vous confirmé comme réalisé',
            'appointment' => $appointment->fresh(),
        ]);
    }

    /**
     * Signaler un no-show du client (tatoueur uniquement)
     */
    public function reportNoShow(Request $request, Appointment $appointment)
    {
        Gate::authorize('confirm', $appointment);

        $request->validate([
            'note' => 'nullable|string|max:10000',
        ]);

        if ($appointment->tattooer_confirmation_status) {
            return response()->json([
                'message' => 'Le statut de ce rendez-vous a déjà été défini'
            ], 422);
        }

        $appointment->reportClientNoShow($request->note);

        return response()->json([
            'message' => 'No-show signalé',
            'appointment' => $appointment->fresh(),
        ]);
    }

    /**
     * Signaler un problème (client uniquement)
     */
    public function reportIssue(Request $request, Appointment $appointment)
    {
        Gate::authorize('reportIssue', $appointment);

        $request->validate([
            'description' => 'required|string|min:20|max:10000',
        ]);

        if ($appointment->client_reported_issue) {
            return response()->json([
                'message' => 'Vous avez déjà signalé un problème pour ce rendez-vous'
            ], 422);
        }

        $appointment->reportIssue($request->description);

        return response()->json([
            'message' => 'Problème signalé. Notre équipe va examiner la situation.',
            'appointment' => $appointment->fresh(),
        ]);
    }

    /**
     * Annuler un rendez-vous
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        Gate::authorize('cancel', $appointment);

        $request->validate([
            'reason' => 'required|string|min:10|max:10000',
        ]);

        if (!$appointment->isCancellable()) {
            return response()->json([
                'message' => 'Ce rendez-vous ne peut plus être annulé (déjà passé ou déjà annulé)'
            ], 422);
        }

        // Déterminer qui annule
        $cancelledBy = $request->user()->isClient() ? 'client' : 'tattooer';

        $appointment->cancel($cancelledBy, $request->reason);

        return response()->json([
            'message' => 'Rendez-vous annulé',
            'appointment' => $appointment->fresh(),
            'refund_info' => [
                'refunded' => $appointment->refunded,
                'refund_amount' => $appointment->refund_amount,
            ],
        ]);
    }

    /**
     * RDV nécessitant une confirmation (tatoueur)
     */
    public function requireConfirmation(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json([
                'message' => 'Réservé aux tatoueurs'
            ], 403);
        }

        $appointments = Appointment::query()
            ->with(['client.user:id,name', 'bookingRequest:id,tattoo_size,body_zone'])
            ->where('tattooer_id', $user->tattooer->id)
            ->requireConfirmation() // Scope du modèle
            ->get();

        return response()->json([
            'appointments_requiring_confirmation' => $appointments,
            'count' => $appointments->count(),
        ]);
    }

    /**
     * Statistiques des rendez-vous
     */
    public function statistics(Request $request)
    {
        $user = $request->user();

        if ($user->isClient()) {
            $clientId = $user->client->id;

            $stats = [
                'upcoming' => Appointment::where('client_id', $clientId)
                    ->upcoming()->count(),

                'completed' => Appointment::where('client_id', $clientId)
                    ->where('status', Appointment::STATUS_COMPLETED)->count(),

                'cancelled' => Appointment::where('client_id', $clientId)
                    ->where('status', Appointment::STATUS_CANCELLED)->count(),

                'total' => Appointment::where('client_id', $clientId)->count(),
            ];

        } elseif ($user->isTattooer()) {
            $tattooerId = $user->tattooer->id;

            $stats = [
                'upcoming' => Appointment::where('tattooer_id', $tattooerId)
                    ->upcoming()->count(),

                'completed' => Appointment::where('tattooer_id', $tattooerId)
                    ->where('status', Appointment::STATUS_COMPLETED)->count(),

                'cancelled' => Appointment::where('tattooer_id', $tattooerId)
                    ->where('status', Appointment::STATUS_CANCELLED)->count(),

                'client_no_shows' => Appointment::where('tattooer_id', $tattooerId)
                    ->where('status', Appointment::STATUS_CLIENT_NO_SHOW)->count(),

                'requiring_confirmation' => Appointment::where('tattooer_id', $tattooerId)
                    ->requireConfirmation()->count(),

                'total' => Appointment::where('tattooer_id', $tattooerId)->count(),
            ];

        } else {
            return response()->json([
                'message' => 'Profil incomplet'
            ], 403);
        }

        return response()->json($stats);
    }

    /**
     * Calendrier mensuel (pour affichage planning)
     */
    public function calendar(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'year' => 'required|integer|min:2024|max:2500',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $startOfMonth = now()->setYear((int)$request->year)
            ->setMonth((int)$request->month)
            ->startOfMonth();

        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $query = Appointment::query()
            ->with(['client.user:id,name', 'tattooer.user:id,name', 'bookingRequest:id,tattoo_size'])
            ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', Appointment::STATUS_CANCELLED);

        if ($user->isClient()) {
            $query->where('client_id', $user->client->id);
        } elseif ($user->isTattooer()) {
            $query->where('tattooer_id', $user->tattooer->id);
        } else {
            return response()->json([
                'message' => 'Profil incomplet'
            ], 403);
        }

        $appointments = $query->orderBy('start_time')->get();

        // Formater pour un calendrier
        $calendar = $appointments->groupBy(function ($appointment) {
            return $appointment->start_time->format('Y-m-d');
        });

        return response()->json([
            'year' => $request->year,
            'month' => $request->month,
            'appointments_by_date' => $calendar,
            'total' => $appointments->count(),
        ]);
    }

    /**
     * Contester le remboursement (client uniquement)
     * Utilisé quand 3 dessins ont été envoyés mais sont non conformes
     */
    public function disputeRefund(Request $request, Appointment $appointment)
    {
        Gate::authorize('reportIssue', $appointment);

        $request->validate([
            'reason' => 'required|string|min:50|max:10000',
        ]);

        // Vérifier que l'appointment est annulé
        if ($appointment->status !== Appointment::STATUS_CANCELLED) {
            return response()->json([
                'message' => 'Seuls les rendez-vous annulés peuvent faire l\'objet d\'une contestation'
            ], 422);
        }

        // Vérifier qu'il n'y a pas déjà une contestation
        if ($appointment->client_dispute_refund) {
            return response()->json([
                'message' => 'Une contestation a déjà été déposée pour ce rendez-vous',
                'dispute_status' => $appointment->dispute_resolution,
            ], 422);
        }

        // Vérifier que le client n'a pas été remboursé
        if ($appointment->refunded && $appointment->refund_amount > 0) {
            return response()->json([
                'message' => 'Vous avez déjà été remboursé pour ce rendez-vous'
            ], 422);
        }

        $appointment->disputeRefund($request->reason);

        return response()->json([
            'message' => 'Votre contestation a été enregistrée. Notre équipe va examiner votre cas sous 48h.',
            'appointment' => $appointment->fresh(),
        ]);
    }
}
