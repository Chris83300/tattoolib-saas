# 🏦 IMPLÉMENTATION STRIPE CONNECT — Ink&Pik

## Vue d'ensemble architecture

Trois profils distincts avec des flux Stripe Connect différents :

```
PLATEFORME (Ink&Pik)
├── Tattooer/Piercer indépendant
│   ├── STARTER : Stripe Connect propre → application_fee 7%
│   └── PRO     : Stripe Connect propre → application_fee 0%
│
└── Studio
    ├── Mode "Paiement Studio"
    │   ├── Studio : Stripe Connect propre (reçoit les paiements)
    │   ├── Artistes studio : PAS de Stripe Connect
    │   └── Commission studio : % libre défini par le studio (0% à 99%)
    │
    └── Mode "Paiement Direct Artiste"
        ├── Studio : PAS de Stripe Connect
        ├── Artistes studio : Stripe Connect propre (chacun)
        └── Commission studio : % libre prélevé via application_fee sur l'artiste
```

---

## PHASE 1 — AUDIT PRÉALABLE (lecture seule)

### 1.1 — Cartographier l'existant Stripe

```bash
# Chercher tout ce qui touche à Stripe Connect actuellement
grep -r "stripe_connect\|connect_id\|account_id\|transfer_data\|application_fee" \
  app/ database/ --include="*.php" -l

# Vérifier les colonnes actuelles liées à Stripe
grep -r "stripe_" database/migrations/ --include="*.php" -l
grep -r "stripe_" app/Models/ --include="*.php"

# Chercher les controllers de paiement
grep -r "PaymentIntent\|Charge\|Transfer\|checkout\|BalancePayment" \
  app/Http/Controllers/ --include="*.php" -l

# Vérifier le mode paiement studio actuel
grep -r "payment_mode\|studio_payment\|direct_payment" app/ --include="*.php"
```

### 1.2 — Lire intégralement avant de modifier

- `app/Http/Controllers/BalancePaymentController.php`
- `app/Http/Controllers/Api/PaymentController.php` (si existe)
- `app/Models/Studio.php` (colonnes, relations)
- `app/Models/Tattooer.php` et `app/Models/Piercer.php` (colonnes Stripe existantes)
- `app/Models/User.php` (Billable Cashier, stripe_id)
- Les migrations des tables `studios`, `tattooers`, `piercers`, `users`

### 1.3 — Afficher l'état actuel des colonnes Stripe en DB

```bash
php artisan tinker --execute="
  \$cols = fn(\$t) => \Illuminate\Support\Facades\Schema::getColumnListing(\$t);
  dd([
    'users'    => array_filter(\$cols('users'),    fn(\$c) => str_contains(\$c, 'stripe')),
    'studios'  => array_filter(\$cols('studios'),   fn(\$c) => str_contains(\$c, 'stripe') || str_contains(\$c, 'payment')),
    'tattooers'=> array_filter(\$cols('tattooers'), fn(\$c) => str_contains(\$c, 'stripe')),
    'piercers' => array_filter(\$cols('piercers'),  fn(\$c) => str_contains(\$c, 'stripe')),
  ]);
"
```

---

## PHASE 2 — MIGRATIONS

Créer UNE migration par table modifiée (ne pas toucher aux migrations existantes).

### 2.1 — Table `users` (artistes indépendants)

```bash
php artisan make:migration add_stripe_connect_to_users_table
```

```php
Schema::table('users', function (Blueprint $table) {
    // Stripe Connect Express account ID
    $table->string('stripe_connect_id')->nullable()->after('stripe_id');
    // Statut onboarding Connect
    $table->enum('stripe_connect_status', [
        'not_started',  // pas encore initié
        'pending',      // onboarding en cours
        'active',       // compte actif, peut recevoir des paiements
        'restricted',   // restrictions (vérification en attente)
        'disabled',     // désactivé
    ])->default('not_started')->after('stripe_connect_id');
    // Données onboarding retournées par Stripe
    $table->boolean('stripe_connect_charges_enabled')->default(false)->after('stripe_connect_status');
    $table->boolean('stripe_connect_payouts_enabled')->default(false)->after('stripe_connect_charges_enabled');
});
```

