<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
        $this->pendingBookings = (clone $bookings)->where('status', 'pending')->count();

        // Inclure à la fois accepted et awaiting_deposit pour "Acceptées"
        $this->acceptedBookings = (clone $bookings)
            ->whereIn('status', ['accepted', 'awaiting_deposit'])
            ->count();

        $this->completedBookings = (clone $bookings)->where('status', 'completed')->count();

        $this->upcomingAppointments = (clone $bookings)
            ->whereIn('status', ['accepted', 'awaiting_deposit'])
            ->whereNotNull('appointment_datetime')
            ->where('appointment_datetime', '>=', now())
            ->count();
    }

    public function render()
    {
        return view('livewire.client.profile');
    }
}
