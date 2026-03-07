# 💳 PROMPT I — ABONNEMENT STARTER & PRO : TRIAL + ACTIVATION
# Pour Claude Code — Afficher le trial + bouton payer pour TOUS les plans
# Commit après chaque fix

## CONTEXTE

Après la refonte pricing (Prompt E), les 3 plans ont un essai 14 jours. Mais :
- **STARTER** : pas de décompte trial visible, pas de bouton "Activer mon abonnement" (seulement "Passer PRO")
- **PRO** : pareil, pas de trial visible et pas d'activation

Le flux d'abonnement Stripe Checkout n'existe que pour le plan STUDIO. Il faut le généraliser à STARTER et PRO.

### Comportement attendu pour CHAQUE plan

| Étape | Ce que voit l'utilisateur |
|-------|--------------------------|
| Inscription | "Bienvenue ! 14 jours d'essai gratuit" |
| Pendant le trial | Bannière "X jours restants" + bouton "Activer mon abonnement" |
| Clic "Activer" | Stripe Checkout → paiement → abonnement actif |
| Trial expiré sans paiement | Compte bloqué, redirect vers page tarifs |
| Après paiement | "Abonnement actif" (pas "Essai gratuit") |

### Tarifs rappel
- STARTER : 9,99€/mois (7% commission)
- PRO : 29,99€/mois (0% commission)  
- STUDIO : 59,99€/mois (0% commission) — déjà fonctionnel

Stack : Laravel 12, Livewire 3.7, Stripe Checkout, Cashier v16.

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT PROMPT I ==="

# ── FLUX ABONNEMENT ARTISTE ──
echo "--- ABONNEMENT ARTISTE ---"

# I0a. Routes abonnement artiste (tattooer/pierceur)
php artisan route:list 2>&1 | grep -i "tattooer.*subscri\|tattooer.*billing\|tattooer.*plan\|tattooer.*upgrade\|pierceur.*subscri\|pricing\|tarif" | head -15

# I0b. Controller abonnement artiste
grep -n "function.*subscri\|function.*billing\|function.*upgrade\|function.*plan\|function.*pricing" app/Http/Controllers/TattooerController.php 2>/dev/null | head -10
grep -n "function.*subscri\|function.*billing\|function.*upgrade" app/Http/Controllers/PiercerController.php 2>/dev/null | head -10

# I0c. Service abonnement artiste (existe-t-il ?)
find app/Services -name "*Subscription*" -o -name "*Billing*" -o -name "*Artis*Billing*" | head -5

# I0d. Le StudioBillingService — peut-on le généraliser ?
grep -n "function " app/Services/StudioBillingService.php | head -15

# I0e. Vue settings tattooer — section abonnement
grep -n "plan\|abonnement\|subscri\|billing\|PRO\|starter\|upgrade\|passer.*pro" resources/views/tattooer/settings.blade.php | head -20

# I0f. Vue settings pierceur
find resources/views -path "*pierc*" -name "*settings*" | head -3
grep -n "plan\|abonnement\|subscri\|PRO\|starter\|upgrade" resources/views/piercer/settings.blade.php 2>/dev/null | head -10

# I0g. Bannière trial actuelle
cat resources/views/partials/trial-banner.blade.php 2>/dev/null | head -40

# I0h. Dashboard tattooer — bannière trial incluse ?
grep -n "trial-banner\|trial_banner" resources/views/tattooer/dashboard.blade.php 2>/dev/null | head -5
grep -n "trial-banner\|trial_banner" resources/views/piercer/dashboard.blade.php 2>/dev/null | head -5

# I0i. Page tarifs publique
cat resources/views/pricing.blade.php 2>/dev/null | head -40
# OU
find resources/views -name "*pricing*" -o -name "*tarif*" -o -name "*subscription-plan*" | head -5

# I0j. Price IDs configurés
grep "STRIPE_PRICE" .env | head -10
grep "stripe_price_id" config/inkpik.php 2>/dev/null | head -10

