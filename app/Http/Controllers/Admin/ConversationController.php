<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    /**
     * Afficher une conversation (pour les admins)
     */
    public function show(Conversation $conversation)
    {
        // Vérifier que l'utilisateur est bien un admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs');
        }

        Log::debug('AdminConversationController::show', [
            'conversation_id' => $conversation->id,
            'admin_user_id' => Auth::user()->id,
        ]);

        // Charger la conversation avec tous les messages et les participants
        $conversation->load([
            'messages.sender',
            'bookingRequest.client.user',
            'bookingRequest.bookable.user',
            'participants',
        ]);

        return view('admin.conversation-show', compact('conversation'));
    }
}
