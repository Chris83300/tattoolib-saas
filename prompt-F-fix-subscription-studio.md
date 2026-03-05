# 🚨 PROMPT F — FIX CRITIQUE ABONNEMENT STUDIO
# Pour Claude Code — Subscription Cashier, billing, webhook, annulation
# URGENCE ÉLEVÉE — Commit après chaque fix

## CONTEXTE

Bug bloquant : après paiement Stripe Checkout, l'abonnement studio n'est pas détecté.

**Erreur** :
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'subscriptions.studio_id' in 'where clause'
```

**Cause racine** : Laravel Cashier lie les subscriptions au modèle qui a le trait `Billable`. Si le trait `Billable` est sur le modèle `Studio`, Cashier cherche `subscriptions.studio_id`. Or la table `subscriptions` standard Cashier n'a que `user_id`.

**Architecture cible** : Le trait `Billable` doit être sur le modèle `User` (pas `Studio`). Toute la logique d'abonnement passe par `$studio->user->subscribed()`.

Stack : Laravel 12, Laravel Cashier, Stripe Connect, Livewire 3.7.

---

## PHASE 0 — AUDIT COMPLET

```bash
echo "=== AUDIT SUBSCRIPTION STUDIO ==="

# ── MODÈLE STUDIO ──
echo "--- MODÈLE STUDIO ---"

# F0a. Trait Billable sur Studio ?
grep -n "Billable\|HasStripeId\|Cashier\|ManagesSubscriptions\|subscription" app/Models/Studio.php | head -15

# F0b. Trait Billable sur User ?
grep -n "Billable\|HasStripeId\|Cashier" app/Models/User.php | head -10

# F0c. Méthodes subscription/subscribed sur Studio
grep -B 2 -A 10 "function.*subscri\|function.*isSubscribed\|function.*billing\|function.*stripe" app/Models/Studio.php | head -40

# F0d. Colonnes Stripe sur Studio
php artisan tinker --execute="
  \$cols = Schema::getColumnListing('studios');
  \$stripeCols = array_filter(\$cols, fn(\$c) => str_contains(\$c, 'stripe') || str_contains(\$c, 'trial') || str_contains(\$c, 'pm_') || str_contains(\$c, 'subscri'));
  echo 'Studios stripe cols: ' . implode(', ', \$stripeCols) . PHP_EOL;
  
  \$userCols = Schema::getColumnListing('users');
  \$userStripeCols = array_filter(\$userCols, fn(\$c) => str_contains(\$c, 'stripe') || str_contains(\$c, 'pm_'));
  echo 'Users stripe cols: ' . implode(', ', \$userStripeCols) . PHP_EOL;
"

# F0e. Table subscriptions
php artisan tinker --execute="
  if (Schema::hasTable('subscriptions')) {
    echo 'subscriptions: ' . implode(', ', Schema::getColumnListing('subscriptions')) . PHP_EOL;
    echo 'Rows: ' . DB::table('subscriptions')->count() . PHP_EOL;
    // Afficher les données existantes
    \$subs = DB::table('subscriptions')->limit(5)->get();
    foreach(\$subs as \$s) {
      echo '  #' . \$s->id . ' user_id=' . (\$s->user_id ?? 'NULL') . ' type=' . (\$s->type ?? \$s->name ?? 'NULL') . ' stripe_id=' . (\$s->stripe_id ?? 'NULL') . ' status=' . (\$s->stripe_status ?? 'NULL') . PHP_EOL;
    }
  } else {
    echo 'TABLE subscriptions ABSENTE' . PHP_EOL;
  }
"

# ── SERVICE BILLING ──
echo "--- BILLING SERVICE ---"

# F0f. StudioBillingService complet
cat app/Services/StudioBillingService.php 2>/dev/null

# F0g. StripeStudioSubscriptionService
cat app/Services/StripeStudioSubscriptionService.php 2>/dev/null

# ── CONTROLLER ──
echo "--- CONTROLLER ---"

# F0h. Méthodes billing dans StudioController
grep -B 2 -A 20 "function.*billing\|function.*subscribe\|function.*cancel\|function.*showSubscribe\|function.*processSubscribe" app/Http/Controllers/StudioController.php | head -80

# F0i. Routes billing
php artisan route:list 2>&1 | grep -i "billing\|subscribe\|subscription\|cancel\|plan\|upgrade" | head -15

# ── STRIPE ──
echo "--- STRIPE ---"

# F0j. Webhook controller
find app/Http/Controllers -name "*Webhook*" -o -name "*Stripe*" | head -5
grep -n "function " app/Http/Controllers/StripeWebhookController.php 2>/dev/null | head -20

# F0k. Cashier listeners
grep -rn "WebhookController\|handleWebhook\|customer.subscription\|invoice.paid\|checkout.session" app/ --include="*.php" | head -15

# F0l. Cashier config
cat config/cashier.php 2>/dev/null | head -20

