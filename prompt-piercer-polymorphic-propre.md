# 🔧 IMPLÉMENTATION PROPRE DU PIERCEUR — Architecture Polymorphique
# Pour Claude Code — Exécution séquentielle
# Commit après chaque phase validée

## CONTEXTE

Ink&Pik, Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, MySQL, Stripe Connect, Laravel Cashier, Spatie Permission, Spatie Media Library, Filament v4.5.

Le système Tattooer est **100% fonctionnel** et sert de référence absolue.
BookingRequest utilise déjà le polymorphisme : `bookable_type` + `bookable_id` (morphTo).

### CE QUI A ÉTÉ MAL FAIT PAR CASCADE
Cascade a créé un système parallèle (composants Livewire dans `app/Livewire/Pierceur/`, routes séparées, etc.) au lieu de réutiliser l'infrastructure Tattooer. C'est un bordel — on repart de zéro pour le Pierceur.

### PRINCIPE FONDAMENTAL
Le Pierceur partage **90% du code** avec le Tattooer :
- Même dashboard, même sidebar, même layout
- Même système de messages, calendrier, clients, consentement
- Même workflow booking (demande → acompte → RDV → terminé/no-show)
- Même système de notifications, paiements, Stripe Connect

Les **10% de différences** :
1. **Portfolio** : catégories piercing (pas de before/after), grille tarifaire publique
2. **Traçabilité** : bijoux (marque, matière, taille, lot) au lieu d'encres + canules au lieu d'aiguilles
3. **Demande client** : types de piercing (lobe, hélix, septum, labret, nostril, industriel, etc.) au lieu de styles tattoo (blackwork, réaliste...)
4. **Soins** : consignes post-piercing différentes du post-tattoo
5. **Durée RDV** : 30min-1h au lieu de 2-6h (slots calendrier plus courts)
6. **Tarification** : prix fixe par type de piercing (grille tarifaire) + champ prix custom pour cas particuliers, au lieu de fourchette min-max

### APPROCHE TECHNIQUE
- **UN SEUL layout** : `layouts/artisan.blade.php` (renommage de `layouts/tattooer.blade.php` ou conditionnel)
- **UN SEUL jeu de controllers** : TattooerController adapté pour gérer les 2 types via un trait ou un helper `artisanType()`
- **Des vues partagées** avec des `@if` conditionnels pour les sections qui diffèrent
- **Le model Piercer** miroir du model Tattooer avec les mêmes relations + colonnes spécifiques

---

## PHASE 0 — NETTOYAGE DU BORDEL CASCADE (obligatoire avant tout)

```bash
# 0A. Inventaire de ce que Cascade a créé pour le pierceur
echo "=== LIVEWIRE PIERCEUR ==="
find app/Livewire/Pierceur -type f 2>/dev/null
echo "=== VUES PIERCEUR ==="
find resources/views/pierceur -type f 2>/dev/null
find resources/views/livewire/pierceur -type f 2>/dev/null
echo "=== CONTROLLER ==="
ls app/Http/Controllers/PiercerController.php 2>/dev/null
echo "=== LAYOUT ==="
ls resources/views/layouts/pierceur.blade.php 2>/dev/null
echo "=== ROUTES ==="
grep -n "pierceur\|piercer" routes/web.php
echo "=== MODEL ==="
ls app/Models/Piercer.php 2>/dev/null
cat app/Models/Piercer.php 2>/dev/null | head -50
echo "=== MIGRATION ==="
find database/migrations -name "*piercer*" -o -name "*pierceur*" 2>/dev/null
echo "=== TABLE BDD ==="
php artisan tinker --execute="
  echo 'Table piercers: ' . (Schema::hasTable('piercers') ? 'OUI (' . DB::table('piercers')->count() . ' rows)' : 'NON');
  if (Schema::hasTable('piercers')) {
    echo PHP_EOL . 'Colonnes: ' . implode(', ', Schema::getColumnListing('piercers'));
  }
"
```

**NE SUPPRIME RIEN ENCORE.** Montre-moi les résultats.

Ensuite, on garde UNIQUEMENT :
- `app/Models/Piercer.php` (on va le corriger)
- La migration + table `piercers` (on va la corriger)
- Les routes pierceur dans web.php (on va les réécrire)

On SUPPRIME :
- Tous les composants Livewire Pierceur (app/Livewire/Pierceur/)
- Toutes les vues pierceur séparées (resources/views/pierceur/, resources/views/livewire/pierceur/)
- Le layout pierceur séparé (resources/views/layouts/pierceur.blade.php)

