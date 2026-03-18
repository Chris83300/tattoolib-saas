# 🟠 CORRECTIONS AVANT BÊTA — Items 11-26 de AUDIT_GLOBAL.md
## Effort total estimé : ~2 jours

---

## A — SÉCURITÉ COMPLÉMENTAIRE

### A1 — Rate limiting manquant (items 14, 15) [XS chacun]

Dans `routes/web.php`, ajouter le throttle sur les routes d'inscription :

```php
// Routes d'inscription — max 10 tentatives par 5 minutes par IP
Route::middleware(['throttle:10,5'])->group(function () {
    Route::post('/register/client',   [RegisterController::class, 'submitClient']);
    Route::post('/register/tattooer', [RegisterController::class, 'submitTattooer']);
    Route::post('/register/piercer',  [RegisterController::class, 'submitPiercer']);
    Route::post('/register/studio',   [RegisterController::class, 'submitStudio']);
});

// Webhook Stripe — max 60 requêtes par minute
Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])
    ->middleware(['throttle:60,1'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
```

### A2 — SESSION_ENCRYPT (item 11) [XS]

Dans `.env` :
```env
SESSION_ENCRYPT=true
```

Dans `config/session.php` vérifier que `encrypt` est bien pris en compte :
```php
'encrypt' => env('SESSION_ENCRYPT', false),
```

### A3 — CSP enforce (item 12) [S]

Dans `app/Http/Middleware/SecurityHeaders.php` :

```php
// ❌ AVANT
$response->headers->set('Content-Security-Policy-Report-Only', $csp);

// ✅ APRÈS — tester d'abord en Report-Only sur staging, puis enforcer
$response->headers->set('Content-Security-Policy', $csp);
```