### 2.2 — Table `studios`

```bash
php artisan make:migration add_stripe_connect_to_studios_table
```

```php
Schema::table('studios', function (Blueprint $table) {
    // Mode de paiement choisi par le studio
    $table->enum('payment_mode', ['studio', 'direct_artist'])
          ->default('studio')
          ->after('id')
          ->comment('studio=paiements centralisés, direct_artist=paiements directs aux artistes');

    // Stripe Connect du studio (si payment_mode = studio)
    $table->string('stripe_connect_id')->nullable()->after('payment_mode');
    $table->enum('stripe_connect_status', [
        'not_started', 'pending', 'active', 'restricted', 'disabled'
    ])->default('not_started')->after('stripe_connect_id');
    $table->boolean('stripe_connect_charges_enabled')->default(false)->after('stripe_connect_status');
    $table->boolean('stripe_connect_payouts_enabled')->default(false)->after('stripe_connect_charges_enabled');

    // Commission personnalisée du studio sur ses artistes (0.00 à 99.99)
    // null = pas de commission, 0.00 = commission 0%, 15.00 = 15%
    $table->decimal('artist_commission_rate', 5, 2)->nullable()->after('stripe_connect_payouts_enabled')
          ->comment('% prélevé par le studio sur les transactions de ses artistes (null = aucun)');
});
```

### 2.3 — Table `tattooers` et `piercers` (artistes studio)

```bash
php artisan make:migration add_stripe_connect_to_tattooers_piercers_table
```

```php
Schema::table('tattooers', function (Blueprint $table) {
    // Connect propre de l'artiste (si indépendant OU si studio payment_mode=direct_artist)
    $table->string('stripe_connect_id')->nullable()->after('current_plan');
    $table->enum('stripe_connect_status', [
        'not_started', 'pending', 'active', 'restricted', 'disabled', 'blocked_by_studio'
    ])->default('not_started')->after('stripe_connect_id');
    $table->boolean('stripe_connect_charges_enabled')->default(false)->after('stripe_connect_status');
    $table->boolean('stripe_connect_payouts_enabled')->default(false)->after('stripe_connect_charges_enabled');
});

// Même chose pour piercers
Schema::table('piercers', function (Blueprint $table) {
    $table->string('stripe_connect_id')->nullable()->after('current_plan');
    $table->enum('stripe_connect_status', [
        'not_started', 'pending', 'active', 'restricted', 'disabled', 'blocked_by_studio'
    ])->default('not_started')->after('stripe_connect_id');
    $table->boolean('stripe_connect_charges_enabled')->default(false)->after('stripe_connect_status');
    $table->boolean('stripe_connect_payouts_enabled')->default(false)->after('stripe_connect_charges_enabled');
});
```

---

## PHASE 3 — SERVICE STRIPE CONNECT

### 3.1 — Créer `app/Services/StripeConnectService.php`

Ce service centralise TOUTE la logique Stripe Connect.