# I0k. Checkout Stripe — comment c'est fait pour le studio
grep -B 5 -A 30 "function createCheckoutSession" app/Services/StudioBillingService.php | head -50

# I0l. TrialService
cat app/Services/TrialService.php 2>/dev/null | head -40

# I0m. Colonnes plan sur les artistes
php artisan tinker --execute="
  echo 'tattooers.current_plan: ' . (Schema::hasColumn('tattooers', 'current_plan') ? 'EXISTS' : 'ABSENT') . PHP_EOL;
  echo 'tattooers.plan: ' . (Schema::hasColumn('tattooers', 'plan') ? 'EXISTS' : 'ABSENT') . PHP_EOL;
  echo 'tattooers.is_subscribed: ' . (Schema::hasColumn('tattooers', 'is_subscribed') ? 'EXISTS' : 'ABSENT') . PHP_EOL;
  echo 'tattooers.trial_ends_at: ' . (Schema::hasColumn('tattooers', 'trial_ends_at') ? 'EXISTS' : 'ABSENT') . PHP_EOL;
  echo 'tattooers.is_blocked: ' . (Schema::hasColumn('tattooers', 'is_blocked') ? 'EXISTS' : 'ABSENT') . PHP_EOL;
"

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX I1 — SERVICE BILLING UNIFIÉ (ARTISTES + STUDIO)

### Problème
Le `StudioBillingService` ne gère que les studios. Il faut un service qui gère AUSSI les artistes indépendants (tattooers/piercers).

### Créer ArtistBillingService

