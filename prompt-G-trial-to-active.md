# ⚡ PROMPT G — FIN DE TRIAL IMMÉDIATE AU PAIEMENT
# Pour Claude Code — Quand l'utilisateur paie, l'essai se termine et l'abonnement est actif
# Petit prompt ciblé — 2-3 commits max

## CONTEXTE

Quand un studio (ou artiste) paie son abonnement PENDANT la période d'essai de 14 jours, le statut Stripe reste `trialing` et l'interface affiche "Essai gratuit". C'est trompeur : l'utilisateur a payé, il doit voir "Actif".

**Comportement actuel** : Paiement effectué → statut reste `trialing` → affichage "Essai gratuit" → confusion utilisateur.

**Comportement souhaité** : Paiement effectué → fin du trial immédiate → statut passe à `active` → affichage "Abonnement actif".

Stack : Laravel 12, Laravel Cashier v16, Stripe API.

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT PROMPT G ==="

# G0a. Méthode syncFromStripe et syncFromCheckoutSession — c'est là qu'on intercepte le paiement
grep -B 5 -A 30 "function syncFromStripe\|function syncFromCheckoutSession" app/Services/StudioBillingService.php | head -80

# G0b. Controller billing — retour checkout success
grep -B 5 -A 30 "checkout.*success\|session_id" app/Http/Controllers/StudioController.php | head -50

# G0c. Vue billing — affichage du statut trial
grep -n "trial\|essai\|trialing\|on_trial\|onTrial" resources/views/studio/billing.blade.php | head -15

# G0d. Comment le checkout est créé (trial_period_days)
grep -n "trial_period_days\|trial" app/Services/StudioBillingService.php | head -10

# G0e. Abonnement existant — vérifier le statut actuel
php artisan tinker --execute="
  \$sub = DB::table('subscriptions')->first();
  if (\$sub) {
    echo 'stripe_id: ' . \$sub->stripe_id . PHP_EOL;
    echo 'stripe_status: ' . \$sub->stripe_status . PHP_EOL;
    echo 'trial_ends_at: ' . (\$sub->trial_ends_at ?? 'NULL') . PHP_EOL;
  }
"

# G0f. Webhook listener — gère-t-il invoice.paid ?
grep -rn "invoice.paid\|invoice_paid\|InvoicePaid\|payment_intent.succeeded\|checkout.session.completed" app/Listeners/ app/Http/Controllers/ --include="*.php" | head -10

# G0g. Vue billing — bannière trial dans les dashboards
grep -rn "trial-banner\|trial_banner\|trialBanner\|daysRemaining\|jours.*restant\|essai.*gratuit" resources/views/ --include="*.blade.php" -l | head -10

# G0h. Tattooer/Pierceur — même logique d'abonnement ?
grep -rn "trial\|trialing\|essai" resources/views/tattooer/settings.blade.php resources/views/tattooer/dashboard.blade.php 2>/dev/null | head -10

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX G1 — TERMINER LE TRIAL STRIPE AU PAIEMENT

### Logique

Quand le retour Checkout est `success` (= paiement effectué), appeler l'API Stripe pour **terminer le trial immédiatement** et passer l'abonnement en `active`.

### Dans StudioBillingService — nouvelle méthode

```php
/**
 * Terminer le trial immédiatement sur un abonnement Stripe.
 * Appelé après un paiement réussi pour que le statut passe de 'trialing' à 'active'.
 */
public function endTrialImmediately(Studio $studio): bool
{
    try {
        $user = $studio->user;
        if (!$user || !$user->hasStripeId()) return false;

        $sub = $user->subscription('default');
        if (!$sub || !$sub->onTrial()) return false;

        $stripe = new \Stripe\StripeClient(config('cashier.secret'));

        // Mettre à jour l'abonnement Stripe : fin du trial = maintenant
        $stripe->subscriptions->update($sub->stripe_id, [
            'trial_end' => 'now',
        ]);

        // Mettre à jour la base locale
        $sub->update([
            'stripe_status' => 'active',
            'trial_ends_at' => null,
        ]);

        // Mettre à jour le studio
        $studio->update(['is_subscribed' => true]);

        // Si le studio a un trial_ends_at local, le supprimer aussi
        if ($studio->trial_ends_at) {
            $studio->update(['trial_ends_at' => null]);
        }

        Log::info('Trial ended immediately after payment', [
            'studio_id' => $studio->id,
            'stripe_sub_id' => $sub->stripe_id,
        ]);

        return true;
    } catch (\Exception $e) {
        Log::error('endTrialImmediately error', [
            'studio_id' => $studio->id,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}
```

