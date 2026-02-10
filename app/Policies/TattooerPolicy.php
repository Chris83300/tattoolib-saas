<?php

namespace App\Policies;

use App\Models\Tattooer;
use App\Models\User;

class TattooerPolicy
{
    /**
     * Voir la liste des tatoueurs
     */
    public function viewAny(?User $user): bool
    {
        // Tout le monde peut voir la liste (même sans connexion)
        return true;
    }

    /**
     * Voir profil (public)
     */
    public function view(?User $user, Tattooer $tattooer): bool
    {
        // Profil public si vérifié et actif
        if ($tattooer->siret_verified && $tattooer->status === 'active') {
            return true;
        }

        // Propriétaire peut toujours voir son profil
        if ($user && $user->isTattooer() && $user->tattooer->id === $tattooer->id) {
            return true;
        }

        // Admin
        return $user && $user->isAdmin();
    }

    /**
     * Créer un profil tatoueur
     */
    public function create(User $user): bool
    {
        // Un utilisateur ne peut créer qu'UN seul profil tatoueur
        return !$user->isTattooer();
    }

    /**
     * Modifier profil
     */
    public function update(User $user, Tattooer $tattooer): bool
    {
        // Admin peut modifier tous les profils
        if ($user->isAdmin()) {
            return true;
        }

        // Seul le propriétaire peut modifier son profil
        return $user->isTattooer() && $user->tattooer->id === $tattooer->id;
    }

    /**
     * Supprimer profil
     */
    public function delete(User $user, Tattooer $tattooer): bool
    {
        // Admin peut supprimer tous les profils
        if ($user->isAdmin()) {
            return true;
        }

        // Seul le propriétaire peut supprimer son profil
        return $user->isTattooer() && $user->tattooer->id === $tattooer->id;
    }

    /**
     * Upload portfolio
     */
    public function uploadPortfolio(User $user, Tattooer $tattooer): bool
    {
        return $user->isTattooer() && $user->tattooer->id === $tattooer->id;
    }

    /**
     * Gérer horaires
     */
    public function manageWorkingHours(User $user, Tattooer $tattooer): bool
    {
        return $user->isTattooer() && $user->tattooer->id === $tattooer->id;
    }

    /**
     * Gérer portfolio
     */
    public function managePortfolio(User $user, Tattooer $tattooer): bool
    {
        return $user->isTattooer() && $user->tattooer->id === $tattooer->id;
    }

    /**
     * Upgrade vers PRO
     */
    public function upgrade(User $user, Tattooer $tattooer): bool
    {
        return $user->isTattooer()
            && $user->tattooer->id === $tattooer->id
            && !$tattooer->is_subscribed;
    }

    /**
     * Voir les statistiques
     */
    public function viewStats(User $user, Tattooer $tattooer): bool
    {
        return $user->isTattooer() && $user->tattooer->id === $tattooer->id;
    }

    /**
     * Gérer les disponibilités
     */
    public function manageAvailability(User $user, Tattooer $tattooer): bool
    {
        return $user->isTattooer() && $user->tattooer->id === $tattooer->id;
    }
}