```php
// app/Services/ArtistBillingService.php
namespace App\Services;

use App\Models\User;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Enums\SubscriptionPlan;
use Illuminate\Support\Facades\Log;

class ArtistBillingService
{
    /**
     * Créer une session Stripe Checkout pour un artiste (STARTER ou PRO).
     */
    public function createCheckoutSession(User $user, string $plan): string
    {
        $artisan = $user->tattooer ?? $user->piercer;
        if (!$artisan) throw new \Exception('Utilisateur sans profil artiste.');

        $subscriptionPlan = SubscriptionPlan::tryFrom($plan);
        if (!$subscriptionPlan || $subscriptionPlan === SubscriptionPlan::STUDIO) {
            throw new \Exception("Plan invalide pour un artiste : {$plan}");
        }

        // Récupérer le Price ID
        $priceId = config("inkpik.pricing.{$plan}.stripe_price_id");
        if (!$priceId || str_starts_with($priceId, 'price_xxx')) {
            throw new \Exception("Stripe Price ID non configuré pour le plan {$plan}. Vérifiez STRIPE_PRICE_ID_" . strtoupper($plan) . " dans .env");
        }

        // Créer le customer Stripe si nécessaire
        if (!$user->hasStripeId()) {
            $user->createAsStripeCustomer([
                'name' => $user->name,
                'email' => $user->email,
                'metadata' => [
                    'artist_id' => $artisan->id,
                    'artist_type' => $artisan instanceof Tattooer ? 'tattooer' : 'piercer',
                    'plan' => $plan,
                ],
            ]);
        }

        // Construire les paramètres Checkout
        $params = [
            'customer' => $user->stripe_id,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription',
            'success_url' => route('tattooer.billing') . '?checkout=success&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('tattooer.billing') . '?checkout=cancel',
            'subscription_data' => [
                'metadata' => [
                    'artist_id' => $artisan->id,
                    'artist_type' => $artisan instanceof Tattooer ? 'tattooer' : 'piercer',
                    'plan' => $plan,
                ],
            ],
            'metadata' => [
                'plan' => $plan,
            ],
        ];

        // Coupon bêta si applicable
        $betaService = app(BetaService::class);
        if ($betaService->isActiveBetaTester($user)) {
            $params['discounts'] = [
                ['coupon' => $betaService->getStripeCouponId()],
            ];
        }

        // Créer la session Stripe
        $stripe = new \Stripe\StripeClient(config('cashier.secret'));
        $session = $stripe->checkout->sessions->create($params);

        Log::info('Artist Checkout Session created', [
            'session_id' => $session->id,
            'user_id' => $user->id,
            'plan' => $plan,
            'price_id' => $priceId,
        ]);

        return $session->url;
    }

    /**
     * Synchroniser l'abonnement depuis une Checkout Session.
     */
    public function syncFromCheckoutSession(User $user, string $sessionId): bool
    {
        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $session = $stripe->checkout->sessions->retrieve($sessionId, [
                'expand' => ['subscription'],
            ]);

            if (!$session->subscription) return false;

            $stripeSub = is_string($session->subscription)
                ? $stripe->subscriptions->retrieve($session->subscription)
                : $session->subscription;

            if (!in_array($stripeSub->status, ['active', 'trialing'])) return false;

            // Mettre à jour stripe_id sur User si nécessaire
            if (!$user->stripe_id && $session->customer) {
                $user->update(['stripe_id' => $session->customer]);
            }

            // Créer l'enregistrement Cashier
            $user->subscriptions()->updateOrCreate(
                ['type' => 'default'],
                [
                    'stripe_id' => $stripeSub->id,
                    'stripe_status' => $stripeSub->status,
                    'stripe_price' => $stripeSub->items->data[0]->price->id ?? null,
                    'quantity' => 1,
                    'trial_ends_at' => $stripeSub->trial_end
                        ? \Carbon\Carbon::createFromTimestamp($stripeSub->trial_end)
                        : null,
                    'ends_at' => null,
                ]
            );

            // Terminer le trial immédiatement (l'utilisateur a payé)
            if ($stripeSub->status === 'trialing') {
                $stripe->subscriptions->update($stripeSub->id, [
                    'trial_end' => 'now',
                ]);
                $user->subscription('default')?->update([
                    'stripe_status' => 'active',
                    'trial_ends_at' => null,
                ]);
            }

            // Mettre à jour l'artiste
            $artisan = $user->tattooer ?? $user->piercer;
            if ($artisan) {
                $plan = $session->metadata->plan ?? $this->detectPlanFromPrice($stripeSub->items->data[0]->price->id ?? '');
                $artisan->update([
                    'is_subscribed' => true,
                    'is_blocked' => false,
                    'trial_ends_at' => null,
                    'current_plan' => $plan,
                ]);
            }

            Log::info('Artist subscription synced', [
                'user_id' => $user->id,
                'plan' => $plan ?? 'unknown',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Artist syncFromCheckoutSession error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Détecter le plan depuis le Price ID.
     */
    private function detectPlanFromPrice(string $priceId): string
    {
        if ($priceId === config('inkpik.pricing.pro.stripe_price_id')) return 'pro';
        if ($priceId === config('inkpik.pricing.starter.stripe_price_id')) return 'starter';
        return 'starter';
    }

    /**
     * Annuler l'abonnement.
     */
    public function cancel(User $user, bool $immediate = false): bool
    {
        try {
            if (!$user->subscribed('default')) return false;

            if ($immediate) {
                $user->subscription('default')->cancelNow();
            } else {
                $user->subscription('default')->cancel();
            }

            $artisan = $user->tattooer ?? $user->piercer;
            if ($artisan && $immediate) {
                $artisan->update(['is_subscribed' => false]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Artist cancel error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Infos abonnement.
     */
    public function getSubscriptionInfo(User $user): ?array
    {
        try {
            $sub = $user->subscription('default');
            if (!$sub) return null;

            return [
                'active' => !$sub->canceled(),
                'on_trial' => $sub->onTrial(),
                'on_grace_period' => $sub->onGracePeriod(),
                'ends_at' => $sub->ends_at,
                'trial_ends_at' => $sub->trial_ends_at,
                'stripe_status' => $sub->stripe_status,
                'stripe_price' => $sub->stripe_price,
                'plan' => $this->detectPlanFromPrice($sub->stripe_price ?? ''),
                'canceled' => $sub->canceled(),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * URL portail Stripe.
     */
    public function billingPortalUrl(User $user): ?string
    {
        try {
            if (!$user->hasStripeId()) return null;
            return $user->billingPortalUrl(route('tattooer.billing'));
        } catch (\Exception $e) {
            return null;
        }
    }
}
```