### Modifier syncFromCheckoutSession pour terminer le trial si paiement effectué

Dans la méthode `syncFromCheckoutSession`, après avoir synchronisé l'abonnement, vérifier si le paiement a été fait et terminer le trial :

```php
// À la fin de syncFromCheckoutSession, APRÈS la synchronisation :

// Si le checkout a été payé (pas juste un trial sans paiement),
// terminer le trial immédiatement
if ($session->payment_status === 'paid') {
    $this->endTrialImmediately($studio);
}
```

### Modifier le controller billing — retour checkout success

```php
public function billing(Request $request)
{
    $studio = auth()->user()->studio;
    $billingService = app(StudioBillingService::class);

    if ($request->has('checkout')) {
        if ($request->get('checkout') === 'success') {
            $sessionId = $request->get('session_id');

            sleep(2); // Laisser Stripe finaliser

            // Sync depuis Stripe
            $synced = false;
            if ($sessionId) {
                $synced = $billingService->syncFromCheckoutSession($studio, $sessionId);
            }
            if (!$synced) {
                $synced = $billingService->syncFromStripe($studio);
            }

            // ═══ AJOUTER : Terminer le trial si paiement effectué ═══
            if ($synced) {
                $billingService->endTrialImmediately($studio);
            }

            $message = $synced
                ? 'Abonnement activé avec succès ! Bienvenue sur le plan Studio.'
                : 'Le paiement semble avoir abouti. Cliquez sur "Synchroniser" si l\'abonnement n\'apparaît pas.';

            return redirect()->route('studio.billing')
                ->with($synced ? 'success' : 'warning', $message);
        }

        if ($request->get('checkout') === 'cancel') {
            return redirect()->route('studio.billing')
                ->with('warning', 'Paiement annulé.');
        }
    }

    $subscriptionInfo = $billingService->getSubscriptionInfo($studio);
    $isSubscribed = $billingService->isSubscribed($studio);
    $portalUrl = $billingService->billingPortalUrl($studio);

    return view('studio.billing', compact('studio', 'subscriptionInfo', 'isSubscribed', 'portalUrl'));
}
```

### Même logique pour le webhook (quand invoice.paid arrive)

Dans le listener webhook, quand un paiement est confirmé, terminer le trial :

```bash
# Trouver le listener webhook
grep -rn "invoice.paid\|checkout.session.completed\|handleCheckoutCompleted" app/Listeners/ --include="*.php" -l | head -5
```

```php
// Dans HandleStripeWebhook ou équivalent
private function handleInvoicePaid(array $payload): void
{
    $customerId = $payload['data']['object']['customer'] ?? null;
    if (!$customerId) return;

    $user = User::where('stripe_id', $customerId)->first();
    if (!$user) return;

    // Mettre à jour le statut local
    $sub = $user->subscription('default');
    if ($sub && $sub->onTrial()) {
        $sub->update([
            'stripe_status' => 'active',
            'trial_ends_at' => null,
        ]);
    }

    // Mettre à jour le studio
    if ($user->studio) {
        $user->studio->update([
            'is_subscribed' => true,
            'trial_ends_at' => null,
        ]);
    }

    // Artiste indépendant
    $artisan = $user->tattooer ?? $user->piercer;
    if ($artisan) {
        $artisan->update([
            'is_subscribed' => true,
            'is_blocked' => false,
            'trial_ends_at' => null,
        ]);
    }

    Log::info('Invoice paid — trial ended', ['user_id' => $user->id]);
}
```

Ajouter `'invoice.paid'` dans le match du listener si pas déjà présent :
```php
match($type) {
    'checkout.session.completed' => $this->handleCheckoutCompleted($payload),
    'customer.subscription.deleted' => $this->handleSubscriptionDeleted($payload),
    'customer.subscription.updated' => $this->handleSubscriptionUpdated($payload),
    'invoice.paid' => $this->handleInvoicePaid($payload),  // ← AJOUTER
    default => null,
};
```

