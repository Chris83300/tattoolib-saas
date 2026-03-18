# 🔧 CONTROLLERS 2/2 — StudioController + ClientController + Form Requests
## Effort estimé : ~12h

---

## PARTIE 1 — DÉCOUPAGE StudioController (1242L → ~5 controllers)

### 1.1 — Audit préalable

```bash
wc -l app/Http/Controllers/StudioController.php
grep -n "public function" app/Http/Controllers/StudioController.php
grep -n "new \\\\Stripe\\\\StripeClient" app/Http/Controllers/StudioController.php
```

### 1.2 — Créer les controllers Studio

```bash
mkdir -p app/Http/Controllers/Studio

php artisan make:controller Studio/StudioDashboardController
php artisan make:controller Studio/StudioBookingController
php artisan make:controller Studio/StudioArtistController
php artisan make:controller Studio/StudioSettingsController
php artisan make:controller Studio/StudioBillingController
```

### 1.3 — Plan de découpage

```bash
# Lire toutes les méthodes
grep -n "public function" app/Http/Controllers/StudioController.php
```

Distribution suggérée (adapter selon les méthodes réelles trouvées) :

| Nouveau Controller | Méthodes probables |
|---|---|
| `StudioDashboardController` | `dashboard()`, `profile()`, `index()` |
| `StudioBookingController` | `requests()`, `requestShow()`, `acceptRequest()`, `cancelRequest()` |
| `StudioArtistController` | `artists()`, `inviteArtist()`, `removeArtist()`, `updateArtistCommission()` |
| `StudioSettingsController` | `settings()`, `settingsUpdate()`, `updatePaymentMode()` |
| `StudioBillingController` | `billing()`, `subscription()`, `connectStripe()` |

### 1.4 — Fix Stripe dans StudioController (ligne ~160)

```bash
grep -n "new \\\\Stripe\\\\StripeClient\|StripeClient(" app/Http/Controllers/StudioController.php
```

```php
// ❌ AVANT — instanciation directe, non testable
$stripe = new \Stripe\StripeClient(config('cashier.secret'));

// ✅ APRÈS — injecter StripeService dans le constructeur du nouveau controller
class StudioBillingController extends Controller
{
    public function __construct(
        protected \App\Services\StripeService $stripeService,
        protected \App\Services\StripeConnectService $connectService,
    ) {}

    public function connectStripe(Request $request)
    {
        // Utiliser $this->connectService au lieu de new StripeClient()
    }
}
```

---

## PARTIE 2 — DÉCOUPAGE ClientController (917L → ~4 controllers)

### 2.1 — Audit préalable

```bash
wc -l app/Http/Controllers/ClientController.php
grep -n "public function" app/Http/Controllers/ClientController.php
```

### 2.2 — Créer les controllers Client

```bash
mkdir -p app/Http/Controllers/Client

php artisan make:controller Client/ClientDashboardController
php artisan make:controller Client/ClientBookingController
php artisan make:controller Client/ClientMessageController
php artisan make:controller Client/ClientProfileController
```

### 2.3 — Plan de découpage

| Nouveau Controller | Méthodes probables |
|---|---|
| `ClientDashboardController` | `dashboard()`, `profile()` |
| `ClientBookingController` | `requests()`, `requestShow()`, `cancelRequest()`, `createReview()`, `createComplaint()` |
| `ClientMessageController` | `messages()`, `messageShow()`, `messageSend()`, `chat()` |
| `ClientProfileController` | `settings()`, `settingsUpdate()`, `exportGdpr()` |

---

## PARTIE 3 — FORM REQUESTS COMPLÉMENTAIRES

### 3.1 — Audit des validations inline restantes

```bash
# Compter les validations inline restantes après le prompt jaune
grep -rn "\$request->validate(" app/Http/Controllers/ --include="*.php" | wc -l
grep -rln "\$request->validate(" app/Http/Controllers/ --include="*.php"
```