```php
namespace App\Services;

use Stripe\StripeClient;
use App\Models\User;
use App\Models\Studio;
use App\Models\Tattooer;
use App\Models\Piercer;

class StripeConnectService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('cashier.secret'));
    }

    // ─────────────────────────────────────────────
    // CRÉATION COMPTE CONNECT (Express)
    // ─────────────────────────────────────────────

    /**
     * Créer un compte Connect Express pour un artiste indépendant (User)
     */
    public function createAccountForUser(User $user): string
    {
        $account = $this->stripe->accounts->create([
            'type'         => 'express',
            'country'      => 'FR',
            'email'        => $user->email,
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers'     => ['requested' => true],
            ],
            'business_type' => 'individual',
            'metadata'      => [
                'user_id'  => $user->id,
                'app'      => 'inkpik',
                'type'     => 'independent_artist',
            ],
        ]);

        $user->update([
            'stripe_connect_id'     => $account->id,
            'stripe_connect_status' => 'pending',
        ]);

        return $account->id;
    }

    /**
     * Créer un compte Connect Express pour un Studio
     */
    public function createAccountForStudio(Studio $studio): string
    {
        $account = $this->stripe->accounts->create([
            'type'         => 'express',
            'country'      => 'FR',
            'email'        => $studio->user->email,
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers'     => ['requested' => true],
            ],
            'business_type' => 'company',
            'metadata'      => [
                'studio_id' => $studio->id,
                'app'       => 'inkpik',
                'type'      => 'studio',
            ],
        ]);

        $studio->update([
            'stripe_connect_id'     => $account->id,
            'stripe_connect_status' => 'pending',
        ]);

        return $account->id;
    }

    // ─────────────────────────────────────────────
    // LIEN ONBOARDING
    // ─────────────────────────────────────────────

    /**
     * Générer le lien d'onboarding Stripe Express
     * @param string $accountId  stripe_connect_id
     * @param string $returnUrl  URL de retour après onboarding
     * @param string $refreshUrl URL si le lien expire
     */
    public function createOnboardingLink(
        string $accountId,
        string $returnUrl,
        string $refreshUrl
    ): string {
        $link = $this->stripe->accountLinks->create([
            'account'     => $accountId,
            'refresh_url' => $refreshUrl,
            'return_url'  => $returnUrl,
            'type'        => 'account_onboarding',
        ]);

        return $link->url;
    }

    /**
     * Lien vers le dashboard Stripe Express (pour l'artiste/studio)
     */
    public function createDashboardLink(string $accountId): string
    {
        $link = $this->stripe->accounts->createLoginLink($accountId);
        return $link->url;
    }

    // ─────────────────────────────────────────────
    // SYNCHRONISATION STATUT
    // ─────────────────────────────────────────────

    /**
     * Mettre à jour le statut Connect depuis Stripe (à appeler après onboarding)
     * Retourne true si le compte est actif et peut recevoir des paiements
     */
    public function syncAccountStatus(string $accountId): array
    {
        $account = $this->stripe->accounts->retrieve($accountId);

        return [
            'charges_enabled' => $account->charges_enabled,
            'payouts_enabled' => $account->payouts_enabled,
            'status'          => $this->resolveStatus($account),
            'requirements'    => $account->requirements?->currently_due ?? [],
        ];
    }

    private function resolveStatus($account): string
    {
        if ($account->charges_enabled && $account->payouts_enabled) return 'active';
        if (!empty($account->requirements?->currently_due))           return 'restricted';
        if ($account->details_submitted)                              return 'pending';
        return 'not_started';
    }

    // ─────────────────────────────────────────────
    // CALCUL APPLICATION FEE
    // ─────────────────────────────────────────────

    /**
     * Calculer le montant de l'application fee en centimes
     *
     * @param int        $amountCents  Montant total en centimes
     * @param mixed      $artist       Tattooer|Piercer
     * @param Studio|null $studio      Studio si artiste studio
     */
    public function calculateApplicationFee(
        int $amountCents,
        $artist,
        ?Studio $studio = null
    ): int {
        // Artiste de studio
        if ($studio) {
            $rate = $studio->artist_commission_rate; // null ou 0.00 à 99.99
            if (is_null($rate) || $rate <= 0) return 0;
            return (int) round($amountCents * ($rate / 100));
        }

        // Artiste indépendant
        if ($artist->isStarter()) {
            return (int) round($amountCents * 0.07); // 7%
        }

        return 0; // PRO = 0%
    }

    // ─────────────────────────────────────────────
    // RÉSOUDRE LE COMPTE DESTINATAIRE
    // ─────────────────────────────────────────────

    /**
     * Retourner le stripe_connect_id qui doit recevoir le paiement
     * selon le mode studio ou l'indépendance de l'artiste
     */
    public function resolveDestinationAccount($artist, ?Studio $studio = null): ?string
    {
        // Artiste de studio
        if ($studio) {
            if ($studio->payment_mode === 'studio') {
                // Paiement centralisé studio
                return $studio->stripe_connect_id;
            } else {
                // Paiement direct artiste
                return $artist->stripe_connect_id;
            }
        }

        // Artiste indépendant → son propre compte
        return $artist->user->stripe_connect_id ?? null;
    }
}
```

