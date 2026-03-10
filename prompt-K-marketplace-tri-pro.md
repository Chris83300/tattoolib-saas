# 🏆 PROMPT K — TRI MARKETPLACE : PRO EN PREMIER (WELCOME + MARKETPLACE)
# Pour Claude Code — Fix du tri artistes sur welcome.blade et marketplace/index.blade
# Commit après chaque fix

## CONTEXTE

Le tri des artistes dans la marketplace et la page d'accueil ne fonctionne pas correctement :
- Les artistes PRO (abonnés payants) ne sont PAS mis en premier
- Les pierceurs apparaissent avant les tatoueurs de façon incohérente
- L'ordre semble aléatoire

**Cause identifiée** : Le tri backend (CacheService/MarketplaceSearchService) est correct, mais le tri JS côté front ÉCRASE le tri backend lors du merge tattooers + piercers.

### Ordre de tri souhaité (priorité décroissante)

| Priorité | Type | Condition |
|----------|------|-----------|
| 1 | **PRO payant** | Artiste indépendant avec abonnement PRO actif (`is_subscribed = true` ET `studio_id IS NULL`) |
| 2 | **PRO via Studio** | Artiste rattaché à un studio avec abonnement actif |
| 3 | **STARTER payant** | Artiste avec abonnement STARTER actif |
| 4 | **Trial actif** | Artiste en période d'essai (`trial_ends_at > now()`) |
| 5 | **Bloqué** | Ne doit PAS apparaître (filtré par `marketplaceVisible()`) |

Au sein de chaque tier : rotation aléatoire hebdomadaire (seed = `startOfWeek()->timestamp`).

Cet ordre s'applique PARTOUT : welcome.blade, marketplace/index.blade, API /api/marketplace/featured, API /api/marketplace/search.

Stack : Laravel 12, Livewire 3.7, Alpine.js, TailwindCSS v4.

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT PROMPT K ==="

# ── WELCOME ──
echo "--- WELCOME ---"

# K0a. Welcome blade — comment les artistes sont affichés
grep -n "featured\|artistes\|artists\|mis.en.avant\|tattooer\|piercer" resources/views/welcome.blade.php | head -20

# K0b. Données envoyées au welcome
grep -B 5 -A 20 "function.*welcome\|function.*home\|function.*index" app/Http/Controllers/HomeController.php app/Http/Controllers/WelcomeController.php 2>/dev/null | head -40

# K0c. Appels API depuis welcome (JS/fetch)
grep -n "fetch\|axios\|api/marketplace\|featured\|/api/" resources/views/welcome.blade.php | head -10


# ── MARKETPLACE ──
echo "--- MARKETPLACE ---"

# K0d. Page marketplace — JS qui fait le tri
grep -n "sort\|order\|tri\|rank\|tier\|pro_tier\|is_pro\|is_subscribed" resources/views/marketplace/index.blade.php | head -20

# K0e. Comment les artistes sont récupérés dans marketplace
grep -n "fetch\|axios\|api/marketplace\|wire:model\|livewire\|artists\|tattooer\|piercer\|concat\|merge\|push\|spread" resources/views/marketplace/index.blade.php | head -25

# K0f. Le composant Alpine/JS qui gère le listing
grep -B 3 -A 30 "x-data\|function.*marketplace\|function.*search\|function.*filter\|artists.*=\|results.*=" resources/views/marketplace/index.blade.php | head -60

# K0g. Y a-t-il un merge JS tattooers + piercers ?
grep -n "concat\|merge\|\[\.\.\.tattoo\|\[\.\.\.pierc\|\.push\|spread" resources/views/marketplace/index.blade.php | head -10


# ── API MARKETPLACE ──
echo "--- API ---"

# K0h. Controller API marketplace
find app/Http/Controllers/Api -name "*Marketplace*" | head -5
grep -n "function " app/Http/Controllers/Api/MarketplaceController.php 2>/dev/null | head -15

# K0i. Méthode featured API
grep -B 5 -A 30 "function featured\|function getFeatured" app/Http/Controllers/Api/MarketplaceController.php 2>/dev/null | head -40

# K0j. Méthode search API
grep -B 5 -A 30 "function search\b\|function index\b" app/Http/Controllers/Api/MarketplaceController.php 2>/dev/null | head -40

