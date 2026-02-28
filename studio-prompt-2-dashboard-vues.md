# 🏢 STUDIO PROMPT 2/4 — Dashboard classique, Layout, Vues, Inscription
# Pour Claude Code — Commit après chaque phase

## CONTEXTE

Suite du Prompt 1/4. Les fondations sont en place :
- Models Studio + StudioArtist complets
- Tables studios + studio_artists avec toutes les colonnes
- Relations User (isStudio, isStudioArtist, isIndependent, artistStudio)
- Routes studio complètes (dashboard, settings, artistes, billing, stats, invitations)
- StudioController complet avec gestion artistes + invitations
- Rôles Spatie : studio, studio_owner, studio_artist
- Middleware role: vérifie user.role (pas Spatie)

Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js.

Design system : noir-profond (#0a0a14), gris-fonde (#1a1a2e), beige-peau (#c4956a), ivoire-text (#f5f0eb), titane (#8a8a9a), rouge-alerte (#ef4444), vert-validation (#22c55e). Mobile-first.

### CE QUE CE PROMPT CRÉE
1. Layout studio (sidebar + header)
2. Inscription studio (formulaire)
3. Dashboard studio (stats, artistes, demandes récentes)
4. Settings studio (infos, horaires, photos, choix paiement)
5. Liste artistes (actifs + invitations en attente)
6. Formulaire création/invitation artiste
7. Profil public studio (photos salon + artistes rattachés)
8. Planning global (vue calendrier tous les artistes)
9. Billing (récapitulatif facturation)

---

## PHASE 0 — AUDIT VUES EXISTANTES

```bash
# 0A. Vues studio existantes
find resources/views/studio -type f 2>/dev/null | sort
find resources/views/livewire/studio -type f 2>/dev/null | sort

# 0B. Layout existant
ls resources/views/layouts/studio* 2>/dev/null
ls resources/views/components/studio* 2>/dev/null

# 0C. Composants Livewire studio
find app/Livewire/Studio -type f 2>/dev/null | sort

# 0D. Inscription studio existante
grep -rn "studio.*register\|register.*studio\|inscription.*studio" routes/web.php resources/views/auth/ | head -10

# 0E. Layout tattooer (référence pour copier le style)
head -50 resources/views/layouts/tattooer.blade.php

# 0F. Vérifier les routes et leur action (Livewire ou Controller)
php artisan route:list --name="studio" --columns=method,uri,name,action 2>&1 | head -30
```

**MONTRE-MOI les résultats avant de continuer.**

---

## PHASE 1 — LAYOUT STUDIO

Le layout studio doit suivre le MÊME pattern que le layout tattooer (sidebar gauche desktop, menu bottom mobile) mais adapté au studio.

```bash
# Examiner le layout tattooer pour copier le pattern
cat resources/views/layouts/tattooer.blade.php
```

### 1A. Créer le layout studio

Créer `resources/views/layouts/studio.blade.php` en copiant la STRUCTURE EXACTE du layout tattooer, avec ces adaptations :

**Sidebar navigation studio (desktop gauche, mobile bottom) :**

```
📊 Dashboard        → route('studio.dashboard')
👥 Artistes          → route('studio.artists')
📅 Planning          → route('studio.planning')
⚙️ Paramètres       → route('studio.settings')
💳 Facturation       → route('studio.billing')
📈 Statistiques      → route('studio.stats')
🌐 Profil public     → route('studio.profile')
```

**Header :** Nom du studio + logo + dropdown profil user

IMPORTANT :
- Copier le HTML/CSS/Alpine.js du layout tattooer pour les animations, le responsive, le menu mobile bottom
- Remplacer les routes `tattooer.xxx` par `studio.xxx`
- Remplacer les labels et icônes
- Garder la MÊME logique de `@yield('content')` ou `{{ $slot }}`
- Si le layout tattooer utilise Livewire `@livewire` ou `<livewire:xxx />`, utiliser le même pattern
- Si le layout utilise des composants de navigation séparés, créer les équivalents studio

Vérifier si le layout tattooer utilise un composant de navigation séparé :
```bash
grep -n "navigation\|sidebar\|menu" resources/views/layouts/tattooer.blade.php | head -10
```

Si oui, créer l'équivalent studio.

```bash
php artisan view:clear
git add -A && git commit -m "feat(studio): layout studio avec sidebar navigation"
```

---

## PHASE 2 — INSCRIPTION STUDIO

Le studio doit pouvoir s'inscrire via un formulaire dédié.

### 2A. Vérifier le flow d'inscription existant

```bash
# Comment l'inscription fonctionne actuellement
grep -rn "register\|inscription" routes/web.php | head -10
cat resources/views/auth/register.blade.php | head -80
# Y a-t-il déjà un choix de type (tattooer/piercer/studio) ?
grep -n "artisan_type\|role\|type.*profil\|studio" resources/views/auth/register.blade.php
```

### 2B. Adapter l'inscription

Dans le formulaire d'inscription (`resources/views/auth/register.blade.php`), le choix du type de profil doit inclure Studio :

```blade
<div>
    <label class="block text-sm font-semibold text-ivoire-text mb-3">Type de profil *</label>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        {{-- Tatoueur --}}
        <label class="cursor-pointer">
            <input type="radio" name="artisan_type" value="tattooer" class="peer hidden" checked>
            <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 text-center transition-colors">
                <span class="text-2xl block mb-1">🎨</span>
                <span class="text-sm font-semibold text-ivoire-text">Tatoueur</span>
                <p class="text-xs text-titane mt-1">Indépendant</p>
            </div>
        </label>
        {{-- Pierceur --}}
        <label class="cursor-pointer">
            <input type="radio" name="artisan_type" value="piercer" class="peer hidden">
            <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 text-center transition-colors">
                <span class="text-2xl block mb-1">💎</span>
                <span class="text-sm font-semibold text-ivoire-text">Pierceur</span>
                <p class="text-xs text-titane mt-1">Indépendant</p>
            </div>
        </label>
        {{-- Studio --}}
        <label class="cursor-pointer">
            <input type="radio" name="artisan_type" value="studio" class="peer hidden">
            <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 text-center transition-colors">
                <span class="text-2xl block mb-1">🏢</span>
                <span class="text-sm font-semibold text-ivoire-text">Studio</span>
                <p class="text-xs text-titane mt-1">Salon avec artistes</p>
            </div>
        </label>
    </div>
</div>

{{-- Champs supplémentaires studio (Alpine.js show/hide) --}}
<div x-show="$refs.studioRadio?.checked || document.querySelector('[name=artisan_type][value=studio]:checked')" 
     x-data="{ isStudio: false }"
     x-init="$watch('isStudio', v => {}); document.querySelectorAll('[name=artisan_type]').forEach(r => r.addEventListener('change', () => isStudio = document.querySelector('[name=artisan_type]:checked')?.value === 'studio'))"
     x-show="isStudio" x-transition>
    <div class="space-y-3 mt-4 p-4 bg-gris-fonde rounded-xl">
        <h4 class="text-sm font-semibold text-ivoire-text">Informations du studio</h4>
        <input type="text" name="studio_name" placeholder="Nom du studio *"
            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
        <input type="text" name="studio_address" placeholder="Adresse"
            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
        <div class="flex gap-3">
            <input type="text" name="studio_city" placeholder="Ville"
                class="flex-1 px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
            <input type="text" name="studio_postal_code" placeholder="Code postal" maxlength="5"
                class="w-28 px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
        </div>
        <input type="text" name="studio_siret" placeholder="SIRET (14 chiffres)" maxlength="14"
            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
    </div>
</div>
```

### 2C. Adapter le controller d'inscription

Dans le controller qui traite l'inscription, ajouter la logique studio :

```bash
# Trouver le controller d'inscription
grep -rn "function.*register\|function.*create.*user\|function.*store" app/Http/Controllers/Auth/ | head -10
```

Ajouter après la création du User :

```php
if ($request->artisan_type === 'studio') {
    $user->assignRole('studio');
    // Ou si le middleware utilise user.role :
    $user->update(['role' => 'studio']);
    
    Studio::create([
        'user_id' => $user->id,
        'name' => $request->studio_name ?? $user->name . "'s Studio",
        'slug' => Str::slug($request->studio_name ?? $user->name . '-studio'),
        'address' => $request->studio_address,
        'city' => $request->studio_city,
        'postal_code' => $request->studio_postal_code,
        'siret' => $request->studio_siret,
        'payment_mode' => 'centralized', // Défaut, modifiable dans settings
        'is_active' => true,
    ]);
    
    return redirect()->route('studio.dashboard');
}
```

IMPORTANT : Adapter au format EXACT du controller d'inscription existant. S'il utilise Fortify, Breeze, ou un controller custom, adapter en conséquence.

```bash
git add -A && git commit -m "feat(studio): inscription studio avec choix type profil"
```

---

## PHASE 3 — DASHBOARD STUDIO

Créer `resources/views/studio/dashboard.blade.php` (ou adapter le composant Livewire existant).

```bash
# Vérifier si c'est Livewire ou Blade classique
php artisan route:list --name="studio.dashboard" --columns=action 2>&1
```

Le dashboard doit afficher :

```blade
@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    {{-- En-tête --}}
    <div>
        <h1 class="text-2xl font-bold text-ivoire-text">Tableau de bord</h1>
        <p class="text-sm text-titane mt-1">{{ $studio->name }}</p>
    </div>

    {{-- Stats rapides --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">Artistes</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1">{{ $artistCount }}</p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">Ce mois</p>
            <p class="text-2xl font-bold text-beige-peau mt-1">{{ number_format($monthlyPrice, 2) }}€</p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">Demandes en cours</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1">{{ $pendingRequests ?? 0 }}</p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">RDV aujourd'hui</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1">{{ $todayAppointments ?? 0 }}</p>
        </div>
    </div>

    {{-- Artistes du studio --}}
    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">👥 Artistes</h2>
            <a href="{{ route('studio.artists') }}" class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">Gérer →</a>
        </div>
        
        @forelse ($artists as $studioArtist)
            <div class="flex items-center gap-3 py-3 {{ !$loop->last ? 'border-b border-titane/10' : '' }}">
                <img src="{{ $studioArtist->user?->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}" 
                    alt="{{ $studioArtist->user?->name }}" 
                    class="w-10 h-10 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate">{{ $studioArtist->user?->name ?? 'Invitation en attente' }}</p>
                    <p class="text-xs text-titane">
                        {{ $studioArtist->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}
                        @if (!$studioArtist->is_active)
                            <span class="text-rouge-alerte ml-1">• Inactif</span>
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-titane">
                        @if ($studioArtist->user)
                            {{ $studioArtist->user->artisan()?->bookingRequests()->where('status', 'pending')->count() ?? 0 }} demandes
                        @else
                            ⏳ En attente
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <p class="text-sm text-titane text-center py-4">Aucun artiste. <a href="{{ route('studio.artists.create') }}" class="text-beige-peau hover:underline">Ajouter un artiste</a></p>
        @endforelse
    </div>

    {{-- Actions rapides --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <a href="{{ route('studio.artists.create') }}" class="bg-beige-peau text-noir-profond rounded-xl p-4 font-semibold text-center hover:bg-beige-peau/90 transition-colors">
            + Ajouter un artiste
        </a>
        <a href="{{ route('studio.planning') }}" class="bg-gris-fonde text-ivoire-text rounded-xl p-4 font-semibold text-center hover:bg-gris-fonde/80 transition-colors border border-titane/20">
            📅 Voir le planning
        </a>
    </div>
</div>
@endsection
```

IMPORTANT : Si le dashboard utilise déjà un composant Livewire (ex: `Livewire\Studio\Dashboard`), adapter le composant Livewire existant au lieu de créer une vue Blade. Vérifier le résultat de la Phase 0 pour savoir.

Enrichir le controller `dashboard()` pour passer les données manquantes :

```php
public function dashboard()
{
    $studio = $this->studio();
    $artists = $studio->studioArtists()->with('user')->get();
    $activeArtists = $artists->where('is_active', true);
    
    // Compter les demandes en cours de tous les artistes
    $artistUserIds = $activeArtists->pluck('user_id')->filter();
    $pendingRequests = \App\Models\BookingRequest::whereIn('bookable_id', function($q) use ($artistUserIds) {
        // Via les profils tattooer/piercer des users artistes
        $q->select('id')->from('tattooers')->whereIn('user_id', $artistUserIds)
          ->union(
            \DB::table('piercers')->select('id')->whereIn('user_id', $artistUserIds)
          );
    })->where('status', 'pending')->count();
    
    return view('studio.dashboard', [
        'studio' => $studio,
        'artists' => $activeArtists,
        'artistCount' => $activeArtists->count(),
        'monthlyPrice' => $studio->monthlyPrice(),
        'pendingRequests' => $pendingRequests,
        'todayAppointments' => 0, // TODO: compter les RDV du jour
    ]);
}
```

```bash
git add -A && git commit -m "feat(studio): dashboard avec stats, artistes, actions rapides"
```

---

## PHASE 4 — SETTINGS STUDIO

Créer `resources/views/studio/settings.blade.php`.

Le formulaire doit permettre de configurer :
- Infos de base (nom, description, adresse, ville, CP, téléphone, email, website, SIRET)
- Horaires d'ouverture (JSON, par jour de semaine)
- Photos (logo, cover, photos du salon)
- Modèle de paiement (centralisé / distribué)
- Liens réseaux sociaux

```blade
@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Paramètres du studio</h1>

    <form action="{{ route('studio.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ═══ INFORMATIONS DE BASE ═══ --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">🏢 Informations</h2>

            <div>
                <label class="text-xs text-titane block mb-1">Nom du studio *</label>
                <input type="text" name="name" value="{{ old('name', $studio->name) }}" required
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Description</label>
                <textarea name="description" rows="4" placeholder="Présentez votre studio..."
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau resize-none">{{ old('description', $studio->description) }}</textarea>
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Adresse</label>
                <input type="text" name="address" value="{{ old('address', $studio->address) }}"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Ville</label>
                    <input type="text" name="city" value="{{ old('city', $studio->city) }}"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                </div>
                <div class="w-full sm:w-32">
                    <label class="text-xs text-titane block mb-1">Code postal</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $studio->postal_code) }}" maxlength="5"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $studio->phone) }}"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                </div>
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Email professionnel</label>
                    <input type="email" name="email" value="{{ old('email', $studio->email) }}"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">Site web</label>
                    <input type="url" name="website" value="{{ old('website', $studio->website) }}" placeholder="https://..."
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
                </div>
                <div class="flex-1">
                    <label class="text-xs text-titane block mb-1">SIRET</label>
                    <input type="text" name="siret" value="{{ old('siret', $studio->siret) }}" maxlength="14" placeholder="14 chiffres"
                        class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
                </div>
            </div>
        </div>

        {{-- ═══ PHOTOS ═══ --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">📸 Photos</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-titane block mb-2">Logo</label>
                    @if ($studio->getFirstMediaUrl('logo'))
                        <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="Logo" class="w-24 h-24 rounded-lg object-cover mb-2">
                    @endif
                    <input type="file" name="logo" accept="image/*"
                        class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
                </div>
                <div>
                    <label class="text-xs text-titane block mb-2">Photo de couverture</label>
                    @if ($studio->getFirstMediaUrl('cover'))
                        <img src="{{ $studio->getFirstMediaUrl('cover') }}" alt="Couverture" class="w-full h-24 rounded-lg object-cover mb-2">
                    @endif
                    <input type="file" name="cover" accept="image/*"
                        class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
                </div>
            </div>
            <div>
                <label class="text-xs text-titane block mb-2">Photos du salon (multiples)</label>
                @if ($studio->getMedia('photos')->count() > 0)
                    <div class="flex flex-wrap gap-2 mb-2">
                        @foreach ($studio->getMedia('photos') as $photo)
                            <div class="relative group">
                                <img src="{{ $photo->getUrl() }}" alt="Photo salon" class="w-20 h-20 rounded-lg object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
                <input type="file" name="photos[]" accept="image/*" multiple
                    class="w-full text-sm text-titane file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau file:text-noir-profond file:font-semibold file:text-sm file:cursor-pointer hover:file:bg-beige-peau/90">
            </div>
        </div>

        {{-- ═══ MODÈLE DE PAIEMENT ═══ --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">💳 Paiement</h2>
            <p class="text-xs text-titane">Comment les clients paient-ils les prestations de vos artistes ?</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="payment_mode" value="centralized" 
                        {{ ($studio->payment_mode ?? 'centralized') === 'centralized' ? 'checked' : '' }} class="peer hidden">
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 transition-colors">
                        <p class="font-semibold text-ivoire-text text-sm">🏦 Centralisé</p>
                        <p class="text-xs text-titane mt-1">Le studio encaisse tout via un seul compte Stripe Connect. Vous reversez aux artistes.</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="payment_mode" value="distributed"
                        {{ ($studio->payment_mode ?? '') === 'distributed' ? 'checked' : '' }} class="peer hidden">
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 transition-colors">
                        <p class="font-semibold text-ivoire-text text-sm">👤 Distribué</p>
                        <p class="text-xs text-titane mt-1">Chaque artiste a son propre Stripe Connect. Vous supervisez seulement.</p>
                    </div>
                </label>
            </div>
        </div>

        {{-- ═══ HORAIRES ═══ --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6" x-data="{
            hours: {{ Js::from($studio->opening_hours ?? [
                'lundi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'mardi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'mercredi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'jeudi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'vendredi' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                'samedi' => ['open' => '10:00', 'close' => '18:00', 'closed' => false],
                'dimanche' => ['open' => '', 'close' => '', 'closed' => true],
            ]) }}
        }">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-4">🕐 Horaires d'ouverture</h2>
            <div class="space-y-2">
                <template x-for="(day, name) in hours" :key="name">
                    <div class="flex items-center gap-2">
                        <span class="w-24 text-sm text-ivoire-text capitalize" x-text="name"></span>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="checkbox" :name="'opening_hours[' + name + '][closed]'" x-model="day.closed"
                                class="rounded border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau">
                            <span class="text-xs text-titane">Fermé</span>
                        </label>
                        <template x-if="!day.closed">
                            <div class="flex items-center gap-1">
                                <input type="time" :name="'opening_hours[' + name + '][open]'" x-model="day.open"
                                    class="px-2 py-1.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                <span class="text-titane text-xs">→</span>
                                <input type="time" :name="'opening_hours[' + name + '][close]'" x-model="day.close"
                                    class="px-2 py-1.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- Bouton sauvegarder --}}
        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
            Sauvegarder
        </button>
    </form>
</div>
@endsection
```

IMPORTANT : Si la vue settings existe déjà en Livewire, ADAPTER le composant Livewire au lieu de créer une nouvelle vue Blade. Sinon, créer cette vue.

```bash
git add -A && git commit -m "feat(studio): settings complets (infos, photos, paiement, horaires)"
```

---

## PHASE 5 — LISTE ET GESTION DES ARTISTES

Créer `resources/views/studio/artists.blade.php` :

```blade
@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Artistes</h1>
            <p class="text-sm text-titane mt-1">
                {{ $activeArtists->count() }} artiste{{ $activeArtists->count() > 1 ? 's' : '' }} actif{{ $activeArtists->count() > 1 ? 's' : '' }}
                @if ($paidArtistCount > 0)
                    <span class="text-beige-peau">(dont {{ $paidArtistCount }} supplémentaire{{ $paidArtistCount > 1 ? 's' : '' }} à 39.99€/mois)</span>
                @endif
            </p>
        </div>
        @if ($canAddArtist)
            <a href="{{ route('studio.artists.create') }}" 
                class="px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                + Ajouter
            </a>
        @endif
    </div>

    {{-- Artistes actifs --}}
    <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
        @forelse ($activeArtists as $sa)
            <div class="flex items-center gap-3 p-4">
                <img src="{{ $sa->user?->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}" 
                    alt="{{ $sa->user?->name }}" class="w-12 h-12 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate">{{ $sa->user?->name }}</p>
                    <p class="text-xs text-titane">
                        {{ $sa->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}
                        • Rejoint {{ $sa->joined_at?->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Toggle actif/inactif --}}
                    <form action="{{ route('studio.artists.toggle', $sa) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-lg font-semibold transition-colors
                            {{ $sa->is_active ? 'bg-vert-validation/20 text-vert-validation' : 'bg-rouge-alerte/20 text-rouge-alerte' }}">
                            {{ $sa->is_active ? 'Actif' : 'Inactif' }}
                        </button>
                    </form>
                    {{-- Retirer --}}
                    <form action="{{ route('studio.artists.remove', $sa) }}" method="POST" 
                        onsubmit="return confirm('Retirer cet artiste du studio ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-rouge-alerte/60 hover:text-rouge-alerte p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-sm text-titane text-center py-8">Aucun artiste actif</p>
        @endforelse
    </div>

    {{-- Invitations en attente --}}
    @if ($pendingInvitations->count() > 0)
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">⏳ Invitations en attente</h2>
            @foreach ($pendingInvitations as $inv)
                <div class="flex items-center gap-3 py-2">
                    <div class="w-10 h-10 rounded-full bg-titane/20 flex items-center justify-center text-titane">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-ivoire-text">{{ $inv->invitation_email }}</p>
                        <p class="text-xs text-titane">
                            {{ $inv->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}
                            • Invité {{ $inv->invited_at?->diffForHumans() }}
                        </p>
                    </div>
                    <form action="{{ route('studio.artists.remove', $inv) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-rouge-alerte/60 hover:text-rouge-alerte">Annuler</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Pricing info --}}
    <div class="bg-gris-fonde/50 rounded-xl p-4 border border-titane/10">
        <p class="text-xs text-titane">
            💡 Votre abonnement Studio inclut <strong class="text-ivoire-text">1 artiste</strong>. 
            Chaque artiste supplémentaire coûte <strong class="text-beige-peau">39,99€/mois</strong>.
            Facturation actuelle : <strong class="text-ivoire-text">{{ number_format($monthlyPrice, 2) }}€/mois</strong>
        </p>
    </div>
</div>
@endsection
```

---

## PHASE 6 — FORMULAIRE CRÉATION / INVITATION ARTISTE

Créer `resources/views/studio/artists-create.blade.php` :

```blade
@extends('layouts.studio')

@section('content')
<div class="max-w-xl mx-auto space-y-6" x-data="{ mode: 'create' }">
    <div>
        <a href="{{ route('studio.artists') }}" class="text-xs text-titane hover:text-ivoire-text">← Retour</a>
        <h1 class="text-2xl font-bold text-ivoire-text mt-2">Ajouter un artiste</h1>
    </div>

    {{-- Choix du mode --}}
    <div class="flex gap-2">
        <button @click="mode = 'create'" 
            :class="mode === 'create' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane'"
            class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-colors">
            ✏️ Créer un compte
        </button>
        <button @click="mode = 'invite'"
            :class="mode === 'invite' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane'"
            class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-colors">
            📧 Envoyer une invitation
        </button>
    </div>

    {{-- Mode : Création directe --}}
    <form x-show="mode === 'create'" action="{{ route('studio.artists.store') }}" method="POST" class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
        @csrf
        <p class="text-xs text-titane">Créez un compte pour votre artiste. Il recevra un mot de passe temporaire.</p>
        
        <div>
            <label class="text-xs text-titane block mb-1">Nom complet *</label>
            <input type="text" name="name" required placeholder="Prénom Nom"
                class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
        </div>
        <div>
            <label class="text-xs text-titane block mb-1">Email *</label>
            <input type="email" name="email" required placeholder="artiste@email.com"
                class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
        </div>
        <div>
            <label class="text-xs text-titane block mb-1">Type de profil *</label>
            <div class="flex gap-3">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="artisan_type" value="tattooer" class="peer hidden" checked>
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                        <span class="text-lg">🎨</span>
                        <p class="text-xs font-semibold text-ivoire-text mt-1">Tatoueur</p>
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="artisan_type" value="piercer" class="peer hidden">
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                        <span class="text-lg">💎</span>
                        <p class="text-xs font-semibold text-ivoire-text mt-1">Pierceur</p>
                    </div>
                </label>
            </div>
        </div>
        <button type="submit" class="w-full py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
            Créer l'artiste
        </button>
    </form>

    {{-- Mode : Invitation --}}
    <form x-show="mode === 'invite'" action="{{ route('studio.artists.invite') }}" method="POST" class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
        @csrf
        <p class="text-xs text-titane">Envoyez une invitation par email. L'artiste créera son propre compte et sera automatiquement rattaché à votre studio.</p>
        
        <div>
            <label class="text-xs text-titane block mb-1">Email de l'artiste *</label>
            <input type="email" name="email" required placeholder="artiste@email.com"
                class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
        </div>
        <div>
            <label class="text-xs text-titane block mb-1">Type de profil *</label>
            <div class="flex gap-3">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="artisan_type" value="tattooer" class="peer hidden" checked>
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                        <span class="text-lg">🎨</span>
                        <p class="text-xs font-semibold text-ivoire-text mt-1">Tatoueur</p>
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="artisan_type" value="piercer" class="peer hidden">
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                        <span class="text-lg">💎</span>
                        <p class="text-xs font-semibold text-ivoire-text mt-1">Pierceur</p>
                    </div>
                </label>
            </div>
        </div>
        <button type="submit" class="w-full py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
            Envoyer l'invitation
        </button>
    </form>
</div>
@endsection
```

```bash
git add -A && git commit -m "feat(studio): vues artistes (liste + création + invitation)"
```

---

## PHASE 7 — PROFIL PUBLIC STUDIO

Créer `resources/views/studio/public-profile.blade.php` — visible par tous à l'URL `/salon/{slug}`.

```blade
{{-- Pas de layout studio ici, utiliser le layout public/marketplace --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 py-6 px-4">
    {{-- Cover --}}
    @if ($studio->getFirstMediaUrl('cover'))
        <div class="relative rounded-2xl overflow-hidden h-48 sm:h-64">
            <img src="{{ $studio->getFirstMediaUrl('cover') }}" alt="{{ $studio->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 to-transparent"></div>
            <div class="absolute bottom-4 left-4 flex items-center gap-3">
                @if ($studio->getFirstMediaUrl('logo'))
                    <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="Logo" class="w-16 h-16 rounded-xl object-cover border-2 border-beige-peau">
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text">{{ $studio->name }}</h1>
                    <p class="text-sm text-ivoire-text/80">{{ $studio->city }}{{ $studio->postal_code ? ' (' . $studio->postal_code . ')' : '' }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="flex items-center gap-3">
            @if ($studio->getFirstMediaUrl('logo'))
                <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="Logo" class="w-16 h-16 rounded-xl object-cover">
            @endif
            <div>
                <h1 class="text-2xl font-bold text-ivoire-text">{{ $studio->name }}</h1>
                <p class="text-sm text-titane">{{ $studio->city }}{{ $studio->postal_code ? ' (' . $studio->postal_code . ')' : '' }}</p>
            </div>
        </div>
    @endif

    {{-- Description --}}
    @if ($studio->description)
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-sm text-ivoire-text leading-relaxed">{{ $studio->description }}</p>
        </div>
    @endif

    {{-- Infos pratiques --}}
    <div class="flex flex-wrap gap-3">
        @if ($studio->address)
            <div class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2">
                <span class="text-sm">📍</span>
                <span class="text-sm text-ivoire-text">{{ $studio->address }}, {{ $studio->city }} {{ $studio->postal_code }}</span>
            </div>
        @endif
        @if ($studio->phone)
            <a href="tel:{{ $studio->phone }}" class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2 hover:bg-gris-fonde/80">
                <span class="text-sm">📞</span>
                <span class="text-sm text-ivoire-text">{{ $studio->phone }}</span>
            </a>
        @endif
        @if ($studio->website)
            <a href="{{ $studio->website }}" target="_blank" class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2 hover:bg-gris-fonde/80">
                <span class="text-sm">🌐</span>
                <span class="text-sm text-beige-peau">Site web</span>
            </a>
        @endif
    </div>

    {{-- Artistes du studio --}}
    <section>
        <h2 class="text-lg font-bold text-ivoire-text mb-4">👥 Nos artistes</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($artists as $sa)
                @if ($sa->user && $sa->is_active)
                    @php $artisan = $sa->user->artisan(); @endphp
                    <a href="{{ $artisan?->getProfileUrl() ?? '#' }}" 
                        class="bg-gris-fonde rounded-xl overflow-hidden hover:ring-2 hover:ring-beige-peau/50 transition-all group">
                        {{-- Photo portfolio ou avatar --}}
                        <div class="h-40 bg-noir-profond overflow-hidden">
                            @php
                                $imageUrl = $artisan?->getFirstMediaUrl('portfolio') 
                                    ?: $sa->user->getFirstMediaUrl('avatar')
                                    ?: asset('images/default-avatar.png');
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $sa->user->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $sa->user->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}" 
                                    alt="" class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <p class="text-sm font-semibold text-ivoire-text">{{ $sa->user->name }}</p>
                                    <p class="text-xs text-titane">
                                        {{ $sa->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            @empty
                <p class="text-sm text-titane col-span-full text-center py-8">Aucun artiste pour le moment</p>
            @endforelse
        </div>
    </section>

    {{-- Horaires --}}
    @if ($studio->opening_hours && count($studio->opening_hours) > 0)
        <section class="bg-gris-fonde rounded-xl p-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">🕐 Horaires</h2>
            <div class="space-y-1">
                @foreach ($studio->opening_hours as $day => $hours)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-ivoire-text capitalize">{{ $day }}</span>
                        <span class="text-titane">
                            @if (!empty($hours['closed']) && $hours['closed'])
                                Fermé
                            @elseif (!empty($hours['open']) && !empty($hours['close']))
                                {{ $hours['open'] }} — {{ $hours['close'] }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Photos du salon --}}
    @if ($studio->getMedia('photos')->count() > 0)
        <section>
            <h2 class="text-lg font-bold text-ivoire-text mb-4">📸 Le salon</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach ($studio->getMedia('photos') as $photo)
                    <img src="{{ $photo->getUrl() }}" alt="Photo salon" 
                        class="w-full h-32 sm:h-40 rounded-lg object-cover hover:opacity-90 transition-opacity cursor-pointer">
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
```

```bash
git add -A && git commit -m "feat(studio): profil public salon avec artistes + photos + horaires"
```

---

## PHASE 8 — VUES PLACEHOLDER (Planning, Billing, Stats)

Pour ces vues, créer des placeholders fonctionnels qu'on enrichira dans les prompts 3 et 4.

### 8A. Planning global

```blade
{{-- resources/views/studio/planning.blade.php --}}
@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Planning</h1>
    <p class="text-sm text-titane">Vue globale des rendez-vous de tous vos artistes</p>
    
    {{-- TODO: Calendrier global avec les RDV de tous les artistes --}}
    <div class="bg-gris-fonde rounded-xl p-6 text-center">
        <p class="text-sm text-titane">📅 Le planning global sera disponible prochainement.</p>
        <p class="text-xs text-titane/60 mt-2">En attendant, chaque artiste peut gérer son propre calendrier depuis son dashboard.</p>
    </div>
</div>
@endsection
```

### 8B. Billing

```blade
{{-- resources/views/studio/billing.blade.php --}}
@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Facturation</h1>
    
    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
        <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-4">Récapitulatif</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-ivoire-text">Abonnement Studio</span>
                <span class="text-sm font-semibold text-ivoire-text">79,99€/mois</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-ivoire-text">Artistes inclus</span>
                <span class="text-sm text-titane">1</span>
            </div>
            @if ($paidArtistCount > 0)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-ivoire-text">Artistes supplémentaires ({{ $paidArtistCount }})</span>
                    <span class="text-sm font-semibold text-beige-peau">{{ number_format($paidArtistCount * 39.99, 2) }}€/mois</span>
                </div>
            @endif
            <div class="border-t border-titane/20 pt-3 flex justify-between items-center">
                <span class="text-sm font-bold text-ivoire-text">Total mensuel</span>
                <span class="text-lg font-bold text-beige-peau">{{ number_format($monthlyPrice, 2) }}€</span>
            </div>
        </div>
    </div>

    {{-- TODO (Prompt 4) : Stripe Customer Portal, historique factures, changement de plan --}}
    <div class="bg-gris-fonde/50 rounded-xl p-4 border border-titane/10">
        <p class="text-xs text-titane">💡 La gestion complète de la facturation (historique, factures, moyens de paiement) sera disponible prochainement via Stripe.</p>
    </div>
</div>
@endsection
```

### 8C. Stats

```blade
{{-- resources/views/studio/stats.blade.php --}}
@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Statistiques</h1>
    
    {{-- TODO (Prompt 4) : Stats globales via Filament widgets --}}
    <div class="bg-gris-fonde rounded-xl p-6 text-center">
        <p class="text-sm text-titane">📈 Les statistiques détaillées seront accessibles depuis votre panel de gestion avancé.</p>
    </div>
</div>
@endsection
```

```bash
git add -A && git commit -m "feat(studio): vues placeholder planning, billing, stats"
```

---

## PHASE 9 — INVITATION (vue publique)

Créer `resources/views/studio/accept-invitation.blade.php` — la page que voit l'artiste invité quand il clique sur le lien d'invitation.

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-12 px-4">
    <div class="bg-gris-fonde rounded-2xl p-6 space-y-4">
        {{-- Logo studio --}}
        @if ($studio->getFirstMediaUrl('logo'))
            <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="{{ $studio->name }}" class="w-16 h-16 rounded-xl object-cover mx-auto">
        @endif
        
        <div class="text-center">
            <h1 class="text-xl font-bold text-ivoire-text">Invitation</h1>
            <p class="text-sm text-titane mt-1">
                <strong class="text-beige-peau">{{ $studio->name }}</strong> vous invite à rejoindre son studio en tant que 
                <strong class="text-ivoire-text">{{ $invitation->artisan_type === 'piercer' ? 'Pierceur' : 'Tatoueur' }}</strong>.
            </p>
        </div>

        <form action="{{ route('studio.invitation.process', $invitation->invitation_token) }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="text-xs text-titane block mb-1">Nom complet *</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                @error('name') <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Email *</label>
                <input type="email" name="email" required value="{{ old('email', $invitation->invitation_email) }}"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                @error('email') <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Mot de passe *</label>
                <input type="password" name="password" required
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                @error('password') <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Confirmer le mot de passe *</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
            </div>
            <button type="submit" class="w-full py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
                Rejoindre le studio
            </button>
        </form>
    </div>
</div>
@endsection
```

```bash
git add -A && git commit -m "feat(studio): page acceptation invitation artiste"
```

---

## PHASE 10 — VÉRIFICATION FINALE

```bash
# 10A. Vues créées
find resources/views/studio -type f | sort
find resources/views/layouts -name "*studio*" | sort

# 10B. Routes compilent
php artisan route:list --name="studio" 2>&1 | head -5
php artisan route:list 2>&1 | head -3

# 10C. Vues compilent
php artisan view:clear
php artisan view:cache 2>&1 | head -3

# 10D. Layout existe
ls -la resources/views/layouts/studio.blade.php

# 10E. Inscription studio fonctionne ?
grep -n "studio" resources/views/auth/register.blade.php | head -5

echo "=== PROMPT 2/4 STUDIO — DASHBOARD ET VUES TERMINÉS ==="
```

---

## ⚠️ RÈGLES

1. **Adapter aux fichiers existants** — Si des vues Livewire existent déjà, ADAPTER au lieu de recréer en Blade
2. **Copier le pattern du layout tattooer** — Même structure HTML/CSS/Alpine.js pour la cohérence UX
3. **payment_mode** (pas payment_model) — Le nom de colonne existant est `payment_mode`
4. **Design system** : noir-profond, gris-fonde, beige-peau, ivoire-text, titane, rouge-alerte, vert-validation
5. **Mobile-first** : Toutes les vues doivent être responsives
6. **Commit après chaque phase**
