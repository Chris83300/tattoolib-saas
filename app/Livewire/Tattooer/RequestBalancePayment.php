<?php

namespace App\Livewire\Tattooer;

use App\Models\BookingRequest;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RequestBalancePayment extends Component
{
    public ?int $bookingRequestId = null;
    public ?BookingRequest $bookingRequest = null;
    public bool $show = false;

    public ?string $finalPrice = null;
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

        $this->estimatedPrice = (float) ($this->bookingRequest->total_price ?? 0);

        // Pré-remplir avec le prix final confirmé si déjà défini, sinon le prix estimé
        $prefill = $this->bookingRequest->confirmed_final_price ?? $this->bookingRequest->total_price ?? 0;
        $this->finalPrice = $prefill > 0 ? number_format((float) $prefill, 2, '.', '') : null;

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
        // Recompute deposit from model on every call (fixes Livewire hydration issue)
        if ($this->bookingRequest) {
            $this->depositAmount = (float) ($this->bookingRequest->deposit_paid_at
                ? ($this->bookingRequest->total_deposit_amount ?? 0)
                : 0);
        }
        $price = (float) ($this->finalPrice ?? 0);
        $this->remainingBalance = max(0, round($price - $this->depositAmount, 2));
    }

    public function submitBalanceRequest(): void
    {
        $this->validate();

        $artisan = Auth::user()->artisan();
        abort_unless(
            $this->bookingRequest->bookable_type === get_class($artisan)
            && $this->bookingRequest->bookable_id === $artisan->id,
            403
        );

        $finalPrice = round((float) $this->finalPrice, 2);
        $remaining = max(0, round($finalPrice - $this->depositAmount, 2));

        // 1. Sauvegarder le prix final et marquer la demande
        $this->bookingRequest->update([
            'confirmed_final_price' => $finalPrice,
            'final_price_confirmed' => true,
            'final_price_confirmed_at' => now(),
            'balance_requested_at' => now(),
        ]);

        // 2. Envoyer un message dans le chat
        $conversation = $this->bookingRequest->conversation;
        if ($conversation) {
            $brId = $this->bookingRequest->id;

            $messageContent = "Demande de paiement du solde\n\n"
                . "Prix définitif : {$finalPrice} €\n"
                . "Acompte versé : {$this->depositAmount} €\n"
                . "Reste à payer : {$remaining} €"
                . "[BALANCE_PAYMENT:{$brId}]";

            Message::create([
                'conversation_id' => $conversation->id,
                'booking_request_id' => $this->bookingRequest->id,
                'sender_id' => Auth::id(),
                'sender_type' => 'tattooer',
                'content' => $messageContent,
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
        session()->flash('success', "Demande de solde envoyée ({$remaining} €). Le client a été notifié.");
    }

    public function closeModal(): void
    {
        $this->show = false;
        $this->reset(['bookingRequestId', 'bookingRequest', 'finalPrice', 'remainingBalance', 'depositAmount', 'estimatedPrice']);
    }

    public function render()
    {
        return view('livewire.tattooer.request-balance-payment');
    }
}