```bash
# 0B. Supprimer les fichiers Cascade pierceur
rm -rf app/Livewire/Pierceur/
rm -rf resources/views/pierceur/
rm -rf resources/views/livewire/pierceur/
rm -f resources/views/layouts/pierceur.blade.php
rm -f app/Http/Controllers/PiercerController.php

git add -A && git commit -m "chore: supprimer implémentation pierceur Cascade (on repart propre)"
```

---

## PHASE 1 — MODEL PIERCER (miroir de Tattooer)

```bash
# 1A. Examiner le model Tattooer (référence absolue)
cat app/Models/Tattooer.php
```

Le model Piercer doit avoir :
- Les MÊMES relations que Tattooer (bookingRequests morphMany, clients, appointments, conversations, etc.)
- Les MÊMES traits (HasMedia, etc.)
- Les MÊMES scopes et helpers (isPro, isFree, isCompleted, etc.)
- Des colonnes SPÉCIFIQUES en plus pour le piercing

### 1B. Corriger le model Piercer

```php
// app/Models/Piercer.php
// DOIT implémenter les mêmes interfaces/traits que Tattooer
// DOIT avoir les mêmes relations morphMany que Tattooer
// Ajouter dans $fillable les champs spécifiques piercing
```

Copier la structure EXACTE de Tattooer.php, puis :
- Remplacer `tattooers` par `piercers` pour la table
- Ajouter les champs spécifiques : `pricing_grid` (JSON), `piercing_types` (JSON), `default_appointment_duration` (INT, default 45)
- Garder TOUTES les relations identiques (bookingRequests, clients, etc.) car elles utilisent bookable_type/bookable_id

### 1C. Vérifier/corriger la migration piercers

```bash
# Examiner la migration existante
find database/migrations -name "*piercer*" -exec cat {} \;
# Examiner la migration tattooers pour comparaison
find database/migrations -name "*tattooer*" | head -1 | xargs head -80
```

La table `piercers` doit avoir AU MINIMUM les mêmes colonnes que `tattooers` + les colonnes spécifiques.
Si des colonnes manquent → créer une migration d'ajout :

```bash
php artisan make:migration add_missing_columns_to_piercers_table
```

Colonnes requises (miroir de tattooers) :
- user_id (FK unique)
- bio, specialties (JSON), experience_years, city, address, postal_code
- siret, hygiene_certificate, ars_declaration_number
- stripe_account_id, stripe_onboarding_complete
- is_pro (BOOLEAN default false)
- aftercare_sheet (TEXT nullable)
- aftercare_reminder_2h, aftercare_reminder_7d, aftercare_reminder_14d (BOOLEAN default true)
- schedule/working_hours (JSON nullable)
- no_show_count (INT default 0)

Colonnes SPÉCIFIQUES piercer :
- pricing_grid (JSON nullable) — grille tarifaire [{type: 'lobe', price: 25}, {type: 'helix', price: 35}, ...]
- piercing_types (JSON nullable) — types proposés
- default_appointment_duration (INT default 45) — durée par défaut en minutes

```bash
php artisan migrate
php artisan tinker --execute="
  echo 'Colonnes piercers: ' . implode(', ', Schema::getColumnListing('piercers'));
"
git add -A && git commit -m "fix(model): Piercer miroir de Tattooer + colonnes spécifiques piercing"
```

---

## PHASE 2 — TRAIT ARTISAN PARTAGÉ

Pour éviter la duplication de code entre Tattooer et Piercer, créer un trait partagé.

```bash
# 2A. Examiner les méthodes communes de Tattooer
grep -n "public function" app/Models/Tattooer.php
```

### 2B. Créer le trait

```php
// app/Models/Traits/IsArtisan.php
namespace App\Models\Traits;

use App\Models\BookingRequest;
use App\Models\Appointment;
use App\Models\Conversation;
// etc.

trait IsArtisan
{
    /**
     * Retourne le type d'artisan : 'tattooer' ou 'piercer'
     */
    public function artisanType(): string
    {
        return $this instanceof \App\Models\Tattooer ? 'tattooer' : 'piercer';
    }

    public function artisanLabel(): string
    {
        return $this instanceof \App\Models\Tattooer ? 'Tatoueur' : 'Pierceur';
    }

    // Relations polymorphiques communes
    public function bookingRequests()
    {
        return $this->morphMany(BookingRequest::class, 'bookable');
    }

    // Copier ICI toutes les relations et méthodes communes de Tattooer :
    // - clients()
    // - appointments()
    // - conversations()
    // - isPro() / isFree()
    // - calculateCommission()
    // - calculateNetAmount()
    // - getProfileUrl()
    // etc.
}
```

