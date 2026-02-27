<?php

namespace App\Listeners;

use App\Models\Studio;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

class HandleStudioSubscriptionCreated
{
    public function handle(WebhookReceived $event): void
    {
        $payload = $event->payload;
        $type    = $payload['type'] ?? '';

        if ($type !== 'customer.subscription.created') {
            return;
        }

        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;
        if (!$stripeCustomerId) {
            return;
        }

        // Chercher un studio avec ce stripe_id (Cashier Billable)
        $studio = Studio::where('stripe_id', $stripeCustomerId)->first();
        if (!$studio) {
            return;
        }

        // Le studio a souscrit — canOperate() retournera true via hasActiveSubscription()
        // trial_ends_at conservé pour historique
        Log::info("Studio {$studio->name} (#{$studio->id}) a activé son abonnement via Stripe", [
            'stripe_customer_id'      => $stripeCustomerId,
            'stripe_subscription_id'  => $payload['data']['object']['id'] ?? null,
        ]);
    }
}
