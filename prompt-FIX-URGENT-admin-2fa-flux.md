# 🚨 FIX URGENT — Accès admin bloqué + Flux UI + CSP confirm-password

## Problèmes en cascade
1. Login /admin/login → redirigé vers /user/confirm-password (bloque l'accès)
2. CSP bloque les scripts de confirm-password (pas de nonce)
3. /settings/two-factor → erreurs `copied is not defined` (Flux UI non installé)
4. Impossible d'accéder au dashboard Filament admin

---

## PHASE 1 — DIAGNOSTIC COMPLET

```bash
# Vérifier la config Fortify
cat config/fortify.php | grep -A 5 "confirmPassword\|confirm_password\|password_timeout"

# Vérifier les middlewares sur les routes admin Filament
grep -n "confirmPassword\|password.confirm\|EnsurePasswordIsConfirmed" \
  app/Providers/Filament/AdminPanelProvider.php \
  routes/web.php 2>/dev/null

# Vérifier la vue confirm-password
cat resources/views/auth/confirm-password.blade.php | head -30

# Vérifier la présence de Flux UI
composer show | grep -i flux
grep -rn "data-flux\|x-flux\|\$flux\|copied" \
  resources/views/ --include="*.blade.php" | grep -v "vendor" | head -20

# Lire le composant TwoFactor Livewire
find app/Livewire -name "*TwoFactor*" -o -name "*two*factor*" 2>/dev/null | head -5
find resources/views/livewire -name "*two*factor*" -o -name "*2fa*" 2>/dev/null | head -5
```

---

## FIX 1 — Désactiver la reconfirmation de mot de passe pour /admin

La reconfirmation de mot de passe Fortify s'active automatiquement
sur certaines routes. Pour le panel Filament admin, il faut la désactiver.

### Option A — Dans config/fortify.php

```php
// Désactiver la fonctionnalité confirmPasswords si pas nécessaire
// (elle force /user/confirm-password avant les actions sensibles)

'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::twoFactorAuthentication([
        'confirm'       => true,
        'confirmPassword' => false, // ← DÉSACTIVER la reconfirmation
    ]),
],
```

### Option B — Exclure /admin/* du middleware password.confirm

Dans `app/Http/Middleware/Authenticate.php` ou dans le
`AdminPanelProvider`, s'assurer que le middleware
`password.confirm` n'est PAS appliqué sur les routes admin :

```bash
# Vérifier si password.confirm est dans les middlewares admin
grep -n "password.confirm\|confirmPassword\|EnsurePassword" \
  app/Providers/Filament/AdminPanelProvider.php
```

Si présent → le retirer.

### Option C — Augmenter le timeout de reconfirmation

Dans `config/auth.php` :
```php
'password_timeout' => 10800, // 3 heures (au lieu de 900 secondes = 15min)
```

Dans `.env` :
```env
PASSWORD_TIMEOUT=10800
```

---

## FIX 2 — CSP sur la page confirm-password

La vue `confirm-password.blade.php` a un script inline sans nonce.

Lire `resources/views/auth/confirm-password.blade.php`.

Ajouter `nonce="{{ csp_nonce() }}"` sur chaque `<script>` inline :

```blade
{{-- Trouver et corriger --}}
<script>  {{-- ❌ --}}
<script nonce="{{ csp_nonce() }}">  {{-- ✅ --}}
```

Si la vue étend un layout guest qui a aussi des scripts :
```bash
grep -n "extends\|component\|layout" \
  resources/views/auth/confirm-password.blade.php
grep -n "<script" resources/views/components/guest-layout.blade.php 2>/dev/null || \
  find resources/views -name "guest*" | xargs grep -l "<script" 2>/dev/null
```

Ajouter le nonce sur tous les scripts inline du layout guest également.

---

## FIX 3 — Flux UI non installé → supprimer les composants Flux

### 3.1 — Identifier tous les fichiers utilisant Flux UI

```bash
grep -rln "data-flux\|\$flux\|x-flux\|copied\|flux-icon\|flux-button" \
  resources/views/ app/Livewire/ --include="*.blade.php" --include="*.php" | \
  grep -v vendor
```

### 3.2 — Lire et réécrire le composant TwoFactor

Lire intégralement la vue Livewire TwoFactor.
Remplacer TOUS les éléments Flux UI par des équivalents Tailwind/Alpine :

**Remplacement des `data-flux-icon` (icônes SVG Flux) :**
```blade
{{-- ❌ AVANT — Flux UI icon avec variable 'copied' --}}
<svg data-flux-icon x-show="!copied" ...>
<svg data-flux-icon x-show="copied" ...>

{{-- ✅ APRÈS — Alpine.js standard --}}
<div x-data="{ copied: false }">
    <button @click="
        navigator.clipboard.writeText($el.closest('[data-copy]').dataset.copy);
        copied = true;
        setTimeout(() => copied = false, 2000);
    ">
        <svg x-show="!copied" class="w-5 h-5" ...><!-- icône copier --></svg>
        <svg x-show="copied" class="w-5 h-5 text-green-500" ...><!-- icône check --></svg>
    </button>
</div>
```

**Remplacement des `$flux.appearance` :**
```blade
{{-- ❌ AVANT --}}
:style="($flux.appearance === 'dark') ? 'filter: invert(1)' : ''"

{{-- ✅ APRÈS - supprimer simplement ce style conditionnel --}}
{{-- Ou utiliser une classe Tailwind dark: --}}
class="dark:filter dark:invert"
```

**Remplacement des composants `<flux:button>`, `<flux:input>`, etc. :**
```blade
{{-- ❌ AVANT --}}
<flux:button variant="primary">Activer</flux:button>

{{-- ✅ APRÈS --}}
<button type="submit"
        class="px-4 py-2 bg-primary-600 text-white rounded-xl
               font-medium hover:bg-primary-700 transition">
    Activer
</button>
```

### 3.3 — Réécrire complètement la vue TwoFactor si trop de Flux UI

Si plus de 10 usages de Flux UI dans la vue → réécrire entièrement :

```blade
{{-- resources/views/livewire/settings/two-factor.blade.php --}}
<div class="space-y-6 max-w-xl">

    <div>
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
            Double authentification (2FA)
        </h3>
        <p class="text-sm text-gray-500 mt-1">
            Protégez votre compte avec une application d'authentification.
        </p>
    </div>

    @if ($this->enabled)

        @if (session('status') === 'two-factor-authentication-confirmed')
        <div class="p-3 bg-green-50 dark:bg-green-900/30 border border-green-200
                    dark:border-green-800 rounded-xl text-sm text-green-700
                    dark:text-green-300">
            ✅ Double authentification activée avec succès.
        </div>
        @endif

        {{-- 2FA Activé --}}
        <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200
                    dark:border-green-800 rounded-xl flex items-center gap-3">
            <span class="text-2xl">🔐</span>
            <div>
                <p class="font-medium text-green-800 dark:text-green-200">
                    Double authentification activée
                </p>
                <p class="text-sm text-green-600 dark:text-green-300 mt-0.5">
                    Votre compte est sécurisé.
                </p>
            </div>
        </div>

        {{-- Codes de récupération --}}
        @if ($showingRecoveryCodes)
        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border
                    border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                🔑 Codes de récupération d'urgence
            </p>
            <p class="text-xs text-gray-500 mb-3">
                Conservez ces codes dans un endroit sûr. Chaque code ne peut
                être utilisé qu'une seule fois.
            </p>
            <div class="grid grid-cols-2 gap-2">
                @foreach ($this->user->recoveryCodes() as $code)
                <code class="text-xs font-mono bg-white dark:bg-gray-900
                             border border-gray-200 dark:border-gray-600
                             rounded px-3 py-2 text-center text-gray-700
                             dark:text-gray-300">
                    {{ $code }}
                </code>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex flex-wrap gap-3">
            @unless ($showingRecoveryCodes)
            <button wire:click="showRecoveryCodes"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600
                           text-gray-700 dark:text-gray-300 rounded-xl text-sm
                           hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Voir les codes de récupération
            </button>
            @else
            <button wire:click="regenerateRecoveryCodes"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600
                           text-gray-700 dark:text-gray-300 rounded-xl text-sm
                           hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Régénérer les codes
            </button>
            @endunless

            <button wire:click="disableTwoFactorAuthentication"
                    wire:confirm="Désactiver la double authentification ?"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm
                           font-medium hover:bg-red-700 transition">
                Désactiver le 2FA
            </button>
        </div>

    @elseif ($this->confirming)

        {{-- Étape 2 : Confirmer avec le QR Code --}}
        <div class="space-y-4">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200
                        dark:border-blue-800 rounded-xl text-sm text-blue-800
                        dark:text-blue-200">
                📱 Scannez ce QR code avec Google Authenticator, Authy ou
                Microsoft Authenticator.
            </div>

            <div class="flex justify-center p-6 bg-white rounded-xl border
                        border-gray-200 dark:border-gray-700">
                {!! $this->user->twoFactorQrCodeSvg() !!}
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Clé manuelle :</p>
                <code class="text-sm font-mono text-gray-700 dark:text-gray-300
                             break-all bg-gray-50 dark:bg-gray-800 px-3 py-2
                             rounded block">
                    {{ decrypt($this->user->two_factor_secret) }}
                </code>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700
                               dark:text-gray-300 mb-2">
                    Code de confirmation
                </label>
                <input type="text"
                       wire:model="confirmationCode"
                       inputmode="numeric"
                       placeholder="123456"
                       autocomplete="one-time-code"
                       class="w-full border border-gray-300 dark:border-gray-600
                              rounded-xl px-4 py-3 text-center text-xl
                              tracking-widest focus:outline-none
                              focus:ring-2 focus:ring-primary-400
                              dark:bg-gray-800 dark:text-white">
                @error('confirmationCode')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button wire:click="confirmTwoFactorAuthentication"
                        class="flex-1 py-3 bg-primary-600 text-white rounded-xl
                               font-medium hover:bg-primary-700 transition">
                    Confirmer et activer
                </button>
                <button wire:click="disableTwoFactorAuthentication"
                        class="px-4 py-3 border border-gray-300 dark:border-gray-600
                               text-gray-700 dark:text-gray-300 rounded-xl text-sm
                               hover:bg-gray-50 transition">
                    Annuler
                </button>
            </div>
        </div>

    @else

        {{-- Étape 1 : Activer --}}
        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200
                    dark:border-yellow-800 rounded-xl flex items-center gap-3">
            <span class="text-2xl">🔓</span>
            <div>
                <p class="font-medium text-yellow-800 dark:text-yellow-200">
                    Double authentification désactivée
                </p>
                <p class="text-sm text-yellow-600 dark:text-yellow-300 mt-0.5">
                    Activez le 2FA pour sécuriser votre compte.
                </p>
            </div>
        </div>

        <button wire:click="enableTwoFactorAuthentication"
                class="px-4 py-2 bg-primary-600 text-white rounded-xl font-medium
                       text-sm hover:bg-primary-700 transition">
            Activer la double authentification
        </button>

    @endif
</div>
```

---

## FIX 4 — Vérifier les méthodes du composant Livewire TwoFactor

Le composant doit avoir ces méthodes (vérifier qu'elles existent) :

```bash
grep -n "public function\|public \$\|public bool" \
  app/Livewire/Settings/TwoFactor.php 2>/dev/null || \
  find app/Livewire -name "*TwoFactor*" -exec grep -n "public function" {} \;
```

Propriétés requises :
- `$this->enabled` → bool (2FA activé ou non)
- `$this->confirming` → bool (en cours de confirmation QR)
- `$this->showingRecoveryCodes` → bool
- `$this->confirmationCode` → string (wire:model)

Méthodes requises :
- `enableTwoFactorAuthentication()`
- `confirmTwoFactorAuthentication()`
- `disableTwoFactorAuthentication()`
- `showRecoveryCodes()`
- `regenerateRecoveryCodes()`

Si certaines manquent → les ajouter en se basant sur la doc Fortify/Jetstream.

---

## VALIDATION

```bash
php artisan view:clear
php artisan config:clear

# Test 1 : Login admin → doit aller sur /admin DIRECTEMENT
# (sans passer par /user/confirm-password)

# Test 2 : /settings/two-factor → 0 erreur console Alpine
# Bouton "Activer" → QR code s'affiche ✅

# Test 3 : Confirmer le code → 2FA activé ✅

# Test 4 : Re-login → page /two-factor-challenge en français ✅
```

## ⚠️ CONTRAINTES
- NE PAS installer Flux UI — tout remplacer par Tailwind + Alpine natif
- Garder les wire:click Livewire existants (ne pas changer la logique)
- Le FIX 1 (confirm-password désactivé pour admin) est prioritaire absolu
- Rapport final : accès /admin direct après login ✅ + 0 erreur console
