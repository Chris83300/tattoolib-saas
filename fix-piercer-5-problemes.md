# 🔧 FIX PIERCER — 5 problèmes identifiés

Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, MySQL.
Le Piercer utilise le même TattooerController et les mêmes vues que Tattooer via polymorphisme (trait IsArtisan, ArtisanInterface).

## PROBLÈME 1 — Settings : Grille tarifaire par type de piercing

Le pierceur doit pouvoir dans ses settings :
- Définir des types de piercing avec le tarif associé à chaque type
- Ajouter/supprimer des types dynamiquement (Alpine.js x-for)
- Le tout stocké en JSON dans la colonne `pricing_grid` de la table `piercers`

### ÉTAPE 1A — AUDIT

```bash
# Trouver la vue settings
grep -rn "settings" resources/views/tattooer/settings* | head -5
cat resources/views/tattooer/settings.blade.php | head -30

# Vérifier si la section pricing_grid existe déjà
grep -rn "pricing_grid\|grille.*tarif\|tarif" resources/views/tattooer/settings.blade.php

# Vérifier la colonne en BDD
php artisan tinker --execute="
  echo 'pricing_grid: ' . (Schema::hasColumn('piercers', 'pricing_grid') ? 'OUI' : 'NON');
  echo ' | custom_pricing_note: ' . (Schema::hasColumn('piercers', 'custom_pricing_note') ? 'OUI' : 'NON');
"

# Vérifier le cast JSON dans le model
grep -n "pricing_grid\|casts" app/Models/Piercer.php
```

### ÉTAPE 1B — AJOUTER COLONNES SI MANQUANTES

# Si pricing_grid n'existe pas :
php artisan make:migration add_pricing_grid_to_piercers_table --table=piercers
```
```php
// Dans la migration :
$table->json('pricing_grid')->nullable(); // [{type: 'Lobe', price: 25}, ...]
$table->string('custom_pricing_note')->nullable(); // "Sur devis pour cas particuliers"
```

Dans `app/Models/Piercer.php`, ajouter dans `$casts` :
```php
protected $casts = [
    'pricing_grid' => 'array',
    // ... existing casts
];
```
Et dans `$fillable` ajouter : `'pricing_grid', 'custom_pricing_note'`

### ÉTAPE 1C — AJOUTER LA SECTION DANS SETTINGS

Dans `resources/views/tattooer/settings.blade.php`, trouver l'endroit approprié (après la bio ou les spécialités) et ajouter une section conditionnelle :

```blade
@if ($isPiercer ?? false)
{{-- ═══ GRILLE TARIFAIRE PIERCING ═══ --}}
<div class="bg-gris-fonde rounded-xl p-4 md:p-6 mt-6">
    <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-4">💰 Grille tarifaire</h3>
    <p class="text-xs text-titane mb-4">Définissez vos tarifs par type de piercing. Cette grille sera affichée sur votre profil public.</p>
    
    <div x-data="{ 
        pricings: {{ Js::from($artisan->pricing_grid ?? [
            ['type' => 'Lobe', 'price' => ''],
            ['type' => 'Hélix', 'price' => ''],
            ['type' => 'Tragus', 'price' => ''],
            ['type' => 'Conch', 'price' => ''],
            ['type' => 'Septum', 'price' => ''],
            ['type' => 'Nostril', 'price' => ''],
            ['type' => 'Labret', 'price' => ''],
            ['type' => 'Industriel', 'price' => ''],
        ]) }}
    }">
        <div class="space-y-2">
            <template x-for="(item, index) in pricings" :key="index">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-titane w-6 text-center" x-text="(index + 1)"></span>
                    <input type="text" :name="'pricing_grid[' + index + '][type]'" x-model="item.type"
                        placeholder="Type de piercing"
                        class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
                    <div class="flex items-center gap-1">
                        <input type="number" :name="'pricing_grid[' + index + '][price]'" x-model="item.price"
                            placeholder="Prix" step="1" min="0"
                            class="w-20 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau text-center">
                        <span class="text-titane text-sm">€</span>
                    </div>
                    <button type="button" @click="if(pricings.length > 1) pricings.splice(index, 1)" 
                        x-show="pricings.length > 1"
                        class="text-rouge-alerte/60 hover:text-rouge-alerte transition-colors p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
        </div>
        <button type="button" @click="pricings.push({ type: '', price: '' })"
            class="mt-3 text-xs text-beige-peau hover:text-beige-peau/80 font-semibold transition-colors">
            + Ajouter un type de piercing
        </button>
    </div>
    
    <div class="mt-4">
        <label class="text-xs text-titane block mb-1">💬 Note tarifaire (cas particuliers)</label>
        <input type="text" name="custom_pricing_note" 
            value="{{ $artisan->custom_pricing_note ?? '' }}"
            placeholder="Ex : Piercing génital sur devis, bijou premium +15€, tarif étudiant -10%..."
            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
    </div>
