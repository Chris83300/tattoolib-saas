# 🔧 PROMPT L — FIXES MINEURS BUNDLE
# Pour Claude Code — Studio settings, modals refresh, bottom_nav, chat doublon
# Commit après chaque fix

## CONTEXTE

6 bugs mineurs identifiés lors des tests. Tous indépendants, corrigés un par un.

Stack : Laravel 12, Livewire 3.7, Alpine.js, TailwindCSS v4.

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT PROMPT L ==="

# ── L1 : STUDIO SETTINGS — AFFICHAGE INVERSÉ ──
echo "--- L1: STUDIO SETTINGS ---"

# L1a. Vue settings studio
grep -n "direct.*artiste\|géré.*studio\|payment.*mode\|paiement.*mode\|studio_managed\|independent\|direct_artist\|managed_by_studio" resources/views/livewire/studio/settings.blade.php resources/views/studio/settings.blade.php 2>/dev/null | head -15

# L1b. Le composant Livewire settings studio
find app/Livewire -path "*Studio*" -name "*Settings*" | head -3
grep -n "payment_mode\|paiement\|direct_artist\|studio_managed\|independent" app/Livewire/Studio/Settings.php 2>/dev/null | head -10

# L1c. Le modèle Studio — champ payment mode
grep -n "payment_mode\|billing_mode\|managed\|independent\|direct" app/Models/Studio.php | head -10
grep -n "payment_mode\|billing_mode" app/Models/StudioArtist.php 2>/dev/null | head -5


# ── L2 : ACCEPT BOOKING MODAL — REFRESH AUTO ──
echo "--- L2: ACCEPT BOOKING ---"

# L2a. Le modal accept-booking (déjà fixé dans Prompt A mais revérifie)
grep -n "dispatch\|emit\|refresh\|reload\|booking-accepted" app/Livewire/Tattooer/AcceptBookingModal.php 2>/dev/null | head -10
# Ou si c'est ailleurs
find app/Livewire -name "*AcceptBooking*" | head -3

# L2b. La page request-show écoute l'event ?
grep -n "booking-accepted\|Livewire.on\|window.location.reload\|wire:poll\|\$refresh" resources/views/tattooer/request-show.blade.php 2>/dev/null | head -10


# ── L3 : CHAT MODAL RDV — REFRESH + DOUBLON ──
echo "--- L3: CHAT RDV ---"

# L3a. Modal RDV depuis le chat
find resources/views -name "*quick*booking*" -o -name "*rdv*modal*" -o -name "*appointment*modal*" | head -5
grep -rn "QuickBooking\|quick_booking\|rdv.*modal\|appointment.*modal" app/Livewire/ --include="*.php" -l | head -5

# L3b. Composant Livewire quick booking
find app/Livewire -name "*QuickBooking*" -o -name "*BookingQuick*" | head -3
grep -n "function\|dispatch\|emit\|refresh\|save\|store\|create" app/Livewire/Tattooer/BookingQuickCreate.php 2>/dev/null | head -15

# L3c. Doublons de RDV
grep -n "BookingRequest::create\|Appointment::create\|->save()" app/Livewire/Tattooer/BookingQuickCreate.php 2>/dev/null | head -10


# ── L4 : BOTTOM_NAV CLIENT ──
echo "--- L4: BOTTOM NAV CLIENT ---"

# L4a. Bottom nav client actuel
find resources/views -path "*client*" -name "*nav*" -o -path "*client*" -name "*bottom*" -o -path "*client*" -name "*mobile*" | head -5
grep -rn "bottom.nav\|nav.bottom\|mobile.nav" resources/views/layouts/client.blade.php 2>/dev/null | head -5

# L4b. Contenu du bottom nav
cat resources/views/client/partials/bottom-nav.blade.php 2>/dev/null | head -50
# OU
grep -A 50 "bottom-nav\|nav-bottom\|fixed.*bottom" resources/views/layouts/client.blade.php 2>/dev/null | head -60

# L4c. Bottom nav tattooer (pour comparer)
cat resources/views/tattooer/partials/bottom-nav.blade.php 2>/dev/null | head -30
# OU
grep -A 30 "bottom-nav\|fixed.*bottom" resources/views/layouts/tattooer.blade.php 2>/dev/null | head -40

# L4d. Bottom nav studio
cat resources/views/studio/partials/bottom-nav.blade.php 2>/dev/null | head -30


# ── L5 : STUDIO BOTTOM_NAV — LIEN FILAMENT ──
echo "--- L5: STUDIO NAV FILAMENT ---"

# L5a. Vérifier si le fix D6 a bien été appliqué dans le bottom_nav
grep -n "admin/studio\|filament\|Gestion avancée\|gestion" resources/views/studio/partials/bottom-nav.blade.php resources/views/layouts/studio.blade.php 2>/dev/null | head -5

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX L1 — STUDIO SETTINGS : AFFICHAGE MODE PAIEMENT INVERSÉ

### Problème
À l'inscription studio, quand on choisit "paiement géré par studio", la page settings affiche "artiste direct" et inversement.