# K0k. MarketplaceSearchService
cat app/Services/MarketplaceSearchService.php 2>/dev/null | head -80


# ── CACHE SERVICE ──
echo "--- CACHE ---"

# K0l. CacheService — tri et featured
grep -B 5 -A 30 "function.*getFeatured\|function.*getMarketplace\|function.*getListing\|pro_tier\|sort\|order" app/Services/CacheService.php | head -60

# K0m. Comment pro_tier est calculé
grep -n "pro_tier\|isDirectPaidPro\|is_pro\|is_subscribed.*sort\|sortBy\|orderBy" app/Services/CacheService.php | head -15


# ── SCOPE MARKETPLACE ──
echo "--- SCOPES ---"

# K0n. scopeMarketplaceVisible sur Tattooer
grep -B 3 -A 20 "scopeMarketplaceVisible" app/Models/Tattooer.php | head -30

# K0o. scopeMarketplaceVisible sur Piercer
grep -B 3 -A 20 "scopeMarketplaceVisible" app/Models/Piercer.php | head -30


# ── DONNÉES ──
echo "--- DONNÉES ---"

# K0p. État des artistes en base
php artisan tinker --execute="
  use App\Models\Tattooer;
  use App\Models\Piercer;
  
  echo '=== TATTOOERS ===' . PHP_EOL;
  Tattooer::select('id', 'user_id', 'studio_id', 'is_subscribed', 'is_blocked', 'current_plan', 'trial_ends_at')
    ->get()->each(function(\$t) {
      echo '#' . \$t->id . ' plan=' . (\$t->current_plan ?? '?') . ' sub=' . (\$t->is_subscribed ? 'Y' : 'N') . ' blocked=' . (\$t->is_blocked ? 'Y' : 'N') . ' studio=' . (\$t->studio_id ?? 'null') . ' trial=' . (\$t->trial_ends_at ?? 'null') . PHP_EOL;
    });
    
  echo PHP_EOL . '=== PIERCERS ===' . PHP_EOL;
  Piercer::select('id', 'user_id', 'studio_id', 'is_subscribed', 'is_blocked', 'current_plan', 'trial_ends_at')
    ->get()->each(function(\$p) {
      echo '#' . \$p->id . ' plan=' . (\$p->current_plan ?? '?') . ' sub=' . (\$p->is_subscribed ? 'Y' : 'N') . ' blocked=' . (\$p->is_blocked ? 'Y' : 'N') . ' studio=' . (\$p->studio_id ?? 'null') . ' trial=' . (\$p->trial_ends_at ?? 'null') . PHP_EOL;
    });
"

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX K1 — UNIFIER LE TRI BACKEND (CacheService + API)

### Problème
Le tri backend est fragmenté entre CacheService, MarketplaceSearchService et le controller API. Il faut un calcul de `sort_rank` UNIQUE appliqué partout.

### Créer un helper de tri

