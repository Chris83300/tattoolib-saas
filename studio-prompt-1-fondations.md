# 🏢 STUDIO PROMPT 1/4 — FONDATIONS (Models, Migrations, Relations)
# Pour Claude Code — Commit après chaque phase

## CONTEXTE

Ink&Pik, Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, MySQL, Stripe Connect + Billing, Laravel Cashier, Spatie Permission, Spatie Media Library, Filament v4.5.

Le système polymorphique est en place :
- Trait IsArtisan partagé entre Tattooer et Piercer
- ArtisanInterface implémentée par les 2 models
- TattooerController polymorphique (accepte Tattooer et Piercer)
- Layout artisan adaptatif avec $artisanType / $isPiercer

Le Studio est un type de profil DIFFÉRENT du Tattooer/Piercer :
- Le Studio est un SALON qui gère des artistes
- Chaque artiste du studio est un User avec un profil Tattooer ou Piercer
- Les artistes du studio utilisent les MÊMES vues/controllers que les artistes indépendants
- La seule différence : ils sont rattachés à un studio (studio_id)

### PRICING
- 79.99€/mois pour le studio (inclut 1 artiste)
- 39.99€/mois par artiste supplémentaire

### MODÈLE DE PAIEMENT (configurable par le studio)
- **Centralisé** : le studio a un Stripe Connect unique, encaisse tout, reverse aux artistes hors plateforme
- **Distribué** : chaque artiste a son propre Stripe Connect, le studio supervise seulement

### AJOUT D'ARTISTE
- **Création directe** : le studio crée un User artiste (nom, email, type tattooer/piercer, mot de passe temporaire)
- **Invitation par email** : le studio envoie un lien, l'artiste s'inscrit lui-même et est automatiquement rattaché

---

## PHASE 0 — AUDIT DE L'EXISTANT

```bash
# 0A. Model Studio
cat app/Models/Studio.php 2>/dev/null || echo "MODEL ABSENT"
cat app/Models/StudioArtist.php 2>/dev/null || echo "MODEL ABSENT"

# 0B. Tables
php artisan tinker --execute="
  echo 'studios: ' . (Schema::hasTable('studios') ? 'OUI (' . DB::table('studios')->count() . ' rows)' : 'NON');
  echo PHP_EOL . 'studio_artists: ' . (Schema::hasTable('studio_artists') ? 'OUI (' . DB::table('studio_artists')->count() . ' rows)' : 'NON');
  if (Schema::hasTable('studios')) {
    echo PHP_EOL . 'Colonnes studios: ' . implode(', ', Schema::getColumnListing('studios'));
  }
  if (Schema::hasTable('studio_artists')) {
    echo PHP_EOL . 'Colonnes studio_artists: ' . implode(', ', Schema::getColumnListing('studio_artists'));
  }
"

# 0C. Routes
php artisan route:list 2>&1 | grep -i "studio" | head -20

# 0D. Vues
find resources/views/studio -type f 2>/dev/null
find resources/views/layouts -name "*studio*" 2>/dev/null

# 0E. Controllers
ls app/Http/Controllers/Studio* 2>/dev/null
ls app/Http/Controllers/Studio/ 2>/dev/null

# 0F. Filament
find app/Filament -name "*Studio*" -o -name "*studio*" 2>/dev/null

# 0G. Rôles
php artisan tinker --execute="
  \$roles = Spatie\Permission\Models\Role::pluck('name');
  echo 'Rôles: ' . \$roles->implode(', ');
"

# 0H. Relations User
grep -n "function studio\|function studioArtist\|isStudio\|isStudioArtist" app/Models/User.php

# 0I. Livewire
find app/Livewire/Studio -type f 2>/dev/null
find app/Livewire -name "*studio*" -type f 2>/dev/null

# 0J. Migrations existantes
find database/migrations -name "*studio*" | sort
```

**MONTRE-MOI TOUS les résultats avant de continuer.** Il faut savoir exactement ce qui existe pour ne pas dupliquer.

---

## PHASE 1 — MODEL STUDIO