# F0m. Routes Stripe webhook
php artisan route:list 2>&1 | grep -i "webhook\|stripe" | head -5

# ── VUES ──
echo "--- VUES ---"

# F0n. Vue billing
cat resources/views/studio/billing.blade.php 2>/dev/null | head -60
# OU
find resources/views/studio -name "*billing*" -o -name "*subscribe*" -o -name "*plan*" | head -5

# F0o. TOUTES les occurrences de $studio->subscription ou $studio->subscribed
grep -rn "\$studio->subscri\|\$studio->subscription\|studio->isSubscribed\|studio->subscribed" app/ --include="*.php" | head -20
grep -rn "\$studio->subscri\|\$studio->subscription" resources/views/ --include="*.blade.php" | head -10

# ── ANNULATION ──
echo "--- ANNULATION ---"

# F0p. Logique d'annulation existante
grep -rn "cancel\|annul\|unsubscribe\|cancelSubscription\|cancelNow" app/ --include="*.php" | head -15

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX F1 — CORRIGER LE TRAIT BILLABLE

### Le problème fondamental

Le trait `Billable` de Cashier crée la relation `subscriptions()` qui cherche `subscriptions.{model}_id`. Si `Billable` est sur `Studio`, il cherche `subscriptions.studio_id` qui n'existe pas.

### Solution

**Le trait `Billable` DOIT être sur `User`, PAS sur `Studio`.**

```bash
# Vérifier l'état actuel
grep -n "use Billable\|use Laravel\\Cashier" app/Models/Studio.php app/Models/User.php 2>/dev/null
```

#### Scénario A — `Billable` est sur `Studio` (le plus probable vu l'erreur)

```php
// app/Models/Studio.php
// RETIRER le trait Billable de Studio
// AVANT :
use Laravel\Cashier\Billable;

class Studio extends Model
{
    use Billable; // ← RETIRER CECI
}

// APRÈS :
class Studio extends Model
{
    // PAS de Billable ici
    
    /**
     * Accès à l'abonnement via le User propriétaire.
     */
    public function subscription(string $type = 'default')
    {
        return $this->user?->subscription($type);
    }

    public function subscribed(string $type = 'default'): bool
    {
        return $this->user?->subscribed($type) ?? false;
    }

    public function onTrial(): bool
    {
        return $this->user?->onTrial() ?? false;
    }

    public function hasStripeId(): bool
    {
        return $this->user?->hasStripeId() ?? false;
    }

    /**
     * Créer ou récupérer le customer Stripe via le User.
     */
    public function createOrGetStripeCustomer(array $options = [])
    {
        return $this->user?->createOrGetStripeCustomer($options);
    }

    /**
     * ID client Stripe (via le User).
     */
    public function stripeId(): ?string
    {
        return $this->user?->stripe_id;
    }
}
```

Et s'assurer que le trait `Billable` EST sur `User` :

```php
// app/Models/User.php
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Billable; // ← DOIT ÊTRE ICI
    // ...
}
```

#### Scénario B — `Billable` est déjà sur `User` mais `Studio` a ses propres méthodes qui interfèrent

Vérifier et corriger les méthodes `subscription()`/`subscribed()` sur Studio pour qu'elles délèguent TOUJOURS au User :

```php
// Toute méthode subscription-related sur Studio DOIT passer par $this->user
```

#### Vérifier les colonnes Cashier sur users

```bash
php artisan tinker --execute="
  \$required = ['stripe_id', 'pm_type', 'pm_last_four', 'trial_ends_at'];
  foreach(\$required as \$col) {
    echo \$col . ' on users: ' . (Schema::hasColumn('users', \$col) ? 'OK' : 'ABSENT') . PHP_EOL;
  }
"
```

Si des colonnes Cashier manquent sur `users`, les ajouter :

```bash
php artisan make:migration add_cashier_columns_to_users_table
```

```php
Schema::table('users', function (Blueprint $table) {
    if (!Schema::hasColumn('users', 'stripe_id')) {
        $table->string('stripe_id')->nullable()->index();
    }
    if (!Schema::hasColumn('users', 'pm_type')) {
        $table->string('pm_type')->nullable();
    }
    if (!Schema::hasColumn('users', 'pm_last_four')) {
        $table->string('pm_last_four', 4)->nullable();
    }
    if (!Schema::hasColumn('users', 'trial_ends_at')) {
        $table->timestamp('trial_ends_at')->nullable();
    }
});
```

```bash
php artisan migrate
git add -A && git commit -m "fix(F1): Billable sur User pas Studio — subscription() délègue au User"
```

---

## FIX F2 — CORRIGER StudioBillingService

### Remplacer TOUTES les références directes

```bash
# Trouver TOUTES les occurrences problématiques
grep -rn "\$studio->subscri\|\$studio->subscription\|\$studio->newSubscription\|\$studio->createAsStripeCustomer\|\$studio->stripe_id\|\$studio->pm_\|\$studio->onTrial\b" app/ --include="*.php" | head -30
```