---

## PHASE 4 — MISE À JOUR DES MODÈLES

### 4.1 — `app/Models/User.php`

Ajouter les helpers Connect sur le User (pour artistes indépendants) :

```php
// Dans User.php

public function hasStripeConnect(): bool
{
    return !is_null($this->stripe_connect_id);
}

public function isStripeConnectActive(): bool
{
    return $this->stripe_connect_status === 'active'
        && $this->stripe_connect_charges_enabled;
}
```

### 4.2 — `app/Models/Studio.php`

```php
// Dans Studio.php

public function hasStripeConnect(): bool
{
    return !is_null($this->stripe_connect_id);
}

public function isStripeConnectActive(): bool
{
    return $this->stripe_connect_status === 'active'
        && $this->stripe_connect_charges_enabled;
}

public function isPaymentModeStudio(): bool
{
    return $this->payment_mode === 'studio';
}

public function isPaymentModeDirect(): bool
{
    return $this->payment_mode === 'direct_artist';
}

/**
 * Commission du studio sur ses artistes (null = désactivée)
 */
public function getArtistCommissionRate(): ?float
{
    return $this->artist_commission_rate;
}
```

### 4.3 — `app/Traits/HasStripeConnect.php` (nouveau trait partagé Tattooer/Piercer)

```php
namespace App\Traits;

trait HasStripeConnect
{
    public function hasStripeConnect(): bool
    {
        return !is_null($this->stripe_connect_id);
    }

    public function isStripeConnectActive(): bool
    {
        return $this->stripe_connect_status === 'active'
            && $this->stripe_connect_charges_enabled;
    }

    public function isStripeConnectBlockedByStudio(): bool
    {
        return $this->stripe_connect_status === 'blocked_by_studio';
    }

    /**
     * Vérifier si cet artiste peut configurer son propre Connect
     * (indépendant, ou studio en mode direct_artist)
     */
    public function canSetupStripeConnect(): bool
    {
        if ($this->isStripeConnectBlockedByStudio()) return false;

        // Si artiste studio, vérifier le payment_mode
        $studio = $this->studioArtist?->studio ?? null;
        if ($studio) {
            return $studio->isPaymentModeDirect();
        }

        return true; // Indépendant
    }
}
```

Ajouter `use HasStripeConnect;` dans `Tattooer.php` et `Piercer.php`.

---

## PHASE 5 — CONTROLLER STRIPE CONNECT

### 5.1 — Créer `app/Http/Controllers/StripeConnectController.php`

