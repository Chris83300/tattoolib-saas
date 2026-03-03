# 🛒 PROMPT B — COOKIE CONSENT + MARKETPLACE
# Pour Claude Code — Modale cookies, PRO en avant, recherche temps réel, cards studio
# Commit après chaque fix

## CONTEXTE

4 problèmes liés à l'expérience publique du SaaS Ink&Pik.
Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js.

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT PROMPT B ==="

# ── B1 : COOKIE CONSENT ──
echo "--- COOKIES ---"

# B1a. Modale cookies existante ?
grep -rn "cookie\|consent\|gdpr\|rgpd\|banner" resources/views/ --include="*.blade.php" -l | head -10

# B1b. Cookie_consent package ?
grep -n "cookie.consent\|spatie.*cookie\|cookie-consent" composer.json | head -3

# B1c. Page politique cookies (créée en P1.1)
ls resources/views/legal/politique-cookies.blade.php 2>/dev/null && echo "OK" || echo "ABSENT"

# B1d. Layout principal (pour y injecter la modale)
grep -n "body\|</body>\|@yield\|@stack\|@livewire" resources/views/layouts/app.blade.php | head -15

# B1e. Footer cookies existant
grep -n "cookie" resources/views/partials/footer.blade.php 2>/dev/null | head -5


# ── B2 : MARKETPLACE PRO EN AVANT ──
echo "--- MARKETPLACE PRO PRIORITY ---"

# B2a. Page marketplace / artistes — controller
grep -rn "marketplace\|artistes\|featured\|mis.en.avant\|highlighted" app/Http/Controllers/ --include="*.php" -l | head -5

# B2b. Composant Livewire marketplace
find app/Livewire -name "*Marketplace*" -o -name "*Search*" -o -name "*Explore*" -o -name "*Artist*" | head -10

# B2c. Vue marketplace
find resources/views -name "*marketplace*" -o -name "*explore*" -o -name "*artistes*" | head -10

# B2d. Logique "mis en avant" existante
grep -rn "featured\|highlighted\|mis_en_avant\|en_avant\|is_featured\|promoted" app/Models/ app/Http/Controllers/ --include="*.php" | head -10

# B2e. is_subscribed / plan PRO
grep -n "is_subscribed\|plan\|subscription\|is_pro" app/Models/Tattooer.php app/Models/Piercer.php 2>/dev/null | head -10

# B2f. Query actuelle de la section "artistes mis en avant"
grep -B 5 -A 20 "featured\|mis.en.avant\|en_avant\|highlighted" app/Http/Controllers/MarketplaceController.php app/Http/Controllers/HomeController.php app/Livewire/ --include="*.php" 2>/dev/null | head -40


# ── B3 : RECHERCHE TEMPS RÉEL ──
echo "--- RECHERCHE TEMPS RÉEL ---"

# B3a. Composant Livewire de recherche marketplace
find app/Livewire -name "*Search*" -o -name "*Filter*" -o -name "*Marketplace*" | head -10

# B3b. Filtres existants
grep -rn "wire:model\|wire:input\|wire:keyup\|updatedSearch\|search\|filter" app/Livewire/ --include="*.php" | grep -i "market\|search\|explore\|artist" | head -10

# B3c. Vue filtres
grep -rn "wire:model\|wire:input\|wire:keyup" resources/views/livewire/ --include="*.blade.php" | grep -i "market\|search\|filter\|explore" | head -10

# B3d. Voir le composant de recherche complet
find app/Livewire -name "*.php" -exec grep -l "search\|filter\|query" {} \; | head -5


# ── B4 : CARDS STUDIO MARKETPLACE ──
echo "--- CARDS STUDIO ---"

# B4a. Modèle Studio — profil public
grep -n "function \|scope\|fillable\|slug\|is_active\|is_visible" app/Models/Studio.php | head -20

# B4b. Profil public studio
grep -rn "publicProfile\|public_profile\|salon" app/Http/Controllers/ --include="*.php" | head -5

# B4c. Card artiste existante (pour s'en inspirer)
find resources/views -name "*card*" -o -name "*artist-card*" -o -name "*tattooer-card*" | head -10
grep -rn "card\|artist.*card\|tattooer.*card" resources/views/ --include="*.blade.php" -l | head -10

# B4d. Relation studio ↔ avis
grep -n "reviews\|avis\|rating\|note" app/Models/Studio.php 2>/dev/null | head -5
grep -n "reviews\|avis\|rating\|note" app/Models/Tattooer.php 2>/dev/null | head -5

