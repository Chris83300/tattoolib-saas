# 🚨 PROMPT F2 — FIX PROFOND ABONNEMENT STUDIO
# Pour Claude Code — Nettoyage données fantômes, flux Checkout, architecture subscription
# URGENCE ÉLEVÉE — Commit après chaque fix

## CONTEXTE DU PROBLÈME

Après le Prompt F, l'abonnement studio est toujours cassé :
1. **Dashboard Stripe** = aucun abonnement de test visible
2. **Table `subscriptions`** = contient un enregistrement (créé par `sync-stripe`) mais c'est un FANTÔME — pas d'abonnement réel côté Stripe
3. **Table `studio_subscriptions`** = vide (ancien système custom jamais alimenté)
4. **Vue billing** = affiche "Abonnement actif / Essai gratuit" basé sur le fantôme

**Cause racine** : Le flux Checkout Stripe ne crée pas d'abonnement réel. Soit le Checkout Session échoue, soit les Price IDs sont invalides, soit le flux ne passe pas par Cashier correctement.

**Architecture à clarifier** :
- `subscriptions` = table Cashier standard (via `User` + trait `Billable`) → **SOURCE DE VÉRITÉ**
- `studio_subscriptions` = ancien système custom → **À SUPPRIMER ou IGNORER**

Stack : Laravel 12, Laravel Cashier v16, Stripe Connect, Livewire 3.7.

---

## PHASE 0 — AUDIT DIAGNOSTIC PROFOND

