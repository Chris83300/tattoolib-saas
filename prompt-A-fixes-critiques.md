# 🔧 PROMPT A — 3 FIXES CRITIQUES
# Pour Claude Code — 2FA + Modal Accept Booking + PDF Fiche Client Complète
# Commit après chaque fix

## CONTEXTE

3 bugs bloquants identifiés lors des tests manuels du SaaS Ink&Pik.
Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, Filament v4.5.

---

## PHASE 0 — AUDIT CIBLÉ

```bash
echo "=== AUDIT PROMPT A ==="

# ── FIX A1 : 2FA ──
echo "--- 2FA ---"

# A1a. Package 2FA installé ?
grep -n "two.factor\|2fa\|fortify\|google2fa\|totp\|otp" composer.json | head -5

# A1b. Fortify installé et configuré ?
cat config/fortify.php 2>/dev/null | grep -n "two\|2fa\|Features" | head -10

# A1c. Modèle User — traits 2FA
grep -n "TwoFactor\|HasTwoFactor\|Fortify" app/Models/User.php | head -5

# A1d. Colonnes 2FA en base
php artisan tinker --execute="
  \$cols = ['two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at'];
  foreach(\$cols as \$c) {
    echo \$c . ': ' . (Schema::hasColumn('users', \$c) ? 'EXISTS' : 'ABSENT') . PHP_EOL;
  }
"

# A1e. Settings.blade.php — lignes 628-668 (section 2FA mentionnée par l'utilisateur)
sed -n '620,680p' resources/views/tattooer/settings.blade.php 2>/dev/null

# A1f. Toutes les settings.blade.php des différents rôles
find resources/views -path "*/settings*" -name "*.blade.php" | sort

# A1g. Routes 2FA existantes
php artisan route:list 2>&1 | grep -i "two.factor\|2fa\|confirm\|recovery" | head -10

# A1h. Actions Fortify
ls app/Actions/Fortify/ 2>/dev/null

# A1i. FortifyServiceProvider
cat app/Providers/FortifyServiceProvider.php 2>/dev/null | head -40


# ── FIX A2 : MODAL ACCEPT BOOKING ──
echo "--- ACCEPT BOOKING MODAL ---"

# A2a. Le composant modal
find resources/views -name "*accept-booking*" -o -name "*accept_booking*" | head -5
find app/Livewire -name "*AcceptBooking*" -o -name "*BookingAccept*" | head -5

# A2b. Contenu du modal blade
cat resources/views/livewire/tattooer/accept-booking-modal.blade.php 2>/dev/null | head -40
# OU
cat resources/views/tattooer/partials/accept-booking-modal.blade.php 2>/dev/null | head -40
# OU chercher autrement
grep -rn "accept.*booking.*modal\|acceptBooking\|AcceptBookingModal" resources/views/ --include="*.blade.php" -l | head -5

# A2c. Composant Livewire associé
grep -rn "class.*AcceptBooking\|accept.*Booking" app/Livewire/ --include="*.php" -l | head -5
# Lire le composant
find app/Livewire -name "*.php" -exec grep -l "acceptBooking\|AcceptBooking\|accept_booking" {} \; | head -5

# A2d. Page request show (là où le rafraîchissement ne se fait pas)
cat resources/views/tattooer/request-show.blade.php 2>/dev/null | head -30
# OU
grep -rn "requests.*show\|request-show\|bookingRequest" resources/views/tattooer/ --include="*.blade.php" -l | head -5

# A2e. Le controller/action qui traite l'acceptation
cat app/Actions/AcceptBookingRequest.php | head -60

# A2f. Composant Livewire de la page request-show (s'il y en a un)
grep -rn "livewire\|wire:" resources/views/tattooer/request-show.blade.php 2>/dev/null | head -10

# A2g. Vérifier si c'est un composant Livewire ou une vue Blade classique avec modal Alpine
grep -rn "x-data\|@livewire\|wire:click\|wire:submit\|\$dispatch\|\$emit" resources/views/tattooer/request-show.blade.php 2>/dev/null | head -10


# ── FIX A3 : PDF FICHE CLIENT COMPLÈTE ──
echo "--- PDF FICHE CLIENT ---"

# A3a. Le template PDF client-summary
cat resources/views/pdf/client-summary.blade.php 2>/dev/null | head -80

# A3b. Le service PdfExportService — méthode generateClientSummary
grep -A 30 "generateClientSummary" app/Services/PdfExportService.php 2>/dev/null

# A3c. Vérifier que les relations consent + traçabilité sont chargées
grep -n "ConsentForm\|consentForm\|consent_form\|TraceabilityRecord\|traceability" app/Services/PdfExportService.php 2>/dev/null | head -10

# A3d. Modèle ClientConsentForm — relations
grep -n "function \|fillable\|belongsTo\|hasMany" app/Models/ClientConsentForm.php | head -15

# A3e. Modèle TraceabilityRecord — relations
grep -n "function \|fillable\|belongsTo\|hasMany\|needles\|inks" app/Models/TraceabilityRecord.php | head -15

# A3f. Le controller PDF — méthode clientSummary
grep -A 20 "clientSummary" app/Http/Controllers/PdfExportController.php 2>/dev/null

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX A1 — 2FA : ACTIVATION + BOUTONS DANS SETTINGS

### Problème
L'authentification à deux facteurs ne fonctionne pas et les boutons pour l'activer/désactiver sont absents des pages settings de tous les rôles (tattooer, piercer, studio, studio-artist).

### Diagnostic attendu (Phase 0)

**Scénario 1** — Fortify est installé et configuré mais la feature 2FA est désactivée :
```php
// config/fortify.php — décommenter la feature
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::twoFactorAuthentication([     // ← DÉCOMMENTER cette ligne
        'confirm' => true,
        'confirmPassword' => true,
    ]),
],
```

**Scénario 2** — Fortify n'est PAS installé :
```bash
composer require laravel/fortify
php artisan fortify:install
php artisan migrate
```

**Scénario 3** — Les colonnes 2FA sont absentes de la table users :
```bash
php artisan migrate
# Ou créer la migration manuellement :
php artisan make:migration add_two_factor_columns_to_users_table
```
```php
Schema::table('users', function (Blueprint $table) {
    $table->text('two_factor_secret')->nullable()->after('password');
    $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
    $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
});
```

### Implémenter le bouton 2FA

Vérifier que le trait `TwoFactorAuthenticatable` est sur le modèle User :
```php
// app/Models/User.php
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use TwoFactorAuthenticatable;
    // ...
}
```

Créer un partial Blade réutilisable pour la section 2FA :

```blade
{{-- resources/views/partials/two-factor-settings.blade.php --}}
@php
    $user = auth()->user();
    $enabled = $user->hasEnabledTwoFactorAuthentication();
    $confirmed = $user->two_factor_confirmed_at !== null;
