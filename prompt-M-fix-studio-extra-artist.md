# 🚨 PROMPT M — FIX FACTURATION ARTISTE SUPPLÉMENTAIRE STUDIO
# Pour Claude Code — Ligne STUDIO_EXTRA séparée, pas quantité STUDIO incrémentée
# URGENCE — Commit après chaque fix

## CONTEXTE

**Bug critique** : Quand un studio ajoute un artiste, le code incrémente la quantité du prix STUDIO au lieu d'ajouter une ligne séparée avec le prix STUDIO_EXTRA.

**Actuel (FAUX)** :
```
Ink&Pik Salon (STUDIO) × 2 = 119,98€/mois  ← FAUX
```

**Attendu (CORRECT)** :
```
Ink&Pik Salon (STUDIO)       × 1 = 59,99€/mois  (abonnement de base)
Ink&Pik Artiste supp. (EXTRA) × 1 = 24,99€/mois  (1 artiste supplémentaire)
Total = 84,98€/mois
```

Le plan STUDIO inclut 1 artiste. Chaque artiste au-delà du 1er coûte 24,99€/mois via le prix `STRIPE_PRICE_ID_STUDIO_EXTRA`.

Stack : Laravel 12, Cashier v16, Stripe API.

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT PROMPT M ==="

# M0a. La méthode updateArtistQuantity
grep -B 5 -A 40 "function updateArtistQuantity\|function.*ArtistQuantity\|function.*artistCount\|function.*syncArtist" app/Services/StudioBillingService.php | head -60

# M0b. Comment cette méthode est appelée
grep -rn "updateArtistQuantity\|artistQuantity\|syncArtist" app/ --include="*.php" | head -10

# M0c. Price IDs .env
grep "STRIPE_PRICE_ID_STUDIO" .env | head -5

# M0d. Config pricing studio
grep -A 10 "'studio'" config/inkpik.php | head -15

# M0e. Subscription items en base
php artisan tinker --execute="
  if (Schema::hasTable('subscription_items')) {
    \$items = DB::table('subscription_items')->get();
    echo 'subscription_items (' . \$items->count() . ' rows):' . PHP_EOL;
    foreach(\$items as \$i) {
      echo '  #' . \$i->id . ' sub_id=' . \$i->subscription_id . ' stripe_id=' . \$i->stripe_id . ' stripe_product=' . (\$i->stripe_product ?? '?') . ' stripe_price=' . \$i->stripe_price . ' qty=' . \$i->quantity . PHP_EOL;
    }
  }
"

# M0f. L'abonnement actuel dans Stripe (sub_id)
php artisan tinker --execute="
  \$sub = DB::table('subscriptions')->where('type', 'default')->first();
  if (\$sub) {
    echo 'Sub: ' . \$sub->stripe_id . ' status=' . \$sub->stripe_status . PHP_EOL;
  }
"

# M0g. Nombre d'artistes dans le studio
php artisan tinker --execute="
  \$studio = \App\Models\Studio::first();
  if (\$studio) {
    \$tattooers = \$studio->tattooers()->count();
    \$piercers = \$studio->piercers()->count();
    echo 'Studio #' . \$studio->id . ' (' . \$studio->name . ')' . PHP_EOL;
    echo 'Tattooers: ' . \$tattooers . ', Piercers: ' . \$piercers . ', Total: ' . (\$tattooers + \$piercers) . PHP_EOL;
    echo 'Included: 1, Extra needed: ' . max(0, (\$tattooers + \$piercers) - 1) . PHP_EOL;
  }
"

# M0h. Le controller qui ajoute un artiste au studio
grep -rn "function.*storeArtist\|function.*createArtist\|function.*addArtist\|function.*inviteArtist" app/Http/Controllers/StudioController.php | head -10

# M0i. Où updateArtistQuantity est appelé
grep -B 5 -A 5 "updateArtistQuantity" app/Http/Controllers/StudioController.php app/Livewire/ --include="*.php" -r | head -20

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX M1 — CORRIGER updateArtistQuantity

### La logique correcte

L'abonnement Stripe doit avoir 2 ligne items :
1. **STUDIO** (prix de base) — quantité TOUJOURS 1
2. **STUDIO_EXTRA** (artiste supplémentaire) — quantité = nombre d'artistes - 1 (le 1er est inclus)

Si le studio a 1 artiste → pas de ligne EXTRA
Si le studio a 2 artistes → EXTRA × 1
Si le studio a 5 artistes → EXTRA × 4

### Réécrire la méthode

