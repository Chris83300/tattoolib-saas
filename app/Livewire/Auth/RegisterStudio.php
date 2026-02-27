<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Studio;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;

class RegisterStudio extends AuthLayoutComponent
{
    // Infos user (gérant)
    #[Validate('required|string|max:255')]
    public string $first_name = '';

    #[Validate('required|string|max:255')]
    public string $last_name = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate([
        'required',
        'string',
        'min:8',
        'confirmed',
        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].{8,}$/'
    ])]
    public string $password = '';

    public string $password_confirmation = '';

    // Infos studio
    #[Validate('required|string|max:255')]
    public string $studio_name = '';

    #[Validate('required|numeric|digits:14|unique:studios,siret')]
    public string $siret = '';

    public string $company_name = '';
    public string $company_address = '';
    public bool $siret_valid = false;
    public bool $siret_loading = false;

    #[Validate('required|string|max:255')]
    public string $city = '';

    #[Validate('required|string|max:10')]
    public string $postal_code = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('required|in:artist_direct,studio_managed')]
    public string $payment_mode = 'artist_direct';

    /**
     * Validation automatique du SIRET
     */
    public function updatedSiret($value)
    {
        // Validation automatique si 14 chiffres
        if (strlen($value) === 14 && is_numeric($value)) {
            $this->siret_valid = true;
            $this->company_name = 'Entreprise à vérifier manuellement';
            $this->company_address = 'Adresse à compléter';
        } else {
            $this->siret_valid = false;
        }
    }

    public function register()
    {
        // Forcer validation SIRET si pas encore fait
        if (!$this->siret_valid) {
            $this->addError('siret', 'Veuillez valider votre SIRET avant de continuer.');
            return;
        }

        $this->validate();

        // Créer user (gérant)
        $user = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->first_name . ' ' . $this->last_name, // Pour compatibilité
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'studio',
            'status' => 'pending_verification', // ⚠️ En attente validation admin
        ]);

        // Créer profil studio
        $studio = Studio::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->studio_name,
            'slug' => Str::slug($this->studio_name . '-' . $this->city),
            'user_id' => $user->id, // Gérant
            'siret' => $this->siret,
            'company_name' => $this->company_name,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'address' => $this->company_address,
            'phone' => $this->phone,
            'payment_mode' => $this->payment_mode,
            'subscription_plan' => 'free', // Plan FREE par défaut
            'trial_ends_at' => now()->addDays(14),
        ]);

        // Associer le user au studio
        $user->update(['studio_id' => $studio->id]);

        // Login automatique
        Auth::login($user);

        // Redirection vers page "en attente validation"
        return redirect()->route('studio.pending-verification');
    }

    protected function getView()
    {
        return 'livewire.auth.register-studio-clean';
    }
}