```bash
echo "=== AUDIT F2 — DIAGNOSTIC PROFOND ==="

# ── ÉTAT ACTUEL DES DONNÉES ──
echo "--- DONNÉES ---"

# F2-0a. Contenu exact de la table subscriptions
php artisan tinker --execute="
  \$subs = DB::table('subscriptions')->get();
  echo 'subscriptions (' . \$subs->count() . ' rows):' . PHP_EOL;
  foreach(\$subs as \$s) {
    echo json_encode((array)\$s, JSON_PRETTY_PRINT) . PHP_EOL;
  }
"

# F2-0b. Contenu exact de la table studio_subscriptions
php artisan tinker --execute="
  if (Schema::hasTable('studio_subscriptions')) {
    \$subs = DB::table('studio_subscriptions')->get();
    echo 'studio_subscriptions (' . \$subs->count() . ' rows):' . PHP_EOL;
    foreach(\$subs as \$s) {
      echo json_encode((array)\$s, JSON_PRETTY_PRINT) . PHP_EOL;
    }
  } else {
    echo 'TABLE studio_subscriptions: ABSENTE' . PHP_EOL;
  }
"

# F2-0c. User du studio — colonnes Stripe
php artisan tinker --execute="
  \$studio = \App\Models\Studio::first();
  if (\$studio && \$studio->user) {
    \$u = \$studio->user;
    echo 'User #' . \$u->id . ' (' . \$u->name . ')' . PHP_EOL;
    echo '  stripe_id: ' . (\$u->stripe_id ?? 'NULL') . PHP_EOL;
    echo '  pm_type: ' . (\$u->pm_type ?? 'NULL') . PHP_EOL;
    echo '  pm_last_four: ' . (\$u->pm_last_four ?? 'NULL') . PHP_EOL;
    echo '  trial_ends_at: ' . (\$u->trial_ends_at ?? 'NULL') . PHP_EOL;
  }
"

# F2-0d. Studio — colonnes subscription
php artisan tinker --execute="
  \$studio = \App\Models\Studio::first();
  if (\$studio) {
    echo 'Studio #' . \$studio->id . ' (' . \$studio->name . ')' . PHP_EOL;
    echo '  is_subscribed: ' . (\$studio->is_subscribed ? 'true' : 'false') . PHP_EOL;
    echo '  trial_ends_at: ' . (\$studio->trial_ends_at ?? 'NULL') . PHP_EOL;
    echo '  stripe_id (studio): ' . (\$studio->stripe_id ?? 'NULL') . PHP_EOL;
  }
"


# ── STRIPE CONFIG ──
echo "--- STRIPE CONFIG ---"

# F2-0e. Clés Stripe dans .env
grep -n "STRIPE_KEY\|STRIPE_SECRET\|STRIPE_WEBHOOK\|STRIPE_PRICE" .env | head -15
# Vérifier que ce sont des clés TEST (sk_test_ / pk_test_)
grep "STRIPE_SECRET" .env | head -1 | cut -c1-25

# F2-0f. Price IDs configurés
grep "STRIPE_PRICE" .env | head -10
grep "stripe_price_id" config/inkpik.php 2>/dev/null | head -10

# F2-0g. Cashier config — modèle, clé
grep -n "model\|key\|secret\|currency\|logger" config/cashier.php 2>/dev/null | head -10


# ── FLUX CHECKOUT ──
echo "--- FLUX CHECKOUT ---"

# F2-0h. Méthode createCheckoutSession complète
grep -B 5 -A 50 "function.*createCheckout\|function.*subscribe\b\|function.*processSubscribe" app/Services/StudioBillingService.php app/Http/Controllers/StudioController.php 2>/dev/null | head -80

# F2-0i. Comment le Checkout est appelé (route + controller)
php artisan route:list 2>&1 | grep "subscribe" | head -10
grep -B 5 -A 20 "function subscribe\b\|function processSubscribe\b\|function showSubscribe\b" app/Http/Controllers/StudioController.php 2>/dev/null | head -50

# F2-0j. Cashier checkout method utilisée
grep -rn "->checkout(\|->newSubscription(\|Cashier::stripe\|Stripe\\\\Checkout" app/Services/ app/Http/Controllers/ --include="*.php" | head -15

# F2-0k. Vérifier si le flux utilise Cashier OU l'API Stripe directe
grep -rn "Stripe\\\\Checkout\\\\Session::create\|stripe()->checkout" app/ --include="*.php" | head -10
grep -rn "newSubscription\|->checkout(" app/ --include="*.php" | head -10


# ── ANCIEN SYSTÈME ──
echo "--- ANCIEN SYSTÈME ---"

# F2-0l. Modèle StudioSubscription
find app/Models -name "*StudioSubscription*" -o -name "*studio_subscription*" | head -5
cat app/Models/StudioSubscription.php 2>/dev/null | head -30

# F2-0m. Références à studio_subscriptions dans le code
grep -rn "studio_subscription\|StudioSubscription\|studio_sub" app/ --include="*.php" | head -20

# F2-0n. StripeStudioSubscriptionService (ancien service ?)
cat app/Services/StripeStudioSubscriptionService.php 2>/dev/null | head -60

# F2-0o. Qui écrit dans studio_subscriptions ?
grep -rn "studio_subscriptions\|StudioSubscription::create\|->studioSubscription" app/ --include="*.php" | head -15


# ── SYNC STRIPE ──
echo "--- SYNC ---"

# F2-0p. Commande sync complète
cat app/Console/Commands/SyncStripeSubscriptions.php 2>/dev/null

# F2-0q. hasActiveSubscription sur Studio
grep -B 5 -A 15 "function hasActiveSubscription\|function isSubscribed\|function subscribed" app/Models/Studio.php app/Services/StudioBillingService.php 2>/dev/null | head -40


# ── WEBHOOK ──
echo "--- WEBHOOK ---"

# F2-0r. Route webhook
php artisan route:list 2>&1 | grep "webhook" | head -5

# F2-0s. CSRF exception pour webhook
grep -n "webhook\|stripe" app/Http/Middleware/VerifyCsrfToken.php 2>/dev/null | head -5
# OU pour Laravel 12
grep -rn "webhook\|except\|stripe" bootstrap/app.php 2>/dev/null | head -10

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer. C'est critique — chaque détail compte.**

---

## FIX F2-1 — NETTOYER LES DONNÉES FANTÔMES

### Problème
La table `subscriptions` contient un enregistrement fantôme (pas d'abonnement réel dans Stripe Dashboard).

### Fix

```php
// ÉTAPE 1 : Vérifier si le stripe_id de la subscription est valide
php artisan tinker --execute="
  \$sub = DB::table('subscriptions')->first();
  if (\$sub) {
    echo 'Stripe Sub ID: ' . (\$sub->stripe_id ?? 'NULL') . PHP_EOL;
    echo 'Stripe Status: ' . (\$sub->stripe_status ?? 'NULL') . PHP_EOL;
    
    // Tenter de récupérer l'abonnement côté Stripe
    try {
      \$stripeSub = \Stripe\Subscription::retrieve(\$sub->stripe_id, [
        'api_key' => config('cashier.secret'),
      ]);
      echo 'Stripe API status: ' . \$stripeSub->status . PHP_EOL;
      echo 'Abonnement VALIDE dans Stripe' . PHP_EOL;
    } catch (\Exception \$e) {
      echo 'Stripe API ERROR: ' . \$e->getMessage() . PHP_EOL;
      echo 'Abonnement FANTÔME — à supprimer' . PHP_EOL;
    }
  }
