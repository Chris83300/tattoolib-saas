<?php

namespace App\Http\Controllers;

use App\Models\TattooerSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * Page des plans
     */
    public function plans()
    {
        $tattooer = auth()->user()->tattooer;
        $activeSubscription = $tattooer->activeSubscription;

        return view('tattooer.subscription-plans', compact('tattooer', 'activeSubscription'));
    }

    /**
     * Créer une session Stripe Checkout pour l'abonnement PRO
     */
    public function subscribe(Request $request)
    {
        $user = auth()->user();
        $tattooer = $user->tattooer;

        // Vérifier qu'il n'est pas déjà PRO
        if ($tattooer->isPro()) {
            return redirect()->route('tattooer.subscription.plans')
                ->with('info', 'Vous êtes déjà abonné PRO.');
        }

        $priceId = config('inkpik.stripe.pro_price_id');

        if (!$priceId) {
            return redirect()->back()->with('error', 'Configuration Stripe incomplète.');
        }

        // Créer une session Stripe Checkout via Cashier
        $checkout = $user->newSubscription('pro', $priceId)
            ->checkout([
                'success_url' => route('tattooer.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('tattooer.subscription.plans'),
                'metadata' => [
                    'tattooer_id' => $tattooer->id,
                    'plan' => 'pro',
                ],
            ]);

        return redirect($checkout->url);
    }

    /**
     * Retour après paiement réussi
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('tattooer.subscription.plans');
        }

        $user = auth()->user();
        $tattooer = $user->tattooer;

        // Récupérer la session Stripe directement
        $stripe = new \Stripe\StripeClient(config('cashier.secret'));
        $session = $stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['subscription'],
        ]);

        if ($session->payment_status !== 'paid' || !$session->subscription) {
            return redirect()->route('tattooer.subscription.plans')
                ->with('error', 'Le paiement n\'a pas été confirmé.');
        }

        $stripeSubscription = $session->subscription;

        // 1. S'assurer que le user a un stripe_id (customer)
        if (!$user->stripe_id) {
            $user->update(['stripe_id' => $session->customer]);
        }

        // 2. Créer/mettre à jour la subscription Cashier
        $cashierSub = $user->subscriptions()->updateOrCreate(
            ['name' => 'pro'],
            [
                'stripe_id' => is_string($stripeSubscription) ? $stripeSubscription : $stripeSubscription->id,
                'stripe_status' => is_string($stripeSubscription) ? 'active' : $stripeSubscription->status,
                'stripe_price' => config('inkpik.stripe.pro_price_id'),
                'quantity' => 1,
                'ends_at' => null,
            ]
        );

        // 3. Sync avec tattooer_subscriptions
        $subId = is_string($stripeSubscription) ? $stripeSubscription : $stripeSubscription->id;

        TattooerSubscription::updateOrCreate(
            [
                'subscribable_type' => 'App\\Models\\Tattooer',
                'subscribable_id' => $tattooer->id,
            ],
            [
                'plan' => 'pro',
                'status' => 'active',
                'stripe_subscription_id' => $subId,
                'stripe_price_id' => config('inkpik.stripe.pro_price_id'),
                'current_period_start' => now(),
                'current_period_end' => now()->addMonth(),
                'price_monthly' => 49.99,
                'commission_rate' => 0.0,
                'features' => [
                    'zero_commission' => true,
                    'client_management' => true,
                    'priority_support' => true,
                    'analytics' => true,
                    'custom_branding' => true,
                ],
            ]
        );

        return redirect()->route('tattooer.dashboard')
            ->with('success', '🎉 Félicitations ! Vous êtes maintenant PRO. Commission 0% !');
    }

    /**
     * Gérer l'abonnement (portail client Stripe)
     */
    public function manage(Request $request)
    {
        $user = auth()->user();

        return $user->redirectToBillingPortal(
            route('tattooer.subscription.plans')
        );
    }

    /**
     * Annuler l'abonnement (fin de période)
     */
    public function cancel(Request $request)
    {
        $user = auth()->user();

        if ($user->subscribed('pro')) {
            // Annulation en fin de période (pas immédiate)
            $user->subscription('pro')->cancel();

            // Mettre à jour notre table
            $tattooer = $user->tattooer;
            $sub = TattooerSubscription::forTattooer($tattooer->id)
                ->active()
                ->first();

            if ($sub) {
                $sub->update([
                    'canceled_at' => now(),
                    'ends_at' => $user->subscription('pro')->ends_at,
                ]);
            }

            return redirect()->route('tattooer.subscription.plans')
                ->with('success', 'Abonnement annulé. Vous gardez accès PRO jusqu\'au ' .
                    $user->subscription('pro')->ends_at->translatedFormat('d F Y') . '.');
        }

        return redirect()->route('tattooer.subscription.plans')
            ->with('error', 'Aucun abonnement actif à annuler.');
    }

    /**
     * Reprendre un abonnement annulé (pendant la grace period)
     */
    public function resume(Request $request)
    {
        $user = auth()->user();

        if ($user->subscription('pro')?->onGracePeriod()) {
            $user->subscription('pro')->resume();

            $tattooer = $user->tattooer;
            $sub = TattooerSubscription::forTattooer($tattooer->id)->first();

            if ($sub) {
                $sub->update([
                    'canceled_at' => null,
                    'ends_at' => null,
                    'status' => 'active',
                ]);
            }

            return redirect()->route('tattooer.subscription.plans')
                ->with('success', 'Abonnement PRO réactivé !');
        }

        return redirect()->route('tattooer.subscription.plans');
    }

    /**
     * Sync la subscription Cashier vers notre table tattooer_subscriptions
     */
    private function syncTattooerSubscription($user, $tattooer): void
    {
        $cashierSub = $user->subscription('pro');
        if (!$cashierSub) return;

        TattooerSubscription::updateOrCreate(
            [
                'subscribable_type' => 'App\\Models\\Tattooer',
                'subscribable_id' => $tattooer->id,
                'stripe_subscription_id' => $cashierSub->stripe_id,
            ],
            [
                'plan' => 'pro',
                'status' => $cashierSub->stripe_status === 'active' ? 'active' : $cashierSub->stripe_status,
                'stripe_price_id' => config('inkpik.stripe.pro_price_id'),
                'current_period_start' => now(),
                'current_period_end' => now()->addMonth(),
                'price_monthly' => 49.99,
                'commission_rate' => 0.0,
                'features' => [
                    'zero_commission' => true,
                    'client_management' => true,
                    'priority_support' => true,
                    'analytics' => true,
                    'custom_branding' => true,
                ],
            ]
        );
    }
}
