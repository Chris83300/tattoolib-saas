 # 🔧 PHASE 8 — CORRECTIONS POST-AUDIT INK&PIK
# Pour Claude Code (terminal)
# Suit le rapport d'audit du 20/02/2026 — 14 étapes par ordre de priorité
# RÈGLE : git commit après CHAQUE étape validée

## CONTEXTE

Rapport d'audit identifie :
- 3 bloquants qui empêchent route:list de compiler
- 8+ comparaisons Enum silencieusement cassées dans les vues Blade client
- Reviews/Complaints invisibles (méthodes manquantes + pas de navigation + pas de section profil)
- 19 routes nommées dupliquées
- 8 controllers orphelins, 6 vues orphelines
- 332/472 tests échoués

Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, MySQL, Stripe Connect, Laravel Cashier, Spatie Permission, Spatie Media Library, Filament v4.5.

## RÈGLES ABSOLUES

1. UN problème à la fois — dans l'ordre donné
2. Après chaque fix : `php artisan route:list 2>&1 | head -3` pour vérifier que ça compile
3. `git add -A && git commit -m "fix(scope): description"` après chaque étape validée
4. NE PAS refactorer ce qui fonctionne
5. Si un fix casse autre chose → rollback immédiat et signale

---

## ÉTAPE 1 — ENREGISTRER LE MIDDLEWARE `role` (BLOQUANT)

Le middleware `role` est utilisé dans routes/web.php (L262, L276) mais n'est pas enregistré.
`EnsureUserHasRole` existe dans app/Http/Middleware/.

```bash
# AUDIT
grep -rn "role:" routes/web.php | head -5
ls app/Http/Middleware/EnsureUserHasRole.php
grep -rn "'role'" bootstrap/app.php
```