"
```

**Si l'abonnement est fantôme (erreur Stripe)** — le supprimer :

```php
// ÉTAPE 2 : Supprimer le fantôme
php artisan tinker --execute="
  DB::table('subscriptions')->truncate();
  echo 'Table subscriptions vidée' . PHP_EOL;
  
  // Réinitialiser le flag is_subscribed
  \App\Models\Studio::query()->update(['is_subscribed' => false]);
  echo 'Studios is_subscribed reset' . PHP_EOL;
"
```

```bash
git add -A && git commit -m "fix(F2-1): nettoyage subscription fantôme + reset is_subscribed"
```

---

## FIX F2-2 — VÉRIFIER ET CRÉER LES PRICE IDs STRIPE

### Problème probable
Les Price IDs dans `.env` sont vides ou invalides → le Checkout Session ne peut pas créer d'abonnement.

### Diagnostic

```bash
# Vérifier les Price IDs
grep "STRIPE_PRICE" .env
```

**Si les Price IDs sont vides ou placeholder** (`price_xxxxxxx`), il faut les créer dans Stripe Dashboard :

1. Aller dans **Stripe Dashboard → Products**
2. Créer un produit "Ink&Pik Studio"
3. Ajouter un prix récurrent : **59,99€/mois** → copier le `price_xxxxx`
4. Ajouter un prix pour artiste supplémentaire : **24,99€/mois** → copier le `price_xxxxx`
5. Créer un produit "Ink&Pik Starter" → prix **9,99€/mois**
6. Créer un produit "Ink&Pik Pro" → prix **29,99€/mois**

**Mettre à jour le .env** :
```env
STRIPE_PRICE_ID_STARTER=price_XXXXX  # Remplacer par le vrai ID
STRIPE_PRICE_ID_PRO=price_XXXXX
STRIPE_PRICE_ID_STUDIO=price_XXXXX
STRIPE_PRICE_ID_STUDIO_EXTRA=price_XXXXX
```

### Vérifier que les Price IDs sont utilisés dans le flux

```bash
grep -rn "STRIPE_PRICE_ID\|stripe_price_id\|price_id\|price_" app/Services/StudioBillingService.php | head -10
grep -rn "config.*pricing.*stripe\|env.*STRIPE_PRICE" app/ config/ --include="*.php" | head -10
```

Le service doit récupérer le Price ID depuis la config :
```php
$priceId = config('inkpik.pricing.studio.stripe_price_id');
// OU
$priceId = env('STRIPE_PRICE_ID_STUDIO');
```

**Si le Price ID est vide ou null**, le Checkout Session échouera silencieusement.

```bash
git add -A && git commit -m "fix(F2-2): documentation Price IDs Stripe + vérification dans le flux"
```

---

## FIX F2-3 — RÉÉCRIRE LE FLUX CHECKOUT COMPLET

### Le flux doit être simple et testable

Le problème est souvent que le code mélange Cashier et l'API Stripe directe. Il faut UN SEUL chemin.

### Réécrire createCheckoutSession dans StudioBillingService

```php
/**
 * Créer une session Stripe Checkout pour l'abonnement studio.
 * Utilise l'API Stripe directe (pas Cashier Checkout) pour plus de contrôle.
 */