```bash
git add -A && git commit -m "feat(I1): ArtistBillingService — Checkout Stripe pour STARTER et PRO"
```

---

## FIX I2 — CONTROLLER + ROUTES BILLING ARTISTE

### Ajouter les méthodes billing dans TattooerController

```php
// Dans TattooerController

public function billing(Request $request)
{
    $user = auth()->user();
    $artisan = $user->tattooer ?? $user->piercer;
    $billingService = app(\App\Services\ArtistBillingService::class);

    // Retour Stripe Checkout
    if ($request->has('checkout')) {
        if ($request->get('checkout') === 'success' && $request->has('session_id')) {
            sleep(2);
            $synced = $billingService->syncFromCheckoutSession($user, $request->get('session_id'));

            return redirect()->route('tattooer.billing')
                ->with($synced ? 'success' : 'warning',
                    $synced ? 'Abonnement activé avec succès !' : 'Synchronisation en cours, rechargez dans quelques instants.');
        }
        if ($request->get('checkout') === 'cancel') {
            return redirect()->route('tattooer.billing')
                ->with('warning', 'Paiement annulé.');
        }
    }

    $subscriptionInfo = $billingService->getSubscriptionInfo($user);
    $isSubscribed = $user->subscribed('default');
    $portalUrl = $billingService->billingPortalUrl($user);

    $trialService = app(\App\Services\TrialService::class);
    $isOnTrial = $artisan && $trialService->isOnTrial($artisan);
    $daysRemaining = $artisan ? $trialService->trialDaysRemaining($artisan) : 0;
    $currentPlan = $artisan->current_plan ?? $artisan->plan ?? 'starter';

    return view('tattooer.billing', compact(
        'artisan', 'subscriptionInfo', 'isSubscribed', 'portalUrl',
        'isOnTrial', 'daysRemaining', 'currentPlan',
    ));
}

public function subscribe(Request $request)
{
    $user = auth()->user();
    $plan = $request->get('plan', 'starter');
    $billingService = app(\App\Services\ArtistBillingService::class);

    try {
        $checkoutUrl = $billingService->createCheckoutSession($user, $plan);
        return redirect($checkoutUrl);
    } catch (\Exception $e) {
        return back()->with('error', 'Erreur : ' . $e->getMessage());
    }
}

public function cancelSubscription(Request $request)
{
    $user = auth()->user();
    $billingService = app(\App\Services\ArtistBillingService::class);

    $success = $billingService->cancel($user, $request->boolean('immediate', false));

    return redirect()->route('tattooer.billing')
        ->with($success ? 'success' : 'error',
            $success ? 'Abonnement annulé.' : 'Erreur lors de l\'annulation.');
}
```

### Routes

```php
// routes/web.php — dans le groupe tattooer auth
Route::get('/billing', [TattooerController::class, 'billing'])->name('tattooer.billing');
Route::post('/subscribe', [TattooerController::class, 'subscribe'])->name('tattooer.subscribe');
Route::post('/subscription/cancel', [TattooerController::class, 'cancelSubscription'])->name('tattooer.subscription.cancel');
```

Vérifier que les routes n'existent pas déjà et ne sont pas en conflit :
```bash
php artisan route:list --name="tattooer" 2>&1 | grep "billing\|subscribe\|cancel" | head -5
```

Faire de même pour le pierceur SI le pierceur a ses propres routes (sinon, les routes tattooer couvrent les deux via `$user->tattooer ?? $user->piercer`).

```bash
git add -A && git commit -m "feat(I2): routes + controller billing artiste — STARTER et PRO"
```

---

## FIX I3 — VUE BILLING ARTISTE

Créer `resources/views/tattooer/billing.blade.php` :

