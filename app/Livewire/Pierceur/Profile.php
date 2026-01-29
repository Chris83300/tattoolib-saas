<?php

namespace App\Livewire\Pierceur;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Profile extends Component
{
    public $user;
    public $pierceur;
    public $stats;
    public $bio;
    public $editingBio = false;

    public function mount()
    {
        $this->user = auth()->user();
        $this->pierceur = $this->user->pierceur;
        $this->bio = $this->pierceur->bio ?? '';

        // Stats
        $this->stats = (object) [
            'appointments_this_month' => $this->pierceur->appointments()
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->count(),
            'total_clients' => $this->pierceur->appointments()
                ->distinct('client_id')
                ->count('client_id'),
            'monthly_revenue' => $this->pierceur->appointments()
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->where('status', 'completed')
                ->sum('total_price'),
            'pending_requests' => $this->pierceur->bookingRequests()
                ->where('status', 'pending')
                ->count(),
        ];
    }

    #[Layout('components.layouts.livewire-site')]
    #[Title('Mon profil - Ink&Pik')]
    public function render()
    {
        // Portfolio via Spatie
        $portfolioImages = $this->pierceur->getMedia('portfolio');
        
        return view('livewire.pierceur.profile', [
            'portfolioImages' => $portfolioImages,
        ]);
    }

    public function updateBio()
    {
        $this->validate([
            'bio' => 'nullable|string|max:1000',
        ]);

        $this->pierceur->update([
            'bio' => $this->bio,
        ]);

        $this->editingBio = false;
        session()->flash('bio_success', 'Bio mise à jour avec succès !');
    }

    public function toggleBioEdit()
    {
        $this->editingBio = !$this->editingBio;
        if ($this->editingBio) {
            $this->bio = $this->pierceur->bio ?? '';
        }
    }
}