# B4e. Filtre studio dans marketplace
grep -rn "studio\|salon\|type.*filter\|filter.*studio" app/Livewire/ --include="*.php" | grep -i "market\|search\|explore" | head -10

# B4f. Route profil public studio
php artisan route:list 2>&1 | grep -i "salon\|studio.*public\|studio.*profil" | head -5

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX B1 — MODALE ACCEPTATION COOKIES (RGPD)

### Objectif
Créer une modale/bandeau de consentement cookies conforme RGPD avec 3 options :
- **Tout accepter**
- **Tout refuser** (seuls les cookies strictement nécessaires restent)
- **Accepter uniquement les cookies nécessaires**
- Lien vers la page politique cookies (`/legal/politique-cookies`)

### Implémentation

Créer un composant Alpine.js autonome (pas besoin de Livewire, pas de requête serveur — le consentement est stocké dans un cookie `cookie_consent`) :

```blade
{{-- resources/views/partials/cookie-consent.blade.php --}}
<div x-data="cookieConsent()" x-show="showBanner" x-transition.opacity x-cloak
    class="fixed bottom-0 inset-x-0 z-[100] p-4 sm:p-6"
    role="dialog" aria-label="Gestion des cookies">

    <div class="max-w-3xl mx-auto bg-gris-fonde border border-titane/20 rounded-2xl shadow-2xl overflow-hidden">
        {{-- Contenu principal --}}
        <div class="p-5 sm:p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 text-2xl">🍪</div>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-ivoire-text mb-2">Nous respectons votre vie privée</h3>
                    <p class="text-sm text-titane leading-relaxed">
                        Ink&Pik utilise des cookies pour assurer le bon fonctionnement du site et améliorer votre expérience.
                        Certains cookies sont strictement nécessaires et ne peuvent pas être désactivés.
                        <a href="{{ route('legal.politique-cookies') }}" class="text-beige-peau hover:underline">
                            En savoir plus
                        </a>
                    </p>
                </div>
            </div>

            {{-- Détails (toggle) --}}
            <div x-show="showDetails" x-transition class="mt-4 ml-10 space-y-3">
                {{-- Cookie nécessaire --}}
                <div class="flex items-center justify-between p-3 bg-noir-profond/40 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-ivoire-text">Cookies strictement nécessaires</p>
                        <p class="text-xs text-titane mt-0.5">Authentification, sécurité CSRF, session. Indispensables au fonctionnement.</p>
                    </div>
                    <span class="text-xs text-green-400 font-medium px-2 py-0.5 bg-green-500/10 rounded">Toujours actifs</span>
                </div>

                {{-- Cookies analytics --}}
                <div class="flex items-center justify-between p-3 bg-noir-profond/40 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-ivoire-text">Cookies de mesure d'audience</p>
                        <p class="text-xs text-titane mt-0.5">Nous aident à comprendre comment vous utilisez le site (anonymisé).</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="analytics" class="sr-only peer">
                        <div class="w-9 h-5 bg-titane/30 peer-checked:bg-beige-peau rounded-full transition-colors
                            after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white
                            after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full"></div>
                    </label>
                </div>

                {{-- Cookies tiers (Stripe) --}}
                <div class="flex items-center justify-between p-3 bg-noir-profond/40 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-ivoire-text">Cookies tiers (Stripe)</p>
                        <p class="text-xs text-titane mt-0.5">Prévention de la fraude lors des paiements.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="thirdParty" class="sr-only peer">
                        <div class="w-9 h-5 bg-titane/30 peer-checked:bg-beige-peau rounded-full transition-colors
                            after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white
                            after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Boutons --}}
        <div class="px-5 pb-5 sm:px-6 sm:pb-6">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                <button @click="acceptAll()" 
                    class="flex-1 px-4 py-2.5 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                    Tout accepter
                </button>
                <button @click="acceptNecessaryOnly()"
                    class="flex-1 px-4 py-2.5 text-sm font-medium bg-gris-fonde text-titane border border-titane/30 rounded-lg hover:border-beige-peau/50 hover:text-ivoire-text transition-colors">
                    Nécessaires uniquement
                </button>
                <button @click="rejectAll()"
                    class="flex-1 px-4 py-2.5 text-sm font-medium bg-gris-fonde text-titane border border-titane/30 rounded-lg hover:border-rouge-alerte/50 hover:text-rouge-alerte transition-colors">
                    Tout refuser
                </button>
                <button @click="showDetails = !showDetails" 
                    class="px-4 py-2.5 text-sm text-beige-peau hover:underline">
                    <span x-text="showDetails ? 'Masquer' : 'Personnaliser'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cookieConsent() {
    return {
        showBanner: false,
        showDetails: false,
        analytics: false,
        thirdParty: false,

        init() {
            const consent = this.getCookie('cookie_consent');
            if (!consent) {
                this.showBanner = true;
            }
        },

        acceptAll() {
            this.setConsent({ necessary: true, analytics: true, thirdParty: true });
        },

        acceptNecessaryOnly() {
            this.setConsent({ necessary: true, analytics: false, thirdParty: false });
        },

        rejectAll() {
            this.setConsent({ necessary: true, analytics: false, thirdParty: false });
        },

        setConsent(preferences) {
            const value = JSON.stringify({
                ...preferences,
                timestamp: new Date().toISOString(),
                version: '1.0',
            });
            // Cookie valide 13 mois (conformité CNIL)
            const expires = new Date(Date.now() + 395 * 24 * 60 * 60 * 1000).toUTCString();
            document.cookie = `cookie_consent=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax; Secure`;
            this.showBanner = false;

            // Déclencher un event pour que d'autres scripts puissent réagir
            window.dispatchEvent(new CustomEvent('cookie-consent-updated', { detail: preferences }));
        },

        getCookie(name) {
            const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? decodeURIComponent(match[2]) : null;
        },
    };
}
</script>
```

