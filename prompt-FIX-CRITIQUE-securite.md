# 🔴 CORRECTIONS CRITIQUES — Sécurité immédiate (Bloquants production)
## Items 1-10 de AUDIT_GLOBAL.md — Effort total estimé : ~1 journée

---

## PHASE 1 — AUDIT PRÉALABLE (lire avant toute modif)

```bash
# Vérifier l'état actuel des 10 points critiques
cat routes/api.php | grep -n "auth:sanctum\|middleware" | head -20
grep -n "Log::info\|substr.*STRIPE" app/Http/Controllers/DepositController.php | head -5
grep -n "request()->all()\|json_encode.*request" app/Http/Controllers/RegisterController.php
grep -n "eval(" resources/views/tattooer/request-show.blade.php
grep -n "Piercer\|pierceur" app/Http/Middleware/EnsureUserIsAdmin.php
grep -n "VAPID\|vapid" resources/js/app.js | head -5
ls fix_badge.php fix_nav.php 2>/dev/null
grep -n "ArtistRevenueChart\|data.*fake\|rand\|random" \
  app/Filament/Admin/Widgets/ArtistRevenueChartWidget.php | head -10
```

---

## FIX 1 — Routes API sans auth:sanctum [CRITIQUE] (~2-4h)

### Fichier : `routes/api.php` lignes 119-263

Lire le fichier complet pour identifier tous les groupes de routes non protégés.

Envelopper TOUS les groupes de routes métier dans `auth:sanctum` :

```php
// ✅ Structure correcte
Route::middleware(['auth:sanctum'])->group(function () {

    // Booking requests
    Route::prefix('booking-requests')->group(function () {
        Route::get('/', [Api\BookingRequestController::class, 'index']);
        Route::post('/', [Api\BookingRequestController::class, 'store']);
        Route::get('/{id}', [Api\BookingRequestController::class, 'show']);
        Route::post('/{id}/accept', [Api\BookingRequestController::class, 'accept']);
        Route::post('/{id}/cancel', [Api\BookingRequestController::class, 'cancel']);
        // etc.
    });

    // Appointments
    Route::prefix('appointments')->group(function () { /* ... */ });

    // Availabilities
    Route::prefix('availabilities')->group(function () { /* ... */ });

    // Planning, care-sheets, inventory, accounting, traceability...
    // TOUT doit être dans ce groupe
});
```

