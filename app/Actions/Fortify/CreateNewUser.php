<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\Studio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'user_type' => ['required', 'string', 'in:client,tattooer,studio'],
            'studio_name' => ['required_if:user_type,studio', 'string', 'max:255'],
        ])->validate();

        // Créer l'utilisateur de base
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'is_active' => true,
        ]);

        // Créer l'enregistrement spécifique selon le rôle
        switch ($input['user_type']) {
            case 'client':
                Client::create([
                    'user_id' => $user->id,
                    'name' => $input['name'],
                    'email' => $input['email'],
                ]);
                break;

            case 'tattooer':
                Tattooer::create([
                    'user_id' => $user->id,
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'studio_name' => $input['studio_name'] ?? null,
                    'current_plan' => 'free',
                    'is_subscribed' => false,
                    'minimum_deposit' => 50.00,
                    'default_deposit_rate' => 20.0,
                    'default_client_payment_deadline_days' => 2,
                    'default_tattooer_design_deadline_days' => 7,
                    'default_design_versions_included' => 2,
                    'weekday_wait_days' => 7,
                    'weekend_wait_days' => 14,
                    'is_decision_maker' => true,
                    'compliance_status' => 'pending',
                ]);
                break;

            case 'studio':
                $studio = Studio::create([
                    'user_id' => $user->id,
                    'name' => $input['studio_name'],
                    'email' => $input['email'],
                    'current_plan' => 'free',
                    'is_subscribed' => false,
                    'is_active' => true,
                ]);

                // Mettre à jour l'utilisateur comme propriétaire du studio
                $user->update([
                    'studio_id' => $studio->id,
                    'is_studio_owner' => true,
                ]);
                break;
        }

        return $user;
    }
}
