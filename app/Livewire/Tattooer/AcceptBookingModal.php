<?php

namespace App\Livewire\Tattooer;

use App\Models\BookingRequest;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class AcceptBookingModal extends Component
{
    public bool $showModal = false;
    public ?int $bookingRequestId = null;
    public ?BookingRequest $bookingRequest = null;

    // Section 1 — Estimation
    public ?float $priceEstimateMin = null;
    public ?float $priceEstimateMax = null;
    public ?float $totalDepositAmount = null;
    public int $clientPaymentDeadlineDays = 7;
    public string $tattooerNotes = '';

    // Section 2 — Dates (reçues du composant AvailabilityCalendar)
    public array $proposedDates = [];

    // Section 3 — Design
    public int $includedDesignVersions = 2;
    public int $modificationsPerDesign = 2;

    protected function rules(): array
    {
        return [
            'priceEstimateMin' => 'required|numeric|min:1',
            'priceEstimateMax' => 'required|numeric|min:1|gte:priceEstimateMin',
            'totalDepositAmount' => 'required|numeric|min:1',
            'clientPaymentDeadlineDays' => 'required|integer|min:1|max:30',
            'proposedDates' => 'required|array|min:1|max:3',
            'proposedDates.*.date' => 'required|date|after:today',
            'proposedDates.*.period' => 'nullable|string',
            'includedDesignVersions' => 'required|integer|min:1|max:5',
            'modificationsPerDesign' => 'required|integer|min:0|max:5',
        ];
    }

    protected $messages = [
        'priceEstimateMin.required' => 'Le prix minimum est obligatoire.',
        'priceEstimateMax.gte' => 'Le prix maximum doit être supérieur ou égal au minimum.',
        'totalDepositAmount.required' => 'Le montant de l\'acompte est obligatoire.',
        'proposedDates.required' => 'Proposez au moins 1 date.',
        'proposedDates.min' => 'Proposez au moins 1 date.',
        'proposedDates.max' => 'Proposez maximum 3 dates.',
        'proposedDates.*.date.required' => 'La date est obligatoire.',
        'proposedDates.*.date.after' => 'La date doit être dans le futur.',
    ];

    #[On('open-accept-modal')]
    public function openModal(int $bookingRequestId): void
    {
        $tattooer = auth()->user()->tattooer;
        $this->bookingRequest = BookingRequest::where('id', $bookingRequestId)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->firstOrFail();

        $this->bookingRequestId = $bookingRequestId;

        // Pré-remplir avec les défauts du tattooer
        $this->clientPaymentDeadlineDays = $tattooer->default_client_payment_deadline_days ?? 7;
        $this->includedDesignVersions = $tattooer->default_design_versions_included ?? 2;
        $this->totalDepositAmount = $tattooer->minimum_deposit ? (float) $tattooer->minimum_deposit : null;
        $this->proposedDates = [];

        $this->showModal = true;
    }

    /**
     * Écouter les dates sélectionnées depuis AvailabilityCalendar
     */
    #[On('dates-updated')]
    public function onDatesUpdated(array $selectedDates): void
    {
        $this->proposedDates = $selectedDates;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset([
            'bookingRequestId', 'bookingRequest',
            'priceEstimateMin', 'priceEstimateMax', 'totalDepositAmount',
            'proposedDates',
        ]);
        $this->resetValidation();
    }

    public function submitAcceptance(): void
    {
        try {
            $this->validate();

            // Debug
            \Log::info('AcceptBookingModal: submitAcceptance called', [
                'booking_request_id' => $this->bookingRequest->id,
                'validated_data' => [
                    'priceEstimateMin' => $this->priceEstimateMin,
                    'priceEstimateMax' => $this->priceEstimateMax,
                    'totalDepositAmount' => $this->totalDepositAmount,
                ]
            ]);

            // Vérifier cohérence prix
            if ($this->totalDepositAmount > $this->priceEstimateMax * 0.5) {
                $this->addError('totalDepositAmount', 'L\'acompte ne peut pas dépasser 50% du prix maximum.');
                return;
            }

            // Mettre à jour directement le bookingRequest
            $this->bookingRequest->update([
                'status' => 'accepted',
                'accepted_at' => now(),
                'price_estimate_min' => $this->priceEstimateMin,
                'price_estimate_max' => $this->priceEstimateMax,
                'total_deposit_amount' => $this->totalDepositAmount,
                'client_payment_deadline_days' => $this->clientPaymentDeadlineDays,
                'proposed_dates' => $this->proposedDates,
                'included_design_versions' => $this->includedDesignVersions,
                'modifications_per_design' => $this->modificationsPerDesign,
                'deposit_deadline' => now()->addDays($this->clientPaymentDeadlineDays),
                'date_selection_deadline' => now()->addHours(48),
            ]);

            // Créer la conversation si elle n'existe pas
            if (!$this->bookingRequest->conversation) {
                $conversation = \App\Models\Conversation::create([
                    'booking_request_id' => $this->bookingRequest->id,
                    'client_id' => $this->bookingRequest->client_id,
                    'tattooer_id' => auth()->user()->tattooer->id,
                    'status' => 'active',
                ]);

                // Envoyer le message d'acceptation
                $conversation->messages()->create([
                    'sender_type' => 'tattooer',
                    'sender_id' => auth()->id(),
                    'content' => "Bonjour ! 🎨\n\n" .
                               "J'accepte votre demande de tattoo avec plaisir !\n\n" .
                               "📍 Zone : {$this->bookingRequest->body_zone}\n" .
                               "💰 Prix : {$this->priceEstimateMin}€ - {$this->priceEstimateMax}€\n" .
                               "💳 Acompte : {$this->totalDepositAmount}€\n\n" .
                               "N'hésitez pas à me contacter si vous avez des questions !",
                    'read_by_client_at' => null,
                    'read_by_tattooer_at' => now(),
                ]);
            }

            // Fermer la modal puis rediriger (force le refresh de la page Blade)
            $this->showModal = false;

            session()->flash('success', '✅ Demande acceptée ! La conversation a été créée.');

            $this->redirect(
                route('tattooer.request.show', $this->bookingRequest),
                navigate: false  // false = full page reload (nécessaire car page Blade, pas Livewire)
            );

        } catch (\Exception $e) {
            \Log::error('AcceptBookingModal: submitAcceptance error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->addError('general', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.tattooer.accept-booking-modal');
    }
}
