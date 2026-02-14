<?php

namespace App\Console\Commands;

use App\Actions\CompleteAppointmentAction;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use Illuminate\Console\Command;

class CheckCompletedAppointments extends Command
{
    protected $signature = 'app:check-completed-appointments';
    protected $description = 'Auto-complète les RDV passés depuis plus de 24h sans validation manuelle';

    public function handle(): int
    {
        $action = new CompleteAppointmentAction();

        // RDV terminés depuis plus de 24h, toujours en status "confirmed" (pas encore validés)
        $appointments = Appointment::where('status', AppointmentStatus::CONFIRMED)
            ->where('end_datetime', '<', now()->subHours(24))
            ->with(['bookingRequest.conversation', 'bookingRequest.client'])
            ->get();

        $count = 0;
        foreach ($appointments as $appointment) {
            try {
                $action->execute($appointment, 'system');
                $count++;
                $this->info("✅ Appointment #{$appointment->id} auto-complété");
            } catch (\Exception $e) {
                $this->error("❌ Appointment #{$appointment->id} erreur : {$e->getMessage()}");
            }
        }

        $this->info("Total : {$count} RDV auto-complétés.");

        return Command::SUCCESS;
    }
}
