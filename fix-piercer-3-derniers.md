# 🔧 FIX PIERCER — 3 problèmes restants

## PROBLÈME 1 — Grille tarifaire : pas de PRIX à côté des types, pas d'ajout dynamique

Actuellement les settings pierceur affichent juste les types de piercing (Lobe, Hélix, Tragus...) mais PAS de champ prix à côté de chaque type, et PAS de possibilité d'ajouter/supprimer des types.

### AUDIT

```bash
# Voir exactement ce qui est affiché actuellement
grep -n "piercing_types\|pricing_grid\|types.*piercing\|tarif\|price\|prix" resources/views/tattooer/settings.blade.php | head -20

# Voir la section complète
grep -B 2 -A 30 "Types de piercing\|piercing_types\|piercingTypes" resources/views/tattooer/settings.blade.php
```

### FIX

Le problème : il y a probablement une section "Types de piercing pratiqués" avec des checkboxes ou tags statiques, MAIS PAS la grille tarifaire avec les prix. Il faut REMPLACER cette section par un tableau dynamique Alpine.js type + prix.

TROUVER la section "Types de piercing pratiqués" dans settings.blade.php et la REMPLACER ENTIÈREMENT par :

```blade
@if ($isPiercer ?? false)
{{-- ═══ GRILLE TARIFAIRE PIERCING ═══ --}}
<div class="bg-gris-fonde rounded-xl p-4 md:p-6 mt-6">
    <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-2">💰 Types de piercing & Tarifs</h3>
    <p class="text-xs text-titane mb-4">Chaque type avec son tarif sera affiché sur votre profil public.</p>
    
    <div x-data="{
        pricings: {{ Js::from($artisan->pricing_grid && count($artisan->pricing_grid) > 0 ? $artisan->pricing_grid : [
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
        {{-- En-tête tableau --}}
        <div class="hidden sm:flex items-center gap-2 mb-2 px-1">
            <span class="w-6"></span>
            <span class="flex-1 text-xs text-titane uppercase tracking-wider">Type de piercing</span>
            <span class="w-28 text-xs text-titane uppercase tracking-wider text-center">Prix</span>
            <span class="w-8"></span>
        </div>

        {{-- Lignes --}}
        <div class="space-y-2">
            <template x-for="(item, index) in pricings" :key="index">
                <div class="flex items-center gap-2 group">
                    <span class="text-xs text-titane/50 w-6 text-center font-mono" x-text="(index + 1)"></span>
                    <input type="text" 
                        :name="'pricing_grid[' + index + '][type]'" 
                        x-model="item.type"
                        placeholder="Ex : Daith, Rook, Smiley..."
                        class="flex-1 px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau transition-colors">
                    <div class="flex items-center gap-1 w-28">
                        <input type="number" 
                            :name="'pricing_grid[' + index + '][price]'" 
                            x-model="item.price"
                            placeholder="0" step="1" min="0"
                            class="w-20 px-2 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau text-center transition-colors">
                        <span class="text-titane text-sm font-semibold">€</span>
                    </div>
                    <button type="button" 
                        @click="if(pricings.length > 1) pricings.splice(index, 1)"
                        x-show="pricings.length > 1"
                        class="w-8 h-8 flex items-center justify-center text-rouge-alerte/40 hover:text-rouge-alerte hover:bg-rouge-alerte/10 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </template>
        </div>

        <button type="button" 
            @click="pricings.push({ type: '', price: '' })"
            class="mt-3 flex items-center gap-1 text-sm text-beige-peau hover:text-beige-peau/80 font-semibold transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter un type de piercing
        </button>

        {{-- Note tarifaire pour cas particuliers --}}
        <div class="mt-4 pt-4 border-t border-titane/10">
            <label class="text-xs text-titane block mb-1">💬 Note (cas particuliers, devis, etc.)</label>
            <input type="text" name="custom_pricing_note" 
                value="{{ $artisan->custom_pricing_note ?? '' }}"
                placeholder="Ex : Piercing génital sur devis, bijou premium +15€, tarif étudiant -10%..."
                class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau transition-colors">
        </div>
    </div>
</div>
@endif
```

IMPORTANT : SUPPRIMER l'ancienne section "Types de piercing pratiqués" qui affiche juste des tags sans prix. Elle doit être REMPLACÉE, pas coexister.

### VÉRIFIER que le controller sauvegarde bien pricing_grid

```bash
grep -A 20 "pricing_grid" app/Http/Controllers/TattooerController.php
```

La sauvegarde doit faire :
```php
if (auth()->user()->isPiercer()) {
    $pricingGrid = $request->input('pricing_grid', []);
    // Filtrer les lignes vides (pas de type)
    $pricingGrid = array_values(array_filter($pricingGrid, fn($item) => !empty(trim($item['type'] ?? ''))));
    // Convertir les prix en nombres
    foreach ($pricingGrid as &$item) {
        $item['price'] = is_numeric($item['price'] ?? '') ? (float)$item['price'] : null;
    }
    $artisan->pricing_grid = $pricingGrid;
    $artisan->custom_pricing_note = $request->input('custom_pricing_note');
    $artisan->save();
}
```

