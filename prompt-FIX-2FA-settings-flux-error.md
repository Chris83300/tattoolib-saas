# 🔧 FIX — Page /settings/two-factor ($flux error + anglais)

## Problèmes identifiés
1. `$flux is not defined` → composant Livewire utilise Flux UI non chargé
2. Page en anglais → vues Livewire/Fortify non personnalisées
3. Le layout affiché est celui du Studio (mauvaise redirection)

---

## PHASE 1 — DIAGNOSTIC

```bash
# Identifier la source de la page two-factor
grep -rn "two-factor\|TwoFactor\|two_factor" \
  app/Livewire/ routes/ --include="*.php" | grep -v "vendor"

# Vérifier si Flux UI est installé
composer show | grep flux
cat package.json | grep flux

# Lire le composant Livewire qui gère le 2FA
find app/Livewire -name "*TwoFactor*" -o -name "*two*factor*" \
  -o -name "*Factor*" 2>/dev/null

# Lire la vue associée
find resources/views/livewire -name "*two*" -o -name "*factor*" \
  -o -name "*2fa*" 2>/dev/null

# Vérifier les routes settings
php artisan route:list | grep -i "settings\|two-factor\|2fa"
```

---

## PHASE 2 — FIX $flux is not defined

### Cas A — Flux UI non installé (probable)

```bash
# Vérifier
composer show livewire/flux 2>/dev/null || echo "Flux UI non installé"
```

Si non installé, le composant utilise `$flux` (Alpine magic de Flux UI)
qu'il faut remplacer par du Alpine.js standard.

Lire le composant Livewire et sa vue, identifier chaque usage de `$flux` :

```bash
grep -rn "\$flux" resources/views/ --include="*.blade.php"
```

Remplacer `$flux.appearance` (gestion dark/light mode de Flux) :

```blade
{{-- ❌ AVANT — requiert Flux UI --}}
:style="($flux.appearance === 'dark' || ...) ? 'filter: invert(1) brightness(1.5)' : ''"

{{-- ✅ APRÈS — Alpine.js natif --}}
:class="document.documentElement.classList.contains('dark') ? 'filter invert brightness-150' : ''"

{{-- Ou simplement supprimer si non critique --}}
```

### Cas B — Flux UI installé mais non chargé dans ce layout

Si Flux est dans composer.json mais que le layout de settings
ne charge pas ses assets :

```bash
grep -rn "@fluxStyles\|@fluxScripts\|flux" \
  resources/views/layouts/ --include="*.blade.php" | head -10
```

Ajouter dans le layout concerné si manquant :
```blade
@fluxStyles  {{-- dans <head> --}}
@fluxScripts {{-- avant </body> --}}
```

---

## PHASE 3 — RÉÉCRIRE LA PAGE 2FA EN FRANÇAIS

### 3.1 — Identifier et lire le composant actuel

```bash
# Lire le composant Livewire TwoFactor
find app/Livewire -name "*.php" | xargs grep -l "two_factor\|TwoFactor\|2fa" 2>/dev/null

# Lire la vue
find resources/views/livewire -name "*.blade.php" | \
  xargs grep -l "two.factor\|Two Factor\|Authentication Code" 2>/dev/null
```

### 3.2 — Réécrire la vue en français sans Flux UI

Réécrire la vue du composant TwoFactor en utilisant
uniquement Tailwind + Alpine.js (pas de $flux) :