FIX : Dans `bootstrap/app.php`, dans la section `->withMiddleware()`, ajouter l'alias :

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    ]);
    // ... existing middleware config
})
```

ATTENTION : s'il y a déjà un bloc `->alias([...])`, AJOUTER la ligne dedans, ne pas créer un 2ème bloc.

```bash
# VÉRIFICATION
php artisan route:list 2>&1 | head -3
# Si toujours "Target class does not exist" → étape 2 avant de re-tester 
git add -A && git commit -m "fix(middleware): enregistrer alias role dans bootstrap/app.php"
```

---

## ÉTAPE 2 — CRÉER PiercerController OU BRANCHER LES ROUTES LIVEWIRE (BLOQUANT)

routes/web.php:264-272 référence `PiercerController` qui existe.
MAIS des composants Livewire existent dans `app/Livewire/Pierceur/`.

```bash
# AUDIT
grep -n "PiercerController" routes/web.php
ls app/Livewire/Pierceur/ 2>/dev/null
ls app/Http/Controllers/PiercerController.php 2>/dev/null
```

**OPTION A (recommandée)** : Remplacer les routes Controller par des routes Livewire.
Les composants Livewire existent déjà, il suffit de les brancher.

Remplacer le bloc routes/web.php:264-272 par :

```php
// Pierceur routes (Livewire)
Route::middleware(['auth', 'verified', 'role:pierceur'])->prefix('pierceur')->name('pierceur.')->group(function () {
    Route::get('/dashboard', App\Livewire\Pierceur\Dashboard::class)->name('dashboard');
    Route::get('/settings', App\Livewire\Pierceur\Settings::class)->name('settings');
    Route::get('/portfolio', App\Livewire\Pierceur\Portfolio::class)->name('portfolio');
    Route::get('/clients', App\Livewire\Pierceur\Clients::class)->name('clients');
    Route::get('/messages', App\Livewire\Pierceur\Messages::class)->name('messages');
    Route::get('/calendar', App\Livewire\Pierceur\Calendar::class)->name('calendar');
    // Ajouter d'autres routes selon les composants Livewire existants
});
```

MAIS D'ABORD vérifie quels composants Livewire existent réellement :
```bash
ls -la app/Livewire/Pierceur/
# Puis adapte les routes à ce qui existe VRAIMENT
```

**OPTION B** : Créer un PiercerController minimal qui délègue aux composants Livewire.
Moins propre mais plus rapide.

```bash
# VÉRIFICATION
php artisan route:list 2>&1 | head -3
php artisan route:list 2>&1 | grep -c "pierceur\|piercer"
git add -A && git commit -m "fix(routes): brancher routes pierceur sur composants Livewire existants"
```

---

## ÉTAPE 3 — FUSIONNER LES ROUTES STUDIO DUPLIQUÉES (BLOQUANT)

Le prefix `studio.` est défini 2 fois : L276 avec StudioController + L284 avec Livewire.

```bash
# AUDIT
grep -n "prefix.*studio\|->name.*studio" routes/web.php
```

FIX : Fusionner en UN SEUL groupe. Vérifier quel système (Controller ou Livewire) est le plus complet et garder celui-là. Supprimer l'autre.

```bash
# Vérifier ce qui existe
ls app/Http/Controllers/StudioController.php 2>/dev/null && wc -l app/Http/Controllers/StudioController.php
ls app/Livewire/Studio/ 2>/dev/null
```

Si StudioController ET Livewire existent tous les deux :
- Garder UN SEUL système
- Le Controller est plus adapté si les pages sont statiques (settings, artists list)
- Livewire est plus adapté si les pages ont des interactions dynamiques (dashboard, planning)
- On peut mixer : certaines routes Controller, d'autres Livewire, mais dans UN SEUL groupe

```bash
# VÉRIFICATION
php artisan route:list 2>&1 | grep studio
php artisan route:list --columns=name 2>&1 | sort | uniq -d | grep studio
# Doit retourner 0 doublon
git add -A && git commit -m "fix(routes): fusionner groupes studio dupliqués"
```

---

## ÉTAPE 4 — SUPPRIMER LES ROUTES DUPLIQUÉES ET DE TEST

```bash
# AUDIT — identifier les doublons exacts
grep -n "client.booking-requests\|client.booking-request.show" routes/web.php
grep -n "webhooks.stripe" routes/web.php
grep -n "auto-login\|test-pierceur\|test-pending\|test-simple\|test-view" routes/web.php
```

FIX :
1. Supprimer le 2ème groupe client dupliqué (L388-394 approximativement)
2. Supprimer la 2ème route webhooks.stripe (L452 approximativement)
3. Supprimer OU conditionner les routes de test (L321-366 approximativement) :

```php
// Si on veut garder pour le dev :
if (app()->environment('local')) {
    Route::get('/auto-login-pierceur', ...);
    // etc.
}
// SINON : supprimer complètement
```

```bash
# VÉRIFICATION
php artisan route:list --columns=name 2>&1 | sort | uniq -d
# Ne doit plus afficher de doublons (sauf les préfixes identiques mais fullnames différents)
php artisan route:list 2>&1 | grep -c "test-"
# Doit retourner 0 (ou les routes sont dans un if local)
git add -A && git commit -m "fix(routes): supprimer doublons client, webhook et routes de test"
```

---

## ÉTAPE 5 — CORRIGER LES COMPARAISONS ENUM DANS LES VUES BLADE CLIENT

C'est la cause racine de BEAUCOUP de bugs silencieux. Le status est un BackedEnum mais les vues comparent avec des strings.

```bash
# AUDIT — trouver l'Enum exact
cat app/Enums/BookingRequestStatus.php
```

FIX : Dans CHAQUE fichier listé, remplacer les comparaisons string par l'Enum.

**Méthode 1 (propre)** : Utiliser l'Enum directement
```php
// AVANT (cassé)
$bookingRequest->status === 'deposit_paid'

