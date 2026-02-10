<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Actions\ConfirmAppointmentDate;
use App\Actions\RequestAlternativeDate;
use App\Enums\BookingRequestStatus;

class ConversationManagement extends Component
{
    #[Layout('components.layouts.livewire-site')]
    #[Title('Gestion de la conversation - Ink&Pik')]
    public $bookingRequest;
    public $messageContent = '';
    public $attachments = [];
    
    protected $rules = [
        'messageContent' => 'required_without:attachments|string|max:2000',
        'attachments.*' => 'nullable|file|mimes:jpeg,jpg,png,webp|max:10240',
    ];
    
    protected $listeners = [
        'echo:booking-request-chat.{bookingRequest.id},MessageSent' => 'refreshMessages',
        'refreshChat' => 'loadMessages',
    ];
    
    public function mount($bookingRequestId)
    {
        $this->bookingRequest = auth()->user()->tattooer->bookingRequests()
            ->with(['client'])
            ->findOrFail($bookingRequestId);
        $this->authorize('view', $this->bookingRequest);
        $this->loadMessages();
    }
    
    public function sendMessage()
    {
        $this->validate();
        
        // Vérifier les permissions via les Policies
        $this->authorize('sendMessage', $this->bookingRequest->conversation);
        
        // Créer le message
        $message = $this->bookingRequest->conversation->messages()->create([
            'conversation_id' => $this->bookingRequest->conversation->id,
            'booking_request_id' => $this->bookingRequest->id,
            'sender_id' => auth()->id(),
            'sender_type' => $this->getSenderType(),
            'content' => $this->messageContent,
        ]);
        
        // Upload des pièces jointes
        foreach ($this->attachments as $file) {
            $message->addMedia($file)->toMediaCollection('attachments');
        }
        
        // Utiliser le service TrackDesignDelivery
        if ($this->bookingRequest->status === \App\Enums\BookingRequestStatus::DEPOSIT_PAID) {
            $trackDesignDelivery = app(\App\Services\TrackDesignDelivery::class);
            $trackDesignDelivery->execute($message, $this->bookingRequest);
        }
        
        // Rafraîchir
        $this->messageContent = '';
        $this->attachments = [];
        $this->dispatch('messageSent');
        
        session()->flash('success', 'Message envoyé avec succès !');
    }
    
    public function selectDate(string $date, string $period)
    {
        $this->authorize('confirmDate', $this->bookingRequest);
        
        $action = app(ConfirmAppointmentDate::class);
        $action->execute($this->bookingRequest, $date, $period);
        $this->bookingRequest->refresh();
    }
    
    public function requestAlternativeDate()
    {
        $this->authorize('requestAlternativeDate', $this->bookingRequest);
        
        $action = app(RequestAlternativeDate::class);
        $action->execute($this->bookingRequest, 'Les dates proposées ne me conviennent pas.');
        $this->bookingRequest->refresh();
    }
    
    public function loadMessages()
    {
        $this->messages = $this->bookingRequest->conversation->messages()
            ->with(['sender', 'media'])
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sender_name' => $message->sender->name,
                    'sender_type' => $message->sender_type,
                    'is_me' => $message->sender_id === auth()->id(),
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
    
    private function getSenderType(): string
    {
        $user = auth()->user();
        
        if ($user->isTattooer() || $user->isStudioArtist() || $user->isPiercer()) {
            return 'tattooer';
        }
        
        return 'client';
    }
    
    public function render()
    {
        return view('livewire.tattooer.conversation-management', [
            'bookingRequest' => $this->bookingRequest,
            'messages' => $this->messages,
            'messageContent' => $this->messageContent,
            'attachments' => $this->attachments,
        ]);
    }
}
