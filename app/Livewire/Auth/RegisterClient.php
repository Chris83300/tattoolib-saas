<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;

class RegisterClient extends AuthLayoutComponent
{
    #[Validate('required|string|max:255')]
    public string $first_name = '';

    #[Validate('required|string|max:255')]
    public string $last_name = '';

    #[Validate('nullable|string|max:50|unique:users,pseudo')]
    public string $pseudo = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    #[Validate('required')]
    public string $password_confirmation = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('accepted')]
    public bool $acceptCgu = false;

    #[Validate('accepted')]
    public bool $acceptPrivacy = false;

    public function register()
    {
        $this->validate();

        // Créer user
        $now = now();
        $user = User::create([
            'name' => $this->first_name . ' ' . $this->last_name,
            'pseudo' => $this->pseudo,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'client',
            'status' => 'active', // Client directement actif
            'cgu_accepted_at' => $now,
            'privacy_accepted_at' => $now,
        ]);

        // Créer profil client
        Client::create([
            'user_id' => $user->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
        ]);

        // Login automatique
        auth()->login($user);

        // Redirection
        return redirect()->route('client.profile');
    }

    protected function getView()
    {
        return 'livewire.auth.register-client-clean';
    }
}
