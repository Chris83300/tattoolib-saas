<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BetaService
{
    /**
     * Coupon Stripe pour le mois gratuit bêta (100% off, once).
     */
    public const COUPON_FREE_MONTH = 'BETA-FREE-MONTH';

    /**
     * Coupon Stripe pour la réduction à vie (-30%).
     */
    public const COUPON_LIFETIME_DISCOUNT = 'BETA-LAUNCH-30';

    /**
     * Inscrire un utilisateur comme bêta-testeur avec abonnement gratuit 1 mois.
     * PAS de trial — le coupon 100% off couvre le premier mois.
     */
    public function registerBetaTester(User $user, string $stripePriceId): void
    {
        $user->update([
            'is_beta_tester'     => true,
            'beta_registered_at' => now(),
            'beta_expires_at'    => Carbon::now()->addMonth(),
            'beta_coupon_used'   => self::COUPON_FREE_MONTH,
        ]);

        $user->newSubscription('default', $stripePriceId)
            ->withCoupon(self::COUPON_FREE_MONTH)
            ->create();

        $user->notify(new \App\Notifications\WelcomeBetaTesterNotification());

        Log::info('[Beta] Bêta-testeur inscrit', [
            'user_id'         => $user->id,
            'price_id'        => $stripePriceId,
            'beta_expires_at' => $user->beta_expires_at->toDateString(),
        ]);
    }

    /**
     * Vérifier si un utilisateur doit recevoir la relance J+20.
     * = bêta-testeur actif, entre 8 et 10 jours restants, pas encore souscrit.
     */
    public function shouldSendUpgradeReminder(User $user): bool
    {
        if (!$user->is_beta_tester || !$user->beta_expires_at) {
            return false;
        }

        $daysRemaining = $user->betaDaysRemaining();

        return $daysRemaining >= 8 && $daysRemaining <= 10
            && !$user->subscribed('default');
    }

    /**
     * Bloquer les bêta-testeurs dont le mois gratuit est expiré sans souscription.
     */
    public function blockExpiredBetaTesters(): int
    {
        $expiredUsers = User::where('is_beta_tester', true)
            ->where('beta_expires_at', '<', now())
            ->whereDoesntHave('subscriptions', function ($q) {
                $q->where('stripe_status', 'active');
            })
            ->get();

        $count = 0;
        foreach ($expiredUsers as $user) {
            $artisan = $user->artisan();
            if ($artisan && !$artisan->is_blocked) {
                $artisan->update(['is_blocked' => true]);
                $count++;
                Log::info('[Beta] Bêta-testeur bloqué (mois expiré)', ['user_id' => $user->id]);
            }
        }

        return $count;
    }

    /**
     * Appliquer le coupon -30% à vie lors de l'upgrade post-bêta.
     */
    public function applyLifetimeDiscount(User $user, string $stripePriceId): void
    {
        if ($user->subscribed('default')) {
            $user->subscription('default')->swap($stripePriceId);
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $stripe->subscriptions->update($user->subscription('default')->stripe_id, [
                'coupon' => self::COUPON_LIFETIME_DISCOUNT,
            ]);
        } else {
            $user->newSubscription('default', $stripePriceId)
                ->withCoupon(self::COUPON_LIFETIME_DISCOUNT)
                ->create();
        }

        $user->update(['beta_coupon_used' => self::COUPON_LIFETIME_DISCOUNT]);

        Log::info('[Beta] Coupon -30% appliqué', [
            'user_id'  => $user->id,
            'price_id' => $stripePriceId,
        ]);
    }

    /**
     * Paramètres Stripe Checkout pour les bêta-testeurs (upgrade post-bêta).
     * Utilisé dans SubscriptionController si l'utilisateur est bêta et demande BETA-LAUNCH-30.
     */
    public function getStripeCheckoutParams(User $user): array
    {
        if (!$user->is_beta_tester) {
            return [];
        }

        return [
            'discounts' => [
                ['coupon' => self::COUPON_LIFETIME_DISCOUNT],
            ],
        ];
    }
}
