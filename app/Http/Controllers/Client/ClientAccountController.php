<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Traits\HasAccountDeletion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientAccountController extends Controller
{
    use HasAccountDeletion;

    /**
     * Supprime définitivement le compte client
     */
    protected function performDeletion(\App\Models\User $user): void
    {
        DB::transaction(function () use ($user) {
            $client = $user->client;
            if (!$client) return;

            // 1. Rembourser les acomptes actifs et annuler les bookings
            \App\Models\BookingRequest::where('client_id', $client->id)
                ->whereNotNull('deposit_paid_at')
                ->whereNotIn('status', ['completed', 'fully_completed', 'cancelled'])
                ->each(function ($booking) {
                    try {
                        app(\App\Services\BookingRequestService::class)
                            ->processStripeRefund($booking, $booking->total_deposit_amount);
                    } catch (\Exception $e) {
                        Log::warning("Remboursement client impossible booking {$booking->id}: " . $e->getMessage());
                    }
                    $booking->update([
                        'status'              => 'cancelled',
                        'cancellation_reason' => 'Compte client supprimé',
                        'cancelled_by'        => 'client',
                        'cancelled_at'        => now(),
                    ]);
                });

            // 2. Anonymiser les bookings complétés (archives pour les artistes)
            \App\Models\BookingRequest::where('client_id', $client->id)
                ->whereIn('status', ['completed', 'fully_completed'])
                ->update(['client_id' => null]);

            // 3. Supprimer les conversations
            $client->conversations()->each(function ($conv) {
                $conv->messages()->forceDelete();
                $conv->forceDelete();
            });

            // 4. Supprimer le profil client
            $client->forceDelete();

            // 5. Anonymiser l'user
            $user->notifications()->delete();
            $user->update([
                'name'      => 'Client supprimé',
                'email'     => 'deleted_' . $user->id . '@inkpik.deleted',
                'phone'     => null,
                'password'  => bcrypt(\Str::random(40)),
                'stripe_id' => null,
                'fcm_token' => null,
            ]);
            $user->forceDelete();
        });
    }
}
