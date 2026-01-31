<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectChat extends Component
{
    use WithFileUploads;

    public Project $project;
    public $message = '';
    public $attachments = [];
    public $messages = [];

    protected $rules = [
        'message' => 'required_without:attachments|string|max:1000',
        'attachments.*' => 'file|max:10240|mimes:jpeg,png,webp,pdf',
    ];

    protected $listeners = [
        'echo:project-chat.{project.id},MessageSent' => 'refreshMessages',
        'refreshChat' => 'loadMessages',
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->authorize('view', $project);
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = $this->project->messages()
            ->with('sender', 'media')
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->message,
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
            // Créer le message
            $message = Message::create([
                'project_id' => $this->project->id,
                'sender_id' => Auth::id(),
                'sender_type' => $this->getSenderType(),
                'message' => $this->message,
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
