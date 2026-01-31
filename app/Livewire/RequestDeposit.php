<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RequestDeposit extends Component
{
    public Project $project;

    // Formulaire
    public $depositAmount;
    public $estimatedPrice;
    public $estimatedDuration;
    public $appointmentDate;
    public $appointmentTime;

    protected $rules = [
        'depositAmount' => 'required|numeric|min:10',
        'estimatedPrice' => 'required|numeric|min:' . 'depositAmount',
        'estimatedDuration' => 'required|integer|min:30',
        'appointmentDate' => 'required|date|after:today',
        'appointmentTime' => 'required',
    ];

    protected $messages = [
        'depositAmount.min' => 'L\'acompte minimum est de 10€.',
        'estimatedPrice.min' => 'Le prix total doit être supérieur à l\'acompte.',
        'estimatedDuration.min' => 'La durée minimum est de 30 minutes.',
        'appointmentDate.after' => 'La date doit être dans le futur.',
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->authorize('update', $project);

        // Pré-remplir avec les valeurs existantes
        $this->depositAmount = $project->deposit_amount;
        $this->estimatedPrice = $project->estimated_price;
        $this->estimatedDuration = $project->estimated_duration;

        if ($project->appointment_date) {
            $this->appointmentDate = $project->appointment_date->format('Y-m-d');
            $this->appointmentTime = $project->appointment_date->format('H:i');
        }
    }

    public function requestDeposit()
    {
        $this->validate();

        try {
            // Combiner date et heure
            $appointmentDateTime = new \DateTime($this->appointmentDate . ' ' . $this->appointmentTime);

            // Mettre à jour le projet
            $this->project->update([
                'deposit_amount' => $this->depositAmount,
                'estimated_price' => $this->estimatedPrice,
                'estimated_duration' => $this->estimatedDuration,
                'appointment_date' => $appointmentDateTime,
                'deposit_requested_at' => now(),
            ]);

            // Notification client (à implémenter)
            // $this->project->client->user->notify(new DepositRequestedNotification($this->project));

            session()->flash('success', 'Demande d\'acompte envoyée au client !');

            // Émettre un événement pour rafraîchir les autres composants
            $this->dispatch('depositRequested');

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la demande d\'acompte.');
            Log::error('Request deposit error: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir le pourcentage de l'acompte
     */
    public function getDepositPercentageProperty(): float
    {
        if (!$this->estimatedPrice || !$this->depositAmount) {
            return 0;
        }

        return round(($this->depositAmount / $this->estimatedPrice) * 100, 1);
    }

    /**
     * Obtenir le montant restant dû
     */
    public function getRemainingAmountProperty(): float
    {
        return max(0, $this->estimatedPrice - $this->depositAmount);
    }

    /**
     * Obtenir l'heure de fin du rendez-vous
     */
    public function getAppointmentEndTimeProperty(): string
    {
        if (!$this->appointmentDate || !$this->appointmentTime || !$this->estimatedDuration) {
            return '';
        }

        $startTime = new \DateTime($this->appointmentDate . ' ' . $this->appointmentTime);
        $endTime = clone $startTime;
        $endTime->modify("+{$this->estimatedDuration} minutes");

        return $endTime->format('H:i');
    }

    /**
     * Obtenir les créneaux horaires disponibles
     */
    public function getAvailableTimeSlots(): array
    {
        $slots = [];
        for ($hour = 8; $hour <= 20; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $time = sprintf('%02d:%02d', $hour, $minute);
                $slots[] = $time;
            }
        }
        return $slots;
    }

    /**
     * Calculer automatiquement l'acompte (30% par défaut)
     */
    public function calculateDeposit()
    {
        if ($this->estimatedPrice) {
            $this->depositAmount = round($this->estimatedPrice * 0.3, 2);
        }
    }

    /**
     * Calculer la durée estimée selon le prix
     */
    public function calculateDuration()
    {
        if ($this->estimatedPrice) {
            // Estimation basique: 100€ = 1h
            $this->estimatedDuration = max(30, intval($this->estimatedPrice * 0.6));
        }
    }

    public function render()
    {
        return view('livewire.request-deposit');
    }
}
