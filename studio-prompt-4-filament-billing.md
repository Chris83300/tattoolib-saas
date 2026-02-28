# 🏢 STUDIO PROMPT 4/4 — Filament Panel + Stripe Billing
# Pour Claude Code — Commit après chaque phase

## CONTEXTE

Suite des Prompts 1-3. TOUT est en place :
- Models Studio + StudioArtist complets
- Dashboard classique (layout, vues, navigation, settings, artistes, profil public)
- Emails invitation + identifiants temporaires
- Stripe Connect centralisé/distribué (getStripeAccountId)
- Artiste studio = mêmes vues que indépendant + badge studio
- Vue demandes studio + marketplace studios

Stack : Laravel 12, Livewire 3.7, Filament v4.5, Stripe Billing, Laravel Cashier, MySQL.

### CE QUE CE PROMPT IMPLÉMENTE
1. Trait Billable sur Studio + colonnes Cashier
2. Stripe Billing : abonnement studio 79.99€ + quantity artistes × 39.99€
3. Panel Filament dédié studio (accès via /studio/admin)
4. Resources Filament : Artistes, Demandes, Traçabilité
5. Widgets dashboard Filament : stats globales
6. Billing page : Stripe Customer Portal

---

## PHASE 0 — AUDIT

```bash
# 0A. Filament panels existants
find app/Providers/Filament -type f 2>/dev/null | sort
grep -rn "class.*Panel.*Provider\|->id(" app/Providers/Filament/ | head -10

# 0B. Panel admin existant
cat app/Providers/Filament/AdminPanelProvider.php 2>/dev/null | head -30

# 0C. Billable déjà sur Studio ?
grep -n "Billable\|stripe_id\|pm_type\|pm_last_four\|trial_ends_at" app/Models/Studio.php

# 0D. Colonnes Cashier sur studios ?
php artisan tinker --execute="
  \$cols = Schema::getColumnListing('studios');
  \$cashierCols = ['stripe_id', 'pm_type', 'pm_last_four', 'trial_ends_at'];
  foreach(\$cashierCols as \$c) {
    echo \$c . ': ' . (in_array(\$c, \$cols) ? 'EXISTS' : 'ABSENT') . PHP_EOL;
  }
"

# 0E. Stripe Price IDs dans .env ou config
grep -rn "STRIPE.*PRICE\|STRIPE.*PLAN\|stripe.*price\|studio.*price" .env config/ 2>/dev/null | head -10

# 0F. Filament Resources existantes pour studio
find app/Filament -path "*Studio*" -o -path "*studio*" 2>/dev/null | sort

# 0G. Comment le panel admin Filament est configuré (path, auth, middleware)
grep -n "path\|auth\|middleware\|login\|canAccess" app/Providers/Filament/AdminPanelProvider.php 2>/dev/null | head -15
```

**MONTRE-MOI les résultats avant de continuer.**

---

## PHASE 1 — BILLABLE + COLONNES CASHIER SUR STUDIO

Laravel Cashier a besoin de colonnes spécifiques sur le model qui est Billable.

### 1A. Migration colonnes Cashier

```bash
# Vérifier si les colonnes existent déjà
php artisan tinker --execute="
  \$cols = Schema::getColumnListing('studios');
  echo implode(', ', \$cols);
"
```

Si les colonnes Cashier sont ABSENTES :

```bash
php artisan make:migration add_cashier_columns_to_studios_table --table=studios
```

```php
public function up(): void
{
    Schema::table('studios', function (Blueprint $table) {
        $table->string('stripe_id')->nullable()->index();
        $table->string('pm_type')->nullable();
        $table->string('pm_last_four', 4)->nullable();
        $table->timestamp('trial_ends_at')->nullable();
    });
}

public function down(): void
{
    Schema::table('studios', function (Blueprint $table) {
        $table->dropColumn(['stripe_id', 'pm_type', 'pm_last_four', 'trial_ends_at']);
    });
}
```

