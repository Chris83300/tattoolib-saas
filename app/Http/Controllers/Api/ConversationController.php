<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ConversationController extends Controller
{
    /**
     * Liste des conversations de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->with([
                'lastMessage.sender',
                'participants' => function ($query) {
                    $query->select('users.id', 'users.name', 'users.email');
                },
                'bookingRequest' => function ($query) {
                    $query->select('id', 'tattoo_size', 'body_zone', 'status');
                }
            ])
            ->where('status', 'active')
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        // Ajouter le compteur de non-lus pour chaque conversation
        $conversations->getCollection()->transform(function ($conversation) use ($user) {
            $pivot = $conversation->participants()
                ->where('user_id', $user->id)
                ->first()
                ->pivot;

            $conversation->unread_count = $conversation->messages()
                ->where('sender_id', '!=', $user->id)
                ->where(function ($query) use ($pivot) {
                    $query->whereNull('created_at')
                        ->orWhere('created_at', '>', $pivot->last_read_at ?? now()->subYears(10));
                })
                ->count();

            $conversation->is_muted = $pivot->is_muted;
            $conversation->user_role = $pivot->role;

            return $conversation;
        });

        return response()->json($conversations);
    }

    /**
     * Afficher une conversation spécifique
     */
    public function show(Request $request, Conversation $conversation)
    {
        // Vérifier que l'utilisateur est participant
        Gate::authorize('view', $conversation);

        $conversation->load([
            'participants' => function ($query) {
                $query->select('users.id', 'users.name', 'users.email');
            },
            'bookingRequest.client.user',
            'bookingRequest.tattooer.user',
        ]);

        // Marquer comme lu
        $conversation->markAsRead($request->user()->id);

        return response()->json($conversation);
    }

    /**
     * Marquer une conversation comme lue
     */
    public function markAsRead(Request $request, Conversation $conversation)
    {
        Gate::authorize('view', $conversation);

        $conversation->markAsRead($request->user()->id);

        return response()->json([
            'message' => 'Conversation marquée comme lue',
            'conversation_id' => $conversation->id,
        ]);
    }

    /**
     * Activer/désactiver les notifications pour une conversation
     */
    public function toggleMute(Request $request, Conversation $conversation)
    {
        Gate::authorize('view', $conversation);

        $participant = $conversation->participants()
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$participant) {
            return response()->json([
                'message' => 'Vous n\'êtes pas participant de cette conversation'
            ], 403);
        }

        $isMuted = !$participant->pivot->is_muted;

        $conversation->participants()->updateExistingPivot($request->user()->id, [
            'is_muted' => $isMuted
        ]);

        return response()->json([
            'message' => $isMuted ? 'Notifications désactivées' : 'Notifications activées',
            'is_muted' => $isMuted,
        ]);
    }

    /**
     * Archiver une conversation
     */
    public function archive(Request $request, Conversation $conversation)
    {
        Gate::authorize('update', $conversation);

        $conversation->update(['status' => 'archived']);

        return response()->json([
            'message' => 'Conversation archivée',
            'conversation' => $conversation,
        ]);
    }

    /**
     * Bloquer une conversation
     */
    public function block(Request $request, Conversation $conversation)
    {
        Gate::authorize('update', $conversation);

        $conversation->update(['status' => 'blocked']);

        return response()->json([
            'message' => 'Conversation bloquée',
            'conversation' => $conversation,
        ]);
    }

    /**
     * Récupérer les conversations archivées
     */
    public function archived(Request $request)
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->with(['lastMessage.sender', 'participants'])
            ->where('status', 'archived')
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return response()->json($conversations);
    }
}
