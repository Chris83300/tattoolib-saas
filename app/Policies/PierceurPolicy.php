<?php

namespace App\Policies;

use App\Models\Piercer;
use App\Models\User;

class PiercerPolicy
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
     * Determine if the user can view the Piercer profile.
     */
    public function view(?User $user, Piercer $Piercer): bool
    {
        // Tout le monde peut voir un profil public
        return true;
    }

    /**
     * Determine if the user can create a Piercer profile.
     */
    public function create(User $user): bool
    {
        // Un utilisateur ne peut créer qu'UN seul profil Piercer
        return !$user->isPiercer();
    }

    /**
     * Determine if the user can update the Piercer profile.
     */
    public function update(User $user, Piercer $Piercer): bool
    {
        // Seul le propriétaire peut modifier son profil
        return $user->id === $Piercer->user_id;
    }

    /**
     * Determine if the user can delete the Piercer profile.
     */
    public function delete(User $user, Piercer $Piercer): bool
    {
        // Seul le propriétaire peut supprimer son profil
        return $user->id === $Piercer->user_id;
    }

    /**
     * Determine if the user can manage working hours.
     */
    public function manageWorkingHours(User $user, Piercer $Piercer): bool
    {
        return $user->id === $Piercer->user_id;
    }

    /**
     * Determine if the user can manage portfolio.
     */
    public function managePortfolio(User $user, Piercer $Piercer): bool
    {
        return $user->id === $Piercer->user_id;
    }

    /**
     * Determine if the user can manage specialization.
     */
    public function manageSpecialization(User $user, Piercer $Piercer): bool
    {
        return $user->id === $Piercer->user_id;
    }

    /**
     * Determine if the user can view booking requests.
     */
    public function viewBookingRequests(User $user, Piercer $Piercer): bool
    {
        return $user->id === $Piercer->user_id;
    }

    /**
     * Determine if the user can manage appointments.
     */
    public function manageAppointments(User $user, Piercer $Piercer): bool
    {
        return $user->id === $Piercer->user_id;
    }

    /**
     * Determine if the user can upgrade to PRO.
     */
    public function upgradeToPro(User $user, Piercer $Piercer): bool
    {
        return $user->id === $Piercer->user_id && !$Piercer->isPro();
    }

    /**
     * Determine if the user can manage compliance.
     */
    public function manageCompliance(User $user, Piercer $Piercer): bool
    {
        return $user->id === $Piercer->user_id;
    }
}
