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
    public $tattooer = null; // Pour compatibilité avec la vue (peut être Tattooer ou Piercer)

    // Section 1 — Estimation
    public ?float $priceEstimateMin = null;
    public ?float $priceEstimateMax = null;
    public ?float $totalDepositAmount = null;
    public int $clientPaymentDeadlineDays = 7;
    public string $tattooerNotes = '';

    // Section 2 — Dates (reçues du composant AvailabilityCalendar)
    public array $proposedDates = [];

    // Section 3 — Design
    public bool $needsDesignPreparation = false;
    public int $includedDesignVersions = 2;
    public int $modificationsPerDesign = 2;

    protected function rules(): array
    {
        $rules = [
            'totalDepositAmount' => 'required|numeric|min:1',
            'clientPaymentDeadlineDays' => 'required|integer|min:1|max:30',
            'proposedDates' => 'required|array|min:1|max:3',
            'proposedDates.*.date' => 'required|date|after_or_equal:today',
            'proposedDates.*.period' => 'nullable|string',
            'needsDesignPreparation' => 'boolean',
            'includedDesignVersions' => 'required_if:needsDesignPreparation,true|integer|min:1|max:5',
            'modificationsPerDesign' => 'required_if:needsDesignPreparation,true|integer|min:0|max:5',
        ];

        // Ajouter les règles de prix seulement pour les tattooers
        if ($this->tattooer && !($this->tattooer instanceof \App\Models\Piercer)) {
            $rules['priceEstimateMin'] = 'required|numeric|min:1';
            $rules['priceEstimateMax'] = 'required|numeric|min:1|gte:priceEstimateMin';
        }

        return $rules;
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
        $user = auth()->user();

        // Récupérer l'artisan (tattooer ou piercer)
        if ($user->isTattooer()) {
            $artisan = $user->tattooer;
        } elseif ($user->isPiercer()) {
            $artisan = $user->piercer;
        } else {
            abort(403, 'Type d\'artisan non autorisé');
        }

        $this->tattooer = $artisan; // Assigner pour la vue
        $this->bookingRequest = BookingRequest::where('id', $bookingRequestId)
            ->where('bookable_id', $artisan->id)
            ->where('bookable_type', get_class($artisan))
            ->firstOrFail();

        $this->showModal = true;
        $this->bookingRequestId = $bookingRequestId;

        // Réinitialiser les valeurs
        $this->reset([
            'priceEstimateMin', 'priceEstimateMax', 'totalDepositAmount',
            'clientPaymentDeadlineDays', 'tattooerNotes', 'proposedDates',
            'needsDesignPreparation', 'includedDesignVersions', 'modificationsPerDesign'
        ]);

        // Pré-remplir avec les défauts de l'artisan
        $this->clientPaymentDeadlineDays = $artisan->default_client_payment_deadline_days ?? 7;
        $this->includedDesignVersions = $artisan->default_design_versions_included ?? 2;
        $this->totalDepositAmount = $artisan->minimum_deposit ? (float) $artisan->minimum_deposit : null;
        $this->proposedDates = [];
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

            // Vérifier cohérence prix (seulement pour les tattooers)
            if ($this->tattooer && !($this->tattooer instanceof \App\Models\Piercer)) {
                if ($this->totalDepositAmount > $this->priceEstimateMax * 0.5) {
                    $this->addError('totalDepositAmount', 'L\'acompte ne peut pas dépasser 50% du prix maximum.');
                    return;
                }
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
                'included_design_versions' => $this->needsDesignPreparation ? $this->includedDesignVersions : 0,
                'modifications_per_design' => $this->needsDesignPreparation ? $this->modificationsPerDesign : 0,
                'deposit_deadline' => now()->addDays($this->clientPaymentDeadlineDays),
                'date_selection_deadline' => now()->addHours(48),
            ]);

            // Créer la conversation si elle n'existe pas
            if (!$this->bookingRequest->conversation) {
                $user = auth()->user();

                // Récupérer l'artisan (tattooer ou piercer)
                if ($user->isTattooer()) {
                    $artisan = $user->tattooer;
                } elseif ($user->isPiercer()) {
                    $artisan = $user->piercer;
                } else {
                    abort(403, 'Type d\'artisan non autorisé');
                }

                $conversation = \App\Models\Conversation::create([
                    'booking_request_id' => $this->bookingRequest->id,
                    'client_id' => $this->bookingRequest->client_id,
                    'tattooer_id' => $artisan->id,
                    'status' => 'active',
                ]);

                // Envoyer le message d'acceptation
                $messageContent = "Bonjour ! 🎨\n\n" .
                                 "J'accepte votre demande avec plaisir !\n\n" .
                                 "📍 Zone : {$this->bookingRequest->body_zone}\n";

                // Ajouter les infos de prix selon le type d'artisan
                if ($artisan instanceof \App\Models\Piercer) {
                    // Pour les pierceurs, afficher le tarif total
                    $messageContent .= "💰 Tarif : {$this->totalDepositAmount}€\n";
                } else {
                    // Pour les tattooers, afficher la fourchette de prix
                    $messageContent .= "💰 Prix : {$this->priceEstimateMin}€ - {$this->priceEstimateMax}€\n";
                }

                $messageContent .= "💳 Acompte : {$this->totalDepositAmount}€\n\n" .
                                 "Propositions de dates :\n";

                foreach ($this->proposedDates as $date) {
                    $dateObj = \Carbon\Carbon::parse($date['date']);
                    $period = $date['period'] ? ' (' . ucfirst($date['period']) . ')' : '';
                    $messageContent .= "📅 " . $dateObj->format('d/m/Y') . $period . "\n";
                }

                $messageContent .= "\nMerci de choisir une date et de procéder au paiement pour confirmer.\n" .
                                 "À bientôt ! ✨";

                $conversation->messages()->create([
                    'sender_type' => 'tattooer',
                    'sender_id' => auth()->id(),
                    'content' => $messageContent,
                ]);
            }

            // Fermer la modal
            $this->closeModal();

            // Message de succès
            session()->flash('success', '✅ Demande acceptée ! La conversation a été créée.');

            // Rediriger vers la page de la demande pour forcer le rechargement
            $artisan = auth()->user()->artisan();
            return $this->redirect(
                route($artisan->routePrefix() . '.request.show', $this->bookingRequest),
                navigate: false
            );

        } catch (\Exception $e) {
            \Log::error('AcceptBookingModal: submitAcceptance error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->addError('general', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    public function render()
    {
        return view('livewire.tattooer.accept-booking-modal');
    }
}
