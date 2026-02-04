<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Dashboard client avec demandes et messages
     */
    public function dashboard()
    {
        $client = Auth::user()->client;

        if (!$client) {
            abort(403, 'Profil client non trouvé');
        }

        // Récupérer toutes les demandes du client
        $bookingRequests = BookingRequest::where('client_id', $client->id)
            ->with('bookable', 'conversation.messages')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistiques
        $stats = [
            'total_requests' => $bookingRequests->count(),
            'pending_requests' => $bookingRequests->where('status', 'pending')->count(),
            'accepted_requests' => $bookingRequests->where('status', 'accepted')->count(),
            'in_progress_requests' => $bookingRequests->where('status', 'in_progress')->count(),
            'completed_requests' => $bookingRequests->where('status', 'completed')->count(),
        ];

        // Demandes récentes avec messages non lus
        $recentRequests = $bookingRequests->take(5);

        foreach ($recentRequests as $bookingRequest) {
            // Compter les messages non lus du tattooer
            $unreadMessages = $bookingRequest->conversation ?
                $bookingRequest->conversation->messages()
                    ->where('sender_type', 'tattooer')
                    ->whereNull('read_by_client_at')
                    ->count() : 0;

            $bookingRequest->unread_messages = $unreadMessages;
        }

        return view('client.dashboard', compact('bookingRequests', 'stats', 'recentRequests'));
    }

    /**
     * Liste des demandes du client
     */
    public function bookingRequests(Request $request)
    {
        $client = Auth::user()->client;

        if (!$client) {
            abort(403, 'Profil client non trouvé');
        }

        $query = BookingRequest::where('client_id', $client->id)
            ->with('bookable', 'conversation.messages');

        // Filtrer par statut si spécifié
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookingRequests = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('client.booking-requests', compact('bookingRequests'));
    }

    /**
     * Détails d'une demande
     */
    public function bookingRequestShow(BookingRequest $bookingRequest)
    {
        $client = Auth::user()->client;

        if (!$client || $bookingRequest->client_id !== $client->id) {
            abort(403, 'Non autorisé');
        }

        $bookingRequest->load('bookable', 'conversation.messages.sender');

        return view('client.booking-request-show', compact('bookingRequest'));
    }

    /**
     * Chat avec le tattooer
     */
    public function chat(Conversation $conversation)
    {
        $client = Auth::user()->client;

        // Vérifier que le client est participant de la conversation
        if (!$client || !$conversation->participants()->where('user_id', $client->user_id)->exists()) {
            abort(403, 'Non autorisé');
        }

        $bookingRequest = $conversation->bookingRequest;

        // Vérifier que le chat est ouvert en utilisant la méthode du modèle
        $chatOpen = $bookingRequest && $bookingRequest->isChatOpen();

        // ⭐ Récupérer les informations d'expiration pour l'affichage
        $expiryInfo = null;
        if ($conversation) {
            // Toujours récupérer les infos si la conversation existe
            $expiryInfo = [
                'expires_at' => $conversation->expires_at,
                'days_remaining' => $conversation->getDaysUntilExpiry(),
                'time_remaining' => $conversation->getTimeUntilExpiry(),
                'warning_message' => $conversation->getExpiryWarningMessage(),
                'is_expired' => $conversation->isExpired(),
                'expiry_type' => $conversation->expiry_type,
                'deposit_deadline_at' => $conversation->deposit_deadline_at,
            ];
        }

        if (!$chatOpen) {
            $messages = collect([]);
        } else {
            // Récupérer les messages de la conversation
            $messages = $conversation->messages()
                ->with('sender')
                ->orderBy('created_at', 'asc')
                ->get();

            // Marquer les messages comme lus par le client
            $messages->where('sender_type', '!=', 'client')
                ->whereNull('read_by_client_at')
                ->each(function ($message) {
                    $message->update(['read_by_client_at' => now()]);
                });
        }

        return view('client.chat', compact('conversation', 'bookingRequest', 'messages', 'chatOpen', 'expiryInfo'));
    }

    /**
     * Supprimer une demande de réservation (uniquement si rejetée)
     */
    public function bookingRequestDelete(BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;

        // Vérifier que la demande appartient au client
        if ($bookingRequest->client_id !== $client->id) {
            abort(403, 'Cette demande ne vous appartient pas.');
        }

        // Vérifier que la demande est rejetée (seul statut autorisé pour suppression)
        if ($bookingRequest->status !== 'rejected') {
            return redirect()->back()
                ->with('error', 'Seules les demandes refusées peuvent être supprimées.');
        }

        // Supprimer la conversation associée si elle existe (hard delete)
        if ($bookingRequest->conversation) {
            $bookingRequest->conversation->messages()->delete(); // Supprimer messages
            $bookingRequest->conversation->delete(); // Supprimer conversation
        }

        // Supprimer la demande (hard delete - définitif)
        $bookingRequest->forceDelete(); // Hard delete au lieu de soft delete

        return redirect()->route('client.booking-requests')
            ->with('success', 'La demande refusée a été supprimée définitivement de la base de données.');
    }

    /**
     * Annuler une demande de réservation
     */
    public function bookingRequestCancel(BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;

        // Vérifier que la demande appartient au client
        if ($bookingRequest->client_id !== $client->id) {
            abort(403, 'Cette demande ne vous appartient pas.');
        }

        // Vérifier que la demande peut être annulée
        if (!in_array($bookingRequest->status, ['pending', 'accepted'])) {
            return redirect()->back()
                ->with('error', 'Cette demande ne peut plus être annulée.');
        }

        // Mettre à jour le statut
        $bookingRequest->update([
            'status' => 'cancelled',
            'cancelled_by' => 'client',
            'cancelled_at' => now(),
            'cancellation_reason' => 'Annulation par le client',
            'chat_status' => 'closed',
        ]);

        // Fermer la conversation associée
        if ($bookingRequest->conversation) {
            $bookingRequest->conversation->update(['status' => 'closed']);
        }

        return redirect()->route('client.booking-requests')
            ->with('success', 'Votre demande a été annulée avec succès.');
    }

    /**
     * Liste des conversations du client
     */
    public function messages()
    {
        $user = auth()->user();

        // Récupérer toutes les conversations où l'utilisateur est participant
        $conversations = $user->conversations()
            ->with([
                'lastMessage.sender',
                'bookingRequest' => function ($query) {
                    $query->with([
                        'bookable.user', // Tattooer ou Pierceur
                        'client.user'
                    ]);
                },
                'participants' => function ($query) {
                    $query->select('users.id', 'users.name', 'users.email');
                }
            ])
            ->where('status', 'active')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Ajouter compteur non-lus pour chaque conversation
        $conversations->transform(function ($conversation) use ($user) {
            // Récupérer le pivot de l'utilisateur dans cette conversation
            $pivot = $conversation->participants()
                ->where('user_id', $user->id)
                ->first()
                ?->pivot;

            if ($pivot) {
                // Compter messages non lus (créés après last_read_at)
                $conversation->unread_count = $conversation->messages()
                    ->where('sender_id', '!=', $user->id)
                    ->where('sender_type', '!=', get_class($user))
                    ->where(function ($query) use ($pivot) {
                        $query->where('created_at', '>', $pivot->last_read_at ?? now()->subYears(10));
                    })
                    ->count();
            } else {
                $conversation->unread_count = 0;
            }

            return $conversation;
        });

        return view('client.messages', compact('conversations'));
    }

    /**
     * Envoyer un message au tattooer
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $client = Auth::user()->client;

        // Vérifier que le client est participant de la conversation
        if (!$client || !$conversation->participants()->where('user_id', $client->user_id)->exists()) {
            abort(403, 'Non autorisé');
        }

        $bookingRequest = $conversation->bookingRequest;

        // Vérifier que le chat est ouvert en utilisant la méthode du modèle
        $chatOpen = $bookingRequest && $bookingRequest->isChatOpen();

        if (!$chatOpen) {
            return back()->with('error', 'Le chat est fermé');
        }

        $validated = $request->validate([
            'content' => 'nullable|string|max:2000',
            'attachments.*' => 'nullable|file|mimes:jpeg,jpg,png,webp,pdf|max:10240',
        ]);

        // Conserver le contenu même si vide quand il y a des pièces jointes
        $content = $validated['content'] ?? '';

        // Si le contenu est vide mais il y a des pièces jointes, ajouter un message par défaut
        if (empty($content) && $request->hasFile('attachments')) {
            $content = 'Image envoyée';
        }

        // Bloquer les pièces jointes si l'acompte n'est pas payé
        if ($request->hasFile('attachments') && !$bookingRequest->deposit_paid_at) {
            return back()->with('error', 'Les pièces jointes ne sont autorisées qu\'après paiement de l\'acompte');
        }

        // Créer le message
        $message = $conversation->messages()->create([
            'conversation_id' => $conversation->id,
            'booking_request_id' => $bookingRequest->id, // ✅ Ajouté
            'sender_id' => Auth::id(),
            'sender_type' => 'client',
            'content' => $content,
        ]);

        // Upload pièces jointes
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $message->addMedia($file)->toMediaCollection('attachments');
            }
        }

        // Mettre à jour la conversation
        $conversation->update([
            'last_message_id' => $message->id,
            'last_message_at' => now(),
        ]);

        // TODO: Notification au tattooer
        // $bookingRequest->bookable->user->notify(new NewMessageNotification($message));

        return back()->with('success', 'Message envoyé');
    }
}
