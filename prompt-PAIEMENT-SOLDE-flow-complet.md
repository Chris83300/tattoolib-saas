# 💰 PROMPT PAIEMENT SOLDE — Flow complet artiste → client
# Pour Claude Code — Ink&Pik SaaS
# Commit après chaque phase — 6 phases

## CONTEXTE

Le flow de paiement du solde est partiellement implémenté (BalancePaymentController existe)
mais les éléments UI ne sont pas en place ou non fonctionnels.

### Flow attendu (complet) :

```
1. ARTISTE clique "Demander le solde" (depuis request-show OU depuis le chat)
   → Modal s'ouvre avec :
     - Input : prix définitif (€) pré-rempli avec le prix estimé du booking
     - Affichage : acompte déjà payé (€)
     - Affichage : reste à payer = prix définitif - acompte (calculé en temps réel)
     - Bouton "Envoyer la demande de solde"

2. ARTISTE valide la modal
   → Le prix définitif est sauvegardé sur le BookingRequest
   → Un message est envoyé automatiquement dans le chat :
     "Le prix définitif de votre prestation est de XX €.
      Acompte versé : XX €. Reste à payer : XX €.
      [Bouton: Payer le solde]"
   → Le statut passe à "balance_requested" ou reste en "confirmed"

3. CLIENT voit le message dans le chat + un bouton "Payer le solde"
   → Le bouton redirige vers la page de paiement Stripe (BalancePaymentController)
   → Le bouton est aussi visible sur la page de la demande (côté client)

4. CLIENT paie via Stripe → solde encaissé
```

### Ce qui existe déjà (à vérifier en Phase 0) :
- `BalancePaymentController` — gère le paiement Stripe du solde
- `DepositController` — gère le paiement de l'acompte (déjà fonctionnel)
- `BookingRequest` — colonnes `total_price`, `total_deposit_amount`, `deposit_paid_at`, `balance_paid_at`
- `Conversation` / `Message` — chat bidirectionnel
- `BalancePaidNotification` — notification existante
- `DepositRequestedNotification` / `DepositPaidNotification` — pour référence pattern

Stack : Laravel 12, Livewire 3.7, Alpine.js, Stripe Connect, TailwindCSS v4.

---

## PHASE 0 — AUDIT COMPLET

