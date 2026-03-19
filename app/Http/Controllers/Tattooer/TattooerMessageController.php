<?php

namespace App\Http\Controllers\Tattooer;

use App\Models\BookingRequest;
use Illuminate\Http\Request;

class TattooerMessageController extends ArtisanBaseController
{
    /**
     * Liste des conversations/messages
     */
    public function messages()
    {
        $tattooer = $this->artisan();

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Récupérer les conversations/messages
        $conversations = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', get_class($tattooer))
            ->has('conversation')
            ->with(['client.user', 'conversation.messages'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.messages', compact('tattooer', 'conversations', 'pendingCount', 'unreadCount'));
    }

    /**
     * Afficher la conversation avec un client
     */
    public function messageShow(BookingRequest $bookingRequest)
    {
        $tattooer = $this->artisan();

        // Vérifier que la demande appartient bien à l'artisan (tattooer ou piercer)
        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== get_class($tattooer)) {
            abort(403, 'Non autorisé');
        }

        // Créer la conversation si elle n'existe pas
        if (!$bookingRequest->conversation) {
            $conversation = \App\Models\Conversation::create([
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $bookingRequest->client_id,
                'tattooer_id' => $tattooer->id,
                'status' => 'active',
            ]);

            // Marquer les messages comme lus pour le tattooer
            $bookingRequest->conversation = $conversation;
        }

        // Charger la conversation avec les messages
        $conversation = $bookingRequest->conversation->load(['messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        // Marquer les messages du client comme lus
        $conversation->messages()
            ->where('sender_type', 'client')
            ->whereNull('read_by_tattooer_at')
            ->update(['read_by_tattooer_at' => now()]);

        // Marquer la conversation comme lue pour le tattooer (mettre à jour le pivot)
        $conversation->markAsRead($tattooer->user_id);

        // Récupérer les messages pour la vue
        $messages = $conversation->messages;

        // Compteurs pour le layout
        $pendingCount = \App\Models\BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', get_class($tattooer))
            ->where('status', 'pending')
            ->count();

        $unreadCount = \App\Models\Conversation::whereHas('messages', function ($query) {
                $query->where(function ($q) {
                    if (auth()->user()->isTattooer() || auth()->user()->isPiercer()) {
                        $q->whereNull('read_by_tattooer_at')
                          ->where('sender_type', 'client');
                    } else {
                        $q->whereNull('read_by_client_at')
                          ->where('sender_type', 'tattooer');
                    }
                });
            })
            ->whereHas('bookingRequest', function($query) use ($tattooer) {
                $query->where('bookable_id', $tattooer->id)
                    ->where('bookable_type', get_class($tattooer));
            })
            ->count();

        return view('tattooer.message-show', compact('bookingRequest', 'conversation', 'messages', 'tattooer', 'pendingCount', 'unreadCount'));
    }

    /**
     * Envoyer un message dans la conversation (avec gestion des dessins)
     */
    public function messageSend(Request $request, BookingRequest $bookingRequest)
    {
        $tattooer = $this->artisan();

        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== get_class($tattooer)) {
            abort(403, 'Non autorisé');
        }

        $validated = $request->validate([
            'content' => 'nullable|string|max:2000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,jpg,png,gif,webp,pdf|max:10240',
            'design_type' => 'nullable|in:new_design,modification',
            'coverage_type' => 'nullable|in:included,send_free',
        ]);

        // Vérifier qu'il y a du contenu ou des pièces jointes
        if (empty($validated['content']) && !$request->hasFile('attachments')) {
            return redirect()->back()->with('error', 'Veuillez entrer un message ou ajouter une pièce jointe');
        }

        // Protection : bloquer l'upload d'images pour les utilisateurs FREE
        if ($tattooer->isFree() && $request->hasFile('attachments')) {
            return redirect()->back()->with('error', '🔒 L\'envoi d\'images est réservé au plan PRO. <a href="' . route($this->routePrefix() . '.subscription.plans') . '" class="text-beige-peau underline">Passer PRO</a> pour débloquer cette fonctionnalité.');
        }

        // Créer la conversation si elle n'existe pas
        if (!$bookingRequest->conversation) {
            $conversation = \App\Models\Conversation::create([
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $bookingRequest->client_id,
                'tattooer_id' => $tattooer->id,
                'status' => 'active',
            ]);
            $bookingRequest->setRelation('conversation', $conversation);
        }

        // Créer le message
        $messageContent = $validated['content'] ?? '';
        $designLabel = null;

        // ═══ DÉTERMINER LE LABEL DE TRACKING AVANT DE CRÉER LE MESSAGE ═══
        $designType = $validated['design_type'] ?? null;
        $coverageType = $validated['coverage_type'] ?? null;

        if ($designType && $request->hasFile('attachments')) {
            $bookingRequest->refresh();

            // Mettre à jour les compteurs
            if ($coverageType === 'included' || $coverageType === 'send_free') {
                if ($designType === 'new_design') {
                    $bookingRequest->recordNewDesign();
                    $designLabel = "🎨 Dessin complet #{$bookingRequest->designs_sent_count}";
                } else {
                    $bookingRequest->recordModification();
                    $tracker = $bookingRequest->design_modifications_tracker ?? [];
                    $currentDesign = $bookingRequest->designs_sent_count;
                    $modifCount = $tracker[(string) $currentDesign] ?? 0;
                    $designLabel = "✏️ Modification #{$modifCount} du dessin #{$currentDesign}";
                }
            } else {
                $designLabel = ($designType === 'new_design')
                    ? "🎨 Nouveau dessin (supplément demandé)"
                    : "✏️ Modification (supplément demandé)";
            }

            // Message système de tracking
            $systemContent = match($coverageType) {
                'included'  => "{$designLabel} envoyé (inclus dans le forfait).",
                'send_free' => "⚠️ {$designLabel} envoyé (hors forfait — envoi gracieux).",
                default     => "{$designLabel} envoyé.",
            };
        }

        // ═══ CRÉER UN SEUL MESSAGE AVEC CONTENU + LABEL ═══
        $finalContent = $messageContent;
        if ($designLabel && $request->hasFile('attachments')) {
            if (empty(trim($messageContent))) {
                // Pas de contenu utilisateur → utiliser le label comme contenu principal
                $finalContent = match($coverageType) {
                    'included'  => "{$designLabel} envoyé (inclus dans le forfait).",
                    'send_free' => "⚠️ {$designLabel} envoyé (hors forfait — envoi gracieux).",
                    default     => "{$designLabel} envoyé.",
                };
            } else {
                // Contenu utilisateur présent → ajouter le label en préfixe
                $finalContent = match($coverageType) {
                    'included'  => "{$designLabel} envoyé (inclus dans le forfait).\n\n{$messageContent}",
                    'send_free' => "⚠️ {$designLabel} envoyé (hors forfait — envoi gracieux).\n\n{$messageContent}",
                    default     => "{$designLabel} envoyé.\n\n{$messageContent}",
                };
            }
        }

        $message = \App\Models\Message::create([
            'conversation_id' => $bookingRequest->conversation->id,
            'sender_type' => 'tattooer',
            'sender_id' => $tattooer->user_id,
            'content' => $finalContent,
            'read_by_client_at' => null,
            'read_by_tattooer_at' => now(),
        ]);

        // Gérer les pièces jointes
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $message->addMedia($attachment)
                    ->withCustomProperties([
                        'design_type' => $validated['design_type'] ?? null,
                        'coverage_type' => $validated['coverage_type'] ?? null,
                        'uploaded_by' => 'tattooer',
                    ])
                    ->toMediaCollection('attachments');
            }
        }

        // ═══ GESTION DU COMPTAGE DESSINS (déjà fait plus haut) ═══
        // Le tracking est déjà intégré dans le message unique ci-dessus

        // Si envoi gratuit hors forfait
        if ($coverageType === 'send_free') {
            $bookingRequest->update([
                'overage_decision' => 'send_free',
                'overage_reason'   => $designType === 'new_design'
                    ? 'Dessin complet supplémentaire offert'
                    : 'Modification supplémentaire offerte',
            ]);
        }

        return redirect()->route($this->routePrefix() . '.message.show', $bookingRequest)
            ->with('success', 'Message envoyé !');
    }
}