// APRÈS (correct)
$bookingRequest->status === \App\Enums\BookingRequestStatus::DEPOSIT_PAID
```

**Méthode 2 (rapide)** : Comparer avec ->value
```php
// APRÈS (correct aussi)
$bookingRequest->status->value === 'deposit_paid'
```

**Méthode 3 (la plus robuste)** : Ajouter un use en haut du Blade
```blade
@php use App\Enums\BookingRequestStatus; @endphp
{{-- puis utiliser --}}
@if ($bookingRequest->status === BookingRequestStatus::DEPOSIT_PAID)
```

FICHIERS À CORRIGER (liste du rapport) :
1. `resources/views/client/booking-requests.blade.php` → L261 (`deposit_paid`), L272 (`rejected`)
2. `resources/views/client/chat.blade.php` → L53, L81 (`awaiting_deposit` → `deposit_requested`)
3. `resources/views/client/dashboard.blade.php` → L165 (`accepted`)
4. `resources/views/livewire/client/profile-tabs/demandes.blade.php` → L174 (`accepted`)
5. `resources/views/livewire/client/bookings.blade.php` → L215 (`accepted`)

**ATTENTION SPÉCIALE** : dans chat.blade.php, le case `awaiting_deposit` N'EXISTE PAS dans l'Enum.
Le bon case est `deposit_requested`. Donc c'est un DOUBLE bug : mauvais type ET mauvaise valeur.

```bash
# Scanner s'il y en a d'autres non listés dans le rapport
grep -rn "->status\s*===\s*'" resources/views/ --include="*.blade.php" | grep -v "->status->value\|Enum\|BackedEnum"
grep -rn "->status\s*==\s*'" resources/views/ --include="*.blade.php" | grep -v "->status->value\|Enum\|BackedEnum"
```

Corriger TOUT ce que le scan remonte, pas seulement les 5 fichiers du rapport.

```bash
# VÉRIFICATION
grep -rn "->status\s*===\s*'" resources/views/ --include="*.blade.php" | grep -v "->status->value\|Enum\|BookingRequestStatus" | wc -l
# Doit retourner 0
git add -A && git commit -m "fix(enum): corriger toutes les comparaisons string vs BackedEnum dans les vues Blade"
```

---

## ÉTAPE 6 — CORRIGER LES match() PIERCEUR

Les vues pierceur utilisent `match($bookingRequest->status)` avec des bras string → UnhandledMatchError.

```bash
# AUDIT
grep -rn "match.*status" resources/views/pierceur/ resources/views/livewire/pierceur/ 2>/dev/null | head -20
```

FIX : Remplacer `match($bookingRequest->status)` par `match($bookingRequest->status->value)` 
OU par `match($bookingRequest->status)` avec des cas Enum.

```bash
# VÉRIFICATION
grep -rn "match.*status" resources/views/pierceur/ resources/views/livewire/pierceur/ 2>/dev/null | grep -v "->value\|Enum" | wc -l
# Doit retourner 0
git add -A && git commit -m "fix(enum): corriger match() pierceur pour utiliser ->status->value"
```

---

## ÉTAPE 7 — AJOUTER MÉTHODES reviews() ET complaints() DANS ClientController

Les routes `client.reviews` (GET) et `client.complaints` (GET) existent mais pointent vers des méthodes inexistantes → 404.

```bash
# AUDIT
grep -n "reviews\|complaints" routes/web.php | grep "client"
grep -n "function reviews\|function complaints" app/Http/Controllers/ClientController.php
```

FIX : Ajouter dans `app/Http/Controllers/ClientController.php` :

```php
public function reviews()
{
    $reviews = \App\Models\Review::where('client_user_id', auth()->id())
        ->with(['bookingRequest', 'bookingRequest.bookable'])
        ->latest()
        ->get();
    
    return view('client.reviews', compact('reviews'));
}

public function complaints()
{
    $complaints = \App\Models\Complaint::where('user_id', auth()->id())
        ->with('bookingRequest')
        ->latest()
        ->get();
    
    return view('client.complaints', compact('complaints'));
}
```

VÉRIFIER que les vues `client/reviews.blade.php` et `client/complaints.blade.php` existent :
```bash
ls resources/views/client/reviews.blade.php resources/views/client/complaints.blade.php
```
Si absentes → les créer (pages simples listant les avis/réclamations).

```bash
# VÉRIFICATION
php artisan route:list --name="client.reviews" 2>&1
php artisan route:list --name="client.complaints" 2>&1
git add -A && git commit -m "fix(client): ajouter méthodes reviews() et complaints() dans ClientController"
```

---

## ÉTAPE 8 — AJOUTER LIENS NAVIGATION AVIS + RÉCLAMATIONS DANS SIDEBAR CLIENT

```bash
# AUDIT
grep -n "route.*client\.\|href.*client" resources/views/layouts/client.blade.php | head -20
```

FIX : Dans `resources/views/layouts/client.blade.php`, dans la section navigation (sidebar desktop ET barre mobile en bas), ajouter :

```blade
{{-- Lien Avis --}}
<a href="{{ route('client.reviews') }}" 
    class="..." 
    :class="request()->routeIs('client.reviews') ? 'active-class' : ''">
    ⭐ Mes avis
</a>

{{-- Lien Réclamations --}}
<a href="{{ route('client.complaints') }}" 
    class="..." 
    :class="request()->routeIs('client.complaints') ? 'active-class' : ''">
    📢 Réclamations