### Diagnostic
C'est probablement une inversion de condition dans le Blade :

```blade
{{-- AVANT (inversé) --}}
@if ($studio->payment_mode === 'studio_managed')
    Artiste direct (indépendant)
@else
    Géré par le studio
@endif

{{-- APRÈS (correct) --}}
@if ($studio->payment_mode === 'studio_managed')
    Géré par le studio (paiement centralisé)
@else
    Artiste direct (indépendant)
@endif
```

OU c'est l'inverse — la valeur en base est inversée :

```bash
# Vérifier la valeur en base
php artisan tinker --execute="
  \$studio = \App\Models\Studio::first();
  echo 'payment_mode: ' . (\$studio->payment_mode ?? \$studio->billing_mode ?? 'ABSENT') . PHP_EOL;
"
```

Comparer avec ce qui a été choisi à l'inscription. Corriger soit la vue, soit la valeur à l'inscription.

```bash
git add -A && git commit -m "fix(L1): studio settings — affichage mode paiement corrigé (plus d'inversion)"
```

---

## FIX L2 — ACCEPT BOOKING MODAL : REFRESH (VÉRIFICATION)

### Contexte
Le fix A2 avait corrigé ce problème en supprimant `.self()` de `$this->dispatch('booking-accepted')`. Mais le problème semble persister.

### Diagnostic approfondi

```bash
# Vérifier que le fix A2 est bien en place
grep -n "dispatch.*booking-accepted\|booking.accepted" app/Livewire/Tattooer/AcceptBookingModal.php 2>/dev/null | head -5
grep -n "booking-accepted\|reload\|Livewire.on" resources/views/tattooer/request-show.blade.php 2>/dev/null | head -10
```

### Fix renforcé

