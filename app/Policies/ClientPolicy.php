<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;

class ClientPolicy
{
    /**
     * Voir profil client
     */
    public function view(User $user, Client $client): bool
    {
        // Propriétaire
        if ($user->isClient() && $user->client?->id === $client->id) {
            return true;
        }
        
        // Artiste avec booking actif
        if ($user->isTattooer() || $user->isPierceur()) {
            $profile = $user->isTattooer() ? $user->tattooer : $user->pierceur;
            
            return $client->bookingRequests()
                ->where('bookable_id', $profile->id)
                ->where('bookable_type', get_class($profile))
                ->exists();
        }
        
        // Studio artist
        if ($user->isStudioArtist()) {
            return $client->bookingRequests()
                ->where('bookable_id', $user->studioArtist->id)
                ->where('bookable_type', get_class($user->studioArtist))
                ->exists();
        }
        
        // Admin
        return $user->isAdmin();
    }
    
    /**
     * Modifier profil
     */
    public function update(User $user, Client $client): bool
    {
        return $user->isClient() && $user->client?->id === $client->id;
    }
    
    /**
     * Supprimer profil
     */
    public function delete(User $user, Client $client): bool
    {
        // Admin peut supprimer tous les profils
        if ($user->isAdmin()) {
            return true;
        }
        
        // Seul le propriétaire peut supprimer son profil
        return $user->isClient() && $user->client?->id === $client->id;
    }
    
    /**
     * Voir les demandes de réservation
     */
    public function viewBookingRequests(User $user, Client $client): bool
    {
        return $user->isClient() && $user->client?->id === $client->id;
    }
    
    /**
     * Créer une demande de réservation
     */
    public function createBookingRequest(User $user, Client $client): bool
    {
        return $user->isClient() 
            && $user->client?->id === $client->id
            && !$client->is_blacklisted;
    }
}
