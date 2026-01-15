<?php

namespace App\Policies;

use App\Models\BookingRequest;
use App\Models\User;

class BookingRequestPolicy
{
    /**
     * Determine if the user can view the booking request.
     */
    public function view(User $user, BookingRequest $bookingRequest): bool
    {
        if ($user->isClient()) {
            return $bookingRequest->client_id === $user->client->id;
        }

        if ($user->isTattooer()) {
            return $bookingRequest->tattooer_id === $user->tattooer->id;
        }

        return false;
    }

    /**
     * Determine if the user can create a booking request.
     */
    public function create(User $user): bool
    {
        return $user->isClient() && !$user->client->is_blacklisted;
    }

    /**
     * Determine if the user can accept the booking request.
     */
    public function accept(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->isTattooer()
            && $bookingRequest->tattooer_id === $user->tattooer->id
            && $bookingRequest->status === BookingRequest::STATUS_PENDING;
    }

    /**
     * Determine if the user can reject the booking request.
     */
    public function reject(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->isTattooer()
            && $bookingRequest->tattooer_id === $user->tattooer->id
            && $bookingRequest->status === BookingRequest::STATUS_PENDING;
    }

    /**
     * Determine if the user can request a deposit.
     */
    public function requestDeposit(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->isTattooer()
            && $bookingRequest->tattooer_id === $user->tattooer->id
            && $bookingRequest->status === BookingRequest::STATUS_ACCEPTED;
    }

    /**
     * Determine if the user can send a design.
     */
    public function sendDesign(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->isTattooer()
            && $bookingRequest->tattooer_id === $user->tattooer->id
            && in_array($bookingRequest->status, [
                BookingRequest::STATUS_DEPOSIT_PAID,
                BookingRequest::STATUS_DESIGN_SENT,
            ]);
    }

    /**
     * Determine if the user can confirm the appointment.
     */
    public function confirmAppointment(User $user, BookingRequest $bookingRequest): bool
    {
        // Les deux parties peuvent confirmer
        if ($user->isClient() && $bookingRequest->client_id === $user->client->id) {
            return $bookingRequest->status === BookingRequest::STATUS_DESIGN_SENT;
        }

        if ($user->isTattooer() && $bookingRequest->tattooer_id === $user->tattooer->id) {
            return $bookingRequest->status === BookingRequest::STATUS_DESIGN_SENT;
        }

        return false;
    }

    /**
     * Determine if the user can cancel the booking request.
     */
    public function cancel(User $user, BookingRequest $bookingRequest): bool
    {
        // Les deux parties peuvent annuler
        if ($user->isClient() && $bookingRequest->client_id === $user->client->id) {
            return in_array($bookingRequest->status, [
                BookingRequest::STATUS_PENDING,
                BookingRequest::STATUS_ACCEPTED,
                BookingRequest::STATUS_AWAITING_DEPOSIT,
                BookingRequest::STATUS_DEPOSIT_PAID,
                BookingRequest::STATUS_DESIGN_SENT,
            ]);
        }

        if ($user->isTattooer() && $bookingRequest->tattooer_id === $user->tattooer->id) {
            return in_array($bookingRequest->status, [
                BookingRequest::STATUS_ACCEPTED,
                BookingRequest::STATUS_AWAITING_DEPOSIT,
                BookingRequest::STATUS_DEPOSIT_PAID,
                BookingRequest::STATUS_DESIGN_SENT,
            ]);
        }

        return false;
    }
}
