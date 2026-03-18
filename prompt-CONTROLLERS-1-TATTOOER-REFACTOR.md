# 🔧 CONTROLLERS 1/2 — Découpage TattooerController (3062L → 13 controllers)
## Effort estimé : ~18h — À exécuter par groupes de méthodes

---

## ⚠️ STRATÉGIE IMPORTANTE
Ne pas tout faire en une seule fois.
Découper en **3 passes** pour éviter de casser les routes :

**Passe A** (ce prompt) : Créer les nouveaux controllers + copier les méthodes
**Passe B** : Mettre à jour les routes pour pointer vers les nouveaux controllers
**Passe C** : Supprimer les méthodes de TattooerController

---

## PHASE 1 — AUDIT PRÉALABLE

```bash
# Taille actuelle
wc -l app/Http/Controllers/TattooerController.php
grep -c "public function" app/Http/Controllers/TattooerController.php

# Vérifier les routes actuelles
php artisan route:list | grep "tattooer\|tattoo" | \
  awk '{print $3, $5, $7}' | sort | head -60

# Vérifier les imports/use dans TattooerController
head -50 app/Http/Controllers/TattooerController.php

# Services injectés dans le constructeur
grep -A 20 "public function __construct" app/Http/Controllers/TattooerController.php
```

---

## PHASE 2 — CRÉER LES NOUVEAUX CONTROLLERS

### Plan de découpage (basé sur AUDIT_CONTROLLERS.md)

```bash
# Créer tous les dossiers et fichiers
mkdir -p app/Http/Controllers/Tattooer

php artisan make:controller Tattooer/TattooerDashboardController
php artisan make:controller Tattooer/TattooerBookingController
php artisan make:controller Tattooer/TattooerCalendarController
php artisan make:controller Tattooer/TattooerMessageController
php artisan make:controller Tattooer/TattooerClientController
php artisan make:controller Tattooer/TattooerConsentController
php artisan make:controller Tattooer/TattooerTraceabilityController
php artisan make:controller Tattooer/TattooerMediaController
php artisan make:controller Tattooer/TattooerPortfolioController
php artisan make:controller Tattooer/TattooerSettingsController
php artisan make:controller Tattooer/TattooerPaymentController
php artisan make:controller Tattooer/TattooerAppointmentController
php artisan make:controller Tattooer/TattooerComplianceController
```

---

## PHASE 3 — DÉPLACER LES MÉTHODES

Pour chaque nouveau controller, copier les méthodes depuis TattooerController.
**Lire TattooerController.php intégralement avant de commencer.**

### Structure de base de chaque nouveau controller

```php
<?php
namespace App\Http\Controllers\Tattooer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Importer les mêmes classes que TattooerController selon les méthodes

class TattooerXxxController extends Controller
{
    public function __construct(
        // Injecter uniquement les services utilisés par CE controller
        // (pas tous les services de TattooerController)
    ) {}
}
```

---

### 3.1 — TattooerDashboardController
**Méthodes** : `dashboard()` (L.413), `profile()` (L.45)

```php
// Services nécessaires : TattooerStatsService, CacheService
// Vues : tattooer/dashboard.blade.php, tattooer/profile.blade.php
```

### 3.2 — TattooerBookingController
**Méthodes** : `requests()` (L.96), `requestShow()` (L.164),
`acceptRequest()` (L.2190), `requestReject()` (L.2266),
`reproposeDates()` (L.2334), `destroyRequest()` (si existe),
`cancelRequest()` (si existe), `completeBooking()` (L.2708),
`markNoShow()` (L.2732)

```php
// Services nécessaires : CancellationService, NotificationService
// IMPORTANT : vérifier que get_class($tattooer) est utilisé
// (pas App\Models\Tattooer hardcodé) — déjà corrigé dans fix critique
```

### 3.3 — TattooerCalendarController
**Méthodes** : `calendar()` (L.483), `calendarStore()` (L.609),
`calendarUpdate()` (L.661), `calendarDestroy()` (L.692),
`calendarEvents()` (L.730)

```php
// Services nécessaires : aucun service dédié identifié
// Note : le JS calendar a été externalisé dans resources/js/tattooer-calendar.js
```

### 3.4 — TattooerMessageController
**Méthodes** : `messages()` (L.1021), `messageShow()` (L.811),
`messageSend()` (L.883)

```php
// Services nécessaires : aucun identifié
// Note : vérifier la relation avec Conversation et Message
```

### 3.5 — TattooerClientController
**Méthodes** : `clients()` (L.1065), `clientShow()` (L.1151),
`updateClient()` (L.1268), `createClient()` (L.2504),
`storeClient()` (L.2512), `updateClientNotes()` (L.1675),
`clientRequests()` (si existe)