@endphp

<div class="bg-gris-fonde rounded-xl border border-titane/10 p-6" x-data="{ 
    showQrCode: false, 
    showRecoveryCodes: false,
    enabling: false,
    confirming: false,
    disabling: false,
    confirmationCode: '',
    password: '',
}">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-ivoire-text">Authentification à deux facteurs (2FA)</h3>
            <p class="text-sm text-titane mt-1">
                Ajoutez une couche de sécurité supplémentaire à votre compte en utilisant une application d'authentification.
            </p>
        </div>
        <div>
            @if ($enabled && $confirmed)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium bg-green-500/10 text-green-400 rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Activée
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium bg-rouge-alerte/10 text-rouge-alerte rounded-full">
                    Désactivée
                </span>
            @endif
        </div>
    </div>

    {{-- ÉTAT 1 : 2FA désactivée → bouton pour activer --}}
    @if (! $enabled)
        <form method="POST" action="{{ url('/user/two-factor-authentication') }}" 
            @submit.prevent="enabling = true; $el.submit()">
            @csrf
            <div class="flex items-center gap-4">
                <button type="submit" :disabled="enabling"
                    class="px-4 py-2 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors disabled:opacity-50">
                    <span x-show="!enabling">Activer la 2FA</span>
                    <span x-show="enabling">Activation...</span>
                </button>
                <p class="text-xs text-titane">
                    Vous aurez besoin d'une application comme Google Authenticator, Authy ou 1Password.
                </p>
            </div>
        </form>
    @endif

    {{-- ÉTAT 2 : 2FA activée mais pas encore confirmée → afficher QR code + champ confirmation --}}
    @if ($enabled && ! $confirmed)
        <div class="space-y-4">
            <div class="p-4 bg-noir-profond/50 rounded-lg">
                <p class="text-sm text-ivoire-text mb-3">Scannez ce QR code avec votre application d'authentification :</p>
                <div class="flex justify-center bg-white p-4 rounded-lg w-fit mx-auto">
                    {!! $user->twoFactorQrCodeSvg() !!}
                </div>
                <div class="mt-3">
                    <p class="text-xs text-titane">Ou entrez ce code manuellement :</p>
                    <code class="block mt-1 text-sm text-beige-peau bg-noir-profond px-3 py-2 rounded font-mono break-all">
                        {{ decrypt($user->two_factor_secret) }}
                    </code>
                </div>
            </div>

            <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}"
                @submit.prevent="confirming = true; $el.submit()">
                @csrf
                <label class="block text-sm text-titane mb-2">
                    Entrez le code à 6 chiffres de votre application pour confirmer :
                </label>
                <div class="flex items-center gap-3">
                    <input type="text" name="code" x-model="confirmationCode" required autofocus
                        maxlength="6" inputmode="numeric" pattern="[0-9]*"
                        class="w-40 px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-center text-lg tracking-widest focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                    <button type="submit" :disabled="confirming || confirmationCode.length < 6"
                        class="px-4 py-2 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors disabled:opacity-50">
                        Confirmer
                    </button>
                </div>
                @error('code')
                    <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p>
                @enderror
            </form>
        </div>
    @endif

    {{-- ÉTAT 3 : 2FA activée et confirmée → afficher codes de récupération + bouton désactiver --}}
    @if ($enabled && $confirmed)
        <div class="space-y-4">
            {{-- Codes de récupération --}}
            <div>
                <button @click="showRecoveryCodes = !showRecoveryCodes" type="button"
                    class="text-sm text-beige-peau hover:underline">
                    <span x-show="!showRecoveryCodes">Afficher les codes de récupération</span>
                    <span x-show="showRecoveryCodes">Masquer les codes de récupération</span>
                </button>

                <div x-show="showRecoveryCodes" x-transition class="mt-3 p-4 bg-noir-profond/50 rounded-lg">
                    <p class="text-xs text-titane mb-2">
                        Conservez ces codes dans un endroit sûr. Ils vous permettent de vous connecter si vous perdez l'accès à votre application d'authentification.
                    </p>
                    <div class="grid grid-cols-2 gap-1">
                        @foreach (json_decode(decrypt($user->two_factor_recovery_codes), true) as $code)
                            <code class="text-sm text-ivoire-text font-mono bg-noir-profond px-2 py-1 rounded">{{ $code }}</code>
                        @endforeach
                    </div>
                    <form method="POST" action="{{ url('/user/two-factor-recovery-codes') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="text-xs text-beige-peau hover:underline">
                            Regénérer les codes de récupération
                        </button>
                    </form>
                </div>
            </div>

            {{-- Désactiver --}}
            <div class="pt-4 border-t border-titane/10">
                <form method="POST" action="{{ url('/user/two-factor-authentication') }}"
                    @submit.prevent="if(confirm('Êtes-vous sûr de vouloir désactiver la 2FA ?')) { disabling = true; $el.submit(); }">
                    @csrf
                    @method('DELETE')
                    <button type="submit" :disabled="disabling"
                        class="px-4 py-2 text-sm font-medium text-rouge-alerte border border-rouge-alerte/30 rounded-lg hover:bg-rouge-alerte/10 transition-colors disabled:opacity-50">
                        Désactiver la 2FA
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
```

### Inclure le partial dans TOUTES les pages settings

```bash
# Trouver toutes les pages settings
find resources/views -path "*/settings*" -name "*.blade.php" | sort
```

Pour CHAQUE fichier settings trouvé (tattooer, piercer, studio, client), inclure le partial.

Trouver la bonne section (vers la ligne 628-668 dans settings.blade.php du tattooer comme indiqué) :

```bash
# Lire la zone autour de la ligne 628
sed -n '610,680p' resources/views/tattooer/settings.blade.php 2>/dev/null
```

Insérer le partial à la suite de la section « Modifier le mot de passe » ou « Sécurité » existante :

```blade
{{-- Après la section mot de passe, ajouter : --}}
<div class="mt-8">
    @include('partials.two-factor-settings')