Pour CHAQUE occurrence trouvée, remplacer par l'accès via `$studio->user` :

```php
// app/Services/StudioBillingService.php — RÉÉCRIRE PROPREMENT

namespace App\Services;

use App\Models\Studio;
use App\Models\User;
use App\Enums\SubscriptionPlan;
use Illuminate\Support\Facades\Log;

class StudioBillingService
{
    /**
     * Le studio est-il abonné ?
     */
    public function isSubscribed(Studio $studio): bool
    {
        try {
            $user = $studio->user;
            if (!$user) return false;

            // Check Cashier subscription
            if ($user->subscribed('default')) return true;

            // Fallback : check le flag is_subscribed sur le studio
            if ($studio->is_subscribed) return true;

            // Fallback : check trial
            if ($studio->trial_ends_at && $studio->trial_ends_at->isFuture()) return true;

            return false;
        } catch (\Exception $e) {
            Log::warning('StudioBillingService::isSubscribed error', [
                'studio_id' => $studio->id,
                'error' => $e->getMessage(),
            ]);
            // En cas d'erreur SQL, se rabattre sur le flag
            return $studio->is_subscribed ?? false;
        }
    }

    /**
     * Créer une session Stripe Checkout pour l'abonnement studio.
     */
    public function createCheckoutSession(Studio $studio, string $plan = 'studio'): string
    {
        $user = $studio->user;
        if (!$user) throw new \Exception('Studio sans utilisateur propriétaire.');

        // Créer le customer Stripe si pas encore fait
        if (!$user->hasStripeId()) {
            $user->createAsStripeCustomer([
                'name' => $user->name,
                'email' => $user->email,
                'metadata' => [
                    'studio_id' => $studio->id,
                    'studio_name' => $studio->name,
                ],
            ]);
        }

        $priceId = config("inkpik.pricing.{$plan}.stripe_price_id");
        if (!$priceId) throw new \Exception("Stripe Price ID manquant pour le plan {$plan}.");

        // Paramètres Checkout
        $checkoutParams = [
            'success_url' => route('studio.billing') . '?checkout=success',
            'cancel_url' => route('studio.billing') . '?checkout=cancel',
            'metadata' => [
                'studio_id' => $studio->id,
                'plan' => $plan,
            ],
        ];

        // Coupon bêta si applicable
        $betaService = app(\App\Services\BetaService::class);
        if ($betaService->isActiveBetaTester($user)) {
            $checkoutParams['discounts'] = [
                ['coupon' => $betaService->getStripeCouponId()],
            ];
        }

        // Trial : 14 jours si pas déjà consommé
        if (!$user->hasEverSubscribedTo('default')) {
            $checkoutParams['subscription_data'] = [
                'trial_period_days' => SubscriptionPlan::STUDIO->trialDays(),
            ];
        }

        $checkout = $user->newSubscription('default', $priceId)
            ->allowPromotionCodes()
            ->checkout($checkoutParams);

        return $checkout->url;
    }

    /**
     * Annuler l'abonnement (fin de période).
     */
    public function cancel(Studio $studio): bool
    {
        try {
            $user = $studio->user;
            if (!$user || !$user->subscribed('default')) return false;

            $user->subscription('default')->cancel();

            Log::info('Studio subscription cancelled', ['studio_id' => $studio->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Studio cancel error', [
                'studio_id' => $studio->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Annuler immédiatement (sans attendre la fin de période).
     */
    public function cancelNow(Studio $studio): bool
    {
        try {
            $user = $studio->user;
            if (!$user || !$user->subscribed('default')) return false;

            $user->subscription('default')->cancelNow();
            $studio->update(['is_subscribed' => false]);

            Log::info('Studio subscription cancelled immediately', ['studio_id' => $studio->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Studio cancelNow error', [
                'studio_id' => $studio->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Reprendre un abonnement annulé (avant fin de période).
     */
    public function resume(Studio $studio): bool
    {
        try {
            $user = $studio->user;
            if (!$user) return false;

            $sub = $user->subscription('default');
            if ($sub && $sub->onGracePeriod()) {
                $sub->resume();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Studio resume error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * URL du portail de facturation Stripe.
     */
    public function billingPortalUrl(Studio $studio): ?string
    {
        try {
            $user = $studio->user;
            if (!$user || !$user->hasStripeId()) return null;

            return $user->billingPortalUrl(route('studio.billing'));
        } catch (\Exception $e) {
            Log::error('Billing portal URL error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Informations de l'abonnement actuel.
     */
    public function getSubscriptionInfo(Studio $studio): ?array
    {
        try {
            $user = $studio->user;
            if (!$user) return null;

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
                'created_at' => $sub->created_at,
                'canceled' => $sub->canceled(),
            ];
        } catch (\Exception $e) {
            Log::warning('getSubscriptionInfo error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Synchroniser le statut d'abonnement depuis Stripe.
     * Utile quand les webhooks ne passent pas (environnement local).
     */
    public function syncFromStripe(Studio $studio): bool
    {
        try {
            $user = $studio->user;
            if (!$user || !$user->hasStripeId()) return false;

            $stripeCustomer = $user->asStripeCustomer();
            $stripeSubscriptions = \Stripe\Subscription::all([
                'customer' => $stripeCustomer->id,
                'status' => 'all',
                'limit' => 5,
            ]);

            foreach ($stripeSubscriptions->data as $stripeSub) {
                if (in_array($stripeSub->status, ['active', 'trialing'])) {
                    // Créer ou mettre à jour l'abonnement dans la DB
                    $user->subscriptions()->updateOrCreate(
                        ['stripe_id' => $stripeSub->id],
                        [
                            'type' => 'default',
                            'stripe_status' => $stripeSub->status,
                            'stripe_price' => $stripeSub->items->data[0]->price->id ?? null,
                            'quantity' => $stripeSub->items->data[0]->quantity ?? 1,
                            'trial_ends_at' => $stripeSub->trial_end ? \Carbon\Carbon::createFromTimestamp($stripeSub->trial_end) : null,
                            'ends_at' => $stripeSub->cancel_at ? \Carbon\Carbon::createFromTimestamp($stripeSub->cancel_at) : null,
                        ]
                    );

                    $studio->update(['is_subscribed' => true]);
                    Log::info('Studio subscription synced from Stripe', [
                        'studio_id' => $studio->id,
                        'stripe_sub' => $stripeSub->id,
                        'status' => $stripeSub->status,
                    ]);
                    return true;
                }
            }

            // Aucun abonnement actif trouvé
            $studio->update(['is_subscribed' => false]);
            return false;
        } catch (\Exception $e) {
            Log::error('syncFromStripe error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return false;
        }
    }
}
```