```bash
git add -A && git commit -m "fix(piercer): grille tarifaire complète type+prix dynamique dans settings"
```

---

## PROBLÈME 2 — Profil public : expérience, prix min et délai non visibles

Les champs `years_of_experience`, `minimum_price`, `wait_time_weeks_min/max` sont en BDD mais pas affichés sur le profil public marketplace.

### AUDIT

```bash
# Trouver la vue profil public
find resources/views -name "show*" -path "*marketplace*"
# Chercher où les infos artiste sont affichées
grep -n "experience\|expérience\|years\|min_price\|minimum\|délai\|wait_time\|attente" resources/views/marketplace/show.blade.php
```

### FIX

Dans `resources/views/marketplace/show.blade.php`, trouver la section avec les infos de l'artiste (bio, localisation, etc.) et AJOUTER :

```blade
{{-- Infos pratiques --}}
<div class="flex flex-wrap gap-3 mt-4">
    @if ($artist->years_of_experience)
        <div class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2">
            <span class="text-sm">🏅</span>
            <span class="text-sm text-ivoire-text">{{ $artist->years_of_experience }} an{{ $artist->years_of_experience > 1 ? 's' : '' }} d'expérience</span>
        </div>
    @endif
    
    @if ($artist->minimum_price)
        <div class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2">
            <span class="text-sm">💰</span>
            <span class="text-sm text-ivoire-text">À partir de {{ number_format($artist->minimum_price, 0) }}€</span>
        </div>
    @endif
    
    @if ($artist->wait_time_weeks_min || $artist->wait_time_weeks_max)
        <div class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2">
            <span class="text-sm">📅</span>
            <span class="text-sm text-ivoire-text">
                Délai : 
                @if ($artist->wait_time_weeks_min && $artist->wait_time_weeks_max)
                    {{ $artist->wait_time_weeks_min }}-{{ $artist->wait_time_weeks_max }} semaines
                @elseif ($artist->wait_time_weeks_min)
                    {{ $artist->wait_time_weeks_min }}+ semaines
                @else
                    ~{{ $artist->wait_time_weeks_max }} semaines
                @endif
            </span>
        </div>
    @endif
</div>

{{-- Grille tarifaire (pierceur uniquement) --}}
@if ($artist instanceof \App\Models\Piercer && !empty($artist->pricing_grid))
    <section class="mt-6">
        <h2 class="text-lg font-bold text-ivoire-text mb-3">💰 Tarifs</h2>
        
        {{-- Desktop : tableau --}}
        <div class="hidden sm:block">
            <div class="bg-gris-fonde rounded-xl overflow-hidden">
                <div class="grid grid-cols-2 bg-noir-profond/50 px-4 py-2">
                    <span class="text-xs text-titane uppercase tracking-wider">Type de piercing</span>
                    <span class="text-xs text-titane uppercase tracking-wider text-right">Tarif</span>
                </div>
                @foreach ($artist->pricing_grid as $pricing)
                    @if (!empty($pricing['type']))
                        <div class="grid grid-cols-2 px-4 py-3 border-t border-titane/10 hover:bg-noir-profond/20 transition-colors">
                            <span class="text-sm text-ivoire-text">{{ $pricing['type'] }}</span>
                            <span class="text-sm font-semibold text-beige-peau text-right">
                                {{ !empty($pricing['price']) ? number_format($pricing['price'], 0) . '€' : 'Sur devis' }}
                            </span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Mobile : accordéon / dépliant --}}
        <div class="sm:hidden" x-data="{ open: false }">
            <button @click="open = !open" 
                class="w-full flex items-center justify-between bg-gris-fonde rounded-xl px-4 py-3">
                <span class="text-sm font-semibold text-ivoire-text">
                    {{ count($artist->pricing_grid) }} type{{ count($artist->pricing_grid) > 1 ? 's' : '' }} de piercing
                </span>
                <svg class="w-5 h-5 text-titane transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-collapse class="mt-1 bg-gris-fonde rounded-xl overflow-hidden">
                @foreach ($artist->pricing_grid as $pricing)
                    @if (!empty($pricing['type']))
                        <div class="flex items-center justify-between px-4 py-3 border-t border-titane/10 first:border-t-0">
                            <span class="text-sm text-ivoire-text">{{ $pricing['type'] }}</span>
                            <span class="text-sm font-bold text-beige-peau">
                                {{ !empty($pricing['price']) ? number_format($pricing['price'], 0) . '€' : 'Sur devis' }}
                            </span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        @if ($artist->custom_pricing_note)
            <p class="text-xs text-titane mt-2 italic">💬 {{ $artist->custom_pricing_note }}</p>
        @endif
    </section>
@endif
```

IMPORTANT : Ces infos (expérience, prix min, délai) doivent aussi s'afficher pour les TATOUEURS, pas seulement les pierceurs. La grille tarifaire est spécifique pierceur, mais les 3 badges sont universels.

```bash
git add -A && git commit -m "feat(marketplace): afficher expérience/prix/délai + grille tarifaire pierceur sur profil public"
```

