<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Enums\BookingRequestStatus;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientMessageController extends Controller
{
    /**
     * Chat avec le tattooer
     */
    public function chat(Conversation $conversation)
    {
        $client = Auth::user()->client;

        if (!$client) {
            abort(403, 'Seuls les clients peuvent accéder au chat');
        }

        Log::debug('ClientMessageController::chat', [
            'conversation_id' => $conversation->id,
            'auth_user_id' => Auth::user()->id,
            'client_id' => $client ? $client->id : 'null',
        ]);

        // Vérifier que le client est bien le propriétaire de la demande associée
        $bookingRequest = $conversation->bookingRequest;
        if (!$bookingRequest || $bookingRequest->client_id !== $client->id) {
            Log::warning('ClientMessageController::chat - ACCESS DENIED', [
                'booking_request_client_id' => $bookingRequest ? $bookingRequest->client_id : 'null',
                'auth_client_id' => $client ? $client->id : 'null',
                'booking_request_id' => $bookingRequest ? $bookingRequest->id : 'null',
            ]);
            abort(403, 'Non autorisé - Cette conversation ne vous appartient pas.');
        }

        // Vérifier si la conversation est expirée ou fermée
        if ($conversation->isClosed() || $conversation->is_expired) {
            // Rediriger vers la marketplace avec un message d'erreur
            return redirect()->route('marketplace.index')
                ->with('error', '⏰ Cette conversation a expiré car le délai de paiement de l\'acompte est dépassé. Vous pouvez soumettre une nouvelle demande de réservation depuis la marketplace.');
        }

        // Vérifier si la demande de réservation est expirée
        if ($bookingRequest->status === BookingRequestStatus::EXPIRED) {
            return redirect()->route('marketplace.index')
                ->with('error', '⏰ Cette demande de réservation a expiré. Vous pouvez soumettre une nouvelle demande depuis la marketplace.');
        }

        // Récupérer les informations d'expiration pour l'affichage
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

        // Récupérer les messages de la conversation (la vue gérera l'affichage conditionnel avec @can)
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

        return view('client.chat', compact('conversation', 'bookingRequest', 'messages', 'expiryInfo'));
    }

    /**
     * Liste des conversations du client
     */
    public function messages()
    {
        $conversations = auth()->user()
            ->conversations()                          // via pivot conversation_user (users.id)
            ->where('status', 'active')
            ->whereNull('deleted_at')                  // soft delete
            ->with([
                'bookingRequest.bookable.user',        // artiste (pour avatar + nom)
                'messages' => function ($query) {
                    $query->latest()->limit(1); // dernier message pour aperçu
                },
            ])
            ->orderByDesc('last_message_at')
            ->get();

        return view('client.messages', compact('conversations'));
    }

    /**
     * Liste des conversations (pour navigation)
     */
    public function conversationsList()
    {
        $user = auth()->user();
        $client = $user->client;

        // Récupérer toutes les conversations où le client est participant
        $conversations = $client->conversations()
            ->with([
                'lastMessage.sender',
                'bookingRequest' => function ($query) {
                    $query->with([
                        'bookable.user', // Tattooer ou Piercer
                        'client.user'
                    ]);
                }
            ])
            ->where('status', 'active')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Ajouter compteur non-lus pour chaque conversation
        $conversations->transform(function ($conversation) use ($client) {
            // Récupérer le pivot de l'utilisateur dans cette conversation
            $pivot = $conversation->pivot; // Disponible grâce à belongsToMany

            if ($pivot) {
                // Compter messages non lus (créés après last_read_at)
                $conversation->unread_count = $conversation->messages()
                    ->where('sender_id', '!=', $client->id)
                    ->where('sender_type', '!=', get_class($client))
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

        // Vérifier que le client est bien le propriétaire de la demande associée
        $bookingRequest = $conversation->bookingRequest;
        if (!$bookingRequest || $bookingRequest->client_id !== $client->id) {
            abort(403, 'Non autorisé - Cette conversation ne vous appartient pas.');
        }

        // Vérifier que le chat est ouvert avec les Policies
        // La vue utilise déjà @can(), ici on vérifie juste l'autorisation de base
        if (!$conversation || $bookingRequest->client_id !== $client->id) {
            return back()->with('error', 'Non autorisé');
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
            'booking_request_id' => $bookingRequest->id,
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

        // Notifier l'artiste du nouveau message
        if ($bookingRequest->bookable?->user) {
            $bookingRequest->bookable->user->notify(new NewMessageNotification($message));
        }

        return back()->with('success', 'Message envoyé');
    }
}