IMPORTANT : Adapter cette réécriture en fonction du code réel trouvé en Phase 0. Le service ci-dessus est un template complet — prendre ce qui est nécessaire.

```bash
git add -A && git commit -m "fix(F2): StudioBillingService réécrit — tout passe par User->subscription()"
```

---

## FIX F3 — CONTROLLER BILLING + VUE

### Controller

Réécrire les méthodes billing du StudioController :

```php
// Dans StudioController

public function billing(Request $request)
{
    $studio = auth()->user()->studio;
    $billingService = app(StudioBillingService::class);

    // Synchroniser depuis Stripe si checkout réussi
    if ($request->get('checkout') === 'success') {
        $billingService->syncFromStripe($studio);
        return redirect()->route('studio.billing')
            ->with('success', 'Abonnement activé avec succès !');
    }

    $subscriptionInfo = $billingService->getSubscriptionInfo($studio);
    $isSubscribed = $billingService->isSubscribed($studio);
    $portalUrl = $billingService->billingPortalUrl($studio);

    return view('studio.billing', compact('studio', 'subscriptionInfo', 'isSubscribed', 'portalUrl'));
}

public function subscribe(Request $request)
{
    $studio = auth()->user()->studio;
    $billingService = app(StudioBillingService::class);

    try {
        $checkoutUrl = $billingService->createCheckoutSession($studio);
        return redirect($checkoutUrl);
    } catch (\Exception $e) {
        return back()->with('error', 'Erreur lors de la création de la session de paiement : ' . $e->getMessage());
    }
}

public function cancelSubscription(Request $request)
{
    $studio = auth()->user()->studio;
    $billingService = app(StudioBillingService::class);

    $immediate = $request->boolean('immediate', false);

    $success = $immediate
        ? $billingService->cancelNow($studio)
        : $billingService->cancel($studio);

    if ($success) {
        $message = $immediate
            ? 'Abonnement annulé immédiatement. Vos prélèvements sont arrêtés.'
            : 'Abonnement annulé. Vous conservez l\'accès jusqu\'à la fin de la période en cours.';
        return redirect()->route('studio.billing')->with('success', $message);
    }

    return back()->with('error', 'Impossible d\'annuler l\'abonnement. Veuillez réessayer.');
}

public function resumeSubscription()
{
    $studio = auth()->user()->studio;
    $billingService = app(StudioBillingService::class);

    if ($billingService->resume($studio)) {
        return redirect()->route('studio.billing')->with('success', 'Abonnement réactivé avec succès !');
    }

    return back()->with('error', 'Impossible de réactiver l\'abonnement.');
}

/**
 * Forcer la synchronisation manuelle depuis Stripe (debug/admin).
 */
public function syncSubscription()
{
    $studio = auth()->user()->studio;
    $billingService = app(StudioBillingService::class);

    if ($billingService->syncFromStripe($studio)) {
        return redirect()->route('studio.billing')->with('success', 'Abonnement synchronisé depuis Stripe.');
    }

    return back()->with('warning', 'Aucun abonnement actif trouvé dans Stripe.');
}
```

### Routes