---

## PROBLÈME 3 — Marketplace et welcome : mêmes images pour tous les artistes

Les cartes artistes dans marketplace et welcome affichent toutes la même image (probablement un placeholder ou l'image du premier artiste).

### AUDIT

```bash
# Trouver comment l'image est affichée dans les cartes marketplace
grep -n "avatar\|photo\|image\|getFirstMediaUrl\|getMedia\|profile.*img\|src=" resources/views/marketplace/index.blade.php resources/views/marketplace/search.blade.php 2>/dev/null | head -20

# Vérifier dans welcome
grep -n "avatar\|photo\|image\|getFirstMediaUrl\|getMedia\|profile.*img\|src=" resources/views/welcome.blade.php | head -20

# Vérifier comment le MarketplaceSearchService retourne les données
grep -n "avatar\|photo\|image\|media\|getFirstMedia" app/Services/MarketplaceSearchService.php | head -10
```

### DIAGNOSTIC PROBABLE

Le problème est soit :
A) La query merger tattooers + piercers perd la relation `media` (Spatie) au moment du merge
B) L'image est récupérée via une relation qui ne fonctionne pas pour Piercer (ex: `$artist->tattooer->getFirstMediaUrl()` au lieu de `$artist->getFirstMediaUrl()`)
C) Le merge crée un format de données différent et la vue accède mal à l'image
D) Les pierceurs n'ont pas d'image uploadée et le fallback est la même image par défaut

### FIX

Vérifier que le merge charge bien les media :
```php
$tattooers = Tattooer::with(['user', 'media'])->get();
$piercers = Piercer::with(['user', 'media'])->get();
```

Dans la vue, l'image doit être récupérée de manière POLYMORPHIQUE :
```blade
{{-- L'artiste (Tattooer ou Piercer) doit utiliser Spatie HasMedia --}}
@php
    $artisan = $item['artisan'] ?? $item; // selon le format
    $avatarUrl = null;
    
    // Avatar : d'abord sur le profil artisan, sinon sur le user
    if (method_exists($artisan, 'getFirstMediaUrl')) {
        $avatarUrl = $artisan->getFirstMediaUrl('avatar') 
            ?: $artisan->getFirstMediaUrl('profile')
            ?: $artisan->getFirstMediaUrl('portfolio');
    }
    if (!$avatarUrl && $artisan->user) {
        $avatarUrl = $artisan->user->getFirstMediaUrl('avatar')
            ?: $artisan->user->getFirstMediaUrl('profile');
    }
    // Fallback
    if (!$avatarUrl) {
        $avatarUrl = $artisan->user?->profile_photo_url 
            ?? asset('images/default-avatar.png');
    }
@endphp

<img src="{{ $avatarUrl }}" alt="{{ $artisan->user?->name ?? 'Artiste' }}" class="w-full h-48 object-cover rounded-t-xl">
```

ATTENTION : Adapter ce code au format EXACT de la vue existante. Le principe : chaque carte doit utiliser l'image de SON artiste, pas une image partagée.

Vérifier aussi que le model Piercer a bien le trait HasMedia de Spatie et enregistre les media collections :

```bash
grep -n "HasMedia\|InteractsWithMedia\|registerMediaCollections" app/Models/Piercer.php
```

Si `registerMediaCollections` n'existe pas dans Piercer → copier celle de Tattooer :

```php
public function registerMediaCollections(): void
{
    $this->addMediaCollection('avatar')->singleFile();
    $this->addMediaCollection('portfolio');
    $this->addMediaCollection('profile')->singleFile();
    // Copier exactement les mêmes collections que Tattooer
}
```

```bash
git add -A && git commit -m "fix(marketplace): images de profil uniques par artiste + media collections Piercer"
```

---

## VÉRIFICATION FINALE

```bash
# 1. pricing_grid sauvegardé ?
php artisan tinker --execute="
  \$p = App\Models\Piercer::first();
  echo 'pricing_grid type: ' . gettype(\$p->pricing_grid);
  echo PHP_EOL . 'pricing_grid: ' . json_encode(\$p->pricing_grid);
  echo PHP_EOL . 'custom_pricing_note: ' . \$p->custom_pricing_note;
"

# 2. Profile public a les infos ?
php artisan tinker --execute="
  \$p = App\Models\Piercer::first();
  echo 'experience: ' . \$p->years_of_experience;
  echo ' | min_price: ' . \$p->minimum_price;
  echo ' | wait_min: ' . \$p->wait_time_weeks_min;
  echo ' | wait_max: ' . \$p->wait_time_weeks_max;
"

# 3. Media collections Piercer ?
php artisan tinker --execute="
  \$p = App\Models\Piercer::first();
  echo 'HasMedia: ' . (\$p instanceof Spatie\MediaLibrary\HasMedia ? 'OUI' : 'NON');
  echo ' | Media count: ' . \$p->getMedia()->count();
  echo ' | Collections: ' . \$p->getRegisteredMediaCollections()->pluck('name')->implode(', ');
"

echo "=== 3 FIX TERMINÉS ==="
```
