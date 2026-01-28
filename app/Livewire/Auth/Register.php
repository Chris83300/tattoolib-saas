<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Register extends AuthLayoutComponent
{
    public function selectRole(string $role)
    {
        // Redirection vers composant dédié selon rôle
        return match($role) {
            'client' => redirect()->route('register.client'),
            'tattooer' => redirect()->route('register.tattooer'),
            'pierceur' => redirect()->route('register.pierceur'),
            'studio' => redirect()->route('register.studio'),
        };
    }

    protected function getView()
    {
        return 'auth.register-wrapper';
    }
}