```blade
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Double authentification (2FA)
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Ajoutez une couche de sécurité supplémentaire à votre compte.
        </p>
    </div>

    @if (!auth()->user()->two_factor_confirmed_at)
    {{-- 2FA désactivé --}}
    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200
                dark:border-yellow-800 rounded-xl">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🔓</span>
            <div>
                <p class="font-medium text-yellow-800 dark:text-yellow-200">
                    Double authentification désactivée
                </p>
                <p class="text-sm text-yellow-600 dark:text-yellow-300 mt-0.5">
                    Votre compte n'est pas protégé par le 2FA.
                </p>
            </div>
        </div>
    </div>

    {{-- Bouton activer --}}
    <form method="POST" action="{{ route('two-factor.enable') }}">
        @csrf
        <button type="submit"
                class="px-4 py-2 bg-primary-600 text-white rounded-xl
                       font-medium text-sm hover:bg-primary-700 transition">
            Activer la double authentification
        </button>
    </form>

    @elseif (session('status') === 'two-factor-authentication-enabled'
             || request()->session()->has('auth.two_factor_secret'))
    {{-- QR Code à scanner --}}
    <div class="space-y-4">
        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200
                    rounded-xl">
            <p class="text-sm text-blue-800 dark:text-blue-200 font-medium mb-2">
                📱 Scannez ce QR code avec votre application d'authentification
            </p>
            <p class="text-xs text-blue-600 dark:text-blue-300">
                Applications recommandées : Google Authenticator, Authy, Microsoft Authenticator
            </p>
        </div>

        {{-- QR Code --}}
        <div class="flex justify-center p-4 bg-white rounded-xl border border-gray-200">
            {!! auth()->user()->twoFactorQrCodeSvg() !!}
        </div>

        {{-- Code de setup --}}
        @if ($setupKey = auth()->user()->two_factor_secret)
        <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <p class="text-xs text-gray-500 mb-1">Clé de configuration manuelle :</p>
            <code class="text-sm font-mono text-gray-700 dark:text-gray-300 break-all">
                {{ $setupKey }}
            </code>
        </div>
        @endif

        {{-- Confirmation --}}
        <form method="POST" action="{{ route('two-factor.confirm') }}">
            @csrf
            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700
                               dark:text-gray-300">
                    Code de confirmation
                </label>
                <input type="text"
                       name="code"
                       inputmode="numeric"
                       placeholder="123456"
                       autocomplete="one-time-code"
                       class="w-full border border-gray-300 dark:border-gray-600
                              rounded-xl px-4 py-3 text-center text-xl tracking-widest
                              focus:outline-none focus:ring-2 focus:ring-primary-400
                              dark:bg-gray-800 dark:text-white">
                @error('code')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <button type="submit"
                        class="w-full py-3 bg-primary-600 text-white rounded-xl
                               font-medium hover:bg-primary-700 transition">
                    Confirmer et activer
                </button>
            </div>
        </form>
    </div>

    @else
    {{-- 2FA activé --}}
    <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200
                dark:border-green-800 rounded-xl">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🔐</span>
            <div>
                <p class="font-medium text-green-800 dark:text-green-200">
                    Double authentification activée
                </p>
                <p class="text-sm text-green-600 dark:text-green-300 mt-0.5">
                    Votre compte est protégé par le 2FA.
                </p>
            </div>
        </div>
    </div>

    {{-- Codes de récupération --}}
    <div>
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Codes de récupération d'urgence
        </h4>
        <p class="text-xs text-gray-500 mb-3">
            Conservez ces codes dans un endroit sûr. Ils permettent
            d'accéder à votre compte si vous perdez votre téléphone.
        </p>
        <form method="POST" action="{{ route('two-factor.recovery-codes') }}">
            @csrf
            <button type="submit"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600
                           text-gray-700 dark:text-gray-300 rounded-lg text-sm
                           hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Régénérer les codes de récupération
            </button>
        </form>
    </div>

    {{-- Désactiver --}}
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <form method="POST" action="{{ route('two-factor.disable') }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                    onclick="return confirm('Désactiver la double authentification ?')"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl
                           text-sm font-medium hover:bg-red-700 transition">
                Désactiver le 2FA
            </button>
        </form>
    </div>
    @endif
</div>
```

### 3.3 — Vérifier le layout de la page settings

La page `/settings/two-factor` affiche le layout Studio alors que
l'admin se connecte. Vérifier la route et le controller :

```bash
# Quelle route gère /settings/two-factor ?
php artisan route:list | grep "settings/two-factor\|settings\.two"

# Quel layout est utilisé ?
grep -rn "extends\|layout\|component" \
  resources/views/settings/ --include="*.blade.php" 2>/dev/null | head -10
```

Si la page utilise un layout générique (pas spécifique à un rôle),
il faut s'assurer que le layout affiché correspond au rôle connecté.

---

## PHASE 4 — VALIDATION

```bash
# Clear cache
php artisan view:clear
php artisan config:clear
npm run build

# Tests à effectuer :
# 1. Admin connecté → /settings/two-factor → doit afficher layout admin
#    avec texte en français, 0 erreur $flux dans la console
# 2. Tatoueur connecté → /settings/two-factor → layout tattooer
# 3. Activer le 2FA → QR code visible → saisir code → confirmé ✅
# 4. Console nav → 0 erreur Alpine/$flux
```

## ⚠️ Contraintes
- Ne PAS installer Flux UI — utiliser Tailwind + Alpine natif
- Garder la logique Fortify intacte (routes, actions)
- Le layout doit changer selon le rôle de l'utilisateur connecté
- Rapport : erreur $flux résolue + page en français + layout correct
