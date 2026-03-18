# 🔐 SÉCURITÉ AVANCÉE 1/2 — CSP Nonces + 2FA Artistes + HSTS
## Objectif : passer de 7.5/10 → 8.5/10

---

## PHASE 1 — AUDIT PRÉALABLE

```bash
# État actuel CSP
grep -n "Content-Security-Policy\|unsafe-inline\|unsafe-eval\|nonce" \
  app/Http/Middleware/SecurityHeaders.php

# Vérifier Livewire config (nonces)
cat config/livewire.php | grep -i "nonce\|csp"

# 2FA artistes actuel
grep -rn "TwoFactor\|two_factor\|2fa\|stripe_connect" \
  app/Http/Middleware/ --include="*.php"

# HSTS actuel
grep -n "Strict-Transport\|HSTS\|hsts" app/Http/Middleware/SecurityHeaders.php

# Session config
grep -n "secure\|same_site\|http_only" config/session.php
```

---

## CORRECTION 1 — CSP avec Nonces (supprimer unsafe-inline / unsafe-eval)

### Contexte
La CSP actuelle contient `'unsafe-inline'` et `'unsafe-eval'` qui annulent
80% de la protection XSS. Supprimer ces directives nécessite des **nonces**
sur chaque script inline — Livewire et Alpine.js le supportent nativement.

### 1.1 — Middleware SecurityHeaders : générer un nonce par requête

