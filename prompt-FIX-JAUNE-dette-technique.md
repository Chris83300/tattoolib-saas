# 🟡 CORRECTIONS DETTE TECHNIQUE — Items 27-40 de AUDIT_GLOBAL.md
## Sprint post-bêta — Effort total estimé : ~1-2 semaines

---

## ⚠️ ORDRE D'EXÉCUTION RECOMMANDÉ
Ces corrections sont importantes mais ne bloquent pas la bêta.
Les exécuter dans cet ordre pour maximiser l'impact et minimiser les risques.

---

## PARTIE 1 — CONTROLLERS : REFACTORING CIBLÉ

### 1.1 — Extraire le bloc $pendingCount/$unreadCount dupliqué (item 29) [XS]

```bash
# Identifier toutes les occurrences
grep -n "pendingCount\|unreadCount\|pending_count\|unread_count" \
  app/Http/Controllers/TattooerController.php | head -20
```

Créer une méthode privée (ou un trait) et l'appeler partout :

```php
// Dans TattooerController — méthode privée partagée
private function getDashboardCounts(Tattooer $tattooer): array
{
    return [
        'pendingCount'  => $tattooer->bookingRequests()
                              ->where('status', 'pending')->count(),
        'unreadCount'   => $tattooer->unreadNotifications()->count(),
        // Ajouter les autres compteurs partagés
    ];
}

// Dans chaque méthode qui utilisait le bloc copié-collé :
$counts = $this->getDashboardCounts($tattooer);
return view('tattooer.xxx', array_merge($data, $counts));
```

### 1.2 — Injecter les services par constructeur (item 31) [M]

```bash
# Identifier tous les app() dans les controllers
grep -rn "app(\\\\App\\\\" app/Http/Controllers/ --include="*.php" | wc -l
grep -rln "app(\\\\App\\\\" app/Http/Controllers/ --include="*.php"
```

```php
// ❌ AVANT — injection dynamique (non testable)
public function process(Request $request)
{
    $result = app(\App\Services\StripeService::class)->createSession(...);
}

// ✅ APRÈS — injection par constructeur (testable, SOLID)
class DepositController extends Controller
{
    public function __construct(
        protected \App\Services\StripeService $stripeService,
        protected \App\Services\CancellationService $cancellationService,
    ) {}

    public function process(Request $request)
    {
        $result = $this->stripeService->createSession(...);
    }
}
```

Prioriser les controllers les plus utilisés :
1. `DepositController`
2. `BalancePaymentController`
3. `StripeWebhookController`

### 1.3 — Créer les Form Requests prioritaires (item 30) [L]

Commencer par les 5 plus critiques :

```bash
php artisan make:request Tattooer/AcceptBookingRequest
php artisan make:request Tattooer/UpdateProfileRequest
php artisan make:request Client/CreateBookingRequest
php artisan make:request Auth/RegisterTattooerRequest
php artisan make:request Payment/ProcessDepositRequest
```

Pattern à suivre pour chaque Form Request :

```php
// app/Http/Requests/Tattooer/AcceptBookingRequest.php
class AcceptBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $tattooer = $this->user()->tattooer;
        $booking  = $this->route('bookingRequest');
        return $tattooer && $booking->bookable_id === $tattooer->id;
    }

    public function rules(): array
    {
        return [
            'message'        => 'nullable|string|max:500',
            'confirmed_date' => 'required|date|after:today',
        ];
    }
}
```

---

## PARTIE 2 — FRONTEND : EXTERNALISATION JS

### 2.1 — Plan d'externalisation (item 32 — 3565 lignes JS inline)

Lire `AUDIT_FRONTEND.md` section "Plan de migration JS" pour la liste complète.

**Priorité 1 — Stripe payment JS** (sécurité + réutilisabilité) :

```bash
# Créer le fichier
touch resources/js/stripe-payment.js
```

```javascript
// resources/js/stripe-payment.js
// Initialisation Stripe et gestion du paiement

export function initStripePayment(publishableKey, clientSecret) {
    const stripe = Stripe(publishableKey);

    const elements = stripe.elements({ clientSecret });
    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');

    const form = document.getElementById('payment-form');
    const submitBtn = document.getElementById('submit-payment');
    const errorDiv = document.getElementById('payment-errors');

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.textContent = 'Traitement...';

        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: window.location.origin + '/payment/success',
            },
        });

        if (error) {
            errorDiv.textContent = error.message;
            submitBtn.disabled = false;
            submitBtn.textContent = 'Payer';
        }
    });
}
```

**Priorité 2 — Calendar JS** :

```bash
touch resources/js/tattooer-calendar.js
```

Extraire tout le code FullCalendar de `calendar.blade.php` (~375 lignes)
vers ce fichier. Passer les données dynamiques via des `data-*` attributes :

