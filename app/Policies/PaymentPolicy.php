<?php

namespace App\Policies;

use App\Models\BookingRequest;
use App\Models\Client;

class PaymentPolicy
{
    /**
     * Déterminer si l'utilisateur peut créer un payment pour ce booking
     */
    public function create(Client $client, BookingRequest $booking): bool
    {
        // Le client doit être le propriétaire du booking
        return $client->id === $booking->client_id;
    }

    /**
     * Déterminer si l'utilisateur peut voir les payments d'un booking
     */
    public function viewAny(Client $client, BookingRequest $booking): bool
    {
        return $client->id === $booking->client_id;
    }

    /**
     * Déterminer si l'utilisateur peut annuler un payment
     */
    public function cancel(Client $client, BookingRequest $booking): bool
    {
        // Seul le client peut annuler son propre payment
        return $client->id === $booking->client_id;
    }
}