Le Studio n'est PAS un artisan. C'est une entité qui GÈRE des artistes.
Il n'implémente PAS ArtisanInterface, il n'a PAS le trait IsArtisan.

### 1A. Vérifier/Compléter app/Models/Studio.php

Le model Studio doit avoir :

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Laravel\Cashier\Billable;

class Studio extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Billable;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'postal_code',
        'phone',
        'email',
        'website',
        'siret',
        'payment_model',       // 'centralized' ou 'distributed'
        'stripe_account_id',   // Stripe Connect du studio (si centralisé)
        'stripe_onboarding_complete',
        'max_artists',         // Limite contractuelle (null = illimité)
        'is_active',
        'opening_hours',       // JSON : {"monday": {"open": "09:00", "close": "19:00"}, ...}
        'social_links',        // JSON : {"instagram": "...", "facebook": "...", "website": "..."}
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'social_links' => 'array',
        'stripe_onboarding_complete' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ═══ RELATIONS ═══

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studioArtists()
    {
        return $this->hasMany(StudioArtist::class);
    }

    /**
     * Retourne tous les Users artistes rattachés au studio
     */
    public function artistUsers()
    {
        return $this->hasManyThrough(
            User::class,
            StudioArtist::class,
            'studio_id',    // FK sur studio_artists
            'id',           // FK sur users
            'id',           // PK de studios
            'user_id'       // FK sur studio_artists vers users
        );
    }

    /**
     * Retourne les profils Tattooer rattachés au studio
     */
    public function tattooers()
    {
        $userIds = $this->studioArtists()->pluck('user_id');
        return Tattooer::whereIn('user_id', $userIds);
    }

    /**
     * Retourne les profils Piercer rattachés au studio
     */
    public function piercers()
    {
        $userIds = $this->studioArtists()->pluck('user_id');
        return Piercer::whereIn('user_id', $userIds);
    }

    // ═══ HELPERS ═══

    public function isCentralized(): bool
    {
        return $this->payment_model === 'centralized';
    }

    public function isDistributed(): bool
    {
        return $this->payment_model === 'distributed';
    }

    /**
     * Nombre d'artistes actuellement rattachés
     */
    public function artistCount(): int
    {
        return $this->studioArtists()->where('is_active', true)->count();
    }

    /**
     * Nombre d'artistes inclus dans l'offre (1)
     */
    public function includedArtists(): int
    {
        return 1;
    }

    /**
     * Nombre d'artistes supplémentaires payants
     */
    public function paidArtistCount(): int
    {
        return max(0, $this->artistCount() - $this->includedArtists());
    }

    /**
     * Coût mensuel total
     * 79.99€ base + 39.99€ × artistes supplémentaires
     */
    public function monthlyPrice(): float
    {
        return 79.99 + ($this->paidArtistCount() * 39.99);
    }

    /**
     * Peut ajouter un artiste ?
     */
    public function canAddArtist(): bool
    {
        if ($this->max_artists === null) return true;
        return $this->artistCount() < $this->max_artists;
    }

    public function getProfileUrl(): string
    {
        return route('studio.public.show', $this->slug);
    }

    // ═══ MEDIA ═══

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('photos'); // Photos du salon
    }
}
```

IMPORTANT : Si le model existe déjà, NE PAS écraser. COMPARER avec ce qui existe et AJOUTER ce qui manque.

---

## PHASE 2 — MODEL STUDIOARTIST (table pivot enrichie)

StudioArtist est la table de liaison entre un Studio et un User artiste.
Ce n'est PAS une simple pivot — elle contient des métadonnées.

### 2A. Vérifier/Compléter app/Models/StudioArtist.php

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudioArtist extends Model
{
    protected $fillable = [
        'studio_id',
        'user_id',
        'artisan_type',      // 'tattooer' ou 'piercer'
        'role',              // 'artist' ou 'manager' (pour l'avenir)
        'is_active',
        'joined_at',
        'invited_at',
        'invitation_token',
        'invitation_email',
        'commission_rate',   // Override du taux de commission pour cet artiste (nullable)
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'joined_at' => 'datetime',
        'invited_at' => 'datetime',
        'commission_rate' => 'decimal:2',
    ];

    // ═══ RELATIONS ═══

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le profil artisan (Tattooer ou Piercer) de cet artiste
     */
    public function artisan()
    {
        $user = $this->user;
        if (!$user) return null;
        return $user->artisan();
    }

    // ═══ HELPERS ═══

    public function isActive(): bool
    {
        return $this->is_active && $this->user_id !== null;
    }

    public function isPending(): bool
    {
        return $this->user_id === null && $this->invitation_token !== null;
    }
}
```