### 3.2 — Créer les Form Requests prioritaires restants

```bash
# Settings artiste
php artisan make:request Tattooer/UpdateSettingsRequest
php artisan make:request Tattooer/UpdateScheduleRequest

# Consentement
php artisan make:request Tattooer/StoreConsentRequest
php artisan make:request Tattooer/StoreDigitalConsentRequest

# Compliance
php artisan make:request Tattooer/StoreComplianceDocumentRequest

# Studio
php artisan make:request Studio/UpdateStudioSettingsRequest
php artisan make:request Studio/InviteArtistRequest
php artisan make:request Studio/UpdatePaymentModeRequest

# Client
php artisan make:request Client/CreateComplaintRequest
php artisan make:request Client/CreateReviewRequest
```

### 3.3 — Pattern Form Request avec authorize()

```php
// app/Http/Requests/Tattooer/UpdateSettingsRequest.php
<?php
namespace App\Http\Requests\Tattooer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'utilisateur doit être le tatoueur concerné
        return (bool) $this->user()->tattooer;
    }

    public function rules(): array
    {
        return [
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'pseudo'              => 'required|string|max:50|unique:tattooers,pseudo,' . $this->user()->tattooer?->id,
            'bio'                 => 'nullable|string|max:1000',
            'city'                => 'required|string|max:100',
            'postal_code'         => 'required|string|size:5|regex:/^[0-9]{5}$/',
            'phone'               => 'nullable|string|max:20',
            'minimum_price'       => 'nullable|numeric|min:0|max:99999',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'instagram'           => 'nullable|url|max:255',
            'facebook'            => 'nullable|url|max:255',
            'tiktok'              => 'nullable|url|max:255',
            'website'             => 'nullable|url|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'pseudo.unique'        => 'Ce pseudo est déjà utilisé.',
            'postal_code.regex'    => 'Le code postal doit contenir 5 chiffres.',
            'postal_code.size'     => 'Le code postal doit contenir exactement 5 chiffres.',
        ];
    }
}
```

```php
// app/Http/Requests/Studio/UpdatePaymentModeRequest.php
class UpdatePaymentModeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()->studio;
    }

    public function rules(): array
    {
        return [
            'payment_mode'           => 'required|in:studio,direct_artist',
            'artist_commission_rate' => 'nullable|numeric|min:0|max:99.99',
        ];
    }
}
```

```php
// app/Http/Requests/Client/CreateReviewRequest.php
class CreateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Vérifier que le client a bien eu un RDV avec cet artiste
        $client  = $this->user()->client;
        $booking = \App\Models\BookingRequest::find($this->booking_id);
        return $client && $booking?->client_id === $client->id
            && $booking?->status === 'completed';
    }

    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:booking_requests,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:500',
        ];
    }
}
```

### 3.4 — Appliquer les Form Requests dans les controllers

Remplacer les `$request->validate([...])` inline par les Form Requests :

```php
// ❌ AVANT — validation inline dans le controller
public function settingsUpdate(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:100',
        // ... 15 règles inline
    ]);
    // ...
}

// ✅ APRÈS — Form Request typé
public function settingsUpdate(UpdateSettingsRequest $request)
{
    $validated = $request->validated();
    // ... logique métier seulement
}
```

---

## PARTIE 4 — MIDDLEWARE CONSOLIDATION

### 4.1 — Vérifier les middlewares redondants

```bash
# EnsureOwnership — est-il vraiment utilisé ?
grep -rn "EnsureOwnership\|ensure.ownership\|ownership" \
  routes/ --include="*.php"

# EnsureUserHasRole vs Spatie Permission — doublon ?
grep -rn "EnsureUserHasRole\|user.has.role" routes/ --include="*.php"
grep -rn "role:\|permission:" routes/ --include="*.php" | head -10

# Middlewares enregistrés mais jamais utilisés
grep -n "alias\[" bootstrap/app.php -A 30 | grep "=>"
```