### 2C. Utiliser le trait dans les deux models

```php
// app/Models/Tattooer.php
use App\Models\Traits\IsArtisan;
class Tattooer extends Model {
    use IsArtisan;
    // ... garder les méthodes SPÉCIFIQUES au tatoueur seulement
}

// app/Models/Piercer.php
use App\Models\Traits\IsArtisan;
class Piercer extends Model {
    use IsArtisan;
    // ... ajouter les méthodes SPÉCIFIQUES au pierceur seulement
    
    public function getPricingGrid(): array
    {
        return $this->pricing_grid ?? [];
    }
}
```

ATTENTION : NE PAS casser les méthodes existantes de Tattooer. Déplacer dans le trait uniquement les méthodes qui sont IDENTIQUES pour les deux. Si une méthode est légèrement différente → la garder dans chaque model.

```bash
# VÉRIFICATION
php artisan tinker --execute="
  \$t = App\Models\Tattooer::first();
  echo 'Tattooer artisanType: ' . \$t->artisanType();
  echo ' | bookingRequests: ' . \$t->bookingRequests()->count();
"
git add -A && git commit -m "feat(model): trait IsArtisan partagé entre Tattooer et Piercer"
```

---

## PHASE 3 — HELPER artisan() SUR LE USER

Le User doit pouvoir retourner son profil artisan (Tattooer ou Piercer) de manière transparente.

```bash
# 3A. Examiner les relations User existantes
grep -n "function tattooer\|function piercer\|function artisan" app/Models/User.php
```

### 3B. Ajouter le helper dans User.php

```php
// app/Models/User.php

// Relations existantes (garder)
public function tattooer() { return $this->hasOne(Tattooer::class); }
public function piercer() { return $this->hasOne(Piercer::class); }

// Helper universel
public function artisan()
{
    return $this->tattooer ?? $this->piercer;
}

public function artisanType(): ?string
{
    if ($this->tattooer) return 'tattooer';
    if ($this->piercer) return 'piercer';
    return null;
}

public function isArtisan(): bool
{
    return $this->artisanType() !== null;
}

public function isTattooer(): bool
{
    return $this->tattooer !== null;
}

public function isPiercer(): bool
{
    return $this->piercer !== null;
}
```

```bash
# VÉRIFICATION
php artisan tinker --execute="
  \$user = App\Models\User::whereHas('tattooer')->first();
  echo 'User ' . \$user->id . ' artisanType: ' . \$user->artisanType();
  echo ' | artisan class: ' . get_class(\$user->artisan());
"
git add -A && git commit -m "feat(user): helper artisan() universel pour Tattooer/Piercer"
```

---

## PHASE 4 — LAYOUT ARTISAN ADAPTATIF

Au lieu de 2 layouts séparés, le layout `tattooer.blade.php` doit s'adapter au type d'artisan.

```bash
# 4A. Examiner le layout actuel
head -30 resources/views/layouts/tattooer.blade.php
grep -n "tattoo\|tatoueur\|Tattoo" resources/views/layouts/tattooer.blade.php | head -20
```

### 4B. Adapter le layout

Dans `resources/views/layouts/tattooer.blade.php`, ajouter EN HAUT :

```blade
@php
    $artisan = auth()->user()->artisan();
    $artisanType = auth()->user()->artisanType(); // 'tattooer' ou 'piercer'
    $isPiercer = $artisanType === 'piercer';
    $artisanLabel = $isPiercer ? 'Pierceur' : 'Tatoueur';
    $routePrefix = $isPiercer ? 'pierceur' : 'tattooer';
@endphp
```

Puis remplacer les références hardcodées :
- `Tatoueur` → `{{ $artisanLabel }}`
- `route('tattooer.xxx')` → `route($routePrefix . '.xxx')` (SEULEMENT là où les routes pierceur existent)
- Les liens de navigation restent les mêmes (dashboard, messages, clients, calendar, portfolio, settings)

