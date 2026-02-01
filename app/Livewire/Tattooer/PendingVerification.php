<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;

class PendingVerification extends Component
{
    public function mount()
    {
        logger('PendingVerification mount called');
        logger('User authenticated: ' . (auth()->check() ? 'yes' : 'no'));
        if (auth()->check()) {
            logger('User ID: ' . auth()->id());
            logger('User status: ' . auth()->user()->status);
            logger('Has tattooer: ' . (auth()->user()->tattooer ? 'yes' : 'no'));
        }
    }

    public function render()
    {
        return '<div><h1>Page Pending Verification</h1><p>Utilisateur: ' . (auth()->check() ? auth()->user()->name : 'Non connecté') . '</p></div>';
    }
}
