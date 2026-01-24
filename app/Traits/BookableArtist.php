<?php

namespace App\Traits;

use App\Models\BookingRequest;

trait BookableArtist
{
    /**
     * Vérifie si le tatoueur/studio artist a complété l'onboarding Stripe
     */
    public function hasCompletedStripeOnboarding(): bool
    {
        return $this->stripe_onboarding_complete
            && !empty($this->stripe_connect_account_id);
    }

    /**
     * Vérifie si le tatoueur/studio artist peut accepter des bookings
     */
    public function canAcceptBookings(): bool
    {
        // Pour Tattooer: utilise siret_verified
        // Pour StudioArtist: utilise credentials_managed_by_studio (géré par le studio)
        if ($this instanceof \App\Models\Tattooer) {
            return $this->siret_verified && $this->hasCompletedStripeOnboarding();
        }

        if ($this instanceof \App\Models\StudioArtist) {
            return $this->credentials_managed_by_studio && $this->hasCompletedStripeOnboarding();
        }

        return false;
    }

    /**
     * Calcule le montant de l'acompte
     */
    public function calculateDepositAmount(float $totalPrice): float
    {
        // Pour Tattooer: utilise ses propres paramètres
        // Pour StudioArtist: utilise les paramètres du studio ou par défaut
        if ($this instanceof \App\Models\Tattooer) {
            $calculated = ($totalPrice * $this->default_deposit_rate) / 100;
            return max($calculated, $this->minimum_deposit);
        }

        if ($this instanceof \App\Models\StudioArtist) {
            // Pour StudioArtist, utilise un taux par défaut ou celui du studio
            $depositRate = 30; // Taux par défaut
            $minimumDeposit = 50; // Minimum par défaut

            $calculated = ($totalPrice * $depositRate) / 100;
            return max($calculated, $minimumDeposit);
        }

        return 0;
    }

    /**
     * Récupère les booking requests pour ce bookable
     */
    public function getBookingRequests()
    {
        return BookingRequest::where('bookable_type', get_class($this))
            ->where('bookable_id', $this->id)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Récupère les booking requests par statut
     */
    public function getBookingRequestsByStatus(string $status)
    {
        return $this->getBookingRequests()->where('status', $status);
    }

    /**
     * Compte les booking requests par statut
     */
    public function countBookingRequestsByStatus(string $status): int
    {
        return $this->getBookingRequestsByStatus($status)->count();
    }

    /**
     * Vérifie s'il y a des booking requests en attente
     */
    public function hasPendingBookingRequests(): bool
    {
        return $this->countBookingRequestsByStatus(BookingRequest::STATUS_PENDING) > 0;
    }

    /**
     * Récupère le revenu total des booking requests confirmés
     */
    public function getTotalConfirmedRevenue(): float
    {
        return $this->getBookingRequestsByStatus(BookingRequest::STATUS_CONFIRMED)
            ->sum('total_price') ?? 0;
    }
}
