<?php

namespace App\Livewire\Studio;

use Livewire\Component;

class Profile extends Component
{
    public function mount()
    {
        return $this->redirect(route('studio.settings'), navigate: true);
    }

    public function render()
    {
        return view('livewire.studio.profile');
    }
}
