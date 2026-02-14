<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class Bookings extends Component
{
    #[Layout('components.layouts.livewire-site')]
    #[Title('Mes réservations - Ink&Pik')]
    public function render()
    {
        $client = Auth::user()->profile;

        if (!$client) {
            // Si l'utilisateur n'a pas de profil client, retourner une vue vide ou un message
            return view('livewire.client.bookings', [
                'bookings' => collect()
            ]);
        }

        $bookings = $client->bookingRequests()
            ->with(['bookable', 'appointment'])
            ->latest()
            ->get();

        return view('livewire.client.bookings', [
            'bookings' => $bookings
        ]);
    }
}
