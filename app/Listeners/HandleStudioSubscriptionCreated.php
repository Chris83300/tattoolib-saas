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

        if (!in_array($type, [
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted',
            'checkout.session.completed',
            'invoice.paid',
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

        $studio  = $user->studio;
        $artisan = $user->tattooer ?? $user->piercer ?? null;

        $status   = $payload['data']['object']['status'] ?? null;
        $isActive = in_array($status, ['active', 'trialing'])
            || $type === 'checkout.session.completed';

        Log::info("Webhook studio subscription [{$type}]", [
            'user_id'                => $user->id,
            'studio_id'              => $studio?->id,
            'stripe_customer_id'     => $stripeCustomerId,
            'stripe_subscription_id' => $payload['data']['object']['id'] ?? null,
            'status'                 => $status,
        ]);

        // Quand une facture est payée, l'abonnement passe de trialing à active
        if ($type === 'invoice.paid') {
            $this->handleInvoicePaid($user, $studio, $artisan, $payload);
        }
    }

    /**
     * Quand invoice.paid arrive : terminer le trial côté local.
     * Stripe a déjà mis à jour le statut à 'active' — on synchronise le DB local.
     */
    private function handleInvoicePaid(User $user, $studio, $artisan, array $payload): void
    {
        $sub = $user->subscription('default') ?? $user->subscription('pro');
        if ($sub && $sub->onTrial()) {
            $sub->update([
                'stripe_status' => 'active',
                'trial_ends_at' => null,
            ]);
            Log::info('invoice.paid — trial ended locally', [
                'user_id'    => $user->id,
                'sub_id'     => $sub->stripe_id,
            ]);
        }

        // Studio
        if ($studio) {
            $studio->update([
                'is_subscribed' => true,
                'trial_ends_at' => null,
            ]);
        }

        // Artiste indépendant
        if ($artisan && method_exists($artisan, 'update')) {
            $updateData = ['is_subscribed' => true, 'trial_ends_at' => null];
            if (isset($artisan->is_blocked)) {
                $updateData['is_blocked'] = false;
            }
            $artisan->update($updateData);
        }

        Log::info('invoice.paid handled', ['user_id' => $user->id]);
    }
}
