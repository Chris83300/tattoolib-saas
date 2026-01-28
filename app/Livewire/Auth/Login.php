<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            
            // Redirection selon rôle
            $user = Auth::user();
            
            return match($user->role) {
                'client' => redirect()->route('client.profile'),
                'tattooer', 'pierceur' => $user->status === 'pending_verification' 
                    ? redirect()->route('tattooer.pending-verification')
                    : redirect()->route('tattooer.dashboard'),
                'studio_artist' => redirect()->route('tattooer.dashboard'),
                'studio' => redirect('/admin/studio'),
                default => redirect('/'),
            };
        }

        $this->addError('email', 'Email ou mot de passe incorrect.');
    }

    public function render()
    {
        return view('livewire.auth.login-new');
    }
}