### 1B. Ajouter Billable au model Studio

Dans `app/Models/Studio.php`, ajouter le trait :

```php
use Laravel\Cashier\Billable;

class Studio extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Billable;
    // ...
}
```

**IMPORTANT** : Cashier s'attend à ce que le model Billable ait un champ `email` pour les factures.
Vérifier que Studio a un champ `email` :
```bash
php artisan tinker --execute="echo Schema::hasColumn('studios', 'email') ? 'OUI' : 'NON';"
```

Si absent → l'ajouter dans la migration ou utiliser le email du User propriétaire.

Si le model utilise l'email du User, override dans Studio :
```php
public function stripeEmail(): ?string
{
    return $this->email ?? $this->user?->email;
}
```

```bash
php artisan migrate
git add -A && git commit -m "feat(studio): Billable + colonnes Cashier sur studios"
```

---

## PHASE 2 — STRIPE PRODUCTS & PRICES (config)

Les Stripe Product/Price IDs doivent être configurés. On ne les crée PAS par code — le studio les créera manuellement dans le Stripe Dashboard. On configure juste les IDs.

### 2A. Config

Ajouter dans `config/services.php` (ou un fichier config dédié) :

```php
// config/services.php, section stripe existante ou nouveau :
'stripe' => [
    // ... existing config ...
    'studio_price_id' => env('STRIPE_STUDIO_PRICE_ID'),         // 79.99€/mois
    'studio_artist_price_id' => env('STRIPE_STUDIO_ARTIST_PRICE_ID'), // 39.99€/artiste/mois
],
```

### 2B. Ajouter dans .env.example

```env
# Studio Billing
STRIPE_STUDIO_PRICE_ID=price_xxx
STRIPE_STUDIO_ARTIST_PRICE_ID=price_yyy
```

NOTE : Les vrais Price IDs seront créés dans le Stripe Dashboard :
- Product "Studio Ink&Pik" → Price 79.99€/mois (recurring)
- Product "Artiste supplémentaire" → Price 39.99€/mois (recurring, metered ou quantity)

```bash
git add -A && git commit -m "feat(studio): config Stripe Price IDs pour abonnement studio"
```

---

## PHASE 3 — SERVICE BILLING STUDIO

Créer un service dédié pour gérer l'abonnement studio.

```php
// app/Services/StudioBillingService.php
namespace App\Services;

use App\Models\Studio;

class StudioBillingService
{
    /**
     * Crée ou met à jour l'abonnement studio.
     * Studio base = 79.99€/mois
     * Artistes supplémentaires = 39.99€ × quantity
     */
    public function subscribe(Studio $studio, string $paymentMethodId): void
    {
        $studioPriceId = config('services.stripe.studio_price_id');
        $artistPriceId = config('services.stripe.studio_artist_price_id');

        // Créer le customer Stripe si nécessaire
        if (!$studio->hasStripeId()) {
            $studio->createAsStripeCustomer([
                'name' => $studio->name,
                'email' => $studio->stripeEmail(),
                'metadata' => [
                    'studio_id' => $studio->id,
                    'type' => 'studio',
                ],
            ]);
        }

        // Ajouter le moyen de paiement
        $studio->updateDefaultPaymentMethod($paymentMethodId);

        $paidArtists = $studio->paidArtistCount();

        // Créer l'abonnement avec les 2 items
        $subscriptionItems = [
            ['price' => $studioPriceId, 'quantity' => 1], // Abonnement base
        ];

        // Ajouter les artistes supplémentaires s'il y en a
        if ($paidArtists > 0 && $artistPriceId) {
            $subscriptionItems[] = ['price' => $artistPriceId, 'quantity' => $paidArtists];
        }

        $studio->newSubscription('studio', $subscriptionItems)->create($paymentMethodId);
    }

    /**
     * Met à jour la quantity d'artistes dans l'abonnement.
     * Appelé quand un artiste est ajouté ou retiré.
     */
    public function updateArtistQuantity(Studio $studio): void
    {
        $subscription = $studio->subscription('studio');
        if (!$subscription || !$subscription->active()) return;

        $artistPriceId = config('services.stripe.studio_artist_price_id');
        if (!$artistPriceId) return;

        $paidArtists = $studio->paidArtistCount();

        // Trouver l'item artiste dans l'abonnement
        $artistItem = $subscription->items->first(function ($item) use ($artistPriceId) {
            return $item->stripe_price === $artistPriceId;
        });

        if ($paidArtists > 0) {
            if ($artistItem) {
                // Mettre à jour la quantity
                $subscription->updateQuantity($paidArtists, $artistItem->stripe_price);
            } else {
                // Ajouter l'item artiste à l'abonnement
                $subscription->addPrice($artistPriceId, $paidArtists);
            }
        } elseif ($artistItem) {
            // Plus d'artistes payants → supprimer l'item
            // (garder quantity 0 ou supprimer selon la config Stripe)
            $subscription->updateQuantity(0, $artistItem->stripe_price);
        }
    }

    /**
     * Retourne l'URL du Stripe Customer Portal.
     */
    public function billingPortalUrl(Studio $studio): string
    {
        return $studio->billingPortalUrl(route('studio.billing'));
    }

    /**
     * Le studio a-t-il un abonnement actif ?
     */
    public function isSubscribed(Studio $studio): bool
    {
        return $studio->subscribed('studio');
    }
}
```