**NE PAS renommer le fichier layout.** Garder `layouts/tattooer.blade.php` et le réutiliser pour les pierceurs aussi. On peut créer un alias `layouts/piercer.blade.php` qui @extends('layouts.tattooer') si nécessaire.

OU mieux : créer `layouts/artisan.blade.php` qui est une copie de `tattooer.blade.php` avec les variables dynamiques, et faire que `tattooer.blade.php` et `piercer.blade.php` soient des alias :

```blade
{{-- resources/views/layouts/piercer.blade.php --}}
@extends('layouts.tattooer')
{{-- C'est tout. Le layout tattooer gère le conditionnel via $artisanType --}}
```

ATTENTION : vérifier que @extends('layouts.tattooer') fonctionne dans TOUTES les vues tattooer existantes après cette modification. Ne rien casser.

```bash
# VÉRIFICATION
php artisan view:clear
php artisan view:cache 2>&1 | head -3
git add -A && git commit -m "feat(layout): layout artisan adaptatif tattooer/piercer"
```

---

## PHASE 5 — ROUTES PIERCEUR (miroir des routes Tattooer)

```bash
# 5A. Lister toutes les routes tattooer actuelles
php artisan route:list --name="tattooer" --columns=method,uri,name,action 2>&1
```

### 5B. Créer les routes pierceur en miroir

Dans `routes/web.php`, le groupe pierceur doit être un MIROIR EXACT du groupe tattooer, pointant vers les MÊMES controllers.

```php
// Routes Pierceur — même controllers que Tattooer, le controller détecte le type via auth()->user()->artisanType()
Route::middleware(['auth', 'verified', 'role:pierceur'])->prefix('pierceur')->name('pierceur.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [TattooerController::class, 'dashboard'])->name('dashboard');
    
    // Settings
    Route::get('/settings', [TattooerController::class, 'settings'])->name('settings');
    Route::put('/settings', [TattooerController::class, 'updateSettings'])->name('settings.update');
    
    // Portfolio
    Route::get('/portfolio', [TattooerController::class, 'portfolio'])->name('portfolio');
    Route::post('/portfolio/upload', [TattooerController::class, 'uploadPortfolio'])->name('portfolio.upload');
    Route::delete('/portfolio/{media}', [TattooerController::class, 'deletePortfolio'])->name('portfolio.delete');
    
    // Clients
    Route::get('/clients', [TattooerController::class, 'clients'])->name('clients');
    Route::get('/clients/{client}', [TattooerController::class, 'clientShow'])->name('client.show');
    
    // Messages
    Route::get('/messages', [TattooerController::class, 'messages'])->name('messages');
    Route::get('/messages/{conversation}', [TattooerController::class, 'conversation'])->name('conversation');
    
    // Calendar
    Route::get('/calendar', [TattooerController::class, 'calendar'])->name('calendar');
    
    // Booking Requests
    Route::get('/requests', [TattooerController::class, 'requests'])->name('requests');
    Route::get('/requests/{bookingRequest}', [TattooerController::class, 'requestShow'])->name('request.show');
    Route::post('/requests/{bookingRequest}/complete', [TattooerController::class, 'markComplete'])->name('request.complete');
    Route::post('/requests/{bookingRequest}/no-show', [TattooerController::class, 'markNoShow'])->name('request.no-show');
    
    // Traceability, Consent, etc. — COPIER TOUTES les routes tattooer
    // ...
});
```

IMPORTANT : Lister TOUTES les routes tattooer et les dupliquer pour pierceur. AUCUNE route ne doit manquer.
Le TattooerController utilisera `auth()->user()->artisan()` au lieu de `auth()->user()->tattooer` pour être polymorphique.

```bash
# VÉRIFICATION
php artisan route:list --name="pierceur" 2>&1 | wc -l
php artisan route:list --name="tattooer" 2>&1 | wc -l
# Les deux nombres doivent être identiques (ou très proches)
git add -A && git commit -m "feat(routes): routes pierceur miroir des routes tattooer, même controllers"
```

---

## PHASE 6 — ADAPTER LE TATTOOERCONTROLLER AU POLYMORPHISME

Le TattooerController doit fonctionner pour TATTOOER et PIERCER.

```bash
# 6A. Examiner comment tattooer est utilisé dans le controller
grep -n "auth()->user()->tattooer\|->tattooer->\|->tattooer()" app/Http/Controllers/TattooerController.php | head -30
```

### 6B. Remplacer les références hardcodées

