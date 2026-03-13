# 🔧 FIX — Bouton "Connecter Stripe Connect" inactif sur /tattooer/payments

## Symptôme
Sur `http://tattoolib-saas.test/tattooer/payments`, le bouton "Connecter Stripe Connect"
ne fait rien au clic. Aucune redirection vers Stripe.

---

## PHASE 1 — AUDIT (lire avant toute modification)

### 1.1 — Lire la vue concernée

Lire intégralement `resources/views/tattooer/payments.blade.php` (ou le fichier
qui contient le bouton "Connecter Stripe Connect").

Identifier exactement :
- Le type du bouton : `<a href>`, `<button>` dans un `<form>`, Alpine.js `@click`, Livewire
- La route ou l'action pointée (ou l'absence de route)
- Si `href="#"` ou `href=""` → c'est ça le bug (route non définie)
- Si `@click` sans handler → handler manquant

### 1.2 — Vérifier les routes Stripe Connect existantes

```bash
php artisan route:list | grep -i "stripe\|connect"
```

Relever les routes présentes et celles qui manquent.
Les routes nécessaires minimum sont :
- `stripe.connect.onboard` → démarre l'onboarding (crée le compte + redirige vers Stripe)
- `stripe.connect.return` → retour après onboarding réussi
- `stripe.connect.refresh` → si le lien a expiré

### 1.3 — Vérifier le controller

```bash
grep -r "onboard\|generateStripeConnect\|createOnboarding\|connectLink" \
  app/Http/Controllers/ --include="*.php" -l
```

Lire le controller trouvé. Vérifier que la méthode qui génère le lien Stripe :
1. Récupère le bon artiste (`$user->tattooer`)
2. Crée le compte Connect si `stripe_connect_id` est null
3. Génère un `AccountLink` Stripe avec les bons `return_url` et `refresh_url`
4. Redirige vers `$accountLink->url`

### 1.4 — Vérifier le StripeService / StripeConnectService

Lire la méthode de génération du lien onboarding dans `app/Services/StripeService.php`
ou `app/Services/StripeConnectService.php`.

Vérifier que `return_url` et `refresh_url` pointent vers des routes qui EXISTENT.
C'est souvent la cause : le lien est généré mais les routes de retour sont undefined.

---

## PHASE 2 — CORRECTIONS

### CAS A — Le bouton n'a pas de route (href="#" ou action vide)

Corriger dans la vue :

```blade
{{-- ❌ AVANT --}}
<a href="#" class="btn-primary">Connecter Stripe Connect</a>
{{-- ou --}}
<button type="button">Connecter Stripe Connect</button>

{{-- ✅ APRÈS --}}
<a href="{{ route('stripe.connect.onboard') }}" class="btn-primary">
    Connecter Stripe Connect
</a>
```

Si c'est un `<form>` :
```blade
<form action="{{ route('stripe.connect.onboard') }}" method="POST">
    @csrf
    <button type="submit" class="btn-primary">Connecter Stripe Connect</button>
</form>
```

### CAS B — La route `stripe.connect.onboard` n'existe pas

Ajouter dans `routes/web.php` (dans le groupe middleware auth) :

```php
Route::middleware(['auth', 'verified'])->group(function () {

    // Stripe Connect — Artiste indépendant
    Route::get('/stripe/connect/onboard',
        [\App\Http\Controllers\StripeConnectController::class, 'onboardArtist'])
        ->name('stripe.connect.onboard');

    Route::get('/stripe/connect/return/{type?}',
        [\App\Http\Controllers\StripeConnectController::class, 'returnFromOnboarding'])
        ->name('stripe.connect.return');

    Route::get('/stripe/connect/refresh/{type?}',
        [\App\Http\Controllers\StripeConnectController::class, 'refreshOnboarding'])
        ->name('stripe.connect.refresh');

    Route::get('/stripe/connect/dashboard',
        [\App\Http\Controllers\StripeConnectController::class, 'artistDashboard'])
        ->name('stripe.connect.dashboard');
});
```

### CAS C — Le controller `StripeConnectController` n'a pas la méthode `onboardArtist`

Vérifier dans `app/Http/Controllers/StripeConnectController.php`.
Si la méthode manque, l'ajouter :