### 3B. Brancher dans StudioController

Quand un artiste est ajouté ou retiré, mettre à jour la quantité :

```bash
# Trouver storeArtist et removeArtist
grep -n "function storeArtist\|function removeArtist\|function toggleArtist" app/Http/Controllers/StudioController.php
```

Dans `storeArtist()`, APRÈS la création du StudioArtist, ajouter :
```php
// Mettre à jour l'abonnement Stripe (quantity artistes)
app(\App\Services\StudioBillingService::class)->updateArtistQuantity($studio);
```

Dans `removeArtist()`, APRÈS la désactivation :
```php
app(\App\Services\StudioBillingService::class)->updateArtistQuantity($studio);
```

Dans `processInvitation()`, APRÈS l'activation :
```php
app(\App\Services\StudioBillingService::class)->updateArtistQuantity($invitation->studio);
```

### 3C. Page Billing mise à jour

Enrichir la vue `studio/billing.blade.php` :

```bash
grep -n "billing" resources/views/studio/billing.blade.php | head -5
```

Ajouter dans le controller `billing()` :
```php
public function billing()
{
    $studio = $this->studio();
    $billingService = app(\App\Services\StudioBillingService::class);
    
    return view('studio.billing', [
        'studio' => $studio,
        'monthlyPrice' => $studio->monthlyPrice(),
        'artistCount' => $studio->artistCount(),
        'paidArtistCount' => $studio->paidArtistCount(),
        'isSubscribed' => $billingService->isSubscribed($studio),
        'portalUrl' => $studio->hasStripeId() ? $billingService->billingPortalUrl($studio) : null,
    ]);
}
```

Dans la vue, ajouter le lien vers le Customer Portal :
```blade
@if ($isSubscribed && $portalUrl)
    <a href="{{ $portalUrl }}" target="_blank"
        class="w-full sm:w-auto px-6 py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95 text-center inline-block">
        Gérer mon abonnement (Stripe)
    </a>
@elseif (!$isSubscribed)
    <div class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-4">
        <p class="text-sm text-orange-400 font-semibold">⚠️ Aucun abonnement actif</p>
        <p class="text-xs text-titane mt-1">Pour activer votre studio, veuillez souscrire à l'abonnement.</p>
        {{-- TODO: bouton souscrire avec Stripe Checkout --}}
    </div>
@endif
```

```bash
git add -A && git commit -m "feat(studio): StudioBillingService + mise à jour quantity artistes + billing page"
```

