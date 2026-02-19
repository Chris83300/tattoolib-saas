<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Liste des messages d'une conversation
     */
    public function index(Request $request, Conversation $conversation)
    {
        Gate::authorize('view', $conversation);

        $messages = $conversation->messages()
            ->with(['sender:id,name,email', 'media'])
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        // Transformer pour inclure les URLs des médias
        $messages->getCollection()->transform(function ($message) {
            if ($message->hasMedia('attachments')) {
                $message->attachment_url = $message->getFirstMediaUrl('attachments');
                $message->attachment_thumbnail = $message->getFirstMediaUrl('attachments', 'thumb');
            }
            return $message;
        });

        return response()->json($messages);
    }

    /**
     * Envoyer un nouveau message
     */
    public function store(StoreMessageRequest $request, Conversation $conversation)
    {
        Gate::authorize('sendMessage', $conversation);

        // Vérifier que la conversation est active
        if ($conversation->status !== 'active') {
            return response()->json([
                'message' => 'Cette conversation est fermée'
            ], 422);
        }

        // Vérifier si l'acompte est expiré
        if ($conversation->bookingRequest && $conversation->bookingRequest->isDepositExpired()) {
            return response()->json([
                'message' => 'Le délai d\'acompte est expiré. Impossible d\'envoyer des messages.'
            ], 422);
        }

        $user = $request->user();
        $userType = $user->getUserType();

        // Créer le message
        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'sender_type' => $userType,
            'content' => $request->content,
            'booking_request_id' => $conversation->booking_request_id,
            'is_design_version' => $request->boolean('is_design_version', false),
            'design_version_number' => $request->design_version_number,
        ]);

        // Gérer la pièce jointe si présente
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');

            // Validation déjà faite par SecureFileUpload middleware
            // Scan antivirus
            app(\App\Services\AntivirusService::class)->scan($file);

            $attachmentType = $this->determineAttachmentType($file);
            $message->update(['attachment_type' => $attachmentType]);
            $message->addAttachment($file, $attachmentType);
        }

        // Si c'est une version de design, incrémenter le compteur
        if ($message->is_design_version && $conversation->bookingRequest) {
            $conversation->bookingRequest->increment('design_versions_used');
        }

        // Charger les relations pour la réponse
        $message->load(['sender:id,name,email', 'media']);

        if ($message->hasMedia('attachments')) {
            $message->attachment_url = $message->getFirstMediaUrl('attachments');
            $message->attachment_thumbnail = $message->getFirstMediaUrl('attachments', 'thumb');
        }

        return response()->json([
            'message' => 'Message envoyé',
            'data' => $message,
        ], 201);
    }

    /**
     * Supprimer un message (soft delete)
     */
    public function destroy(Request $request, Conversation $conversation, Message $message)
    {
        Gate::authorize('delete', $message);

        // Vérifier que le message appartient à la conversation
        if ($message->conversation_id !== $conversation->id) {
            return response()->json([
                'message' => 'Message non trouvé dans cette conversation'
            ], 404);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message supprimé',
        ]);
    }

    /**
     * Récupérer les versions de design d'une conversation
     */
    public function designVersions(Request $request, Conversation $conversation)
    {
        Gate::authorize('view', $conversation);

        $designMessages = $conversation->messages()
            ->where('is_design_version', true)
            ->with(['sender:id,name', 'media'])
            ->orderBy('design_version_number', 'asc')
            ->get();

        // Ajouter les URLs des médias
        $designMessages->transform(function ($message) {
            if ($message->hasMedia('attachments')) {
                $message->attachment_url = $message->getFirstMediaUrl('attachments');
                $message->attachment_preview = $message->getFirstMediaUrl('attachments', 'preview');
            }
            return $message;
        });

        return response()->json([
            'design_versions' => $designMessages,
            'total_used' => $designMessages->count(),
            'total_included' => $conversation->bookingRequest->included_design_versions ?? 3,
        ]);
    }

    /**
     * Télécharger une pièce jointe
     */
    public function downloadAttachment(Request $request, Message $message)
    {
        Gate::authorize('view', $message->conversation);

        if (!$message->hasMedia('attachments')) {
            return response()->json([
                'message' => 'Aucune pièce jointe trouvée'
            ], 404);
        }

        $media = $message->getFirstMedia('attachments');
        abort_if(!$media, 404);

        // URL signée temporaire (expire 1h)
        return response()->download(
            $media->getPath(),
            $media->file_name,
            ['Content-Type' => $media->mime_type]
        );
    }

    /**
     * Déterminer le type de pièce jointe
     */
    private function determineAttachmentType($file): string
    {
        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        return 'document';
    }

    /**
     * Rechercher dans les messages d'une conversation
     */
    public function search(Request $request, Conversation $conversation)
    {
        Gate::authorize('view', $conversation);

        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $messages = $conversation->messages()
            ->where('content', 'like', '%' . $request->query . '%')
            ->with(['sender:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($messages);
    }
}
