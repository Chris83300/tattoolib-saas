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

        // Filtrer par statut si demandé
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
        if (!$client || !$conversation->users()->where('user_id', $client->user_id)->exists()) {
            abort(403, 'Non autorisé');
        }

        $bookingRequest = $conversation->bookingRequest;

        // Vérifier que le chat est ouvert
        $chatOpen = $bookingRequest &&
                   $bookingRequest->status === 'accepted' &&
                   $bookingRequest->accepted_at;

        if (!$chatOpen) {
            $messages = collect([]);
        } else {
            // Récupérer les messages de la conversation
            $messages = $conversation->messages()
                ->with('sender', 'media')
                ->orderBy('created_at')
                ->get();

            // Marquer les messages du tattooer comme lus
            $conversation->messages()
                ->where('sender_type', 'tattooer')
                ->update(['read_by_client_at' => now()]);
        }

        return view('client.chat', compact('conversation', 'bookingRequest', 'messages', 'chatOpen'));
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
        if (!$client || !$conversation->users()->where('user_id', $client->user_id)->exists()) {
            abort(403, 'Non autorisé');
        }

        $bookingRequest = $conversation->bookingRequest;

        // Vérifier que le chat est ouvert
        $chatOpen = $bookingRequest &&
                   $bookingRequest->status === 'accepted' &&
                   $bookingRequest->accepted_at;

        if (!$chatOpen) {
            return back()->with('error', 'Le chat est fermé');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'attachments.*' => 'nullable|file|mimes:jpeg,jpg,png,webp,pdf|max:10240',
        ]);

        // Créer le message
        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'sender_type' => 'client',
            'content' => $validated['content'],
        ]);

        // Upload pièces jointes
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $message->addMedia($file)->toMediaCollection('attachments');
            }
        }

        return back()->with('success', 'Message envoyé');
    }
}
