<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Dashboard extends Component
{
    public $user;
    public $tattooer;
    public $stats;

    public function mount()
    {
        $this->user = auth()->user();
        $this->tattooer = $this->user->tattooer;

        // Stats rapides
        $this->stats = [
            'pending_requests' => 0, // À implémenter avec les modèles
            'upcoming_appointments' => 0,
            'unread_messages' => 0,
        ];
    }

    #[Layout('components.layouts.livewire-site')]
    #[Title('Mon espace pro - Ink&Pik')]
    public function render()
    {
        return view('livewire.tattooer.dashboard');
    }
}