---

## PHASE 4 — PANEL FILAMENT STUDIO

Créer un panel Filament SÉPARÉ pour les studios (pas dans le panel admin existant).

### 4A. Créer le Panel Provider

```bash
php artisan make:filament-panel studio
```

Si la commande échoue, créer manuellement :

```php
// app/Providers/Filament/StudioPanelProvider.php
namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StudioPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('studio')
            ->path('studio/admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#c4956a'), // beige-peau
                'gray' => Color::hex('#8a8a9a'),    // titane
                'danger' => Color::Red,
                'success' => Color::Green,
            ])
            ->brandName('Ink&Pik Studio')
            ->darkMode(true, true) // Force dark mode
            ->discoverResources(in: app_path('Filament/Studio/Resources'), for: 'App\\Filament\\Studio\\Resources')
            ->discoverPages(in: app_path('Filament/Studio/Pages'), for: 'App\\Filament\\Studio\\Pages')
            ->discoverWidgets(in: app_path('Filament/Studio/Widgets'), for: 'App\\Filament\\Studio\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->tenant(\App\Models\Studio::class)
            ->tenantRegistration(false)
            ->navigation(function () {
                // Navigation personnalisée si besoin
            });
    }
}
```

**IMPORTANT** : Le `->tenant(Studio::class)` fait que Filament scope automatiquement les données au studio de l'utilisateur connecté. L'utilisateur doit être un propriétaire de studio.

### 4B. Configurer l'accès

L'accès au panel studio doit être limité aux users avec role 'studio'.

Si Filament utilise `canAccessPanel()` sur le User model :

```php
// Dans app/Models/User.php, ajouter ou modifier :
public function canAccessPanel(\Filament\Panel $panel): bool
{
    if ($panel->getId() === 'admin') {
        return $this->role === 'admin' || $this->is_admin;
    }
    if ($panel->getId() === 'studio') {
        return $this->role === 'studio' || $this->isStudio();
    }
    return false;
}
```

Si `canAccessPanel` n'existe pas encore, l'ajouter. Si le User n'implémente pas FilamentUser :

```php
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    // ...
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin' || $this->is_admin;
        }
        if ($panel->getId() === 'studio') {
            return $this->role === 'studio' || $this->isStudio();
        }
        return false;
    }
}
```

### 4C. Enregistrer le provider

Vérifier que le provider est enregistré dans `config/app.php` ou `bootstrap/providers.php` :

```bash
grep -rn "StudioPanelProvider" config/app.php bootstrap/providers.php bootstrap/app.php 2>/dev/null
```

Si absent → l'ajouter.

### 4D. Tenant relationship

Pour que le multi-tenant fonctionne, le User doit avoir une relation vers Studio :

```php
// Dans User.php, la relation studio() existe déjà (hasOne)
// Filament a besoin que le user puisse "accéder" à ses tenants
// Si Filament utilise getTenants(), ajouter :
public function getTenants(\Filament\Panel $panel): array|\Illuminate\Support\Collection
{
    if ($panel->getId() === 'studio') {
        return $this->studio ? collect([$this->studio]) : collect();
    }
    return collect();
}
```

```bash
php artisan filament:optimize-clear 2>/dev/null
php artisan route:clear
git add -A && git commit -m "feat(studio): Panel Filament studio avec auth + tenant scoping"
```

---

## PHASE 5 — RESOURCES FILAMENT (architecture Filament v4)

En Filament v4, les Resources séparent Form et Table dans des fichiers dédiés.

Structure cible :
```
app/Filament/Studio/Resources/
├── StudioArtistResource.php
├── StudioArtistResource/
│   ├── Pages/
│   │   ├── ListStudioArtists.php
│   │   └── EditStudioArtist.php
│   ├── Schemas/
│   │   └── StudioArtistForm.php
│   └── Tables/
│       └── StudioArtistTable.php
├── BookingRequestResource.php
├── BookingRequestResource/
│   ├── Pages/
│   │   └── ListBookingRequests.php
│   ├── Schemas/
│   │   └── BookingRequestForm.php
│   └── Tables/
│       └── BookingRequestTable.php
```