</a>
```

Adapter les classes CSS au style existant de la sidebar (copier le pattern des autres liens).
Ajouter dans la navigation DESKTOP et dans la barre MOBILE en bas.

```bash
# VÉRIFICATION
grep -c "client.reviews\|client.complaints" resources/views/layouts/client.blade.php
# Doit retourner au moins 2 (1 pour desktop + 1 pour mobile, × 2 liens)
git add -A && git commit -m "fix(client): ajouter liens navigation Avis et Réclamations"
```

---

## ÉTAPE 9 — AJOUTER SECTION #reviews SUR LE PROFIL PUBLIC TATTOOER

Le lien `href="#reviews"` existe (L151 de marketplace/show.blade.php) mais la section cible n'existe pas.
Les avis sont chargés (`$artist->reviews`) mais jamais affichés.

```bash
# AUDIT
grep -n "reviews\|#reviews\|avis" resources/views/marketplace/show.blade.php | head -20
```

FIX : Dans `resources/views/marketplace/show.blade.php`, ajouter une section avant la fermeture du container principal :

```blade
{{-- Section Avis --}}
<section id="reviews" class="mt-8">
    <h2 class="text-xl font-bold text-ivoire-text mb-4">
        ⭐ Avis clients
        @if ($artist->reviews->count() > 0)
            <span class="text-sm font-normal text-titane">
                ({{ number_format($artist->reviews->avg('rating'), 1) }}/5 — {{ $artist->reviews->count() }} avis)
            </span>
        @endif
    </h2>

    @forelse ($artist->reviews->where('is_published', true)->sortByDesc('created_at') as $review)
        <div class="bg-gris-fonde rounded-xl p-4 mb-3">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="flex">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $review->rating ? 'text-ambre-warning' : 'text-titane/30' }}">★</span>
                        @endfor
                    </div>
                    <span class="text-sm font-semibold text-ivoire-text">
                        {{ $review->client?->name ?? 'Client' }}
                    </span>
                </div>
                <span class="text-xs text-titane">{{ $review->created_at->diffForHumans() }}</span>
            </div>
            @if ($review->comment)
                <p class="text-sm text-ivoire-text/80">{{ $review->comment }}</p>
            @endif
            @if ($review->tattooer_response)
                <div class="mt-2 pl-4 border-l-2 border-beige-peau/30">
                    <p class="text-xs text-titane">Réponse de l'artiste :</p>
                    <p class="text-sm text-ivoire-text/70">{{ $review->tattooer_response }}</p>
                </div>
            @endif
        </div>
    @empty
        <p class="text-titane text-sm text-center py-6">Aucun avis pour le moment</p>
    @endforelse