```bash
echo "=== AUDIT PAIEMENT SOLDE ==="

# ── BACKEND ──
echo "--- BACKEND ---"

# 0A. BalancePaymentController
cat app/Http/Controllers/BalancePaymentController.php 2>/dev/null
echo "==="

# 0B. Routes balance/solde
php artisan route:list 2>&1 | grep -i "balance\|solde\|paiement.*solde" | head -10

# 0C. BookingRequest — colonnes financières
php artisan tinker --execute="
  \$br = \App\Models\BookingRequest::first();
  \$cols = ['total_price', 'final_price', 'total_deposit_amount', 'deposit_paid_at', 
            'balance_amount', 'balance_paid_at', 'balance_requested_at',
            'remaining_amount', 'status'];
  foreach (\$cols as \$c) {
    echo \$c . ': ' . (Schema::hasColumn('booking_requests', \$c) ? 'EXISTS' : 'ABSENT') . PHP_EOL;
  }
"

# 0D. Statuts BookingRequest liés au solde
grep -n "balance\|solde\|BALANCE\|final_price\|remaining" app/Models/BookingRequest.php | head -15
grep -n "balance\|BALANCE\|solde" app/Enums/BookingRequestStatus.php 2>/dev/null | head -10

# 0E. Notifications solde
find app/Notifications -name "*Balance*" -o -name "*Solde*" | head -5
ls app/Notifications/BalancePaidNotification.php 2>/dev/null
ls app/Notifications/DepositRequestedNotification.php 2>/dev/null

# ── LIVEWIRE / MODALS ──
echo "--- LIVEWIRE ---"

# 0F. Composant Livewire balance/solde existant ?
find app/Livewire -name "*Balance*" -o -name "*Solde*" -o -name "*FinalPrice*" -o -name "*RequestPayment*" | head -5

# 0G. Modal existante dans les vues
find resources/views -name "*balance*" -o -name "*solde*" -o -name "*final*price*" | head -5
grep -rn "balance\|solde\|final.price\|prix.final\|reste.*payer" resources/views/tattooer/request-show.blade.php 2>/dev/null | head -10
grep -rn "balance\|solde\|payer.*solde\|reste.*payer" resources/views/livewire/ --include="*.blade.php" | head -10

# 0H. RequestDeposit composant (pour référence pattern)
cat app/Livewire/RequestDeposit.php 2>/dev/null | head -40

# ── CHAT ──
echo "--- CHAT ---"

# 0I. Comment les messages sont envoyés dans le chat
grep -n "function send\|function create.*Message\|Message::create" app/Livewire/ProjectChat.php 2>/dev/null | head -10
grep -n "function send\|Message::create" app/Livewire/Tattooer/BookingChat.php 2>/dev/null | head -10
# Chercher le composant chat booking
find app/Livewire -name "*Chat*" -o -name "*chat*" | head -10

# 0J. Structure d'un message (colonnes)
php artisan tinker --execute="
  echo implode(', ', Schema::getColumnListing('messages')) . PHP_EOL;
"

# 0K. Types de messages spéciaux (system messages, payment links)
grep -rn "type.*system\|type.*payment\|is_system\|metadata\|action_type" app/Models/Message.php | head -10

# ── CLIENT SIDE ──
echo "--- CLIENT ---"

# 0L. Vue client booking-request (détail)
find resources/views/client -name "*request*" -o -name "*booking*" | head -5
grep -n "balance\|solde\|payer\|reste.*payer\|Payer" resources/views/client/booking-request-show.blade.php 2>/dev/null | head -10

# 0M. Chat client
grep -n "balance\|solde\|payer" resources/views/client/chat.blade.php 2>/dev/null | head -5

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## PHASE 1 — MIGRATION (si colonnes manquantes)

Vérifier en Phase 0 si `final_price` et `balance_requested_at` existent.
Si absent :

```bash
php artisan make:migration add_balance_payment_fields_to_booking_requests
```

```php
public function up(): void
{
    Schema::table('booking_requests', function (Blueprint $table) {
        if (!Schema::hasColumn('booking_requests', 'final_price')) {
            $table->decimal('final_price', 10, 2)->nullable()->after('total_price');
        }
        if (!Schema::hasColumn('booking_requests', 'balance_requested_at')) {
            $table->timestamp('balance_requested_at')->nullable()->after('deposit_paid_at');
        }
    });
}
```

Ajouter au fillable du modèle BookingRequest :

```php
'final_price',
'balance_requested_at',
```

Et les casts :

```php
'final_price' => 'decimal:2',
'balance_requested_at' => 'datetime',
```

Ajouter un helper sur BookingRequest :

```php
/**
 * Montant restant à payer (prix final - acompte).
 */
public function getRemainingBalanceAttribute(): float
{
    $finalPrice = $this->final_price ?? $this->total_price ?? 0;
    $depositPaid = $this->deposit_paid_at ? ($this->total_deposit_amount ?? 0) : 0;

    return max(0, round((float) $finalPrice - (float) $depositPaid, 2));
}

/**
 * Le solde a-t-il été demandé ?
 */
public function isBalanceRequested(): bool
{
    return $this->balance_requested_at !== null && $this->balance_paid_at === null;
}

/**
 * Le solde est-il payé ?
 */
public function isBalancePaid(): bool
{
    return $this->balance_paid_at !== null;
}
```

```bash
php artisan migrate

git add -A && git commit -m "feat(balance): migration final_price + balance_requested_at + helpers remainingBalance"
```

---

## PHASE 2 — COMPOSANT LIVEWIRE : MODAL DEMANDE DE SOLDE (ARTISTE)

```php
// app/Livewire/Tattooer/RequestBalancePayment.php
namespace App\Livewire\Tattooer;

