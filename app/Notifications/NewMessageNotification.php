<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Message $message) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'fcm']; // Ajoutez d'autres canaux si nécessaire
    }

    public function toFcm($notifiable)
    {
        $senderName = $this->message->sender->name;
        $messagePreview = $this->message->message
            ? str_limit($this->message->message, 100)
            : 'Vous avez reçu une pièce jointe';

        return [
            'title' => $senderName,
            'body' => $messagePreview,
            'click_action' => route('project.chat.show', $this->message->project_id),
            'data' => [
                'type' => 'new_message',
                'project_id' => $this->message->project_id,
                'message_id' => $this->message->id,
                'sender_name' => $senderName,
            ],
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $senderName = $this->message->sender->name;
        $messagePreview = $this->message->message
            ? str_limit($this->message->message, 100)
            : 'Vous avez reçu une pièce jointe';

        return (new MailMessage)
            ->subject('💬 Nouveau message de ' . $senderName)
            ->greeting('Bonjour,')
            ->line($senderName . ' vous a envoyé un nouveau message :')
            ->line('"' . $messagePreview . '"')
            ->action('Voir la conversation', route('project.chat.show', $this->message->project_id))
            ->line('Connectez-vous pour répondre à ce message.')
            ->line('Merci d\'utiliser Ink&Pik !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message_id' => $this->message->id,
            'project_id' => $this->message->project_id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'sender_type' => $this->message->sender_type,
            'message_preview' => $this->message->message
                ? str_limit($this->message->message, 100)
                : 'Pièce jointe',
            'sent_at' => $this->message->created_at,
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'message_id' => $this->message->id,
            'project_id' => $this->message->project_id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'sender_type' => $this->message->sender_type,
            'message_preview' => $this->message->message
                ? str_limit($this->message->message, 100)
                : 'Pièce jointe',
            'sent_at' => $this->message->created_at,
        ]);
    }
}