```php
// routes/web.php — groupe studio auth
Route::get('/billing', [StudioController::class, 'billing'])->name('studio.billing');
Route::post('/subscribe', [StudioController::class, 'subscribe'])->name('studio.subscribe');
Route::post('/subscription/cancel', [StudioController::class, 'cancelSubscription'])->name('studio.subscription.cancel');
Route::post('/subscription/resume', [StudioController::class, 'resumeSubscription'])->name('studio.subscription.resume');
Route::post('/subscription/sync', [StudioController::class, 'syncSubscription'])->name('studio.subscription.sync');
```

Vérifier que les routes existantes ne sont pas en conflit :
```bash
php artisan route:list --name="studio" 2>&1 | grep "billing\|subscribe\|cancel\|resume\|sync" | head -10
```

### Vue billing

Réécrire la vue `studio/billing.blade.php` pour gérer TOUS les états :

```blade
{{-- resources/views/studio/billing.blade.php --}}
@extends('layouts.studio')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-ivoire-text mb-6">Facturation & Abonnement</h1>

    {{-- Messages flash --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-xl text-sm text-green-400">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-xl text-sm text-rouge-alerte">
            {{ session('error') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="mb-6 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl text-sm text-yellow-400">
            {{ session('warning') }}
        </div>
    @endif

    {{-- ÉTAT 1 : Abonnement actif --}}
    @if ($isSubscribed && $subscriptionInfo && !($subscriptionInfo['canceled'] ?? false))
        <div class="bg-gris-fonde rounded-xl border border-green-500/30 p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="flex h-3 w-3 rounded-full bg-green-400"></span>
                <h2 class="text-lg font-semibold text-ivoire-text">Abonnement actif</h2>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-titane">Plan</p>
                    <p class="text-ivoire-text font-medium">Studio — {{ number_format(\App\Enums\SubscriptionPlan::STUDIO->price(), 2, ',', '') }}€/mois</p>
                </div>
                <div>
                    <p class="text-titane">Statut</p>
                    <p class="text-green-400 font-medium">
                        {{ $subscriptionInfo['on_trial'] ? 'Essai gratuit' : 'Actif' }}
                    </p>
                </div>
                @if ($subscriptionInfo['on_trial'] && $subscriptionInfo['trial_ends_at'])
                <div>
                    <p class="text-titane">Fin de l'essai</p>
                    <p class="text-ivoire-text">{{ $subscriptionInfo['trial_ends_at']->format('d/m/Y') }}</p>
                </div>
                @endif
                <div>
                    <p class="text-titane">Depuis le</p>
                    <p class="text-ivoire-text">{{ $subscriptionInfo['created_at']?->format('d/m/Y') ?? '—' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-titane/10">
                {{-- Portail Stripe --}}
                @if ($portalUrl)
                    <a href="{{ $portalUrl }}" target="_blank"
                        class="px-4 py-2 text-sm bg-gris-fonde text-titane border border-titane/30 rounded-lg hover:text-ivoire-text hover:border-beige-peau/30 transition-colors">
                        Gérer le paiement (Stripe)
                    </a>
                @endif

                {{-- Annuler --}}
                <div x-data="{ showCancel: false }">
                    <button @click="showCancel = true"
                        class="px-4 py-2 text-sm text-rouge-alerte border border-rouge-alerte/30 rounded-lg hover:bg-rouge-alerte/10 transition-colors">
                        Annuler l'abonnement
                    </button>

                    {{-- Modal confirmation annulation --}}
                    <div x-show="showCancel" x-transition x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-noir-profond/80 p-4">
                        <div class="bg-gris-fonde rounded-2xl border border-titane/20 p-6 max-w-md w-full" @click.away="showCancel = false">
                            <h3 class="text-lg font-semibold text-ivoire-text mb-3">Annuler votre abonnement ?</h3>

                            <div class="space-y-4">
                                {{-- Option 1 : Fin de période --}}
                                <form method="POST" action="{{ route('studio.subscription.cancel') }}">
                                    @csrf
                                    <div class="p-4 bg-noir-profond/30 rounded-lg">
                                        <p class="text-sm text-ivoire-text font-medium">Annuler à la fin de la période</p>
                                        <p class="text-xs text-titane mt-1">
                                            Vous conservez l'accès jusqu'à la fin de votre période payée.
                                            Vos artistes gardent leurs profils. Aucun prélèvement supplémentaire.
                                        </p>
                                        <button type="submit" class="mt-3 px-4 py-2 text-sm text-rouge-alerte border border-rouge-alerte/30 rounded-lg hover:bg-rouge-alerte/10 transition-colors">
                                            Annuler à la fin de la période
                                        </button>
                                    </div>
                                </form>

                                {{-- Option 2 : Immédiat --}}
                                <form method="POST" action="{{ route('studio.subscription.cancel') }}">
                                    @csrf
                                    <input type="hidden" name="immediate" value="1">
                                    <div class="p-4 bg-rouge-alerte/5 border border-rouge-alerte/20 rounded-lg">
                                        <p class="text-sm text-rouge-alerte font-medium">Annuler immédiatement</p>
                                        <p class="text-xs text-titane mt-1">
                                            L'abonnement est arrêté tout de suite. Vos artistes seront masqués de la marketplace.
                                            Les prélèvements sont stoppés immédiatement. Pas de remboursement au prorata.
                                        </p>
                                        <button type="submit" class="mt-3 px-4 py-2 text-sm text-white bg-rouge-alerte rounded-lg hover:bg-rouge-alerte/80 transition-colors"
                                            onclick="return confirm('Êtes-vous sûr ? Cette action est irréversible.')">
                                            Annuler maintenant
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <button @click="showCancel = false" class="w-full mt-4 px-4 py-2 text-sm text-titane hover:text-ivoire-text transition-colors">
                                Garder mon abonnement
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- ÉTAT 2 : Abonnement annulé mais en grace period --}}
    @elseif ($subscriptionInfo && ($subscriptionInfo['on_grace_period'] ?? false))
        <div class="bg-gris-fonde rounded-xl border border-yellow-500/30 p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="text-xl">⏳</span>
                <h2 class="text-lg font-semibold text-ivoire-text">Abonnement annulé</h2>
            </div>
            <p class="text-sm text-titane mb-2">
                Votre abonnement est annulé mais vous conservez l'accès jusqu'au
                <strong class="text-ivoire-text">{{ $subscriptionInfo['ends_at']?->format('d/m/Y') ?? '—' }}</strong>.
            </p>
            <p class="text-xs text-titane mb-4">Après cette date, votre studio et vos artistes seront masqués de la marketplace.</p>

            <form method="POST" action="{{ route('studio.subscription.resume') }}">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                    Réactiver l'abonnement
                </button>
            </form>
        </div>

    {{-- ÉTAT 3 : Pas d'abonnement --}}
    @else
        <div class="bg-gris-fonde rounded-xl border border-titane/10 p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="text-xl">💳</span>
                <h2 class="text-lg font-semibold text-ivoire-text">Aucun abonnement actif</h2>
            </div>
            <p class="text-sm text-titane mb-4">
                Choisissez le plan Studio pour gérer vos artistes, accéder au planning global et aux statistiques.
            </p>

            {{-- Card plan Studio --}}
            <div class="p-5 bg-noir-profond/30 rounded-xl border border-beige-peau/20 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-bold text-beige-peau">Plan Studio</h3>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-ivoire-text">{{ number_format(\App\Enums\SubscriptionPlan::STUDIO->price(), 2, ',', '') }}€<span class="text-sm text-titane font-normal">/mois</span></p>
                        <p class="text-xs text-titane">+ {{ number_format(\App\Enums\SubscriptionPlan::STUDIO->pricePerExtraArtist(), 2, ',', '') }}€ par artiste supplémentaire</p>
                    </div>
                </div>
                <ul class="space-y-1.5 text-sm text-titane mb-4">
                    @foreach (\App\Enums\SubscriptionPlan::STUDIO->features() as $feature)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-beige-peau flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>

                <form method="POST" action="{{ route('studio.subscribe') }}">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                        Commencer l'essai gratuit de 14 jours
                    </button>
                </form>
                <p class="text-xs text-titane text-center mt-2">Sans engagement. Annulable à tout moment.</p>
            </div>
        </div>

        {{-- Bouton sync pour debug --}}
        <form method="POST" action="{{ route('studio.subscription.sync') }}" class="mb-4">
            @csrf
            <button type="submit" class="text-xs text-titane hover:text-beige-peau transition-colors">
                🔄 Synchroniser l'abonnement depuis Stripe
            </button>
        </form>
    @endif
</div>
@endsection
```