```php
// app/Services/StudioBillingService.php

/**
 * Mettre à jour le nombre d'artistes supplémentaires dans l'abonnement Stripe.
 * Le plan STUDIO inclut 1 artiste. Chaque artiste au-delà coûte STUDIO_EXTRA.
 */
public function updateArtistQuantity(Studio $studio): bool
{
    try {
        $user = $studio->user;
        if (!$user || !$user->subscribed('default')) {
            Log::warning('updateArtistQuantity: pas d\'abonnement actif', ['studio_id' => $studio->id]);
            return false;
        }

        $sub = $user->subscription('default');
        $stripe = new \Stripe\StripeClient(config('cashier.secret'));
        
        // Compter les artistes du studio
        $totalArtists = $studio->tattooers()->count() + $studio->piercers()->count();
        $includedArtists = (int) config('inkpik.pricing.studio.included_artists', 1);
        $extraArtists = max(0, $totalArtists - $includedArtists);

        $studioPriceId = config('inkpik.pricing.studio.stripe_price_id');
        $extraPriceId = config('inkpik.pricing.studio.stripe_price_id_extra');
        
        if (!$extraPriceId) {
            Log::error('updateArtistQuantity: STRIPE_PRICE_ID_STUDIO_EXTRA non configuré');
            return false;
        }

        // Récupérer l'abonnement Stripe
        $stripeSub = $stripe->subscriptions->retrieve($sub->stripe_id, [
            'expand' => ['items'],
        ]);

        // Chercher les items existants
        $studioItem = null;
        $extraItem = null;
        foreach ($stripeSub->items->data as $item) {
            if ($item->price->id === $studioPriceId) {
                $studioItem = $item;
            }
            if ($item->price->id === $extraPriceId) {
                $extraItem = $item;
            }
        }

        // S'assurer que le prix STUDIO est bien à quantité 1 (pas plus)
        if ($studioItem && $studioItem->quantity > 1) {
            Log::warning('updateArtistQuantity: prix STUDIO avait quantité > 1, correction', [
                'old_quantity' => $studioItem->quantity,
            ]);
            $stripe->subscriptionItems->update($studioItem->id, [
                'quantity' => 1,
            ]);
        }

        if ($extraArtists > 0) {
            if ($extraItem) {
                // Mettre à jour la quantité de l'item EXTRA existant
                $stripe->subscriptionItems->update($extraItem->id, [
                    'quantity' => $extraArtists,
                ]);
                Log::info('updateArtistQuantity: EXTRA mis à jour', [
                    'studio_id' => $studio->id,
                    'extra_artists' => $extraArtists,
                ]);
            } else {
                // Ajouter un nouvel item EXTRA à l'abonnement
                $stripe->subscriptionItems->create([
                    'subscription' => $sub->stripe_id,
                    'price' => $extraPriceId,
                    'quantity' => $extraArtists,
                ]);
                Log::info('updateArtistQuantity: EXTRA ajouté', [
                    'studio_id' => $studio->id,
                    'extra_artists' => $extraArtists,
                    'price_id' => $extraPriceId,
                ]);
            }
        } else {
            // Aucun artiste supplémentaire — supprimer l'item EXTRA s'il existe
            if ($extraItem) {
                $stripe->subscriptionItems->delete($extraItem->id, [
                    'proration_behavior' => 'create_prorations',
                ]);
                Log::info('updateArtistQuantity: EXTRA supprimé (plus d\'artiste supp.)', [
                    'studio_id' => $studio->id,
                ]);
            }
        }

        // Synchroniser subscription_items en local (Cashier)
        $this->syncSubscriptionItems($user, $sub);

        return true;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        Log::error('updateArtistQuantity Stripe error', [
            'studio_id' => $studio->id,
            'error' => $e->getMessage(),
            'code' => $e->getStripeCode(),
        ]);
        return false;
    } catch (\Exception $e) {
        Log::error('updateArtistQuantity error', [
            'studio_id' => $studio->id,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}

/**
 * Synchroniser les subscription_items Cashier avec Stripe.
 */
private function syncSubscriptionItems(User $user, $subscription): void
{
    try {
        $stripe = new \Stripe\StripeClient(config('cashier.secret'));
        $stripeSub = $stripe->subscriptions->retrieve($subscription->stripe_id, [
            'expand' => ['items'],
        ]);

        // Supprimer les items locaux
        $subscription->items()->delete();

        // Recréer depuis Stripe
        foreach ($stripeSub->items->data as $item) {
            $subscription->items()->create([
                'stripe_id' => $item->id,
                'stripe_product' => $item->price->product,
                'stripe_price' => $item->price->id,
                'quantity' => $item->quantity,
            ]);
        }
    } catch (\Exception $e) {
        Log::warning('syncSubscriptionItems error', ['error' => $e->getMessage()]);
    }
}
```

```bash
git add -A && git commit -m "fix(M1): updateArtistQuantity — ligne EXTRA séparée au lieu d'incrémenter quantité STUDIO"
```

