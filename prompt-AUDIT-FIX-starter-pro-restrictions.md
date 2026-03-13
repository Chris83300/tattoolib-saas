# 🚨 AUDIT & FIX CRITIQUE — Restrictions plan STARTER + Commission Stripe 7%

## Objectif
1. Auditer TOUT le code lié aux plans STARTER et PRO pour avoir le contexte complet
2. Corriger les restrictions STARTER qui ne s'appliquent pas (accès PRO gratuit)
3. Vérifier/implémenter l'Application Fee Stripe à 7% sur les transactions STARTER

---

## PHASE 1 — AUDIT COMPLET (lecture seule, aucune modification)

### 1.1 — Cartographier tous les fichiers liés aux plans

```bash
# Trouver toutes les occurrences de isPro / isStarter / is_subscribed / current_plan
grep -r "isPro\|isStarter\|isFree\|isOnTrial\|current_plan\|is_subscribed" app/ --include="*.php" -l

# Trouver tous les middlewares liés aux plans
grep -r "middleware\|Middleware" app/Http/Middleware/ --include="*.php" -l
grep -r "isPro\|isStarter\|plan\|subscription" app/Http/Middleware/ --include="*.php"

# Trouver les gates / policies
grep -r "isPro\|isStarter\|PRO\|STARTER" app/Providers/ --include="*.php"
grep -r "can\|Gate\|Policy" app/Http/Middleware/ --include="*.php" -l

# Trouver les vues qui conditionnent du contenu selon le plan
grep -r "isPro\|isStarter\|isFree\|current_plan\|cadenas\|lock\|upgrade" \
  resources/views/ --include="*.blade.php" -l

# Trouver les controllers qui vérifient le plan
grep -r "isPro\|isStarter\|abort\|authorize\|plan" app/Http/Controllers/ --include="*.php" -l

# Chercher la config des features par plan
grep -r "STARTER\|PRO\|features\|limits\|plan_features" app/ config/ --include="*.php" -l
```

### 1.2 — Lire intégralement les fichiers clés identifiés

Pour CHAQUE fichier trouvé, lire et documenter :
- **Trait `HasSubscription`** : toutes les méthodes (isPro, isStarter, isFree, isOnTrial, canAccess...)
- **Tous les Middlewares** liés aux plans : logique exacte de chaque check
- **`RouteServiceProvider` ou `routes/web.php`** : quels middlewares sont appliqués sur quelles routes
- **`AppServiceProvider`** ou `AuthServiceProvider` : Gates définies par plan
- **Sidebar views** (tattooer, piercer) : items avec cadenas ou conditions `@if isPro`
- **Controllers** : lesquels vérifient le plan en début de méthode

### 1.3 — Dresser la matrice des features par plan

À partir de l'audit, remplir cette matrice et l'afficher avant toute correction :

| Feature | STARTER attendu | PRO attendu | Implémenté ? | Où ? |
|---------|----------------|------------|-------------|------|
| Profil marketplace | ✅ | ✅ | ? | ? |
| Réception demandes | ✅ | ✅ | ? | ? |
| Chat client | ✅ | ✅ | ? | ? |
| Calendrier | ✅ | ✅ | ? | ? |
| Paiements sécurisés | ✅ | ✅ | ? | ? |
| Commission | 7% | 0% | ? | ? |
| Fiche client avancée | ❌ | ✅ | ? | ? |
| Analytics & stats | ❌ | ✅ | ? | ? |
| Export PDF fiches | ❌ | ✅ | ? | ? |
| Export CSV/Excel | ❌ | ✅ | ? | ? |
| Portfolio illimité | ❌ | ✅ | ? | ? |
| Traçabilité complète | ❌ | ✅ | ? | ? |
| Support prioritaire | ❌ | ✅ | ? | ? |

### 1.4 — Identifier les routes PRO-only

Lister toutes les routes qui DEVRAIENT être protégées PRO-only mais ne le sont pas.
Comparer les routes existantes avec la matrice ci-dessus.

---

## PHASE 2 — DIAGNOSTIC : Pourquoi STARTER accède au contenu PRO

Après l'audit, identifier la cause exacte parmi :

**CAS A** — Les middlewares PRO-only existent mais ne sont pas appliqués sur les routes
**CAS B** — Les middlewares existent mais `isPro()` retourne toujours `true` (bug logique)
**CAS C** — Aucun middleware de restriction n'existe (le code "en place" est incomplet)
**CAS D** — Les vues ont des conditions `@if isPro` mais les controllers n'ont aucune protection
**CAS E** — `isPro()` inclut `isOnTrial()` donc STARTER est traité comme PRO pendant le trial