use App\Models\BookingRequest;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class RequestBalancePayment extends Component
{
    public ?int $bookingRequestId = null;
    public ?BookingRequest $bookingRequest = null;
    public bool $show = false;

    // Champs du formulaire
    public ?string $finalPrice = null;

    // Données affichées
    public float $depositAmount = 0;
    public float $remainingBalance = 0;
    public float $estimatedPrice = 0;

    protected $rules = [
        'finalPrice' => 'required|numeric|min:1|max:50000',
    ];

    protected $messages = [
        'finalPrice.required' => 'Le prix définitif est obligatoire.',
        'finalPrice.numeric' => 'Le prix doit être un nombre.',
        'finalPrice.min' => 'Le prix minimum est 1 €.',
    ];

    #[On('open-balance-modal')]
    public function openModal(int $bookingRequestId): void
    {
        $this->bookingRequestId = $bookingRequestId;
        $this->bookingRequest = BookingRequest::with(['client.user', 'bookable.user', 'conversation'])
            ->findOrFail($bookingRequestId);

        // Pré-remplir avec le prix estimé
        $this->estimatedPrice = (float) ($this->bookingRequest->total_price ?? 0);
        $this->finalPrice = $this->estimatedPrice > 0 ? number_format($this->estimatedPrice, 2, '.', '') : null;
        $this->depositAmount = (float) ($this->bookingRequest->deposit_paid_at
            ? ($this->bookingRequest->total_deposit_amount ?? 0)
            : 0);

        $this->calculateRemaining();
        $this->show = true;
    }

    public function updatedFinalPrice(): void
    {
        $this->calculateRemaining();
    }

    protected function calculateRemaining(): void
    {
        $price = (float) ($this->finalPrice ?? 0);
        $this->remainingBalance = max(0, round($price - $this->depositAmount, 2));
    }

    public function submitBalanceRequest(): void
    {
        $this->validate();

        $artisan = auth()->user()->artisan();
        abort_unless(
            $this->bookingRequest->bookable_type === get_class($artisan)
            && $this->bookingRequest->bookable_id === $artisan->id,
            403
        );

        $finalPrice = round((float) $this->finalPrice, 2);
        $remaining = max(0, round($finalPrice - $this->depositAmount, 2));

        // 1. Sauvegarder le prix final
        $this->bookingRequest->update([
            'final_price' => $finalPrice,
            'balance_requested_at' => now(),
        ]);

        // 2. Envoyer un message dans le chat
        $conversation = $this->bookingRequest->conversation;
        if ($conversation) {
            $paymentUrl = route('balance.payment', ['booking' => $this->bookingRequest->id]);

            $messageContent = "💰 **Demande de paiement du solde**\n\n"
                . "Prix définitif de la prestation : **{$finalPrice} €**\n"
                . "Acompte déjà versé : **{$this->depositAmount} €**\n"
                . "━━━━━━━━━━━━━━━━━━\n"
                . "**Reste à payer : {$remaining} €**\n\n"
                . "Vous pouvez régler le solde en cliquant sur le lien ci-dessous.";

            Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => auth()->id(),
                'content' => $messageContent,
                'sender_type' => 'tattooer',
                'metadata' => json_encode([
                    'type' => 'balance_request',
                    'final_price' => $finalPrice,
                    'deposit_amount' => $this->depositAmount,
                    'remaining' => $remaining,
                    'payment_url' => $paymentUrl,
                    'booking_request_id' => $this->bookingRequest->id,
                ]),
            ]);
        }

        // 3. Notifier le client
        if ($this->bookingRequest->client?->user) {
            try {
                $this->bookingRequest->client->user->notify(
                    new \App\Notifications\BalanceRequestedNotification(
                        $this->bookingRequest,
                        $finalPrice,
                        $remaining
                    )
                );
            } catch (\Exception $e) {
                Log::warning('[Balance] Notification échouée', ['error' => $e->getMessage()]);
            }
        }

        Log::info('[Balance] Demande de solde envoyée', [
            'booking_id' => $this->bookingRequest->id,
            'final_price' => $finalPrice,
            'remaining' => $remaining,
        ]);

        $this->show = false;
        $this->dispatch('balance-requested');
        session()->flash('success', "Demande de solde envoyée ({$remaining} €)");
    }

    public function closeModal(): void
    {
        $this->show = false;
        $this->reset(['bookingRequestId', 'bookingRequest', 'finalPrice']);
    }

    public function render()
    {
        return view('livewire.tattooer.request-balance-payment');
    }
}
```

### Vue du composant

```blade
{{-- resources/views/livewire/tattooer/request-balance-payment.blade.php --}}
<div>
    @if ($show && $bookingRequest)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data x-transition>

            {{-- Overlay --}}
            <div class="fixed inset-0 bg-noir-profond/70" wire:click="closeModal"></div>

            {{-- Modal --}}
            <div class="relative bg-gris-fonde rounded-2xl shadow-xl border border-titane/20 w-full max-w-md z-10">

                {{-- Header --}}
                <div class="p-5 border-b border-titane/10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-beige-peau">
                            💰 Demande de paiement du solde
                        </h3>
                        <button wire:click="closeModal" class="text-titane hover:text-ivoire-text transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-ivoire-text/60 mt-1">
                        Client : {{ $bookingRequest->client?->user?->name ?? 'Client' }}
                    </p>
                </div>

                {{-- Body --}}
                <div class="p-5 space-y-5">

                    {{-- Récap booking --}}
                    <div class="p-3 bg-noir-profond/30 rounded-xl text-sm">
                        <div class="flex justify-between text-ivoire-text/60">
                            <span>Zone</span>
                            <span class="text-titane">{{ $bookingRequest->body_zone ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between text-ivoire-text/60 mt-1">
                            <span>Taille</span>
                            <span class="text-titane">{{ $bookingRequest->tattoo_size ?? '—' }}</span>
                        </div>
                        @if ($estimatedPrice > 0)
                            <div class="flex justify-between text-ivoire-text/60 mt-1">
                                <span>Prix estimé initial</span>
                                <span class="text-titane">{{ number_format($estimatedPrice, 2, ',', ' ') }} €</span>
                            </div>
                        @endif
                    </div>

                    {{-- Input prix définitif --}}
                    <div>
                        <label class="block text-sm font-medium text-ivoire-text/80 mb-2">
                            Prix définitif (€)
                        </label>
                        <input type="number"
                               wire:model.live.debounce.300ms="finalPrice"
                               step="0.01"
                               min="1"
                               placeholder="Ex: 350.00"
                               class="w-full bg-noir-profond border border-titane/20 rounded-xl px-4 py-3
                                      text-xl text-center text-beige-peau font-bold
                                      focus:outline-none focus:ring-2 focus:ring-beige-peau/40 focus:border-beige-peau/40
                                      placeholder:text-titane/30">
                        @error('finalPrice')
                            <p class="text-rouge-alerte text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Récap financier --}}
                    <div class="p-4 bg-noir-profond/50 rounded-xl space-y-2">
                        <div class="flex justify-between text-sm text-ivoire-text/60">
                            <span>Prix définitif</span>
                            <span class="text-titane font-medium">
                                {{ $finalPrice ? number_format((float)$finalPrice, 2, ',', ' ') : '—' }} €
                            </span>
                        </div>

                        @if ($depositAmount > 0)
                            <div class="flex justify-between text-sm text-ivoire-text/60">
                                <span>Acompte versé ✅</span>
                                <span class="text-vert-succes font-medium">
                                    - {{ number_format($depositAmount, 2, ',', ' ') }} €
                                </span>
                            </div>
                        @endif

                        <div class="border-t border-titane/10 pt-2">
                            <div class="flex justify-between">
                                <span class="text-sm font-semibold text-ivoire-text">Reste à payer</span>
                                <span class="text-xl font-bold text-beige-peau">
                                    {{ number_format($remainingBalance, 2, ',', ' ') }} €
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Info --}}
                    <p class="text-xs text-ivoire-text/40 text-center">
                        Le client recevra un message avec le détail et un lien de paiement sécurisé (Stripe).
                    </p>
                </div>

                {{-- Footer --}}
                <div class="p-5 border-t border-titane/10 flex gap-3">
                    <button wire:click="closeModal"
                            class="flex-1 py-3 border border-titane/20 text-titane rounded-xl
                                   text-sm hover:bg-titane/5 transition">
                        Annuler
                    </button>
                    <button wire:click="submitBalanceRequest"
                            wire:loading.attr="disabled"
                            @if(!$finalPrice || (float)$finalPrice <= 0) disabled @endif
                            class="flex-1 py-3 bg-beige-peau text-noir-profond rounded-xl
                                   text-sm font-semibold hover:bg-beige-peau/90 transition
                                   disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="submitBalanceRequest">
                            Envoyer la demande
                        </span>
                        <span wire:loading wire:target="submitBalanceRequest">
                            Envoi en cours...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
