<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Enums\BookingRequestStatus;

class Profile extends Component
{
    public $user;
    public $client;
    public $activeTab = 'demandes'; // Onglet par défaut

    // Stats
    public $totalBookings = 0;
    public $pendingBookings = 0;
    public $acceptedBookings = 0;
    public $completedBookings = 0;
    public $rejectedBookings = 0;
    public $cancelledBookings = 0;
    public $upcomingAppointments = 0;

    public function mount()
    {
        // ✅ PAS DE REDIRECTION ICI !
        $this->user = Auth::user();
        $this->client = $this->user->client;

        if ($this->client) {
            $this->loadStats();
        }
    }

    private function loadStats()
    {
        $bookings = $this->client->bookingRequests();

        $this->totalBookings = $bookings->count();
        $this->pendingBookings = (clone $bookings)->where('status', BookingRequestStatus::PENDING->value)->count();

        // Inclure à la fois accepted et awaiting_deposit pour "Acceptées"
        $this->acceptedBookings = (clone $bookings)
            ->whereIn('status', [
                BookingRequestStatus::ACCEPTED->value,
                BookingRequestStatus::DEPOSIT_REQUESTED->value
            ])
            ->count();

        $this->completedBookings = (clone $bookings)->where('status', BookingRequestStatus::COMPLETED->value)->count();
        $this->rejectedBookings = (clone $bookings)->where('status', BookingRequestStatus::REJECTED->value)->count();
        $this->cancelledBookings = (clone $bookings)->where('status', BookingRequestStatus::CANCELLED->value)->count();

        $this->upcomingAppointments = (clone $bookings)
            ->whereIn('status', [
                BookingRequestStatus::ACCEPTED->value,
                BookingRequestStatus::DEPOSIT_REQUESTED->value
            ])
            ->whereNotNull('appointment_datetime')
            ->where('appointment_datetime', '>=', now())
            ->count();
    }

    public function render()
    {
        return view('livewire.client.profile');
    }
}
