# 💰 PROMPT E — REFONTE PRICING + SYSTÈME BÊTA-TESTEURS
# Pour Claude Code — Suppression plan Free, nouveaux tarifs, trial 14j, blocage post-trial, coupons bêta
# Commit après chaque phase

## CONTEXTE

Refonte complète du modèle tarifaire d'Ink&Pik suite à l'étude de marché et de la concurrence.

### CHANGEMENTS MAJEURS

| | ANCIEN | NOUVEAU |
|--|--------|---------|
| Plan FREE | 0€/mois, 7% commission | **SUPPRIMÉ** |
| Plan STARTER | ❌ n'existait pas | **9,99€/mois**, 7% commission |
| Plan PRO | 49,99€/mois, 0% commission | **29,99€/mois**, 0% commission |
| Plan STUDIO | 79,99€/mois + 39,99€/artiste | **59,99€/mois** + **24,99€/artiste** |
| Trial | 14j studio uniquement | **14j TOUS les plans, sans CB** |
| Post-trial | Plan Free par défaut | **Compte bloqué** (invisible marketplace) |

### SYSTÈME BÊTA-TESTEURS
- **30% de réduction à vie** (tant que l'abonnement reste actif sans interruption)
- **1 mois offert** à l'inscription
- Flag `is_beta_tester` sur le user
- Coupon Stripe `BETA-LAUNCH-30` appliqué automatiquement

### PRIX BÊTA-TESTEURS
| Plan | Normal | Bêta (-30%) |
|------|--------|-------------|
| STARTER | 9,99€ | 6,99€ |
| PRO | 29,99€ | 20,99€ |
| STUDIO | 59,99€ | 41,99€ |
| +Artiste | 24,99€ | 17,49€ |

Stack : Laravel 12, Livewire 3.7, Stripe Connect, Cashier.

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT PRICING ==="

# ── ENUMS & CONFIG ──
echo "--- ENUMS ---"

# 0A. Enum des plans existant
find app/Enums -name "*Plan*" -o -name "*Subscription*" -o -name "*Pricing*" | head -5
grep -rn "FREE\|PRO\|STUDIO\|STARTER\|plan\|Plan" app/Enums/ --include="*.php" | head -15

# 0B. Config inkpik (plans, tarifs, commissions)
cat config/inkpik.php 2>/dev/null | head -60
grep -rn "49.99\|79.99\|39.99\|0.07\|7%\|commission\|plan_" config/ --include="*.php" | head -15

# 0C. .env pricing
grep -n "PLAN\|PRICE\|COMMISSION\|STRIPE_PRICE\|SUBSCRIPTION\|TRIAL\|FREE\|PRO\|STUDIO" .env | head -20

# ── STRIPE ──
echo "--- STRIPE ---"

# 0D. Stripe price IDs
grep -rn "price_\|STRIPE_PRICE\|stripe_price\|price_id" .env config/ app/ --include="*.php" --include="*.env" | head -15

# 0E. Services Stripe abonnement
find app/Services -name "*Stripe*" -o -name "*Subscription*" -o -name "*Billing*" | head -5
grep -n "function " app/Services/StripeService.php 2>/dev/null | head -20
grep -n "function " app/Services/StripeStudioSubscriptionService.php 2>/dev/null | head -20

# 0F. Modèle Subscription
grep -n "fillable\|function \|class " app/Models/Subscription.php 2>/dev/null | head -15

# ── TRIAL ──
echo "--- TRIAL ---"

# 0G. Logique trial actuelle
grep -rn "trial\|trial_ends_at\|trial_days\|is_trial\|onTrial" app/ --include="*.php" | head -15

# 0H. Colonnes trial en base
php artisan tinker --execute="
  foreach(['users', 'tattooers', 'piercers', 'studios', 'subscriptions'] as \$t) {
    if (Schema::hasTable(\$t)) {
      \$cols = Schema::getColumnListing(\$t);
      \$trialCols = array_filter(\$cols, fn(\$c) => str_contains(\$c, 'trial'));
      if (\$trialCols) echo \$t . ' trial cols: ' . implode(', ', \$trialCols) . PHP_EOL;
      \$subCols = array_filter(\$cols, fn(\$c) => str_contains(\$c, 'subscri') || str_contains(\$c, 'plan') || str_contains(\$c, 'beta'));
      if (\$subCols) echo \$t . ' plan/sub cols: ' . implode(', ', \$subCols) . PHP_EOL;
    }
  }
"

# ── COMMISSION ──
echo "--- COMMISSION ---"

# 0I. Logique commission 7% / 0%
grep -rn "application_fee\|commission\|0.07\|7%\|fee_amount" app/ --include="*.php" | head -15

# 0J. Où est calculée la commission dans le flux de paiement
grep -rn "application_fee_amount\|applicationFee\|getCommission\|calculateFee" app/ --include="*.php" | head -10

# ── MARKETPLACE VISIBILITÉ ──
echo "--- MARKETPLACE ---"

# 0K. Logique de visibilité marketplace
grep -rn "is_visible\|is_active\|isVisible\|isActive\|visible\|scope.*Active\|scope.*Visible" app/Models/Tattooer.php app/Models/Piercer.php app/Models/Studio.php 2>/dev/null | head -15

# 0L. Query marketplace (comment les artistes sont filtrés)
grep -rn "is_visible\|is_active\|where.*active\|where.*visible" app/Http/Controllers/MarketplaceController.php app/Livewire/ --include="*.php" | head -15

# ── INSCRIPTION ──
echo "--- INSCRIPTION ---"

# 0M. Flux inscription tattooer/pierceur/studio
grep -n "function " app/Livewire/RegisterTattooer.php 2>/dev/null | head -10
grep -n "function " app/Livewire/RegisterPiercer.php 2>/dev/null | head -10
grep -n "function " app/Livewire/RegisterStudio.php 2>/dev/null | head -10

# 0N. Choix du plan à l'inscription
grep -rn "plan\|pricing\|FREE\|PRO\|STUDIO\|subscription" app/Livewire/Register*.php --include="*.php" | head -15

# ── PAGES LÉGALES ──
echo "--- PAGES LÉGALES ---"

# 0O. Références aux anciens prix dans CGV et autres pages
grep -rn "49.99\|79.99\|39.99\|0€\|gratuit\|plan FREE\|plan free" resources/views/legal/ --include="*.blade.php" | head -10

# ── BETA ──
echo "--- BETA ---"

# 0P. Colonnes beta existantes
php artisan tinker --execute="
  echo 'is_beta_tester on users: ' . (Schema::hasColumn('users', 'is_beta_tester') ? 'EXISTS' : 'ABSENT') . PHP_EOL;
"

# 0Q. Coupons Stripe existants
grep -rn "coupon\|promo\|discount\|BETA" app/ config/ --include="*.php" | head -10

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## PHASE 1 — ENUM DES PLANS

Modifier ou créer l'enum des plans :

```php
// app/Enums/SubscriptionPlan.php
namespace App\Enums;

enum SubscriptionPlan: string
{
    case STARTER = 'starter';
    case PRO = 'pro';
    case STUDIO = 'studio';

    public function label(): string
    {
        return match ($this) {
            self::STARTER => 'Starter',
            self::PRO => 'Pro',
            self::STUDIO => 'Studio',
        };
    }

    public function price(): float
    {
        return match ($this) {
            self::STARTER => 9.99,
            self::PRO => 29.99,
            self::STUDIO => 59.99,
        };
    }

    public function pricePerExtraArtist(): float
    {
        return match ($this) {
            self::STUDIO => 24.99,
            default => 0,
        };
    }

    public function commissionRate(): float
    {
        return match ($this) {
            self::STARTER => 0.07, // 7%
            self::PRO => 0.0,     // 0%
            self::STUDIO => 0.0,  // 0%
        };
    }

    public function hasCommission(): bool
    {
        return $this === self::STARTER;
    }

    /**
     * Prix bêta-testeur (-30%).
     */
    public function betaPrice(): float
    {
        return round($this->price() * 0.70, 2);
    }

    public function betaPricePerExtraArtist(): float
    {
        return round($this->pricePerExtraArtist() * 0.70, 2);
    }

    public function trialDays(): int
    {
        return 14;
    }

    /**
     * Features incluses par plan.
     */
    public function features(): array
    {
        $starter = [
            'Profil artiste vérifié',
            'Visible dans la marketplace',
            'Gestion des demandes & RDV',
            'Messagerie client',
            'Acompte sécurisé (Stripe)',
            'Fiches clients & traçabilité',
            'Consentements & soins',
            'Notifications automatiques',
            'Commission 7% par prestation',
        ];

        $pro = [
            'Tout le plan Starter',
            '0% de commission',
            'Mise en avant dans la marketplace',
            'Export PDF complet',
            'Export comptabilité CSV/Excel',
            'Badge PRO vérifié',
            'Support prioritaire',
        ];

        $studio = [
            'Tout le plan Pro',
            '1 artiste inclus',
            'Gestion multi-artistes',
            'Dashboard studio centralisé',
            'Planning global',
            'Statistiques & revenus',
            'Profil studio marketplace',
            'Facturation centralisée',
            'Panel Filament avancé',
        ];

        return match ($this) {
            self::STARTER => $starter,
            self::PRO => $pro,
            self::STUDIO => $studio,
        };
    }
}
```

IMPORTANT :
- Si un enum `Plan` ou similaire existe déjà, le MODIFIER plutôt que d'en créer un nouveau
- Supprimer TOUTES les références au plan FREE dans l'enum
- Mettre à jour les imports partout où l'ancien enum est utilisé

```bash
# Trouver toutes les références à l'ancien plan FREE
grep -rn "Plan::FREE\|'free'\|\"free\"\|FREE_PLAN\|FREE" app/ config/ --include="*.php" | grep -iv "freelance\|freeText\|prefix" | head -20
```

Pour CHAQUE référence au plan FREE trouvée :
- Si c'est le plan par défaut → remplacer par `SubscriptionPlan::STARTER`
- Si c'est un check "pas d'abonnement" → remplacer par un check trial/actif
- Si c'est dans la logique commission → le STARTER a 7% commission

```bash
git add -A && git commit -m "feat(pricing): enum SubscriptionPlan — STARTER/PRO/STUDIO, suppression FREE"
```

---

## PHASE 2 — CONFIG & .ENV

### Config tarifs

```php
// config/inkpik.php — section pricing (ajouter ou modifier)
'pricing' => [
    'starter' => [
        'price' => env('STRIPE_PRICE_STARTER', 9.99),
        'stripe_price_id' => env('STRIPE_PRICE_ID_STARTER', ''),
        'commission_rate' => 0.07,
    ],
    'pro' => [
        'price' => env('STRIPE_PRICE_PRO', 29.99),
        'stripe_price_id' => env('STRIPE_PRICE_ID_PRO', ''),
        'commission_rate' => 0.0,
    ],
    'studio' => [
        'price' => env('STRIPE_PRICE_STUDIO', 59.99),
        'stripe_price_id' => env('STRIPE_PRICE_ID_STUDIO', ''),
        'extra_artist_price' => env('STRIPE_PRICE_STUDIO_EXTRA', 24.99),
        'stripe_price_id_extra' => env('STRIPE_PRICE_ID_STUDIO_EXTRA', ''),
        'commission_rate' => 0.0,
        'included_artists' => 1,
    ],
    'trial_days' => 14,
    'beta_discount_percent' => 30,
    'beta_coupon_id' => env('STRIPE_BETA_COUPON_ID', 'BETA-LAUNCH-30'),
],
```

### .env (ajouter les nouvelles variables)

```env
# Pricing — Stripe Price IDs (à créer dans Stripe Dashboard)
# IMPORTANT : Créer ces prix dans Stripe Dashboard AVANT de les référencer ici
STRIPE_PRICE_ID_STARTER=price_xxxxxxx
STRIPE_PRICE_ID_PRO=price_xxxxxxx
STRIPE_PRICE_ID_STUDIO=price_xxxxxxx
STRIPE_PRICE_ID_STUDIO_EXTRA=price_xxxxxxx

# Beta
STRIPE_BETA_COUPON_ID=BETA-LAUNCH-30
```

Mettre à jour le .env.example aussi.

### Supprimer les anciennes variables

```bash
# Trouver les anciennes variables de prix
grep -n "49.99\|79.99\|39.99\|PRICE.*FREE\|STRIPE_PRICE.*FREE" .env .env.example 2>/dev/null | head -10
```

Remplacer/supprimer les anciennes valeurs.

```bash
git add -A && git commit -m "feat(pricing): config tarifs STARTER/PRO/STUDIO + variables .env"
```

---

## PHASE 3 — MIGRATION + MODÈLE

### Migration

```bash
php artisan make:migration update_pricing_system
```

```php
public function up()
{
    // 1. Ajouter is_beta_tester sur users (si absent)
    if (!Schema::hasColumn('users', 'is_beta_tester')) {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_beta_tester')->default(false)->after('email_verified_at');
            $table->timestamp('beta_registered_at')->nullable()->after('is_beta_tester');
        });
    }

    // 2. Ajouter/modifier colonnes trial sur tattooers
    Schema::table('tattooers', function (Blueprint $table) {
        if (!Schema::hasColumn('tattooers', 'trial_ends_at')) {
            $table->timestamp('trial_ends_at')->nullable();
        }
        if (!Schema::hasColumn('tattooers', 'is_blocked')) {
            $table->boolean('is_blocked')->default(false);
        }
        // Modifier le plan par défaut : 'free' → 'starter' (si colonne plan existe)
        // OU ajouter la colonne plan si absente
        if (!Schema::hasColumn('tattooers', 'plan')) {
            $table->string('plan')->default('starter');
        }
    });

    // 3. Même chose pour piercers
    Schema::table('piercers', function (Blueprint $table) {
        if (!Schema::hasColumn('piercers', 'trial_ends_at')) {
            $table->timestamp('trial_ends_at')->nullable();
        }
        if (!Schema::hasColumn('piercers', 'is_blocked')) {
            $table->boolean('is_blocked')->default(false);
        }
        if (!Schema::hasColumn('piercers', 'plan')) {
            $table->string('plan')->default('starter');
        }
    });

    // 4. Studios (trial existe déjà normalement)
    Schema::table('studios', function (Blueprint $table) {
        if (!Schema::hasColumn('studios', 'is_blocked')) {
            $table->boolean('is_blocked')->default(false);
        }
    });

    // 5. Convertir les anciens plans FREE en STARTER
    DB::table('tattooers')->where('plan', 'free')->update(['plan' => 'starter']);
    DB::table('piercers')->where('plan', 'free')->update(['plan' => 'starter']);
    // OU si le plan est stocké ailleurs, adapter
}

public function down()
{
    // Reverse si nécessaire
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['is_beta_tester', 'beta_registered_at']);
    });
    // ... etc
}
```

IMPORTANT :
- Vérifier en Phase 0 quelles colonnes existent déjà
- Ne pas dupliquer des colonnes existantes
- Si `plan` est stocké dans une autre table ou comme enum, adapter
- Le `DB::table()->update()` convertit les anciens `free` en `starter`

```bash
php artisan migrate
git add -A && git commit -m "feat(pricing): migration — is_beta_tester, trial_ends_at, is_blocked, plan FREE→STARTER"
```

---

## PHASE 4 — LOGIQUE TRIAL 14 JOURS + BLOCAGE

### Service Trial

```php
// app/Services/TrialService.php
namespace App\Services;

use App\Enums\SubscriptionPlan;
use Carbon\Carbon;

class TrialService
{
    /**
     * Démarrer un trial de 14 jours pour un artiste.
     */
    public function startTrial($artisan): void
    {
        $artisan->update([
            'trial_ends_at' => now()->addDays(SubscriptionPlan::STARTER->trialDays()),
            'is_blocked' => false,
        ]);
    }

    /**
     * Vérifier si le trial est actif.
     */
    public function isOnTrial($artisan): bool
    {
        return $artisan->trial_ends_at 
            && $artisan->trial_ends_at->isFuture()
            && !$artisan->is_subscribed;
    }

    /**
     * Vérifier si le trial est expiré.
     */
    public function isTrialExpired($artisan): bool
    {
        if (!$artisan->trial_ends_at) return false;
        return $artisan->trial_ends_at->isPast() && !$artisan->is_subscribed;
    }

    /**
     * Vérifier si l'artiste a un accès actif (trial OU abonnement).
     */
    public function hasActiveAccess($artisan): bool
    {
        if ($artisan->is_subscribed) return true;
        if ($artisan->studio_id) return true; // Artiste studio = couvert
        return $this->isOnTrial($artisan);
    }

    /**
     * Bloquer un artiste dont le trial est expiré.
     */
    public function blockExpiredTrial($artisan): void
    {
        if ($this->isTrialExpired($artisan)) {
            $artisan->update(['is_blocked' => true]);
        }
    }

    /**
     * Jours restants du trial.
     */
    public function trialDaysRemaining($artisan): int
    {
        if (!$artisan->trial_ends_at || $artisan->trial_ends_at->isPast()) return 0;
        return (int) now()->diffInDays($artisan->trial_ends_at, false);
    }
}
```

### Commande artisan pour bloquer les trials expirés

```php
// app/Console/Commands/BlockExpiredTrials.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Services\TrialService;

class BlockExpiredTrials extends Command
{
    protected $signature = 'inkpik:block-expired-trials';
    protected $description = 'Bloquer les artistes dont le trial 14j est expiré sans abonnement';

    public function handle(TrialService $trialService)
    {
        $blocked = 0;

        // Tattooers
        Tattooer::where('is_subscribed', false)
            ->whereNull('studio_id')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', now())
            ->where('is_blocked', false)
            ->chunk(100, function ($tattooers) use ($trialService, &$blocked) {
                foreach ($tattooers as $tattooer) {
                    $trialService->blockExpiredTrial($tattooer);
                    $blocked++;
                }
            });

        // Piercers
        Piercer::where('is_subscribed', false)
            ->whereNull('studio_id')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', now())
            ->where('is_blocked', false)
            ->chunk(100, function ($piercers) use ($trialService, &$blocked) {
                foreach ($piercers as $piercer) {
                    $trialService->blockExpiredTrial($piercer);
                    $blocked++;
                }
            });

        $this->info("$blocked artiste(s) bloqué(s) pour trial expiré.");
    }
}
```

Ajouter au scheduler :
```php
// Dans app/Console/Kernel.php ou routes/console.php (Laravel 12)
Schedule::command('inkpik:block-expired-trials')->daily();
```

### Middleware de visibilité marketplace

Modifier la query marketplace pour exclure les artistes bloqués :

```php
// PARTOUT où les artistes sont requêtés pour la marketplace :
// Ajouter ->where('is_blocked', false) aux queries

// Exemple dans le composant marketplace :
$query = Tattooer::where('is_active', true)
    ->where('is_blocked', false) // ← AJOUTER PARTOUT
    // ... reste de la query
```

### Démarrer le trial à l'inscription

Dans les composants Livewire d'inscription :

```php
// Dans RegisterTattooer::register() / RegisterPiercer::register()
// APRÈS la création du tattooer/piercer :

app(TrialService::class)->startTrial($tattooer);

// Pour le studio, le trial existe déjà — vérifier qu'il est de 14 jours
```

```bash
git add -A && git commit -m "feat(pricing): TrialService + commande BlockExpiredTrials + middleware marketplace + trial à l'inscription"
```

---

## PHASE 5 — LOGIQUE COMMISSION ADAPTÉE

### Modifier le calcul de commission

La commission ne dépend plus de `is_subscribed` (boolean) mais du **plan** :
- STARTER = 7% commission
- PRO / STUDIO = 0% commission

```bash
# Trouver la logique de calcul de commission actuelle
grep -rn "application_fee\|commission\|0.07\|fee_amount\|calculateFee\|getCommission" app/ --include="*.php" | head -15
```

Modifier la logique :

```php
// Exemple — adapter selon le code existant
public function getCommissionRate($artisan): float
{
    // Studio = toujours 0%
    if ($artisan->studio_id) return 0.0;

    // Plan de l'artiste
    $plan = SubscriptionPlan::tryFrom($artisan->plan ?? 'starter');
    
    return $plan ? $plan->commissionRate() : 0.07; // défaut 7%
}

// Dans le flux de paiement Stripe (DepositPaymentController ou équivalent) :
$commissionRate = $this->getCommissionRate($artisan);
$applicationFee = $commissionRate > 0 
    ? (int) round($amount * $commissionRate) 
    : 0;

// Stripe Checkout session
$sessionParams = [
    // ... autres params
];
if ($applicationFee > 0) {
    $sessionParams['payment_intent_data']['application_fee_amount'] = $applicationFee;
}
```

IMPORTANT : Chercher TOUTES les occurrences de `is_subscribed` utilisées pour le calcul de commission et les remplacer par le check du plan.

```bash
git add -A && git commit -m "feat(pricing): commission basée sur plan (STARTER=7%, PRO/STUDIO=0%)"
```

---

## PHASE 6 — SYSTÈME BÊTA-TESTEURS

### Service Beta

```php
// app/Services/BetaService.php
namespace App\Services;

use App\Models\User;

class BetaService
{
    const DISCOUNT_PERCENT = 30;
    const FREE_MONTHS = 1;
    const COUPON_ID = 'BETA-LAUNCH-30'; // À créer dans Stripe Dashboard

    /**
     * Marquer un utilisateur comme bêta-testeur.
     */
    public function registerBetaTester(User $user): void
    {
        $user->update([
            'is_beta_tester' => true,
            'beta_registered_at' => now(),
        ]);
    }

    /**
     * L'utilisateur est-il un bêta-testeur actif ?
     * Actif = is_beta_tester ET abonnement jamais interrompu depuis le beta_registered_at.
     */
    public function isActiveBetaTester(User $user): bool
    {
        return $user->is_beta_tester && $user->beta_registered_at !== null;
    }

    /**
     * Obtenir le coupon Stripe ID pour les bêta-testeurs.
     */
    public function getStripeCouponId(): string
    {
        return config('inkpik.pricing.beta_coupon_id', self::COUPON_ID);
    }

    /**
     * Obtenir le nombre de mois offerts.
     */
    public function getFreeTrialDays(): int
    {
        return self::FREE_MONTHS * 30; // 1 mois = ~30 jours
    }

    /**
     * Appliquer le coupon bêta lors de la création d'abonnement Stripe.
     * Retourne les paramètres Stripe à ajouter à la session/subscription.
     */
    public function getStripeDiscountParams(User $user): array
    {
        if (!$this->isActiveBetaTester($user)) {
            return [];
        }

        return [
            'discounts' => [
                [
                    'coupon' => $this->getStripeCouponId(),
                ],
            ],
            // 1 mois offert en plus du trial de 14j
            // Implémenté comme trial étendu : 14j trial + 30j offert = 44j
            // OU coupon avec 1 mois gratuit dans Stripe
        ];
    }
}
```

### Intégrer dans le flux d'abonnement

Trouver le service/controller qui crée les abonnements Stripe :

```bash
grep -rn "createSubscription\|newSubscription\|checkout\|Subscription::create\|stripe.*subscribe" app/Services/ app/Http/Controllers/ --include="*.php" | head -10
```

Modifier pour appliquer le coupon bêta :

```php
// Dans le service d'abonnement, lors de la création Stripe Checkout :
$betaService = app(BetaService::class);
$user = auth()->user();

$checkoutParams = [
    'line_items' => [...],
    'mode' => 'subscription',
    // ... autres params
];

// Appliquer le coupon bêta si applicable
if ($betaService->isActiveBetaTester($user)) {
    $checkoutParams['discounts'] = [
        ['coupon' => $betaService->getStripeCouponId()],
    ];
    // OU trial étendu pour le 1er mois gratuit
    $checkoutParams['subscription_data']['trial_period_days'] = 44; // 14j trial + 30j offert
}
```

### Afficher le statut bêta dans le profil

```blade
{{-- Dans les vues settings/profil --}}
@if (auth()->user()->is_beta_tester)
<div class="flex items-center gap-2 px-3 py-2 bg-beige-peau/10 border border-beige-peau/30 rounded-lg">
    <span class="text-beige-peau text-lg">🏆</span>
    <div>
        <p class="text-sm font-semibold text-beige-peau">Bêta-testeur</p>
        <p class="text-xs text-titane">-30% à vie sur votre abonnement, merci pour votre confiance !</p>
    </div>
</div>
@endif
```

### NOTE IMPORTANTE — Coupon Stripe à créer manuellement

Le coupon `BETA-LAUNCH-30` doit être créé dans le **Stripe Dashboard** :
1. Aller dans Stripe Dashboard → Products → Coupons
2. Créer un coupon : `BETA-LAUNCH-30`, 30% de réduction, durée "forever" (tant que l'abonnement est actif)
3. OU via API Stripe (mais le dashboard est plus sûr pour un coupon permanent)

```bash
# Ajouter un commentaire/README rappelant de créer le coupon
echo "# STRIPE — Coupons à créer
# BETA-LAUNCH-30 : 30% forever, pour les bêta-testeurs
# Créer dans Stripe Dashboard → Products → Coupons" >> STRIPE_SETUP.md
```

```bash
git add -A && git commit -m "feat(pricing): BetaService — coupon 30% à vie + 1 mois offert pour bêta-testeurs"
```

---

## PHASE 7 — PAGES LÉGALES + PAGE PRICING

### Mettre à jour les CGV artistes

```bash
grep -n "49.99\|79.99\|39.99\|0€/mois\|plan FREE\|gratuit\|free" resources/views/legal/cgv-artistes.blade.php | head -10
```

Remplacer TOUTES les références aux anciens prix :

| Ancien | Nouveau |
|--------|---------|
| Plan FREE : 0€/mois, commission 7% | **Plan STARTER : 9,99€/mois, commission 7%** |
| Plan PRO : 49,99€ TTC/mois | **Plan PRO : 29,99€ TTC/mois** |
| Plan STUDIO : 79,99€ TTC/mois + 39,99€/artiste | **Plan STUDIO : 59,99€ TTC/mois + 24,99€/artiste** |
| Essai 14j studio uniquement | **Essai 14j tous les plans, sans carte bancaire** |

Ajouter la mention bêta-testeurs dans les CGV :
```
Les utilisateurs ayant participé au programme bêta-testeur bénéficient d'une réduction de 30% 
sur le prix de leur abonnement, applicable tant que l'abonnement reste actif sans interruption.
```

### Créer ou mettre à jour la page pricing

```bash
# Vérifier si une page pricing existe
find resources/views -name "*pricing*" -o -name "*tarif*" -o -name "*plan*" | head -5
php artisan route:list 2>&1 | grep "pricing\|tarif\|plan" | head -5
```

Si elle n'existe pas, créer une page pricing publique :

```php
// Route
Route::get('/tarifs', function () {
    return view('pricing');
})->name('pricing');
```

Créer la vue `resources/views/pricing.blade.php` avec les 3 plans et le design Ink&Pik. Inclure :
- Les 3 cards (STARTER / PRO / STUDIO) avec prix, features, bouton CTA
- Badge "14 jours d'essai gratuit" sur chaque plan
- Mention "Sans carte bancaire"
- Section FAQ pricing
- Si bêta actif : afficher les prix barrés avec les prix bêta

```bash
git add -A && git commit -m "feat(pricing): pages légales mises à jour + page tarifs publique"
```

---

## PHASE 8 — BANNIÈRE TRIAL + BLOCAGE UI

### Bannière trial (jours restants)

Créer un partial affiché dans le dashboard quand l'artiste est en trial :

```blade
{{-- resources/views/partials/trial-banner.blade.php --}}
@php
    $artisan = auth()->user()->tattooer ?? auth()->user()->piercer ?? null;
    $trialService = app(\App\Services\TrialService::class);
    $isOnTrial = $artisan && $trialService->isOnTrial($artisan);
    $daysRemaining = $artisan ? $trialService->trialDaysRemaining($artisan) : 0;
    $isBlocked = $artisan?->is_blocked ?? false;
@endphp

@if ($isOnTrial && $daysRemaining <= 7)
{{-- Bannière urgente : moins de 7 jours --}}
<div class="bg-gradient-to-r from-rouge-alerte/20 to-rouge-alerte/5 border border-rouge-alerte/30 rounded-xl p-4 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-2xl">⏰</span>
            <div>
                <p class="text-sm font-semibold text-ivoire-text">
                    Plus que {{ $daysRemaining }} jour{{ $daysRemaining > 1 ? 's' : '' }} d'essai gratuit
                </p>
                <p class="text-xs text-titane mt-0.5">
                    Votre profil sera masqué de la marketplace après la fin de l'essai. Choisissez un abonnement pour continuer.
                </p>
            </div>
        </div>
        <a href="{{ route('pricing') }}" class="px-4 py-2 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors whitespace-nowrap">
            Voir les tarifs
        </a>
    </div>
</div>
@elseif ($isOnTrial)
{{-- Bannière info : trial actif --}}
<div class="bg-beige-peau/5 border border-beige-peau/20 rounded-xl p-4 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-lg">🎁</span>
            <p class="text-sm text-titane">
                Essai gratuit — <strong class="text-ivoire-text">{{ $daysRemaining }} jours restants</strong>
            </p>
        </div>
        <a href="{{ route('pricing') }}" class="text-xs text-beige-peau hover:underline">Voir les tarifs</a>
    </div>
</div>
@elseif ($isBlocked)
{{-- Bannière bloquée --}}
<div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-xl p-6 mb-6 text-center">
    <span class="text-3xl">🔒</span>
    <h3 class="text-lg font-semibold text-ivoire-text mt-3">Votre essai gratuit est terminé</h3>
    <p class="text-sm text-titane mt-2 max-w-md mx-auto">
        Votre profil n'est plus visible dans la marketplace. Choisissez un abonnement pour réactiver votre compte et recevoir des demandes.
    </p>
    <a href="{{ route('pricing') }}" class="inline-block mt-4 px-6 py-2.5 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
        Choisir mon abonnement
    </a>
</div>
@endif
```

Inclure dans les dashboards tattooer, piercer, studio :

```blade
{{-- En haut du dashboard --}}
@include('partials.trial-banner')
```

### Page bloquée (pour les artistes bloqués)

Quand un artiste bloqué accède à une fonctionnalité restreinte, afficher un message au lieu de bloquer avec une 403 :

```php
// Middleware ou check dans les controllers
// Pour les pages qui nécessitent un accès actif :
$artisan = auth()->user()->tattooer ?? auth()->user()->piercer;
if ($artisan?->is_blocked) {
    return redirect()->route('pricing')
        ->with('warning', 'Votre essai gratuit est terminé. Choisissez un abonnement pour continuer.');
}
```

```bash
git add -A && git commit -m "feat(pricing): bannière trial + UI blocage post-trial + redirect pricing"
```

---

## PHASE 9 — INSCRIPTION : SUPPRESSION CHOIX FREE + TRIAL AUTO

### Modifier les formulaires d'inscription

```bash
grep -rn "free\|FREE\|plan.*free\|gratuit\|0€" app/Livewire/Register*.php resources/views/livewire/register-*.blade.php resources/views/auth/ --include="*.php" --include="*.blade.php" | head -20
```

Pour CHAQUE formulaire d'inscription (tattooer, piercer, studio) :

1. **Supprimer le choix du plan FREE** dans l'interface
2. **Plan STARTER par défaut** (au lieu de FREE)
3. **Démarrer le trial 14j automatiquement** après inscription
4. **Message de bienvenue** mentionnant le trial

```php
// Dans RegisterTattooer::register() (ou équivalent)
// APRÈS la création du profil :

// Plan starter par défaut
$tattooer->update(['plan' => 'starter']);

// Démarrer le trial 14 jours
app(TrialService::class)->startTrial($tattooer);

// Notification de bienvenue avec info trial
$user->notify(new WelcomeWithTrialNotification());
```

### Créer la notification de bienvenue

```php
// app/Notifications/WelcomeWithTrialNotification.php
class WelcomeWithTrialNotification extends Notification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bienvenue sur Ink&Pik — Votre essai gratuit de 14 jours')
            ->greeting('Bienvenue !')
            ->line('Votre compte artiste a été créé avec succès.')
            ->line('Vous bénéficiez de 14 jours d\'essai gratuit pour découvrir toutes les fonctionnalités de la plateforme.')
            ->line('Pendant cette période, votre profil est visible dans la marketplace et vous pouvez recevoir des demandes.')
            ->action('Découvrir les tarifs', route('pricing'))
            ->line('À la fin de l\'essai, choisissez un abonnement pour continuer à utiliser Ink&Pik.');
    }
}
```

```bash
git add -A && git commit -m "feat(pricing): inscription — STARTER par défaut + trial 14j auto + notification bienvenue"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PRICING ==="

# V1. Enum
echo "--- ENUM ---"
grep -c "STARTER\|PRO\|STUDIO" app/Enums/SubscriptionPlan.php 2>/dev/null
grep -c "FREE" app/Enums/SubscriptionPlan.php 2>/dev/null
echo "FREE devrait être 0, les 3 plans > 0"

# V2. Config
echo "--- CONFIG ---"
grep "9.99\|29.99\|59.99\|24.99" config/inkpik.php 2>/dev/null | wc -l
echo "Nouveaux prix dans config (doit être > 0)"

# V3. Migration
echo "--- MIGRATION ---"
php artisan tinker --execute="
  echo 'is_beta_tester: ' . (Schema::hasColumn('users', 'is_beta_tester') ? 'OK' : 'ABSENT') . PHP_EOL;
  echo 'trial_ends_at tattooers: ' . (Schema::hasColumn('tattooers', 'trial_ends_at') ? 'OK' : 'ABSENT') . PHP_EOL;
  echo 'is_blocked tattooers: ' . (Schema::hasColumn('tattooers', 'is_blocked') ? 'OK' : 'ABSENT') . PHP_EOL;
"

# V4. Services
echo "--- SERVICES ---"
ls app/Services/TrialService.php 2>/dev/null && echo "TrialService OK"
ls app/Services/BetaService.php 2>/dev/null && echo "BetaService OK"

# V5. Commande
echo "--- COMMANDE ---"
php artisan list 2>&1 | grep "block-expired\|inkpik" | head -5

# V6. Commission
echo "--- COMMISSION ---"
grep -c "commissionRate\|commission_rate\|STARTER\|plan.*commission" app/ -r --include="*.php" | head -5

# V7. Pages légales
echo "--- LEGAL ---"
grep -c "9,99\|29,99\|59,99\|24,99" resources/views/legal/cgv-artistes.blade.php 2>/dev/null
echo "Nouveaux prix dans CGV (doit être > 0)"
grep -c "49,99\|79,99\|39,99\|0€/mois\|plan FREE" resources/views/legal/cgv-artistes.blade.php 2>/dev/null
echo "Anciens prix dans CGV (devrait être 0)"

# V8. Marketplace blocage
echo "--- MARKETPLACE ---"
grep -c "is_blocked" app/Models/Tattooer.php app/Models/Piercer.php app/Livewire/ -r --include="*.php" 2>/dev/null
echo "is_blocked dans les queries (doit être > 0)"

# V9. Inscription
echo "--- INSCRIPTION ---"
grep -c "startTrial\|TrialService\|plan.*starter" app/Livewire/Register*.php 2>/dev/null
echo "Trial dans inscription (doit être > 0)"
grep -c "free\|FREE" app/Livewire/Register*.php 2>/dev/null
echo "Références FREE dans inscription (devrait être 0)"

# V10. Compilation
php artisan route:clear && php artisan view:clear
php artisan route:list 2>&1 | head -3
echo "Pas d'erreur = OK"

echo "=== PRICING TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 révèle la structure existante
2. **SUPPRIMER toutes les références au plan FREE** — grep exhaustif dans tout le projet
3. **Les anciens users FREE deviennent STARTER** — migration automatique
4. **Studio = toujours PRO** — ne JAMAIS bloquer un studio (studio has is_subscribed via le plan studio)
5. **Artistes studio = toujours PRO** — le check `studio_id` prime sur tout
6. **Commission = basée sur le plan** pas sur `is_subscribed`
7. **Coupon Stripe** `BETA-LAUNCH-30` doit être créé manuellement dans Stripe Dashboard
8. **Stripe Price IDs** doivent être créés dans Stripe Dashboard et renseignés dans .env
9. **Ne pas casser le flux de paiement existant** — tester le checkout après les modifications
10. **Commit après chaque phase** (8-9 commits)
11. **Les pages légales (CGV) doivent refléter les nouveaux prix exactement**