> ⚠️ Documenter LE CAS EXACT identifié avant de passer à la Phase 3.

---

## PHASE 3 — CORRECTIONS

### 3.1 — Solidifier `isPro()` et `isStarter()` dans le trait HasSubscription

```php
// app/Traits/HasSubscription.php (ou dans les modèles Tattooer/Piercer)

public function isStarter(): bool
{
    return $this->is_subscribed && $this->current_plan === 'starter';
}

public function isPro(): bool
{
    // ⚠️ Le trial donne accès PRO MAIS n'est PAS un abonnement STARTER
    // isPro() = PRO payant OU trial actif (accès complet pendant l'essai)
    return ($this->is_subscribed && $this->current_plan === 'pro')
        || $this->isOnTrial();
}

public function isOnTrial(): bool
{
    return !$this->is_subscribed
        && $this->trial_ends_at !== null
        && $this->trial_ends_at->isFuture();
}

public function canAccessProFeature(): bool
{
    return $this->isPro(); // PRO payant OU trial
}

public function canAccessStarterFeature(): bool
{
    return $this->isStarter() || $this->isPro(); // STARTER peut accéder aux features de base
}
```

### 3.2 — Créer ou corriger le middleware `EnsureProPlan`

Si le middleware existe : vérifier et corriger sa logique.
Si il n'existe pas : le créer.

```php
// app/Http/Middleware/EnsureProPlan.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProPlan
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $artist = $user->tattooer ?? $user->piercer ?? null;

        if (!$artist || !$artist->canAccessProFeature()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Accès réservé au plan PRO'], 403);
            }
            // Rediriger vers la page de souscription avec message
            return redirect()
                ->route('subscription.plans') // adapter le nom de route exact
                ->with('upgrade_required', 'Cette fonctionnalité est réservée au plan PRO.');
        }

        return $next($request);
    }
}
```

Enregistrer dans `bootstrap/app.php` (Laravel 12) :
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'pro.plan'     => \App\Http\Middleware\EnsureProPlan::class,
        'starter.plan' => \App\Http\Middleware\EnsureStarterPlan::class,
    ]);
})
```

### 3.3 — Appliquer le middleware sur les routes PRO-only

Dans `routes/web.php`, protéger les routes selon la matrice :

```php
// Routes accessibles STARTER + PRO (fonctionnalités de base)
Route::middleware(['auth', 'verified', 'starter.plan'])->group(function () {
    // chat, calendrier, demandes, profil marketplace...
});

// Routes PRO uniquement
Route::middleware(['auth', 'verified', 'pro.plan'])->group(function () {
    // analytics, export PDF, export CSV, fiche client avancée...
    Route::get('/tattooer/analytics', [AnalyticsController::class, 'index']);
    Route::get('/tattooer/clients/{id}', [ClientController::class, 'show']); // fiche avancée
    Route::get('/tattooer/export/pdf/{id}', [ExportController::class, 'pdf']);
    Route::get('/tattooer/export/csv', [ExportController::class, 'csv']);
    // Même chose pour pierceur/...
});
```

> Adapter les noms de routes exacts trouvés dans l'audit Phase 1.

### 3.4 — Protéger les controllers en double sécurité

Pour chaque controller gérant une feature PRO-only, ajouter en début de méthode :

```php
public function show(Request $request, $id)
{
    $artist = $request->user()->tattooer ?? $request->user()->piercer;

    abort_unless($artist?->canAccessProFeature(), 403, 'Accès réservé au plan PRO');

    // ... reste du code
}
```

### 3.5 — Cadenas dans les vues sidebar/navigation

Pour les items PRO-only dans la sidebar, vérifier que la condition est correcte :

```blade
{{-- ✅ Correct : cadenas pour STARTER, accessible pour PRO/trial --}}
@if ($artist->canAccessProFeature())
    <a href="{{ route('tattooer.analytics') }}">Analytics</a>
@else
    <span class="opacity-50 cursor-not-allowed">
        Analytics 🔒
        <span class="text-xs">(Plan PRO)</span>
    </span>
@endif
```

---

## PHASE 4 — AUDIT & FIX Application Fee Stripe (Commission 7% STARTER)

### 4.1 — Audit de l'implémentation actuelle

```bash
# Chercher toute implémentation de application_fee / commission
grep -r "application_fee\|applicationFee\|commission\|transfer_data\|7\b" \
  app/ --include="*.php" -l