```blade
@extends('layouts.tattooer')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-ivoire-text mb-6">Mon abonnement</h1>

    {{-- Messages flash --}}
    @foreach (['success' => 'green', 'error' => 'rouge-alerte', 'warning' => 'yellow'] as $type => $color)
        @if (session($type))
            <div class="mb-6 p-4 bg-{{ $color }}-500/10 border border-{{ $color }}-500/30 rounded-xl text-sm text-{{ $color }}-400">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    {{-- ÉTAT 1 : Abonnement actif payé --}}
    @if ($isSubscribed && $subscriptionInfo && $subscriptionInfo['stripe_status'] === 'active')
        <div class="bg-gris-fonde rounded-xl border border-green-500/30 p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="flex h-3 w-3 rounded-full bg-green-400"></span>
                <h2 class="text-lg font-semibold text-ivoire-text">Abonnement actif</h2>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-titane">Plan</p>
                    <p class="text-ivoire-text font-medium capitalize">{{ $subscriptionInfo['plan'] ?? $currentPlan }}</p>
                </div>
                <div>
                    <p class="text-titane">Depuis le</p>
                    <p class="text-ivoire-text">{{ $subscriptionInfo['created_at'] ?? '—' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-titane/10">
                @if ($portalUrl)
                    <a href="{{ $portalUrl }}" target="_blank"
                        class="px-4 py-2 text-sm bg-gris-fonde text-titane border border-titane/30 rounded-lg hover:text-ivoire-text transition-colors">
                        Gérer le paiement (Stripe)
                    </a>
                @endif

                {{-- Upgrade vers PRO si STARTER --}}
                @if (($subscriptionInfo['plan'] ?? $currentPlan) === 'starter')
                    <form method="POST" action="{{ route('tattooer.subscribe') }}">
                        @csrf
                        <input type="hidden" name="plan" value="pro">
                        <button type="submit" class="px-4 py-2 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                            Passer au plan PRO — 0% commission
                        </button>
                    </form>
                @endif

                {{-- Annuler --}}
                <form method="POST" action="{{ route('tattooer.subscription.cancel') }}"
                    onsubmit="return confirm('Voulez-vous annuler votre abonnement ? Vous conserverez l\'accès jusqu\'à la fin de la période payée.')">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm text-rouge-alerte border border-rouge-alerte/30 rounded-lg hover:bg-rouge-alerte/10 transition-colors">
                        Annuler l'abonnement
                    </button>
                </form>
            </div>
        </div>

    {{-- ÉTAT 2 : En période d'essai (pas encore payé) --}}
    @elseif ($isOnTrial)
        <div class="bg-gris-fonde rounded-xl border border-beige-peau/30 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="text-xl">🎁</span>
                    <h2 class="text-lg font-semibold text-ivoire-text">Période d'essai</h2>
                </div>
                <span class="px-3 py-1 text-sm font-medium bg-beige-peau/10 text-beige-peau rounded-full">
                    {{ $daysRemaining }} jour{{ $daysRemaining > 1 ? 's' : '' }} restant{{ $daysRemaining > 1 ? 's' : '' }}
                </span>
            </div>

            <p class="text-sm text-titane mb-2">
                Vous êtes actuellement sur le plan <strong class="text-ivoire-text capitalize">{{ $currentPlan }}</strong> en essai gratuit.
            </p>
            <p class="text-sm text-titane mb-6">
                Activez votre abonnement maintenant pour ne pas perdre l'accès à la fin de l'essai.
                Votre profil sera masqué de la marketplace si aucun abonnement n'est activé.
            </p>

            {{-- Choix des plans --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Plan STARTER --}}
                <div class="p-5 bg-noir-profond/30 rounded-xl border {{ $currentPlan === 'starter' ? 'border-beige-peau/40' : 'border-titane/10' }}">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-bold text-ivoire-text">Starter</h3>
                        @if ($currentPlan === 'starter')
                            <span class="text-xs text-beige-peau">Plan actuel</span>
                        @endif
                    </div>
                    <p class="text-2xl font-bold text-ivoire-text">9,99€<span class="text-sm text-titane font-normal">/mois</span></p>
                    <p class="text-xs text-titane mt-1 mb-4">Commission 7% par prestation</p>

                    <ul class="space-y-1.5 text-xs text-titane mb-4">
                        <li class="flex items-center gap-1.5">
                            <span class="text-beige-peau">✓</span> Profil vérifié dans la marketplace
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-beige-peau">✓</span> Gestion des demandes & RDV
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-beige-peau">✓</span> Messagerie client
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-beige-peau">✓</span> Fiches clients & traçabilité
                        </li>
                    </ul>

                    <form method="POST" action="{{ route('tattooer.subscribe') }}">
                        @csrf
                        <input type="hidden" name="plan" value="starter">
                        <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium {{ $currentPlan === 'starter' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane border border-titane/30' }} rounded-lg hover:opacity-90 transition-colors">
                            Activer le plan Starter
                        </button>
                    </form>
                </div>

                {{-- Plan PRO --}}
                <div class="p-5 bg-noir-profond/30 rounded-xl border {{ $currentPlan === 'pro' ? 'border-beige-peau/40' : 'border-titane/10' }} relative">
                    <span class="absolute -top-2.5 left-4 px-2 py-0.5 text-[10px] font-bold bg-beige-peau text-noir-profond rounded-full">
                        RECOMMANDÉ
                    </span>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-bold text-ivoire-text">Pro</h3>
                        @if ($currentPlan === 'pro')
                            <span class="text-xs text-beige-peau">Plan actuel</span>
                        @endif
                    </div>
                    <p class="text-2xl font-bold text-beige-peau">29,99€<span class="text-sm text-titane font-normal">/mois</span></p>
                    <p class="text-xs text-titane mt-1 mb-4">0% de commission !</p>

                    <ul class="space-y-1.5 text-xs text-titane mb-4">
                        <li class="flex items-center gap-1.5">
                            <span class="text-beige-peau">✓</span> Tout le plan Starter
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-beige-peau">✓</span> 0% commission
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-beige-peau">✓</span> Mise en avant marketplace
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-beige-peau">✓</span> Export PDF & comptabilité
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-beige-peau">✓</span> Badge PRO vérifié
                        </li>
                    </ul>

                    <form method="POST" action="{{ route('tattooer.subscribe') }}">
                        @csrf
                        <input type="hidden" name="plan" value="pro">
                        <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                            Activer le plan Pro
                        </button>
                    </form>
                </div>
            </div>
        </div>

    {{-- ÉTAT 3 : Bloqué (trial expiré) --}}
    @elseif ($artisan?->is_blocked)
        <div class="bg-rouge-alerte/5 rounded-xl border border-rouge-alerte/30 p-6 mb-6 text-center">
            <span class="text-3xl">🔒</span>
            <h2 class="text-lg font-semibold text-ivoire-text mt-3">Votre essai est terminé</h2>
            <p class="text-sm text-titane mt-2 mb-6">
                Votre profil n'est plus visible. Choisissez un abonnement pour réactiver votre compte.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg mx-auto">
                <form method="POST" action="{{ route('tattooer.subscribe') }}">
                    @csrf
                    <input type="hidden" name="plan" value="starter">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium bg-gris-fonde text-titane border border-titane/30 rounded-lg hover:text-ivoire-text transition-colors">
                        Starter — 9,99€/mois
                    </button>
                </form>
                <form method="POST" action="{{ route('tattooer.subscribe') }}">
                    @csrf
                    <input type="hidden" name="plan" value="pro">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                        Pro — 29,99€/mois
                    </button>
                </form>
            </div>
        </div>

    {{-- ÉTAT 4 : Grace period --}}
    @elseif ($subscriptionInfo && ($subscriptionInfo['on_grace_period'] ?? false))
        <div class="bg-gris-fonde rounded-xl border border-yellow-500/30 p-6 mb-6">
            <h2 class="text-lg font-semibold text-ivoire-text mb-2">Abonnement annulé</h2>
            <p class="text-sm text-titane">
                Accès conservé jusqu'au <strong>{{ $subscriptionInfo['ends_at']?->format('d/m/Y') ?? '—' }}</strong>.
            </p>
            <form method="POST" action="{{ route('tattooer.subscribe') }}" class="mt-4">
                @csrf
                <input type="hidden" name="plan" value="{{ $currentPlan }}">
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                    Réactiver l'abonnement
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
```