### 4.2 — Nettoyer les middlewares non utilisés

```bash
# Pour chaque middleware listé dans bootstrap/app.php :
# Vérifier s'il est utilisé dans routes/ ou dans les controllers
for middleware in EnsureOwnership EnsureUserHasRole EnsureUserHasStatus; do
  count=$(grep -rn "$middleware" routes/ app/Http/Controllers/ \
    --include="*.php" 2>/dev/null | wc -l)
  echo "$middleware : $count utilisations"
done
```

---

## PARTIE 5 — TRAIT PARTAGÉ TATTOOER/PIERCER

### 5.1 — Méthodes identiques dans les deux controllers

```bash
# Comparer les méthodes Tattooer vs Piercer
grep -n "public function" app/Http/Controllers/TattooerController.php | \
  sed 's/.*public function //' | sed 's/(.*//' | sort > /tmp/tattooer_methods.txt

# Trouver le PiercerController
find app/Http/Controllers -name "*Pierc*" -o -name "*Pierceur*" | head -5
grep -n "public function" app/Http/Controllers/PiercerController.php 2>/dev/null | \
  sed 's/.*public function //' | sed 's/(.*//' | sort > /tmp/piercer_methods.txt

comm -12 /tmp/tattooer_methods.txt /tmp/piercer_methods.txt
echo "↑ Méthodes identiques → candidats pour un trait IsArtisanController"
```

### 5.2 — Créer un trait pour les méthodes communes (si > 5 méthodes identiques)

```php
// app/Http/Controllers/Concerns/HandlesArtistBookings.php
<?php
namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait HandlesArtistBookings
{
    /**
     * Méthode partagée entre TattooerBookingController et PiercerBookingController
     */
    private function getArtistFromRequest(Request $request): mixed
    {
        return $request->user()->tattooer
            ?? $request->user()->piercer
            ?? abort(403, 'Profil artiste introuvable');
    }

    private function validateBookingOwnership($booking, $artist): void
    {
        abort_unless(
            $booking->bookable_id   === $artist->id
            && $booking->bookable_type === get_class($artist),
            403,
            'Cette demande ne vous appartient pas.'
        );
    }
}
```

---

## VALIDATION FINALE

```bash
# Taille des controllers après découpage
echo "=== Taille controllers après ==="
wc -l app/Http/Controllers/TattooerController.php 2>/dev/null || echo "Supprimé ✅"
wc -l app/Http/Controllers/StudioController.php
wc -l app/Http/Controllers/ClientController.php
wc -l app/Http/Controllers/Tattooer/*.php 2>/dev/null | sort -rn | head -15
wc -l app/Http/Controllers/Studio/*.php 2>/dev/null | sort -rn | head -8
wc -l app/Http/Controllers/Client/*.php 2>/dev/null | sort -rn | head -6

# Form Requests créés
ls app/Http/Requests/Tattooer/ 2>/dev/null
ls app/Http/Requests/Studio/ 2>/dev/null
ls app/Http/Requests/Client/ 2>/dev/null

# Validations inline restantes
grep -rn "\$request->validate(" app/Http/Controllers/ --include="*.php" | wc -l
echo "↑ Objectif : < 20"

# Toutes les routes fonctionnent
php artisan route:cache
php artisan route:list | grep -c "tattooer\."
```

## ⚠️ CONTRAINTES
- **Ordre impératif** : créer controllers → mettre à jour routes → supprimer l'ancien
- Ne jamais modifier la logique métier lors du déplacement des méthodes
- Garder exactement les mêmes noms de routes (les vues Blade utilisent route('...'))
- Tester chaque groupe de routes après migration avant de continuer
- Si une méthode du TattooerController appelle une autre méthode du même controller :
  passer par un service ou une méthode statique partagée
- Rapport final :
  1. Tableau avant/après lignes par controller
  2. Liste Form Requests créés et controllers mis à jour
  3. `php artisan route:cache` sans erreur ✅