# Chercher dans les controllers de booking/paiement
grep -r "PaymentIntent\|charge\|transfer\|fee" app/ --include="*.php"
```

Afficher le code complet de chaque méthode trouvée avant modification.

### 4.2 — Logique Application Fee selon le plan

L'Application Fee Stripe doit s'appliquer UNIQUEMENT sur les artistes STARTER :

```php
// Service ou controller gérant le paiement client → artiste

private function getApplicationFeeAmount(int $amountInCents, $artist): int
{
    // 7% commission sur STARTER uniquement
    if ($artist->isStarter()) {
        return (int) round($amountInCents * 0.07);
    }

    // PRO et trial : 0% commission
    return 0;
}
```

### 4.3 — Vérifier l'implémentation sur PaymentIntent

L'Application Fee doit être sur le PaymentIntent au moment du paiement client :

```php
// ✅ Correct — avec Connect Stripe
$paymentIntent = \Stripe\PaymentIntent::create([
    'amount'               => $amountInCents,
    'currency'             => 'eur',
    'payment_method_types' => ['card'],
    'application_fee_amount' => $this->getApplicationFeeAmount($amountInCents, $artist),
    'transfer_data'        => [
        'destination' => $artist->user->stripe_connect_id, // compte Connect de l'artiste
    ],
    // ...
]);
```

> ⚠️ `application_fee_amount` ne fonctionne qu'avec Stripe Connect (destination charges
> ou direct charges). Vérifier que les artistes ont bien un `stripe_connect_id` sur le
> modèle User ou Tattooer/Piercer.

### 4.4 — Vérifier que le trial n'est PAS commissionné

```php
// Un artiste en trial a accès PRO → commission 0%
// isOnTrial() → isPro() → 0% commission ✅
// Vérifier que isStarter() retourne false pendant le trial (voir Phase 3.1)
```

### 4.5 — Test de la commission

```bash
php artisan tinker
```
```php
// Simuler le calcul
$artist = Tattooer::find(ID_ARTISTE_STARTER);
$amount = 10000; // 100€ en centimes

$fee = $artist->isStarter() ? round($amount * 0.07) : 0;
echo "Commission : {$fee} centimes = " . ($fee/100) . "€"; // doit afficher 700 centimes = 7€

// Vérifier qu'un PRO n'est pas commissionné
$artistPro = Tattooer::find(ID_ARTISTE_PRO);
$feePro = $artistPro->isStarter() ? round($amount * 0.07) : 0;
echo "Commission PRO : {$feePro}"; // doit afficher 0
```

---

## PHASE 5 — Vérification finale

Après toutes les corrections, exécuter :

```bash
# Vérifier qu'aucune route PRO-only n'est accessible sans middleware
php artisan route:list | grep -E "analytics|export|fiche|client" 

# Vérifier les middlewares enregistrés
php artisan route:list --path=tattooer | grep middleware
```

Tester manuellement avec les comptes de test :
- Compte STARTER → tenter d'accéder à `/tattooer/analytics` → doit retourner 403 ou redirect
- Compte PRO → accès analytics → doit fonctionner
- Compte trial → accès analytics → doit fonctionner (trial = accès PRO complet)
- Compte trial expiré → doit être bloqué par le middleware `TrialService` existant

---

## 📋 Rapport attendu à la fin

Claude Code doit fournir :

1. **Matrice complète** features/plan remplie (ce qui était implémenté vs manquant)
2. **Cause racine** du problème STARTER (CAS A/B/C/D/E identifié)
3. **Liste fichiers modifiés** avec résumé des changements
4. **État Application Fee** : était-elle en place ? Correcte ? Modifiée ?
5. **Commandes de test tinker** pour vérifier rapidement en local

---

## ⚠️ Contraintes absolues
- Ne PAS casser la logique Studio (ne pas toucher aux fichiers Studio sauf si partagés)
- Le trial donne accès PRO complet → `isOnTrial()` doit être traité comme `isPro()` partout
- Les montants sont en EUROS en base (pas en centimes) — l'Application Fee Stripe
  se calcule en centimes uniquement au moment de créer le PaymentIntent
- Ne PAS modifier les migrations existantes
- Garder la compatibilité avec `TrialService` et `inkpik:block-expired-trials`
- Billable reste sur `User`
