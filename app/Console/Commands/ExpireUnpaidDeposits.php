<?php

namespace App\Console\Commands;

use App\Models\BookingRequest;
use Illuminate\Console\Command;

class ExpireUnpaidDeposits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire les demandes dont le délai d\'acompte est dépassé';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Recherche des demandes avec acompte expiré...');

        $expiredBookings = BookingRequest::where('status', 'accepted')
            ->whereNotNull('deposit_deadline')
            ->where('deposit_deadline', '<', now())
            ->whereNull('deposit_paid_at')
            ->with(['conversation', 'client', 'bookable'])
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('✅ Aucune demande expirée à traiter.');
            return 0;
        }

        $this->info("📋 {$expiredBookings->count()} demande(s) expirée(s) trouvée(s)");

        foreach ($expiredBookings as $booking) {
            // Passer la demande en statut expiré
            $booking->update(['status' => 'expired']);

            // Fermer la conversation liée
            if ($booking->conversation) {
                $booking->conversation->update([
                    'status' => 'closed'
                ]);
            }

            $this->line("   • Demande #{$booking->id} - Client: {$booking->client->user->name} - Expirée le: {$booking->deposit_deadline}");
        }

        $this->info('✅ Traitement terminé.');
        return 0;
    }
}