```php
public function onboardArtist(Request $request)
{
    $user   = $request->user();
    $artist = $user->tattooer ?? $user->piercer;

    abort_unless($artist, 403, 'Profil artiste introuvable');

    // Vérifier que l'artiste peut configurer son Connect
    // (pas bloqué par un studio en mode studio_managed)
    if (method_exists($artist, 'canSetupStripeConnect')) {
        abort_unless($artist->canSetupStripeConnect(), 403,
            'Votre studio gère les paiements. Stripe Connect non disponible.');
    }

    // Créer le compte Connect si pas encore fait
    if (empty($user->stripe_connect_id) && empty($artist->stripe_connect_id)) {
        app(\App\Services\StripeService::class)->createConnectAccount($artist);
        // OU selon le nom exact de la méthode dans StripeService :
        // app(\App\Services\StripeService::class)->createAccountForUser($user);
        $artist->refresh();
        $user->refresh();
    }

    // Récupérer le stripe_connect_id (sur l'artiste ou sur le user)
    $accountId = $artist->stripe_connect_id ?? $user->stripe_connect_id;

    abort_unless($accountId, 500, 'Impossible de créer le compte Stripe Connect');

    // Générer le lien d'onboarding
    $onboardingUrl = app(\App\Services\StripeService::class)->generateOnboardingLink(
        $accountId,
        route('stripe.connect.return'),
        route('stripe.connect.refresh'),
    );
    // OU selon le nom exact dans StripeService :
    // $onboardingUrl = app(\App\Services\StripeService::class)
    //     ->createOnboardingLink($accountId, route(...), route(...));

    return redirect($onboardingUrl);
}

public function returnFromOnboarding(Request $request)
{
    $user   = $request->user();
    $artist = $user->tattooer ?? $user->piercer;

    $accountId = $artist?->stripe_connect_id ?? $user->stripe_connect_id;

    if ($accountId) {
        // Synchroniser le statut depuis Stripe
        $stripeService = app(\App\Services\StripeService::class);

        // Appeler la méthode de sync qui existe déjà (voir audit)
        if (method_exists($stripeService, 'syncAccountStatus')) {
            $status = $stripeService->syncAccountStatus($accountId);
        } elseif (method_exists($stripeService, 'activateStripeConnect')) {
            $stripeService->activateStripeConnect($artist);
        }
    }

    $artistType = $user->tattooer ? 'tattooer' : 'piercer';
    $settingsRoute = $artistType === 'tattooer'
        ? 'tattooer.payments'
        : 'piercer.payments';

    return redirect()->route($settingsRoute)
        ->with('success', '✅ Stripe Connect configuré ! Vous pouvez recevoir des paiements.');
}

public function refreshOnboarding(Request $request)
{
    // Le lien a expiré → régénérer
    return $this->onboardArtist($request);
}

public function artistDashboard(Request $request)
{
    $user   = $request->user();
    $artist = $user->tattooer ?? $user->piercer;

    $accountId = $artist?->stripe_connect_id ?? $user->stripe_connect_id;

    abort_unless($accountId, 404, 'Aucun compte Stripe Connect trouvé');

    $stripe = new \Stripe\StripeClient(config('cashier.secret'));
    $link   = $stripe->accounts->createLoginLink($accountId);

    return redirect($link->url);
}
```

### CAS D — Les noms de méthodes StripeService ne correspondent pas

L'audit Phase 1.3 a relevé les méthodes exactes dans `StripeService`.
Adapter les appels dans le controller pour utiliser les noms exacts trouvés
(ne pas renommer les méthodes existantes).

---

## PHASE 3 — METTRE À JOUR LA VUE payments.blade.php

Après avoir établi que les routes fonctionnent, mettre à jour le bloc
Stripe Connect dans la vue pour afficher le bon état selon le statut :

```blade
@php
    $connectId     = $tattooer->stripe_connect_id ?? auth()->user()->stripe_connect_id;
    $connectStatus = $tattooer->stripe_connect_status ?? auth()->user()->stripe_connect_status ?? 'not_started';
    $isActive      = $connectStatus === 'active' && ($tattooer->stripe_connect_charges_enabled ?? false);
@endphp

@if ($isActive)
    {{-- Compte actif --}}
    <div class="flex items-center gap-3 p-4 bg-green-50 rounded-xl border border-green-200">
        <span class="text-2xl">✅</span>
        <div>
            <p class="font-semibold text-green-800">Stripe Connect actif</p>
            <p class="text-sm text-green-600">Vous recevez les paiements directement.</p>
        </div>
        <a href="{{ route('stripe.connect.dashboard') }}"
           target="_blank"
           class="ml-auto btn-secondary text-sm">
            Mon dashboard Stripe →
        </a>
    </div>

@elseif ($connectStatus === 'pending' || $connectStatus === 'restricted')
    {{-- En cours de vérification --}}
    <div class="flex items-center gap-3 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
        <span class="text-2xl">⏳</span>
        <div>
            <p class="font-semibold text-yellow-800">Vérification en cours</p>
            <p class="text-sm text-yellow-600">Stripe peut vous demander des documents supplémentaires.</p>
        </div>
        <a href="{{ route('stripe.connect.onboard') }}"
           class="ml-auto btn-secondary text-sm">
            Compléter mon profil
        </a>
    </div>

@elseif ($connectStatus === 'blocked_by_studio')
    {{-- Bloqué studio --}}
    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
        <p class="text-gray-600">🏢 Votre studio gère les paiements. Stripe Connect non requis.</p>
    </div>

@else
    {{-- Pas encore configuré --}}
    <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl border border-blue-200">
        <span class="text-2xl">💳</span>
        <div>
            <p class="font-semibold text-blue-800">Configurez Stripe Connect</p>
            <p class="text-sm text-blue-600">Pour recevoir les paiements de vos clients directement.</p>
        </div>
        <a href="{{ route('stripe.connect.onboard') }}"
           class="ml-auto btn-primary text-sm">
            Connecter Stripe Connect
        </a>
    </div>
@endif
```

---

## PHASE 4 — TEST

```bash
# Vérifier que les routes sont bien enregistrées
php artisan route:list | grep stripe

# Tester le flux complet
# 1. Aller sur /tattooer/payments
# 2. Cliquer "Connecter Stripe Connect"
# 3. Doit rediriger vers une URL Stripe (stripe.com/connect/...)
# 4. Compléter le formulaire Stripe avec les données test
# 5. Retour sur /tattooer/payments avec message succès
# 6. Statut doit afficher "✅ Stripe Connect actif"

# Si le retour ne sync pas le statut, tester avec Stripe CLI :
stripe listen --forward-to http://tattoolib-saas.test/webhooks/stripe
stripe trigger account.updated
```

---

## ⚠️ Contraintes
- Adapter les noms de méthodes aux noms EXACTS trouvés dans `StripeService` (audit 1.3)
- Ne pas dupliquer la logique déjà présente dans `StripeService`
- Ne pas modifier les migrations
- Si `tattooer.payments` n'est pas le bon nom de route pour le redirect retour,
  utiliser le nom exact trouvé dans `php artisan route:list`
