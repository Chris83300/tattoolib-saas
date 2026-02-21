<?php

namespace App\Livewire\Piercer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Profile extends Component
{
    public $user;
    public $Piercer;
    public $stats;
    public $bio;
    public $editingBio = false;

    public function mount()
    {
        $this->user = auth()->user();
        $this->Piercer = $this->user->Piercer;
        $this->bio = $this->Piercer->bio ?? '';

        // Stats
        $this->stats = (object) [
            'appointments_this_month' => $this->Piercer->appointments()
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->count(),
            'total_clients' => $this->Piercer->appointments()
                ->distinct('client_id')
                ->count('client_id'),
            'monthly_revenue' => $this->Piercer->appointments()
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->where('status', 'completed')
                ->sum('total_price'),
            'pending_requests' => $this->Piercer->bookingRequests()
                ->where('status', 'pending')
                ->count(),
        ];
    }

    #[Layout('components.layouts.livewire-site')]
    #[Title('Mon profil - Ink&Pik')]
    public function render()
    {
        // Portfolio via Spatie
        $portfolioImages = $this->Piercer->getMedia('portfolio');

        return view('livewire.Piercer.profile', [
            'portfolioImages' => $portfolioImages,
        ]);
    }

    public function updateBio()
    {
        $this->validate([
            'bio' => 'nullable|string|max:1000',
        ]);

        $this->Piercer->update([
            'bio' => $this->bio,
        ]);

        $this->editingBio = false;
        session()->flash('bio_success', 'Bio mise à jour avec succès !');
    }

    public function toggleBioEdit()
    {
        $this->editingBio = !$this->editingBio;
        if ($this->editingBio) {
            $this->bio = $this->Piercer->bio ?? '';
        }
    }
}