public function createCheckoutSession(Studio $studio): string
{
    $user = $studio->user;
    if (!$user) throw new \Exception('Studio sans utilisateur propriétaire.');

    // Récupérer le Price ID
    $priceId = config('inkpik.pricing.studio.stripe_price_id');
    if (!$priceId || $priceId === '' || str_starts_with($priceId, 'price_xxxxxxx')) {
        throw new \Exception('Stripe Price ID Studio non configuré. Vérifiez STRIPE_PRICE_ID_STUDIO dans .env');
    }

    // Créer le customer Stripe si nécessaire
    if (!$user->hasStripeId()) {
        $user->createAsStripeCustomer([
            'name' => $user->name,
            'email' => $user->email,
            'metadata' => [
                'studio_id' => $studio->id,
                'studio_name' => $studio->name,
                'type' => 'studio_owner',
            ],
        ]);
    }

    // Paramètres de la session Checkout
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
        'success_url' => route('studio.billing') . '?checkout=success&session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('studio.billing') . '?checkout=cancel',
        'subscription_data' => [
            'metadata' => [
                'studio_id' => $studio->id,
                'plan' => 'studio',
            ],
            'trial_period_days' => 14,
        ],
        'metadata' => [
            'studio_id' => $studio->id,
        ],
    ];

    // Coupon bêta si applicable
    $betaService = app(\App\Services\BetaService::class);
    if ($betaService->isActiveBetaTester($user)) {
        $params['discounts'] = [
            ['coupon' => $betaService->getStripeCouponId()],
        ];
        // Si coupon bêta inclut 1 mois gratuit, étendre le trial
        $params['subscription_data']['trial_period_days'] = 44; // 14j + 30j
    }

    // Créer la session Stripe Checkout
    $stripe = new \Stripe\StripeClient(config('cashier.secret'));
    $session = $stripe->checkout->sessions->create($params);

    Log::info('Stripe Checkout Session created', [
        'session_id' => $session->id,
        'studio_id' => $studio->id,
        'user_id' => $user->id,
        'price_id' => $priceId,
        'url' => $session->url,
    ]);

    return $session->url;
}
```

### Réécrire syncFromStripe pour qu'il crée correctement l'enregistrement Cashier

```php
/**
 * Synchroniser depuis Stripe après un Checkout réussi.
 * Crée l'enregistrement dans la table subscriptions (Cashier).
 */
