# ✅ FIX URGENT — Badges "Solde payé" + masquer boutons paiement
# Pour Claude Code — Ink&Pik SaaS
# Commit après chaque phase

## CONTEXTE

Le paiement du solde Stripe fonctionne mais les vues client ne reflètent pas le statut
"payé". Problèmes constatés :

| Page | Problème |
|------|----------|
| `/client/booking-requests/5` (détail) | Bouton "Payer le solde" encore visible alors que `balance_paid_at` est rempli |
| `/client/chat/5` | Bouton "Payer le solde" encore visible dans le chat |
| `/client/booking-requests` (liste) | Pas d'indication que le solde a été réglé |
| `/client/messages` (liste) | Pas d'indication du statut paiement |

### Règle :
- Si `balance_paid_at` est NOT NULL → masquer TOUT bouton de paiement + afficher badge "✅ Solde payé"
- Si `balance_requested_at` NOT NULL mais `balance_paid_at` NULL → afficher bouton "Payer le solde"
- Si `deposit_paid_at` NOT NULL mais `balance_requested_at` NULL → badge "Acompte payé — En attente du tarif final"

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT BADGES PAIEMENT ==="

# 0A. Vérifier le booking #5 — état réel
php artisan tinker --execute="
  \$br = \App\Models\BookingRequest::find(5);
  echo 'status: ' . \$br->status . PHP_EOL;
  echo 'deposit_paid_at: ' . (\$br->deposit_paid_at ?? 'NULL') . PHP_EOL;
  echo 'balance_requested_at: ' . (\$br->balance_requested_at ?? 'NULL') . PHP_EOL;
  echo 'balance_paid_at: ' . (\$br->balance_paid_at ?? 'NULL') . PHP_EOL;
  echo 'final_price: ' . (\$br->final_price ?? 'NULL') . PHP_EOL;
  echo 'total_deposit_amount: ' . (\$br->total_deposit_amount ?? 'NULL') . PHP_EOL;
"

# 0B. Vue détail booking client
find resources/views/client -name "*booking*request*show*" -o -name "*booking*show*" | head -5
# Lire la vue
cat resources/views/client/booking-request-show.blade.php 2>/dev/null || \
  cat resources/views/client/booking-requests-show.blade.php 2>/dev/null || \
  find resources/views/client -name "*show*" -exec echo {} \;

# 0C. Chat client — comment le bouton payer est rendu
grep -n "balance\|solde\|payer\|Payer\|payment_url\|balance_request\|balance_paid" resources/views/client/chat.blade.php 2>/dev/null | head -15
# Ou chercher le composant Livewire chat
find resources/views/livewire/client -name "*chat*" | head -3
grep -n "balance\|solde\|payer\|Payer\|metadata\|balance_paid" resources/views/livewire/client/chat.blade.php 2>/dev/null | head -15

# 0D. Liste booking-requests client
cat resources/views/client/booking-requests.blade.php 2>/dev/null | head -100
# Ou composant Livewire
find resources/views -path "*client*" -name "*booking*request*" | head -5
grep -n "balance\|solde\|deposit\|paid\|badge" resources/views/client/booking-requests.blade.php 2>/dev/null | head -15

# 0E. Liste messages client
find resources/views/client -name "*message*" | head -5
cat resources/views/client/messages.blade.php 2>/dev/null | head -80
grep -n "balance\|solde\|paid\|badge\|status" resources/views/client/messages.blade.php 2>/dev/null | head -15

# 0F. Composant Livewire chat si utilisé
find app/Livewire/Client -name "*Chat*" -o -name "*Message*" | head -5
grep -n "balance_paid\|bookingRequest\|booking" app/Livewire/Client/Chat.php 2>/dev/null | head -10

# 0G. Comment le chat rend les messages (template messages)
grep -rn "metadata\|balance_request\|type.*balance\|payment_url" resources/views/ --include="*.blade.php" | grep -i "client\|chat\|message" | head -10

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## PHASE 1 — CRÉER UN PARTIAL BADGE PAIEMENT RÉUTILISABLE