</div>
```

Faire de même pour :
- `resources/views/piercer/settings.blade.php` (ou la vue settings du pierceur)
- `resources/views/studio/settings.blade.php`
- `resources/views/client/settings.blade.php` (si applicable)

Vérifier aussi les layouts Livewire settings si la page est un composant Livewire :
```bash
grep -rn "class.*Settings" app/Livewire/ --include="*.php" -l | head -10
```

### Vérifier que les routes Fortify sont actives

```bash
php artisan route:list 2>&1 | grep "two-factor" | head -5
```

Si les routes sont absentes, vérifier que FortifyServiceProvider est dans `config/app.php` ou `bootstrap/providers.php` :

```bash
grep -rn "FortifyServiceProvider\|Fortify" config/app.php bootstrap/providers.php 2>/dev/null | head -5
```

```bash
git add -A && git commit -m "fix(A1): 2FA fonctionnel — Fortify activé + boutons dans toutes les pages settings"
```

---

## FIX A2 — ACCEPT BOOKING MODAL : RAFRAÎCHISSEMENT PAGE

### Problème
Après acceptation d'une demande via `accept-booking-modal.blade.php`, la page `/tattooer/requests/{id}` n'est pas mise à jour automatiquement. L'utilisateur doit recharger manuellement pour voir le nouveau statut.

### Diagnostic

Le problème est très probablement l'un de ces cas :

**Cas 1** — Le modal est un composant Alpine/Blade (pas Livewire) et fait un appel AJAX/fetch ou un form POST classique. Après le POST, il n'y a pas de redirect ou de refresh de la page.

**Cas 2** — Le modal est un composant Livewire enfant et émet un event, mais le composant parent ne l'écoute pas pour se rafraîchir.

**Cas 3** — La page `request-show` est une vue Blade classique (pas Livewire). Le modal Livewire met à jour les données en base mais la page Blade ne sait pas qu'il faut se recharger.

### Solution selon le cas

#### Si le modal est Alpine/Blade avec form POST :

Trouver la méthode du controller qui traite l'acceptation :
```bash
grep -rn "acceptBooking\|accept_booking\|AcceptBooking" app/Http/Controllers/ app/Livewire/ --include="*.php" | head -10
```

Vérifier ce que retourne le controller après acceptation :
```bash
grep -A 10 "function accept\|function store\|function update" app/Http/Controllers/TattooerController.php 2>/dev/null | grep -A 5 "return\|redirect"
```

**Fix** — S'assurer que le controller redirige vers la même page après acceptation :
```php
return redirect()->route('tattooer.requests.show', $bookingRequest)
    ->with('success', 'Demande acceptée avec succès.');
