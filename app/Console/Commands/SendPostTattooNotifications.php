<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use App\Notifications\PostTattooCareNotification;
use App\Notifications\HealingCheckNotification;
use App\Notifications\RequestReviewNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPostTattooNotifications extends Command
{
    protected $signature = 'app:send-post-tattoo-notifications';
    protected $description = 'Envoie les notifications post-tattoo (soins 2h, suivi J+7, avis J+14)';

    public function handle(): int
    {
        $sent = 0;

        // 1. PostTattooCare : 2h après fin du RDV
        $careAppointments = Appointment::where('status', AppointmentStatus::COMPLETED)
            ->whereNull('care_notification_sent_at')
            ->where('end_datetime', '<=', now()->subHours(2))
            ->with('bookingRequest.client.user')
            ->get();

        foreach ($careAppointments as $apt) {
            $client = $apt->bookingRequest?->client?->user;
            if ($client && class_exists(PostTattooCareNotification::class)) {
                $client->notify(new PostTattooCareNotification($apt));
                $apt->update(['care_notification_sent_at' => now()]);
                $sent++;
                $this->info("💊 PostTattooCare envoyé → {$client->pseudo} (Apt #{$apt->id})");
            }
        }

        // 2. HealingCheck : J+7
        $healingAppointments = Appointment::where('status', AppointmentStatus::COMPLETED)
            ->whereNull('healing_notification_sent_at')
            ->whereNotNull('care_notification_sent_at') // care déjà envoyé
            ->where('end_datetime', '<=', now()->subDays(7))
            ->with('bookingRequest.client.user')
            ->get();

        foreach ($healingAppointments as $apt) {
            $client = $apt->bookingRequest?->client?->user;
            if ($client && class_exists(HealingCheckNotification::class)) {
                $client->notify(new HealingCheckNotification($apt));
                $apt->update(['healing_notification_sent_at' => now()]);
                $sent++;
                $this->info("🩹 HealingCheck envoyé → {$client->pseudo} (Apt #{$apt->id})");
            }
        }

        // 3. RequestReview : J+14
        $reviewAppointments = Appointment::where('status', AppointmentStatus::COMPLETED)
            ->whereNull('review_notification_sent_at')
            ->whereNotNull('healing_notification_sent_at') // healing déjà envoyé
            ->where('end_datetime', '<=', now()->subDays(14))
            ->with('bookingRequest.client.user')
            ->get();

        foreach ($reviewAppointments as $apt) {
            $client = $apt->bookingRequest?->client?->user;
            if ($client && class_exists(RequestReviewNotification::class)) {
                $client->notify(new RequestReviewNotification($apt));
                $apt->update(['review_notification_sent_at' => now()]);
                $sent++;
                $this->info("⭐ RequestReview envoyé → {$client->pseudo} (Apt #{$apt->id})");
            }
        }

        $this->info("Total : {$sent} notifications post-tattoo envoyées");

        return Command::SUCCESS;
    }
}