```php
namespace App\Http\Controllers;

use App\Services\StripeConnectService;
use App\Models\Studio;
use Illuminate\Http\Request;

class StripeConnectController extends Controller
{
    public function __construct(protected StripeConnectService $connectService) {}

    // ─── ARTISTE INDÉPENDANT ───────────────────────────────────

    /**
     * Initier l'onboarding Connect pour un artiste indépendant
     */
    public function onboardArtist(Request $request)
    {
        $user   = $request->user();
        $artist = $user->tattooer ?? $user->piercer;

        abort_unless($artist, 403, 'Profil artiste introuvable');
        abort_if($artist->isStripeConnectBlockedByStudio(), 403,
            'Votre studio gère les paiements. Vous ne pouvez pas configurer Stripe Connect.');
        abort_unless($artist->canSetupStripeConnect(), 403, 'Non autorisé');

        // Créer le compte si pas encore fait
        if (!$user->stripe_connect_id) {
            $this->connectService->createAccountForUser($user);
        }

        $onboardingUrl = $this->connectService->createOnboardingLink(
            $user->stripe_connect_id,
            route('stripe.connect.return'),
            route('stripe.connect.refresh'),
        );

        return redirect($onboardingUrl);
    }

    /**
     * Retour après onboarding artiste
     */
    public function returnFromOnboarding(Request $request)
    {
        $user   = $request->user();
        $artist = $user->tattooer ?? $user->piercer;

        // Synchroniser le statut
        if ($user->stripe_connect_id) {
            $status = $this->connectService->syncAccountStatus($user->stripe_connect_id);
            $user->update([
                'stripe_connect_status'          => $status['status'],
                'stripe_connect_charges_enabled' => $status['charges_enabled'],
                'stripe_connect_payouts_enabled' => $status['payouts_enabled'],
            ]);

            // Sync aussi sur le modèle artiste
            if ($artist) {
                $artist->update([
                    'stripe_connect_id'              => $user->stripe_connect_id,
                    'stripe_connect_status'          => $status['status'],
                    'stripe_connect_charges_enabled' => $status['charges_enabled'],
                    'stripe_connect_payouts_enabled' => $status['payouts_enabled'],
                ]);
            }
        }

        $message = $user->isStripeConnectActive()
            ? 'Votre compte Stripe Connect est actif ! Vous pouvez recevoir des paiements.'
            : 'Compte en cours de vérification. Vous recevrez un email de Stripe.';

        return redirect()->route('tattooer.settings') // adapter selon le type artiste
            ->with('success', $message);
    }

    /**
     * Lien vers le dashboard Stripe Express de l'artiste
     */
    public function artistDashboard(Request $request)
    {
        $user = $request->user();
        abort_unless($user->stripe_connect_id && $user->isStripeConnectActive(), 403,
            'Compte Stripe Connect non configuré ou inactif');

        $url = $this->connectService->createDashboardLink($user->stripe_connect_id);
        return redirect($url);
    }

    // ─── STUDIO ───────────────────────────────────────────────

    /**
     * Initier l'onboarding Connect pour un Studio
     */
    public function onboardStudio(Request $request)
    {
        $user   = $request->user();
        $studio = $user->studio;

        abort_unless($studio, 403, 'Studio introuvable');
        abort_unless($studio->isPaymentModeStudio(), 403,
            'Votre studio est en mode paiement direct artiste. Configurez Stripe Connect sur chaque artiste.');

        if (!$studio->stripe_connect_id) {
            $this->connectService->createAccountForStudio($studio);
        }

        $onboardingUrl = $this->connectService->createOnboardingLink(
            $studio->stripe_connect_id,
            route('stripe.connect.studio.return'),
            route('stripe.connect.studio.refresh'),
        );

        return redirect($onboardingUrl);
    }

    /**
     * Retour après onboarding studio
     */
    public function returnFromOnboardingStudio(Request $request)
    {
        $user   = $request->user();
        $studio = $user->studio;

        if ($studio?->stripe_connect_id) {
            $status = $this->connectService->syncAccountStatus($studio->stripe_connect_id);
            $studio->update([
                'stripe_connect_status'          => $status['status'],
                'stripe_connect_charges_enabled' => $status['charges_enabled'],
                'stripe_connect_payouts_enabled' => $status['payouts_enabled'],
            ]);
        }

        return redirect()->route('studio.settings')
            ->with('success', 'Compte Stripe Connect studio mis à jour.');
    }

    // ─── PARAMÈTRES STUDIO ────────────────────────────────────

    /**
     * Mettre à jour le mode de paiement + commission du studio
     */
    public function updateStudioPaymentSettings(Request $request)
    {
        $request->validate([
            'payment_mode'          => 'required|in:studio,direct_artist',
            'artist_commission_rate' => 'nullable|numeric|min:0|max:99.99',
        ]);

        $studio = $request->user()->studio;
        abort_unless($studio, 403);

        $newMode    = $request->payment_mode;
        $oldMode    = $studio->payment_mode;

        $studio->update([
            'payment_mode'           => $newMode,
            'artist_commission_rate' => $request->artist_commission_rate,
        ]);

        // Bloquer/débloquer les artistes selon le mode
        if ($newMode === 'studio' && $oldMode !== 'studio') {
            // Studio prend les paiements → bloquer Connect des artistes
            $this->blockArtistsConnect($studio);
        } elseif ($newMode === 'direct_artist' && $oldMode !== 'direct_artist') {
            // Paiement direct → débloquer les artistes, bloquer le studio
            $this->unblockArtistsConnect($studio);
        }

        return back()->with('success', 'Paramètres de paiement mis à jour.');
    }

    private function blockArtistsConnect(Studio $studio): void
    {
        // Bloquer tous les artistes du studio
        foreach ($studio->tattooers as $tattooer) {
            $tattooer->update(['stripe_connect_status' => 'blocked_by_studio']);
        }
        foreach ($studio->piercers as $piercer) {
            $piercer->update(['stripe_connect_status' => 'blocked_by_studio']);
        }
    }

    private function unblockArtistsConnect(Studio $studio): void
    {
        // Remettre à not_started les artistes bloqués (ils devront onboarder)
        foreach ($studio->tattooers as $tattooer) {
            if ($tattooer->stripe_connect_status === 'blocked_by_studio') {
                $tattooer->update([
                    'stripe_connect_status' => 'not_started',
                    'stripe_connect_id'     => null, // reset, ils devront re-onboarder
                ]);
            }
        }
        foreach ($studio->piercers as $piercer) {
            if ($piercer->stripe_connect_status === 'blocked_by_studio') {
                $piercer->update([
                    'stripe_connect_status' => 'not_started',
                    'stripe_connect_id'     => null,
                ]);
            }
        }
        // Vider le Connect du studio (il ne reçoit plus les paiements)
        // NE PAS supprimer le compte Stripe (juste ignorer) — il pourrait rechanger
    }
}
```

