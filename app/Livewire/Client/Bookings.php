<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Bookings extends Component
{
    #[Layout('components.layouts.livewire-site')]
    #[Title('Mes réservations - Ink&Pik')]
    public function render()
    {
        $bookings = auth()->user()->client->bookingRequests()
            ->with(['tattooer', 'appointment'])
            ->latest()
            ->get();

        return view('livewire.client.bookings', [
            'bookings' => $bookings
        ]);
    }
}