```bash
git add -A && git commit -m "fix(F3): controller billing complet + vue avec 3 états + annulation + sync Stripe"
```

---

## FIX F4 — ANNULATION ARTISTES INDÉPENDANTS

La logique d'annulation doit aussi fonctionner pour les tattooers et piercers indépendants (pas que le studio).

### Vérifier les routes annulation artiste

```bash
php artisan route:list 2>&1 | grep -i "tattooer.*cancel\|tattooer.*subscri\|pierceur.*cancel\|artiste.*cancel" | head -10
```

### Si les routes d'annulation n'existent pas pour les artistes :

```php
// routes/web.php — groupe tattooer auth
Route::post('/subscription/cancel', [TattooerController::class, 'cancelSubscription'])->name('tattooer.subscription.cancel');
Route::post('/subscription/resume', [TattooerController::class, 'resumeSubscription'])->name('tattooer.subscription.resume');
```

```php
// Dans TattooerController (ou équivalent pour pierceurs)
public function cancelSubscription(Request $request)
{
    $user = auth()->user();
    $immediate = $request->boolean('immediate', false);

    try {
        if (!$user->subscribed('default')) {
            return back()->with('error', 'Aucun abonnement actif.');
        }

        if ($immediate) {
            $user->subscription('default')->cancelNow();
            $artisan = $user->tattooer ?? $user->piercer;
            if ($artisan) {
                $artisan->update(['is_subscribed' => false]);
            }
        } else {
            $user->subscription('default')->cancel();
        }

        $message = $immediate
            ? 'Abonnement annulé immédiatement.'
            : 'Abonnement annulé. Accès conservé jusqu\'à la fin de la période.';
        
        return redirect()->route('tattooer.settings')->with('success', $message);
    } catch (\Exception $e) {
        return back()->with('error', 'Erreur : ' . $e->getMessage());
    }
}

public function resumeSubscription()
{
    $user = auth()->user();
    $sub = $user->subscription('default');

    if ($sub && $sub->onGracePeriod()) {
        $sub->resume();
        return redirect()->route('tattooer.settings')->with('success', 'Abonnement réactivé !');
    }

    return back()->with('error', 'Impossible de réactiver l\'abonnement.');
}
```