```

OU si c'est un appel AJAX/fetch dans le modal Alpine :
```javascript
// Dans le modal Alpine, après le fetch/POST réussi :
// AJOUTER un window.location.reload() ou un redirect
fetch(url, { method: 'POST', ... })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload(); // ← AJOUTER CECI
        }
    });
```

#### Si le modal est Livewire :

Trouver le composant Livewire du modal :
```bash
find app/Livewire -name "*AcceptBooking*" -o -name "*BookingAccept*" | head -5
grep -rn "class.*AcceptBooking" app/Livewire/ --include="*.php" | head -5
```

Lire la méthode d'acceptation dans le composant :
```bash
grep -A 20 "function accept\|function submit\|function confirm\|function store" app/Livewire/Tattooer/AcceptBookingModal.php 2>/dev/null
```

**Fix Livewire** — À la fin de la méthode d'acceptation, émettre un event pour rafraîchir :

```php
// Dans le composant Livewire du modal, après l'acceptation :
public function accept() // ou submit/confirm
{
    // ... logique existante d'acceptation ...

    // AJOUTER : dispatch un event navigateur pour rafraîchir
    $this->dispatch('booking-accepted');
    
    // OU : redirect dans Livewire
    return redirect()->route('tattooer.requests.show', $this->bookingRequest);
}
```

**Si la page parent est Blade classique** (pas un composant Livewire), le `$this->dispatch()` ne sera pas capté. Dans ce cas, utiliser un redirect OU un événement JavaScript :

```php
// Option A : Redirect Livewire
return $this->redirect(route('tattooer.requests.show', $this->bookingRequest), navigate: true);