Créer les dossiers :
```bash
mkdir -p app/Filament/Studio/Resources/StudioArtistResource/{Pages,Schemas,Tables}
mkdir -p app/Filament/Studio/Resources/BookingRequestResource/{Pages,Schemas,Tables}
mkdir -p app/Filament/Studio/Pages
mkdir -p app/Filament/Studio/Widgets
```

IMPORTANT : Avant de coder, vérifier le pattern exact utilisé dans le panel admin existant pour respecter la même architecture :

```bash
# Voir comment les Resources admin existantes sont structurées
find app/Filament/Resources -type f | head -20
# Regarder un exemple de Resource existante
ls app/Filament/Resources/ | head -5
# Examiner la structure d'une Resource existante
RESOURCE=$(ls app/Filament/Resources/*.php | head -1)
cat "$RESOURCE"
# Examiner le Form séparé
find app/Filament/Resources -path "*/Schemas/*" -type f | head -3
FORM=$(find app/Filament/Resources -path "*/Schemas/*" -type f | head -1)
cat "$FORM" 2>/dev/null
# Examiner la Table séparée
find app/Filament/Resources -path "*/Tables/*" -type f | head -3
TABLE=$(find app/Filament/Resources -path "*/Tables/*" -type f | head -1)
cat "$TABLE" 2>/dev/null
```

**Copier le pattern EXACT des Resources admin existantes.** Les exemples ci-dessous sont indicatifs — adapter les namespaces, imports, et signatures de méthodes selon le pattern réel trouvé.

### 5A. Resource : Studio Artists

```php
// app/Filament/Studio/Resources/StudioArtistResource.php
namespace App\Filament\Studio\Resources;

use App\Filament\Studio\Resources\StudioArtistResource\Pages;
use App\Filament\Studio\Resources\StudioArtistResource\Schemas\StudioArtistForm;
use App\Filament\Studio\Resources\StudioArtistResource\Tables\StudioArtistTable;
use App\Models\StudioArtist;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class StudioArtistResource extends Resource
{
    protected static ?string $model = StudioArtist::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Artistes';
    protected static ?string $modelLabel = 'Artiste';
    protected static ?string $pluralModelLabel = 'Artistes';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return StudioArtistForm::make($form);
    }

    public static function table(Table $table): Table
    {
        return StudioArtistTable::make($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudioArtists::route('/'),
            'edit' => Pages\EditStudioArtist::route('/{record}/edit'),
        ];
    }

    // Scope au studio du user connecté
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $studio = auth()->user()->studio;
        if (!$studio) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
        return parent::getEloquentQuery()->where('studio_id', $studio->id);
    }
}
```

```php
// app/Filament/Studio/Resources/StudioArtistResource/Schemas/StudioArtistForm.php
namespace App\Filament\Studio\Resources\StudioArtistResource\Schemas;

use Filament\Forms;
use Filament\Forms\Form;

class StudioArtistForm
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('user.name')
                ->label('Nom')
                ->disabled(),
            Forms\Components\TextInput::make('user.email')
                ->label('Email')
                ->disabled(),
            Forms\Components\Select::make('artisan_type')
                ->label('Type')
                ->options([
                    'tattooer' => '🎨 Tatoueur',
                    'piercer' => '💎 Pierceur',
                ])
                ->disabled(),
            Forms\Components\Toggle::make('is_active')
                ->label('Actif'),
            Forms\Components\TextInput::make('commission_rate')
                ->label('Taux de commission (%)')
                ->numeric()
                ->step(0.01)
                ->placeholder('Défaut plateforme'),
        ]);
    }
}
```

