<?php

namespace App\Listeners;

use App\Models\TattooerSubscription;
use Laravel\Cashier\Events\WebhookReceived;

class StripeSubscriptionListener
{
    public function handle(WebhookReceived $event): void
    {
        $payload = $event->payload;
        $type = $payload['type'] ?? '';

        // Événements subscription
        if (str_starts_with($type, 'customer.subscription.')) {
            $this->handleSubscriptionEvent($payload);
        }
    }

    private function handleSubscriptionEvent(array $payload): void
    {
        $subscription = $payload['data']['object'] ?? [];
        $stripeSubId = $subscription['id'] ?? null;
        $status = $subscription['status'] ?? null;

        if (!$stripeSubId) return;

        $tattooerSub = TattooerSubscription::where('stripe_subscription_id', $stripeSubId)->first();

        if (!$tattooerSub) return;

        $type = $payload['type'];

        match ($type) {
            'customer.subscription.updated' => $tattooerSub->update([
                'status' => $status === 'active' ? 'active' : $status,
                'current_period_start' => isset($subscription['current_period_start'])
                    ? \Carbon\Carbon::createFromTimestamp($subscription['current_period_start'])
                    : $tattooerSub->current_period_start,
                'current_period_end' => isset($subscription['current_period_end'])
                    ? \Carbon\Carbon::createFromTimestamp($subscription['current_period_end'])
                    : $tattooerSub->current_period_end,
                'canceled_at' => isset($subscription['canceled_at']) && $subscription['canceled_at']
                    ? \Carbon\Carbon::createFromTimestamp($subscription['canceled_at'])
                    : null,
            ]),

            'customer.subscription.deleted' => $tattooerSub->update([
                'status' => 'canceled',
                'ends_at' => now(),
            ]),

            default => null,
        };
    }
}
