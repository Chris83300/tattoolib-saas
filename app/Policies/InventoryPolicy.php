<?php

namespace App\Policies;

use App\Models\InventoryItem;
use App\Models\User;

class InventoryPolicy
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
    public function view(User $user, InventoryItem $item): bool
    {
        return $user->isTattooer() && $item->user_id === $user->id;
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
    public function update(User $user, InventoryItem $item): bool
    {
        return $user->isTattooer() && $item->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InventoryItem $item): bool
    {
        return $user->isTattooer() && $item->user_id === $user->id;
    }

    /**
     * Determine whether the user can manage stock movements.
     */
    public function manageMovements(User $user, InventoryItem $item): bool
    {
        return $user->isTattooer() && $item->user_id === $user->id;
    }
}