```php
// app/Helpers/ArtistSortHelper.php
namespace App\Helpers;

class ArtistSortHelper
{
    /**
     * Calculer le rang de tri d'un artiste pour la marketplace.
     * Plus le rang est ÉLEVÉ, plus l'artiste apparaît en premier (tri DESC).
     *
     * 100 = PRO payant direct
     *  90 = PRO via studio (studio abonné)
     *  50 = STARTER payant
     *  30 = Trial actif
     *   0 = Pas d'accès (ne devrait pas apparaître)
     */
    public static function calculateRank($artisan): int
    {
        // Bloqué = 0 (ne devrait pas passer le scope marketplaceVisible)
        if ($artisan->is_blocked ?? false) return 0;

        // PRO payant direct (pas de studio, abonnement actif)
        if (!$artisan->studio_id && ($artisan->is_subscribed ?? false)) {
            $plan = $artisan->current_plan ?? $artisan->plan ?? 'starter';
            if ($plan === 'pro') return 100;
            if ($plan === 'starter') return 50;
            return 50; // abonné mais plan inconnu → STARTER
        }

        // PRO via studio
        if ($artisan->studio_id) {
            $studio = $artisan->studio;
            if ($studio && ($studio->is_subscribed || $studio->hasActiveSubscription())) {
                return 90;
            }
            // Studio en trial
            if ($studio && $studio->trial_ends_at && $studio->trial_ends_at->isFuture()) {
                return 30;
            }
        }

        // Trial actif (artiste indépendant)
        if ($artisan->trial_ends_at && $artisan->trial_ends_at->isFuture()) {
            return 30;
        }

        return 0;
    }

    /**
     * Trier une collection d'artistes avec le rang + rotation hebdomadaire.
     */
    public static function sortCollection($artists): \Illuminate\Support\Collection
    {
        $weeklySeed = (int) now()->startOfWeek()->timestamp;

        return $artists
            ->map(function ($artist) {
                $artist->sort_rank = self::calculateRank($artist);
                return $artist;
            })
            ->sortByDesc('sort_rank')
            ->groupBy('sort_rank')
            ->map(function ($group) use ($weeklySeed) {
                // Au sein de chaque tier, rotation aléatoire hebdomadaire
                $items = $group->values()->toArray();
                mt_srand($weeklySeed + count($items));
                shuffle($items);
                return collect($items);
            })
            ->flatten(1)
            ->values();
    }

    /**
     * Trier un array (pour les réponses API JSON).
     */
    public static function sortArray(array $artists): array
    {
        $weeklySeed = (int) now()->startOfWeek()->timestamp;

        // Ajouter sort_rank à chaque artiste
        foreach ($artists as &$artist) {
            $rank = 0;
            $isBlocked = $artist['is_blocked'] ?? false;
            $studioId = $artist['studio_id'] ?? null;
            $isSubscribed = $artist['is_subscribed'] ?? false;
            $plan = $artist['current_plan'] ?? $artist['plan'] ?? 'starter';
            $trialEndsAt = $artist['trial_ends_at'] ?? null;

            if ($isBlocked) {
                $rank = 0;
            } elseif (!$studioId && $isSubscribed && $plan === 'pro') {
                $rank = 100;
            } elseif (!$studioId && $isSubscribed) {
                $rank = 50;
            } elseif ($studioId) {
                // Vérifier si studio est abonné (info doit être dans l'array)
                $rank = ($artist['studio_subscribed'] ?? false) ? 90 : 30;
            } elseif ($trialEndsAt && strtotime($trialEndsAt) > time()) {
                $rank = 30;
            }

            $artist['sort_rank'] = $rank;
        }
        unset($artist);

        // Trier par rang DESC
        usort($artists, function ($a, $b) use ($weeklySeed) {
            if ($a['sort_rank'] !== $b['sort_rank']) {
                return $b['sort_rank'] - $a['sort_rank'];
            }
            // Même rang → rotation hebdo
            $hashA = crc32($weeklySeed . ($a['id'] ?? 0) . ($a['type'] ?? ''));
            $hashB = crc32($weeklySeed . ($b['id'] ?? 0) . ($b['type'] ?? ''));
            return $hashA - $hashB;
        });

        return $artists;
    }
}
```

### Appliquer dans CacheService

```bash
grep -n "function.*getFeatured\|function.*getMarketplace\|sortBy\|sort(" app/Services/CacheService.php | head -15
```

Modifier la méthode qui retourne les artistes pour utiliser `ArtistSortHelper::sortCollection()` :

```php
// Dans CacheService, après avoir récupéré et mergé tattooers + piercers :
use App\Helpers\ArtistSortHelper;

// AVANT (probablement un tri basique ou cassé) :
$artists = $tattooers->merge($piercers)->sortByDesc('is_subscribed');

// APRÈS :
$merged = $tattooers->merge($piercers);
$artists = ArtistSortHelper::sortCollection($merged);
```

### Appliquer dans MarketplaceSearchService et API Controller

```php
// Dans MarketplaceSearchService ou Api\MarketplaceController
// Pour les réponses JSON (API) :

$results = [...]; // array d'artistes
$sorted = ArtistSortHelper::sortArray($results);
return response()->json($sorted);
```

### Ajouter `sort_rank` dans les réponses API

Si les réponses API incluent des artistes en JSON, ajouter le `sort_rank` pour que le front puisse le respecter :