// Option B : Émettre un event JS pour que Alpine recharge la page
$this->dispatch('booking-accepted');
```

Et dans la vue Blade qui contient le modal :
```blade
{{-- Dans request-show.blade.php --}}
<div x-data @booking-accepted.window="window.location.reload()">
    {{-- ... contenu de la page ... --}}
    @livewire('tattooer.accept-booking-modal', ['bookingRequest' => $bookingRequest])
</div>
```

#### Si le modal est un <form> HTML classique dans une @include Blade :

```bash
# Trouver le form action
grep -n "action=\|method=\|@csrf\|wire:submit\|x-on:submit\|fetch\|axios" resources/views/tattooer/partials/accept-booking-modal.blade.php 2>/dev/null
grep -n "action=\|method=\|@csrf\|wire:submit\|x-on:submit\|fetch\|axios" resources/views/livewire/tattooer/accept-booking-modal.blade.php 2>/dev/null
```

Si c'est un `<form action="..." method="POST">` classique :
- Le controller doit retourner `redirect()->back()->with('success', ...)` ou `redirect()->route(...)`.

Si c'est un `wire:submit` :
- Le composant Livewire doit émettre un redirect après le traitement (voir ci-dessus).

### Test

Après le fix :
1. Aller sur `/tattooer/requests/4`
2. Cliquer sur "Accepter"
3. Remplir le modal
4. Soumettre
5. **La page doit se rafraîchir automatiquement et afficher le nouveau statut**

```bash
git add -A && git commit -m "fix(A2): accept-booking-modal rafraîchit la page après acceptation"
```

---

## FIX A3 — PDF FICHE CLIENT COMPLÈTE (CONSENTEMENT + TRAÇABILITÉ MANQUANTS)

### Problème
L'export PDF de la fiche client complète (`/pdf/client/{id}/recap`) ne contient pas les consentements signés ni les enregistrements de traçabilité. Seules les fiches de soins sont exportées.

### Diagnostic

Lire la méthode `generateClientSummary` dans le service :
```bash
grep -B 5 -A 40 "generateClientSummary" app/Services/PdfExportService.php
```

Et le template :
```bash
cat resources/views/pdf/client-summary.blade.php
```

### Fix — Service PdfExportService

La méthode `generateClientSummary` doit charger ET passer au template :
1. Les fiches de soins (`ClientCareSheet`) ← probablement déjà fait
2. Les consentements signés (`ClientConsentForm`) ← MANQUANT ?
3. Les enregistrements de traçabilité + leurs aiguilles et encres (`TraceabilityRecord` + `needles` + `inks`) ← MANQUANT ?

Vérifier ce qui est chargé :
```bash
grep -n "CareSheet\|ConsentForm\|TraceabilityRecord\|consent\|traceability\|needles\|inks" app/Services/PdfExportService.php | head -20
```

**Fix dans PdfExportService** — S'assurer que la méthode charge TOUTES les données :

```php
public function generateClientSummary($client, $artisan): \Barryvdh\DomPDF\PDF
{
    // Fiches de soins (déjà fait normalement)
    $careSheets = ClientCareSheet::where('client_id', $client->id)
        ->where(function ($q) use ($artisan) {
            $q->where('bookable_type', get_class($artisan))
              ->where('bookable_id', $artisan->id);
            if ($artisan->studio_id) {
                $q->orWhere('studio_id', $artisan->studio_id);
            }
        })
        ->latest()
        ->get();

    // ═══ AJOUTER : Consentements signés ═══
    $consentForms = ClientConsentForm::where('client_id', $client->id)
        ->where(function ($q) use ($artisan) {
            $q->where('bookable_type', get_class($artisan))
              ->where('bookable_id', $artisan->id);
            if ($artisan->studio_id) {
                $q->orWhere('studio_id', $artisan->studio_id);
            }
        })
        ->latest()
        ->get();

    // ═══ AJOUTER : Enregistrements de traçabilité avec aiguilles et encres ═══
    $traceRecords = TraceabilityRecord::where('client_id', $client->id)
        ->where(function ($q) use ($artisan) {
            $q->where('bookable_type', get_class($artisan))
              ->where('bookable_id', $artisan->id);
            if ($artisan->studio_id) {
                $q->orWhere('studio_id', $artisan->studio_id);
            }
        })
        ->with(['needles', 'inks'])
        ->latest()
        ->get();

    return Pdf::loadView('pdf.client-summary', [
        'client' => $client,
        'artisan' => $artisan,
        'careSheets' => $careSheets,
        'consentForms' => $consentForms,       // ← AJOUTER
        'traceRecords' => $traceRecords,       // ← AJOUTER
        'generatedAt' => now(),
    ])->setPaper('a4');
}
```

IMPORTANT : Adapter les noms de colonnes et relations selon la structure réelle des modèles trouvée en Phase 0. Les queries polymorphiques utilisent `bookable_type`/`bookable_id` — vérifier que ces colonnes existent sur les 3 tables. Si les tables utilisent `tattooer_id`/`piercer_id` directement, adapter les where clauses.

### Fix — Template PDF client-summary

Vérifier que le template affiche les 3 sections. Si les sections consentement et traçabilité sont absentes, les ajouter :

```blade
{{-- DANS resources/views/pdf/client-summary.blade.php --}}

