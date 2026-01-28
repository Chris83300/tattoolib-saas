<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Profile extends Component
{
    #[Layout('components.layouts.livewire-site')]
    #[Title('Mon profil public - Ink&Pik')]
    public function render()
    {
        return view('livewire.tattooer.profile');
    }
}
