<?php

namespace App\Policies;

use App\Models\BookingRequest;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Piercer;

class BookingRequestPolicy
{
    /**
     * Voir une demande de réservation
     */
    public function view(User $user, BookingRequest $bookingRequest): bool
    {
        // Client propriétaire
        if ($user->isClient() && $bookingRequest->client_id === $user->client?->id) {
            return true;
        }

        // Tatoueur/Piercer destinataire
        if ($user->isTattooer() || $user->isPiercer()) {
            $profile = $user->isTattooer() ? $user->tattooer : $user->Piercer;

            return $bookingRequest->bookable_id === $profile->id
                && $bookingRequest->bookable_type === get_class($profile);
        }

        // Studio artist
        if ($user->isStudioArtist()) {
            return $bookingRequest->bookable->user_id === $user->id;
        }

        // Admin
        return $user->isAdmin();
    }

    /**
     * Créer une demande (client uniquement)
     */
    public function create(User $user): bool
    {
        return $user->isClient()
            && $user->client !== null
            && !$user->client->is_blacklisted;
    }

    /**
     * Accepter/rejeter une demande (artiste uniquement)
     */
    public function update(User $user, BookingRequest $bookingRequest): bool
    {
        if (!($user->isTattooer() || $user->isPiercer() || $user->isStudioArtist())) {
            return false;
        }

        // Studio artist
        if ($user->isStudioArtist()) {
            return $bookingRequest->bookable->user_id === $user->id;
        }

        // Tattooer/Piercer
        $profile = $user->isTattooer() ? $user->tattooer : $user->Piercer;

        return $bookingRequest->bookable_id === $profile->id
            && $bookingRequest->bookable_type === get_class($profile);
    }

    /**
     * Annuler une demande
     */
    public function cancel(User $user, BookingRequest $bookingRequest): bool
    {
        // Client peut annuler sa propre demande avant confirmation
        if ($user->isClient()
            && $bookingRequest->client_id === $user->client?->id
            && in_array($bookingRequest->status, [
                BookingRequest::STATUS_PENDING,
                BookingRequest::STATUS_ACCEPTED,
                BookingRequest::STATUS_AWAITING_DEPOSIT,
                BookingRequest::STATUS_DEPOSIT_PAID,
                BookingRequest::STATUS_DESIGN_SENT,
            ])
        ) {
            return true;
        }

        // Artiste peut annuler à tout moment
        if ($user->isTattooer() || $user->isPiercer() || $user->isStudioArtist()) {
            if ($user->isStudioArtist()) {
                return $bookingRequest->bookable->user_id === $user->id
                    && in_array($bookingRequest->status, [
                        BookingRequest::STATUS_ACCEPTED,
                        BookingRequest::STATUS_AWAITING_DEPOSIT,
                        BookingRequest::STATUS_DEPOSIT_PAID,
                        BookingRequest::STATUS_DESIGN_SENT,
                    ]);
            }

            $profile = $user->isTattooer() ? $user->tattooer : $user->Piercer;

            return $bookingRequest->bookable_id === $profile->id
                && $bookingRequest->bookable_type === get_class($profile)
                && in_array($bookingRequest->status, [
                    BookingRequest::STATUS_ACCEPTED,
                    BookingRequest::STATUS_AWAITING_DEPOSIT,
                    BookingRequest::STATUS_DEPOSIT_PAID,
                    BookingRequest::STATUS_DESIGN_SENT,
                ]);
        }

        return false;
    }

    /**
     * Payer l'acompte (client uniquement)
     */
    public function payDeposit(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->isClient()
            && $bookingRequest->client_id === $user->client?->id
            && $bookingRequest->status === BookingRequest::STATUS_AWAITING_DEPOSIT;
    }

    /**
     * Envoyer un design (artiste avec acompte payé)
     */
    public function sendDesign(User $user, BookingRequest $bookingRequest): bool
    {
        if (!($user->isTattooer() || $user->isPiercer() || $user->isStudioArtist())) {
            return false;
        }

        // Studio artist
        if ($user->isStudioArtist()) {
            return $bookingRequest->bookable->user_id === $user->id
                && in_array($bookingRequest->status, [
                    BookingRequest::STATUS_DEPOSIT_PAID,
                    BookingRequest::STATUS_DESIGN_SENT,
                ]);
        }

        // Tattooer/Piercer
        $profile = $user->isTattooer() ? $user->tattooer : $user->Piercer;

        return $bookingRequest->bookable_id === $profile->id
            && $bookingRequest->bookable_type === get_class($profile)
            && in_array($bookingRequest->status, [
                BookingRequest::STATUS_DEPOSIT_PAID,
                BookingRequest::STATUS_DESIGN_SENT
            ]);
    }

    /**
     * Confirmer RDV (artiste uniquement)
     */
    public function confirmAppointment(User $user, BookingRequest $bookingRequest): bool
    {
        // Les deux parties peuvent confirmer
        if ($user->isClient() && $bookingRequest->client_id === $user->client?->id) {
            return $bookingRequest->status === BookingRequest::STATUS_DESIGN_SENT;
        }

        if (!($user->isTattooer() || $user->isPiercer() || $user->isStudioArtist())) {
            return false;
        }

        // Studio artist
        if ($user->isStudioArtist()) {
            return $bookingRequest->bookable->user_id === $user->id
                && $bookingRequest->status === BookingRequest::STATUS_DESIGN_SENT;
        }

        // Tattooer/Piercer
        $profile = $user->isTattooer() ? $user->tattooer : $user->Piercer;

        return $bookingRequest->bookable_id === $profile->id
            && $bookingRequest->bookable_type === get_class($profile)
            && $bookingRequest->status === BookingRequest::STATUS_DESIGN_SENT;
    }

    /**
     * Accepter une demande
     */
    public function accept(User $user, BookingRequest $bookingRequest): bool
    {
        return $this->update($user, $bookingRequest)
            && $bookingRequest->status === BookingRequest::STATUS_PENDING;
    }

    /**
     * Rejeter une demande
     */
    public function reject(User $user, BookingRequest $bookingRequest): bool
    {
        return $this->update($user, $bookingRequest)
            && $bookingRequest->status === BookingRequest::STATUS_PENDING;
    }

    /**
     * Demander un acompte
     */
    public function requestDeposit(User $user, BookingRequest $bookingRequest): bool
    {
        if (!($user->isTattooer() || $user->isPiercer() || $user->isStudioArtist())) {
            return false;
        }

        // Studio artist
        if ($user->isStudioArtist()) {
            return $bookingRequest->bookable->user_id === $user->id
                && $bookingRequest->status === BookingRequest::STATUS_ACCEPTED;
        }

        // Tattooer/Piercer
        $profile = $user->isTattooer() ? $user->tattooer : $user->Piercer;

        return $bookingRequest->bookable_id === $profile->id
            && $bookingRequest->bookable_type === get_class($profile)
            && $bookingRequest->status === BookingRequest::STATUS_ACCEPTED;
    }
}