---

## PHASE 6 — MISE À JOUR DES CONTROLLERS DE PAIEMENT

### 6.1 — Mettre à jour `BalancePaymentController` (et `Api/PaymentController`)

Injecter `StripeConnectService` et remplacer le calcul fee + destination :

```php
// Remplacer la création du PaymentIntent par :

$artist = $booking->tattooer ?? $booking->piercer;
$studio = $artist->studioArtist?->studio ?? null;

$destinationAccount = $this->connectService->resolveDestinationAccount($artist, $studio);
$feeAmount          = $this->connectService->calculateApplicationFee($amountCents, $artist, $studio);

// Vérification : le destinataire doit avoir un compte Connect actif
if (!$destinationAccount) {
    return back()->with('error',
        'Le compte de paiement de cet artiste n\'est pas encore configuré.');
}

$paymentIntentData = [
    'amount'               => $amountCents,
    'currency'             => 'eur',
    'payment_method_types' => ['card'],
    'transfer_data'        => ['destination' => $destinationAccount],
    'metadata'             => [
        'booking_id' => $booking->id,
        'artist_id'  => $artist->id,
        'plan'       => $artist->current_plan,
        'studio_id'  => $studio?->id,
    ],
];

// Ajouter l'application fee seulement si > 0
if ($feeAmount > 0) {
    $paymentIntentData['application_fee_amount'] = $feeAmount;
}

$paymentIntent = \Stripe\PaymentIntent::create($paymentIntentData);
```

