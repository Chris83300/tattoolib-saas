<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.livewire-site')]
class PendingVerification extends Component
{
    public $user;
    public $tattooer;

    public function mount()
    {
        $this->user = auth()->user();
        $this->tattooer = $this->user->tattooer;

        // Rediriger si déjà validé
        if ($this->user->status === 'active') {
            return redirect()->route('tattooer.dashboard');
        }
    }

    public function render()
    {
        return view('livewire.tattooer.pending-verification');
    }
}