Dans TattooerController.php, ajouter un helper :

```php
// En haut du controller
private function artisan()
{
    $user = auth()->user();
    return $user->tattooer ?? $user->piercer;
}

private function artisanType(): string
{
    return auth()->user()->artisanType();
}
```

Puis PARTOUT dans le controller, remplacer :
```php
// AVANT
auth()->user()->tattooer
auth()->user()->tattooer->id
$user->tattooer

// APRÈS
$this->artisan()
$this->artisan()->id
$user->artisan()
```

ATTENTION : 
- Ne pas toucher aux queries qui filtrent sur `tattooer_id` spécifiquement → il faut utiliser `bookable_id` + `bookable_type`
- Vérifier que `abort_unless` compare bien avec le bon artisan
- Les vues passées doivent recevoir `$artisan` et `$artisanType` en plus

### 6C. Adapter les vues qui reçoivent les données

Dans chaque méthode du controller, ajouter les variables artisan :

```php
return view('tattooer.dashboard', [
    // ... existing data
    'artisan' => $this->artisan(),
    'artisanType' => $this->artisanType(),
    'isPiercer' => $this->artisanType() === 'piercer',
]);
```

```bash
# VÉRIFICATION
grep -c "auth()->user()->tattooer" app/Http/Controllers/TattooerController.php
# Idéalement 0 (tout remplacé par $this->artisan())
# Au minimum, les méthodes critiques doivent être polymorphiques
git add -A && git commit -m "refactor(controller): TattooerController polymorphique tattooer/piercer"
```

---

## PHASE 7 — VUES CONDITIONNELLES (les 10% de différences)

### 7A. Portfolio — Catégories piercing + Grille tarifaire

Dans `resources/views/tattooer/portfolio.blade.php` (ou le fichier équivalent) :

```blade
@if ($isPiercer ?? false)
    {{-- Catégories piercing au lieu de styles tattoo --}}
    <div class="mb-4">
        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">💎 Types de piercing</h3>
        {{-- Filtres par type : Oreille, Nez, Lèvre, Langue, etc. --}}
    </div>
    
    {{-- Pas de section Before/After --}}
@else
    {{-- Portfolio tattoo existant avec before/after --}}
@endif
```

### 7B. Grille tarifaire (NOUVEAU, spécifique pierceur)

Dans `resources/views/tattooer/settings.blade.php`, ajouter un tab/section conditionnel :

```blade
@if ($isPiercer ?? false)
    {{-- Section Grille Tarifaire --}}
    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">💰 Grille tarifaire</h3>
        <p class="text-xs text-titane mb-3">Définissez vos tarifs par type de piercing. Visible sur votre profil public.</p>
        
        <div x-data="{ 
            pricings: {{ json_encode($artisan->pricing_grid ?? [
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
            <template x-for="(item, index) in pricings" :key="index">
                <div class="flex items-center gap-3 mb-2">
                    <input type="text" :name="'pricing_grid[' + index + '][type]'" x-model="item.type"
                        placeholder="Type de piercing"
                        class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                    <div class="flex items-center gap-1">
                        <input type="number" :name="'pricing_grid[' + index + '][price]'" x-model="item.price"
                            placeholder="Prix" step="0.01" min="0"
                            class="w-24 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                        <span class="text-titane text-sm">€</span>
                    </div>
                    <button type="button" @click="if(pricings.length > 1) pricings.splice(index, 1)" 
                        x-show="pricings.length > 1"
                        class="text-rouge-alerte/60 hover:text-rouge-alerte">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
            <button type="button" @click="pricings.push({ type: '', price: '' })"
                class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold mt-2">+ Ajouter un tarif</button>
        </div>
        
        <div class="mt-3">
            <label class="text-xs text-titane block mb-1">💬 Tarif personnalisé (cas particuliers)</label>
            <input type="text" name="custom_pricing_note" 
                value="{{ $artisan->custom_pricing_note ?? '' }}"
                placeholder="Ex : Piercing génital sur devis, bijou haut de gamme +15€..."
                class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
        </div>
    </div>
@endif
```

### 7C. Traçabilité — Bijoux + Canules au lieu d'Encres + Aiguilles

Dans les formulaires de traçabilité (client-show.blade.php et vues traçabilité) :