---

## FIX M2 — CORRIGER L'ABONNEMENT STRIPE ACTUEL

### Le problème actuel
L'abonnement `sub_1T8LyXIsCWRG6bTQiy0Xp38J` a le prix STUDIO × 2 au lieu de STUDIO × 1 + EXTRA × 1.

### Commande de correction

```php
// app/Console/Commands/FixStudioSubscriptionItems.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Studio;

class FixStudioSubscriptionItems extends Command
{
    protected $signature = 'inkpik:fix-studio-items {--studio-id= : ID du studio à corriger}';
    protected $description = 'Corriger les items d\'abonnement studio (STUDIO×1 + EXTRA×N au lieu de STUDIO×N)';

    public function handle()
    {
        $studioId = $this->option('studio-id');
        $studio = $studioId ? Studio::find($studioId) : Studio::first();

        if (!$studio) {
            $this->error('Studio non trouvé.');
            return 1;
        }

        $user = $studio->user;
        if (!$user || !$user->subscribed('default')) {
            $this->error('Pas d\'abonnement actif.');
            return 1;
        }

        $sub = $user->subscription('default');
        $stripe = new \Stripe\StripeClient(config('cashier.secret'));

        $studioPriceId = config('inkpik.pricing.studio.stripe_price_id');
        $extraPriceId = config('inkpik.pricing.studio.stripe_price_id_extra');

        $this->info("Studio #{$studio->id} — {$studio->name}");
        $this->info("Sub: {$sub->stripe_id}");
        $this->info("STUDIO price: {$studioPriceId}");
        $this->info("EXTRA price: {$extraPriceId}");

        // Récupérer l'abonnement Stripe
        $stripeSub = $stripe->subscriptions->retrieve($sub->stripe_id, [
            'expand' => ['items'],
        ]);

        $this->newLine();
        $this->info('--- Items actuels ---');
        foreach ($stripeSub->items->data as $item) {
            $this->line("  {$item->id}: price={$item->price->id} qty={$item->quantity}");
        }

        // Compter les artistes
        $totalArtists = $studio->tattooers()->count() + $studio->piercers()->count();
        $extraNeeded = max(0, $totalArtists - 1);

        $this->newLine();
        $this->info("Artistes: {$totalArtists} (1 inclus, {$extraNeeded} supplémentaire(s))");

        // Chercher l'item STUDIO
        $studioItem = collect($stripeSub->items->data)->firstWhere('price.id', $studioPriceId);

        if (!$studioItem) {
            $this->error("Item STUDIO non trouvé dans l'abonnement !");
            return 1;
        }

        if ($studioItem->quantity === 1) {
            $this->info("Item STUDIO déjà à quantité 1. OK.");
        } else {
            $this->warn("Item STUDIO à quantité {$studioItem->quantity} → correction vers 1");

            if (!$this->confirm('Corriger ?')) return 0;

            // Remettre STUDIO à quantité 1
            $stripe->subscriptionItems->update($studioItem->id, [
                'quantity' => 1,
            ]);
            $this->info("✅ STUDIO remis à quantité 1");
        }

        // Gérer l'item EXTRA
        $extraItem = collect($stripeSub->items->data)->firstWhere('price.id', $extraPriceId);

        if ($extraNeeded > 0) {
            if ($extraItem) {
                if ($extraItem->quantity !== $extraNeeded) {
                    $stripe->subscriptionItems->update($extraItem->id, [
                        'quantity' => $extraNeeded,
                    ]);
                    $this->info("✅ EXTRA mis à jour: quantité {$extraNeeded}");
                } else {
                    $this->info("EXTRA déjà correct: quantité {$extraNeeded}");
                }
            } else {
                $stripe->subscriptionItems->create([
                    'subscription' => $sub->stripe_id,
                    'price' => $extraPriceId,
                    'quantity' => $extraNeeded,
                ]);
                $this->info("✅ EXTRA ajouté: quantité {$extraNeeded} à {$extraPriceId}");
            }
        } else {
            if ($extraItem) {
                $stripe->subscriptionItems->delete($extraItem->id, [
                    'proration_behavior' => 'create_prorations',
                ]);
                $this->info("✅ EXTRA supprimé (aucun artiste supplémentaire)");
            } else {
                $this->info("Pas d'EXTRA nécessaire. OK.");
            }
        }

        // Synchroniser Cashier
        $sub->items()->delete();
        $freshSub = $stripe->subscriptions->retrieve($sub->stripe_id, ['expand' => ['items']]);
        foreach ($freshSub->items->data as $item) {
            $sub->items()->create([
                'stripe_id' => $item->id,
                'stripe_product' => $item->price->product,
                'stripe_price' => $item->price->id,
                'quantity' => $item->quantity,
            ]);
        }

        $this->newLine();
        $this->info('--- Items après correction ---');
        foreach ($freshSub->items->data as $item) {
            $this->line("  {$item->id}: price={$item->price->id} qty={$item->quantity}");
        }

        $expectedTotal = 59.99 + ($extraNeeded * 24.99);
        $this->newLine();
        $this->info("💰 Total attendu: {$expectedTotal}€/mois");

        return 0;
    }
}
```