Ajouter un bouton d'annulation dans les settings tattooer/pierceur si absent :

```blade
{{-- Dans la section abonnement des settings --}}
@if (auth()->user()->subscribed('default'))
    <form method="POST" action="{{ route('tattooer.subscription.cancel') }}">
        @csrf
        <button type="submit" onclick="return confirm('Voulez-vous annuler votre abonnement ? Vous conserverez l\'accès jusqu\'à la fin de la période payée.')"
            class="px-4 py-2 text-sm text-rouge-alerte border border-rouge-alerte/30 rounded-lg hover:bg-rouge-alerte/10 transition-colors">
            Annuler mon abonnement
        </button>
    </form>
@endif
```

```bash
git add -A && git commit -m "fix(F4): annulation abonnement artistes indépendants + routes + boutons"
```

---

## FIX F5 — WEBHOOK STRIPE SUBSCRIPTION EVENTS

### Problème
En local, les webhooks Stripe ne fonctionnent pas (pas de tunnel). Il faut :
1. Gérer les événements webhook quand ils arrivent (production)
2. Avoir un fallback sync pour le développement local

### Vérifier le webhook controller

```bash
cat app/Http/Controllers/StripeWebhookController.php 2>/dev/null | head -80
```

### S'assurer que les events subscription sont gérés

```php
// Dans le webhook controller (ou listeners Cashier)
// Ces events DOIVENT être gérés :

// customer.subscription.created → marquer is_subscribed = true
// customer.subscription.updated → mettre à jour le statut
// customer.subscription.deleted → marquer is_subscribed = false
// invoice.paid → confirmer le paiement
// checkout.session.completed → confirmer le checkout
```

Si le webhook controller est un `CashierController` standard, il gère déjà ces events. Sinon, ajouter les handlers :

```php
// Écouter les events Cashier dans EventServiceProvider ou listeners

// Option A : Events Cashier (Laravel Cashier gère automatiquement)
// Vérifier que la route webhook est configurée :
// Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);

// Option B : Listeners personnalisés
// Dans EventServiceProvider :
protected $listen = [
    \Laravel\Cashier\Events\WebhookReceived::class => [
        \App\Listeners\HandleStripeWebhook::class,
    ],
];
```

```php
// app/Listeners/HandleStripeWebhook.php
namespace App\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use App\Models\Studio;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class HandleStripeWebhook
{
    public function handle(WebhookReceived $event)
    {
        $payload = $event->payload;
        $type = $payload['type'] ?? '';

        match($type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($payload),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($payload),
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($payload),
            default => null,
        };
    }

    private function handleCheckoutCompleted(array $payload): void
    {
        $customerId = $payload['data']['object']['customer'] ?? null;
        if (!$customerId) return;

        $user = User::where('stripe_id', $customerId)->first();
        if (!$user) return;

        // Mettre à jour is_subscribed sur le studio/artiste
        if ($user->studio) {
            $user->studio->update(['is_subscribed' => true]);
        }
        if ($user->tattooer) {
            $user->tattooer->update(['is_subscribed' => true]);
        }
        if ($user->piercer) {
            $user->piercer->update(['is_subscribed' => true]);
        }

        Log::info('Checkout completed', ['user_id' => $user->id]);
    }

    private function handleSubscriptionDeleted(array $payload): void
    {
        $customerId = $payload['data']['object']['customer'] ?? null;
        if (!$customerId) return;

        $user = User::where('stripe_id', $customerId)->first();
        if (!$user) return;

        if ($user->studio) {
            $user->studio->update(['is_subscribed' => false]);
        }
        if ($user->tattooer) {
            $user->tattooer->update(['is_subscribed' => false, 'is_blocked' => true]);
        }
        if ($user->piercer) {
            $user->piercer->update(['is_subscribed' => false, 'is_blocked' => true]);
        }

        Log::info('Subscription deleted', ['user_id' => $user->id]);
    }

    private function handleSubscriptionUpdated(array $payload): void
    {
        $customerId = $payload['data']['object']['customer'] ?? null;
        $status = $payload['data']['object']['status'] ?? null;
        if (!$customerId) return;

        $user = User::where('stripe_id', $customerId)->first();
        if (!$user) return;

        $isActive = in_array($status, ['active', 'trialing']);

        if ($user->studio) {
            $user->studio->update(['is_subscribed' => $isActive]);
        }
        if ($user->tattooer) {
            $user->tattooer->update([
                'is_subscribed' => $isActive,
                'is_blocked' => !$isActive,
            ]);
        }
        if ($user->piercer) {
            $user->piercer->update([
                'is_subscribed' => $isActive,
                'is_blocked' => !$isActive,
            ]);
        }
    }
}
```