public function syncFromStripe(Studio $studio): bool
{
    try {
        $user = $studio->user;
        if (!$user || !$user->hasStripeId()) {
            Log::warning('syncFromStripe: user sans stripe_id', ['studio_id' => $studio->id]);
            return false;
        }

        $stripe = new \Stripe\StripeClient(config('cashier.secret'));

        // Récupérer les abonnements du customer
        $stripeSubscriptions = $stripe->subscriptions->all([
            'customer' => $user->stripe_id,
            'limit' => 10,
        ]);

        if (empty($stripeSubscriptions->data)) {
            Log::info('syncFromStripe: aucun abonnement trouvé', [
                'studio_id' => $studio->id,
                'stripe_customer' => $user->stripe_id,
            ]);
            $studio->update(['is_subscribed' => false]);
            return false;
        }

        foreach ($stripeSubscriptions->data as $stripeSub) {
            // Ne prendre que les abonnements actifs ou en trial
            if (!in_array($stripeSub->status, ['active', 'trialing'])) {
                Log::info('syncFromStripe: sub ignorée (status=' . $stripeSub->status . ')', [
                    'stripe_sub_id' => $stripeSub->id,
                ]);
                continue;
            }

            $priceId = $stripeSub->items->data[0]->price->id ?? null;

            // Créer ou mettre à jour dans la table Cashier subscriptions
            $user->subscriptions()->updateOrCreate(
                ['type' => 'default'],
                [
                    'stripe_id' => $stripeSub->id,
                    'stripe_status' => $stripeSub->status,
                    'stripe_price' => $priceId,
                    'quantity' => $stripeSub->items->data[0]->quantity ?? 1,
                    'trial_ends_at' => $stripeSub->trial_end
                        ? \Carbon\Carbon::createFromTimestamp($stripeSub->trial_end)
                        : null,
                    'ends_at' => $stripeSub->cancel_at
                        ? \Carbon\Carbon::createFromTimestamp($stripeSub->cancel_at)
                        : null,
                ]
            );

            $studio->update(['is_subscribed' => true]);

            Log::info('syncFromStripe: abonnement synchronisé', [
                'studio_id' => $studio->id,
                'stripe_sub_id' => $stripeSub->id,
                'status' => $stripeSub->status,
                'price_id' => $priceId,
            ]);

            return true;
        }

        // Aucun abonnement actif
        $studio->update(['is_subscribed' => false]);
        return false;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        Log::error('syncFromStripe Stripe API error', [
            'studio_id' => $studio->id,
            'error' => $e->getMessage(),
            'code' => $e->getStripeCode(),
        ]);
        return false;
    } catch (\Exception $e) {
        Log::error('syncFromStripe error', [
            'studio_id' => $studio->id,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}
```

### Controller — gérer le retour de Checkout

```php
public function billing(Request $request)
{
    $studio = auth()->user()->studio;
    $billingService = app(StudioBillingService::class);

    // Retour de Stripe Checkout
    if ($request->has('checkout')) {
        if ($request->get('checkout') === 'success') {
            $sessionId = $request->get('session_id');

            // Tenter de synchroniser l'abonnement depuis Stripe
            // En local (sans webhook), c'est le seul moyen de récupérer l'abonnement
            sleep(2); // Laisser le temps à Stripe de finaliser
            $synced = $billingService->syncFromStripe($studio);

            if ($synced) {
                return redirect()->route('studio.billing')
                    ->with('success', 'Abonnement activé avec succès ! Bienvenue sur le plan Studio.');
            } else {
                // Si sync échoue (Stripe pas encore prêt), retry avec le session_id
                if ($sessionId) {
                    $synced = $billingService->syncFromCheckoutSession($studio, $sessionId);
                    if ($synced) {
                        return redirect()->route('studio.billing')
                            ->with('success', 'Abonnement activé avec succès !');
                    }
                }

                return redirect()->route('studio.billing')
                    ->with('warning', 'Le paiement semble avoir abouti mais l\'abonnement n\'est pas encore synchronisé. Cliquez sur "Synchroniser" dans quelques instants.');
            }
        }

        if ($request->get('checkout') === 'cancel') {
            return redirect()->route('studio.billing')
                ->with('warning', 'Paiement annulé. Vous pouvez réessayer quand vous le souhaitez.');
        }
    }

    $subscriptionInfo = $billingService->getSubscriptionInfo($studio);
    $isSubscribed = $billingService->isSubscribed($studio);
    $portalUrl = $billingService->billingPortalUrl($studio);

    return view('studio.billing', compact('studio', 'subscriptionInfo', 'isSubscribed', 'portalUrl'));
}
```

### Ajouter syncFromCheckoutSession dans le service

```php
/**
 * Synchroniser depuis une Checkout Session spécifique.
 * Utile quand syncFromStripe ne trouve rien (délai Stripe).
 */
public function syncFromCheckoutSession(Studio $studio, string $sessionId): bool
{
    try {
        $user = $studio->user;
        if (!$user) return false;

        $stripe = new \Stripe\StripeClient(config('cashier.secret'));
        $session = $stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['subscription'],
        ]);

        if (!$session->subscription) {
            Log::warning('syncFromCheckoutSession: pas de subscription dans la session', [
                'session_id' => $sessionId,
            ]);
            return false;
        }

        $stripeSub = is_string($session->subscription)
            ? $stripe->subscriptions->retrieve($session->subscription)
            : $session->subscription;

        if (!in_array($stripeSub->status, ['active', 'trialing'])) {
            Log::warning('syncFromCheckoutSession: subscription pas active', [
                'status' => $stripeSub->status,
            ]);
            return false;
        }

        // Mettre à jour le stripe_id du User si pas encore fait
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
                'quantity' => $stripeSub->items->data[0]->quantity ?? 1,
                'trial_ends_at' => $stripeSub->trial_end
                    ? \Carbon\Carbon::createFromTimestamp($stripeSub->trial_end)
                    : null,
                'ends_at' => null,
            ]
        );

        $studio->update(['is_subscribed' => true]);

        Log::info('syncFromCheckoutSession: OK', [
            'studio_id' => $studio->id,
            'stripe_sub_id' => $stripeSub->id,
        ]);

        return true;

    } catch (\Exception $e) {
        Log::error('syncFromCheckoutSession error', [
            'session_id' => $sessionId,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}
```

```bash
git add -A && git commit -m "fix(F2-3): flux Checkout complet — API Stripe directe + sync session + fallback"
```

---

## FIX F2-4 — ABANDONNER L'ANCIEN SYSTÈME studio_subscriptions

### Problème
Le code fait encore référence à `studio_subscriptions` et au modèle `StudioSubscription` à certains endroits, ce qui crée de la confusion.

### Fix

```bash
# Trouver TOUTES les références à l'ancien système
grep -rn "studio_subscription\|StudioSubscription\|studio_sub" app/ config/ --include="*.php" | head -30
grep -rn "studio_subscription\|StudioSubscription" resources/views/ --include="*.blade.php" | head -10
```

Pour CHAQUE référence trouvée :

1. **Modèle StudioSubscription** — Ajouter un commentaire deprecated :
```php
/**
 * @deprecated Utiliser Laravel Cashier via User::subscription('default') à la place.
 * Cette table n'est plus alimentée depuis le fix F2.
 * Conservée temporairement pour ne pas casser les migrations.
 */
class StudioSubscription extends Model
{
    // ...
}
```

2. **StripeStudioSubscriptionService** — Soit le supprimer, soit le marquer deprecated et s'assurer qu'il n'est plus appelé :
```bash
grep -rn "StripeStudioSubscriptionService\|StudioSubscriptionService" app/ --include="*.php" | head -10
```
Si encore utilisé quelque part, remplacer les appels par `StudioBillingService`.

3. **hasActiveSubscription() sur Studio** — S'assurer que ça passe par Cashier :
```php
// Dans app/Models/Studio.php
public function hasActiveSubscription(): bool
{
    // Priorité 1 : Cashier via User
    if ($this->user && $this->user->subscribed('default')) {
        return true;
    }

    // Priorité 2 : flag is_subscribed (mis à jour par sync/webhook)
    if ($this->is_subscribed) {
        return true;
    }

    // Priorité 3 : trial actif
    if ($this->trial_ends_at && $this->trial_ends_at->isFuture()) {
        return true;
    }

    return false;
}
```

4. **Ne PAS supprimer la table** `studio_subscriptions` — elle peut contenir des données historiques. Juste ne plus l'utiliser.

```bash
git add -A && git commit -m "fix(F2-4): abandon studio_subscriptions — tout passe par Cashier subscriptions"
```

---

## FIX F2-5 — COMMANDE DE TEST END-TO-END

Créer une commande pour diagnostiquer et tester le flux complet :

```php
// app/Console/Commands/DiagnoseStudioSubscription.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Studio;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DiagnoseStudioSubscription extends Command
{
    protected $signature = 'inkpik:diagnose-subscription {--studio-id= : ID du studio à diagnostiquer}';
    protected $description = 'Diagnostiquer l\'état de l\'abonnement studio (local + Stripe)';

    public function handle()
    {
        $studioId = $this->option('studio-id');
        $studio = $studioId ? Studio::find($studioId) : Studio::first();

        if (!$studio) {
            $this->error('Aucun studio trouvé.');
            return 1;
        }

        $user = $studio->user;
        $this->info("=== Studio #{$studio->id} — {$studio->name} ===");
        $this->newLine();

        // 1. État local
        $this->info('--- ÉTAT LOCAL ---');
        $this->line("is_subscribed: " . ($studio->is_subscribed ? 'true' : 'false'));
        $this->line("trial_ends_at: " . ($studio->trial_ends_at ?? 'NULL'));

        if ($user) {
            $this->line("User #{$user->id} ({$user->email})");
            $this->line("  stripe_id: " . ($user->stripe_id ?? 'NULL'));
            $this->line("  pm_type: " . ($user->pm_type ?? 'NULL'));

            // Cashier subscriptions
            $subs = $user->subscriptions()->get();
            $this->line("  Cashier subscriptions: " . $subs->count());
            foreach ($subs as $sub) {
                $this->line("    - type={$sub->type} stripe_id={$sub->stripe_id} status={$sub->stripe_status} price={$sub->stripe_price}");
                $this->line("      trial_ends_at=" . ($sub->trial_ends_at ?? 'NULL') . " ends_at=" . ($sub->ends_at ?? 'NULL'));
            }
        } else {
            $this->error("PAS DE USER ASSOCIÉ AU STUDIO !");
        }

        $this->newLine();

        // 2. État Stripe
        $this->info('--- ÉTAT STRIPE ---');
        if (!$user?->stripe_id) {
            $this->warn("Pas de stripe_id → pas de customer Stripe.");
            $this->warn("Le studio n'a jamais passé par Stripe Checkout.");
            return 0;
        }

        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));

            // Customer
            $customer = $stripe->customers->retrieve($user->stripe_id);
            $this->line("Customer: {$customer->id} ({$customer->email})");

            // Subscriptions
            $stripeSubs = $stripe->subscriptions->all([
                'customer' => $user->stripe_id,
                'limit' => 10,
            ]);

            if (empty($stripeSubs->data)) {
                $this->warn("AUCUN abonnement trouvé dans Stripe !");
                $this->warn("→ Si la table subscriptions contient des données, ce sont des FANTÔMES.");
            } else {
                foreach ($stripeSubs->data as $ss) {
                    $this->line("  Sub: {$ss->id}");
                    $this->line("    status: {$ss->status}");
                    $this->line("    plan: " . ($ss->items->data[0]->price->id ?? '?'));
                    $this->line("    trial_end: " . ($ss->trial_end ? date('Y-m-d H:i', $ss->trial_end) : 'none'));
                    $this->line("    current_period_end: " . date('Y-m-d H:i', $ss->current_period_end));
                }
            }

            // Checkout Sessions récentes
            $sessions = $stripe->checkout->sessions->all([
                'customer' => $user->stripe_id,
                'limit' => 5,
            ]);

            if (!empty($sessions->data)) {
                $this->newLine();
                $this->info('--- CHECKOUT SESSIONS RÉCENTES ---');
                foreach ($sessions->data as $sess) {
                    $this->line("  Session: {$sess->id}");
                    $this->line("    status: {$sess->status}");
                    $this->line("    payment_status: {$sess->payment_status}");
                    $this->line("    subscription: " . ($sess->subscription ?? 'NULL'));
                }
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->error("Stripe API Error: " . $e->getMessage());
        }

        $this->newLine();

        // 3. Config
        $this->info('--- CONFIG ---');
        $priceId = config('inkpik.pricing.studio.stripe_price_id');
        $this->line("STRIPE_PRICE_ID_STUDIO: " . ($priceId ?: 'VIDE ⚠️'));

        if (!$priceId || str_starts_with($priceId, 'price_xxxxxxx')) {
            $this->error("⚠️  Le Price ID Studio n'est pas configuré !");
            $this->error("→ Créez-le dans Stripe Dashboard et ajoutez-le dans .env");
        } else {
            // Vérifier que le price existe dans Stripe
            try {
                $price = $stripe->prices->retrieve($priceId);
                $this->line("  Price valide: {$price->id} — " . ($price->unit_amount / 100) . "€/{$price->recurring->interval}");
            } catch (\Exception $e) {
                $this->error("⚠️  Price ID INVALIDE dans Stripe : " . $e->getMessage());
            }
        }

        // 4. Recommandations
        $this->newLine();
        $this->info('--- RECOMMANDATIONS ---');

        $localSub = $user?->subscriptions()->first();
        $hasStripeSub = !empty($stripeSubs?->data);

        if ($localSub && !$hasStripeSub) {
            $this->error("❌ Abonnement local FANTÔME — supprimer avec :");
            $this->line("   php artisan tinker --execute=\"DB::table('subscriptions')->truncate(); App\\Models\\Studio::query()->update(['is_subscribed' => false]);\"");
        } elseif (!$localSub && $hasStripeSub) {
            $this->warn("⚠️  Abonnement Stripe existe mais pas synchronisé — lancer :");
            $this->line("   php artisan inkpik:sync-stripe");
        } elseif ($localSub && $hasStripeSub) {
            $this->info("✅ Abonnement cohérent (local + Stripe)");
        } else {
            $this->line("ℹ️  Pas d'abonnement. Flux normal : aller sur /studio/billing et cliquer 'S'abonner'.");
        }

        return 0;
    }
}
```

```bash
git add -A && git commit -m "fix(F2-5): commande diagnose-subscription pour debug complet local + Stripe"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION F2 ==="