---

## PHASE 7 — ROUTES

Ajouter dans `routes/web.php` :

```php
// ─── Stripe Connect — Artiste indépendant ───────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('stripe/connect')->name('stripe.connect.')->group(function () {

    // Onboarding
    Route::get('/onboard',  [StripeConnectController::class, 'onboardArtist'])->name('onboard');
    Route::get('/return',   [StripeConnectController::class, 'returnFromOnboarding'])->name('return');
    Route::get('/refresh',  fn() => redirect()->route('stripe.connect.onboard'))->name('refresh');

    // Dashboard Express
    Route::get('/dashboard', [StripeConnectController::class, 'artistDashboard'])->name('dashboard');
});

// ─── Stripe Connect — Studio ─────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('stripe/connect/studio')->name('stripe.connect.studio.')->group(function () {

    Route::get('/onboard', [StripeConnectController::class, 'onboardStudio'])->name('onboard');
    Route::get('/return',  [StripeConnectController::class, 'returnFromOnboardingStudio'])->name('return');
    Route::get('/refresh', fn() => redirect()->route('stripe.connect.studio.onboard'))->name('refresh');
    Route::get('/dashboard', [StripeConnectController::class, 'artistDashboard'])->name('dashboard');

    // Paramètres paiement + commission
    Route::post('/settings', [StripeConnectController::class, 'updateStudioPaymentSettings'])->name('settings');
});
```

**Ajouter la route webhook Stripe dans `routes/web.php` (hors groupe auth) :**
```php
Route::post('stripe/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook')
    ->name('stripe.webhook');
```

**Exclure du CSRF dans `app/Http/Middleware/VerifyCsrfToken.php` :**
```php
protected $except = [
    'stripe/webhook',
    'stripe/connect/*',
];
```

---

## PHASE 8 — VUES À CRÉER / METTRE À JOUR

### 8.1 — Bloc Stripe Connect dans les settings artiste

Créer un composant Blade `resources/views/components/stripe-connect-status.blade.php` :

```blade
@props(['artist', 'user'])

@php
    $status = $user->stripe_connect_status ?? 'not_started';
    $canSetup = $artist->canSetupStripeConnect();
@endphp

<div class="stripe-connect-block">
    @if (!$canSetup)
        {{-- Bloqué par le studio --}}
        <div class="alert-info">
            🏢 Votre studio gère les paiements directement.
            Vous n'avez pas besoin de configurer Stripe Connect.
        </div>

    @elseif ($status === 'active')
        {{-- Compte actif --}}
        <div class="alert-success">
            ✅ Stripe Connect actif — vous recevez les paiements directement.
        </div>
        <a href="{{ route('stripe.connect.dashboard') }}" target="_blank"
           class="btn-secondary">
            Accéder à mon dashboard Stripe
        </a>

    @elseif ($status === 'pending' || $status === 'restricted')
        {{-- En cours de vérification --}}
        <div class="alert-warning">
            ⏳ Vérification en cours. Stripe peut vous envoyer un email.
        </div>
        <a href="{{ route('stripe.connect.onboard') }}" class="btn-primary">
            Compléter mon profil Stripe
        </a>

    @else
        {{-- Pas encore configuré --}}
        <div class="alert-info">
            💳 Configurez Stripe Connect pour recevoir les paiements de vos clients.
        </div>
        <a href="{{ route('stripe.connect.onboard') }}" class="btn-primary">
            Configurer Stripe Connect
        </a>
    @endif
</div>
```

### 8.2 — Bloc commission dans les settings Studio

Dans `resources/views/studio/settings.blade.php`, ajouter une section Paiement :

