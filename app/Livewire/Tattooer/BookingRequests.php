<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

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

        $pendingRequests = auth()->user()->tattooer->bookingRequests()
            ->where('status', 'pending')
            ->count();

        return view('livewire.tattooer.booking-requests', [
            'bookingRequests' => $bookingRequests,
            'pendingRequests' => $pendingRequests
        ]);
    }

    #[On('open-booking-modal')]
    public function openBookingModal(string $date, string $period, int $bookingRequestId): void
    {
        $this->dispatch('open-booking-modal', [
            'date' => $date,
            'period' => $period,
            'bookingRequestId' => $bookingRequestId,
        ]);
    }
}