</section>
```

IMPORTANT : Vérifier que `is_published` a une valeur par défaut `true` dans la migration et le model.
```bash
grep -n "is_published" database/migrations/*review* app/Models/Review.php
```
Si `default(false)` → changer en `default(true)` car les avis sont auto-validés.
Si le model met `is_published => false` dans `$attributes` ou dans le controller `createReview` → corriger en `true`.

```bash
# VÉRIFICATION
grep -c "id=\"reviews\"" resources/views/marketplace/show.blade.php
# Doit retourner 1
git add -A && git commit -m "feat(marketplace): ajouter section avis sur profil public tattooer + auto-publish reviews"
```

---

## ÉTAPE 10 — VALIDATION + AUTH DANS StudioController

```bash
# AUDIT
grep -n "function " app/Http/Controllers/StudioController.php | head -15
grep -c "validate\|authorize\|abort_unless\|abort_if\|can(" app/Http/Controllers/StudioController.php
```

FIX : Pour CHAQUE méthode du StudioController, ajouter :

1. Vérification de propriété :
```php
abort_unless(auth()->user()->studio?->id === $studio->id, 403);
```

2. Validation des inputs (pour store/update) :
```php
$validated = $request->validate([...]);
```

Si le temps manque, au minimum ajouter un `__construct` avec middleware :
```php
public function __construct()
{
    $this->middleware(['auth', 'verified', 'role:studio']);
}
```

```bash
# VÉRIFICATION
grep -c "abort_unless\|authorize\|validate" app/Http/Controllers/StudioController.php
# Doit être > 0
git add -A && git commit -m "fix(security): ajouter validation et autorisation dans StudioController"
```

---

## ÉTAPE 11 — SUPPRIMER LES FICHIERS MORTS

```bash
# Backups
rm -f app/Http/Controllers/RegisterController_backup.php
rm -f app/Http/Controllers/TattooerController_backup.php

# Vues orphelines
rm -f resources/views/tattooer/subscription-plans-new.blade.php
rm -f resources/views/tattooer/subscription-plans-old.blade.php
rm -f resources/views/client/messages-empty.blade.php
rm -f resources/views/client/messages-old.blade.php

# Controllers orphelins (ATTENTION : vérifier qu'ils ne sont VRAIMENT pas utilisés)
# ClientComplaintController et ClientReviewController sont des scaffolds vides
# Le vrai code est dans ClientController
ls -la app/Http/Controllers/ClientComplaintController.php app/Http/Controllers/ClientReviewController.php
grep -rn "ClientComplaintController\|ClientReviewController" routes/ app/ 2>/dev/null
# Si 0 références dans les routes → supprimer
rm -f app/Http/Controllers/ClientComplaintController.php
rm -f app/Http/Controllers/ClientReviewController.php

# VÉRIFICATION
php artisan route:list 2>&1 | head -3
git add -A && git commit -m "chore: supprimer fichiers morts (backups, vues orphelines, controllers scaffolds)"
```

---

## ÉTAPE 12 — DÉPLACER QUERIES DU LAYOUT CLIENT DANS VIEW COMPOSER

```bash
# AUDIT
grep -n "Conversation::\|Message::\|::where\|DB::" resources/views/layouts/client.blade.php
```

FIX : Créer un View Composer :

```bash
# Créer le fichier
```

```php
// app/View/Composers/ClientLayoutComposer.php
namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Conversation;

class ClientLayoutComposer
{
    public function compose(View $view): void
    {
        if (!auth()->check()) return;
        
        $unreadCount = Conversation::where('client_user_id', auth()->id())
            ->whereHas('messages', fn($q) => $q->where('sender_id', '!=', auth()->id())->whereNull('read_at'))
            ->count();
        
        $view->with('clientUnreadCount', $unreadCount);
    }
}
```

Enregistrer dans `app/Providers/AppServiceProvider.php` :
```php
use Illuminate\Support\Facades\View;
use App\View\Composers\ClientLayoutComposer;

public function boot(): void
{
    View::composer('layouts.client', ClientLayoutComposer::class);
    // ...
}
```

Puis dans le layout, remplacer les queries inline par `$clientUnreadCount`.

```bash
# VÉRIFICATION
grep -c "Conversation::\|Message::" resources/views/layouts/client.blade.php
# Doit retourner 0
git add -A && git commit -m "perf: déplacer queries layout client dans View Composer"
```

---

## ÉTAPE 13 — RELANCER LES TESTS

```bash
php artisan test --parallel 2>&1 | tail -30
# Compter les fails
php artisan test --parallel 2>&1 | grep -E "Tests:|FAIL"
```

Après les fixes P0, beaucoup de tests devraient repasser.
Les tests restants en erreur sont probablement :
- QueryException → tests qui dépendent de la structure DB (migrations non alignées)
- BindingResolutionException → DI cassée par les changements de routes
- Enum errors → tests unitaires qui comparent aussi en string

NE PAS corriger les tests un par un maintenant. Juste documenter combien passent vs avant (332/472 fails → combien maintenant ?).

```bash
git add -A && git commit -m "test: relancer tests après corrections P0"
```

---

## ÉTAPE 14 — VÉRIFICATION FINALE

```bash
# Route list compile ?
php artisan route:list 2>&1 | head -3

# Nombre total de routes
php artisan route:list 2>&1 | wc -l

# Routes critiques
for route in tattooer.dashboard client.dashboard client.reviews client.complaints pierceur.dashboard studio.dashboard; do
    php artisan route:list --name="$route" 2>&1 | grep -q "$route" \
        && echo "✅ $route" \
        || echo "❌ $route"
done

# Plus de comparaisons Enum string dans les Blades ?
grep -rn "->status\s*===\s*'" resources/views/ --include="*.blade.php" | grep -v "->status->value\|Enum\|BookingRequestStatus" | wc -l

# Plus de routes dupliquées ?
php artisan route:list --columns=name 2>&1 | sort | uniq -d | grep -v "^$"

# Tests
php artisan test --parallel 2>&1 | grep "Tests:"

echo "=== AUDIT TERMINÉ ==="
```
