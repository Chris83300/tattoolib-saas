<?php

namespace App\Policies;

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Déterminer si l'utilisateur peut créer un payment pour ce booking
     */
    public function create(User $user): bool
    {
        // Pour la création, on vérifie juste si l'utilisateur est un client
        return $user->isClient();
    }

    /**
     * Déterminer si l'utilisateur peut voir les payments d'un booking
     */
    public function viewAny(User $user): bool
    {
        // Pour l'admin, on autorise tout
        if ($user->isAdmin()) {
            return true;
        }

        // Pour les autres, on vérifie s'ils ont des payments
        return true; // Simplifié pour le dashboard
    }

    /**
     * Déterminer si l'utilisateur peut annuler un payment
     */
    public function cancel(User $user, BookingRequest $booking): bool
    {
        // Seul le client peut annuler son propre payment
        return $user->id === $booking->client_id;
    }
}