---

## PHASE 3 — MIGRATION(S)

Créer les migrations SEULEMENT si les tables n'existent pas ou si des colonnes manquent.

### 3A. Table studios (si absente ou incomplète)

```bash
# Vérifier
php artisan tinker --execute="
  if (Schema::hasTable('studios')) {
    echo implode(', ', Schema::getColumnListing('studios'));
  } else {
    echo 'TABLE ABSENTE';
  }
"
```

Colonnes requises pour `studios` :
```php
Schema::create('studios', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('address')->nullable();
    $table->string('city')->nullable();
    $table->string('postal_code', 10)->nullable();
    $table->string('phone', 20)->nullable();
    $table->string('email')->nullable();
    $table->string('website')->nullable();
    $table->string('siret', 14)->nullable();
    $table->enum('payment_model', ['centralized', 'distributed'])->default('centralized');
    $table->string('stripe_account_id')->nullable();
    $table->boolean('stripe_onboarding_complete')->default(false);
    $table->integer('max_artists')->nullable();
    $table->boolean('is_active')->default(true);
    $table->json('opening_hours')->nullable();
    $table->json('social_links')->nullable();
    $table->softDeletes();
    $table->timestamps();
});
```

### 3B. Table studio_artists (si absente ou incomplète)

```php
Schema::create('studio_artists', function (Blueprint $table) {
    $table->id();
    $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
    // user_id nullable car une invitation en attente n'a pas encore de user
    $table->string('artisan_type')->default('tattooer'); // 'tattooer' ou 'piercer'
    $table->string('role')->default('artist'); // 'artist' ou 'manager'
    $table->boolean('is_active')->default(true);
    $table->timestamp('joined_at')->nullable();
    $table->timestamp('invited_at')->nullable();
    $table->string('invitation_token')->nullable()->unique();
    $table->string('invitation_email')->nullable();
    $table->decimal('commission_rate', 5, 2)->nullable();
    $table->timestamps();
    
    // Un user ne peut être rattaché qu'à un seul studio
    $table->unique(['user_id']);
});
```

### 3C. Ajouter studio_id sur tattooers et piercers (lien optionnel)

Pour savoir si un artiste est indépendant ou rattaché à un studio :

```bash
php artisan tinker --execute="
  echo 'tattooers.studio_id: ' . (Schema::hasColumn('tattooers', 'studio_id') ? 'EXISTS' : 'ABSENT');
  echo PHP_EOL . 'piercers.studio_id: ' . (Schema::hasColumn('piercers', 'studio_id') ? 'EXISTS' : 'ABSENT');
"
```

Si absent → migration :
```php
// add_studio_id_to_artisan_tables
Schema::table('tattooers', function (Blueprint $table) {
    $table->foreignId('studio_id')->nullable()->constrained()->nullOnDelete();
});
Schema::table('piercers', function (Blueprint $table) {
    $table->foreignId('studio_id')->nullable()->constrained()->nullOnDelete();
});
```

```bash
php artisan migrate
git add -A && git commit -m "feat(studio): models Studio + StudioArtist + migrations"
```

---

## PHASE 4 — RELATIONS USER + RÔLES

### 4A. Helpers dans User.php

```bash
grep -n "function studio\|isStudio\|isStudioArtist" app/Models/User.php
```

Ajouter dans `app/Models/User.php` (si absent) :