```php
// Vérifier les Form Requests créés dans le prompt jaune :
// AcceptBookingRequest, CreateBookingRequest → adapter ici
```

### 3.6 — TattooerConsentController
**Méthodes** : `storeConsent()` (L.1533), `storeDigitalConsent()` (L.2586),
`uploadConsent()` (L.1323), `deleteConsent()` (L.1363)

```php
// Services : SecureFileUpload middleware déjà en place
// RGPD : ces méthodes gèrent des données de santé
// → vérifier que les uploads vont sur disk 'private'
```

### 3.7 — TattooerTraceabilityController
**Méthodes** : `storeTraceability()` (L.1585),
`storeClientTraceability()` (L.1394)

### 3.8 — TattooerMediaController
**Méthodes** : `uploadClientPhotos()` (L.1462),
`deleteClientPhoto()` (L.1502), `uploadClientTattooPhotos()` (L.1698),
`deleteClientMedia()` (L.1727), `deleteAvatar()` (L.385),
`deleteBanner()` (L.399)

```php
// Vérifier SecureFileUpload middleware sur toutes les routes d'upload
```

### 3.9 — TattooerPortfolioController
**Méthodes** : `portfolio()` (L.1759), `portfolioUpload()` (L.1815),
`portfolioBeforeAfterStore()` (L.1876), `portfolioDestroy()` (L.1940),
`portfolioBeforeAfterDestroy()` (L.1984)

```php
// Services : Spatie MediaLibrary
// Vérifier limite portfolio (20 STARTER / 100 PRO) → HasSubscription::getPortfolioLimit()
```

### 3.10 — TattooerSettingsController
**Méthodes** : `settings()` (L.216), `settingsUpdate()` (L.251),
`settingsUpdateSchedule()` (L.2030), `settingsAftercareUpdate()` (si existe),
`settingsPricingUpdate()` (si existe)

```php
// Form Requests : UpdateProfileRequest déjà créé dans prompt jaune
```

### 3.11 — TattooerPaymentController
**Méthodes** : `payments()` (L.2093), `connectStripe()` (L.2160)

```php
// Services : StripeService (injecté par constructeur — déjà fait)
// Note : la route /tattooer/payments a le middleware artist.2fa
```

### 3.12 — TattooerAppointmentController
**Méthodes** : `completeAppointment()` (L.2431), `reportNoShow()` (L.2454)

### 3.13 — TattooerComplianceController
**Méthodes** : `compliance()` (si existe), `complianceDocuments()` (si existe),
`complianceDocumentsUpload()` (si existe), `complianceDocumentDelete()` (si existe)

```bash
# Vérifier les méthodes compliance dans TattooerController
grep -n "compliance\|Compliance" app/Http/Controllers/TattooerController.php
```

---

## PHASE 4 — METTRE À JOUR LES ROUTES

Dans `routes/web.php`, remplacer les références à `TattooerController` :

