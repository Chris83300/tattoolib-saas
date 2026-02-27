<?php

namespace App\Console\Commands;

use App\Mail\StudioTrialEndingSoonMail;
use App\Models\Studio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendStudioTrialReminders extends Command
{
    protected $signature = 'studios:send-trial-reminders';
    protected $description = 'Envoie des rappels aux studios dont le trial expire dans 4 jours';

    public function handle(): int
    {
        $studios = Studio::whereNotNull('trial_ends_at')
            ->whereDate('trial_ends_at', now()->addDays(4)->toDateString())
            ->whereDoesntHave('subscriptions', function ($q) {
                $q->where('name', 'studio')->where('stripe_status', 'active');
            })
            ->with('user')
            ->get();

        $count = 0;
        foreach ($studios as $studio) {
            $email = $studio->email ?? $studio->user?->email;
            if (!$email) {
                continue;
            }

            Mail::to($email)->send(new StudioTrialEndingSoonMail($studio));
            $count++;
            $this->info("Rappel envoyé à {$studio->name} ({$email})");
        }

        $this->info("Total : {$count} rappel(s) envoyé(s)");
        return Command::SUCCESS;
    }
}