</div>
@endif
```

### ÉTAPE 1D — SAUVEGARDER pricing_grid DANS LE CONTROLLER

Trouver la méthode `updateSettings` dans TattooerController :

```bash
grep -n "function updateSettings\|function saveSettings\|function update.*settings\|function store.*settings" app/Http/Controllers/TattooerController.php
```

DANS cette méthode, ajouter la sauvegarde conditionnelle :

```php
// Après la sauvegarde des champs communs, ajouter :
if (auth()->user()->isPiercer()) {
    $pricingGrid = $request->input('pricing_grid', []);
    // Filtrer les entrées vides
    $pricingGrid = array_values(array_filter($pricingGrid, fn($item) => !empty($item['type'])));
    $artisan->pricing_grid = $pricingGrid;
    $artisan->custom_pricing_note = $request->input('custom_pricing_note');
    $artisan->save();
}
```

```bash
git add -A && git commit -m "feat(piercer): grille tarifaire dans settings avec types dynamiques"
```

---

## PROBLÈME 2 — Settings : Années d'expérience, tarif minimum et délai d'attente ne s'enregistrent pas

```bash
# AUDIT — Trouver la méthode de sauvegarde
grep -n "function updateSettings\|function saveSettings" app/Http/Controllers/TattooerController.php

# Voir quels champs sont sauvegardés
grep -A 40 "function updateSettings\|function saveSettings" app/Http/Controllers/TattooerController.php | head -50

# Vérifier les colonnes sur la table piercers
php artisan tinker --execute="
  echo implode(', ', Schema::getColumnListing('piercers'));
"

# Vérifier $fillable du model Piercer
grep -n "fillable" app/Models/Piercer.php
```

Le problème est probablement que la méthode `updateSettings` sauvegarde sur `$artisan` (qui est un Piercer) mais les champs `experience_years`, `min_price`, `waiting_delay` ne sont PAS dans `$fillable` du model Piercer, OU la méthode ne les inclut pas dans l'update.

FIX :
1. Vérifier que les colonnes existent sur la table `piercers` (sinon migration)
2. Les ajouter dans `$fillable` de Piercer.php
3. Vérifier que la méthode updateSettings les inclut dans l'update

```php
// Dans la méthode updateSettings, s'assurer que ces champs sont sauvegardés :
$artisan->update($request->validate([
    'experience_years' => 'nullable|integer|min:0',
    'min_price' => 'nullable|numeric|min:0',
    'waiting_delay' => 'nullable|string|max:255',
    // ... autres champs
]));
```

Si la méthode fait un `$artisan->update()` avec des champs hardcodés spécifiques au Tattooer, il faut s'assurer que les mêmes champs existent aussi dans le model Piercer.

```bash
# Comparer les colonnes des 2 tables
php artisan tinker --execute="
  \$t = Schema::getColumnListing('tattooers');
  \$p = Schema::getColumnListing('piercers');
  \$missing = array_diff(\$t, \$p);
  echo 'Colonnes tattooers absentes de piercers: ' . PHP_EOL;
  foreach(\$missing as \$col) echo '  - ' . \$col . PHP_EOL;