{{-- ═══ SECTION CONSENTEMENTS — AJOUTER SI ABSENTE ═══ --}}
@if ($consentForms->count() > 0)
<h2>Consentements signés ({{ $consentForms->count() }})</h2>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Zone corporelle</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($consentForms as $cf)
        <tr>
            <td>{{ $cf->created_at?->format('d/m/Y') }}</td>
            <td>{{ $cf->type ?? 'Consentement éclairé' }}</td>
            <td>{{ $cf->body_area ?? $cf->zone ?? '—' }}</td>
            <td>{{ $cf->signed_at ? '✓ Signé le ' . $cf->signed_at->format('d/m/Y') : ($cf->consent_given ? '✓ Consenti' : '✗ Non signé') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<h2>Consentements</h2>
<p class="text-muted">Aucun consentement enregistré.</p>
@endif

{{-- ═══ SECTION TRAÇABILITÉ — AJOUTER SI ABSENTE ═══ --}}
@if ($traceRecords->count() > 0)
<h2>Traçabilité des actes ({{ $traceRecords->count() }})</h2>

@foreach ($traceRecords as $tr)
<h3>Acte du {{ $tr->performed_at?->format('d/m/Y') ?? $tr->created_at?->format('d/m/Y') }} — {{ $tr->body_area ?? 'Zone non précisée' }}</h3>

@if ($tr->notes)
<p><strong>Notes :</strong> {{ $tr->notes }}</p>
@endif

{{-- Aiguilles --}}
@if ($tr->needles && $tr->needles->count() > 0)
<table>
    <thead>
        <tr>
            <th>Aiguille — Marque</th>
            <th>Référence</th>
            <th>N° de lot</th>
            <th>Péremption</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tr->needles as $needle)
        <tr>
            <td>{{ $needle->brand ?? '—' }}</td>
            <td>{{ $needle->reference ?? '—' }}</td>
            <td>{{ $needle->batch_number ?? $needle->lot_number ?? '—' }}</td>
            <td>{{ $needle->expiry_date?->format('d/m/Y') ?? '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Encres --}}
@if ($tr->inks && $tr->inks->count() > 0)
<table>
    <thead>
        <tr>
            <th>Encre — Marque</th>
            <th>Couleur</th>
            <th>Référence</th>
            <th>N° de lot</th>
            <th>REACH</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tr->inks as $ink)
        <tr>
            <td>{{ $ink->brand ?? '—' }}</td>
            <td>{{ $ink->color ?? '—' }}</td>
            <td>{{ $ink->reference ?? '—' }}</td>
            <td>{{ $ink->batch_number ?? $ink->lot_number ?? '—' }}</td>
            <td>{{ $ink->reach_compliant ? '✓' : '✗' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if (!$loop->last)
<div style="border-bottom: 1px dashed #d4c9bc; margin: 15px 0;"></div>
@endif

@endforeach
@else
<h2>Traçabilité</h2>
<p class="text-muted">Aucun enregistrement de traçabilité.</p>
@endif
```

Si ces sections existent déjà dans le template mais ne reçoivent pas de données (variables `$consentForms` et `$traceRecords` vides ou non passées), le fix est uniquement dans le Service (Phase 3 ci-dessus).

### Vérifier que le Controller passe bien les variables

```bash
grep -A 15 "clientSummary" app/Http/Controllers/PdfExportController.php
```

Le controller doit appeler `$this->pdfService->generateClientSummary($client, $artisan)` qui retourne le PDF avec toutes les données. Si le controller fait lui-même des queries au lieu d'utiliser le service, s'assurer qu'il charge aussi les consentements et la traçabilité.

```bash
git add -A && git commit -m "fix(A3): PDF fiche client complète avec consentements + traçabilité"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT A ==="

# V1. 2FA
echo "--- 2FA ---"
php artisan route:list 2>&1 | grep "two-factor" | head -5
echo "Routes 2FA OK si > 0"

grep -c "two-factor-settings\|partials.two-factor" resources/views/tattooer/settings.blade.php resources/views/piercer/settings.blade.php resources/views/studio/settings.blade.php resources/views/client/settings.blade.php 2>/dev/null
echo "Boutons 2FA dans les settings"

php artisan tinker --execute="
  echo 'two_factor_secret col: ' . (Schema::hasColumn('users', 'two_factor_secret') ? 'OK' : 'ABSENT') . PHP_EOL;
"

# V2. Accept Booking Modal
echo "--- ACCEPT BOOKING ---"
# Vérifier qu'un redirect ou reload existe après l'acceptation
grep -n "redirect\|reload\|dispatch.*booking-accepted\|navigate" app/Livewire/Tattooer/AcceptBookingModal.php app/Http/Controllers/TattooerController.php 2>/dev/null | head -5
echo "Redirect/refresh après acceptation OK si > 0"

# V3. PDF Client Summary
echo "--- PDF CLIENT SUMMARY ---"
grep -c "consentForms\|consent" app/Services/PdfExportService.php
echo "ConsentForms dans le service (doit être > 0)"
grep -c "traceRecords\|traceability\|TraceabilityRecord" app/Services/PdfExportService.php
echo "TraceabilityRecords dans le service (doit être > 0)"
grep -c "consentForms\|consent" resources/views/pdf/client-summary.blade.php
echo "Section consentement dans le template (doit être > 0)"
grep -c "traceRecords\|needles\|inks" resources/views/pdf/client-summary.blade.php
echo "Section traçabilité dans le template (doit être > 0)"

# V4. Compilation
php artisan route:clear
php artisan view:clear
php artisan route:list 2>&1 | head -3
echo "Pas d'erreur = OK"

echo "=== PROMPT A TERMINÉ — 3 fixes ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire, les vrais fichiers/noms peuvent différer
2. **2FA : Fortify natif** — utiliser les routes Fortify `/user/two-factor-authentication`, ne pas réinventer
3. **Modal : identifier le type** (Alpine/Blade vs Livewire vs form classique) AVANT de fixer
4. **PDF : ne pas supprimer** ce qui existe déjà dans client-summary, AJOUTER les sections manquantes
5. **Adapter les noms de colonnes** aux vrais noms trouvés en Phase 0 (bookable_type vs tattooer_id, signed_at vs consent_given, etc.)
6. **Commit après chaque fix** (3 commits)
7. **Tester visuellement si possible** : `php artisan serve` et naviguer vers les pages concernées
