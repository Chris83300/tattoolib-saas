# 🔧 FIX STUDIO — 5 corrections post-test
# Pour Claude Code — Commit après chaque fix

## CONTEXTE

Le studio est fonctionnel mais 5 problèmes ont été identifiés lors des tests.

Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, MySQL, Stripe Connect + Cashier, Spatie Media Library.

### LES 5 FIXES
1. Trial limité à 1 artiste (l'inclus) — bloquer création supplémentaire sans abonnement
2. Artiste studio = PRO automatiquement (pas de commission 7%)
3. Fiches clients + traçabilité liées au studio (pas à l'artiste) — lecture+écriture studio ET artiste
4. Stripe Connect centralisé/distribué : bien câbler le flow artiste
5. SIRET dans settings artiste : obligatoire si distribué, optionnel si centralisé

---

## PHASE 0 — AUDIT GLOBAL

```bash
# 0A. canAddArtist() actuel
grep -A 10 "function canAddArtist" app/Models/Studio.php

# 0B. Plan artiste — comment le plan est géré
grep -n "plan\|is_pro\|isPro\|commission" app/Models/Tattooer.php | head -10
grep -n "plan\|is_pro\|isPro\|commission" app/Models/Piercer.php | head -10
grep -n "plan\|is_pro\|isPro\|commission" app/Models/Traits/IsArtisan.php | head -10

# 0C. Colonnes plan sur tattooers/piercers
php artisan tinker --execute="
  echo 'tattooers.plan: ' . (Schema::hasColumn('tattooers', 'plan') ? 'EXISTS' : 'ABSENT');
  echo PHP_EOL . 'tattooers.is_pro: ' . (Schema::hasColumn('tattooers', 'is_pro') ? 'EXISTS' : 'ABSENT');
  echo PHP_EOL . 'piercers.plan: ' . (Schema::hasColumn('piercers', 'plan') ? 'EXISTS' : 'ABSENT');
  echo PHP_EOL . 'piercers.is_pro: ' . (Schema::hasColumn('piercers', 'is_pro') ? 'EXISTS' : 'ABSENT');
"

# 0D. Fiches clients — structure actuelle
php artisan tinker --execute="
  echo 'client_records: ' . (Schema::hasTable('client_records') ? implode(', ', Schema::getColumnListing('client_records')) : 'TABLE ABSENTE');
"
# Si le nom de table est différent :
grep -rn "class.*ClientRecord\|class.*ClientFile\|class.*ClientSheet\|class.*Fiche" app/Models/ --include="*.php" | head -10

# 0E. Traçabilité — tables
php artisan tinker --execute="
  \$tables = ['traceability_records', 'traceability_needles', 'traceability_inks'];
  foreach(\$tables as \$t) {
    echo \$t . ': ' . (Schema::hasTable(\$t) ? implode(', ', Schema::getColumnListing(\$t)) : 'ABSENT') . PHP_EOL;
  }
"
# Si les noms sont différents :
grep -rn "class.*Traceability\|class.*Tracabilite\|class.*TraceRecord" app/Models/ --include="*.php" | head -10

# 0F. studio_id sur ces tables ?
php artisan tinker --execute="
  \$tables = ['client_records', 'traceability_records', 'traceability_needles', 'traceability_inks'];
  foreach(\$tables as \$t) {
    if (Schema::hasTable(\$t)) {
      echo \$t . '.studio_id: ' . (Schema::hasColumn(\$t, 'studio_id') ? 'EXISTS' : 'ABSENT') . PHP_EOL;
    }
  }
"

# 0G. StudioController storeArtist — comment l'artiste est créé
grep -A 40 "function storeArtist" app/Http/Controllers/StudioController.php

# 0H. Stripe Connect dans le layout artiste
grep -n "stripe\|Stripe\|onboarding\|connect" resources/views/layouts/tattooer.blade.php | head -10
grep -rn "needsOwnStripeConnect\|getStripeAccountId" resources/views/ --include="*.blade.php" | head -10

# 0I. Settings artiste — SIRET existant ?
grep -n "siret\|SIRET" resources/views/livewire/tattooer/settings.blade.php resources/views/tattooer/settings.blade.php 2>/dev/null | head -5
grep -n "siret\|SIRET" app/Livewire/Tattooer/Settings.php 2>/dev/null | head -5

# 0J. Comment isPro() est déterminé
grep -A 10 "function isPro\|function is_pro\|function commission" app/Models/Traits/IsArtisan.php app/Models/Tattooer.php | head -20
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX 1 — TRIAL LIMITÉ À 1 ARTISTE

### Logique

En période d'essai (trial actif, pas d'abonnement), le studio ne peut avoir qu'1 seul artiste actif (celui inclus dans le forfait). Pour en ajouter plus → il doit souscrire à l'abonnement.

### 1A. Modifier canAddArtist() dans Studio model

```bash
grep -n "function canAddArtist\|function artistCount\|function includedArtists" app/Models/Studio.php
```

Remplacer `canAddArtist()` par :

```php
/**
 * Peut ajouter un artiste ?
 * - En trial : max 1 artiste (l'inclus)
 * - Avec abonnement : illimité (ou max_artists si défini)
 */
public function canAddArtist(): bool
{
    $currentCount = $this->artistCount();
    
    // En période d'essai sans abonnement : max 1 artiste
    if ($this->onTrial() && !$this->hasActiveSubscription()) {
        return $currentCount < 1;
    }
    
    // Trial expiré sans abonnement : aucun ajout
    if ($this->trialExpired()) {
        return false;
    }
    
    // Avec abonnement actif : vérifier la limite contractuelle
    if ($this->max_artists !== null) {
        return $currentCount < $this->max_artists;
    }
    
    return true; // Abonnement actif, pas de limite
}

/**
 * Le studio doit-il souscrire pour ajouter un artiste ?
 * True si trial avec déjà 1 artiste, ou trial expiré.
 */
public function needsSubscriptionForNewArtist(): bool
{
    if ($this->hasActiveSubscription()) return false;
    
    // Trial avec déjà 1 artiste
    if ($this->onTrial() && $this->artistCount() >= 1) return true;
    
    // Trial expiré
    if ($this->trialExpired()) return true;
    
    return false;
}
```

### 1B. Adapter le controller storeArtist() et inviteArtist()

Dans `StudioController::storeArtist()` et `inviteArtist()`, après le `abort_unless($studio->canAddArtist(), 403)` :

```php
// Si le studio doit souscrire pour ajouter un artiste
if ($studio->needsSubscriptionForNewArtist()) {
    return redirect()->route('studio.subscribe')
        ->with('info', 'Votre essai inclut 1 artiste. Activez votre abonnement pour en ajouter davantage.');
}
```

### 1C. Adapter la vue artistes (artists-create et artists)

Dans la vue de création d'artiste, si le studio ne peut pas ajouter :

```bash
grep -rn "canAddArtist\|can_add_artist" resources/views/ --include="*.blade.php" | head -5
```

Remplacer le bloc conditionnel existant. Si `canAddArtist` est false ET `needsSubscriptionForNewArtist` est true :

```blade
@if ($studio->canAddArtist())
    {{-- Formulaire création/invitation existant --}}
@elseif ($studio->needsSubscriptionForNewArtist())
    <div class="bg-gris-fonde rounded-xl p-6 text-center space-y-4">
        <div class="text-4xl">🔒</div>
        <h2 class="text-lg font-bold text-ivoire-text">Artiste supplémentaire</h2>
        <p class="text-sm text-titane">
            @if ($studio->onTrial())
                Votre essai gratuit inclut <strong class="text-ivoire-text">1 artiste</strong>.<br>
                Activez votre abonnement pour ajouter des artistes supplémentaires à <strong class="text-beige-peau">39,99€/mois</strong> chacun.
            @else
                Votre essai est terminé. Activez votre abonnement pour continuer.
            @endif
        </p>
        <a href="{{ route('studio.subscribe') }}"
            class="inline-block px-6 py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
            Activer l'abonnement — 79,99€/mois
        </a>
    </div>
@endif
```

Passer `$studio` dans les données de la vue si ce n'est pas déjà fait.

Dans la liste des artistes, adapter le bouton "Ajouter" :

```blade
@if ($studio->canAddArtist())
    <a href="{{ route('studio.artists.create') }}" class="px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm">+ Ajouter</a>
@elseif ($studio->needsSubscriptionForNewArtist())
    <a href="{{ route('studio.subscribe') }}" class="px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm">🔓 Souscrire pour ajouter</a>
@endif
```

```bash
git add -A && git commit -m "fix(studio): trial limité à 1 artiste, redirection subscribe si besoin"
```

---

## FIX 2 — ARTISTE STUDIO = PRO AUTOMATIQUEMENT

Un artiste créé par un studio est TOUJOURS en plan PRO :
- Pas de commission 7% (application_fee)
- Accès à toutes les features PRO (fiches clients, traçabilité, etc.)
- C'est le studio qui paie l'abonnement, pas l'artiste

### 2A. Forcer le plan PRO à la création

Dans `StudioController::storeArtist()`, quand le profil Tattooer/Piercer est créé :

```bash
grep -A 15 "Tattooer::create\|Piercer::create" app/Http/Controllers/StudioController.php
```

Ajouter le plan PRO :

```php
// Création tattooer
if ($validated['artisan_type'] === 'piercer') {
    Piercer::create([
        'user_id' => $user->id,
        'studio_id' => $studio->id,
        'plan' => 'pro',  // ← AJOUTER : artiste studio = PRO
    ]);
} else {
    Tattooer::create([
        'user_id' => $user->id,
        'studio_id' => $studio->id,
        'plan' => 'pro',  // ← AJOUTER : artiste studio = PRO
    ]);
}
```

IMPORTANT : vérifier le nom exact de la colonne/valeur (plan, is_pro, subscription_plan...) :
```bash
grep -n "plan\|is_pro" app/Models/Tattooer.php | head -10
```

Adapter selon ce qui existe. Si c'est `is_pro` (boolean) → mettre `'is_pro' => true`. Si c'est `plan` (string) → mettre `'plan' => 'pro'`.

### 2B. Même chose dans processInvitation()

```bash
grep -A 15 "Tattooer::create\|Piercer::create" app/Http/Controllers/StudioController.php | grep -A 3 "processInvitation" 
# Si les créations sont dans processInvitation aussi :
grep -B 5 -A 15 "artisanModel::create\|Tattooer::create\|Piercer::create" app/Http/Controllers/StudioController.php
```

Ajouter `'plan' => 'pro'` (ou `'is_pro' => true`) à chaque création dans processInvitation().

### 2C. Forcer isPro() pour les artistes studio

Pour être doublement sûr, dans le trait IsArtisan ou dans les models Tattooer/Piercer, override isPro() :

```php
/**
 * Un artiste studio est TOUJOURS PRO.
 */
public function isPro(): bool
{
    // Artiste studio = toujours PRO (le studio paie)
    if ($this->studio_id) {
        return true;
    }
    
    // Artiste indépendant : vérifier le plan normal
    // ... logique existante ...
}
```

IMPORTANT : trouver la méthode isPro() existante et AJOUTER la condition studio_id au début, sans casser la logique pour les indépendants.

### 2D. Commission à 0% pour artiste studio

Vérifier comment l'application_fee est calculée :

```bash
grep -rn "application_fee\|commission\|getCommission\|getFeePercent" app/ --include="*.php" | head -15
```

S'assurer que si `isPro() === true`, l'application_fee est 0. Si ce n'est pas déjà le cas, ajouter la vérification.

```bash
git add -A && git commit -m "fix(studio): artiste studio = PRO automatique, 0% commission"
```

---

## FIX 3 — FICHES CLIENTS + TRAÇABILITÉ LIÉES AU STUDIO

Les fiches clients et la traçabilité sont liées au STUDIO (pas à l'artiste individuel).
Le studio ET ses artistes ont tous lecture + écriture.

### 3A. Identifier les tables et models

```bash
# Fiches clients
grep -rn "class.*Client.*Record\|class.*Client.*File\|class.*Fiche" app/Models/ --include="*.php" | head -5
# Traçabilité
grep -rn "class.*Trace\|class.*Tracab" app/Models/ --include="*.php" | head -5
```

Identifier les noms exacts des tables et models. Les exemples ci-dessous utilisent des noms génériques — adapter aux noms réels.

### 3B. Ajouter studio_id aux tables

Pour CHAQUE table concernée (fiches clients + traçabilité), si studio_id n'existe pas :

```bash
php artisan make:migration add_studio_id_to_client_and_traceability_tables
```

```php
public function up(): void
{
    // Adapter les noms de tables aux vrais noms trouvés en audit
    $tables = ['client_records', 'traceability_records', 'traceability_needles', 'traceability_inks'];
    
    foreach ($tables as $table) {
        if (Schema::hasTable($table) && !Schema::hasColumn($table, 'studio_id')) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('studio_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }
    }
}
```

IMPORTANT : studio_id est NULLABLE car les artistes indépendants n'ont pas de studio. Seuls les records créés dans un contexte studio ont un studio_id.

### 3C. Adapter les Models

Pour chaque model concerné (ClientRecord, TraceabilityRecord, etc.), ajouter :

```php
// Relation
public function studio()
{
    return $this->belongsTo(\App\Models\Studio::class);
}
```

Et ajouter `'studio_id'` dans `$fillable`.

### 3D. Auto-fill studio_id à la création

Quand un artiste studio crée une fiche client ou un record de traçabilité, le studio_id doit être rempli automatiquement.

Trouver les controllers/Livewire qui créent ces records :

```bash
# Fiches clients
grep -rn "ClientRecord::create\|client_record.*create\|->create.*client" app/ --include="*.php" | head -10
# Traçabilité
grep -rn "TraceabilityRecord::create\|traceability.*create\|->create.*trace" app/ --include="*.php" | head -10
```

Pour CHAQUE endroit de création, ajouter :

```php
// Déterminer le studio_id
$studioId = null;
$artisan = auth()->user()->artisan();
if ($artisan && $artisan->studio_id) {
    $studioId = $artisan->studio_id;
}

// À la création du record :
$record = ClientRecord::create([
    // ... champs existants ...
    'studio_id' => $studioId,
]);
```

OU mieux, un helper réutilisable dans le trait IsArtisan :

```php
// Dans le trait IsArtisan, ajouter :
public function getStudioIdForRecords(): ?int
{
    return $this->studio_id;
}
```

### 3E. Scoping : le studio voit les fiches de TOUS ses artistes

Quand le studio accède aux fiches clients ou traçabilité, il doit voir TOUS les records de ses artistes.

Dans le StudioController ou les composants Livewire du studio, les queries doivent être scopées par studio_id :

```php
// Au lieu de filtrer par artiste :
$records = ClientRecord::where('studio_id', $studio->id)->get();
$traceRecords = TraceabilityRecord::where('studio_id', $studio->id)->get();
```

### 3F. Scoping : l'artiste studio voit les fiches du studio

Quand un artiste studio accède à ses fiches, il doit voir les fiches du STUDIO (pas seulement les siennes).

Trouver où les fiches sont chargées pour l'artiste :

```bash
grep -rn "clientRecords\|client_records\|fiches\|ClientRecord::where" app/ --include="*.php" | grep -v "migration\|Model" | head -15
```

Adapter la query :

```php
// AVANT (artiste indépendant) :
$records = ClientRecord::where('tattooer_id', $artisan->id)->get();

// APRÈS (compatible studio + indépendant) :
if ($artisan->studio_id) {
    // Artiste studio : voir toutes les fiches du studio
    $records = ClientRecord::where('studio_id', $artisan->studio_id)->get();
} else {
    // Artiste indépendant : voir ses propres fiches
    $records = ClientRecord::where('tattooer_id', $artisan->id)->get();
}
```

IMPORTANT : Ce pattern doit être appliqué PARTOUT où les fiches et la traçabilité sont chargées. Utiliser un scope réutilisable :

```php
// Dans les models de fiches/traçabilité, ajouter un scope :
public function scopeForArtisan($query, $artisan)
{
    if ($artisan->studio_id) {
        return $query->where('studio_id', $artisan->studio_id);
    }
    // Adapter selon le type (tattooer_id ou bookable polymorphique)
    return $query->where('tattooer_id', $artisan->id);
}
```

### 3G. Ajouter les fiches clients et traçabilité au dashboard studio

Le studio doit pouvoir accéder aux fiches clients et à la traçabilité depuis son dashboard classique.

Ajouter dans les routes studio :

```php
Route::get('/fiches-clients', [StudioController::class, 'clientRecords'])->name('client-records');
Route::get('/tracabilite', [StudioController::class, 'traceability'])->name('traceability');
```

Ajouter dans le StudioController :

```php
public function clientRecords()
{
    $studio = $this->studio();
    $records = \App\Models\ClientRecord::where('studio_id', $studio->id)
        ->with(['client.user', 'tattooer.user'])
        ->latest()
        ->paginate(20);

    return view('studio.client-records', [
        'studio' => $studio,
        'records' => $records,
    ]);
}

public function traceability()
{
    $studio = $this->studio();
    $records = \App\Models\TraceabilityRecord::where('studio_id', $studio->id)
        ->with(['tattooer.user'])
        ->latest()
        ->paginate(20);

    return view('studio.traceability', [
        'studio' => $studio,
        'records' => $records,
    ]);
}
```

IMPORTANT : Adapter les noms de Models et relations aux vrais noms trouvés lors de l'audit Phase 0.

Créer les vues (même style que la vue demandes existante — liste avec pagination) :

```blade
{{-- resources/views/studio/client-records.blade.php --}}
@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Fiches clients</h1>
    <p class="text-sm text-titane">Toutes les fiches clients du studio</p>

    <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
        @forelse ($records as $record)
            <div class="p-4 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate">{{ $record->client?->user?->name ?? 'Client' }}</p>
                    <p class="text-xs text-titane">
                        Artiste : {{ $record->tattooer?->user?->name ?? '—' }}
                        • {{ $record->created_at?->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        @empty
            <p class="text-sm text-titane text-center py-8">Aucune fiche client</p>
        @endforelse
    </div>
    {{ $records->links() }}
</div>
@endsection
```

```blade
{{-- resources/views/studio/traceability.blade.php --}}
@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Traçabilité</h1>
    <p class="text-sm text-titane">Historique de traçabilité de tous les artistes du studio</p>

    <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
        @forelse ($records as $record)
            <div class="p-4 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate">
                        {{ $record->client?->user?->name ?? 'Client' }}
                    </p>
                    <p class="text-xs text-titane">
                        Artiste : {{ $record->tattooer?->user?->name ?? '—' }}
                        • {{ $record->created_at?->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        @empty
            <p class="text-sm text-titane text-center py-8">Aucun enregistrement de traçabilité</p>
        @endforelse
    </div>
    {{ $records->links() }}
</div>
@endsection
```

Ajouter les liens dans la navigation sidebar du layout studio :

```blade
<a href="{{ route('studio.client-records') }}" class="...">📋 Fiches clients</a>
<a href="{{ route('studio.traceability') }}" class="...">🔍 Traçabilité</a>
```

### 3H. Ajouter les Resources Filament studio

Dans le panel Filament studio (/studio/admin), ajouter des Resources pour les fiches clients et la traçabilité, scopées par studio_id.

Créer les dossiers :
```bash
mkdir -p app/Filament/Studio/Resources/ClientRecordResource/{Pages,Schemas,Tables}
mkdir -p app/Filament/Studio/Resources/TraceabilityRecordResource/{Pages,Schemas,Tables}
```

Suivre le MÊME pattern que StudioArtistResource et BookingRequestResource (architecture Filament v4 avec Schemas/ et Tables/ séparés). Scoper avec `getEloquentQuery()` :

```php
public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    $studio = auth()->user()->studio;
    if (!$studio) {
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
    return parent::getEloquentQuery()->where('studio_id', $studio->id);
}
```

IMPORTANT : vérifier le pattern exact des Resources Filament v4 existantes dans le projet et copier la même structure.

```bash
git add -A && git commit -m "fix(studio): fiches clients + traçabilité liées au studio, lecture+écriture studio ET artiste"
```

---

## FIX 4 — STRIPE CONNECT CENTRALISÉ/DISTRIBUÉ BIEN CÂBLÉ

### 4A. Vérifier l'état actuel

```bash
# getStripeAccountId et needsOwnStripeConnect
grep -A 15 "function getStripeAccountId\|function needsOwnStripeConnect" app/Models/Traits/IsArtisan.php app/Models/Tattooer.php app/Models/Piercer.php 2>/dev/null

# Dashboard artiste — section Stripe
grep -rn "stripe\|Stripe\|onboarding\|connect" resources/views/livewire/tattooer/settings.blade.php resources/views/tattooer/settings.blade.php 2>/dev/null | head -10
grep -rn "stripe\|Stripe\|onboarding\|connect" resources/views/layouts/tattooer.blade.php | head -10
```

### 4B. Dashboard artiste : message clair selon le mode

Dans les settings ou le dashboard de l'artiste, la section Stripe doit être adaptée :

**Si centralisé** (studio encaisse) :
```blade
<div class="bg-gris-fonde rounded-xl p-4">
    <p class="text-sm text-ivoire-text font-semibold">💳 Paiements gérés par le studio</p>
    <p class="text-xs text-titane mt-1">
        Les paiements clients sont encaissés par <strong class="text-beige-peau">{{ $artisan->studio?->name }}</strong>. 
        Vous n'avez pas besoin de configurer Stripe Connect.
    </p>
</div>
```

**Si distribué** (artiste encaisse) :
```blade
{{-- Afficher le flow Stripe Connect normal --}}
@if (!$artisan->stripe_account_id)
    <div class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-4">
        <p class="text-sm text-orange-400 font-semibold">⚠️ Stripe Connect requis</p>
        <p class="text-xs text-titane mt-1">
            Votre studio a choisi le paiement par artiste. Vous devez configurer votre compte Stripe Connect pour recevoir les paiements clients.
        </p>
        <a href="{{ route($routePrefix . '.stripe.onboarding') }}" 
            class="mt-3 inline-block px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold">
            Configurer Stripe Connect
        </a>
    </div>
@else
    <div class="bg-vert-validation/10 border border-vert-validation/30 rounded-xl p-4">
        <p class="text-sm text-vert-validation font-semibold">✅ Stripe Connect actif</p>
        <p class="text-xs text-titane mt-1">Vous recevez les paiements directement sur votre compte.</p>
    </div>
@endif
```

Le conditionnel dans la vue :

```blade
@if ($artisan->isStudioArtist())
    @if (!$artisan->needsOwnStripeConnect())
        {{-- Centralisé : message studio gère --}}
    @else
        {{-- Distribué : Stripe Connect flow --}}
    @endif
@else
    {{-- Artiste indépendant : Stripe Connect flow normal --}}
@endif
```

### 4C. Bloquer l'onboarding Stripe si centralisé

Dans le controller qui gère l'onboarding Stripe de l'artiste :

```bash
grep -rn "onboarding\|stripeOnboarding\|createStripeAccount" app/Http/Controllers/ --include="*.php" | head -10
```

Ajouter une vérification :

```php
// Au début de la méthode d'onboarding Stripe artiste
$artisan = auth()->user()->artisan();
if ($artisan && !$artisan->needsOwnStripeConnect()) {
    return redirect()->back()
        ->with('info', 'Les paiements sont gérés par votre studio. Vous n\'avez pas besoin de Stripe Connect.');
}
```

```bash
git add -A && git commit -m "fix(studio): Stripe Connect adapté centralisé/distribué dans le flow artiste"
```

---

## FIX 5 — SIRET DANS SETTINGS ARTISTE

Le SIRET est utile pour les artistes studio car ils sont souvent auto-entrepreneurs.
- **Obligatoire** si le studio est en mode distribué (artiste encaisse → besoin de SIRET pour Stripe/facturation)
- **Optionnel** si le studio est en mode centralisé (studio encaisse)
- Pour les artistes indépendants : le SIRET est déjà géré à l'inscription

### 5A. Vérifier si le champ existe

```bash
# Colonne siret sur tattooers/piercers
php artisan tinker --execute="
  echo 'tattooers.siret: ' . (Schema::hasColumn('tattooers', 'siret') ? 'EXISTS' : 'ABSENT');
  echo PHP_EOL . 'piercers.siret: ' . (Schema::hasColumn('piercers', 'siret') ? 'EXISTS' : 'ABSENT');
"

# Dans les settings actuels
grep -n "siret\|SIRET" resources/views/livewire/tattooer/settings.blade.php resources/views/tattooer/settings.blade.php 2>/dev/null
```

### 5B. Migration si colonne absente

Si `siret` n'existe pas sur tattooers/piercers :

```bash
php artisan make:migration add_siret_to_artisan_tables
```

```php
public function up(): void
{
    if (!Schema::hasColumn('tattooers', 'siret')) {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->string('siret', 14)->nullable()->after('studio_id');
        });
    }
    if (!Schema::hasColumn('piercers', 'siret')) {
        Schema::table('piercers', function (Blueprint $table) {
            $table->string('siret', 14)->nullable()->after('studio_id');
        });
    }
}
```

Ajouter `'siret'` dans $fillable des models Tattooer et Piercer.

### 5C. Ajouter le champ dans la vue settings artiste

Trouver la vue settings de l'artiste :
```bash
find resources/views -path "*tattooer*settings*" -o -path "*tattooer*setting*" | head -5
find app/Livewire -path "*Tattooer*Settings*" -o -path "*Tattooer*Setting*" | head -5
```

Ajouter le champ SIRET dans la section informations :

```blade
{{-- Section SIRET --}}
@php
    $artisan = auth()->user()->artisan();
    $studio = $artisan?->studio;
    $siretRequired = $studio && $studio->payment_mode === 'distributed';
    $isStudioArtist = $artisan?->isStudioArtist() ?? false;
@endphp

@if ($isStudioArtist)
    <div class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-3">
        <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">📄 Informations légales</h2>
        
        <div>
            <label class="text-xs text-titane block mb-1">
                Numéro SIRET 
                @if ($siretRequired) 
                    <span class="text-rouge-alerte">*</span>
                @else
                    <span class="text-titane/60">(optionnel)</span>
                @endif
            </label>
            <input type="text" name="siret" value="{{ old('siret', $artisan->siret) }}" 
                maxlength="14" placeholder="14 chiffres"
                {{ $siretRequired ? 'required' : '' }}
                class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau">
            
            @if ($siretRequired)
                <p class="text-xs text-orange-400 mt-1">
                    ⚠️ Votre studio utilise le paiement par artiste. Le SIRET est requis pour recevoir les paiements via Stripe Connect.
                </p>
            @else
                <p class="text-xs text-titane/60 mt-1">
                    Les paiements sont gérés par votre studio. Le SIRET est optionnel mais recommandé pour votre comptabilité.
                </p>
            @endif
        </div>
    </div>
@endif
```

IMPORTANT : Si les settings sont en Livewire, adapter : utiliser `wire:model` au lieu de `name=`, et ajouter la propriété + validation dans le composant Livewire.

Si c'est un composant Livewire :
```php
// Dans le composant Settings, ajouter :
public ?string $siret = '';

public function mount()
{
    // ... existant ...
    $this->siret = auth()->user()->artisan()?->siret ?? '';
}

public function save() // ou update()
{
    // ... existant ...
    
    // Validation SIRET conditionnelle
    $artisan = auth()->user()->artisan();
    $studio = $artisan?->studio;
    $siretRequired = $studio && $studio->payment_mode === 'distributed';
    
    if ($siretRequired) {
        $this->validate(['siret' => 'required|string|size:14']);
    } else {
        $this->validate(['siret' => 'nullable|string|size:14']);
    }
    
    $artisan->update(['siret' => $this->siret]);
}
```

### 5D. Validation Stripe Connect : vérifier SIRET avant onboarding

Si l'artiste est en mode distribué et n'a pas de SIRET, bloquer l'onboarding Stripe :

```php
// Dans le controller d'onboarding Stripe
$artisan = auth()->user()->artisan();
$studio = $artisan?->studio;

if ($studio && $studio->payment_mode === 'distributed' && empty($artisan->siret)) {
    return redirect()->route($routePrefix . '.settings')
        ->with('error', 'Veuillez renseigner votre numéro SIRET avant de configurer Stripe Connect.');
}
```

```bash
git add -A && git commit -m "fix(studio): SIRET artiste - obligatoire si distribué, optionnel si centralisé"
```

---

## VÉRIFICATION FINALE

```bash
# V1. canAddArtist en trial
php artisan tinker --execute="
  \$s = App\Models\Studio::first();
  if (\$s) {
    echo 'onTrial: ' . (\$s->onTrial() ? 'true' : 'false');
    echo ' | artistCount: ' . \$s->artistCount();
    echo ' | canAddArtist: ' . (\$s->canAddArtist() ? 'true' : 'false');
    echo ' | needsSubscriptionForNewArtist: ' . (\$s->needsSubscriptionForNewArtist() ? 'true' : 'false');
  }
"

# V2. Artiste studio isPro
php artisan tinker --execute="
  \$sa = App\Models\StudioArtist::with('user')->first();
  if (\$sa && \$sa->user) {
    \$artisan = \$sa->user->artisan();
    if (\$artisan) {
      echo 'plan: ' . (\$artisan->plan ?? 'null');
      echo ' | isPro: ' . (\$artisan->isPro() ? 'true' : 'false');
      echo ' | studio_id: ' . \$artisan->studio_id;
    }
  }
"

# V3. studio_id sur les tables
php artisan tinker --execute="
  \$tables = ['client_records', 'traceability_records', 'traceability_needles', 'traceability_inks'];
  foreach(\$tables as \$t) {
    if (Schema::hasTable(\$t)) {
      echo \$t . '.studio_id: ' . (Schema::hasColumn(\$t, 'studio_id') ? 'OK' : 'ABSENT') . PHP_EOL;
    }
  }
"

# V4. SIRET colonne
php artisan tinker --execute="
  echo 'tattooers.siret: ' . (Schema::hasColumn('tattooers', 'siret') ? 'OK' : 'ABSENT');
  echo PHP_EOL . 'piercers.siret: ' . (Schema::hasColumn('piercers', 'siret') ? 'OK' : 'ABSENT');
"

# V5. Routes
php artisan route:list --name="studio" 2>&1 | wc -l

# V6. Compilation
php artisan view:clear
php artisan route:list 2>&1 | head -3

echo "=== 5 FIXES STUDIO — TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire, les noms de tables/models peuvent différer
2. **Ne pas casser les artistes indépendants** — Toutes les modifications doivent être compatibles
3. **payment_mode** (pas payment_model) — Nom de colonne existant
4. **Architecture Filament v4** — Schemas/ et Tables/ séparés pour les nouvelles Resources
5. **Commit après chaque fix** (5 commits au total)
6. **scopeForArtisan** — Pattern réutilisable pour le scoping fiches/traçabilité