```php
// app/Filament/Studio/Resources/StudioArtistResource/Tables/StudioArtistTable.php
namespace App\Filament\Studio\Resources\StudioArtistResource\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class StudioArtistTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('artisan_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('joined_at')
                    ->label('Rejoint le')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('artisan_type')
                    ->label('Type')
                    ->options([
                        'tattooer' => 'Tatoueur',
                        'piercer' => 'Pierceur',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Actif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
```

```php
// app/Filament/Studio/Resources/StudioArtistResource/Pages/ListStudioArtists.php
namespace App\Filament\Studio\Resources\StudioArtistResource\Pages;
use App\Filament\Studio\Resources\StudioArtistResource;
use Filament\Resources\Pages\ListRecords;
class ListStudioArtists extends ListRecords
{
    protected static string $resource = StudioArtistResource::class;
}

// app/Filament/Studio/Resources/StudioArtistResource/Pages/EditStudioArtist.php
namespace App\Filament\Studio\Resources\StudioArtistResource\Pages;
use App\Filament\Studio\Resources\StudioArtistResource;
use Filament\Resources\Pages\EditRecord;
class EditStudioArtist extends EditRecord
{
    protected static string $resource = StudioArtistResource::class;
}
```

### 5B. Resource : Booking Requests

```php
// app/Filament/Studio/Resources/BookingRequestResource.php
namespace App\Filament\Studio\Resources;

use App\Filament\Studio\Resources\BookingRequestResource\Pages;
use App\Filament\Studio\Resources\BookingRequestResource\Schemas\BookingRequestForm;
use App\Filament\Studio\Resources\BookingRequestResource\Tables\BookingRequestTable;
use App\Models\BookingRequest;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class BookingRequestResource extends Resource
{
    protected static ?string $model = BookingRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Demandes';
    protected static ?string $modelLabel = 'Demande';
    protected static ?string $pluralModelLabel = 'Demandes';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return BookingRequestForm::make($form);
    }

    public static function table(Table $table): Table
    {
        return BookingRequestTable::make($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingRequests::route('/'),
        ];
    }

    // Scope aux artistes du studio
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $studio = auth()->user()->studio;
        if (!$studio) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        $artistUserIds = $studio->studioArtists()->where('is_active', true)->pluck('user_id')->filter();
        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        return parent::getEloquentQuery()->where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $piercerIds);
            });
        });
    }
}
```

```php
// app/Filament/Studio/Resources/BookingRequestResource/Schemas/BookingRequestForm.php
namespace App\Filament\Studio\Resources\BookingRequestResource\Schemas;

use Filament\Forms;
use Filament\Forms\Form;

class BookingRequestForm
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            // Vue en lecture seule — le studio ne modifie pas les demandes
            Forms\Components\TextInput::make('status')
                ->label('Statut')
                ->disabled(),
        ]);
    }
}
```

```php
// app/Filament/Studio/Resources/BookingRequestResource/Tables/BookingRequestTable.php
namespace App\Filament\Studio\Resources\BookingRequestResource\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class BookingRequestTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.user.name')
                    ->label('Client')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bookable.user.name')
                    ->label('Artiste')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn ($state) => match($state?->value ?? $state) {
                        'pending' => 'warning',
                        'accepted', 'deposit_paid' => 'success',
                        'completed' => 'info',
                        'cancelled', 'refused' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('deposit_amount')
                    ->label('Acompte')
                    ->money('EUR', divideBy: 100),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options(fn () => collect(['pending', 'accepted', 'deposit_requested', 'deposit_paid', 'completed', 'cancelled', 'refused'])
                        ->mapWithKeys(fn ($s) => [$s => ucfirst($s)])
                        ->toArray()),
            ]);
    }
}
```

```php
// app/Filament/Studio/Resources/BookingRequestResource/Pages/ListBookingRequests.php
namespace App\Filament\Studio\Resources\BookingRequestResource\Pages;
use App\Filament\Studio\Resources\BookingRequestResource;
use Filament\Resources\Pages\ListRecords;
class ListBookingRequests extends ListRecords
{
    protected static string $resource = BookingRequestResource::class;
}
```

