<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Enums\BookingRequestStatus;

class ClientDashboardController extends Controller
{
    /**
     * Dashboard client avec demandes et messages
     */
    public function dashboard()
    {
        $client = auth()->user()->client;

        if (!$client) {
            abort(403, 'Profil client non trouvé');
        }

        // UNE SEULE requête avec eager loading avancé et withCount
        $bookingRequests = BookingRequest::where('client_id', $client->id)
            ->with([
                'bookable.user', // Charger le tatoueur en même temps
                'conversation' => function($query) {
                    $query->withCount(['messages as unread_count' => function($q) {
                        $q->where('sender_type', 'tattooer')
                              ->whereNull('read_by_client_at');
                    }]);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Stats calculées à partir de la collection (pas de requêtes supplémentaires)
        $stats = [
            'total_requests' => $bookingRequests->count(),
            'pending' => $bookingRequests->where('status', BookingRequestStatus::PENDING->value)->count(),
            'accepted' => $bookingRequests->whereIn('status', [
                BookingRequestStatus::ACCEPTED->value,
                BookingRequestStatus::DEPOSIT_REQUESTED->value,
            ])->count(),
            'active' => $bookingRequests->whereIn('status', [
                BookingRequestStatus::DEPOSIT_PAID->value,
                BookingRequestStatus::DATE_CONFIRMED->value,
            ])->count(),
            'completed' => $bookingRequests->where('status', BookingRequestStatus::COMPLETED->value)->count(),
            'unread_messages' => $bookingRequests->sum('conversation.unread_count'),
        ];

        // Prendre les 5 plus récents APRÈS le chargement (évite 2ème requête)
        $recentBookingRequests = $bookingRequests->take(5);

        return view('client.dashboard', compact('bookingRequests', 'stats', 'recentBookingRequests'));
    }
}