```php
// Dans la transformation des artistes pour l'API
$artistData['sort_rank'] = ArtistSortHelper::calculateRank($artist);
// OU si c'est un array :
$artistData['sort_rank'] = $artist->sort_rank ?? 0;
```

```bash
git add -A && git commit -m "feat(K1): ArtistSortHelper — tri unifié PRO > Studio > STARTER > Trial avec rotation hebdo"
```

---

## FIX K2 — SUPPRIMER LE TRI JS QUI ÉCRASE LE BACKEND

### Problème
Le JavaScript dans `marketplace/index.blade.php` fait un tri ou un merge qui écrase l'ordre du backend.

### Diagnostic

```bash
# Trouver le code JS qui trie
grep -n "sort\|order\|concat\|merge\|\[\.\.\.t\|\[\.\.\.p\|\.push" resources/views/marketplace/index.blade.php | head -15
grep -n "sort\|order\|concat\|merge" resources/views/welcome.blade.php | head -15
```

### Fix

**Option A** — Si le JS fait un `concat(piercers, tattooers)` séparé :
Remplacer par un seul appel API qui retourne les artistes DÉJÀ triés :

```javascript
// AVANT (2 appels séparés + concat côté JS) :
const tattooers = await fetch('/api/marketplace/search?type=tattooer').then(r => r.json());
const piercers = await fetch('/api/marketplace/search?type=piercer').then(r => r.json());
const artists = [...piercers, ...tattooers]; // ← CASSE LE TRI

// APRÈS (1 seul appel, tri backend) :
const artists = await fetch('/api/marketplace/search').then(r => r.json());
// artists est DÉJÀ trié par sort_rank côté serveur
```

**Option B** — Si le JS doit merger, respecter le `sort_rank` :

```javascript
// APRÈS merge, trier par sort_rank (fourni par le backend) :
const allArtists = [...tattooers, ...piercers];
allArtists.sort((a, b) => (b.sort_rank || 0) - (a.sort_rank || 0));
```

**Option C** — Si le backend retourne tout dans un seul array (via CacheService), le JS ne doit PAS re-trier :

```javascript
// Supprimer tout .sort() côté JS
// Les artistes arrivent déjà dans le bon ordre
```

### Même fix pour welcome.blade.php

```bash
grep -n "fetch\|api\|featured\|artists\|sort\|concat\|merge" resources/views/welcome.blade.php | head -20
```

Si la welcome fait un appel à `/api/marketplace/featured`, s'assurer que :
1. L'API retourne les artistes triés par `sort_rank`
2. Le JS ne re-trie PAS

Si la welcome utilise des données server-side (via le controller), s'assurer que le controller utilise `ArtistSortHelper::sortCollection()` :

```php
// Dans HomeController ou WelcomeController
public function index()
{
    $featured = app(CacheService::class)->getFeaturedArtists();
    // featured est déjà trié par sort_rank grâce au fix K1
    
    return view('welcome', compact('featured'));
}
```

```bash
git add -A && git commit -m "fix(K2): supprimer tri JS qui écrasait le tri backend — sort_rank respecté"
```

---

## FIX K3 — ENRICHIR LES RÉPONSES API AVEC SORT_RANK

### Dans le controller API marketplace

S'assurer que chaque artiste retourné a un champ `sort_rank` :

```php
// Dans Api\MarketplaceController — featured()
public function featured()
{
    $artists = app(CacheService::class)->getFeaturedArtists();
    
    // Si c'est une collection Eloquent, transformer avec sort_rank
    $result = $artists->map(function ($artist) {
        $data = $artist->toArray();
        $data['sort_rank'] = \App\Helpers\ArtistSortHelper::calculateRank($artist);
        $data['type'] = $artist instanceof \App\Models\Tattooer ? 'tattooer' : 'piercer';
        // Ajouter le flag studio_subscribed pour le tri côté client si besoin
        if ($artist->studio_id && $artist->studio) {
            $data['studio_subscribed'] = $artist->studio->is_subscribed ?? false;
            $data['studio_name'] = $artist->studio->name ?? null;
        }
        return $data;
    });

    return response()->json($result);
}
```

### Dans search()

Même logique : ajouter `sort_rank` et utiliser `ArtistSortHelper::sortArray()` sur le résultat final.

