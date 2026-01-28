<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class BookingRequests extends Component
{
    #[Layout('components.layouts.livewire-site')]
    #[Title('Demandes de réservation - Ink&Pik')]
    public function render()
    {
        $bookingRequests = auth()->user()->tattooer->bookingRequests()
            ->with(['client'])
            ->latest()
            ->get();

        return view('livewire.tattooer.booking-requests', [
            'bookingRequests' => $bookingRequests
        ]);
    }
}
