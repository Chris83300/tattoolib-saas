<?php

namespace App\Policies;

use App\Models\Tattooer;
use App\Models\User;

class TattooerPolicy
{
    /**
     * Determine if the user can view any tattooers.
     */
    public function viewAny(?User $user): bool
    {
        // Tout le monde peut voir la liste (même sans connexion)
        return true;
    }

    /**
     * Determine if the user can view the tattooer profile.
     */
    public function view(?User $user, Tattooer $tattooer): bool
    {
        // Tout le monde peut voir un profil public
        return true;
    }

    /**
     * Determine if the user can create a tattooer profile.
     */
    public function create(User $user): bool
    {
        // Un utilisateur ne peut créer qu'UN seul profil tatoueur
        return !$user->isTattooer();
    }

    /**
     * Determine if the user can update the tattooer profile.
     */
    public function update(User $user, Tattooer $tattooer): bool
    {
        // Seul le propriétaire peut modifier son profil
        return $user->id === $tattooer->user_id;
    }

    /**
     * Determine if the user can delete the tattooer profile.
     */
    public function delete(User $user, Tattooer $tattooer): bool
    {
        // Seul le propriétaire peut supprimer son profil
        return $user->id === $tattooer->user_id;
    }

    /**
     * Determine if the user can manage working hours.
     */
    public function manageWorkingHours(User $user, Tattooer $tattooer): bool
    {
        return $user->id === $tattooer->user_id;
    }

    /**
     * Determine if the user can manage portfolio.
     */
    public function managePortfolio(User $user, Tattooer $tattooer): bool
    {
        return $user->id === $tattooer->user_id;
    }
}