Créer un composant Blade réutilisable pour les badges de statut paiement,
utilisable dans toutes les vues (liste, détail, chat).

```blade
{{-- resources/views/components/ui/payment-status-badge.blade.php --}}
@props(['booking'])

@php
    $depositPaid = $booking->deposit_paid_at !== null;
    $balanceRequested = $booking->balance_requested_at !== null;
    $balancePaid = $booking->balance_paid_at !== null;
    $finalPrice = $booking->final_price ?? $booking->total_price ?? 0;
    $depositAmount = $booking->total_deposit_amount ?? 0;
    $remaining = max(0, $finalPrice - $depositAmount);
@endphp

@if ($balancePaid)
    {{-- TOUT payé --}}
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-vert-succes/15 text-vert-succes rounded-lg text-xs font-semibold">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Solde payé
    </span>
@elseif ($balanceRequested)
    {{-- Solde demandé mais pas payé --}}
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-orange-terre-cuite/15 text-orange-terre-cuite rounded-lg text-xs font-semibold">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Solde à payer ({{ number_format($remaining, 2, ',', ' ') }} €)
    </span>
@elseif ($depositPaid)
    {{-- Acompte payé, en attente tarif final --}}
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-beige-peau/15 text-beige-peau rounded-lg text-xs font-semibold">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Acompte payé ({{ number_format($depositAmount, 2, ',', ' ') }} €)
    </span>
@endif
```

```bash
git add -A && git commit -m "feat(ui): composant Blade payment-status-badge — badge réutilisable acompte/solde/payé"
```

---

## PHASE 2 — VUE DÉTAIL BOOKING CLIENT

Lire la vue détail booking client, puis :
1. **Masquer** le bouton "Payer le solde" si `balance_paid_at` est rempli
2. **Afficher** le badge composant à la place
3. **Afficher** un récap du paiement complet si tout est payé

```bash
# Trouver et lire la vue
find resources/views/client -name "*booking*request*show*" -o -name "*booking*show*" -o -name "*request-show*" | head -5
```

### Modifications dans la vue détail :

Chercher le bloc qui affiche le bouton de paiement du solde et l'entourer d'une condition :

```blade
{{-- REMPLACER le bloc paiement solde existant par : --}}

{{-- Badge statut paiement --}}
<x-ui.payment-status-badge :booking="$bookingRequest" />

@if ($bookingRequest->balance_paid_at)
    {{-- Solde payé — afficher récap complet --}}
    <div class="p-4 bg-vert-succes/10 border border-vert-succes/20 rounded-xl mt-4">
        <h4 class="text-sm font-semibold text-vert-succes mb-3">✅ Paiement complet</h4>
        <div class="space-y-1.5 text-sm">
            <div class="flex justify-between text-ivoire-text/60">
                <span>Prix définitif</span>
                <span class="text-titane font-medium">{{ number_format($bookingRequest->final_price ?? $bookingRequest->total_price, 2, ',', ' ') }} €</span>
            </div>
            <div class="flex justify-between text-ivoire-text/60">
                <span>Acompte versé</span>
                <span class="text-vert-succes">{{ number_format($bookingRequest->total_deposit_amount ?? 0, 2, ',', ' ') }} €</span>
            </div>
            <div class="flex justify-between text-ivoire-text/60">
                <span>Solde payé le {{ $bookingRequest->balance_paid_at->format('d/m/Y') }}</span>
                <span class="text-vert-succes">{{ number_format(($bookingRequest->final_price ?? $bookingRequest->total_price) - ($bookingRequest->total_deposit_amount ?? 0), 2, ',', ' ') }} €</span>
            </div>
        </div>
    </div>

@elseif ($bookingRequest->balance_requested_at && !$bookingRequest->balance_paid_at)
    {{-- Solde demandé — bouton payer --}}
    <div class="p-4 bg-beige-peau/10 border border-beige-peau/20 rounded-xl mt-4">
        <h4 class="text-sm font-semibold text-beige-peau mb-3">💰 Paiement du solde demandé</h4>
        <div class="space-y-1.5 text-sm mb-4">
            <div class="flex justify-between text-ivoire-text/60">
                <span>Prix définitif</span>
                <span class="text-titane font-medium">{{ number_format($bookingRequest->final_price ?? $bookingRequest->total_price, 2, ',', ' ') }} €</span>
            </div>
            @if ($bookingRequest->deposit_paid_at)
                <div class="flex justify-between text-ivoire-text/60">
                    <span>Acompte versé ✅</span>
                    <span class="text-vert-succes">- {{ number_format($bookingRequest->total_deposit_amount ?? 0, 2, ',', ' ') }} €</span>
                </div>
            @endif
            <div class="flex justify-between border-t border-titane/10 pt-1.5">
                <span class="font-semibold text-ivoire-text">Reste à payer</span>
                <span class="text-xl font-bold text-beige-peau">{{ number_format($bookingRequest->remaining_balance, 2, ',', ' ') }} €</span>
            </div>
        </div>
        <a href="{{ route('client.balance.show', $bookingRequest) }}"
           class="w-full inline-flex items-center justify-center gap-2 px-4 py-3
                  bg-beige-peau text-noir-profond rounded-xl font-semibold
                  hover:bg-beige-peau/90 transition">
            💳 Payer le solde ({{ number_format($bookingRequest->remaining_balance, 2, ',', ' ') }} €)
        </a>
    </div>
@endif
```

