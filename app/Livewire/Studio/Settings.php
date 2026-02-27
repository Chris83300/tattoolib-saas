<?php

namespace App\Livewire\Studio;

use App\Models\Studio;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.studio')]
class Settings extends Component
{
    public ?Studio $studio = null;

    public function mount(): void
    {
        $this->studio = auth()->user()->studio;
        abort_unless($this->studio, 403, 'Profil studio non trouvé');
    }

    public function render()
    {
        return view('livewire.studio.settings');
    }
}