```php
use App\Http\Controllers\Tattooer\TattooerDashboardController;
use App\Http\Controllers\Tattooer\TattooerBookingController;
use App\Http\Controllers\Tattooer\TattooerCalendarController;
use App\Http\Controllers\Tattooer\TattooerMessageController;
use App\Http\Controllers\Tattooer\TattooerClientController;
use App\Http\Controllers\Tattooer\TattooerConsentController;
use App\Http\Controllers\Tattooer\TattooerTraceabilityController;
use App\Http\Controllers\Tattooer\TattooerMediaController;
use App\Http\Controllers\Tattooer\TattooerPortfolioController;
use App\Http\Controllers\Tattooer\TattooerSettingsController;
use App\Http\Controllers\Tattooer\TattooerPaymentController;
use App\Http\Controllers\Tattooer\TattooerAppointmentController;
use App\Http\Controllers\Tattooer\TattooerComplianceController;

Route::middleware(['auth', 'verified', 'artisan.can.operate'])
    ->prefix('tattooer')
    ->name('tattooer.')
    ->group(function () {

        // Dashboard & Profil
        Route::get('/dashboard',   [TattooerDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile',     [TattooerDashboardController::class, 'profile'])->name('profile');

        // Réservations
        Route::get('/requests',                    [TattooerBookingController::class, 'requests'])->name('requests');
        Route::get('/requests/{id}',               [TattooerBookingController::class, 'requestShow'])->name('requests.show');
        Route::post('/requests/{id}/accept',       [TattooerBookingController::class, 'acceptRequest'])->name('requests.accept');
        Route::post('/requests/{id}/reject',       [TattooerBookingController::class, 'requestReject'])->name('requests.reject');
        Route::post('/requests/{id}/repropose',    [TattooerBookingController::class, 'reproposeDates'])->name('requests.repropose');
        Route::patch('/requests/{id}/cancel',      [TattooerBookingController::class, 'cancelRequest'])->name('requests.cancel');
        Route::delete('/requests/{id}',            [TattooerBookingController::class, 'destroyRequest'])->name('requests.destroy');
        Route::post('/requests/{id}/complete',     [TattooerBookingController::class, 'completeBooking'])->name('requests.complete');
        Route::post('/requests/{id}/no-show',      [TattooerBookingController::class, 'markNoShow'])->name('requests.no-show');

        // Calendrier
        Route::get('/calendar',                    [TattooerCalendarController::class, 'calendar'])->name('calendar');
        Route::post('/calendar',                   [TattooerCalendarController::class, 'calendarStore'])->name('calendar.store');
        Route::put('/calendar/{id}',               [TattooerCalendarController::class, 'calendarUpdate'])->name('calendar.update');
        Route::delete('/calendar/{id}',            [TattooerCalendarController::class, 'calendarDestroy'])->name('calendar.destroy');
        Route::get('/calendar/events',             [TattooerCalendarController::class, 'calendarEvents'])->name('calendar.events');

        // Messages
        Route::get('/messages',                    [TattooerMessageController::class, 'messages'])->name('messages');
        Route::get('/messages/{id}',               [TattooerMessageController::class, 'messageShow'])->name('messages.show');
        Route::post('/messages/{id}',              [TattooerMessageController::class, 'messageSend'])->name('messages.send');

        // Clients
        Route::get('/clients',                     [TattooerClientController::class, 'clients'])->name('clients');
        Route::get('/clients/{id}',                [TattooerClientController::class, 'clientShow'])->name('clients.show');
        Route::put('/clients/{id}',                [TattooerClientController::class, 'updateClient'])->name('clients.update');
        Route::get('/clients/create',              [TattooerClientController::class, 'createClient'])->name('clients.create');
        Route::post('/clients',                    [TattooerClientController::class, 'storeClient'])->name('clients.store');
        Route::put('/clients/{id}/notes',          [TattooerClientController::class, 'updateClientNotes'])->name('clients.notes');

        // Portfolio
        Route::get('/portfolio',                   [TattooerPortfolioController::class, 'portfolio'])->name('portfolio');
        Route::post('/portfolio/upload',           [TattooerPortfolioController::class, 'portfolioUpload'])->name('portfolio.upload');
        Route::delete('/portfolio/{id}',           [TattooerPortfolioController::class, 'portfolioDestroy'])->name('portfolio.destroy');

        // Settings
        Route::get('/settings',                    [TattooerSettingsController::class, 'settings'])->name('settings');
        Route::post('/settings',                   [TattooerSettingsController::class, 'settingsUpdate'])->name('settings.update');

        // Paiements (middleware 2FA artiste Connect)
        Route::middleware(['artist.2fa'])->group(function () {
            Route::get('/payments',                [TattooerPaymentController::class, 'payments'])->name('payments');
            Route::get('/stripe/connect',          [TattooerPaymentController::class, 'connectStripe'])->name('stripe.connect');
        });

        // Compliance & Médias — adapter selon les méthodes trouvées
    });
```

---

## PHASE 5 — NETTOYER TattooerController

Une fois toutes les méthodes migrées et les routes mises à jour :

```bash
# Vérifier qu'aucune route ne pointe encore vers TattooerController
php artisan route:list | grep "TattooerController"
# Doit retourner vide (ou seulement les méthodes non encore migrées)

# Si vide → supprimer les méthodes migrées de TattooerController
# Ou supprimer le fichier entièrement si tout est migré
```

---

## VALIDATION

```bash
# Toutes les routes tattooer fonctionnent
php artisan route:list | grep "tattooer\." | wc -l

# Pas d'erreur de classe introuvable
php artisan route:cache 2>&1 | head -20

# Test manuel des routes principales
# → /tattooer/dashboard
# → /tattooer/requests
# → /tattooer/clients
# → /tattooer/settings
# → /tattooer/payments

# Taille de TattooerController après nettoyage
wc -l app/Http/Controllers/TattooerController.php
# Doit être < 100 lignes (imports + constructeur vide)
```

## ⚠️ CONTRAINTES CRITIQUES
- Copier les méthodes EXACTEMENT (ne pas modifier la logique)
- Garder les mêmes noms de routes (les vues utilisent route('tattooer.xxx'))
- Tester chaque groupe de méthodes avant de passer au suivant
- Ne pas supprimer les méthodes de TattooerController avant que les routes soient validées
- Si une méthode utilise une propriété/méthode privée de TattooerController :
  l'extraire aussi ou la transformer en service
- Rapport final : taille TattooerController avant/après + liste controllers créés
