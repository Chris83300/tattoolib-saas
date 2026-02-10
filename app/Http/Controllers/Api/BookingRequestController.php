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
        
        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        // Si c'est un client, afficher ses demandes
        if ($user->hasRole('client')) {
            $bookingRequests = BookingRequest::where('client_id', $user->client->id)
                ->with(['bookable.user'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // Si c'est un tattooer, afficher les demandes reçues
        elseif ($user->hasRole('tattooer')) {
            $bookingRequests = BookingRequest::where('bookable_id', $user->tattooer->id)
                ->where('bookable_type', Tattooer::class)
                ->with(['client.user'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // Si c'est un studio, afficher les demandes de ses artistes
        elseif ($user->hasRole('studio')) {
            $bookingRequests = BookingRequest::whereHas('bookable', function ($query) use ($user) {
                $query->where('studio_id', $user->studio->id);
            })
                ->with(['bookable.user', 'client.user'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        else {
            return response()->json(['message' => 'Rôle non autorisé'], 403);
        }

        return response()->json([
            'data' => $bookingRequests->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'client' => $booking->client ? [
                        'id' => $booking->client->id,
                        'user' => [
                            'id' => $booking->client->user->id,
                            'name' => $booking->client->user->name,
                            'email' => $booking->client->user->email,
                        ]
                    ] : null,
                    'bookable' => [
                        'id' => $booking->bookable_id,
                        'type' => class_basename($booking->bookable_type),
                        'user' => $booking->bookable->user ? [
                            'id' => $booking->bookable->user->id,
                            'name' => $booking->bookable->user->name,
                        ] : null,
                    ],
                    'status' => $booking->status,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                ];
            })
        ]);
    }

    /**
     * Créer une nouvelle demande de réservation
     */
    public function store(StoreBookingRequestRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        if (!$user || !$user->hasRole('client')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        try {
            $bookingRequest = BookingRequest::create([
                'client_id' => $user->client->id,
                'bookable_id' => $validated['bookable_id'],
                'bookable_type' => $validated['bookable_type'],
                'project_description' => $validated['project_description'],
                'estimated_duration_hours' => $validated['estimated_duration_hours'],
                'estimated_price' => $validated['estimated_price'],
                'preferred_date' => $validated['preferred_date'] ?? null,
                'preferred_period' => $validated['preferred_period'] ?? null,
                'status' => BookingRequest::STATUS_PENDING,
            ]);

            return response()->json([
                'message' => 'Demande créée avec succès',
                'data' => $bookingRequest->load(['bookable.user', 'client.user']),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création de la demande',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Afficher une demande de réservation spécifique
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $bookingRequest = BookingRequest::with(['bookable.user', 'client.user'])->find($id);

        if (!$bookingRequest) {
            return response()->json(['message' => 'Demande non trouvée'], 404);
        }

        // Vérifier les permissions
        $canView = false;
        
        if ($user->hasRole('client') && $bookingRequest->client_id === $user->client->id) {
            $canView = true;
        } elseif ($user->hasRole('tattooer') && $bookingRequest->bookable_id === $user->tattooer->id) {
            $canView = true;
        } elseif ($user->hasRole('studio') && $bookingRequest->bookable->studio_id === $user->studio->id) {
            $canView = true;
        }

        if (!$canView) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        return response()->json([
            'data' => [
                'id' => $bookingRequest->id,
                'client' => [
                    'id' => $bookingRequest->client->id,
                    'user' => [
                        'id' => $bookingRequest->client->user->id,
                        'name' => $bookingRequest->client->user->name,
                        'email' => $bookingRequest->client->user->email,
                    ]
                ],
                'bookable' => [
                    'id' => $bookingRequest->bookable_id,
                    'type' => class_basename($bookingRequest->bookable_type),
                    'user' => $bookingRequest->bookable->user ? [
                        'id' => $bookingRequest->bookable->user->id,
                        'name' => $bookingRequest->bookable->user->name,
                    ] : null,
                ],
                'project_description' => $bookingRequest->project_description,
                'estimated_duration_hours' => $bookingRequest->estimated_duration_hours,
                'estimated_price' => $bookingRequest->estimated_price,
                'preferred_date' => $bookingRequest->preferred_date,
                'preferred_period' => $bookingRequest->preferred_period,
                'status' => $bookingRequest->status,
                'created_at' => $bookingRequest->created_at,
                'updated_at' => $bookingRequest->updated_at,
            ]
        ]);
    }

    /**
     * Mettre à jour une demande de réservation
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $bookingRequest = BookingRequest::find($id);

        if (!$bookingRequest) {
            return response()->json(['message' => 'Demande non trouvée'], 404);
        }

        // Vérifier les permissions (seul le client peut modifier sa demande)
        if (!$user->hasRole('client') || $bookingRequest->client_id !== $user->client->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // Seules les demandes en attente peuvent être modifiées
        if ($bookingRequest->status !== BookingRequest::STATUS_PENDING) {
            return response()->json(['message' => 'Cette demande ne peut plus être modifiée'], 403);
        }

        $validated = $request->validate([
            'project_description' => 'sometimes|string|max:1000',
            'estimated_duration_hours' => 'sometimes|integer|min:1|max:8',
            'estimated_price' => 'sometimes|numeric|min:50|max:5000',
            'preferred_date' => 'sometimes|date|after_or_equal:today',
            'preferred_period' => 'sometimes|in:morning,afternoon',
        ]);

        $bookingRequest->update($validated);

        return response()->json([
            'message' => 'Demande mise à jour avec succès',
            'data' => $bookingRequest->fresh(['bookable.user', 'client.user']),
        ]);
    }

    /**
     * Supprimer une demande de réservation
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $bookingRequest = BookingRequest::find($id);

        if (!$bookingRequest) {
            return response()->json(['message' => 'Demande non trouvée'], 404);
        }

        // Vérifier les permissions
        $canDelete = false;
        
        if ($user->hasRole('client') && $bookingRequest->client_id === $user->client->id) {
            $canDelete = true;
        } elseif ($user->hasRole('tattooer') && $bookingRequest->bookable_id === $user->tattooer->id) {
            $canDelete = true;
        } elseif ($user->hasRole('studio') && $bookingRequest->bookable->studio_id === $user->studio->id) {
            $canDelete = true;
        }

        if (!$canDelete) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // Seules les demandes en attente peuvent être supprimées
        if ($bookingRequest->status !== BookingRequest::STATUS_PENDING) {
            return response()->json(['message' => 'Cette demande ne peut plus être supprimée'], 403);
        }

        $bookingRequest->delete();

        return response()->json([
            'message' => 'Demande supprimée avec succès',
        ]);
    }

    /**
     * Accepter une demande de réservation (tattooer)
     */
    public function accept(Request $request, $id)
    {
        $user = $request->user();
        
        if (!$user->hasRole('tattooer')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $bookingRequest = BookingRequest::with(['client.user'])->find($id);
        
        if (!$bookingRequest) {
            return response()->json(['message' => 'Demande non trouvée'], 404);
        }

        if ($bookingRequest->bookable_id !== $user->tattooer->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        if ($bookingRequest->status !== BookingRequest::STATUS_PENDING) {
            return response()->json(['message' => 'Cette demande ne peut plus être acceptée'], 403);
        }

        $validated = $request->validate([
            'estimated_price' => 'required|numeric|min:50|max:5000',
            'estimated_duration_hours' => 'required|integer|min:1|max:8',
            'deposit_rate' => 'required|integer|min:0|max:100',
            'deposit_deadline_hours' => 'required|integer|min:1|max:168',
            'proposed_dates' => 'required|array|min:1|max:3',
            'proposed_dates.*.date' => 'required|date|after_or_equal:today',
            'proposed_dates.*.period' => 'required|in:morning,afternoon',
        ]);

        try {
            $bookingRequest->update([
                'status' => BookingRequest::STATUS_ACCEPTED,
                'accepted_at' => now(),
                'estimated_price' => $validated['estimated_price'],
                'estimated_duration_hours' => $validated['estimated_duration_hours'],
                'deposit_rate' => $validated['deposit_rate'],
                'deposit_deadline_hours' => $validated['deposit_deadline_hours'],
                'proposed_dates' => $validated['proposed_dates'],
                'date_selection_deadline' => now()->addHours(48),
            ]);

            return response()->json([
                'message' => 'Demande acceptée avec succès',
                'data' => $bookingRequest->load(['bookable.user', 'client.user']),
            ]);

        } catch (\App\Exceptions\BookingException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Rejeter une demande de réservation (tattooer)
     */
    public function reject(Request $request, $id)
    {
        $user = $request->user();
        
        if (!$user->hasRole('tattooer')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $bookingRequest = BookingRequest::find($id);
        
        if (!$bookingRequest) {
            return response()->json(['message' => 'Demande non trouvée'], 404);
        }

        if ($bookingRequest->bookable_id !== $user->tattooer->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        if ($bookingRequest->status !== BookingRequest::STATUS_PENDING) {
            return response()->json(['message' => 'Cette demande ne peut plus être rejetée'], 403);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $bookingRequest->update([
            'status' => BookingRequest::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return response()->json([
            'message' => 'Demande rejetée avec succès',
        ]);
    }
}
