<?php

namespace App\Livewire;

use App\Models\BookingRequest;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectChat extends Component
{
    use WithFileUploads;

    public BookingRequest $bookingRequest;
    public $message = '';
    public $attachments = [];
    public $messages = [];

    protected $rules = [
        'message' => 'required_without:attachments|string|max:1000',
        'attachments.*' => 'file|max:10240|mimes:jpeg,png,webp,pdf',
    ];

    protected $listeners = [
        'echo:booking-request-chat.{bookingRequest.id},MessageSent' => 'refreshMessages',
        'refreshChat' => 'loadMessages',
    ];

    public function mount(BookingRequest $bookingRequest)
    {
        $this->bookingRequest = $bookingRequest;
        $this->authorize('view', $bookingRequest);
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = $this->bookingRequest->messages()
            ->with('sender', 'media') // ✅ Utilise la relation BookingRequest::messages()
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content, // ✅ Corrigé: message -> content
                    'sender_name' => $message->sender->name,
                    'sender_type' => $message->sender_type,
                    'is_me' => $message->sender_id === Auth::id(),
                    'created_at' => $message->created_at->format('H:i'),
                    'attachments' => $message->getMedia('attachments')->map(function ($media) {
                        return [
                            'id' => $media->id,
                            'url' => $media->getUrl(),
                            'name' => $media->file_name,
                            'mime_type' => $media->mime_type,
                            'is_image' => str_starts_with($media->mime_type, 'image/'),
                        ];
                    })->toArray(),
                ];
            })->toArray();
    }

    public function sendMessage()
    {
        $this->validate();

        try {
            // Créer le message avec les bonnes colonnes
            $message = Message::create([
                'conversation_id' => $this->bookingRequest->conversation->id,
                'booking_request_id' => $this->bookingRequest->id,
                'sender_id' => Auth::id(),
                'sender_type' => $this->getSenderType(),
                'content' => $this->message, // ✅ Corrigé: message -> content
            ]);

            // Upload des pièces jointes
            foreach ($this->attachments as $file) {
                $message->addMedia($file)->toMediaCollection('attachments');
            }

            // Broadcasting via Reverb
            // broadcast(new MessageSent($message))->toOthers();

            // Notification push (à implémenter)
            // $recipient = $this->getRecipient();
            // $recipient->notify(new NewMessageNotification($message));

            // Vider le formulaire
            $this->reset(['message', 'attachments']);

            // Recharger les messages
            $this->loadMessages();

            // Scroller en bas
            $this->dispatch('scrollToBottom');

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de l\'envoi du message.');
            Log::error('Send message error: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer un message avec dessin (nouveau complet ou modification).
     * Appelé depuis la modal Alpine designSendModal.
     *
     * @param string $designType     'new_design' | 'modification'
     * @param string $coverageType   'included' | 'send_free' | 'request_payment'
     * @param float|null $surchargeAmount  Montant si request_payment
     */
    public function sendDesignMessage(string $designType, string $coverageType, ?float $surchargeAmount = null): void
    {
        $bookingRequest = $this->bookingRequest;
        // Refresh pour avoir les derniers compteurs
        $bookingRequest->refresh();

        // ── 1. Mettre à jour les compteurs ──
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
            // request_payment : on ne décompte pas tant que le client n'a pas payé
            $designLabel = ($designType === 'new_design')
                ? "🎨 Nouveau dessin (supplément demandé)"
                : "✏️ Modification (supplément demandé)";
        }

        // ── 2. Créer le message avec la/les pièce(s) jointe(s) ──
        // Utiliser la logique existante d'envoi de messages
        $message = Message::create([
            'conversation_id' => $this->bookingRequest->conversation->id,
            'booking_request_id' => $this->bookingRequest->id,
            'sender_id' => Auth::id(),
            'sender_type' => $this->getSenderType(),
            'content' => $this->message ?? $designLabel,
        ]);

        // Ajouter les pièces jointes avec metadata design
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $message->addMedia($attachment)
                    ->withCustomProperties([
                        'design_type'    => $designType,
                        'coverage_type'  => $coverageType,
                        'design_number'  => $bookingRequest->designs_sent_count,
                    ])
                    ->toMediaCollection('attachments');
            }
        }

        // ── 3. Message système ──
        $systemContent = match($coverageType) {
            'included'        => "{$designLabel} envoyé (inclus dans le forfait).",
            'send_free'       => "⚠️ {$designLabel} envoyé (hors forfait — envoi gracieux).",
            'request_payment' => "💰 {$designLabel} — Supplément de {$surchargeAmount}€ demandé.",
            default           => "{$designLabel} envoyé.",
        };

        Message::create([
            'conversation_id' => $this->bookingRequest->conversation->id,
            'booking_request_id' => $this->bookingRequest->id,
            'sender_type'     => 'system',
            'sender_id'       => null,
            'content'         => $systemContent,
        ]);

        // ── 4. Si supplément demandé, mettre à jour le booking ──
        if ($coverageType === 'request_payment' && $surchargeAmount > 0) {
            $bookingRequest->update([
                'overage_decision' => 'request_payment',
                'surcharge_amount' => $surchargeAmount,
                'overage_reason'   => $designType === 'new_design'
                    ? 'Dessin complet supplémentaire hors forfait'
                    : 'Modification supplémentaire hors forfait',
            ]);
        } elseif ($coverageType === 'send_free') {
            $bookingRequest->update([
                'overage_decision' => 'send_free',
                'overage_reason'   => $designType === 'new_design'
                    ? 'Dessin complet supplémentaire offert'
                    : 'Modification supplémentaire offerte',
            ]);
        }

        // ── 5. Reset formulaire ──
        $this->message = '';
        $this->attachments = [];
        $this->loadMessages();
        $this->dispatch('message-sent');
        $this->dispatch('scrollToBottom');
    }

    /**
     * Obtenir le type d'expéditeur
     */
    private function getSenderType(): string
    {
        $user = Auth::user();

        if ($user->isTattooer() || $user->isStudioArtist() || $user->isPiercer()) {
            return 'tattooer';
        }

        return 'client';
    }

    /**
     * Obtenir le destinataire du message
     */
    private function getRecipient()
    {
        $user = Auth::user();

        if ($user->isClient()) {
            return $this->project->bookable->user;
        }

        return $this->project->client->user;
    }

    /**
     * Marquer les messages comme lus
     */
    public function markMessagesAsRead()
    {
        $this->project->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Supprimer un message
     */
    public function deleteMessage($messageId)
    {
        $message = Message::findOrFail($messageId);

        // Vérifier que l'utilisateur peut supprimer ce message
        if ($message->sender_id !== Auth::id()) {
            return;
        }

        $message->delete();
        $this->loadMessages();
    }

    /**
     * Obtenir le statut du projet
     */
    public function getProjectStatusProperty(): string
    {
        return $this->project->status_formatted;
    }

    /**
     * Obtenir le nom du client
     */
    public function getClientNameProperty(): string
    {
        return $this->project->client->full_name;
    }

    /**
     * Obtenir le nom de l'artiste
     */
    public function getArtistNameProperty(): string
    {
        return match($this->project->bookable_type) {
            'App\\Models\\Tattooer' => $this->project->bookable->user->name,
            'App\\Models\\StudioArtist' => $this->project->bookable->artist_name,
            'App\\Models\\Piercer' => $this->project->bookable->user->name,
            default => 'Artiste',
        };
    }

    /**
     * Vérifier si l'utilisateur est le tatoueur
     */
    public function getIsTattooerProperty(): bool
    {
        return Auth::user()->isTattooer() || Auth::user()->isStudioArtist() || Auth::user()->isPiercer();
    }

    /**
     * Obtenir les informations financières du projet
     */
    public function getFinancialInfoProperty(): array
    {
        return [
            'deposit_amount' => $this->project->deposit_amount,
            'deposit_paid' => $this->project->isDepositPaid(),
            'estimated_price' => $this->project->estimated_price,
            'final_price' => $this->project->final_price,
            'remaining_amount' => $this->project->remaining_amount,
        ];
    }

    public function render()
    {
        return view('livewire.project-chat');
    }
}
