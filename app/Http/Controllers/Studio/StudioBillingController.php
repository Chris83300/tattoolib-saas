<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Traits\HasAccountDeletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudioBillingController extends Controller
{
    use HasAccountDeletion;

    /**
     * Récupère le studio que l'utilisateur connecté POSSÈDE.
     */
    private function studio(): Studio
    {
        $studio = auth()->user()->studio;
        abort_unless($studio, 403, 'Profil studio non trouvé');
        return $studio;
    }

    /**
     * Initier le Stripe Connect du studio (mode studio_managed).
     * Crée ou reprend l'onboarding Standard et redirige vers Stripe.
     */
    public function connectStripe(Request $request)
    {
        $studio = $this->studio();

        // Sécurité : uniquement si le mode le requiert
        if ($studio->payment_mode !== 'studio_managed') {
            return back()->with('info', 'Le Stripe Connect du studio n\'est utile qu\'en mode "Géré par le studio".');
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

            // Créer le compte Connect s'il n'existe pas encore
            if (!$studio->stripe_account_id) {
                $account = $stripe->accounts->create([
                    'type'          => 'standard',
                    'country'       => 'FR',
                    'email'         => $studio->stripeEmail(),
                    'capabilities'  => [
                        'card_payments' => ['requested' => true],
                        'transfers'     => ['requested' => true],
                    ],
                    'business_type' => 'company',
                    'metadata'      => [
                        'studio_id'    => $studio->id,
                        'user_id'      => auth()->id(),
                        'account_type' => 'studio_owner',
                    ],
                ]);

                $studio->update(['stripe_account_id' => $account->id]);

                Log::info('Compte Stripe Connect Studio créé', [
                    'studio_id'  => $studio->id,
                    'account_id' => $account->id,
                ]);
            }

            // Générer le lien d'onboarding
            $accountLink = $stripe->accountLinks->create([
                'account'     => $studio->stripe_account_id,
                'refresh_url' => route('studio.stripe.refresh'),
                'return_url'  => route('studio.stripe.return'),
                'type'        => 'account_onboarding',
            ]);

            return redirect($accountLink->url);
        } catch (\Exception $e) {
            Log::error('Erreur Stripe Connect Studio', [
                'studio_id' => $studio->id,
                'error'     => $e->getMessage(),
            ]);

            return back()->with('error', 'Impossible de connecter Stripe. Veuillez réessayer.');
        }
    }

    public function billing(Request $request)
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        // Retour de Stripe Checkout
        if ($request->get('checkout') === 'success') {
            $sessionId = $request->get('session_id');

            // Sync via session d'abord (plus précis), puis fallback syncFromStripe
            $synced = false;
            if ($sessionId) {
                $synced = $billingService->syncFromCheckoutSession($studio, $sessionId);
            }
            if (!$synced) {
                $synced = $billingService->syncFromStripe($studio);
            }

            // Terminer le trial si paiement effectué (syncFromCheckoutSession le fait aussi,
            // mais syncFromStripe n'a pas accès au payment_status — on refait ici par sécurité)
            if ($synced) {
                $billingService->endTrialImmediately($studio);
            }

            if ($synced) {
                return redirect()->route('studio.billing')
                    ->with('success', 'Abonnement activé avec succès ! Bienvenue sur le plan Studio.');
            }

            return redirect()->route('studio.billing')
                ->with('warning', "Le paiement semble avoir abouti mais l'abonnement n'est pas encore synchronisé. Cliquez sur « Synchroniser » dans quelques instants.");
        }

        if ($request->get('checkout') === 'cancel') {
            return redirect()->route('studio.billing')
                ->with('warning', 'Paiement annulé. Vous pouvez réessayer quand vous le souhaitez.');
        }

        $subscriptionInfo = $billingService->getSubscriptionInfo($studio);
        $isSubscribed     = $billingService->isSubscribed($studio);
        $portalUrl        = $billingService->billingPortalUrl($studio);

        $totalArtists = $studio->tattooers()->count() + $studio->piercers()->count();
        $includedArtists = (int) config('inkpik.pricing.studio.included_artists', 1);
        $extraArtists = max(0, $totalArtists - $includedArtists);
        $basePrice = \App\Enums\SubscriptionPlan::STUDIO->price();
        $extraPrice = \App\Enums\SubscriptionPlan::STUDIO->pricePerExtraArtist();
        $totalPrice = $basePrice + ($extraArtists * $extraPrice);

        // Charger les subscription_items Cashier si abonnement actif
        $subscriptionItems = [];
        if ($isSubscribed && $studio->user && $studio->user->subscription('default')) {
            $sub = $studio->user->subscription('default');
            $studioPriceId = config('inkpik.pricing.studio.stripe_price_id');
            $extraPriceId = config('inkpik.pricing.studio.stripe_price_id_extra');
            $subscriptionItems = $sub->items()->with(['subscription'])->get()->map(function ($item) use ($studioPriceId, $extraPriceId) {
                $unitPrice = null;
                if ($item->stripe_price === $studioPriceId) {
                    $unitPrice = \App\Enums\SubscriptionPlan::STUDIO->price();
                } elseif ($item->stripe_price === $extraPriceId) {
                    $unitPrice = \App\Enums\SubscriptionPlan::STUDIO->pricePerExtraArtist();
                }
                return [
                    'stripe_id'      => $item->stripe_id,
                    'stripe_product' => $item->stripe_product,
                    'stripe_price'   => $item->stripe_price,
                    'quantity'       => $item->quantity,
                    'unit_price'     => $unitPrice,
                ];
            })->toArray();
        }

        return view('studio.billing', compact(
            'studio',
            'subscriptionInfo',
            'isSubscribed',
            'portalUrl',
            'totalArtists',
            'includedArtists',
            'extraArtists',
            'basePrice',
            'extraPrice',
            'totalPrice',
            'subscriptionItems'
        ));
    }

    public function subscribe()
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        try {
            $checkoutUrl = $billingService->createCheckoutSession($studio);
            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de la session de paiement : ' . $e->getMessage());
        }
    }

    public function cancelSubscription(Request $request)
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        $immediate = $request->boolean('immediate', false);
        $success = $immediate
            ? $billingService->cancelNow($studio)
            : $billingService->cancel($studio);

        if ($success) {
            $message = $immediate
                ? 'Abonnement annulé immédiatement. Vos prélèvements sont arrêtés.'
                : "Abonnement annulé. Vous conservez l'accès jusqu'à la fin de la période en cours.";
            return redirect()->route('studio.billing')->with('success', $message);
        }

        return back()->with('error', "Impossible d'annuler l'abonnement. Veuillez réessayer.");
    }

    public function resumeSubscription()
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        if ($billingService->resume($studio)) {
            return redirect()->route('studio.billing')->with('success', 'Abonnement réactivé avec succès !');
        }

        return back()->with('error', "Impossible de réactiver l'abonnement.");
    }

    public function syncSubscription()
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        if ($billingService->syncFromStripe($studio)) {
            return redirect()->route('studio.billing')->with('success', 'Abonnement synchronisé depuis Stripe.');
        }

        return back()->with('warning', 'Aucun abonnement actif trouvé dans Stripe.');
    }

    // ═══ SOUSCRIPTION (ancienne page dédiée — redirige vers billing) ═══

    public function showSubscribe()
    {
        return redirect()->route('studio.billing');
    }

    public function processSubscribe()
    {
        return $this->subscribe();
    }

    protected function performDeletion(\App\Models\User $user): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            $studio = $user->studio;
            if (!$studio) return;

            // 1. Annuler l'abonnement Stripe
            try {
                if ($user->subscribed('default')) {
                    $user->subscription('default')->cancelNow();
                }
            } catch (\Exception $e) {
                Log::warning('Annulation abo studio impossible: ' . $e->getMessage());
            }

            // 2. Détacher les artistes (ils deviennent indépendants)
            foreach ($studio->studioArtists as $sa) {
                if ($linkedUser = $sa->user) {
                    $linkedUser->tattooer?->update(['studio_id' => null]);
                    $linkedUser->piercer?->update(['studio_id' => null]);
                }
                $sa->forceDelete();
            }

            // 3. Supprimer les médias studio
            $studio->media()->each(fn($m) => $m->delete());
            $studio->forceDelete();

            // 4. Anonymiser l'user
            $user->notifications()->delete();
            $user->update([
                'name'      => 'Compte supprimé',
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
