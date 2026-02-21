<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Piercer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;

class RegisterPiercer extends AuthLayoutComponent
{
    // Infos user
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    // Infos pro
    #[Validate('required|digits:14|unique:piercers,siret')]
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

    /**
     * Validation SIRET via API gouvernementale (GRATUITE)
     */
    public function validateSiret()
    {
        $this->validate(['siret' => 'required|digits:14']);

        $this->siret_loading = true;

        try {
            $response = Http::timeout(10)
                ->get("https://entreprise.data.gouv.fr/api/sirene/v3/etablissements/{$this->siret}");

            if ($response->successful()) {
                $data = $response->json();
                $etablissement = $data['etablissement'] ?? null;

                if ($etablissement) {
                    // Extraction données
                    $uniteLegale = $etablissement['uniteLegale'] ?? [];
                    $adresse = $etablissement['adresseEtablissement'] ?? [];

                    $this->company_name = $uniteLegale['denominationUniteLegale']
                        ?? $uniteLegale['nomUniteLegale']
                        ?? 'Entreprise individuelle';

                    // Adresse complète
                    $this->company_address = trim(
                        ($adresse['numeroVoieEtablissement'] ?? '') . ' ' .
                        ($adresse['typeVoieEtablissement'] ?? '') . ' ' .
                        ($adresse['libelleVoieEtablissement'] ?? '')
                    );

                    // Pré-remplir si vides
                    if (empty($this->city)) {
                        $this->city = $adresse['libelleCommuneEtablissement'] ?? '';
                    }
                    if (empty($this->postal_code)) {
                        $this->postal_code = $adresse['codePostalEtablissement'] ?? '';
                    }

                    $this->siret_valid = true;

                    session()->flash('siret_success', 'SIRET valide ! Entreprise reconnue.');
                }
            } else {
                $this->siret_valid = false;
                $this->addError('siret', 'SIRET non reconnu dans la base gouvernementale.');
            }
        } catch (\Exception $e) {
            $this->siret_valid = false;
            $this->addError('siret', 'Erreur lors de la vérification du SIRET. Réessayez.');
        } finally {
            $this->siret_loading = false;
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

        // Créer user
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'Piercer',
            'status' => 'pending_verification', // ⚠️ En attente validation admin
        ]);

        // Créer profil piercer
        Piercer::create([
            'user_id' => $user->id,
            'siret' => $this->siret,
            'company_name' => $this->company_name,
            'slug' => Str::slug($this->name . '-' . $this->city),
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'address' => $this->company_address,
            'phone' => $this->phone,
            'subscription_plan' => 'free', // Plan FREE par défaut
            'has_compliance_badge' => false, // Pas encore de badge
        ]);

        // Login automatique
        auth()->login($user);

        // Redirection vers page "en attente validation"
        return redirect()->route('Piercer.pending-verification');
    }

    protected function getView()
    {
        return 'livewire.auth.register-Piercer-clean';
    }
}
