<?php

namespace App\Policies;

use App\Models\Availability;
use App\Models\User;

class AvailabilityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isTattooer();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Availability $availability): bool
    {
        return $user->isTattooer() && $availability->tattooer_id === $user->tattooer->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isTattooer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Availability $availability): bool
    {
        return $user->isTattooer() && $availability->tattooer_id === $user->tattooer->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Availability $availability): bool
    {
        return $user->isTattooer() && $availability->tattooer_id === $user->tattooer->id;
    }
}
