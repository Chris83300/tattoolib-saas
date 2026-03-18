<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTattooerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !auth()->check(); // Uniquement pour les non-authentifiés
    }

    public function rules(): array
    {
        return [
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'pseudo'      => 'nullable|string|max:50|unique:users,pseudo',
            'email'       => 'required|email|unique:users,email',
            'siret'       => 'required|numeric|digits:14|unique:tattooers,siret',
            'city'        => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'phone'       => 'nullable|string|max:20',
            'plan'        => 'required|in:starter,pro',
            'password'    => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].{8,}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.regex'     => 'Le mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (@$!%*?&).',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'siret.numeric'      => 'Le SIRET doit contenir uniquement des chiffres.',
            'siret.digits'       => 'Le SIRET doit contenir exactement 14 chiffres.',
            'siret.unique'       => 'Ce numéro SIRET est déjà utilisé.',
            'plan.in'            => 'Le plan sélectionné n\'est pas valide.',
        ];
    }
}