```bash
git add -A && git commit -m "fix(client): vue booking detail — badge solde payé + masquer bouton si déjà réglé"
```

---

## PHASE 3 — CHAT CLIENT — MASQUER BOUTON DANS LES MESSAGES

Le message de demande de solde dans le chat a un bouton "Payer" qui doit
afficher "✅ Solde payé" si `balance_paid_at` est rempli.

```bash
# Trouver où le rendu du message balance_request est fait
grep -rn "balance_request\|payment_url\|Payer le solde\|Payer.*solde" resources/views/ --include="*.blade.php" | head -10
```

Dans la vue chat client, trouver le bloc qui rend les messages de type `balance_request`
et ajouter la condition sur `balance_paid_at` :

Le composant chat a besoin d'accéder au bookingRequest pour vérifier `balance_paid_at`.
Deux approches :

### Approche A — Passer le bookingRequest au chat

Si le chat a déjà accès au bookingRequest (via propriété Livewire ou variable Blade) :

```blade
{{-- Dans le rendu du message spécial balance_request --}}
@php
    $meta = is_string($message->metadata) ? json_decode($message->metadata, true) : ($message->metadata ?? []);
    $isBalanceMessage = ($meta['type'] ?? null) === 'balance_request';
    // Récupérer le booking via l'ID dans les metadata
    $relatedBooking = $isBalanceMessage ? \App\Models\BookingRequest::find($meta['booking_request_id'] ?? null) : null;
    $isBalancePaid = $relatedBooking?->balance_paid_at !== null;
@endphp

@if ($isBalanceMessage)
    <div class="p-4 bg-beige-peau/10 border border-beige-peau/20 rounded-xl my-2">
        <p class="text-sm font-semibold text-beige-peau mb-2">💰 Demande de paiement du solde</p>

        <div class="space-y-1 text-sm text-ivoire-text/70">
            <div class="flex justify-between">
                <span>Prix définitif</span>
                <span class="text-titane font-medium">{{ number_format($meta['final_price'] ?? 0, 2, ',', ' ') }} €</span>
            </div>
            <div class="flex justify-between">
                <span>Acompte versé</span>
                <span class="text-vert-succes">- {{ number_format($meta['deposit_amount'] ?? 0, 2, ',', ' ') }} €</span>
            </div>
            <div class="flex justify-between border-t border-titane/10 pt-1">
                <span class="font-semibold text-ivoire-text">Reste à payer</span>
                <span class="text-lg font-bold text-beige-peau">{{ number_format($meta['remaining'] ?? 0, 2, ',', ' ') }} €</span>
            </div>
        </div>

        @if ($isBalancePaid)
            {{-- ✅ PAYÉ --}}
            <div class="mt-3 p-2.5 bg-vert-succes/15 rounded-xl text-center">
                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-vert-succes">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Solde payé le {{ $relatedBooking->balance_paid_at->format('d/m/Y') }}
                </span>
            </div>
        @else
            {{-- Bouton payer --}}
            <a href="{{ $meta['payment_url'] ?? '#' }}"
               class="mt-3 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5
                      bg-beige-peau text-noir-profond rounded-xl text-sm font-semibold
                      hover:bg-beige-peau/90 transition">
                💳 Payer le solde ({{ number_format($meta['remaining'] ?? 0, 2, ',', ' ') }} €)
            </a>
        @endif
    </div>
@endif
```