```bash
git add -A && git commit -m "feat(I3): vue billing artiste — trial visible + activation STARTER et PRO"
```

---

## FIX I4 — BANNIÈRE TRIAL + LIEN BILLING DANS SETTINGS

### Mettre à jour la bannière trial

La bannière dans `partials/trial-banner.blade.php` doit inclure un lien vers la page billing :

```blade
{{-- Dans la bannière trial, remplacer le lien route('pricing') par route('tattooer.billing') --}}
{{-- AVANT --}}
<a href="{{ route('pricing') }}" ...>Voir les tarifs</a>

{{-- APRÈS --}}
@if (auth()->user()->studio)
    <a href="{{ route('studio.billing') }}" ...>Activer mon abonnement</a>
@else
    <a href="{{ route('tattooer.billing') }}" ...>Activer mon abonnement</a>
@endif
```

### Ajouter le lien billing dans la sidebar/settings tattooer

```bash
# Trouver la sidebar tattooer
find resources/views -path "*tattooer*" -name "*sidebar*" -o -path "*tattooer*" -name "*nav*" | head -5
```

Ajouter un item "Abonnement" dans la sidebar :

```blade
<a href="{{ route('tattooer.billing') }}"
    class="{{ request()->routeIs('tattooer.billing') ? 'text-beige-peau bg-beige-peau/10' : 'text-titane hover:text-ivoire-text' }} flex items-center gap-3 px-3 py-2 rounded-lg transition-colors">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
    </svg>
    Abonnement
    @if ($isOnTrial ?? false)
        <span class="ml-auto px-1.5 py-0.5 text-[10px] bg-beige-peau/10 text-beige-peau rounded">Essai</span>
    @endif
</a>
```