```php
// ═══ STUDIO RELATIONS ═══

public function studio()
{
    return $this->hasOne(Studio::class);
}

public function studioArtistPivot()
{
    return $this->hasOne(StudioArtist::class);
}

// ═══ STUDIO HELPERS ═══

/**
 * Est-ce un propriétaire de studio ?
 */
public function isStudio(): bool
{
    return $this->studio !== null;
}

/**
 * Est-ce un artiste rattaché à un studio ?
 */
public function isStudioArtist(): bool
{
    return $this->studioArtistPivot !== null && $this->studioArtistPivot->is_active;
}

/**
 * Retourne le studio auquel cet artiste est rattaché (si applicable)
 */
public function artistStudio(): ?Studio
{
    return $this->studioArtistPivot?->studio;
}

/**
 * Est-ce un artiste indépendant (pas rattaché à un studio) ?
 */
public function isIndependent(): bool
{
    return $this->isArtisan() && !$this->isStudioArtist();
}
```

### 4B. Ajouter relations dans Tattooer et Piercer

Dans `app/Models/Tattooer.php` et `app/Models/Piercer.php`, ajouter :

```php
public function studio()
{
    return $this->belongsTo(Studio::class);
}

public function isStudioArtist(): bool
{
    return $this->studio_id !== null;
}
```

### 4C. Rôles Spatie

```bash
php artisan tinker --execute="
  \$roles = ['studio', 'studio_artist'];
  foreach(\$roles as \$r) {
    if (!\Spatie\Permission\Models\Role::where('name', \$r)->exists()) {
      \Spatie\Permission\Models\Role::create(['name' => \$r, 'guard_name' => 'web']);
      echo 'Créé: ' . \$r . PHP_EOL;
    } else {
      echo 'Existe: ' . \$r . PHP_EOL;
    }
  }
"
```

### 4D. Middleware role pour studio

Vérifier que le middleware `role` dans `bootstrap/app.php` accepte `role:studio` et `role:studio_artist`.

```bash
grep -n "role" bootstrap/app.php
# Le middleware EnsureUserHasRole doit déjà être enregistré (fait en Phase 8)
# Vérifier qu'il supporte les paramètres comme role:studio,studio_artist
cat app/Http/Middleware/EnsureUserHasRole.php
```

Si le middleware vérifie via Spatie `hasRole()`, ça devrait fonctionner.
Si c'est une vérification custom → adapter pour supporter studio et studio_artist.

```bash
git add -A && git commit -m "feat(studio): relations User + rôles Spatie studio/studio_artist"
```

---

## PHASE 5 — ROUTES STUDIO (structure de base)

Dans `routes/web.php`, créer le groupe studio avec les routes essentielles.
Les routes pointent vers des controllers/Livewire qu'on créera au Prompt 2.

```php
// ═══ ROUTES STUDIO (propriétaire) ═══
Route::middleware(['auth', 'verified', 'role:studio'])->prefix('studio')->name('studio.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\StudioController::class, 'dashboard'])->name('dashboard');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\StudioController::class, 'settings'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\StudioController::class, 'updateSettings'])->name('settings.update');
    
    // Artistes
    Route::get('/artists', [App\Http\Controllers\StudioController::class, 'artists'])->name('artists');
    Route::get('/artists/create', [App\Http\Controllers\StudioController::class, 'createArtist'])->name('artists.create');
    Route::post('/artists', [App\Http\Controllers\StudioController::class, 'storeArtist'])->name('artists.store');
    Route::post('/artists/invite', [App\Http\Controllers\StudioController::class, 'inviteArtist'])->name('artists.invite');
    Route::delete('/artists/{studioArtist}', [App\Http\Controllers\StudioController::class, 'removeArtist'])->name('artists.remove');
    Route::put('/artists/{studioArtist}/toggle', [App\Http\Controllers\StudioController::class, 'toggleArtist'])->name('artists.toggle');
    
    // Planning global
    Route::get('/planning', [App\Http\Controllers\StudioController::class, 'planning'])->name('planning');
    
    // Profil public
    Route::get('/profile', [App\Http\Controllers\StudioController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\StudioController::class, 'updateProfile'])->name('profile.update');
    
    // Billing
    Route::get('/billing', [App\Http\Controllers\StudioController::class, 'billing'])->name('billing');
    
    // Stats
    Route::get('/stats', [App\Http\Controllers\StudioController::class, 'stats'])->name('stats');
});

// Route publique profil studio
Route::get('/salon/{slug}', [App\Http\Controllers\StudioController::class, 'publicProfile'])->name('studio.public.show');

// Route invitation artiste (publique, avec token)
Route::get('/studio/invitation/{token}', [App\Http\Controllers\StudioController::class, 'acceptInvitation'])->name('studio.invitation.accept');
Route::post('/studio/invitation/{token}', [App\Http\Controllers\StudioController::class, 'processInvitation'])->name('studio.invitation.process');
```