Après déploiement, lancer :
```bash
php artisan inkpik:fix-studio-items --studio-id=2
```

```bash
git add -A && git commit -m "fix(M2): commande fix-studio-items — corrige STUDIO×N vers STUDIO×1 + EXTRA×(N-1)"
```

---

## FIX M3 — AFFICHAGE BILLING STUDIO (DÉTAIL ITEMS)

### Dans la vue billing studio, afficher le détail de la facturation

```blade
{{-- Dans resources/views/studio/billing.blade.php — section abonnement actif --}}

{{-- Détail tarification --}}
@php
    $totalArtists = ($studio->tattooers()->count() ?? 0) + ($studio->piercers()->count() ?? 0);
    $includedArtists = config('inkpik.pricing.studio.included_artists', 1);
    $extraArtists = max(0, $totalArtists - $includedArtists);
    $basePrice = \App\Enums\SubscriptionPlan::STUDIO->price();
    $extraPrice = \App\Enums\SubscriptionPlan::STUDIO->pricePerExtraArtist();
    $totalMonthly = $basePrice + ($extraArtists * $extraPrice);
@endphp

<div class="mt-4 p-4 bg-noir-profond/30 rounded-lg">
    <h4 class="text-xs text-titane uppercase tracking-wider mb-3">Détail de la facturation</h4>
    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-titane">Plan Studio ({{ $includedArtists }} artiste inclus)</span>
            <span class="text-ivoire-text">{{ number_format($basePrice, 2, ',', '') }}€</span>
        </div>
        @if ($extraArtists > 0)
            <div class="flex justify-between">
                <span class="text-titane">{{ $extraArtists }} artiste{{ $extraArtists > 1 ? 's' : '' }} supplémentaire{{ $extraArtists > 1 ? 's' : '' }} × {{ number_format($extraPrice, 2, ',', '') }}€</span>
                <span class="text-ivoire-text">{{ number_format($extraArtists * $extraPrice, 2, ',', '') }}€</span>
            </div>
        @endif
        <div class="flex justify-between pt-2 border-t border-titane/10 font-semibold">
            <span class="text-ivoire-text">Total mensuel</span>
            <span class="text-beige-peau">{{ number_format($totalMonthly, 2, ',', '') }}€/mois</span>
        </div>
    </div>
</div>
```

```bash
git add -A && git commit -m "fix(M3): billing studio affiche le détail STUDIO + EXTRA artistes"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT M ==="

# V1. Méthode corrigée
grep -c "extraPriceId\|STUDIO_EXTRA\|stripe_price_id_extra" app/Services/StudioBillingService.php
echo "Référence EXTRA dans service (doit être > 0)"

# V2. Quantité STUDIO toujours 1
grep -c "quantity.*1\|'quantity' => 1" app/Services/StudioBillingService.php
echo "STUDIO qty=1 forcé (doit être > 0)"

# V3. Commande fix
php artisan list 2>&1 | grep "fix-studio-items"

# V4. Config EXTRA
php artisan tinker --execute="
  echo 'STUDIO_EXTRA price: ' . config('inkpik.pricing.studio.stripe_price_id_extra') . PHP_EOL;
  echo 'Included artists: ' . config('inkpik.pricing.studio.included_artists', 1) . PHP_EOL;
"

# V5. Vue billing détail
grep -c "extraArtists\|artiste.*supplémentaire" resources/views/studio/billing.blade.php
echo "Détail EXTRA dans vue billing (doit être > 0)"

echo "=== PROMPT M TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT**
2. **STUDIO × 1 TOUJOURS** — le prix de base ne change jamais de quantité
3. **EXTRA = ligne séparée** avec le prix `STRIPE_PRICE_ID_STUDIO_EXTRA`
4. **Quantité EXTRA = total artistes - 1** (1 artiste inclus dans le plan)
5. **Quand on supprime un artiste** → décrémenter la quantité EXTRA (ou supprimer la ligne si 0)
6. **syncSubscriptionItems** après chaque modification Stripe → garder Cashier en sync
7. **Corriger l'abonnement actuel** avec `php artisan inkpik:fix-studio-items --studio-id=2`
8. **Prorations** : Stripe gère automatiquement les prorata (create_prorations par défaut)
9. **Commit après chaque fix** (3 commits)
