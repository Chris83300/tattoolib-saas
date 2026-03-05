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
            $routePrefix = 'piercer';
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
     * Créer une session Stripe Checkout pour l'abonnement PRO
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
            $routePrefix = 'piercer';
        } else {
            return redirect()->route('dashboard')->with('error', 'Aucun profil artiste trouvé.');
        }

        // Vérifier qu'il n'est pas déjà PRO
        if ($artist->isPro()) {
            return redirect()->route($routePrefix . '.subscription.plans')
                ->with('info', 'Vous êtes déjà abonné PRO.');
        }

        $priceId = config('inkpik.stripe.pro_price_id');

        if (!$priceId) {
            return redirect()->back()->with('error', 'Configuration Stripe incomplète.');
        }

        // Créer une session Stripe Checkout via Cashier
        $betaParams = app(BetaService::class)->getStripeCheckoutParams($user);

        $checkoutParams = array_merge([
            'success_url' => route($routePrefix . '.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route($routePrefix . '.subscription.plans'),
            'metadata'    => [
                'artist_id'   => $artist->id,
                'artist_type' => get_class($artist),
                'plan'        => 'pro',
            ],
        ], $betaParams);

        $checkout = $user->newSubscription('pro', $priceId)->checkout($checkoutParams);

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
            $routePrefix = 'piercer';
        } else {
            return redirect()->route('dashboard')->with('error', 'Aucun profil artiste trouvé.');
        }

        // Terminer le trial immédiatement si l'artiste était en trialing
        try {
            sleep(1); // Laisser Stripe finaliser
            $sub = $user->subscription('pro') ?? $user->subscription('default');
            if ($sub && $sub->onTrial()) {
                $stripe = new \Stripe\StripeClient(config('cashier.secret'));
                $stripe->subscriptions->update($sub->stripe_id, ['trial_end' => 'now']);
                $sub->update(['stripe_status' => 'active', 'trial_ends_at' => null]);
                $artist->update(['trial_ends_at' => null, 'is_subscribed' => true]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Artiste endTrialImmediately error', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }

        return redirect()->route($routePrefix . '.subscription.plans')
            ->with('success', 'Félicitations ! Votre abonnement PRO est maintenant actif.');
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
            $routePrefix = 'piercer';
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
            $routePrefix = 'piercer';
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
            $routePrefix = 'piercer';
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
