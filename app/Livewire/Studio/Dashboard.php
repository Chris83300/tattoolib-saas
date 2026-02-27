<?php

namespace App\Livewire\Studio;

use App\Models\Studio;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.studio')]
class Dashboard extends Component
{
    public ?Studio $studio = null;
    public $artists;
    public int $artistCount = 0;
    public float $monthlyPrice = 0;
    public int $pendingRequests = 0;
    public int $todayAppointments = 0;

    public function mount(): void
    {
        $this->studio = auth()->user()->studio;
        abort_unless($this->studio, 403, 'Profil studio non trouvé');

        $this->artists = $this->studio->studioArtists()->with('user')->where('is_active', true)->get();
        $this->artistCount = $this->artists->count();
        $this->monthlyPrice = $this->studio->monthlyPrice();
    }

    public function render()
    {
        return view('livewire.studio.dashboard');
    }
}
