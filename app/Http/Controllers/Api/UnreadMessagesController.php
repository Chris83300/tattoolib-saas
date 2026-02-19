<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UnreadMessagesController extends Controller
{
    /**
     * Récupérer le nombre de messages non-lus pour l'utilisateur connecté
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user || !$user->tattooer) {
            return response()->json([
                'unreadCount' => 0,
                'pendingCount' => 0
            ]);
        }

        $tattooer = $user->tattooer;

        // Récupérer les demandes en attente
        $pendingCount = \App\Models\BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\\Models\\Tattooer')
            ->where('status', 'pending')
            ->count();

        // Récupérer toutes les conversations du tattooer avec eager loading
        $conversations = \App\Models\Conversation::whereHas('bookingRequest', function ($query) use ($tattooer) {
            $query->where('bookable_type', 'App\\Models\\Tattooer')
                  ->where('bookable_id', $tattooer->id);
        })
        ->with(['participants.pivot', 'messages' => function ($query) use ($user) {
            $query->where('sender_id', '!=', $user->id);
        }])
        ->get();

        // Compter les messages non-lus
        $unreadCount = 0;

        foreach ($conversations as $conversation) {
            $pivot = $conversation->participants()
                ->where('user_id', $user->id)
                ->first()?->pivot;

            $lastReadAt = $pivot?->last_read_at ?? now()->subYears(10);

            $unreadCount += $conversation->messages
                ->where('created_at', '>', $lastReadAt)
                ->count();
        }

        return response()->json([
            'unreadCount' => $unreadCount,
            'pendingCount' => $pendingCount
        ]);
    }
}