# V1. Données propres
php artisan tinker --execute="
  echo 'subscriptions: ' . DB::table('subscriptions')->count() . ' rows' . PHP_EOL;
  \$studio = \App\Models\Studio::first();
  echo 'Studio is_subscribed: ' . (\$studio->is_subscribed ? 'true' : 'false') . PHP_EOL;
"

# V2. Prix configuré
php artisan tinker --execute="
  \$priceId = config('inkpik.pricing.studio.stripe_price_id');
  echo 'Studio Price ID: ' . (\$priceId ?: 'VIDE') . PHP_EOL;
  echo (\$priceId && !\str_starts_with(\$priceId, 'price_xxx') ? 'OK' : 'CONFIGURER LE PRICE ID') . PHP_EOL;
"

# V3. Flux checkout
grep -c "checkout->sessions->create\|Checkout\\\\Session" app/Services/StudioBillingService.php
echo "Appel Stripe Checkout (doit être 1)"

# V4. Plus de références studio_subscriptions actives
grep -c "StudioSubscription::" app/Services/StudioBillingService.php app/Http/Controllers/StudioController.php 2>/dev/null
echo "Références StudioSubscription dans billing (devrait être 0)"

# V5. hasActiveSubscription passe par Cashier
grep -c "user->subscribed\|user->subscription" app/Models/Studio.php
echo "Délégation Cashier dans Studio (doit être > 0)"

# V6. Commande diagnose
php artisan inkpik:diagnose-subscription

# V7. Compilation
php artisan route:clear && php artisan view:clear
php artisan route:list 2>&1 | head -3

echo "=== F2 TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 est ESSENTIELLE pour comprendre le vrai état
2. **NETTOYER les fantômes AVANT de corriger le flux** — sinon le diagnostic est faussé
3. **UN SEUL chemin d'abonnement** : User → Cashier → table `subscriptions` → Stripe
4. **studio_subscriptions = MORT** — ne plus écrire dedans, ne plus lire dessus
5. **Price IDs Stripe** doivent être VALIDES et testés — vérifier avec `diagnose-subscription`
6. **En local sans webhook** → le sync se fait au retour du Checkout (`?checkout=success&session_id=`)
7. **syncFromCheckoutSession** = fallback quand syncFromStripe ne trouve rien immédiatement (délai Stripe)
8. **Logs partout** — chaque étape du flux doit logger pour le debug
9. **Commit après chaque fix** (5 commits)
10. **Tester le flux complet** : billing page → clic "S'abonner" → Stripe Checkout → retour → page billing avec abonnement actif
