# 🔒 PROMPT J — BLOCAGE FONCTIONNALITÉS ARTISTE TRIAL EXPIRÉ
# Pour Claude Code — Limiter l'accès post-trial, sauf conversations avec acompte payé
# Commit après chaque fix

## CONTEXTE

Quand le trial 14 jours expire sans abonnement, l'artiste voit le message de blocage mais a ENCORE accès à toutes les fonctionnalités. Il faut verrouiller l'accès.

### RÈGLES MÉTIER

**BLOQUÉ** (trial expiré, pas d'abonnement) :
- ❌ Planning / Calendrier
- ❌ Fiches clients
- ❌ Nouvelles demandes (ne reçoit plus de demandes)
- ❌ Accepter/refuser des demandes
- ❌ Export PDF / CSV
- ❌ Statistiques
- ❌ Profil masqué dans la marketplace (déjà fait)
- ❌ Créer des fiches de soins / consentements / traçabilité

**ACCESSIBLE même bloqué** (pour ne pas pénaliser les clients) :
- ✅ Messages des conversations avec acompte payé (`deposit_paid_at IS NOT NULL`)
- ✅ Dashboard (avec bannière de blocage uniquement)
- ✅ Settings (pour pouvoir modifier son profil et souscrire)
- ✅ Page billing / abonnement (pour pouvoir payer)
- ✅ Notifications (lecture seule)
- ✅ Compléter un RDV déjà confirmé et payé (obligation légale envers le client)

Stack : Laravel 12, Livewire 3.7, Middleware.

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT PROMPT J ==="

# J0a. Middleware existants
ls app/Http/Middleware/ | sort
grep -rn "is_blocked\|isBlocked\|blocked\|trial\|subscri" app/Http/Middleware/ --include="*.php" | head -15

# J0b. Routes tattooer — groupes et middleware
grep -B 2 -A 5 "tattooer\|prefix.*tattooer\|group.*tattooer" routes/web.php | head -40

# J0c. Routes pierceur
grep -B 2 -A 5 "pierceur\|prefix.*pierc" routes/web.php | head -20

# J0d. Comment les routes sont groupées (middleware auth, etc.)
grep -n "middleware\|group\|prefix" routes/web.php | head -30

# J0e. Le check is_blocked actuel dans les controllers
grep -rn "is_blocked\|isBlocked" app/Http/Controllers/ --include="*.php" | head -10

# J0f. Les routes de messages (à ne PAS bloquer)
php artisan route:list 2>&1 | grep -i "message\|chat\|conversation" | head -10

# J0g. Les routes billing/subscription (à ne PAS bloquer)
php artisan route:list 2>&1 | grep -i "billing\|subscri\|plan\|pricing\|tarif" | head -10

# J0h. Les routes settings (à ne PAS bloquer)
php artisan route:list 2>&1 | grep -i "settings\|profil\|profile" | head -10

# J0i. Les routes dashboard
php artisan route:list 2>&1 | grep -i "dashboard" | head -10

# J0j. Les routes notifications
php artisan route:list 2>&1 | grep -i "notif" | head -10

# J0k. Toutes les routes tattooer
php artisan route:list --name="tattooer" --columns=method,uri,name 2>&1 | head -40

# J0l. Toutes les routes pierceur
php artisan route:list --name="pierceur" --columns=method,uri,name 2>&1 | head -30

# J0m. Routes de complétion de RDV (à ne PAS bloquer)
php artisan route:list 2>&1 | grep -i "complete\|complet\|finish\|done" | head -5

# J0n. TrialService — méthode hasActiveAccess
grep -B 3 -A 15 "function hasActiveAccess\|function isBlocked\|function canOperate" app/Services/TrialService.php app/Models/Tattooer.php app/Models/Studio.php 2>/dev/null | head -30

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX J1 — MIDDLEWARE DE BLOCAGE

### Créer le middleware

```bash
php artisan make:middleware EnsureActiveSubscription
```

```php
// app/Http/Middleware/EnsureActiveSubscription.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TrialService;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) return $next($request);

        // Admin = jamais bloqué
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return $next($request);
        }

        // Studio owner — vérifier via le studio
        $studio = $user->studio;
        if ($studio) {
            if ($studio->hasActiveSubscription()) {
                return $next($request);
            }
            // Studio bloqué → redirect billing studio
            return redirect()->route('studio.billing')
                ->with('warning', 'Votre essai est terminé. Activez votre abonnement pour continuer.');
        }

        // Artiste indépendant (tattooer ou piercer)
        $artisan = $user->tattooer ?? $user->piercer;
        if (!$artisan) return $next($request); // Client ou autre rôle = pas bloqué

        $trialService = app(TrialService::class);

        // Accès actif = trial en cours OU abonnement payé OU rattaché à un studio
        if ($trialService->hasActiveAccess($artisan)) {
            return $next($request);
        }

        // Artiste bloqué → redirect billing
        $billingRoute = $user->tattooer
            ? 'tattooer.billing'
            : 'pierceur.billing';

        // Vérifier que la route existe, sinon fallback
        $route = route($billingRoute, [], false);

        return redirect()->route($billingRoute)
            ->with('warning', 'Votre essai est terminé. Activez votre abonnement pour accéder à cette fonctionnalité.');
    }
}
```

### Enregistrer le middleware

```bash
# Laravel 12 — dans bootstrap/app.php
grep -n "middleware\|alias\|withMiddleware" bootstrap/app.php | head -10
```

```php
// Dans bootstrap/app.php — ajouter l'alias
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        // ... alias existants
        'active.subscription' => \App\Http\Middleware\EnsureActiveSubscription::class,
    ]);
})
```

OU dans `app/Http/Kernel.php` si Laravel < 12 :
```php
protected $middlewareAliases = [
    // ...
    'active.subscription' => \App\Http\Middleware\EnsureActiveSubscription::class,
];
```

```bash
git add -A && git commit -m "feat(J1): middleware EnsureActiveSubscription — bloque artistes trial expiré"
```

---

## FIX J2 — APPLIQUER LE MIDDLEWARE AUX ROUTES

### Identifier les routes à protéger

Le middleware `active.subscription` doit être appliqué à TOUTES les routes artiste SAUF :
- Dashboard (affiche la bannière de blocage)
- Billing / Abonnement (pour pouvoir payer)
- Settings / Profil (pour modifier son compte)
- Messages (conversations avec acompte payé uniquement — filtré dans le controller, pas le middleware)
- Notifications (lecture seule)
- Routes de complétion de RDV déjà confirmé

### Stratégie : protéger par groupe

```php
// routes/web.php — DANS le groupe tattooer auth

// Routes TOUJOURS accessibles (même bloqué)
Route::middleware(['auth'])->prefix('tattooer')->name('tattooer.')->group(function () {
    Route::get('/dashboard', [TattooerController::class, 'dashboard'])->name('dashboard');
    Route::get('/billing', [TattooerController::class, 'billing'])->name('billing');
    // OU
    Route::get('/subscription-plans', ...)->name('subscription.plans');
    Route::post('/subscribe', ...)->name('subscribe');
    Route::post('/subscription/cancel', ...)->name('subscription.cancel');
    Route::get('/settings', ...)->name('settings');
    Route::put('/settings', ...)->name('settings.update');
    Route::get('/profile', ...)->name('profile');
    Route::get('/notifications', ...)->name('notifications');
    Route::get('/messages', ...)->name('messages');        // Filtré dans le controller
    Route::get('/messages/{id}', ...)->name('messages.show'); // Filtré dans le controller
    // Routes complétion RDV existant
    Route::post('/appointments/{id}/complete', ...)->name('appointments.complete');
});

// Routes PROTÉGÉES (bloquées si trial expiré)
Route::middleware(['auth', 'active.subscription'])->prefix('tattooer')->name('tattooer.')->group(function () {
    Route::get('/requests', ...)->name('requests');
    Route::get('/requests/{id}', ...)->name('requests.show');
    Route::post('/requests/{id}/accept', ...)->name('requests.accept');
    Route::post('/requests/{id}/reject', ...)->name('requests.reject');
    Route::get('/planning', ...)->name('planning');
    Route::get('/clients', ...)->name('clients');
    Route::get('/clients/{id}', ...)->name('clients.show');
    Route::get('/statistics', ...)->name('statistics');
    // ... toutes les autres routes fonctionnelles
});
```

**ATTENTION** : La structure exacte des routes dépend de ce qui est trouvé en Phase 0. Ne pas créer de doublons. L'approche recommandée est :

**Option A** — Séparer en 2 groupes (libre + protégé) comme ci-dessus.

**Option B** — Appliquer le middleware au groupe global et exclure les routes libres via `withoutMiddleware()` :

```php
Route::middleware(['auth', 'active.subscription'])
    ->prefix('tattooer')
    ->name('tattooer.')
    ->group(function () {
        // Toutes les routes tattooer...

        // Routes exemptées du blocage
        Route::withoutMiddleware('active.subscription')->group(function () {
            Route::get('/dashboard', ...)->name('dashboard');
            Route::get('/billing', ...)->name('billing');
            Route::get('/settings', ...)->name('settings');
            Route::get('/messages', ...)->name('messages');
            Route::get('/messages/{id}', ...)->name('messages.show');
            Route::get('/notifications', ...)->name('notifications');
            Route::post('/subscribe', ...)->name('subscribe');
            Route::post('/subscription/cancel', ...)->name('subscription.cancel');
            Route::post('/appointments/{id}/complete', ...)->name('appointments.complete');
        });
    });
```

**Option B est préférable** car elle ne duplique pas les routes et est plus maintenable.

Appliquer la même logique pour les routes pierceur et studio.

```bash
git add -A && git commit -m "feat(J2): middleware active.subscription appliqué aux routes — sauf billing/settings/messages/dashboard"
```

---

## FIX J3 — FILTRER LES MESSAGES (ACOMPTE PAYÉ UNIQUEMENT)

### Problème
Un artiste bloqué doit pouvoir accéder UNIQUEMENT aux conversations liées à des bookings avec acompte payé. Pas aux nouvelles conversations.

### Fix dans le controller messages

```bash
# Trouver le controller/composant messages tattooer
grep -rn "function.*message\|function.*chat\|function.*conversation" app/Http/Controllers/TattooerController.php | head -10
find app/Livewire -name "*Message*" -o -name "*Chat*" -o -name "*Conversation*" | head -5
```

Modifier la query des conversations pour filtrer quand l'artiste est bloqué :

```php
// Dans le controller ou composant Livewire messages du tattooer

public function messages()
{
    $user = auth()->user();
    $artisan = $user->tattooer ?? $user->piercer;

    $query = Conversation::whereHas('users', function ($q) use ($user) {
        $q->where('user_id', $user->id);
    });

    // Si artiste bloqué → seulement les conversations avec acompte payé
    if ($artisan && $artisan->is_blocked) {
        $query->where(function ($q) use ($artisan) {
            $q->whereHas('bookingRequest', function ($sub) {
                $sub->whereNotNull('deposit_paid_at');
            });
            // OU si la conversation est liée au booking via un champ
            // Adapter selon la structure réelle
        });
    }

    $conversations = $query->with(['users', 'lastMessage'])
        ->orderBy('updated_at', 'desc')
        ->get();

    return view('tattooer.messages', compact('conversations'));
}
```

**IMPORTANT** : La relation entre `Conversation` et `BookingRequest` doit être vérifiée en Phase 0. Possibilités :
- `conversations.booking_request_id` → FK directe
- Via une table pivot
- Via les participants (le client qui a payé l'acompte)

Adapter la query selon la structure réelle trouvée.

### Afficher un message dans la vue messages

```blade
{{-- Dans la vue messages, si artiste bloqué --}}
@if ($artisan?->is_blocked)
<div class="bg-gris-fonde rounded-xl border border-titane/10 p-4 mb-4">
    <p class="text-sm text-titane">
        <span class="text-beige-peau">ℹ️</span>
        Seules les conversations liées à des prestations avec acompte versé sont accessibles.
        <a href="{{ route('tattooer.billing') }}" class="text-beige-peau hover:underline">Activez votre abonnement</a> pour accéder à toutes vos conversations.
    </p>
</div>
@endif
```

```bash
git add -A && git commit -m "feat(J3): messages filtrés — artiste bloqué voit uniquement conversations avec acompte payé"
```

---

## FIX J4 — PAGE BLOQUÉE COHÉRENTE

### Quand le middleware redirige vers billing, il faut que le dashboard affiche clairement l'état bloqué

Vérifier que le dashboard tattooer affiche la bannière de blocage correctement :

```bash
grep -n "is_blocked\|trial-banner\|blocked" resources/views/tattooer/dashboard.blade.php | head -10
```

### S'assurer que les items de la sidebar sont visuellement désactivés

Dans la sidebar, les liens vers les fonctionnalités bloquées doivent être grisés ou afficher un cadenas :

```blade
{{-- Dans la sidebar tattooer — pour chaque lien protégé --}}
@php
    $artisan = auth()->user()->tattooer ?? auth()->user()->piercer;
    $isBlocked = $artisan?->is_blocked ?? false;
@endphp

{{-- Exemple pour "Planning" --}}
@if ($isBlocked)
    <span class="flex items-center gap-3 px-3 py-2 text-titane/40 cursor-not-allowed rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Planning
        <svg class="w-3.5 h-3.5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
    </span>
@else
    <a href="{{ route('tattooer.planning') }}" class="flex items-center gap-3 px-3 py-2 text-titane hover:text-ivoire-text rounded-lg transition-colors">
        {{-- icône + texte normal --}}
        Planning
    </a>
@endif
```

**Créer un partial réutilisable** pour ne pas dupliquer cette logique sur chaque lien :

```blade
{{-- resources/views/partials/sidebar-link.blade.php --}}
@props(['route', 'label', 'icon' => '', 'blocked' => false, 'active' => false])

@if ($blocked)
    <span class="flex items-center gap-3 px-3 py-2 text-titane/30 cursor-not-allowed rounded-lg" title="Activez votre abonnement pour accéder à cette fonctionnalité">
        {!! $icon !!}
        {{ $label }}
        <svg class="w-3.5 h-3.5 ml-auto text-titane/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
    </span>
@else
    <a href="{{ route($route) }}" 
        class="{{ $active ? 'text-beige-peau bg-beige-peau/10' : 'text-titane hover:text-ivoire-text' }} flex items-center gap-3 px-3 py-2 rounded-lg transition-colors">
        {!! $icon !!}
        {{ $label }}
    </a>
@endif
```

Puis dans la sidebar :
```blade
@include('partials.sidebar-link', [
    'route' => 'tattooer.planning',
    'label' => 'Planning',
    'blocked' => $isBlocked,
    'active' => request()->routeIs('tattooer.planning'),
    'icon' => '<svg class="w-5 h-5" ...>...</svg>',
])
```

Appliquer pour CHAQUE lien de la sidebar qui doit être bloqué :
- Planning ❌
- Demandes ❌
- Fiches clients ❌
- Statistiques ❌
- Export ❌

Et garder accessibles :
- Dashboard ✅
- Messages ✅
- Abonnement ✅
- Settings ✅
- Notifications ✅

```bash
git add -A && git commit -m "feat(J4): sidebar grisée + cadenas sur fonctionnalités bloquées"
```

---

## FIX J5 — COMPLÉTION RDV DÉJÀ PAYÉ (EXCEPTION)

### Règle métier
Un artiste bloqué DOIT pouvoir compléter un RDV déjà confirmé et payé. C'est une obligation envers le client.

### Fix

Les routes de complétion de RDV doivent être exemptées du middleware. Vérifier en Phase 0 quelles routes sont concernées :

```bash
php artisan route:list 2>&1 | grep -i "complete\|finish\|done\|no.show" | head -10
```

S'assurer que ces routes sont dans le groupe `withoutMiddleware('active.subscription')` (fait dans J2).

En plus, le controller de complétion doit vérifier que le RDV appartient bien à l'artiste et que l'acompte a été payé :

```php
// Dans la méthode de complétion (si pas déjà fait)
public function completeAppointment(BookingRequest $booking)
{
    $artisan = auth()->user()->tattooer ?? auth()->user()->piercer;
    
    // Vérifier que c'est bien un RDV de cet artiste
    abort_unless(
        $booking->bookable_type === get_class($artisan) && $booking->bookable_id === $artisan->id,
        403
    );

    // Vérifier que l'acompte a été payé
    abort_unless($booking->deposit_paid_at !== null, 403, 'Acompte non payé.');

    // ... logique de complétion existante
}
```

```bash
git add -A && git commit -m "feat(J5): complétion RDV payé accessible même en mode bloqué"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT J ==="

# V1. Middleware
ls app/Http/Middleware/EnsureActiveSubscription.php && echo "Middleware OK"
grep -c "active.subscription\|EnsureActiveSubscription" bootstrap/app.php app/Http/Kernel.php 2>/dev/null
echo "Middleware enregistré (doit être > 0)"

# V2. Routes protégées
php artisan route:list 2>&1 | grep "active.subscription" | wc -l
echo "Routes avec middleware active.subscription"

# V3. Routes exemptées
php artisan route:list --name="tattooer.dashboard" 2>&1 | head -3
php artisan route:list --name="tattooer.billing" 2>&1 | head -3
php artisan route:list --name="tattooer.settings" 2>&1 | head -3
echo "Routes dashboard/billing/settings accessibles"

# V4. Filtrage messages
grep -c "is_blocked\|deposit_paid_at" app/Http/Controllers/TattooerController.php app/Livewire/ -r --include="*.php" 2>/dev/null
echo "Filtrage messages bloqué (doit être > 0)"

# V5. Sidebar cadenas
grep -c "is_blocked\|blocked\|sidebar-link\|cursor-not-allowed\|cadenas" resources/views/tattooer/ resources/views/layouts/tattooer.blade.php -r --include="*.blade.php" 2>/dev/null
echo "Sidebar grisée (doit être > 0)"

# V6. Test : artiste bloqué ne peut pas accéder au planning
php artisan tinker --execute="
  \$t = \App\Models\Tattooer::where('is_blocked', true)->first();
  if (\$t) {
    echo 'Artiste bloqué trouvé: #' . \$t->id . PHP_EOL;
  } else {
    echo 'Aucun artiste bloqué — pour tester: update un tattooer avec is_blocked=true' . PHP_EOL;
  }
"

# V7. Compilation
php artisan route:clear && php artisan view:clear
php artisan route:list 2>&1 | head -3
echo "OK"

echo "=== PROMPT J TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — La structure des routes est critique
2. **`withoutMiddleware`** est l'approche la plus propre (pas de duplication de routes)
3. **Messages filtrés dans le CONTROLLER** (pas dans le middleware) — le middleware laisse passer la route, le controller filtre les conversations
4. **Complétion RDV payé = toujours accessible** — obligation envers le client
5. **Sidebar visuelle** — les liens bloqués sont grisés avec un cadenas, pas juste cachés
6. **Studio** : même logique via `$studio->hasActiveSubscription()`
7. **Admin** : jamais bloqué
8. **Client** : jamais bloqué (le middleware ne s'applique qu'aux artistes/studios)
9. **Commit après chaque fix** (5 commits)
10. **Tester avec un artiste dont is_blocked=true** pour vérifier que le middleware fonctionne
