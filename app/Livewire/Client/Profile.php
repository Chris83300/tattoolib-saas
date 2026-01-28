<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Profile extends Component
{
    public $user;
    public $client;

    public function mount()
    {
        $this->user = auth()->user();
        // Simplification pour éviter les erreurs
        $this->client = $this->user->client ?? (object) [
            'first_name' => explode(' ', $this->user->name)[0] ?? 'Client',
            'last_name' => explode(' ', $this->user->name)[1] ?? '',
        ];
    }

    #[Layout('components.layouts.livewire-profile')]
    #[Title('Mon profil - Ink&Pik')]
    public function render()
    {
        return view('livewire.client.profile');
    }
}
