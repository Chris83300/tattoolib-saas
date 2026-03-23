<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UnreadMessagesComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Uniquement pour les tattooers
        if (!$user->tattooer) {
            return;
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
        ->with(['participants', 'messages' => function ($query) use ($user) {
            $query->where('sender_id', '!=', $user->id);
        }])
        ->get();

        // Compter les messages non-lus
        $unreadCount = 0;
        $unreadConversations = [];

        foreach ($conversations as $conversation) {
            // Compter les messages non-lus avec read_by_tattooer_at IS NULL
            $conversationUnread = $conversation->messages
                ->where('sender_type', 'client')
                ->whereNull('read_by_tattooer_at')
                ->count();

            if ($conversationUnread > 0) {
                $unreadCount += $conversationUnread;
                $unreadConversations[] = $conversation->id;
            }
        }

        $view->with([
            'unreadCount' => $unreadCount,
            'unreadConversations' => $unreadConversations,
            'pendingCount' => $pendingCount
        ]);
    }
}