```blade
@if ($isPiercer ?? false)
    {{-- Section Bijoux (au lieu d'Encres) --}}
    <div class="flex items-center justify-between">
        <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">💎 Bijoux utilisés</p>
        <button type="button" @click="jewelry.push({ brand: '', material: '', size: '', lot_number: '' })"
            class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">+ Ajouter</button>
    </div>
    <template x-for="(jewel, ji) in jewelry" :key="'j'+ji">
        <div class="bg-noir-profond/30 rounded-lg p-3 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs text-titane font-semibold" x-text="'Bijou ' + (ji + 1)"></span>
                <button type="button" @click="if(jewelry.length > 1) jewelry.splice(ji, 1)" x-show="jewelry.length > 1"
                    class="text-rouge-alerte/60 hover:text-rouge-alerte">×</button>
            </div>
            <input type="text" :name="'jewelry[' + ji + '][brand]'" x-model="jewel.brand" placeholder="Marque (Anatometal, Neometal, BVLA...)"
                class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
            <div class="flex flex-col sm:flex-row gap-2">
                <select :name="'jewelry[' + ji + '][material]'" x-model="jewel.material"
                    class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                    <option value="">Matière</option>
                    <option value="titane_implantable">Titane implantable (ASTM F136)</option>
                    <option value="or_14k">Or 14k</option>
                    <option value="or_18k">Or 18k</option>
                    <option value="niobium">Niobium</option>
                    <option value="acier_chirurgical">Acier chirurgical (ASTM F138)</option>
                    <option value="verre">Verre</option>
                </select>
                <input type="text" :name="'jewelry[' + ji + '][size]'" x-model="jewel.size" placeholder="Taille (ex: 6mm, 8mm, 1.2x8)"
                    class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                <input type="text" :name="'jewelry[' + ji + '][lot_number]'" x-model="jewel.lot_number" placeholder="N° lot"
                    class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
            </div>
        </div>
    </template>

    {{-- Section Canules (au lieu d'Aiguilles) --}}
    <div class="flex items-center justify-between">
        <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">💉 Canules / Aiguilles de perçage</p>
        <button type="button" @click="cannulas.push({ brand: '', gauge: '', lot_number: '' })"
            class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">+ Ajouter</button>
    </div>
    <template x-for="(cannula, ci) in cannulas" :key="'c'+ci">
        <div class="bg-noir-profond/30 rounded-lg p-3 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs text-titane font-semibold" x-text="'Canule ' + (ci + 1)"></span>
                <button type="button" @click="if(cannulas.length > 1) cannulas.splice(ci, 1)" x-show="cannulas.length > 1"
                    class="text-rouge-alerte/60 hover:text-rouge-alerte">×</button>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <input type="text" :name="'cannulas[' + ci + '][brand]'" x-model="cannula.brand" placeholder="Marque"
                    class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                <select :name="'cannulas[' + ci + '][gauge]'" x-model="cannula.gauge"
                    class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                    <option value="">Gauge</option>
                    <option value="18G">18G (1.0mm)</option>
                    <option value="16G">16G (1.2mm)</option>
                    <option value="14G">14G (1.6mm)</option>
                    <option value="12G">12G (2.0mm)</option>
                </select>
                <input type="text" :name="'cannulas[' + ci + '][lot_number]'" x-model="cannula.lot_number" placeholder="N° lot"
                    class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
            </div>
        </div>
    </template>
@else
    {{-- Section Encres + Aiguilles existante pour tattooer --}}
    {{-- ... code existant inchangé ... --}}
@endif
```

### 7D. Formulaire demande client — Types de piercing au lieu de styles tattoo

Dans la vue de demande côté client (marketplace ou formulaire booking) :

```blade
@if ($artisan instanceof \App\Models\Piercer)
    {{-- Type de piercing --}}
    <div>
        <label class="text-xs text-titane block mb-1">Type de piercing *</label>
        <select name="piercing_type" required class="...">
            <option value="">-- Choisir --</option>
            <option value="lobe">Lobe</option>
            <option value="helix">Hélix</option>
            <option value="anti_helix">Anti-hélix / Snug</option>
            <option value="tragus">Tragus</option>
            <option value="anti_tragus">Anti-tragus</option>
            <option value="conch">Conch</option>
            <option value="daith">Daith</option>
            <option value="rook">Rook</option>
            <option value="industriel">Industriel</option>
            <option value="septum">Septum</option>
            <option value="nostril">Nostril</option>
            <option value="bridge">Bridge</option>
            <option value="labret">Labret</option>
            <option value="medusa">Médusa</option>
            <option value="monroe">Monroe / Madonna</option>
            <option value="langue">Langue</option>
            <option value="smiley">Smiley</option>
            <option value="nombril">Nombril</option>
            <option value="teton">Téton</option>
            <option value="surface">Surface / Dermal</option>
            <option value="autre">Autre (préciser)</option>
        </select>
    </div>
    {{-- Pas de taille/style, mais indication prix depuis grille --}}
@else
    {{-- Champs tattoo existants : style, taille, zone, etc. --}}
@endif
```

