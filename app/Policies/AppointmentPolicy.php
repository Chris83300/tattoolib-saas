<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine if the user can view the appointment.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->isClient()) {
            return $appointment->client_id === $user->client->id;
        }

        if ($user->isTattooer()) {
            return $appointment->bookable_type === \App\Models\Tattooer::class
                && $appointment->bookable_id === $user->tattooer->user_id;
        }

        return false;
    }

    /**
     * Determine if the user can confirm completion of the appointment.
     */
    public function confirm(User $user, Appointment $appointment): bool
    {
        return $user->isTattooer()
            && $appointment->bookable_type === \App\Models\Tattooer::class
            && $appointment->bookable_id === $user->tattooer->user_id
            && $appointment->isPast()
            && !$appointment->tattooer_confirmation_status;
    }

    /**
     * Determine if the user can report an issue with the appointment.
     */
    public function reportIssue(User $user, Appointment $appointment): bool
    {
        return $user->isClient()
            && $appointment->client_id === $user->client->id
            && $appointment->isPast()
            && !$appointment->client_reported_issue;
    }

    /**
     * Determine if the user can cancel the appointment.
     */
    public function cancel(User $user, Appointment $appointment): bool
    {
        if (!$appointment->isCancellable()) {
            return false;
        }
        if ($user->isClient() && $appointment->client?->user_id === $user->id) {
        return true;
    }

        if ($user->isClient() && $appointment->client_id === $user->client->id) {
            return true;
        }

        if ($user->isTattooer() && $appointment->user_id === $user->id) {
            return true;
        }

        return false;
    }
}
