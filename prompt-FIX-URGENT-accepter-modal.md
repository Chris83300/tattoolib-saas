# 🚨 FIX URGENT — Bouton "Accepter" ne réagit pas du tout
# Le clic ne déclenche rien : pas de modal, pas de réaction, sur les DEUX pages :
# - /tattooer/requests (liste)
# - /tattooer/requests/{id} (détail)

## DIAGNOSTIC — LIRE TOUT AVANT DE MODIFIER

```bash
echo "=== DIAGNOSTIC ACCEPT MODAL ==="

# 1. Le composant Livewire est-il inclus dans les vues ?
grep -n "accept-booking-modal\|AcceptBookingModal\|livewire.*accept" resources/views/tattooer/requests.blade.php | head -5
grep -n "accept-booking-modal\|AcceptBookingModal\|livewire.*accept" resources/views/tattooer/request-show.blade.php | head -5

# 2. Le bouton — quel événement dispatch-t-il ?
grep -n "open-accept\|dispatch.*accept\|Livewire.dispatch\|\$dispatch" resources/views/tattooer/requests.blade.php | head -10
grep -n "open-accept\|dispatch.*accept\|Livewire.dispatch\|\$dispatch" resources/views/tattooer/request-show.blade.php | head -10

# 3. Le composant Livewire — quel listener écoute-t-il ?
grep -n "listeners\|#\[On\|protected \$listeners\|on\(" app/Livewire/Tattooer/AcceptBookingModal.php | head -10
# ET la ligne mount/open/show
grep -n "function mount\|function open\|function show\|function setBooking\|bookingRequestId" app/Livewire/Tattooer/AcceptBookingModal.php | head -10

# 4. LIRE LE COMPOSANT EN ENTIER
cat app/Livewire/Tattooer/AcceptBookingModal.php

# 5. LIRE LA VUE DU COMPOSANT EN ENTIER
cat resources/views/livewire/tattooer/accept-booking-modal.blade.php

# 6. Vérifier le bouton dans request-show — est-ce du JS onclick ou du wire:click ?
grep -B 2 -A 5 "Accepter" resources/views/tattooer/request-show.blade.php | head -20
grep -B 2 -A 5 "Accepter" resources/views/tattooer/requests.blade.php | head -20

# 7. Erreurs PHP
tail -30 storage/logs/laravel.log

# 8. Vérifier que le composant Livewire est bien découvert par Laravel
php artisan livewire:discover 2>/dev/null
php artisan tinker --execute="echo class_exists('App\Livewire\Tattooer\AcceptBookingModal') ? 'CLASSE OK' : 'CLASSE INTROUVABLE';"

# 9. Layout utilisé — le layout inclut-il @livewireScripts ou @filamentScripts ?
grep -n "livewireScripts\|livewire_scripts\|@livewire\|wire:" resources/views/layouts/tattooer.blade.php 2>/dev/null | head -5
# Ou le layout utilisé par requests.blade.php
head -3 resources/views/tattooer/requests.blade.php

echo "=== FIN DIAGNOSTIC ==="
```

**MONTRE-MOI TOUS les résultats. Ne corrige RIEN avant d'avoir montré le diagnostic complet.**

---

## CAUSES PROBABLES (vérifier dans l'ordre)

### Cause 1 — Le nom de l'événement ne correspond pas

Le bouton fait :
```javascript
Livewire.dispatch('open-accept-modal', { bookingRequestId: 14 })
```

Mais le composant écoute peut-être un nom différent. Vérifier :
- Livewire 3 utilise `#[On('event-name')]` sur une méthode OU `protected $listeners`
- Le nom doit correspondre EXACTEMENT (avec les tirets)

```php
// ✅ Correct Livewire 3.7
#[On('open-accept-modal')]
public function openModal(int $bookingRequestId): void
{
    // ...
}

// ✅ Alternative
protected $listeners = ['open-accept-modal' => 'openModal'];
```

### Cause 2 — Le composant n'est PAS dans la page

Si `<livewire:tattooer.accept-booking-modal />` n'est pas dans la vue,
l'événement n'a aucun récepteur. Vérifier qu'il est bien inclus dans :
- `requests.blade.php` (la liste)
- `request-show.blade.php` (le détail)

Si absent dans l'une des deux → l'ajouter.