### 7E. Soins post-piercing (aftercare)

Dans `resources/views/tattooer/settings.blade.php`, dans la section aftercare :

```blade
@if ($isPiercer ?? false)
    {{-- Texte par défaut soins piercing --}}
    @php
        $defaultAftercare = $artisan->aftercare_sheet ?? "- Ne pas toucher le piercing avec des mains sales\n- Nettoyer 2x/jour avec du sérum physiologique\n- Ne pas retirer le bijou pendant la cicatrisation\n- Éviter piscine, mer, bain pendant 4 semaines\n- Durée de cicatrisation : 6 semaines (lobe) à 12 mois (cartilage)\n- Consulter en cas de rougeur, gonflement anormal ou écoulement";
    @endphp
@else
    @php
        $defaultAftercare = $artisan->aftercare_sheet ?? "- Ne pas gratter la zone tatouée\n- Appliquer la crème cicatrisante 2x/jour pendant 15 jours\n...";
    @endphp
@endif
```

### 7F. Profil public marketplace — Grille tarifaire

Dans `resources/views/marketplace/show.blade.php`, ajouter après la bio :

```blade
@if ($artist instanceof \App\Models\Piercer && !empty($artist->pricing_grid))
    <section class="mt-6">
        <h2 class="text-lg font-bold text-ivoire-text mb-3">💰 Tarifs</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            @foreach ($artist->pricing_grid as $pricing)
                @if (!empty($pricing['type']) && !empty($pricing['price']))
                    <div class="bg-gris-fonde rounded-lg p-3 text-center">
                        <p class="text-sm font-semibold text-ivoire-text">{{ $pricing['type'] }}</p>
                        <p class="text-lg font-bold text-beige-peau">{{ number_format($pricing['price'], 0) }}€</p>
                    </div>
                @endif
            @endforeach
        </div>
        @if ($artist->custom_pricing_note)
            <p class="text-xs text-titane mt-2 italic">{{ $artist->custom_pricing_note }}</p>
        @endif
    </section>
@endif
```

```bash
# VÉRIFICATION GLOBALE
php artisan view:clear
php artisan route:list 2>&1 | head -3
git add -A && git commit -m "feat(piercer): vues conditionnelles portfolio, traçabilité, tarifs, soins, demande"
```

---

## PHASE 8 — INSCRIPTION PIERCEUR

```bash
# 8A. Examiner le flow d'inscription tattooer
grep -rn "register\|inscription\|tattooer.*create\|createTattooer" app/Http/Controllers/Auth/ app/Http/Controllers/ routes/web.php | head -20
cat resources/views/auth/register.blade.php | head -50
```

### 8B. Adapter l'inscription

Dans le formulaire d'inscription, ajouter le choix du type d'artisan :

```blade
<div>
    <label class="text-sm text-ivoire-text block mb-2">Type de profil *</label>
    <div class="flex gap-3">
        <label class="flex-1 cursor-pointer">
            <input type="radio" name="artisan_type" value="tattooer" class="peer hidden" checked>
            <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 text-center transition-colors">
                <span class="text-2xl block mb-1">🎨</span>
                <span class="text-sm font-semibold text-ivoire-text">Tatoueur</span>
            </div>
        </label>
        <label class="flex-1 cursor-pointer">
            <input type="radio" name="artisan_type" value="piercer" class="peer hidden">
            <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-4 text-center transition-colors">
                <span class="text-2xl block mb-1">💎</span>
                <span class="text-sm font-semibold text-ivoire-text">Pierceur</span>
            </div>
        </label>
    </div>
</div>
```

Dans le controller d'inscription, après création du User :

```php
if ($request->artisan_type === 'piercer') {
    Piercer::create(['user_id' => $user->id]);
    $user->assignRole('pierceur');
} else {
    Tattooer::create(['user_id' => $user->id]);
    $user->assignRole('tattooer');
}
```