### Inclure dans le layout principal

```bash
# Trouver le layout principal
head -5 resources/views/layouts/app.blade.php
```

Ajouter juste avant `</body>` dans le layout principal :

```blade
{{-- resources/views/layouts/app.blade.php — avant </body> --}}
@include('partials.cookie-consent')
```

### Lien « Gérer mes cookies » dans le footer

Dans le footer existant (`partials/footer.blade.php`), ajouter un bouton qui ré-ouvre la modale :

```blade
<button onclick="document.cookie='cookie_consent=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'; location.reload();"
    class="text-xs text-titane hover:text-beige-peau transition-colors">
    Gérer mes cookies
</button>
```

```bash
git add -A && git commit -m "feat(B1): modale consentement cookies RGPD — accepter/refuser/nécessaires + lien politique"
```

---

## FIX B2 — MARKETPLACE : ARTISTES PRO EN PREMIER + ROTATION HEBDO

### Objectif
- Les artistes avec un plan PRO (ou Studio) doivent apparaître EN PREMIER dans la section "Artistes mis en avant"
- Rotation aléatoire hebdomadaire : chaque semaine, l'ordre aléatoire change (mais les PRO restent toujours devant les FREE)

### Implémentation

Trouver la query qui alimente la section "Artistes mis en avant" :

```bash
grep -rn "featured\|mis.en.avant\|en_avant\|highlighted\|inRandomOrder\|random" app/Http/Controllers/ app/Livewire/ --include="*.php" | head -15
```

**Modifier la query** pour trier PRO d'abord, puis aléatoire avec seed hebdomadaire :

```php
// Le seed change chaque semaine (lundi = nouveau seed)
$weeklySeed = (int) now()->startOfWeek()->timestamp;

// Query artistes mis en avant
$featuredArtists = Tattooer::where('is_active', true)
    ->where('is_visible', true) // ou équivalent
    // PRO en premier (is_subscribed = true OU studio avec abonnement actif)
    ->orderByDesc('is_subscribed')
    // Rotation aléatoire avec seed hebdomadaire
    ->inRandomOrder($weeklySeed)
    ->limit(12) // ou le nombre souhaité
    ->get();
```

Si les pierceurs sont aussi dans la marketplace :
```php
// Combiner tattooers + piercers, trier PRO d'abord
$featuredTattooers = Tattooer::where('is_active', true)
    ->where('is_visible', true)
    ->select('*', DB::raw("'tattooer' as artist_type"))
    ->get();

$featuredPiercers = Piercer::where('is_active', true)
    ->where('is_visible', true)
    ->select('*', DB::raw("'piercer' as artist_type"))
    ->get();

$weeklySeed = (int) now()->startOfWeek()->timestamp;

$featuredArtists = $featuredTattooers->merge($featuredPiercers)
    // PRO en premier
    ->sortByDesc('is_subscribed')
    // Puis aléatoire avec seed hebdomadaire (au sein de chaque groupe)
    ->groupBy('is_subscribed')
    ->map(function ($group) use ($weeklySeed) {
        srand($weeklySeed);
        return $group->shuffle();
    })
    ->flatten()
    ->take(12);
```

