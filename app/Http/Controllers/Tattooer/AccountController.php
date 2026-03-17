<?php

namespace App\Http\Controllers\Tattooer;

use App\Http\Controllers\Controller;
use App\Traits\HasAccountDeletion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    use HasAccountDeletion;

    protected function performDeletion(\App\Models\User $user): void
    {
        DB::transaction(function () use ($user) {
            $artist = $user->tattooer ?? $user->piercer;
            if (!$artist) return;

            // 1. Annuler l'abonnement Stripe
            try {
                if ($user->subscribed('default')) {
                    $user->subscription('default')->cancelNow();
                }
            } catch (\Exception $e) {
                Log::warning('Annulation abo impossible lors suppression: ' . $e->getMessage());
            }

            // 2. Rembourser les acomptes en cours et annuler les bookings
            $artist->bookingRequests()
                ->whereNotNull('deposit_paid_at')
                ->whereIn('status', ['date_confirmed', 'awaiting_balance', 'design_pending', 'deposit_paid'])
                ->each(function ($booking) {
                    try {
                        app(\App\Services\BookingRequestService::class)
                            ->processStripeRefund($booking, $booking->total_deposit_amount);
                        $booking->update([
                            'status'              => 'cancelled',
                            'cancellation_reason' => 'Compte artiste supprimé',
                            'cancelled_by'        => 'artist',
                            'cancelled_at'        => now(),
                        ]);
                    } catch (\Exception $e) {
                        Log::warning("Remboursement impossible booking {$booking->id}: " . $e->getMessage());
                    }
                });

            // 3. Anonymiser les bookings complétés (archives comptables)
            $artist->bookingRequests()
                ->whereIn('status', ['completed', 'fully_completed'])
                ->update(['bookable_id' => null, 'bookable_type' => null]);

            // 4. Supprimer les médias
            $artist->media()->each(fn($m) => $m->delete());

            // 5. Supprimer les conversations
            $artist->conversations()->each(function ($conv) {
                $conv->messages()->forceDelete();
                $conv->forceDelete();
            });

            // 6. Détacher les clients et supprimer les bookings non complétés
            $artist->clients()->detach();
            $artist->bookingRequests()
                ->whereNotIn('status', ['completed', 'fully_completed'])
                ->forceDelete();

            // 7. Supprimer le profil artiste
            $artist->forceDelete();

            // 8. Anonymiser l'user
            $user->notifications()->delete();
            $user->update([
                'name'      => 'Compte supprimé',
                'email'     => 'deleted_' . $user->id . '@inkpik.deleted',
                'phone'     => null,
                'password'  => bcrypt(\Str::random(40)),
                'stripe_id' => null,
                'fcm_token' => null,
                'status'    => 'deleted',
            ]);
            $user->forceDelete();
        });
    }
}
