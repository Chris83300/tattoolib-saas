<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Message $message) {}

    public function via(object $notifiable): array
    {
        // FCM retiré : canal non implémenté (FcmChannel manquant → exception)
        // À réactiver quand app/Channels/FcmChannel.php sera créé
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $senderName    = $this->message->sender->name;
        $messagePreview = $this->message->content
            ? Str::limit($this->message->content, 100)
            : 'Vous avez reçu une pièce jointe';

        return (new MailMessage)
            ->subject('💬 Nouveau message de ' . $senderName)
            ->greeting('Bonjour,')
            ->line($senderName . ' vous a envoyé un nouveau message :')
            ->line('"' . $messagePreview . '"')
            ->action('Voir la conversation', route('conversation.chat.show', $this->message->conversation_id))
            ->line('Connectez-vous pour répondre à ce message.')
            ->line('Merci d\'utiliser Ink&Pik !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message_id'      => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id'       => $this->message->sender_id,
            'sender_name'     => $this->message->sender->name,
            'sender_type'     => $this->message->sender_type,
            'message_preview' => $this->message->content
                ? Str::limit($this->message->content, 100)
                : 'Pièce jointe',
            'sent_at'         => $this->message->created_at,
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'message_id'      => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id'       => $this->message->sender_id,
            'sender_name'     => $this->message->sender->name,
            'sender_type'     => $this->message->sender_type,
            'message_preview' => $this->message->content
                ? Str::limit($this->message->content, 100)
                : 'Pièce jointe',
            'sent_at'         => $this->message->created_at,
        ]);
    }
}