### Vérifier les doublons

```bash
# Supprimer les anciennes routes studio si elles existent (Cascade les avait mal créées)
grep -n "studio" routes/web.php
# Si doublons → nettoyer
```

ATTENTION : lors de la Phase 8, les routes studio ont été fusionnées. Vérifier qu'il n'y a pas de conflit avec les routes existantes. Si des routes studio existent déjà, les REMPLACER par celles ci-dessus.

```bash
php artisan route:list --name="studio" 2>&1 | head -20
git add -A && git commit -m "feat(studio): routes studio complètes"
```

---

## PHASE 6 — CONTROLLER STUDIO (squelette)

Créer un StudioController avec les méthodes en squelette (return view avec données basiques).
Les vues seront créées dans le Prompt 2.

```php
// app/Http/Controllers/StudioController.php
namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\StudioArtist;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Piercer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class StudioController extends Controller
{
    private function studio(): Studio
    {
        return auth()->user()->studio;
    }

    public function dashboard()
    {
        $studio = $this->studio();
        $artists = $studio->studioArtists()->with('user')->where('is_active', true)->get();
        
        return view('studio.dashboard', [
            'studio' => $studio,
            'artists' => $artists,
            'artistCount' => $artists->count(),
            'monthlyPrice' => $studio->monthlyPrice(),
        ]);
    }

    public function settings()
    {
        return view('studio.settings', [
            'studio' => $this->studio(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $studio = $this->studio();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'siret' => 'nullable|string|size:14',
            'payment_model' => 'required|in:centralized,distributed',
            'opening_hours' => 'nullable|array',
            'social_links' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $studio->update($validated);

        // Photos
        if ($request->hasFile('logo')) {
            $studio->addMediaFromRequest('logo')->toMediaCollection('logo');
        }
        if ($request->hasFile('cover')) {
            $studio->addMediaFromRequest('cover')->toMediaCollection('cover');
        }
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $studio->addMedia($photo)->toMediaCollection('photos');
            }
        }

        return back()->with('success', 'Paramètres mis à jour');
    }

    // ═══ ARTISTES ═══

    public function artists()
    {
        $studio = $this->studio();
        $artists = $studio->studioArtists()->with('user')->get();
        $pendingInvitations = $artists->where('user_id', null);
        $activeArtists = $artists->where('user_id', '!=', null);

        return view('studio.artists', [
            'studio' => $studio,
            'activeArtists' => $activeArtists,
            'pendingInvitations' => $pendingInvitations,
            'canAddArtist' => $studio->canAddArtist(),
            'paidArtistCount' => $studio->paidArtistCount(),
            'monthlyPrice' => $studio->monthlyPrice(),
        ]);
    }

    public function createArtist()
    {
        $studio = $this->studio();
        abort_unless($studio->canAddArtist(), 403, 'Limite d\'artistes atteinte');

        return view('studio.artists-create', [
            'studio' => $studio,
        ]);
    }

    /**
     * Création directe d'un artiste
     */
    public function storeArtist(Request $request)
    {
        $studio = $this->studio();
        abort_unless($studio->canAddArtist(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'artisan_type' => 'required|in:tattooer,piercer',
        ]);

        // Créer le User avec mot de passe temporaire
        $tempPassword = Str::random(12);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($tempPassword),
            'email_verified_at' => now(), // Vérifié par le studio
        ]);

        // Assigner le rôle
        $role = $validated['artisan_type'] === 'piercer' ? 'pierceur' : 'tattooer';
        $user->assignRole($role);
        $user->assignRole('studio_artist');

        // Créer le profil artisan
        if ($validated['artisan_type'] === 'piercer') {
            Piercer::create([
                'user_id' => $user->id,
                'studio_id' => $studio->id,
            ]);
        } else {
            Tattooer::create([
                'user_id' => $user->id,
                'studio_id' => $studio->id,
            ]);
        }

        // Créer le lien StudioArtist
        StudioArtist::create([
            'studio_id' => $studio->id,
            'user_id' => $user->id,
            'artisan_type' => $validated['artisan_type'],
            'is_active' => true,
            'joined_at' => now(),
        ]);

        // TODO (Prompt 4) : Envoyer email avec identifiants temporaires
        // TODO (Prompt 4) : Mettre à jour la subscription Stripe (quantity)

        return redirect()->route('studio.artists')
            ->with('success', "Artiste {$validated['name']} créé. Mot de passe temporaire : {$tempPassword}");
    }

    /**
     * Invitation par email
     */
    public function inviteArtist(Request $request)
    {
        $studio = $this->studio();
        abort_unless($studio->canAddArtist(), 403);

        $validated = $request->validate([
            'email' => 'required|email',
            'artisan_type' => 'required|in:tattooer,piercer',
        ]);

        $token = Str::uuid()->toString();

        StudioArtist::create([
            'studio_id' => $studio->id,
            'user_id' => null, // Pas encore de user
            'artisan_type' => $validated['artisan_type'],
            'is_active' => false,
            'invitation_token' => $token,
            'invitation_email' => $validated['email'],
            'invited_at' => now(),
        ]);

        // TODO (Prompt 3) : Envoyer email d'invitation avec lien
        // Mail::to($validated['email'])->send(new StudioInvitationMail($studio, $token));

        return back()->with('success', "Invitation envoyée à {$validated['email']}");
    }

    public function removeArtist(StudioArtist $studioArtist)
    {
        abort_unless($studioArtist->studio_id === $this->studio()->id, 403);
        
        // Désactiver plutôt que supprimer (garder l'historique)
        $studioArtist->update(['is_active' => false]);
        
        // Retirer le studio_id du profil artisan
        $artisan = $studioArtist->user?->artisan();
        if ($artisan) {
            $artisan->update(['studio_id' => null]);
        }

        // TODO (Prompt 4) : Mettre à jour la subscription Stripe (quantity -1)

        return back()->with('success', 'Artiste retiré du studio');
    }

    public function toggleArtist(StudioArtist $studioArtist)
    {
        abort_unless($studioArtist->studio_id === $this->studio()->id, 403);
        $studioArtist->update(['is_active' => !$studioArtist->is_active]);
        return back();
    }

    // ═══ PLANNING ═══

    public function planning()
    {
        $studio = $this->studio();
        $artists = $studio->studioArtists()->with('user')->where('is_active', true)->get();
        
        // Charger les appointments de tous les artistes du studio
        $artistUserIds = $artists->pluck('user_id')->filter();
        // Les appointments sont liés aux BookingRequests via bookable
        
        return view('studio.planning', [
            'studio' => $studio,
            'artists' => $artists,
        ]);
    }

    // ═══ PROFIL PUBLIC ═══

    public function profile()
    {
        return view('studio.profile-edit', [
            'studio' => $this->studio(),
        ]);
    }

    public function publicProfile(string $slug)
    {
        $studio = Studio::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $artists = $studio->studioArtists()->with('user')->where('is_active', true)->get();

        return view('studio.public-profile', [
            'studio' => $studio,
            'artists' => $artists,
        ]);
    }

    // ═══ BILLING ═══

    public function billing()
    {
        return view('studio.billing', [
            'studio' => $this->studio(),
            'monthlyPrice' => $this->studio()->monthlyPrice(),
            'artistCount' => $this->studio()->artistCount(),
            'paidArtistCount' => $this->studio()->paidArtistCount(),
        ]);
    }

    // ═══ STATS ═══

    public function stats()
    {
        return view('studio.stats', [
            'studio' => $this->studio(),
        ]);
    }

    // ═══ INVITATION ═══

    public function acceptInvitation(string $token)
    {
        $invitation = StudioArtist::where('invitation_token', $token)
            ->whereNull('user_id')
            ->firstOrFail();

        return view('studio.accept-invitation', [
            'invitation' => $invitation,
            'studio' => $invitation->studio,
        ]);
    }

    public function processInvitation(Request $request, string $token)
    {
        $invitation = StudioArtist::where('invitation_token', $token)
            ->whereNull('user_id')
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Créer le user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        // Rôle
        $role = $invitation->artisan_type === 'piercer' ? 'pierceur' : 'tattooer';
        $user->assignRole($role);
        $user->assignRole('studio_artist');

        // Profil artisan
        $artisanModel = $invitation->artisan_type === 'piercer' ? Piercer::class : Tattooer::class;
        $artisanModel::create([
            'user_id' => $user->id,
            'studio_id' => $invitation->studio_id,
        ]);

        // Lier l'invitation
        $invitation->update([
            'user_id' => $user->id,
            'is_active' => true,
            'joined_at' => now(),
            'invitation_token' => null, // Token consommé
        ]);

        // TODO (Prompt 4) : Mettre à jour subscription Stripe

        auth()->login($user);
        return redirect()->route($role === 'pierceur' ? 'pierceur.dashboard' : 'tattooer.dashboard')
            ->with('success', 'Bienvenue dans le studio ' . $invitation->studio->name . ' !');
    }
}
```

