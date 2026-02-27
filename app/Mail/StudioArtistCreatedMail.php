<?php

namespace App\Mail;

use App\Models\Studio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudioArtistCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Studio $studio,
        public string $name,
        public string $email,
        public string $tempPassword,
        public string $artisanType
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Votre compte Ink&Pik a été créé par {$this->studio->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.studio.artist-created',
            with: [
                'studio'       => $this->studio,
                'name'         => $this->name,
                'email'        => $this->email,
                'tempPassword' => $this->tempPassword,
                'artisanType'  => $this->artisanType === 'piercer' ? 'Pierceur' : 'Tatoueur',
                'loginUrl'     => route('login'),
            ],
        );
    }
}