```bash
git add -A && git commit -m "feat(K3): API marketplace retourne sort_rank — tri respecté côté front"
```

---

## FIX K4 — AJOUTER BADGE PRO VISUEL SUR LES CARDS

### Pour que l'utilisateur VOIT que les artistes PRO sont en avant

Sur chaque card artiste (welcome + marketplace), afficher un badge si l'artiste est PRO :

```blade
{{-- Dans la card artiste (partial) --}}
@if ($artist->is_subscribed && !$artist->studio_id && ($artist->current_plan ?? '') === 'pro')
    <span class="absolute top-3 right-3 px-2 py-0.5 text-[10px] font-bold bg-beige-peau text-noir-profond rounded-full z-10">
        PRO
    </span>
@elseif ($artist->studio_id)
    <span class="absolute top-3 right-3 px-2 py-0.5 text-[10px] font-bold bg-beige-peau/70 text-noir-profond rounded-full z-10">
        Studio
    </span>
@endif
```

Pour les cards rendues via JS (API), ajouter dans le template JS :

```javascript
// Dans le template de card Alpine/JS
${artist.sort_rank >= 100 ? '<span class="badge-pro">PRO</span>' : ''}
${artist.sort_rank >= 90 && artist.sort_rank < 100 ? '<span class="badge-studio">Studio</span>' : ''}
```

```bash
git add -A && git commit -m "feat(K4): badge PRO/Studio visible sur les cards artistes marketplace"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT K ==="

# V1. Helper
ls app/Helpers/ArtistSortHelper.php && echo "Helper OK"

# V2. CacheService utilise le helper
grep -c "ArtistSortHelper\|sort_rank\|sortCollection" app/Services/CacheService.php
echo "CacheService trié (doit être > 0)"

# V3. API retourne sort_rank
grep -c "sort_rank\|ArtistSortHelper" app/Http/Controllers/Api/MarketplaceController.php 2>/dev/null
echo "API sort_rank (doit être > 0)"

# V4. JS ne re-trie plus (ou trie par sort_rank)
grep -c "\.sort(" resources/views/marketplace/index.blade.php 2>/dev/null
echo "JS sort dans marketplace (devrait être 0 ou 1 basé sur sort_rank)"

# V5. Badge PRO
grep -c "badge.*pro\|PRO\|sort_rank.*100\|is_pro" resources/views/ -r --include="*.blade.php" 2>/dev/null | head -3

# V6. Test tri
php artisan tinker --execute="
  use App\Helpers\ArtistSortHelper;
  use App\Models\Tattooer;
  use App\Models\Piercer;
  
  \$all = Tattooer::marketplaceVisible()->get()
    ->merge(Piercer::marketplaceVisible()->get());
  \$sorted = ArtistSortHelper::sortCollection(\$all);
  
  echo 'Artistes visibles marketplace: ' . \$sorted->count() . PHP_EOL;
  foreach(\$sorted->take(5) as \$a) {
    echo '  rank=' . \$a->sort_rank . ' plan=' . (\$a->current_plan ?? '?') . ' sub=' . (\$a->is_subscribed ? 'Y' : 'N') . ' studio=' . (\$a->studio_id ?? 'null') . ' name=' . (\$a->user?->name ?? '?') . PHP_EOL;
  }
"

# V7. Compilation
php artisan route:clear && php artisan view:clear
echo "OK"

echo "=== PROMPT K TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Le JS est la cause probable, il faut le lire
2. **UN SEUL endroit pour le tri** : `ArtistSortHelper` — utilisé par CacheService, API, et si nécessaire JS
3. **Le JS ne doit PAS re-trier** sauf par `sort_rank` fourni par le backend
4. **Merge tattooers + piercers** côté BACKEND (pas côté JS) pour un tri global cohérent
5. **sort_rank dans la réponse API** — le front peut l'utiliser s'il doit re-trier
6. **Rotation hebdomadaire** : au sein de chaque tier, l'ordre change chaque lundi
7. **Studio PRO ≠ PRO direct** : un artiste studio avec `is_subscribed=true` est PRO seulement si le studio est abonné
8. **Invalider le cache** après les modifications : `app(CacheService::class)->invalidateMarketplace()`
9. **Commit après chaque fix** (4 commits)