**EXCEPTION — Route webhook** : la route `POST /webhooks/stripe` et
`POST /stripe/webhook` doivent rester HORS de `auth:sanctum` (webhooks Stripe
ne s'authentifient pas ainsi — ils ont déjà la vérification de signature).

**Route `mark-deposit-paid`** : SUPPRIMER cette route ou la commenter.
Elle ne doit jamais être appelable depuis l'extérieur.
```php
// ❌ SUPPRIMER — ne jamais exposer en route publique
// Route::post('/{id}/mark-deposit-paid', ...)

// ✅ Cette action doit être déclenchée uniquement par le webhook Stripe
// checkout.session.completed → handleDepositPayment()
```

---

## FIX 2 — Clé Stripe dans les logs [CRITIQUE] (<30min)

### Fichier : `app/Http/Controllers/DepositController.php` ligne ~61

```php
// ❌ SUPPRIMER cette ligne entièrement
Log::info('Stripe key: ' . substr($stripeKey, 0, 20) . '...');

// ❌ Et toute autre ligne similaire dans ce fichier
// Chercher : grep -n "Log::info.*stripe\|Log::info.*key\|Log::info.*secret" DepositController.php
```

---

## FIX 3 — Mots de passe en clair dans les logs [CRITIQUE] (<1h)

### Fichier : `app/Http/Controllers/RegisterController.php` lignes 112, 212, 310

```php
// ❌ AVANT — mot de passe en clair dans les logs
Log::info('submitTattooer appelé avec: ' . json_encode($request->all()));
Log::info('submitPiercer appelé avec: ' . json_encode($request->all()));
Log::info('submitClient appelé avec: ' . json_encode($request->all()));

// ✅ APRÈS — exclure les champs sensibles
Log::debug('submitTattooer appelé', [
    'fields' => array_keys($request->all()), // seulement les noms des champs
    'ip'     => $request->ip(),
]);
// Ou simplement supprimer ces logs de debug
```

**Chercher aussi dans TattooerController.php ligne 2517 et
Api/MarketplaceController.php ligne 88 :**
```bash
grep -n "request()->all()\|json_encode.*request" \
  app/Http/Controllers/TattooerController.php \
  app/Http/Controllers/Api/MarketplaceController.php
```
Même correction : remplacer par `array_keys()` ou supprimer.

---

## FIX 4 — eval(event.script) XSS [CRITIQUE] (~1-3h)

### Fichier : `resources/views/tattooer/request-show.blade.php` ligne ~858

Lire le contexte autour de la ligne 858 pour comprendre l'usage.

```javascript
// ❌ DANGEREUX — exécute du code arbitraire
eval(event.script);

// ✅ ALTERNATIVES SÛRES selon le cas d'usage :

// Cas 1 : Si event.script contient un nom de fonction à appeler
const allowedFunctions = {
    'refreshBooking': refreshBooking,
    'showModal': showModal,
    'updateStatus': updateStatus,
    // Lister toutes les fonctions légitimes
};
if (event.script && allowedFunctions[event.script]) {
    allowedFunctions[event.script](event.data);
}

// Cas 2 : Si c'est pour dispatcher un événement Livewire
// Utiliser $dispatch() côté Livewire directement
// this.$dispatch('eventName', { data: value })

// Cas 3 : Si c'est pour Alpine.js
// Utiliser $dispatch() Alpine
```

> ⚠️ Lire le contexte AVANT de choisir l'alternative.
> Chercher comment `event.script` est défini et depuis où il vient.
> Si ça vient d'un composant Livewire → utiliser les events Livewire natifs.

---

## FIX 5 — Bug 'Piercer' majuscule [CRITIQUE] (<30min)

### Fichier : `app/Http/Middleware/EnsureUserIsAdmin.php` ligne ~57-63

```bash
grep -n "Piercer\|pierceur\|piercer" app/Http/Middleware/EnsureUserIsAdmin.php
```

```php
// ❌ AVANT — majuscule incorrecte → RouteNotFoundException
route('Piercer.dashboard')

// ✅ APRÈS — vérifier le nom exact de la route
php artisan route:list | grep "piercer.*dashboard\|pierceur.*dashboard"
// Utiliser le nom exact trouvé, ex:
route('pierceur.dashboard')
// ou
route('piercer.dashboard')
```

---

## FIX 6 — VAPID key en clair dans app.js [CRITIQUE] (<1h)

### Fichier : `resources/js/app.js` ligne ~5

```bash
grep -n "VAPID\|vapid\|applicationServerKey" resources/js/app.js
```

La clé VAPID publique dans le JS frontend est normale (elle est publique par design).
Mais si c'est la **clé privée** qui est exposée → CRITIQUE.

```javascript
// Vérifier : s'agit-il de VAPID_PUBLIC_KEY ou VAPID_PRIVATE_KEY ?

// ✅ Si c'est la clé publique (normale dans le JS)
// Rien à changer — la clé publique VAPID est faite pour être publique

// ❌ Si c'est la clé privée → la retirer du JS et faire une rotation
// La clé privée ne doit JAMAIS être dans du code frontend

// ✅ Passer la clé publique via une variable Blade (depuis .env)
const vapidPublicKey = '{{ config("webpush.vapid.public_key") }}';
```

**Si la clé privée était exposée :** Regénérer une nouvelle paire VAPID :
```bash
php artisan webpush:vapid
# Mettre les nouvelles clés dans .env
```

---

## FIX 7 — Fichiers debug exécutables à la racine [CRITIQUE] (<10min)

```bash
# Vérifier ce que contiennent ces fichiers
cat fix_badge.php
cat fix_nav.php

# Supprimer
rm fix_badge.php fix_nav.php

# Vérifier qu'il n'y a pas d'autres fichiers PHP à la racine
ls *.php
# Seuls artisan (sans .php) et éventuellement server.php sont normaux
```

---

## FIX 8 — Widget revenus avec données inventées [CRITIQUE] (~1-2h)

### Fichier : `app/Filament/Admin/Widgets/ArtistRevenueChartWidget.php` lignes 86-115

Lire le code complet du widget.
Si les données sont générées avec `rand()`, `fake()` ou des valeurs hardcodées :

```php
// ❌ AVANT — données fictives
'data' => [rand(100, 1000), rand(100, 1000), ...],

// ✅ APRÈS — données réelles depuis BookingTransaction
protected function getData(): array
{
    $months = collect(range(1, 12))->map(function ($month) {
        $revenue = \App\Models\BookingTransaction::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->where('type', 'balance') // ou le type exact
            ->where('status', 'paid')
            ->sum('amount');

        return round($revenue, 2);
    });

    return [
        'datasets' => [[
            'label' => 'Revenus artistes (€)',
            'data'  => $months->values()->toArray(),
        ]],
        'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun',
                     'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
    ];
}
```

---

## FIX 9 — Erreurs Stripe brutes exposées aux clients [CRITIQUE] (~1-2h)

### Fichiers : `ClientController.php:773`, `DepositController.php:233`,
### `RegisterController.php:104,205,303,394`, `TattooerController.php:1867,2083`

```php
// ❌ AVANT — message technique brut retourné au client
} catch (\Stripe\Exception\ApiErrorException $e) {
    return back()->withErrors(['error' => $e->getMessage()]);
}
// ou
return response()->json(['error' => $e->getMessage()], 500);

// ✅ APRÈS — message générique + log de l'erreur technique
} catch (\Stripe\Exception\CardException $e) {
    // Erreur carte → message explicite mais générique
    \Log::warning('Erreur carte Stripe', ['code' => $e->getStripeCode(), 'booking' => $bookingId ?? null]);
    return back()->withErrors(['error' => 'Votre carte a été refusée. Vérifiez vos informations de paiement.']);

} catch (\Stripe\Exception\ApiErrorException $e) {
    // Erreur API Stripe → message générique
    \Log::error('Erreur API Stripe', [
        'message'    => $e->getMessage(),
        'http_status'=> $e->getHttpStatus(),
        'stripe_code'=> $e->getStripeCode(),
    ]);
    return back()->withErrors(['error' => 'Une erreur de paiement est survenue. Veuillez réessayer ou contacter le support.']);

} catch (\Exception $e) {
    \Log::error('Erreur inattendue paiement', ['message' => $e->getMessage()]);
    return back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer.']);
}
```

---

## FIX 10 — App\Models\Tattooer hardcodé dans TattooerController [CRITIQUE] (~1-3h)

```bash
grep -n "App\\\\Models\\\\Tattooer\|'App\\\Models\\\Tattooer'\|Tattooer::class" \
  app/Http/Controllers/TattooerController.php | head -20
```

Pour chaque occurrence hardcodée dans les méthodes qui traitent aussi des piercers
(`acceptRequest`, `requestReject`, `reproposeDates`, `completeBooking`, `markNoShow`) :

```php
// ❌ AVANT — hardcodé Tattooer
$booking->update(['bookable_type' => 'App\Models\Tattooer']);
// ou
BookingRequest::where('bookable_type', 'App\Models\Tattooer')

// ✅ APRÈS — utiliser le type polymorphique dynamique
$artist = $booking->bookable; // retourne Tattooer ou Piercer
$booking->update(['bookable_type' => get_class($artist)]);
// ou
BookingRequest::where('bookable_type', get_class($artist))
```

---

## VALIDATION FINALE

```bash
# Vérifier les routes API
php artisan route:list | grep "api/" | grep -v "auth:sanctum\|name:"

# Vérifier qu'aucun log sensible ne reste
grep -rn "substr.*STRIPE\|json_encode.*request\(\)" \
  app/Http/Controllers/ --include="*.php"

# Vérifier eval supprimé
grep -rn "eval(" resources/views/ --include="*.blade.php"

# Vérifier fichiers debug supprimés
ls fix_*.php 2>/dev/null && echo "⚠️ FICHIERS DEBUG ENCORE PRÉSENTS" || echo "✅ OK"

# Vérifier fix EnsureUserIsAdmin
php artisan route:list | grep "pierceur.*dashboard\|piercer.*dashboard"
```

## ⚠️ CONTRAINTES
- Commencer par FIX 1 (routes API) — c'est le plus critique
- Pour FIX 4 (eval) : lire le contexte avant de choisir l'alternative
- Ne pas modifier la logique métier, seulement sécuriser
- Rapport final : chaque fix avec fichier:ligne avant/après