```blade
{{-- Section mode de paiement et commission --}}
<form action="{{ route('stripe.connect.studio.settings') }}" method="POST">
    @csrf

    {{-- Mode de paiement --}}
    <div class="form-group">
        <label>Mode de paiement</label>
        <select name="payment_mode">
            <option value="studio" @selected($studio->payment_mode === 'studio')>
                🏢 Paiements centralisés (studio reçoit)
            </option>
            <option value="direct_artist" @selected($studio->payment_mode === 'direct_artist')>
                👤 Paiements directs aux artistes
            </option>
        </select>
        <p class="text-sm text-gray-500">
            @if ($studio->payment_mode === 'studio')
                Les clients paient le studio. Vous redistribuez ensuite à vos artistes.
            @else
                Les clients paient directement chaque artiste.
                Le studio ne reçoit pas les paiements.
            @endif
        </p>
    </div>

    {{-- Commission sur les artistes --}}
    <div class="form-group">
        <label>Commission sur les artistes (%)</label>
        <input type="number"
               name="artist_commission_rate"
               value="{{ $studio->artist_commission_rate ?? '' }}"
               min="0" max="99.99" step="0.01"
               placeholder="0 = pas de commission">
        <p class="text-sm text-gray-500">
            Laissez vide ou à 0 pour ne pas prélever de commission sur vos artistes.
            Exemple : 10 = vous gardez 10% de chaque transaction.
        </p>
    </div>

    <button type="submit" class="btn-primary">Enregistrer</button>
</form>

{{-- Stripe Connect Studio --}}
@if ($studio->isPaymentModeStudio())
    <x-stripe-connect-status-studio :studio="$studio" />
@else
    <p class="text-sm">En mode paiement direct, configurez Stripe Connect sur chaque artiste.</p>
@endif
```

---

## PHASE 9 — WEBHOOK STRIPE (synchronisation statut Connect)

Créer ou mettre à jour un WebhookHandler custom pour intercepter les événements Connect :

```bash
php artisan make:listener HandleStripeConnectWebhook
```

Dans `app/Listeners/HandleStripeConnectWebhook.php` :

```php
// Événements à gérer :
// account.updated → sync stripe_connect_status sur User ou Studio
// capability.updated → sync charges_enabled, payouts_enabled

// Vérifier dans le handler Cashier existant si account.updated est déjà traité.
// Si non, ajouter dans WebhookController custom (étendre le Cashier WebhookController).
```

> ⚠️ Vérifier si un `WebhookController` custom existe déjà dans l'app.
> Si oui, ajouter les méthodes `handleAccountUpdated` et `handleCapabilityUpdated`.
> Si non, créer `app/Http/Controllers/WebhookController.php` qui étend
> `\Laravel\Cashier\Http\Controllers\WebhookController`.

---

## PHASE 10 — CONFIGURATION `.env`

Vérifier que ces variables sont présentes (ne pas les modifier, juste vérifier) :

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# À AJOUTER si manquant :
STRIPE_CONNECT_CLIENT_ID=ca_...   # Depuis Dashboard Stripe → Connect → Settings → Client ID
```

---

## ⚠️ Contraintes absolues
- Ne PAS toucher aux migrations existantes — seulement créer de nouvelles
- Ne PAS casser la logique trial/subscription existante (TrialService, HasSubscription)
- Billable Cashier reste sur User
- Les montants métier restent en EUROS en base — centimes uniquement dans les PaymentIntent Stripe
- Si `studioArtist` / `studio` relations n'existent pas encore sur Tattooer/Piercer :
  les créer avec le nom exact utilisé dans le codebase (vérifier dans l'audit Phase 1)
- Tester avec les comptes Stripe test avant tout (cartes 4242...)

## 📋 Rapport final attendu
1. Liste des migrations créées
2. Confirmation que `StripeConnectService` est enregistré dans `AppServiceProvider`
3. Liste des routes ajoutées (`php artisan route:list | grep stripe`)
4. Instructions Stripe CLI pour tester les webhooks account.updated en local
5. Checklist de test pour valider les 4 cas (indépendant STARTER/PRO, studio centralisé, studio direct)
