<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

class HandleStudioSubscriptionCreated
{
    public function handle(WebhookReceived $event): void
    {
        $payload = $event->payload;
        $type    = $payload['type'] ?? '';

        // Gérer creation, update et suppression d'abonnement
        if (!in_array($type, [
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted',
            'checkout.session.completed',
        ])) {
            return;
        }

        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;
        if (!$stripeCustomerId) {
            return;
        }

        // Le Billable est sur User — chercher via users.stripe_id
        $user = User::where('stripe_id', $stripeCustomerId)->first();
        if (!$user) {
            return;
        }

        $studio = $user->studio;
        if (!$studio) {
            return;
        }

        $status = $payload['data']['object']['status'] ?? null;
        $isActive = in_array($status, ['active', 'trialing'])
            || $type === 'checkout.session.completed';

        Log::info("Webhook studio subscription [{$type}]", [
            'user_id'                => $user->id,
            'studio_id'              => $studio->id,
            'studio_name'            => $studio->name,
            'stripe_customer_id'     => $stripeCustomerId,
            'stripe_subscription_id' => $payload['data']['object']['id'] ?? null,
            'status'                 => $status,
        ]);
    }
}
