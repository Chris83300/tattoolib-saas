<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Profile extends Component
{
    public $user;
    public $client;

    // Stats
    public $totalBookings;
    public $upcomingAppointments;
    public $favoriteArtists;

    public function mount()
    {
        $this->user = auth()->user();
        $this->client = $this->user->client;

        // Calcul stats
        if ($this->client) {
            $this->totalBookings = $this->client->bookingRequests()->count();
            $this->upcomingAppointments = $this->client->bookingRequests()
                ->whereHas('appointment', function($q) {
                    $q->where('appointment_date', '>=', now());
                })->count();
        } else {
            $this->totalBookings = 0;
            $this->upcomingAppointments = 0;
        }

        $this->favoriteArtists = 0; // À implémenter si système favoris
    }

    #[Layout('components.layouts.livewire-site')]
    #[Title('Mon profil - Ink&Pik')]
    public function render()
    {
        return view('livewire.client.profile');
    }
}
