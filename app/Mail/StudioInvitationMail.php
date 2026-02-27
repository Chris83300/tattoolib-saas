<?php

namespace App\Mail;

use App\Models\Studio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudioInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Studio $studio,
        public string $token,
        public string $artisanType,
        public string $email
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->studio->name} vous invite à rejoindre Ink&Pik",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.studio.invitation',
            with: [
                'studio'        => $this->studio,
                'invitationUrl' => route('studio.invitation.accept', $this->token),
                'artisanType'   => $this->artisanType === 'piercer' ? 'Pierceur' : 'Tatoueur',
            ],
        );
    }
}