Vérifier que le rôle 'pierceur' existe dans Spatie Permission :
```bash
php artisan tinker --execute="
  echo 'Role pierceur: ' . (\Spatie\Permission\Models\Role::where('name', 'pierceur')->exists() ? 'EXISTS' : 'ABSENT');
"
```
Si absent :
```bash
php artisan tinker --execute="\Spatie\Permission\Models\Role::create(['name' => 'pierceur', 'guard_name' => 'web']);"
```

```bash
# VÉRIFICATION
git add -A && git commit -m "feat(auth): inscription pierceur avec choix type artisan"
```

---

## PHASE 9 — MARKETPLACE PIERCEUR

Le profil public pierceur doit être trouvable dans la marketplace.

```bash
# 9A. Examiner comment les tattooers sont listés dans la marketplace
grep -rn "Tattooer::where\|tattooers\|marketplace" app/Http/Controllers/ | head -10
```

### 9B. Adapter la recherche marketplace

Dans le controller marketplace, la query doit chercher dans les 2 tables :

```php
// Tattooers
$tattooers = Tattooer::with('user', 'media')->where('is_active', true)->get();
// Piercers
$piercers = Piercer::with('user', 'media')->where('is_active', true)->get();
// Merger
$artists = $tattooers->merge($piercers)->sortByDesc('created_at');
```

Ou mieux, ajouter un filtre par type dans la marketplace :
```blade
{{-- Filtres --}}
<button @click="filter = 'all'" :class="filter === 'all' ? 'active' : ''">Tous</button>
<button @click="filter = 'tattooer'" :class="filter === 'tattooer' ? 'active' : ''">🎨 Tatoueurs</button>
<button @click="filter = 'piercer'" :class="filter === 'piercer' ? 'active' : ''">💎 Pierceurs</button>
```

```bash
git add -A && git commit -m "feat(marketplace): pierceurs visibles dans la marketplace + filtre type"
```

---

## PHASE 10 — VÉRIFICATION FINALE

```bash
# 10A. Routes
php artisan route:list 2>&1 | head -3
php artisan route:list --name="pierceur" 2>&1 | wc -l
php artisan route:list --name="tattooer" 2>&1 | wc -l

# 10B. Models
php artisan tinker --execute="
  \$t = new App\Models\Tattooer;
  \$p = new App\Models\Piercer;
  echo 'Tattooer trait IsArtisan: ' . (method_exists(\$t, 'artisanType') ? 'OUI' : 'NON');
  echo ' | Piercer trait IsArtisan: ' . (method_exists(\$p, 'artisanType') ? 'OUI' : 'NON');
  echo ' | Tattooer type: ' . \$t->artisanType();
  echo ' | Piercer type: ' . \$p->artisanType();
"

# 10C. Relations polymorphiques
php artisan tinker --execute="
  \$br = App\Models\BookingRequest::first();
  echo 'BookingRequest bookable_type: ' . \$br->bookable_type;
  echo ' | bookable: ' . get_class(\$br->bookable);
"

# 10D. Rôle pierceur
php artisan tinker --execute="
  echo 'Role pierceur exists: ' . (\Spatie\Permission\Models\Role::where('name', 'pierceur')->exists() ? 'OUI' : 'NON');
"

# 10E. Pas de fichiers Cascade orphelins
ls app/Livewire/Pierceur/ 2>/dev/null && echo "⚠️ LIVEWIRE PIERCEUR ENCORE LÀ" || echo "✅ Pas de Livewire pierceur"
ls resources/views/pierceur/ 2>/dev/null && echo "⚠️ VUES PIERCEUR ENCORE LÀ" || echo "✅ Pas de vues pierceur séparées"

# 10F. Compilation
php artisan view:clear
php artisan route:clear
php artisan config:clear

echo "=== IMPLÉMENTATION PIERCEUR TERMINÉE ==="
```

---

## ⚠️ RÈGLES ABSOLUES

1. **NE JAMAIS casser le Tattooer existant** — tester que tout fonctionne toujours pour les tattooers après chaque phase
2. **Réutiliser, pas dupliquer** — aucun fichier en double, que des conditionnels
3. **Le TattooerController gère les 2 types** via `$this->artisan()`
4. **Les vues sont PARTAGÉES** avec `@if ($isPiercer)`
5. **Commit après chaque phase** avec vérification
6. **Si ça casse quelque chose** → rollback immédiat