"
```

Si des colonnes manquent dans piercers → créer une migration pour les ajouter.
Ajouter TOUTES les colonnes manquantes en une seule migration.

```bash
git add -A && git commit -m "fix(piercer): colonnes manquantes + fillable pour experience/tarif/délai"
```

---

## PROBLÈME 3 — Portfolio : remplacer "Tattoos" par "Piercings"

```bash
# Trouver les labels "Tattoos" / "Tattoo" dans les vues portfolio
grep -rn "Tattoo\|tattoo" resources/views/tattooer/portfolio* resources/views/livewire/tattooer/portfolio* 2>/dev/null | grep -vi "tattooer\|tatoueur\|controller" | head -20
```

FIX : Dans les vues portfolio, ajouter des conditionnels pour le label :

```blade
{{-- AVANT --}}
<h2>Tattoos</h2>
<p>Ajoutez vos tatouages</p>

{{-- APRÈS --}}
<h2>{{ ($isPiercer ?? false) ? 'Piercings' : 'Tattoos' }}</h2>
<p>{{ ($isPiercer ?? false) ? 'Ajoutez vos réalisations piercing' : 'Ajoutez vos tatouages' }}</p>
```

Chercher et remplacer TOUTES les occurrences de labels "Tattoo/Tattoos" dans les vues portfolio par des conditionnels `$isPiercer`.

Inclure aussi :
- Les catégories de portfolio (si filtre par catégorie)
- Les placeholders des champs de description
- Les boutons d'upload
- Les textes d'aide

```bash
# Vérifier qu'on a tout
grep -rn "Tattoo" resources/views/tattooer/portfolio* 2>/dev/null | grep -vi "tattooer\|controller\|route"
git add -A && git commit -m "fix(piercer): labels portfolio Tattoos → Piercings conditionnel"
```

---

## PROBLÈME 4 — Profil Piercer invisible dans la marketplace

C'est le plus important. Les pierceurs ne s'affichent pas dans la marketplace.

```bash
# AUDIT — Trouver le controller marketplace
grep -rn "marketplace\|MarketplaceController\|artisan.*search\|search.*artist" app/Http/Controllers/ routes/web.php | head -15

# Trouver la query qui charge les artistes
grep -rn "Tattooer::where\|Tattooer::query\|Tattooer::with\|tattooers" app/Http/Controllers/ app/Livewire/ --include="*.php" | grep -vi "model\|migration\|test" | head -15

# Trouver la vue marketplace
find resources/views -name "*marketplace*" -type f
```

Le problème : la query marketplace fait `Tattooer::where(...)` et ne cherche JAMAIS dans la table `piercers`.

FIX : Dans le controller ou composant Livewire qui charge la marketplace :

```php
// AVANT (ne montre que les tattooers)
$artists = Tattooer::with('user', 'media')->where('is_active', true)->get();

// APRÈS (montre tattooers + pierceurs)
$tattooers = Tattooer::with('user', 'media')
    ->whereHas('user', fn($q) => $q->where('is_active', true))
    ->get()
    ->map(fn($a) => ['artisan' => $a, 'type' => 'tattooer']);

$piercers = Piercer::with('user', 'media')
    ->whereHas('user', fn($q) => $q->where('is_active', true))
    ->get()
    ->map(fn($a) => ['artisan' => $a, 'type' => 'piercer']);