### Cause 3 — Le dispatch JS est bloqué par CSP

Si le bouton utilise un `onclick="Livewire.dispatch(...)"` inline,
la CSP peut le bloquer (script inline sans nonce).

**Fix** : Remplacer le onclick JS par du Alpine.js ou du wire:click :

```blade
{{-- ❌ AVANT — onclick JS inline (bloqué par CSP) --}}
<button onclick="Livewire.dispatch('open-accept-modal', { bookingRequestId: {{ $bookingRequest->id }} })">
    Accepter
</button>

{{-- ✅ APRÈS — Alpine.js avec $dispatch (pas bloqué par CSP) --}}
<button x-data @click="$dispatch('open-accept-modal', { bookingRequestId: {{ $bookingRequest->id }} })">
    Accepter
</button>

{{-- ✅ OU — Livewire dispatch via Alpine $wire (si dans un composant Livewire) --}}
<button @click="Livewire.dispatch('open-accept-modal', { bookingRequestId: {{ $bookingRequest->id }} })">
    Accepter
</button>
```

> ⚠️ ATTENTION : `Livewire.dispatch()` en JS fonctionne en Livewire 3, mais SEULEMENT
> si le script n'est pas bloqué par la CSP. Si le bouton est dans un `onclick=""`,
> la CSP bloque l'exécution.

### Cause 4 — Le modal est caché mais jamais montré

Le composant a peut-être une propriété `$show = false` mais le listener
ne la passe jamais à `true`. Vérifier :

```php
// La propriété qui contrôle l'affichage
public bool $show = false; // ou $isOpen, $showModal, etc.

// Le listener doit la passer à true
#[On('open-accept-modal')]
public function openModal(int $bookingRequestId): void
{
    $this->bookingRequestId = $bookingRequestId;
    $this->show = true; // ← CRITIQUE
}
```

### Cause 5 — Conflit avec le fix L2 (redirect)

Le prompt L2 a remplacé le dispatch par un redirect. Vérifier que
la méthode d'ouverture n'a pas été touchée (seule la méthode d'acceptation
après submit devait être modifiée, pas l'ouverture).

---

## FIX — APPLIQUER APRÈS DIAGNOSTIC

Selon la cause identifiée, appliquer le fix approprié.

**Si Cause 3 (CSP)** — C'est la plus probable vu que "rien ne se passe" :

Chercher TOUS les `onclick="Livewire.dispatch"` dans les vues tattooer et les remplacer :

```bash
grep -rn 'onclick="Livewire.dispatch\|onclick="livewire.dispatch\|onclick=.Livewire' resources/views/tattooer/ --include="*.blade.php" | head -10
```

Remplacer chaque occurrence par l'équivalent Alpine :

```blade
{{-- Pattern de remplacement --}}
{{-- ❌ onclick="Livewire.dispatch('event', { key: value })" --}}
{{-- ✅ x-data @click="$dispatch('event', { key: value })" --}}
```

**Vérification après fix** :

```bash
# Plus aucun onclick Livewire.dispatch
grep -c 'onclick="Livewire.dispatch' resources/views/tattooer/ -r --include="*.blade.php"
echo "onclick Livewire.dispatch (doit être 0)"

# Compilation OK
php artisan route:cache 2>&1 | head -3
php artisan view:clear

# Tester manuellement :
# 1. Ouvrir /tattooer/requests dans le navigateur
# 2. Ouvrir la console (F12)
# 3. Cliquer sur "Accepter"
# 4. La console ne doit PAS afficher d'erreur CSP
# 5. Le modal doit s'ouvrir
```

```bash
git add -A && git commit -m "fix(urgent): bouton Accepter — remplacer onclick Livewire.dispatch par Alpine \$dispatch (CSP compatible)"
```

## ⚠️ RÈGLES
1. **DIAGNOSTIC D'ABORD** — montrer TOUS les résultats avant de coder
2. **La cause la plus probable est la CSP** bloquant le onclick inline
3. **Ne pas toucher la logique d'acceptation** (submitAcceptance) — seulement le DÉCLENCHEMENT du modal
4. **Tester sur les DEUX pages** : /tattooer/requests ET /tattooer/requests/{id}
5. **Vérifier la console navigateur** après le fix — 0 erreur CSP