```bash
git add -A && git commit -m "fix(G1): fin du trial immédiate au paiement — Stripe API + local DB + webhook"
```

---

## FIX G2 — VUE BILLING + BANNIÈRES : AFFICHAGE COHÉRENT

### Problème
Même après le fix G1, il faut que les vues n'affichent JAMAIS "Essai gratuit" si le paiement a été fait.

### Fix vue billing

```bash
# Lire la vue billing actuelle
grep -n "trial\|essai\|trialing\|on_trial\|onTrial" resources/views/studio/billing.blade.php | head -15
```

Modifier l'affichage du statut dans la vue :

```blade
{{-- AVANT --}}
<p class="text-green-400 font-medium">
    {{ $subscriptionInfo['on_trial'] ? 'Essai gratuit' : 'Actif' }}
</p>

{{-- APRÈS --}}
<p class="text-green-400 font-medium">
    @if ($subscriptionInfo['stripe_status'] === 'active')
        Actif
    @elseif ($subscriptionInfo['on_trial'])
        Essai gratuit — {{ $subscriptionInfo['trial_ends_at']?->diffInDays(now()) }} jours restants
    @else
        {{ ucfirst($subscriptionInfo['stripe_status'] ?? 'Inconnu') }}
    @endif
</p>
```

La clé : si `stripe_status === 'active'`, afficher "Actif" point final, même si `on_trial` était vrai avant.

### Fix bannière trial dans les dashboards

```bash
# Trouver la bannière trial
grep -rn "trial-banner\|trial_banner\|isOnTrial\|daysRemaining" resources/views/ --include="*.blade.php" -l | head -10
```

Modifier la bannière pour ne s'afficher QUE si le trial est réellement actif (pas si l'abonnement est payé) :

```blade
{{-- Dans partials/trial-banner.blade.php --}}
@php
    $artisan = auth()->user()->tattooer ?? auth()->user()->piercer ?? null;
    $studio = auth()->user()->studio ?? null;
    $user = auth()->user();

    // Vérifier si l'utilisateur a un abonnement PAYÉ (active)
    $hasPaidSubscription = $user->subscription('default') 
        && $user->subscription('default')->stripe_status === 'active';

    // Trial seulement si PAS d'abonnement payé
    $trialService = app(\App\Services\TrialService::class);
    $entity = $studio ?? $artisan;
    $isOnTrial = $entity && !$hasPaidSubscription && $trialService->isOnTrial($entity);
    $daysRemaining = $entity ? $trialService->trialDaysRemaining($entity) : 0;
    $isBlocked = $entity?->is_blocked ?? false;
@endphp

{{-- Ne RIEN afficher si l'abonnement est payé et actif --}}
@if ($hasPaidSubscription)
    {{-- Abonnement actif payé — pas de bannière --}}
@elseif ($isOnTrial && $daysRemaining <= 7)
    {{-- Bannière urgente --}}
    ...
@elseif ($isOnTrial)
    {{-- Bannière info --}}
    ...
@elseif ($isBlocked)
    {{-- Bannière bloquée --}}
    ...
@endif
```

### Fix pour les artistes indépendants aussi

La même logique s'applique pour les tattooers/piercers. Si un artiste paie pendant son trial :
- Stripe passe de `trialing` à `active`
- La bannière trial disparaît
- Le statut affiché est "Actif"

S'assurer que `endTrialImmediately` est aussi appelé pour les artistes indépendants dans leur flux d'abonnement.

```bash
# Vérifier le flux d'abonnement artiste
grep -rn "subscribe\|checkout\|processSubscr" app/Http/Controllers/TattooerController.php app/Http/Controllers/PricingController.php 2>/dev/null | head -10
```

Si un controller gère l'abonnement des artistes, appliquer la même logique après le checkout success :
```php
// Après sync de l'abonnement artiste
$sub = auth()->user()->subscription('default');
if ($sub && $sub->onTrial()) {
    $stripe = new \Stripe\StripeClient(config('cashier.secret'));
    $stripe->subscriptions->update($sub->stripe_id, ['trial_end' => 'now']);
    $sub->update(['stripe_status' => 'active', 'trial_ends_at' => null]);
    $artisan->update(['trial_ends_at' => null, 'is_subscribed' => true]);
}
```