### Commande sync manuelle (pour local)

```php
// app/Console/Commands/SyncStripeSubscriptions.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Studio;
use App\Services\StudioBillingService;

class SyncStripeSubscriptions extends Command
{
    protected $signature = 'inkpik:sync-stripe';
    protected $description = 'Synchroniser les abonnements depuis Stripe (utile en local sans webhook)';

    public function handle()
    {
        $billingService = app(StudioBillingService::class);
        $synced = 0;

        // Studios
        Studio::whereHas('user', function ($q) {
            $q->whereNotNull('stripe_id');
        })->each(function ($studio) use ($billingService, &$synced) {
            if ($billingService->syncFromStripe($studio)) {
                $this->info("Studio #{$studio->id} ({$studio->name}) : synchronisé ✓");
                $synced++;
            }
        });

        $this->info("$synced abonnement(s) synchronisé(s).");
    }
}
```

```bash
git add -A && git commit -m "fix(F5): webhook listener Stripe + commande sync manuelle + fallback local"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT F ==="

# V1. Billable sur User, PAS sur Studio
echo "--- BILLABLE ---"
grep -c "use Billable" app/Models/User.php
echo "User Billable (doit être 1)"
grep -c "use Billable" app/Models/Studio.php 2>/dev/null
echo "Studio Billable (doit être 0)"

# V2. Studio->subscription délègue au User
grep -c "this->user" app/Models/Studio.php | head -1
echo "Délégation User dans Studio (doit être > 0)"

# V3. StudioBillingService
echo "--- BILLING SERVICE ---"
grep -c "studio->user" app/Services/StudioBillingService.php
echo "Accès via User (doit être > 5)"
grep -c "\$studio->subscri" app/Services/StudioBillingService.php
echo "Accès direct studio->subscription (devrait être 0 sauf les méthodes de délégation)"

# V4. Aucune référence directe $studio->subscribed dans les controllers
grep -c "\$studio->subscribed\b" app/Http/Controllers/StudioController.php 2>/dev/null
echo "Direct studio->subscribed dans controller (devrait être 0, utiliser billingService)"

# V5. Routes
echo "--- ROUTES ---"
php artisan route:list 2>&1 | grep -i "billing\|subscribe\|cancel\|resume\|sync" | head -10

# V6. Vue billing
echo "--- VUE ---"
grep -c "subscription.cancel\|subscription.resume\|subscription.sync" resources/views/studio/billing.blade.php 2>/dev/null
echo "Formulaires cancel/resume/sync dans vue (doit être > 0)"

# V7. Webhook
echo "--- WEBHOOK ---"
grep -c "HandleStripeWebhook\|WebhookReceived\|checkout.session.completed" app/Listeners/ app/Providers/ -r --include="*.php" 2>/dev/null
echo "Webhook listener (doit être > 0)"

# V8. Commande sync
php artisan list 2>&1 | grep "sync-stripe" | head -1

# V9. Annulation artiste
echo "--- ANNULATION ARTISTE ---"
php artisan route:list 2>&1 | grep "subscription.cancel" | head -5

# V10. Compilation
php artisan route:clear && php artisan view:clear
php artisan route:list 2>&1 | head -3
echo "Pas d'erreur = OK"

echo "=== PROMPT F TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 révèle où se trouvent les appels problématiques
2. **Billable sur USER, JAMAIS sur Studio** — c'est la source du bug SQL
3. **TOUTES les méthodes Cashier** (subscribed, subscription, newSubscription, etc.) passent par `$studio->user`
4. **try/catch PARTOUT** autour des appels Stripe — une erreur Stripe ne doit JAMAIS retourner une 500
5. **syncFromStripe** = fallback indispensable pour le dev local sans webhook
6. **Annulation** : 2 options (fin de période / immédiat) — TOUJOURS demander confirmation
7. **Webhook listener** : mettre à jour `is_subscribed` sur le modèle artiste/studio quand Stripe notifie
8. **Les artistes studio** ne sont PAS impactés par l'annulation de LEUR abonnement — ils n'en ont pas, c'est le studio qui paie
9. **Commit après chaque fix** (5 commits)
10. **Tester avec `php artisan inkpik:sync-stripe`** après un paiement Stripe test
