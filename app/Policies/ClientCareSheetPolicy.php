<?php

namespace App\Policies;

use App\Models\ClientCareSheet;
use App\Models\User;

class ClientCareSheetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isTattooer() || $user->isClient();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClientCareSheet $careSheet): bool
    {
        // Tatoueur : peut voir ses propres fiches
        if ($user->isTattooer() && $careSheet->tattooer_id === $user->tattooer->id) {
            return true;
        }

        // Client : peut voir ses propres fiches
        if ($user->isClient() && $careSheet->client_id === $user->client->id) {
            return true;
        }

        return false;
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
    public function update(User $user, ClientCareSheet $careSheet): bool
    {
        // Tatoueur : peut modifier ses fiches
        if ($user->isTattooer() && $careSheet->tattooer_id === $user->tattooer->id) {
            return true;
        }

        // Client : peut modifier le statut de cicatrisation de ses fiches
        if ($user->isClient() && $careSheet->client_id === $user->client->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClientCareSheet $careSheet): bool
    {
        return $user->isTattooer() && $careSheet->tattooer_id === $user->tattooer->id;
    }

    /**
     * Determine whether the user can add photos.
     */
    public function addPhoto(User $user, ClientCareSheet $careSheet): bool
    {
        // Tatoueur : peut ajouter des photos à ses fiches
        if ($user->isTattooer() && $careSheet->tattooer_id === $user->tattooer->id) {
            return true;
        }

        // Client : peut ajouter des photos de suivi à ses fiches
        if ($user->isClient() && $careSheet->client_id === $user->client->id) {
            return true;
        }

        return false;
    }
}