```bash
git add -A && git commit -m "fix(G2): vue billing + bannières — jamais 'Essai gratuit' après paiement"
```

---

## FIX G3 — POUR L'ABONNEMENT EXISTANT : CORRIGER MAINTENANT

L'abonnement `sub_1T7ZJJIsCWRG6bTQacGzIF4p` est actuellement en `trialing`. Si le studio a déjà payé (carte enregistrée via Checkout), on peut terminer le trial maintenant.

Créer une commande rapide pour corriger l'abonnement en cours :

```php
// app/Console/Commands/EndTrialNow.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Studio;
use App\Services\StudioBillingService;

class EndTrialNow extends Command
{
    protected $signature = 'inkpik:end-trial {--studio-id= : ID du studio}';
    protected $description = 'Terminer le trial immédiatement pour un studio (passe de trialing à active)';

    public function handle()
    {
        $studioId = $this->option('studio-id');
        $studio = $studioId ? Studio::find($studioId) : Studio::first();

        if (!$studio) {
            $this->error('Studio non trouvé.');
            return 1;
        }

        $billingService = app(StudioBillingService::class);

        $this->info("Studio #{$studio->id} — {$studio->name}");

        $sub = $studio->user?->subscription('default');
        if (!$sub) {
            $this->error('Aucun abonnement trouvé.');
            return 1;
        }

        $this->line("Statut actuel : {$sub->stripe_status}");
        $this->line("Trial ends at : " . ($sub->trial_ends_at ?? 'NULL'));

        if ($sub->stripe_status === 'active') {
            $this->info('Déjà actif, rien à faire.');
            return 0;
        }

        if (!$this->confirm('Terminer le trial maintenant et passer en active ?')) {
            return 0;
        }

        if ($billingService->endTrialImmediately($studio)) {
            $this->info('✅ Trial terminé — abonnement actif !');
            
            // Re-vérifier
            $sub->refresh();
            $this->line("Nouveau statut : {$sub->stripe_status}");
        } else {
            $this->error('Échec. Vérifiez les logs.');
        }

        return 0;
    }
}
```

Après le déploiement du prompt, exécuter :
```bash
php artisan inkpik:end-trial --studio-id=3
```

```bash
git add -A && git commit -m "fix(G3): commande end-trial pour corriger les abonnements existants en trialing"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT G ==="

# V1. Méthode endTrialImmediately
grep -c "endTrialImmediately" app/Services/StudioBillingService.php
echo "Méthode existe (doit être >= 1)"

# V2. Appelée au retour checkout
grep -c "endTrialImmediately" app/Http/Controllers/StudioController.php
echo "Appelée dans controller (doit être >= 1)"

# V3. Webhook invoice.paid
grep -c "invoice.paid\|handleInvoicePaid" app/Listeners/ -r --include="*.php"
echo "Webhook invoice.paid géré (doit être >= 1)"

# V4. Vue billing — pas de trialing si active
grep -c "stripe_status.*active\|=== 'active'" resources/views/studio/billing.blade.php
echo "Check stripe_status dans vue (doit être >= 1)"

# V5. Bannière trial — check hasPaidSubscription
grep -c "hasPaidSubscription\|stripe_status.*active" resources/views/partials/trial-banner.blade.php 2>/dev/null
echo "Check abonnement payé dans bannière (doit être >= 1)"

# V6. Commande
php artisan list 2>&1 | grep "end-trial" | head -1

# V7. Compilation
php artisan route:clear && php artisan view:clear
echo "OK"

echo "=== PROMPT G TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 montre l'état réel
2. **`trial_end: 'now'`** via l'API Stripe = le seul moyen fiable de terminer un trial
3. **Mettre à jour STRIPE + LOCAL** en même temps (pas l'un sans l'autre)
4. **Vue : vérifier `stripe_status === 'active'`** pas `on_trial` pour l'affichage
5. **Bannière trial : ne jamais afficher si abonnement payé** (check `stripe_status`)
6. **Webhook `invoice.paid`** : même logique, terminer le trial quand Stripe confirme le paiement
7. **Artistes indépendants** : appliquer la même logique dans leur flux
8. **Commit après chaque fix** (3 commits)