```

```bash
git add -A && git commit -m "feat(balance): composant Livewire RequestBalancePayment — modal prix final + calcul reste à payer"
```

---

## PHASE 3 — NOTIFICATION BALANCE REQUESTED

```bash
php artisan make:notification BalanceRequestedNotification
```

```php
// app/Notifications/BalanceRequestedNotification.php
namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BalanceRequestedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected BookingRequest $booking,
        protected float $finalPrice,
        protected float $remaining,
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $paymentUrl = route('balance.payment', ['booking' => $this->booking->id]);
        $artistName = $this->booking->bookable?->user?->name ?? 'Votre artiste';

        return (new MailMessage)
            ->subject("Ink&Pik — Paiement du solde ({$this->remaining} €)")
            ->greeting("Bonjour {$notifiable->name} !")
            ->line("{$artistName} vous demande le paiement du solde de votre prestation.")
            ->line("**Prix définitif : {$this->finalPrice} €**")
            ->line("Acompte déjà versé : " . number_format($this->finalPrice - $this->remaining, 2, ',', ' ') . " €")
            ->line("**Reste à payer : {$this->remaining} €**")
            ->action("Payer le solde ({$this->remaining} €)", $paymentUrl)
            ->line('Le paiement est sécurisé via Stripe (3D Secure).')
            ->salutation('L\'équipe Ink&Pik');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'balance_requested',
            'booking_id' => $this->booking->id,
            'final_price' => $this->finalPrice,
            'remaining' => $this->remaining,
            'message' => "Paiement du solde demandé : {$this->remaining} € à régler.",
        ];
    }
}
```

```bash
git add -A && git commit -m "feat(balance): BalanceRequestedNotification — email + DB avec lien paiement"
```

---

## PHASE 4 — INTÉGRER DANS LES VUES (ARTISTE)

### 4A. Inclure le composant Livewire dans les layouts/vues

Le composant doit être présent dans les pages où l'artiste peut déclencher la demande :

```bash
# Vérifier quels layouts/vues incluent déjà des modals Livewire
grep -rn "livewire.*accept\|livewire.*booking\|livewire.*modal" resources/views/tattooer/request-show.blade.php resources/views/tattooer/requests.blade.php resources/views/layouts/tattooer.blade.php 2>/dev/null | head -10
```

Ajouter le composant dans les mêmes endroits :

```blade
{{-- Dans request-show.blade.php OU dans le layout tattooer --}}
<livewire:tattooer.request-balance-payment />
```

### 4B. Bouton "Demander le solde" dans request-show

Ajouter le bouton dans la section actions de `request-show.blade.php`.
**Conditions d'affichage** :
- Le booking a un acompte payé (`deposit_paid_at` non null)
- Le solde n'a pas encore été payé (`balance_paid_at` null)
- Le statut permet la demande (confirmed, deposit_paid, design_sent, completed)

```blade
{{-- Dans request-show.blade.php — section actions artiste --}}
@if ($bookingRequest->deposit_paid_at
    && !$bookingRequest->balance_paid_at
    && in_array($bookingRequest->status, ['confirmed', 'deposit_paid', 'design_sent', 'completed']))

    <button x-data
            @click="$dispatch('open-balance-modal', { bookingRequestId: {{ $bookingRequest->id }} })"
            class="inline-flex items-center gap-2 px-4 py-2 bg-beige-peau text-noir-profond
                   rounded-xl text-sm font-semibold hover:bg-beige-peau/90 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Demander le solde
    </button>

    {{-- Si déjà demandé mais pas payé --}}
    @if ($bookingRequest->balance_requested_at && !$bookingRequest->balance_paid_at)
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-terre-cuite/20
                     text-orange-terre-cuite rounded-lg text-xs font-medium">
            ⏳ Solde demandé le {{ $bookingRequest->balance_requested_at->format('d/m/Y') }}
            — en attente de paiement
        </span>
    @endif