$artists = $tattooers->merge($piercers)->shuffle(); // ou sortByDesc('created_at')
```

OU si la query utilise un champ `is_active` sur le model artisan :
```php
$tattooers = Tattooer::with('user', 'media')->get();
$piercers = Piercer::with('user', 'media')->get();
$artists = $tattooers->merge($piercers);
```

ADAPTER selon la query exacte trouvée par l'audit. Le principe : merger les 2 collections.

### Dans la vue marketplace, ajouter un filtre par type :

```blade
{{-- Filtres en haut --}}
<div x-data="{ filter: 'all' }" class="mb-6">
    <div class="flex flex-wrap gap-2">
        <button @click="filter = 'all'" 
            :class="filter === 'all' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane'"
            class="px-4 py-2 rounded-full text-sm font-semibold transition-colors">
            Tous
        </button>
        <button @click="filter = 'tattooer'"
            :class="filter === 'tattooer' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane'"
            class="px-4 py-2 rounded-full text-sm font-semibold transition-colors">
            🎨 Tatoueurs
        </button>
        <button @click="filter = 'piercer'"
            :class="filter === 'piercer' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane'"
            class="px-4 py-2 rounded-full text-sm font-semibold transition-colors">
            💎 Pierceurs
        </button>
    </div>
    
    {{-- Cards artistes avec filtre Alpine --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
        @foreach ($artists as $item)
            <div x-show="filter === 'all' || filter === '{{ $item['type'] }}'" x-transition>
                {{-- Card artiste existante --}}
                {{-- Ajouter un badge type --}}
                <span class="absolute top-2 right-2 px-2 py-1 rounded-full text-xs font-bold
                    {{ $item['type'] === 'piercer' ? 'bg-violet-500/20 text-violet-400' : 'bg-beige-peau/20 text-beige-peau' }}">
                    {{ $item['type'] === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}
                </span>
            </div>
        @endforeach
    </div>
</div>
```

ATTENTION : adapter le code ci-dessus à la structure EXACTE de la vue marketplace existante. Ne pas casser le layout. Le code est indicatif — adapter les classes, les variables, le format des données.

Si la vue utilise directement `$artist` au lieu de `$item['artisan']`, adapter.

```bash
git add -A && git commit -m "feat(marketplace): pierceurs visibles + filtre type tattooer/pierceur"
```

---

## PROBLÈME 5 — Profil Piercer invisible dans welcome.blade.php

```bash
# AUDIT
grep -rn "Tattooer\|tattooer" resources/views/welcome.blade.php | head -20
cat resources/views/welcome.blade.php | head -100
```

Même logique que la marketplace : la page d'accueil ne charge que les Tattooers.

FIX : Dans le controller qui retourne welcome (probablement dans routes/web.php ou un HomeController) :

```bash
grep -rn "welcome\|home\|landing" routes/web.php | head -10
```

Ajouter les pierceurs à la query :

```php
// AVANT
$featuredArtists = Tattooer::with('user', 'media')->latest()->take(6)->get();

// APRÈS  
$featuredTattooers = Tattooer::with('user', 'media')->latest()->take(4)->get();
$featuredPiercers = Piercer::with('user', 'media')->latest()->take(2)->get();
$featuredArtists = $featuredTattooers->merge($featuredPiercers)->shuffle();
```

Dans `welcome.blade.php`, ajouter le badge type sur chaque carte artiste (même pattern que marketplace).

Et si la page mentionne "Trouvez votre tatoueur" ou texte similaire → adapter :
```blade
{{-- AVANT --}}
<h1>Trouvez votre tatoueur</h1>

{{-- APRÈS --}}
<h1>Trouvez votre artiste</h1>
<p>Tatoueurs et pierceurs professionnels près de chez vous</p>
```

```bash
git add -A && git commit -m "feat(welcome): pierceurs visibles sur la page d'accueil"
```

---

## VÉRIFICATION FINALE

```bash
# 1. Settings enregistre la grille tarifaire ?
php artisan tinker --execute="
  \$p = App\Models\Piercer::first();
  echo 'pricing_grid: ' . json_encode(\$p->pricing_grid);
  echo PHP_EOL . 'experience_years: ' . \$p->experience_years;
  echo PHP_EOL . 'min_price: ' . \$p->min_price;
  echo PHP_EOL . 'fillable: ' . implode(', ', \$p->getFillable());
"

# 2. Marketplace inclut les pierceurs ?
php artisan tinker --execute="
  echo 'Tattooers: ' . App\Models\Tattooer::count();
  echo ' | Piercers: ' . App\Models\Piercer::count();
"

# 3. Plus de "Tattoo" hardcodé dans portfolio pierceur ?
grep -rn "\"Tattoo\|'Tattoo\|>Tattoo" resources/views/tattooer/portfolio* 2>/dev/null | grep -vi "tattooer\|controller\|route"

# 4. Route:list OK ?
php artisan route:list 2>&1 | head -3

echo "=== 5 FIX PIERCER TERMINÉS ==="
```