Dans `app/Http/Middleware/SecurityHeaders.php` :

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        // Générer un nonce unique par requête
        $nonce = base64_encode(random_bytes(16));

        // Stocker dans le request pour les vues
        $request->attributes->set('csp_nonce', $nonce);
        app()->instance('csp-nonce', $nonce);

        $response = $next($request);

        // ✅ CSP avec nonce — sans unsafe-inline ni unsafe-eval
        $isProd = app()->environment('production');

        $csp = implode('; ', array_filter([
            "default-src 'self'",

            // Scripts : nonce uniquement (plus d'unsafe-inline)
            "script-src 'self' 'nonce-{$nonce}' https://js.stripe.com https://cdn.jsdelivr.net",

            // Styles : nonce + self (Tailwind génère des styles inline)
            "style-src 'self' 'nonce-{$nonce}' 'unsafe-inline' https://fonts.googleapis.com",
            // Note : unsafe-inline sur styles uniquement est acceptable
            // Le risque principal est sur les scripts

            "font-src 'self' https://fonts.gstatic.com data:",

            "img-src 'self' data: https: blob:",

            "connect-src 'self' https://api.stripe.com https://checkout.stripe.com wss: ws:",

            "frame-src 'self' https://js.stripe.com https://hooks.stripe.com",

            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "upgrade-insecure-requests",

            // HSTS (production uniquement)
            // Géré séparément ci-dessous
        ]));

        $response->headers->set('Content-Security-Policy', $csp);

        // HSTS — production uniquement
        if ($isProd) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Autres headers sécurité
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy',
            'camera=(), microphone=(), geolocation=(self), payment=(self)');

        // Anti-indexation du panel admin
        if ($request->is('admin/*') || $request->is('admin')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
```

### 1.2 — Helper Blade pour accéder au nonce

Créer `app/helpers.php` (ou ajouter à un fichier helpers existant) :

```php
if (!function_exists('csp_nonce')) {
    function csp_nonce(): string
    {
        return app('csp-nonce', '');
    }
}
```

Enregistrer dans `composer.json` si pas déjà fait :
```json
"autoload": {
    "files": ["app/helpers.php"]
}
```

### 1.3 — Configurer Livewire pour utiliser le nonce

Dans `config/livewire.php` :

```php
// Livewire v3 supporte les nonces CSP
'asset_url' => null,
'app_url'   => null,

// Nonce pour les scripts Livewire injectés
'inject_assets' => true,
'inject_morph_markers' => true,
```

Dans `app/Providers/AppServiceProvider.php` :

```php
use Livewire\Livewire;

public function boot(): void
{
    // Injecter le nonce dans les assets Livewire
    Livewire::forceAssetInjection();

    // Si Livewire 3.x supporte setScriptRoute/nonce :
    if (method_exists(Livewire::class, 'scriptNonce')) {
        Livewire::scriptNonce(fn() => app('csp-nonce', ''));
    }
}
```

### 1.4 — Ajouter nonce sur les scripts inline dans les layouts

Dans les fichiers de layout principaux
(`resources/views/layouts/tattooer.blade.php`, `client.blade.php`,
`studio.blade.php`, `app.blade.php`) :

```bash
# Trouver les scripts inline sans nonce
grep -rn "<script>" resources/views/layouts/ --include="*.blade.php"
grep -rn "<script " resources/views/layouts/ --include="*.blade.php" | \
  grep -v "nonce\|@vite\|src="
```

```blade
{{-- ❌ AVANT --}}
<script>
    window.userId = {{ auth()->id() }};
</script>

{{-- ✅ APRÈS --}}
<script nonce="{{ csp_nonce() }}">
    window.userId = {{ auth()->id() }};
</script>
```

### 1.5 — Alpine.js : configuration nonce

Dans le layout principal, ajouter avant Alpine :

```blade
<script nonce="{{ csp_nonce() }}">
    document.addEventListener('alpine:init', () => {
        // Alpine.js configurations globales
    });
</script>
```

### 1.6 — Tester la CSP

```bash
# Tester en Report-Only d'abord (rollback sécurisé)
# Changer temporairement dans SecurityHeaders :
# Content-Security-Policy-Report-Only

# Ouvrir DevTools → Console
# Naviguer sur : /tattooer/dashboard, /client/profile,
#                /deposit/*/payment, /admin
# → Aucune violation CSP ne doit apparaître

# Une fois validé → repasser en Content-Security-Policy
```

---

## CORRECTION 2 — 2FA Obligatoire pour Artistes avec Stripe Connect Actif

### Contexte
Un artiste avec Stripe Connect actif gère des flux financiers réels.
Si son compte est compromis → accès aux paiements de ses clients.
Le 2FA doit être obligatoire pour ces artistes spécifiquement.

### 2.1 — Créer le middleware EnsureArtistHas2FA

```bash
php artisan make:middleware EnsureArtistHas2FA
```

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureArtistHas2FA
{
    public function handle(Request $request, Closure $next)
    {
        $user   = $request->user();
        $artist = $user?->tattooer ?? $user?->piercer ?? null;

        if (!$artist) {
            return $next($request);
        }

        // Forcer 2FA si l'artiste a Stripe Connect actif
        $hasActiveConnect = $artist->stripe_connect_charges_enabled ?? false;

        if ($hasActiveConnect && !$user->two_factor_confirmed_at) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'La double authentification est requise pour accéder à cette fonctionnalité.',
                    'requires_2fa' => true,
                ], 403);
            }

            return redirect()->route('two-factor.setup')
                ->with('warning',
                    '🔐 La double authentification est obligatoire car vous avez un compte Stripe Connect actif. '
                    . 'Sécurisez votre compte pour continuer.'
                );
        }

        return $next($request);
    }
}
```

### 2.2 — Enregistrer et appliquer le middleware

Dans `bootstrap/app.php` :

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        // Existants...
        'artist.2fa' => \App\Http\Middleware\EnsureArtistHas2FA::class,
    ]);
})
```

Dans `routes/web.php`, appliquer sur les routes de paiement artiste :

```php
// Routes sensibles artiste (paiements + settings financiers)
Route::middleware(['auth', 'verified', 'artisan.can.operate', 'artist.2fa'])
    ->prefix('tattooer')
    ->group(function () {
        Route::get('/payments', [TattooerController::class, 'payments'])
            ->name('tattooer.payments');
        Route::get('/settings', [TattooerController::class, 'settings'])
            ->name('tattooer.settings');
        // Stripe connect routes
    });

// Même chose pour pierceur
Route::middleware(['auth', 'verified', 'artisan.can.operate', 'artist.2fa'])
    ->prefix('pierceur')
    ->group(function () {
        Route::get('/payments', [PiercerController::class, 'payments'])
            ->name('piercer.payments');
    });
