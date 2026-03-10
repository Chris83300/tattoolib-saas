<?php

namespace App\Http\Controllers;

use App\Models\TattooerSubscription;
use App\Services\BetaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * Page des plans
     */
    public function plans()
    {
        $user = auth()->user();

        // Déterminer si c'est un tattooer ou un piercer
        if ($user->tattooer) {
            $artist = $user->tattooer;
            $activeSubscription = $artist->activeSubscription;
            $routePrefix = 'tattooer';
        } elseif ($user->piercer) {
            $artist = $user->piercer;
            $activeSubscription = $artist->activeSubscription;
            $routePrefix = 'pierceur';
        } else {
            return redirect()->route('dashboard')->with('error', 'Aucun profil artiste trouvé.');
        }

        // Compteurs pour le layout
        $pendingCount = \App\Models\BookingRequest::where('bookable_id', $artist->id)
            ->where('bookable_type', get_class($artist))
            ->where('status', 'pending')
            ->count();

        $unreadCount = \App\Models\Conversation::whereHas('messages', function ($query) use ($artist) {
                $query->where(function ($q) use ($artist) {
                    // Si l'utilisateur est un tattooer/piercer, vérifier read_by_tattooer_at
                    if ($artist instanceof \App\Models\Tattooer || $artist instanceof \App\Models\Piercer) {
                        $q->whereNull('read_by_tattooer_at');
                    } else {
                        $q->whereNull('read_by_client_at');
                    }
                })
                ->where('sender_id', '!=', $artist->user_id);
            })
            ->whereHas('participants', function ($query) use ($artist) {
                $query->where('user_id', $artist->user_id);
            })
            ->count();

        if ($user->tattooer) {
            return view('tattooer.subscription-plans', compact('artist', 'activeSubscription', 'pendingCount', 'unreadCount'));
        } elseif ($user->piercer) {
            return view('piercer.subscription-plans', compact('artist', 'activeSubscription', 'pendingCount', 'unreadCount'));
        }

        return redirect()->route('dashboard')->with('error', 'Aucun profil artiste trouvé.');
    }

    /**
     * Créer une session Stripe Checkout pour l'abonnement STARTER ou PRO
     */
    public function subscribe(Request $request)
    {
        $user = auth()->user();

        // Déterminer si c'est un tattooer ou un piercer
        if ($user->tattooer) {
            $artist = $user->tattooer;
            $routePrefix = 'tattooer';
        } elseif ($user->piercer) {
            $artist = $user->piercer;
            $routePrefix = 'pierceur';
        } else {
            return redirect()->route('dashboard')->with('error', 'Aucun profil artiste trouvé.');
        }

        // Valider le plan demandé
        $plan = in_array($request->get('plan'), ['starter', 'pro']) ? $request->get('plan') : 'pro';

        // Vérifier qu'il n'est pas déjà abonné au même plan
        if ($artist->is_subscribed && $artist->current_plan === $plan) {
            return redirect()->route($routePrefix . '.subscription.plans')
                ->with('info', 'Vous êtes déjà abonné au plan ' . strtoupper($plan) . '.');
        }

        $priceId = config("inkpik.pricing.{$plan}.stripe_price_id");

        if (!$priceId) {
            return redirect()->back()->with('error', 'Configuration Stripe incomplète pour le plan ' . strtoupper($plan) . '.');
        }

        // Créer une session Stripe Checkout via Cashier
        $betaParams = app(BetaService::class)->getStripeCheckoutParams($user);

        $checkoutParams = array_merge([
            'success_url' => route($routePrefix . '.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}&plan=' . $plan,
            'cancel_url'  => route($routePrefix . '.subscription.plans'),
            'mode'        => 'subscription', // Mode subscription pour les prix récurrents
            'metadata'    => [
                'artist_id'   => $artist->id,
                'artist_type' => get_class($artist),
                'plan'        => $plan,
            ],
            // Permettre à l'utilisateur d'entrer un code manuellement (temporairement, en attendant le coupon)
            'allow_promotion_codes' => true,
        ], $betaParams);

        // Utiliser checkout() directement pour créer l'abonnement
        $checkout = $user->checkout($priceId, $checkoutParams);

        return redirect($checkout->url);
    }

    /**
     * Retour après paiement réussi
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('dashboard');
        }

        $user = auth()->user();

        // Déterminer si c'est un tattooer ou un piercer
        if ($user->tattooer) {
            $artist      = $user->tattooer;
            $routePrefix = 'tattooer';
        } elseif ($user->piercer) {
            $artist      = $user->piercer;
            $routePrefix = 'pierceur';
        } else {
            return redirect()->route('dashboard')->with('error', 'Aucun profil artiste trouvé.');
        }

        // Récupérer le plan depuis la query string (passé dans success_url)
        $planKey = in_array($request->get('plan'), ['starter', 'pro']) ? $request->get('plan') : 'pro';

        try {
            sleep(2); // Laisser Stripe finaliser la session
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));

            // Récupérer la session Checkout pour obtenir le stripe_subscription_id
            $session = $stripe->checkout->sessions->retrieve($sessionId, ['expand' => ['subscription']]);
            $stripeSub = $session->subscription ?? null;

            if ($stripeSub) {
                // Synchroniser dans la table Cashier subscriptions (important en local sans webhook)
                $user->subscriptions()->updateOrCreate(
                    ['stripe_id' => $stripeSub->id],
                    [
                        'type'          => $planKey,
                        'stripe_status' => $stripeSub->status,
                        'stripe_price'  => $stripeSub->items->data[0]->price->id ?? null,
                        'quantity'      => 1,
                        'trial_ends_at' => $stripeSub->trial_end
                            ? \Carbon\Carbon::createFromTimestamp($stripeSub->trial_end)
                            : null,
                        'ends_at'       => null,
                    ]
                );

                // Mettre à jour le stripe_id customer sur le user si manquant
                if (!$user->stripe_id && $stripeSub->customer) {
                    $user->update(['stripe_id' => $stripeSub->customer]);
                }

                // Terminer le trial Stripe immédiatement si en trialing
                if ($stripeSub->status === 'trialing') {
                    $stripe->subscriptions->update($stripeSub->id, ['trial_end' => 'now']);
                }
            }

            // Mettre à jour l'artiste avec le plan souscrit
            $artist->update([
                'trial_ends_at' => null,
                'is_subscribed'  => true,
                'current_plan'   => $planKey,
                'is_blocked'     => false,
            ]);

            Log::info('Subscription success: artist updated', [
                'artist_id'   => $artist->id,
                'plan'        => $planKey,
                'stripe_sub'  => $stripeSub?->id,
            ]);
        } catch (\Exception $e) {
            Log::warning('Subscription success: sync error', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            // Mettre à jour l'artiste même si la sync Cashier échoue
            $artist->update([
                'trial_ends_at' => null,
                'is_subscribed'  => true,
                'current_plan'   => $planKey,
                'is_blocked'     => false,
            ]);
        }

        $planLabel = $planKey === 'pro' ? 'PRO' : 'Starter';
        return redirect()->route($routePrefix . '.subscription.plans')
            ->with('success', 'Félicitations ! Votre abonnement ' . $planLabel . ' est maintenant actif.');
    }

    /**
     * S'abonner depuis le trial
     */
    public function subscribeFromTrial(Request $request)
    {
        $user = auth()->user();

        // Déterminer si c'est un tattooer ou un piercer
        if ($user->tattooer) {
            $artist = $user->tattooer;
            $routePrefix = 'tattooer';
        } elseif ($user->piercer) {
            $artist = $user->piercer;
            $routePrefix = 'pierceur';
        } else {
            return redirect()->route('dashboard')->with('error', 'Aucun profil artiste trouvé.');
        }

        // Créer une session Stripe Checkout pour le plan PRO
        try {
            $checkoutSession = $user->checkout('pro', [
                'success_url' => route($routePrefix . '.subscription.success'),
                'cancel_url' => route($routePrefix . '.subscription.plans'),
                'metadata' => [
                    'user_id' => $user->id,
                    'plan' => 'pro',
                    'source' => 'trial_upgrade',
                ],
            ]);

            return redirect()->away($checkoutSession->url);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Checkout session creation error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route($routePrefix . '.subscription.plans')
                ->with('error', 'Une erreur est survenue lors de la création de votre session de paiement.');
        }
    }

    /**
     * Gérer l'abonnement (portail client Stripe)
     */
    public function manage(Request $request)
    {
        $user = auth()->user();

        // Déterminer si c'est un tattooer ou un piercer
        if ($user->tattooer) {
            $routePrefix = 'tattooer';
        } elseif ($user->piercer) {
            $routePrefix = 'pierceur';
        } else {
            return redirect()->route('dashboard')->with('error', 'Aucun profil artiste trouvé.');
        }

        return $user->redirectToBillingPortal(
            route($routePrefix . '.subscription.plans')
        );
    }

    /**
     * Annuler l'abonnement (fin de période)
     */
    public function cancel(Request $request)
    {
        $user = auth()->user();

        // Déterminer si c'est un tattooer ou un piercer
        if ($user->tattooer) {
            $artist = $user->tattooer;
            $routePrefix = 'tattooer';
        } elseif ($user->piercer) {
            $artist = $user->piercer;
            $routePrefix = 'pierceur';
        } else {
            return redirect()->route('dashboard')->with('error', 'Aucun profil artiste trouvé.');
        }

        $subscription = $artist->activeSubscription;

        if (!$subscription) {
            return redirect()->route($routePrefix . '.subscription.plans')
                ->with('error', 'Aucun abonnement actif à annuler.');
        }

        $subscription->cancel();

        return redirect()->route($routePrefix . '.subscription.plans')
            ->with('success', 'Abonnement annulé. Vous gardez l\'accès PRO jusqu\'à la fin de la période.');
    }

    /**
     * Réactiver un abonnement annulé (grace period)
     */
    public function resume(Request $request)
    {
        $user = auth()->user();

        // Déterminer si c'est un tattooer ou un piercer
        if ($user->tattooer) {
            $artist = $user->tattooer;
            $routePrefix = 'tattooer';
        } elseif ($user->piercer) {
            $artist = $user->piercer;
            $routePrefix = 'pierceur';
        } else {
            return redirect()->route('dashboard')->with('error', 'Aucun profil artiste trouvé.');
        }

        $subscription = $artist->activeSubscription;

        if (!$subscription || !$subscription->onGracePeriod()) {
            return redirect()->route($routePrefix . '.subscription.plans')
                ->with('error', 'Impossible de réactiver cet abonnement.');
        }

        $subscription->resume();

        return redirect()->route($routePrefix . '.subscription.plans')
            ->with('success', 'Abonnement réactivé avec succès !');
    }
}
