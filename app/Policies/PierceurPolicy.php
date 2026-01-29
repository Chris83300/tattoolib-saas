<?php

namespace App\Policies;

use App\Models\Pierceur;
use App\Models\User;

class PierceurPolicy
{
    /**
     * Determine if the user can view any piercers.
     */
    public function viewAny(?User $user): bool
    {
        // Tout le monde peut voir la liste (même sans connexion)
        return true;
    }

    /**
     * Determine if the user can view the pierceur profile.
     */
    public function view(?User $user, Pierceur $pierceur): bool
    {
        // Tout le monde peut voir un profil public
        return true;
    }

    /**
     * Determine if the user can create a pierceur profile.
     */
    public function create(User $user): bool
    {
        // Un utilisateur ne peut créer qu'UN seul profil pierceur
        return !$user->isPierceur();
    }

    /**
     * Determine if the user can update the pierceur profile.
     */
    public function update(User $user, Pierceur $pierceur): bool
    {
        // Seul le propriétaire peut modifier son profil
        return $user->id === $pierceur->user_id;
    }

    /**
     * Determine if the user can delete the pierceur profile.
     */
    public function delete(User $user, Pierceur $pierceur): bool
    {
        // Seul le propriétaire peut supprimer son profil
        return $user->id === $pierceur->user_id;
    }

    /**
     * Determine if the user can manage working hours.
     */
    public function manageWorkingHours(User $user, Pierceur $pierceur): bool
    {
        return $user->id === $pierceur->user_id;
    }

    /**
     * Determine if the user can manage portfolio.
     */
    public function managePortfolio(User $user, Pierceur $pierceur): bool
    {
        return $user->id === $pierceur->user_id;
    }

    /**
     * Determine if the user can manage specialization.
     */
    public function manageSpecialization(User $user, Pierceur $pierceur): bool
    {
        return $user->id === $pierceur->user_id;
    }

    /**
     * Determine if the user can view booking requests.
     */
    public function viewBookingRequests(User $user, Pierceur $pierceur): bool
    {
        return $user->id === $pierceur->user_id;
    }

    /**
     * Determine if the user can manage appointments.
     */
    public function manageAppointments(User $user, Pierceur $pierceur): bool
    {
        return $user->id === $pierceur->user_id;
    }

    /**
     * Determine if the user can upgrade to PRO.
     */
    public function upgradeToPro(User $user, Pierceur $pierceur): bool
    {
        return $user->id === $pierceur->user_id && !$pierceur->isPro();
    }

    /**
     * Determine if the user can manage compliance.
     */
    public function manageCompliance(User $user, Pierceur $pierceur): bool
    {
        return $user->id === $pierceur->user_id;
    }
}