```

### 2.3 — Notification incitative 2FA dans les settings

Dans la vue `resources/views/tattooer/settings.blade.php` (et piercer) :

```blade
@php
    $artist = auth()->user()->tattooer ?? auth()->user()->piercer;
    $has2FA = auth()->user()->two_factor_confirmed_at;
    $hasConnect = $artist?->stripe_connect_charges_enabled;
@endphp

@if ($hasConnect && !$has2FA)
<div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200
            dark:border-red-800 rounded-xl flex items-start gap-3">
    <span class="text-2xl flex-shrink-0">🔐</span>
    <div>
        <p class="font-semibold text-red-800 dark:text-red-200">
            Double authentification requise
        </p>
        <p class="text-sm text-red-600 dark:text-red-300 mt-1">
            Vous avez un compte Stripe Connect actif. Activez le 2FA pour
            sécuriser vos paiements.
        </p>
        <a href="{{ route('two-factor.setup') }}"
           class="inline-block mt-2 px-3 py-1.5 bg-red-600 text-white
                  text-sm rounded-lg hover:bg-red-700 transition">
            Activer le 2FA →
        </a>
    </div>
</div>
@elseif (!$has2FA)
<div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200
            rounded-xl flex items-start gap-3">
    <span class="text-xl flex-shrink-0">⚠️</span>
    <div>
        <p class="font-semibold text-yellow-800 dark:text-yellow-200 text-sm">
            Recommandé : activez la double authentification
        </p>
        <a href="{{ route('two-factor.setup') }}"
           class="text-xs text-yellow-700 underline mt-1 inline-block">
            Activer le 2FA
        </a>
    </div>
</div>
@endif
```

---

## CORRECTION 3 — HSTS + Secure Cookies en Production

### 3.1 — HSTS (déjà dans SecurityHeaders ci-dessus)

Le header HSTS est ajouté dans la Correction 1.
Vérifier que le `.env` de production a :

```env
APP_ENV=production
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
```

### 3.2 — Secure cookies dans config/session.php

```php
'secure'    => env('SESSION_SECURE_COOKIE', false),
'same_site' => env('SESSION_SAME_SITE', 'lax'),
'http_only' => true,
```

### 3.3 — Rate limiting sur reset password (item A42)

Dans `routes/web.php` :

```php
// Rate limiting sur reset password — anti énumération email
Route::middleware(['throttle:5,10'])->group(function () {
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
        ->name('password.email');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

// Rate limiting sur 2FA attempts
Route::middleware(['throttle:5,5'])->group(function () {
    Route::post('/two-factor-challenge',
        [TwoFactorAuthenticatedSessionController::class, 'store'])
        ->name('two-factor.login');
});
```

---

## VALIDATION FINALE

```bash
# Vérifier le nonce dans les headers
curl -I http://tattoolib-saas.test/ 2>/dev/null | grep -i "content-security"
# Doit afficher : nonce-XXXXX dans la valeur, sans unsafe-inline pour script-src

# Vérifier le 2FA middleware enregistré
php artisan route:list | grep "payments\|settings" | grep "tattooer\|pierceur"

# Vérifier HSTS (en mode production seulement)
grep -n "Strict-Transport" app/Http/Middleware/SecurityHeaders.php

# Vérifier rate limiting reset password
php artisan route:list | grep "password\|two-factor" | grep throttle

# Test nonce Livewire (DevTools → Network → voir les scripts Livewire)
# → Les scripts Livewire doivent avoir l'attribut nonce
```

## ⚠️ CONTRAINTES
- Tester la CSP en mode Report-Only AVANT d'enforcer
- Le nonce change à chaque requête → ne jamais le cacher
- Si des violations CSP apparaissent sur des pages spécifiques :
  identifier le script inline en cause et lui ajouter `nonce="{{ csp_nonce() }}"`
- Ne PAS ajouter 'unsafe-eval' même pour Alpine.js — Alpine 3.x n'en a pas besoin
- Rapport final : screenshot headers CSP + test 2FA artiste avec Connect