> ⚠️ Tester la console navigateur sur toutes les pages avant d'activer.
> Si des violations apparaissent : les corriger dans $csp d'abord.
> Pages à tester : /admin, /tattooer/requests, /deposit/*/payment, /client/profile

### A4 — Documents compliance en accès public (item 24) [M]

```bash
# Vérifier où sont stockés les documents compliance
grep -rn "compliance\|document\|certificate" \
  app/Http/Controllers/ app/Livewire/ --include="*.php" | \
  grep -i "store\|upload\|disk\|public" | head -10
```

Les documents compliance (certificats ARS, pièces d'identité) doivent être
dans un disk privé, pas `public` :

```php
// ❌ AVANT
Storage::disk('public')->put('compliance-documents/' . $filename, $file);

// ✅ APRÈS — disk privé avec accès contrôlé
Storage::disk('private')->put('compliance/' . $filename, $file);

// Pour servir le fichier de façon contrôlée :
// Créer une route protégée auth + vérification ownership
Route::get('/compliance/{filename}', [ComplianceController::class, 'serve'])
    ->middleware(['auth', 'admin']);

// Dans le controller :
public function serve(string $filename)
{
    abort_unless(auth()->user()->isAdmin(), 403);
    return Storage::disk('private')->download('compliance/' . $filename);
}
```

---

## B — CONTROLLERS : FIXES RAPIDES

### B1 — sleep(2) bloquant (item 16) [S]

```bash
grep -rn "sleep(" app/Http/Controllers/ --include="*.php"
```

```php
// ❌ AVANT — bloque le worker PHP 2 secondes
sleep(2);

// ✅ APRÈS — supprimer le sleep
// Si utilisé pour attendre une confirmation Stripe :
// → utiliser le webhook plutôt qu'un sleep
// Si utilisé pour "simuler" un délai en dev :
// → supprimer complètement
```

### B2 — BalancePaymentController sans try/catch (item 17) [XS]

```bash
grep -n "try\|catch\|StripeSession\|Session::create\|Checkout" \
  app/Http/Controllers/BalancePaymentController.php | head -20
```

```php
// ✅ Envelopper la création de session Stripe
try {
    $session = \Stripe\Checkout\Session::create($sessionData);
    return redirect($session->url);

} catch (\Stripe\Exception\ApiErrorException $e) {
    \Log::error('BalancePayment session error', [
        'booking_id' => $booking->id,
        'error'      => $e->getMessage(),
    ]);
    return redirect()->back()
        ->with('error', 'Impossible de créer la session de paiement. Réessayez ou contactez le support.');
}
```

### B3 — Duplication routes subscription pierceur (item 25) [XS]

```bash
grep -n "subscription\|subscribe" routes/web.php | grep "pierc"
```

Identifier et supprimer la déclaration dupliquée.

---

## C — PANEL ADMIN : PERFORMANCE CRITIQUE

### C1 — ~215 requêtes DB par chargement dashboard (item 18) [S]

```bash
# Identifier tous les widgets sans cache
grep -rn "protected function getStats\|protected function getData\|public function getStats" \
  app/Filament/Admin/Widgets/ --include="*.php"

# Vérifier présence de cache
grep -rn "Cache::\|remember(" app/Filament/Admin/Widgets/ --include="*.php"
```

Ajouter du cache sur TOUS les widgets stats :

```php
// Pattern à appliquer sur chaque widget stats
protected function getStats(): array
{
    return Cache::remember('admin.widget.stats_overview', now()->addMinutes(5), function () {
        return [
            Stat::make('Tatoueurs', \App\Models\Tattooer::count())
                ->description('Inscrits sur la plateforme'),
            Stat::make('Réservations actives',
                \App\Models\BookingRequest::whereNotIn('status', ['completed', 'cancelled', 'expired'])->count()
            ),
            // ...
        ];
    });
}
```

### C2 — N+1 dans RevenueStatsWidget et MonthlyRevenueChartWidget (item 19) [M]

```bash
# Lire les widgets concernés
cat app/Filament/Admin/Widgets/RevenueStatsWidget.php
cat app/Filament/Admin/Widgets/MonthlyRevenueChartWidget.php 2>/dev/null || \
  find app/Filament/Admin/Widgets -name "*Revenue*" -o -name "*Monthly*" | xargs cat
```

Remplacer les boucles qui font 1 requête par itération par des agrégats SQL :

```php
// ❌ AVANT — N+1 : 1 requête par paiement
$payments->each(function ($payment) {
    $total += $payment->subscription->amount; // requête par itération
});

// ✅ APRÈS — 1 seule requête agrégée
$total = \App\Models\BookingTransaction::query()
    ->whereYear('created_at', now()->year)
    ->where('status', 'paid')
    ->sum('amount');
```

### C3 — wire:poll.4s dans SupportChat (item 20) [M]

Dans `app/Filament/Admin/Pages/SupportChat.php` et sa vue :

```blade
{{-- ❌ AVANT — poll agressif --}}
wire:poll.4s="$refresh"

{{-- ✅ APRÈS — poll moins fréquent --}}
wire:poll.15s="$refresh"
{{-- Ou seulement sur la section messages, pas sur tout le layout --}}
```

### C4 — Eager loading manquant dans les tables Filament (item 36) [S]

```bash
# Tables sans with()
grep -rn "function table\|->query(" \
  app/Filament/Admin/Resources/*/Tables/*.php | head -20
```

```php
// Dans chaque TableClass, ajouter ->with() sur la query :

// TattooersTable
public static function table(Table $table): Table
{
    return $table
        ->query(
            Tattooer::query()->with(['user', 'studio']) // ← AJOUTER
        )
        // ...
}

// SubscriptionsTable
->query(
    \Laravel\Cashier\Subscription::query()
        ->with(['user.tattooer', 'user.piercer']) // ← AJOUTER
)

// TransactionsTable
->query(
    Transaction::query()
        ->with(['client.user', 'artist']) // ← AJOUTER selon les colonnes affichées
)
```

### C5 — Hook sidebar avec requêtes DB sans cache (item 38) [XS]

Dans `resources/views/filament/hooks/sidebar-nav-start.blade.php` :

```blade
{{-- ❌ AVANT — requêtes DB sur chaque page --}}
{{ \App\Models\BookingRequest::where('status', 'pending')->count() }}

{{-- ✅ APRÈS — avec cache 2 minutes --}}
{{ Cache::remember('admin.sidebar.pending_count', 120, fn() =>
    \App\Models\BookingRequest::where('status', 'pending')->count()
) }}
```

### C6 — Widgets orphelins non enregistrés (item 50) [XS]

```bash
# Lister les widgets dans AdminPanelProvider
grep -n "Widget::\|Widgets\\\\" app/Providers/Filament/AdminPanelProvider.php

# Lister tous les fichiers widget
ls app/Filament/Admin/Widgets/

# Identifier les orphelins (fichiers présents mais non enregistrés)
```

Supprimer de `AdminPanelProvider::getWidgets()` les widgets non utilisés.

---

## D — FRONTEND : FIXES RAPIDES

### D1 — 2FA obligatoire pour les admins (item 26) [S]

Dans `app/Http/Middleware/EnsureUserIsAdmin.php` :

```php
public function handle(Request $request, Closure $next)
{
    $user = $request->user();

    if (!$user || !$user->isAdmin()) {
        abort(403);
    }

    // Forcer 2FA pour les admins
    if (!$user->two_factor_confirmed_at) {
        return redirect()->route('two-factor.setup')
            ->with('warning', 'La double authentification est obligatoire pour accéder au panel administrateur.');
    }

    return $next($request);
}
```

### D2 — Bootstrap.js inutile (item 23) [XS]

```bash
grep -rn "bootstrap\|Bootstrap" resources/js/ --include="*.js"
grep -rn "import.*bootstrap\|require.*bootstrap" resources/js/app.js
```

```javascript
// ❌ AVANT — si présent dans app.js
import 'bootstrap';
// ou
import './bootstrap';

// ✅ APRÈS — vérifier si utilisé
// Si seul axios.defaults est dans bootstrap.js → garder uniquement axios
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

### D3 — Firebase SDK version incohérente (item 22) [S]

```bash
grep -rn "firebase\|Firebase\|initializeApp" resources/js/ resources/views/ \
  public/ --include="*.js" --include="*.blade.php" 2>/dev/null | head -10
```

Si Firebase v9 est chargé en CDN mais v12 est dans package.json :

```javascript
// ✅ Unifier — utiliser uniquement la version npm installée
// Supprimer les CDN Firebase dans les vues Blade
// Importer Firebase depuis le package npm dans app.js ou un fichier dédié
import { initializeApp } from 'firebase/app';
import { getMessaging, getToken } from 'firebase/messaging';
```

### D4 — Optimiser logo.png (item 33) [XS]

```bash
ls -la public/images/logo.png
# Si > 200KB → à optimiser
```

```bash
# Convertir en WebP (si imagemagick disponible)
convert public/images/logo.png -quality 85 public/images/logo.webp
# Ou utiliser squoosh, tinypng en ligne
```

### D5 — Vues orphelines (item 35) [XS]

```bash
# Identifier les 7 vues orphelines identifiées dans l'audit frontend
cat AUDIT_FRONTEND.md | grep -A 20 "orphelines"
```

Supprimer les vues confirmées comme orphelines après vérification manuelle.

### D6 — npm audit fix (item A5 de AUDIT_SECURITE) [XS]

```bash
npm audit fix
npm audit fix --force  # pour vite-plugin-pwa (breaking change vers v0.19.8)
npm audit             # vérifier qu'il ne reste plus de HIGH
```

---

## VALIDATION FINALE

```bash
# Rate limiting en place
php artisan route:list | grep -E "register|webhook" | grep -i throttle

# Session encrypt
php artisan tinker --execute="dd(config('session.encrypt'));"

# Widgets avec cache
grep -rn "Cache::remember" app/Filament/Admin/Widgets/ --include="*.php"

# Eager loading ajouté
grep -rn "->with(" app/Filament/Admin/Resources/*/Tables/ --include="*.php"

# npm vulnérabilités
npm audit 2>/dev/null | grep "vulnerabilities"
```

## ⚠️ CONTRAINTES
- Tester la CSP en mode Report-Only d'abord avant d'enforcer
- Le cache sur les widgets : 5 minutes est un bon compromis dashboard admin
- Ne pas supprimer les vues orphelines sans vérification manuelle qu'elles sont vraiment non utilisées
- Rapport final : chaque item avec statut ✅/❌ + notes