IMPORTANT : Si un StudioController existe déjà, COMPARER et MERGER. Ne pas écraser ce qui fonctionne.

```bash
wc -l app/Http/Controllers/StudioController.php
git add -A && git commit -m "feat(studio): StudioController complet avec gestion artistes + invitations"
```

---

## PHASE 7 — VÉRIFICATION FINALE PROMPT 1

```bash
# 7A. Models
php artisan tinker --execute="
  echo 'Studio: ' . (class_exists('App\Models\Studio') ? 'OK' : 'ABSENT');
  echo ' | StudioArtist: ' . (class_exists('App\Models\StudioArtist') ? 'OK' : 'ABSENT');
  \$s = new App\Models\Studio;
  echo ' | fillable: ' . count(\$s->getFillable()) . ' champs';
  echo ' | casts: ' . count(\$s->getCasts()) . ' casts';
"

# 7B. Tables
php artisan tinker --execute="
  echo 'studios: ' . implode(', ', Schema::getColumnListing('studios'));
  echo PHP_EOL . 'studio_artists: ' . implode(', ', Schema::getColumnListing('studio_artists'));
  echo PHP_EOL . 'tattooers.studio_id: ' . (Schema::hasColumn('tattooers', 'studio_id') ? 'OUI' : 'NON');
  echo PHP_EOL . 'piercers.studio_id: ' . (Schema::hasColumn('piercers', 'studio_id') ? 'OUI' : 'NON');
"

# 7C. Rôles
php artisan tinker --execute="
  echo 'Rôles: ' . Spatie\Permission\Models\Role::pluck('name')->implode(', ');
"

# 7D. Routes
php artisan route:list --name="studio" 2>&1 | wc -l

# 7E. User helpers
php artisan tinker --execute="
  \$user = App\Models\User::first();
  \$methods = ['isStudio', 'isStudioArtist', 'isIndependent', 'artistStudio'];
  foreach(\$methods as \$m) {
    echo \$m . '(): ' . (method_exists(\$user, \$m) ? 'EXISTS' : 'MISSING') . PHP_EOL;
  }
"

# 7F. Controller
php artisan route:list --name="studio" 2>&1 | head -3
# Doit compiler sans erreur

echo "=== PROMPT 1/4 STUDIO — FONDATIONS TERMINÉES ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire avant de créer quoi que ce soit
2. **NE PAS écraser** les fichiers existants — COMPARER et MERGER
3. **Les vues seront créées au Prompt 2** — ce prompt ne crée que les fondations back-end
4. **Commit après chaque phase**
5. **Le studio N'EST PAS un artisan** — pas de trait IsArtisan, pas de ArtisanInterface
6. **Les artistes du studio SONT des artisans** — ils utilisent l'infra existante Tattooer/Piercer