```blade
{{-- Dans calendar.blade.php --}}
<div id="calendar"
     data-bookings="{{ json_encode($bookings) }}"
     data-availabilities="{{ json_encode($availabilities) }}"
     data-csrf="{{ csrf_token() }}">
</div>
@vite('resources/js/tattooer-calendar.js')
```

```javascript
// resources/js/tattooer-calendar.js
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const bookings = JSON.parse(calendarEl.dataset.bookings || '[]');
    const availabilities = JSON.parse(calendarEl.dataset.availabilities || '[]');

    // ... initialisation FullCalendar
});
```

**Ajouter dans `vite.config.js`** :

```js
input: [
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/js/stripe-payment.js',    // ← ajouter
    'resources/js/tattooer-calendar.js', // ← ajouter
    // autres fichiers JS externalisés
],
```

**Priorité 3 — Marketplace filters JS** (~80 lignes) :

```bash
touch resources/js/marketplace-filters.js
```

Extraire le JS de filtres de `marketplace/index.blade.php` et `welcome.blade.php`.

---

## PARTIE 3 — FILAMENT V4 : DÉPRÉCIATIONS

### 3.1 — Remplacer ->reactive() par ->live() (item 37) [XS]

```bash
grep -rn "->reactive()" app/Filament/ --include="*.php"
```

```php
// ❌ AVANT — Filament v3
->reactive()

// ✅ APRÈS — Filament v4
->live()
```

---

## PARTIE 4 — SÉCURITÉ : DONNÉES MÉDICALES

### 4.1 — Chiffrer les colonnes médicales sensibles (item 39) [M]

```bash
# Colonnes identifiées non chiffrées
grep -rn "blood_type\|medical_conditions\|parent_id_number\|parent_name" \
  app/Models/ --include="*.php" | head -10
```

**Étape 1 — Migration** :

```bash
php artisan make:migration encrypt_sensitive_medical_columns
```

Les colonnes doivent être de type `text` pour stocker les données chiffrées :

```php
Schema::table('client_care_sheets', function (Blueprint $table) {
    $table->text('blood_type')->nullable()->change();
    $table->text('medical_conditions')->nullable()->change();
    // etc.
});
```

**Étape 2 — Ajouter le cast sur le modèle** :

```php
// Dans le modèle ClientCareSheet (ou équivalent)
use Illuminate\Database\Eloquent\Casts\Attribute;

protected $casts = [
    'blood_type'         => 'encrypted',
    'medical_conditions' => 'encrypted',
    'parent_id_number'   => 'encrypted',
    'parent_name'        => 'encrypted',
];
```

**Étape 3 — Migrer les données existantes** :

```bash
php artisan tinker
```

```php
// Chiffrer les données existantes
\App\Models\ClientCareSheet::chunk(100, function ($sheets) {
    foreach ($sheets as $sheet) {
        $sheet->update([
            'blood_type'         => $sheet->getRawOriginal('blood_type'),
            'medical_conditions' => $sheet->getRawOriginal('medical_conditions'),
        ]);
    }
});
echo "Données chiffrées.";
```

---

## PARTIE 5 — LOGS DEBUG RESTANTS

### 5.1 — Nettoyer les logs de debug restants (item 40) [XS]

```bash
# Trouver les logs debug restants après les fixes critiques
grep -rn "Log::info.*request\|Log::debug.*request\|request()->all()" \
  app/Http/Controllers/ --include="*.php"
```

```php
// ❌ SUPPRIMER ou downgrader en debug
Log::info('DEBUG: ...', [$request->all()]);

// ✅ Si besoin de debug en dev uniquement :
if (app()->environment('local')) {
    Log::debug('submitXxx', $request->except(['password', 'password_confirmation']));
}
```

---

## VALIDATION

```bash
# Vérifier l'injection par constructeur
grep -rn "public function __construct" app/Http/Controllers/ --include="*.php" | \
  grep -v "parent::" | head -10

# Vérifier les Form Requests créés
ls app/Http/Requests/

# Vérifier les fichiers JS externalisés
ls resources/js/

# Vérifier les dépréciations Filament
grep -rn "->reactive()" app/Filament/ --include="*.php"

# Vérifier le chiffrement colonnes médicales
php artisan tinker --execute="
  \$sheet = \App\Models\ClientCareSheet::first();
  dd(['blood_type_encrypted' => \$sheet?->blood_type ? 'OUI' : 'vide']);
"
```

---

## 📋 RAPPORT ATTENDU

Mettre à jour `AUDIT_GLOBAL.md` avec les items traités :
- Changer le statut de chaque item de ⬜ à ✅
- Recalculer le score global
- Estimer le nouveau score après corrections

## ⚠️ CONTRAINTES
- Ne pas refactorer TattooerController entièrement maintenant (item 27, XL) —
  trop risqué avant la bêta. Faire uniquement les quick wins (items 29, 31).
- La migration des données chiffrées doit être faite avec une sauvegarde DB préalable
- Tester chaque fichier JS externalisé avant de supprimer le code inline
