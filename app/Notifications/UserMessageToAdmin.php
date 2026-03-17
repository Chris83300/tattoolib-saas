<?php
namespace App\Notifications;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserMessageToAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $message,
        public User $sender,
        public Conversation $conversation,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database']; // pas de mail pour les messages support internes
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'            => 'user_support_message',
            'message'         => \Str::limit($this->message, 100),
            'sender_name'     => $this->sender->name ?? $this->sender->pseudo ?? 'Utilisateur',
            'sender_id'       => $this->sender->id,
            'conversation_id' => $this->conversation->id,
            'url'             => '/admin/support-chat',
        ];
    }
}