Si le dispatch Livewire ne fonctionne toujours pas (peut arriver si la page parent n'est pas un composant Livewire), utiliser un event navigateur JavaScript directement :

```php
// Dans le composant AcceptBookingModal, après l'acceptation :
public function accept()
{
    // ... logique d'acceptation ...

    // Option 1 : Dispatch Livewire
    $this->dispatch('booking-accepted');
    
    // Option 2 (plus fiable) : Dispatch événement navigateur
    $this->js('window.dispatchEvent(new Event("booking-accepted"))');
    
    // Option 3 (le plus fiable) : Redirect direct
    return $this->redirect(
        route('tattooer.requests.show', $this->bookingRequest),
        navigate: false
    );
}
```

Et dans la vue request-show :
```blade
{{-- S'assurer que le listener est bien présent --}}
<div x-data x-on:booking-accepted.window="window.location.reload()">
    {{-- contenu de la page --}}
</div>

{{-- OU via Livewire --}}
@script
<script>
    Livewire.on('booking-accepted', () => {
        window.location.reload();
    });
</script>
@endscript
```

L'option 3 (redirect) est la plus fiable car elle ne dépend d'aucun event system.

```bash
git add -A && git commit -m "fix(L2): accept-booking-modal — redirect après acceptation (plus fiable que dispatch)"
```

---

## FIX L3 — CHAT MODAL RDV : REFRESH + SUPPRESSION DOUBLON

### Problème
1. Après avoir créé un RDV depuis le chat, pas de refresh automatique
2. Le RDV est créé en double

### Fix doublon

Le doublon est probablement causé par un double submit. Vérifier :

```bash
grep -n "function.*create\|function.*store\|function.*save\|function.*book" app/Livewire/Tattooer/BookingQuickCreate.php 2>/dev/null | head -10
```

**Fix anti-doublon** :
```php
// Dans le composant Livewire BookingQuickCreate
public bool $isSubmitting = false;

public function create() // ou store/save
{
    // Guard contre double submit
    if ($this->isSubmitting) return;
    $this->isSubmitting = true;

    try {
        // ... logique de création existante ...

        // Refresh après création
        $this->dispatch('booking-created');
        $this->js('window.dispatchEvent(new Event("booking-created"))');
        
        // Fermer le modal
        $this->dispatch('close-modal');
    } finally {
        $this->isSubmitting = false;
    }
}
```

Dans la vue du modal :
```blade
{{-- Désactiver le bouton pendant le submit --}}
<button wire:click="create" wire:loading.attr="disabled" :disabled="$wire.isSubmitting"
    class="... disabled:opacity-50">
    <span wire:loading.remove wire:target="create">Créer le RDV</span>
    <span wire:loading wire:target="create">Création...</span>
</button>
```

**Fix refresh** — Dans le chat ou la page qui contient le modal :
```blade
<div x-on:booking-created.window="window.location.reload()">
    {{-- chat content --}}
</div>
```

```bash
git add -A && git commit -m "fix(L3): chat modal RDV — anti-doublon + refresh auto après création"
```

---

## FIX L4 — BOTTOM_NAV CLIENT : NETTOYAGE

### Problème
Le bottom_nav client affiche trop d'items mélangés :
> Accueil, Explorer, RDV, Messages, Profil, Dashboard, Planning, Demandes, Portfolio, Chat, Dashboard, Artistes, Planning, Stats, Plus

C'est un mélange de nav client + nav tattooer + nav studio.

### Fix

Le bottom_nav client doit avoir MAXIMUM 5 items :

```blade
{{-- resources/views/client/partials/bottom-nav.blade.php (ou dans le layout client) --}}
<nav class="fixed bottom-0 inset-x-0 bg-gris-fonde border-t border-titane/10 z-50 sm:hidden">
    <div class="flex items-center justify-around py-2">
        {{-- 1. Accueil --}}
        <a href="{{ route('client.dashboard') }}"
            class="{{ request()->routeIs('client.dashboard') ? 'text-beige-peau' : 'text-titane' }} flex flex-col items-center gap-0.5 text-[10px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Accueil
        </a>

        {{-- 2. Explorer (marketplace) --}}
        <a href="{{ route('marketplace.index') }}"
            class="{{ request()->routeIs('marketplace.*') ? 'text-beige-peau' : 'text-titane' }} flex flex-col items-center gap-0.5 text-[10px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Explorer
        </a>

        {{-- 3. Mes RDV --}}
        <a href="{{ route('client.bookings') }}"
            class="{{ request()->routeIs('client.bookings*') ? 'text-beige-peau' : 'text-titane' }} flex flex-col items-center gap-0.5 text-[10px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Mes RDV
        </a>

        {{-- 4. Messages --}}
        <a href="{{ route('client.messages') }}"
            class="{{ request()->routeIs('client.messages*') ? 'text-beige-peau' : 'text-titane' }} flex flex-col items-center gap-0.5 text-[10px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Messages
        </a>

        {{-- 5. Profil --}}
        <a href="{{ route('client.profile') }}"
            class="{{ request()->routeIs('client.profile*') || request()->routeIs('client.settings*') ? 'text-beige-peau' : 'text-titane' }} flex flex-col items-center gap-0.5 text-[10px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil
        </a>
    </div>
</nav>
```

IMPORTANT :
- Vérifier les noms de routes exacts en Phase 0 (`client.bookings`, `client.messages`, `client.profile`)
- S'il y a un badge "messages non lus", le conserver
- Adapter les routes si elles sont nommées différemment

**Supprimer les items qui ne sont PAS pour le client** (Dashboard artiste, Planning, Portfolio, Demandes, Stats, Artistes — tout ça c'est artiste/studio, PAS client).

```bash
git add -A && git commit -m "fix(L4): bottom_nav client nettoyé — 5 items max (accueil, explorer, RDV, messages, profil)"
```

---

## FIX L5 — STUDIO BOTTOM_NAV : LIEN FILAMENT (VÉRIFICATION)

```bash
grep -n "admin/studio\|filament\|gestion" resources/views/studio/partials/bottom-nav.blade.php resources/views/layouts/studio.blade.php 2>/dev/null | head -5
```

Si le lien est absent malgré le fix D6, l'ajouter dans le menu "Plus" du bottom_nav ou directement :

```blade
{{-- Dans le bottom_nav studio --}}
<a href="/admin/studio" target="_blank"
    class="text-titane flex flex-col items-center gap-0.5 text-[10px]">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    Gestion
</a>
```

```bash
git add -A && git commit -m "fix(L5): bottom_nav studio — lien Filament vérifié"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PROMPT L ==="

# V1. Settings studio
grep -c "payment_mode\|billing_mode\|studio_managed\|direct" resources/views/studio/settings.blade.php resources/views/livewire/studio/settings.blade.php 2>/dev/null
echo "Mode paiement dans settings (doit être > 0)"

# V2. Accept booking refresh
grep -c "redirect\|reload\|booking-accepted" app/Livewire/Tattooer/AcceptBookingModal.php 2>/dev/null
echo "Refresh après accept (doit être > 0)"

# V3. Chat doublon
grep -c "isSubmitting\|submitting\|loading.attr" app/Livewire/Tattooer/BookingQuickCreate.php 2>/dev/null
echo "Anti-doublon chat (doit être > 0)"

# V4. Bottom nav client
grep -c "Explorer\|Mes RDV\|Messages\|Profil" resources/views/client/partials/bottom-nav.blade.php resources/views/layouts/client.blade.php 2>/dev/null
echo "Items bottom nav client (doit être ~5)"

# V5. Compilation
php artisan route:clear && php artisan view:clear
echo "OK"

echo "=== PROMPT L TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT**
2. **Settings inversé** : comparer la valeur DB avec l'affichage, corriger le bon côté
3. **Accept booking** : le redirect est le plus fiable (pas de dépendance event)
4. **Chat doublon** : `isSubmitting` flag + `wire:loading.attr="disabled"` 
5. **Bottom nav client = 5 items MAX** : Accueil, Explorer, RDV, Messages, Profil
6. **Ne pas mélanger** les navs client / tattooer / studio
7. **Commit après chaque fix** (5 commits)