### Approche B — Si le chat ne peut pas accéder au bookingRequest

Ajouter une propriété computed dans le composant Livewire chat :

```php
// Dans le composant Livewire Chat client
public function isBalancePaid(int $bookingRequestId): bool
{
    return \App\Models\BookingRequest::where('id', $bookingRequestId)
        ->whereNotNull('balance_paid_at')
        ->exists();
}
```

> ⚠️ ADAPTER selon la structure réelle du composant chat.
> Lire le composant EN ENTIER en Phase 0 pour choisir la bonne approche.

```bash
git add -A && git commit -m "fix(client): chat — masquer bouton payer si solde déjà réglé + badge ✅ Solde payé"
```

---

## PHASE 4 — LISTE BOOKING-REQUESTS CLIENT

Ajouter le badge de statut paiement dans la liste des demandes client.

```bash
# Lire la vue
cat resources/views/client/booking-requests.blade.php 2>/dev/null | head -100
```

Dans la boucle `@foreach` des bookings, ajouter le badge :

```blade
{{-- Dans chaque card/ligne de booking dans la liste --}}
{{-- À côté du statut existant du booking --}}
<x-ui.payment-status-badge :booking="$booking" />
```

**Vérifier que le controller passe les bookings avec les bonnes colonnes** :

```bash
grep -n "booking_requests\|BookingRequest\|function.*bookingRequests\|function.*index" app/Http/Controllers/Client/ -r --include="*.php" | head -10
```

S'assurer que les colonnes `balance_paid_at`, `balance_requested_at`, `deposit_paid_at`
sont bien chargées (elles le sont par défaut si on fait un `BookingRequest::where(...)->get()`).

```bash
git add -A && git commit -m "feat(client): badge payment-status dans la liste booking-requests"
```

---

## PHASE 5 — LISTE MESSAGES CLIENT

Ajouter une indication du statut paiement dans la liste des conversations/messages.

```bash
cat resources/views/client/messages.blade.php 2>/dev/null | head -100
```

La liste des messages est probablement une liste de conversations.
Chaque conversation est liée à un `BookingRequest` (via `booking_request_id`).

Dans la boucle des conversations :

```blade
{{-- Pour chaque conversation dans la liste messages --}}
@if ($conversation->bookingRequest)
    <x-ui.payment-status-badge :booking="$conversation->bookingRequest" />
@endif
```

**Vérifier que la relation est eager loaded** pour éviter les N+1 :

```bash
grep -n "bookingRequest\|booking_request\|with(" app/Http/Controllers/Client/ClientMessageController.php 2>/dev/null | head -10
# Ou le composant Livewire
find app/Livewire/Client -name "*Message*" | head -3
grep -n "bookingRequest\|with(" app/Livewire/Client/Messages.php 2>/dev/null | head -10
```

Si pas eager loaded, ajouter dans le controller/composant :

