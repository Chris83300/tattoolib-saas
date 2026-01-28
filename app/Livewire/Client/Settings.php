<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Settings extends Component
{
    #[Layout('components.layouts.livewire-site')]
    #[Title('Paramètres - Ink&Pik')]
    public function render()
    {
        return view('livewire.client.settings');
    }
}