@endif
```

> ⚠️ Utiliser `@click="$dispatch(...)"` (Alpine), PAS `onclick="Livewire.dispatch()"` (bloqué par CSP).

### 4C. Bouton dans le chat artiste

Trouver le composant chat et ajouter un bouton d'action :

```bash
# Trouver le chat booking
find app/Livewire -name "*Chat*" -o -name "*ProjectChat*" | head -5
grep -n "function render\|bookingRequest\|booking_request" app/Livewire/ProjectChat.php 2>/dev/null | head -10
```

Ajouter dans la vue du chat (au-dessus ou en-dessous de l'input de message) :

```blade
{{-- Dans la vue chat artiste — barre d'actions --}}
@if ($bookingRequest
    && $bookingRequest->deposit_paid_at
    && !$bookingRequest->balance_paid_at)

    <button x-data
            @click="$dispatch('open-balance-modal', { bookingRequestId: {{ $bookingRequest->id }} })"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-beige-peau/10 text-beige-peau
                   border border-beige-peau/20 rounded-lg text-xs font-medium
                   hover:bg-beige-peau/20 transition">
        💰 Demander le solde
    </button>
@endif
```

```bash
git add -A && git commit -m "feat(balance): boutons 'Demander le solde' dans request-show + chat artiste"
```

---

## PHASE 5 — CÔTÉ CLIENT : BOUTON PAYER LE SOLDE

### 5A. Dans le chat client — afficher le message de demande avec bouton

Quand un message a un `metadata.type === 'balance_request'`, l'afficher avec un bouton :

```bash
# Trouver la vue chat client
find resources/views -path "*client*" -name "*chat*" -o -path "*livewire*" -name "*chat*" | head -5
```

Dans la vue chat (là où les messages sont rendus), ajouter une condition :

```blade
{{-- Dans le rendu des messages du chat --}}
@php
    $meta = is_string($message->metadata) ? json_decode($message->metadata, true) : $message->metadata;
@endphp

@if (($meta['type'] ?? null) === 'balance_request')
    {{-- Message spécial paiement solde --}}
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

        @if (!($bookingRequest?->balance_paid_at))
            <a href="{{ $meta['payment_url'] ?? '#' }}"
               class="mt-3 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5
                      bg-beige-peau text-noir-profond rounded-xl text-sm font-semibold
                      hover:bg-beige-peau/90 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Payer le solde ({{ number_format($meta['remaining'] ?? 0, 2, ',', ' ') }} €)
            </a>
        @else
            <div class="mt-3 p-2 bg-vert-succes/10 text-vert-succes rounded-lg text-sm text-center font-medium">
                ✅ Solde payé
            </div>
        @endif
    </div>
@else
    {{-- Message normal --}}
    <p>{{ $message->content }}</p>
@endif
```

### 5B. Bouton payer dans la vue client booking-request-show

```bash
find resources/views/client -name "*request*show*" -o -name "*booking*show*" | head -3
```

Ajouter dans la vue détail booking côté client :

```blade
{{-- Dans la vue client booking detail --}}
@if ($bookingRequest->balance_requested_at && !$bookingRequest->balance_paid_at)
    <div class="p-4 bg-beige-peau/10 border border-beige-peau/20 rounded-xl">
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

        <a href="{{ route('balance.payment', ['booking' => $bookingRequest->id]) }}"
           class="w-full inline-flex items-center justify-center gap-2 px-4 py-3
                  bg-beige-peau text-noir-profond rounded-xl font-semibold
                  hover:bg-beige-peau/90 transition">
            Payer le solde ({{ number_format($bookingRequest->remaining_balance, 2, ',', ' ') }} €)
        </a>
    </div>
@elseif ($bookingRequest->balance_paid_at)
    <div class="p-3 bg-vert-succes/10 border border-vert-succes/20 rounded-xl text-center">
        <p class="text-sm font-medium text-vert-succes">✅ Solde payé le {{ $bookingRequest->balance_paid_at->format('d/m/Y') }}</p>
    </div>
@endif
```

```bash
git add -A && git commit -m "feat(balance): côté client — bouton payer dans le chat + vue booking detail"
```

---

## PHASE 6 — VÉRIFIER LE BALANCE PAYMENT CONTROLLER

Le `BalancePaymentController` existe déjà. Vérifier qu'il :
1. Utilise `final_price` (pas `total_price`) pour calculer le montant Stripe
2. Déduit correctement l'acompte
3. Redirige correctement après paiement

```bash
cat app/Http/Controllers/BalancePaymentController.php
```

**Vérifications** :
- Le montant Stripe doit être : `(int) round($booking->remaining_balance * 100)` (centimes)
- La route de succès doit mettre à jour `balance_paid_at = now()`
- La notification `BalancePaidNotification` doit être envoyée

Si le controller utilise `total_price` au lieu de `final_price` :

```php
// ❌ AVANT
$amount = $bookingRequest->total_price - $bookingRequest->total_deposit_amount;

// ✅ APRÈS
$amount = $bookingRequest->remaining_balance;
// OU
$finalPrice = $bookingRequest->final_price ?? $bookingRequest->total_price;
$amount = max(0, $finalPrice - ($bookingRequest->total_deposit_amount ?? 0));
```

```bash
git add -A && git commit -m "fix(balance): BalancePaymentController utilise final_price + remaining_balance"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PAIEMENT SOLDE ==="

# V1. Migration
php artisan tinker --execute="
  echo 'final_price: ' . (Schema::hasColumn('booking_requests', 'final_price') ? 'OK' : 'ABSENT') . PHP_EOL;
  echo 'balance_requested_at: ' . (Schema::hasColumn('booking_requests', 'balance_requested_at') ? 'OK' : 'ABSENT') . PHP_EOL;
"

# V2. Composant Livewire
ls app/Livewire/Tattooer/RequestBalancePayment.php && echo "Composant OK"
ls resources/views/livewire/tattooer/request-balance-payment.blade.php && echo "Vue OK"

# V3. Notification
ls app/Notifications/BalanceRequestedNotification.php && echo "Notification OK"

# V4. Composant inclus dans les vues
grep -c "request-balance-payment\|RequestBalancePayment" resources/views/tattooer/request-show.blade.php resources/views/layouts/tattooer.blade.php 2>/dev/null
echo "Composant inclus (doit être > 0)"

# V5. Bouton dans request-show
grep -c "open-balance-modal\|Demander le solde" resources/views/tattooer/request-show.blade.php
echo "Bouton artiste (doit être > 0)"

# V6. Bouton dans chat
grep -c "balance_request\|Payer le solde" resources/views/client/chat.blade.php resources/views/livewire/*/chat*.blade.php 2>/dev/null
echo "Bouton client chat (doit être > 0)"

# V7. Routes balance
php artisan route:list 2>&1 | grep -i "balance" | head -5

# V8. Compilation
php artisan route:cache 2>&1 | head -3
php artisan view:clear

echo "=== PAIEMENT SOLDE TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — le BalancePaymentController existe, lire en entier avant de toucher
2. **Alpine `$dispatch`** pour ouvrir la modal — JAMAIS `onclick="Livewire.dispatch()"` (CSP)
3. **Montants en EUROS** en base, **CENTIMES** pour Stripe — conversion `(int) round($euros * 100)`
4. **remaining_balance** = final_price - deposit — calcul côté modèle (accessor)
5. **Message metadata JSON** — stocker type + montants dans le message pour le rendu spécial chat
6. **Le client voit le bouton payer** dans le chat ET dans la vue booking detail
7. **L'artiste déclenche** depuis request-show ET depuis le chat
8. **Ne PAS modifier DepositController** — il gère l'acompte, pas le solde
9. **Notification email** au client avec le lien de paiement direct
10. **Commit après chaque phase** (6 commits)
