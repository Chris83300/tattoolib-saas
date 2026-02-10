<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookingRequest;
use Carbon\Carbon;

class CleanupOldBookingRequests extends Command
{
    protected $signature = 'booking-requests:cleanup';
    protected $description = 'Supprimer les demandes de réservation anciennes (7 jours pour pending, 2 jours pour rejected)';

    public function handle()
    {
        $this->info('Nettoyage des demandes de réservation anciennes...');

        // Supprimer les demandes de plus de 7 jours (temps pour que le client voie les demandes en attente)
        $cutoffDate = Carbon::now()->subDays(7);

        // Récupérer les demandes à supprimer avec leurs médias
        $oldPendingRequests = BookingRequest::where('created_at', '<', $cutoffDate)
            ->where('status', 'pending')
            ->with('media')
            ->get();

        // Supprimer les médias associés
        $mediaDeleted = 0;
        foreach ($oldPendingRequests as $request) {
            $mediaDeleted += $request->media()->count();
            $request->media()->delete(); // Supprimer les médias de la table media
        }

        // Supprimer les demandes
        $deletedCount = $oldPendingRequests->count();
        BookingRequest::whereIn('id', $oldPendingRequests->pluck('id'))->delete();

        $this->info("{$deletedCount} demande(s) supprimée(s)");
        $this->info("{$mediaDeleted} média(s) supprimé(s)");

        // Supprimer les demandes rejetées/cancelled de plus de 2 jours
        $rejectedCutoff = Carbon::now()->subDays(2);

        // Récupérer les demandes rejetées à supprimer avec leurs médias
        $oldRejectedRequests = BookingRequest::where('created_at', '<', $rejectedCutoff)
            ->whereIn('status', ['rejected', 'cancelled', 'expired'])
            ->with('media')
            ->get();

        // Supprimer les médias associés
        $rejectedMediaDeleted = 0;
        foreach ($oldRejectedRequests as $request) {
            $rejectedMediaDeleted += $request->media()->count();
            $request->media()->delete(); // Supprimer les médias de la table media
        }

        // Supprimer les demandes rejetées
        $rejectedDeleted = $oldRejectedRequests->count();
        BookingRequest::whereIn('id', $oldRejectedRequests->pluck('id'))->delete();

        $this->info("{$rejectedDeleted} demande(s) rejetée(s) supprimée(s)");
        $this->info("{$rejectedMediaDeleted} média(s) rejeté(s) supprimé(s)");

        $this->info('Nettoyage terminé!');

        return 0;
    }
}