**CRITIQUE** : Les exemples ci-dessus utilisent `static function make(Form $form)` comme pattern. VÉRIFIER le pattern exact des Resources admin existantes et adapter. Si le pattern réel est différent (ex: `configure()`, `schema()`, constructeur), utiliser celui-là.

```bash
# Vérifier que les namespaces sont corrects
grep -rn "namespace" app/Filament/Studio/ --include="*.php" | head -20
```

```bash
git add -A && git commit -m "feat(studio): Resources Filament artistes + demandes avec scoping studio"
```

---

## PHASE 6 — WIDGETS DASHBOARD FILAMENT

```bash
mkdir -p app/Filament/Studio/Widgets
```

```php
// app/Filament/Studio/Widgets/StudioStatsOverview.php
namespace App\Filament\Studio\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudioStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $studio = auth()->user()->studio;
        if (!$studio) return [];

        $artistCount = $studio->studioArtists()->where('is_active', true)->count();
        
        $artistUserIds = $studio->studioArtists()->where('is_active', true)->pluck('user_id')->filter();
        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');
        
        $pendingRequests = \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $piercerIds);
            });
        })->where('status', 'pending')->count();

        $completedThisMonth = \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $piercerIds);
            });
        })->where('status', 'completed')->whereMonth('updated_at', now()->month)->count();

        return [
            Stat::make('Artistes actifs', $artistCount)
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Demandes en attente', $pendingRequests)
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Complétées ce mois', $completedThisMonth)
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Facturation', number_format($studio->monthlyPrice(), 2) . '€/mois')
                ->icon('heroicon-o-currency-euro')
                ->color('primary'),
        ];
    }
}
```

```bash
git add -A && git commit -m "feat(studio): widget stats overview Filament"
```

---

## PHASE 7 — VÉRIFICATION FINALE

```bash
# 7A. Billable
php artisan tinker --execute="
  \$s = App\Models\Studio::first();
  echo 'Billable: ' . (method_exists(\$s, 'subscription') ? 'OUI' : 'NON');
  echo PHP_EOL . 'stripe_id col: ' . (Schema::hasColumn('studios', 'stripe_id') ? 'OUI' : 'NON');
  echo PHP_EOL . 'stripeEmail: ' . \$s->stripeEmail();
"

# 7B. StudioBillingService
php artisan tinker --execute="
  echo 'StudioBillingService: ' . (class_exists('App\Services\StudioBillingService') ? 'OK' : 'ABSENT');
"

# 7C. Filament Panel
php artisan route:list 2>&1 | grep "studio/admin" | head -5

# 7D. Resources
find app/Filament/Studio -type f | sort

# 7E. Widgets
find app/Filament/Studio/Widgets -type f | sort

# 7F. Config
php artisan tinker --execute="
  echo 'studio_price_id: ' . config('services.stripe.studio_price_id', 'NON DÉFINI');
  echo PHP_EOL . 'studio_artist_price_id: ' . config('services.stripe.studio_artist_price_id', 'NON DÉFINI');
"

# 7G. Tout compile
php artisan route:clear
php artisan view:clear
php artisan route:list 2>&1 | head -3

echo "=== PROMPT 4/4 STUDIO — FILAMENT + BILLING TERMINÉ ==="
echo "=== IMPLÉMENTATION STUDIO COMPLÈTE ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire
2. **NE PAS toucher au panel admin existant** — Créer un panel SÉPARÉ
3. **Scoping studio** : toutes les Resources doivent filtrer par studio_id du user connecté
4. **Billable sur Studio** (pas sur User) — c'est le studio qui paie
5. **Les Price IDs Stripe sont dans .env** — ne pas les hardcoder
6. **Commit après chaque phase**
7. **Ne pas casser le Stripe Connect existant** — les artistes indépendants continuent de fonctionner
