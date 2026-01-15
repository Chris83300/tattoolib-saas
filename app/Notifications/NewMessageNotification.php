<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Message $message)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'fcm']; // Ajoutez d'autres canaux si nécessaire
    }
    public function toFcm($notifiable)
    {
        return [
            'title' => $this->message->sender->name,
            'body' => $this->message->content,
            'click_action' => 'YOUR_FRONTEND_URL/chat', // URL à ouvrir au clic
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau message dans la conversation')
            ->line($this->message->sender->name . ' vous a envoyé un message')
            ->action('Voir la conversation', route('conversations.show', $this->message->conversation_id))
            ->line('Merci d\'utiliser notre application !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'preview' => str($this->message->content)->limit(100),
            'sent_at' => $this->message->created_at,
        ];
    }
}