### Supprimer ou remplacer le bouton "Passer PRO" isolé dans les settings

```bash
grep -n "passer.*pro\|upgrade.*pro\|plan.*PRO" resources/views/tattooer/settings.blade.php | head -10
```

Remplacer par un lien vers la page billing :
```blade
{{-- AVANT --}}
<a href="...">Passer au plan PRO</a>

{{-- APRÈS --}}
<a href="{{ route('tattooer.billing') }}" class="...">
    Gérer mon abonnement
</a>
```

```bash
git add -A && git commit -m "feat(I4): bannière trial → lien billing + sidebar + remplacement bouton PRO isolé"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT I ==="

# V1. Service
ls app/Services/ArtistBillingService.php && echo "Service OK"

# V2. Routes
php artisan route:list --name="tattooer" 2>&1 | grep "billing\|subscribe" | head -5

# V3. Vue
ls resources/views/tattooer/billing.blade.php && echo "Vue billing OK"

# V4. Lien sidebar
grep -c "tattooer.billing\|Abonnement" resources/views/tattooer/partials/sidebar.blade.php 2>/dev/null
echo "Lien sidebar (doit être > 0)"

# V5. Bannière trial mise à jour
grep -c "tattooer.billing\|Activer mon abonnement" resources/views/partials/trial-banner.blade.php 2>/dev/null
echo "Lien billing dans bannière (doit être > 0)"

# V6. Compilation
php artisan route:clear && php artisan view:clear
php artisan route:list 2>&1 | head -3

echo "=== PROMPT I TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 révèle les routes et vues existantes
2. **ArtistBillingService** est séparé de StudioBillingService — pas de mélange
3. **Le Checkout termine le trial immédiatement** (`trial_end: 'now'`) — pas de statut "Essai gratuit" après paiement
4. **Colonne plan** : vérifier si c'est `current_plan` ou `plan` sur le modèle (Phase 0 le révèle)
5. **STARTER et PRO** utilisent les mêmes routes — le plan est passé en hidden input
6. **Ne pas casser le flux studio** — le studio garde son propre StudioBillingService
7. **Pierceur** : même flux que tattooer (via `$user->tattooer ?? $user->piercer`)
8. **Commit après chaque fix** (4 commits)
