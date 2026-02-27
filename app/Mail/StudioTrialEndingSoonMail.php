<?php

namespace App\Mail;

use App\Models\Studio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudioTrialEndingSoonMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Studio $studio) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Votre essai Ink&Pik se termine dans {$this->studio->trialDaysLeft()} jours",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.studio.trial-ending',
            with: [
                'studio'     => $this->studio,
                'daysLeft'   => $this->studio->trialDaysLeft(),
                'progress'   => $this->studio->onboardingProgress(),
                'billingUrl' => route('studio.billing'),
            ],
        );
    }
}