IMPORTANT :
- `is_subscribed` est le champ qui indique si l'artiste est PRO — vérifier le vrai nom en Phase 0
- Pour les artistes rattachés à un studio, `is_subscribed` est `true` automatiquement (trouvé dans l'audit : "StudioArtist = PRO auto")
- `inRandomOrder($seed)` est natif Laravel et utilise un seed pour la reproductibilité
- Le seed `startOfWeek()->timestamp` change chaque lundi → nouvelle rotation

```bash
git add -A && git commit -m "feat(B2): marketplace artistes PRO en premier + rotation aléatoire hebdomadaire"
```

---

## FIX B3 — RECHERCHE DYNAMIQUE TEMPS RÉEL

### Objectif
La recherche dans la marketplace doit filtrer en temps réel à chaque lettre tapée (debounce Livewire), sans rechargement de page.

### Diagnostic

Trouver le composant Livewire de recherche :
```bash
find app/Livewire -name "*.php" -exec grep -l "search\|query\|filter" {} \; | head -10
```

Lire le composant et vérifier le `wire:model` :
```bash
# Vérifier si wire:model.live ou wire:model.debounce est utilisé
grep -rn "wire:model" resources/views/livewire/ --include="*.blade.php" | grep -i "search\|query\|filter" | head -10
```

### Fix

**Problème typique** : `wire:model="search"` au lieu de `wire:model.live.debounce.300ms="search"`.

Dans la vue Blade du composant marketplace/recherche :

```blade
{{-- AVANT (pas réactif — attend un submit) --}}
<input wire:model="search" type="text" placeholder="Rechercher...">

{{-- APRÈS (réactif à chaque frappe avec debounce 300ms) --}}
<input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher un artiste, un style, une ville...">
```

Faire de même pour TOUS les filtres de la marketplace :
```blade
{{-- Filtres select --}}
<select wire:model.live="filterStyle">
<select wire:model.live="filterCity">
<select wire:model.live="filterType"> {{-- tattooer/pierceur/studio --}}
```

Dans le composant Livewire PHP, vérifier que les propriétés déclenchent bien une requête :
```php
// Le composant doit avoir ces propriétés publiques
public string $search = '';
public string $filterStyle = '';
public string $filterCity = '';
public string $filterType = '';

// Livewire 3 : les propriétés wire:model.live mettent à jour automatiquement
// Pas besoin de updatedSearch() si la query est dans render()

public function render()
{
    $query = Tattooer::query()
        ->where('is_active', true);

    // Recherche texte
    if ($this->search) {
        $query->where(function ($q) {
            $q->whereHas('user', function ($sub) {
                $sub->where('name', 'LIKE', '%' . $this->search . '%');
            })
            ->orWhere('city', 'LIKE', '%' . $this->search . '%')
            ->orWhere('bio', 'LIKE', '%' . $this->search . '%')
            ->orWhere('specialties', 'LIKE', '%' . $this->search . '%');
            // Ajouter styles, tags, etc.
        });
    }

    // Filtres
    if ($this->filterStyle) {
        $query->where('style', $this->filterStyle);
        // OU whereJsonContains si c'est un JSON
    }

    if ($this->filterCity) {
        $query->where('city', $this->filterCity);
    }

    // PRO en premier + rotation hebdomadaire
    $weeklySeed = (int) now()->startOfWeek()->timestamp;
    $query->orderByDesc('is_subscribed')
          ->inRandomOrder($weeklySeed);

    $artists = $query->paginate(12);

    return view('livewire.marketplace.artist-list', [
        'artists' => $artists,
    ]);
}
```

**Point clé** : la query se fait dans `render()`, donc chaque changement de propriété via `wire:model.live` déclenche automatiquement un re-render avec les nouveaux résultats. Pas besoin de méthodes `updatedSearch()` ou de listeners.

### Réinitialiser la pagination à chaque changement de filtre

```php
// Dans le composant Livewire
use Livewire\WithPagination;

class MarketplaceSearch extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStyle = '';
    public string $filterCity = '';

    // Réinitialiser la page quand un filtre change
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterStyle() { $this->resetPage(); }
    public function updatingFilterCity() { $this->resetPage(); }
    
    // ...
}
```

```bash
git add -A && git commit -m "feat(B3): recherche marketplace temps réel — wire:model.live.debounce + filtres dynamiques"
```

---

## FIX B4 — CARDS STUDIO DANS LA MARKETPLACE

### Objectif
1. Afficher des cards studio dans la marketplace (même filtre "studio" activé)
2. Chaque card studio montre : nom, photo, adresse, note globale (moyenne des avis de tous les artistes du studio)
3. La card est cliquable et mène au profil public du studio (`/salon/{slug}`)
4. Sur les cards des artistes rattachés à un studio, ajouter un lien vers le profil studio

### Card studio

Créer un partial pour la card studio :

```blade
{{-- resources/views/partials/studio-card.blade.php --}}
@props(['studio'])

<a href="{{ route('studio.public-profile', $studio->slug ?? $studio->id) }}" 
    class="group block bg-gris-fonde rounded-xl border border-titane/10 hover:border-beige-peau/30 overflow-hidden transition-all hover:shadow-lg hover:shadow-beige-peau/5">
    
    {{-- Bannière / Photo --}}
    <div class="relative h-40 bg-noir-profond">
        @if ($studio->getFirstMediaUrl('banner') || $studio->getFirstMediaUrl('cover'))
            <img src="{{ $studio->getFirstMediaUrl('banner') ?: $studio->getFirstMediaUrl('cover') }}" 
                alt="{{ $studio->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-noir-profond to-gris-fonde">
                <span class="text-3xl text-titane/30">🏢</span>
            </div>
        @endif
        
        {{-- Badge Studio --}}
        <span class="absolute top-3 left-3 px-2 py-0.5 text-xs font-medium bg-beige-peau/90 text-noir-profond rounded-full">
            Studio
        </span>

        {{-- Nombre d'artistes --}}
        <span class="absolute top-3 right-3 px-2 py-0.5 text-xs font-medium bg-noir-profond/70 text-ivoire-text rounded-full">
            {{ $studio->artists_count ?? $studio->tattooers_count ?? 0 }} artiste{{ ($studio->artists_count ?? 0) > 1 ? 's' : '' }}
        </span>
    </div>

    {{-- Infos --}}
    <div class="p-4">
        <h3 class="text-base font-semibold text-ivoire-text group-hover:text-beige-peau transition-colors truncate">
            {{ $studio->name }}
        </h3>

        {{-- Localisation --}}
        @if ($studio->city || $studio->address)
            <p class="text-xs text-titane mt-1 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $studio->city ?? Str::limit($studio->address, 40) }}
            </p>
        @endif

        {{-- Note globale (moyenne des avis des artistes du studio) --}}
        @php
            $avgRating = $studio->average_rating ?? null;
            $reviewCount = $studio->reviews_count ?? 0;
        @endphp
        @if ($avgRating)
            <div class="flex items-center gap-1.5 mt-2">
                <div class="flex items-center">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-3.5 h-3.5 {{ $i <= round($avgRating) ? 'text-beige-peau' : 'text-titane/30' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-xs text-titane">{{ number_format($avgRating, 1) }} ({{ $reviewCount }} avis)</span>
            </div>
        @endif

        {{-- Spécialités du studio (styles des artistes) --}}
        @if ($studio->specialties && count($studio->specialties) > 0)
            <div class="flex flex-wrap gap-1 mt-2">
                @foreach (array_slice($studio->specialties, 0, 3) as $specialty)
                    <span class="px-2 py-0.5 text-[10px] bg-noir-profond text-titane rounded-full">{{ $specialty }}</span>
                @endforeach
            </div>
        @endif
    </div>
</a>
```

### Note globale du studio

Ajouter un accessor ou une méthode sur le modèle Studio pour calculer la note moyenne :

```php
// Dans app/Models/Studio.php — ajouter un accessor ou scope
public function getAverageRatingAttribute(): ?float
{
    // Calculer la moyenne des avis de tous les artistes du studio
    $tattooerIds = $this->tattooers()->pluck('id');
    $piercerIds = $this->piercers()->pluck('id');

    $avgRating = \App\Models\Review::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->whereIn('reviewable_id', $tattooerIds)->where('reviewable_type', Tattooer::class)
              ->orWhere(function ($sub) use ($piercerIds) {
                  $sub->whereIn('reviewable_id', $piercerIds)->where('reviewable_type', Piercer::class);
              });
        })
        ->avg('rating');

    return $avgRating ? round($avgRating, 1) : null;
}

public function getReviewsCountAttribute(): int
{
    $tattooerIds = $this->tattooers()->pluck('id');
    $piercerIds = $this->piercers()->pluck('id');

    return \App\Models\Review::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->whereIn('reviewable_id', $tattooerIds)->where('reviewable_type', Tattooer::class)
              ->orWhere(function ($sub) use ($piercerIds) {
                  $sub->whereIn('reviewable_id', $piercerIds)->where('reviewable_type', Piercer::class);
              });
        })
        ->count();
}
```

IMPORTANT : Adapter le modèle Review et les relations polymorphiques selon la structure réelle trouvée en Phase 0.

### Intégrer les cards studio dans la marketplace

Dans le composant Livewire de la marketplace, ajouter les studios quand le filtre est actif :

```php
// Dans le render() du composant marketplace
if ($this->filterType === 'studio' || $this->filterType === '' || $this->filterType === 'all') {
    $studios = Studio::where('is_active', true)
        ->withCount('tattooers')
        ->when($this->search, function ($q) {
            $q->where('name', 'LIKE', '%' . $this->search . '%')
              ->orWhere('city', 'LIKE', '%' . $this->search . '%');
        })
        ->get();
}
```

Dans la vue :
```blade
{{-- Afficher les cards studio --}}
@if (isset($studios) && $studios->count() > 0)
    @foreach ($studios as $studio)
        @include('partials.studio-card', ['studio' => $studio])
    @endforeach
@endif
```

### Lien vers le profil studio sur les cards artistes

Sur la card artiste existante, si l'artiste est rattaché à un studio, ajouter un lien :

```blade
{{-- Dans la card artiste existante --}}
@if ($artist->studio)
    <a href="{{ route('studio.public-profile', $artist->studio->slug ?? $artist->studio->id) }}" 
        class="inline-flex items-center gap-1 text-xs text-beige-peau hover:underline mt-1">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        {{ $artist->studio->name }}
    </a>
@endif
```

```bash
git add -A && git commit -m "feat(B4): cards studio marketplace + note globale avis + lien profil studio sur cards artistes"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT B ==="

# V1. Cookie consent
echo "--- COOKIES ---"
ls resources/views/partials/cookie-consent.blade.php && echo "Modale OK"
grep -c "cookie-consent\|cookie_consent" resources/views/layouts/app.blade.php
echo "Incluse dans layout (doit être > 0)"

# V2. PRO en premier
echo "--- PRO PRIORITY ---"
grep -c "is_subscribed\|orderByDesc.*subscri" app/Http/Controllers/ app/Livewire/ --include="*.php" -r 2>/dev/null
echo "Tri PRO en premier (doit être > 0)"
grep -c "startOfWeek\|weeklySeed\|inRandomOrder" app/Http/Controllers/ app/Livewire/ --include="*.php" -r 2>/dev/null
echo "Rotation hebdomadaire (doit être > 0)"

# V3. Recherche temps réel
echo "--- RECHERCHE ---"
grep -c "wire:model.live\|wire:model.live.debounce" resources/views/livewire/ --include="*.blade.php" -r 2>/dev/null
echo "wire:model.live dans les vues (doit être > 0)"

# V4. Cards studio
echo "--- CARDS STUDIO ---"
ls resources/views/partials/studio-card.blade.php 2>/dev/null && echo "Card studio OK" || echo "Card studio ABSENTE"
grep -c "average_rating\|getAverageRatingAttribute" app/Models/Studio.php 2>/dev/null
echo "Note globale studio (doit être > 0)"

# V5. Compilation
php artisan route:clear
php artisan view:clear
php artisan route:list 2>&1 | head -3
echo "Pas d'erreur = OK"

echo "=== PROMPT B TERMINÉ — 4 fixes ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire
2. **Cookie consent** : pur Alpine.js, pas de Livewire, pas de requête serveur. Cookie stocké 13 mois (CNIL).
3. **PRO en premier** : `orderByDesc('is_subscribed')` AVANT `inRandomOrder()`. Les artistes studio sont PRO auto.
4. **Recherche** : `wire:model.live.debounce.300ms` pour Livewire 3.7 (PAS `.defer` qui est Livewire 2)
5. **Cards studio** : ne pas casser les cards artistes existantes, AJOUTER les cards studio
6. **Note globale** : agrégation des avis de TOUS les artistes du studio (tattooers + piercers)
7. **Commit après chaque fix** (4 commits)