```php
// Dans le controller ou composant qui charge les conversations
$conversations = Conversation::where(...)
    ->with(['bookingRequest', 'messages' => fn ($q) => $q->latest()->limit(1)])
    ->get();
```

```bash
git add -A && git commit -m "feat(client): badge payment-status dans la liste messages/conversations"
```

---

## PHASE 6 — CÔTÉ ARTISTE : BADGES AUSSI

Les mêmes badges doivent apparaître côté artiste pour qu'il voie le statut des paiements.

```bash
# Vues artiste à vérifier
grep -n "balance\|solde\|deposit\|paid" resources/views/tattooer/request-show.blade.php | head -10
grep -n "balance\|solde\|deposit\|paid" resources/views/tattooer/requests.blade.php | head -10
```

Ajouter dans `request-show.blade.php` (côté artiste) et `requests.blade.php` (liste) :

```blade
{{-- Côté artiste — badge statut paiement --}}
<x-ui.payment-status-badge :booking="$bookingRequest" />

{{-- Côté artiste — si solde payé, afficher récap --}}
@if ($bookingRequest->balance_paid_at)
    <div class="p-3 bg-vert-succes/10 border border-vert-succes/20 rounded-xl">
        <p class="text-sm font-medium text-vert-succes">
            ✅ Solde payé par le client le {{ $bookingRequest->balance_paid_at->format('d/m/Y') }}
        </p>
    </div>
@endif
```

Et masquer le bouton "Demander le solde" si déjà payé :

```blade
{{-- Modifier la condition du bouton Demander le solde --}}
@if ($bookingRequest->deposit_paid_at
    && !$bookingRequest->balance_paid_at  {{-- ← CETTE CONDITION EST CLÉ --}}
    && in_array($bookingRequest->status, ['confirmed', 'deposit_paid', 'design_sent', 'completed']))
    {{-- Bouton Demander le solde --}}
@endif
```

```bash
git add -A && git commit -m "feat(artiste): badges payment-status dans request-show + requests liste + masquer bouton si payé"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION BADGES PAIEMENT ==="

# V1. Composant badge existe
ls resources/views/components/ui/payment-status-badge.blade.php && echo "Badge OK"

# V2. Badge utilisé dans les vues
grep -rn "payment-status-badge" resources/views/ --include="*.blade.php" | head -10
echo "Vues utilisant le badge"

# V3. Condition balance_paid_at dans le chat
grep -c "balance_paid_at\|isBalancePaid" resources/views/client/chat.blade.php resources/views/livewire/client/chat.blade.php 2>/dev/null
echo "Condition payé dans chat (doit être > 0)"

# V4. Condition dans detail booking
grep -c "balance_paid_at" resources/views/client/booking-request-show.blade.php resources/views/client/booking-requests-show.blade.php 2>/dev/null
echo "Condition payé dans detail (doit être > 0)"

# V5. Vérifier le booking #5
php artisan tinker --execute="
  \$br = \App\Models\BookingRequest::find(5);
  echo 'balance_paid_at: ' . (\$br->balance_paid_at ?? 'NULL') . PHP_EOL;
  echo 'Le badge devrait afficher: ' . (\$br->balance_paid_at ? 'Solde payé ✅' : 'Payer le solde') . PHP_EOL;
"

# V6. Compilation
php artisan view:clear

echo "=== BADGES TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — lire les vues existantes en entier avant de modifier
2. **Composant `<x-ui.payment-status-badge>`** réutilisable partout — NE PAS dupliquer la logique
3. **`balance_paid_at` NOT NULL** = tout bouton "Payer" disparaît, remplacé par badge vert
4. **Eager loading** — vérifier que `bookingRequest` est chargé dans les listes pour éviter N+1
5. **Côté artiste ET client** — les badges sont visibles des deux côtés
6. **Chat** — le message `balance_request` devient dynamique (bouton OU badge selon l'état)
7. **Ne PAS modifier la logique de paiement** — seulement l'affichage
8. **Commit après chaque phase** (6 commits)
