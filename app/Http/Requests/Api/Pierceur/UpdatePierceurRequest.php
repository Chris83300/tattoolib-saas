<?php

namespace App\Http\Requests\Api\Pierceur;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePierceurRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('pierceur'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'bio' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'string', 'max:10'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            
            // Réseaux sociaux
            'instagram' => ['sometimes', 'nullable', 'string', 'max:100'],
            'facebook' => ['sometimes', 'nullable', 'string', 'max:100'],
            'tiktok' => ['sometimes', 'nullable', 'string', 'max:100'],
            'website' => ['sometimes', 'nullable', 'url', 'max:255'],
            
            // Paramètres par défaut
            'minimum_deposit' => ['sometimes', 'numeric', 'min:0', 'max:1000'],
            'default_deposit_rate' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'default_client_payment_deadline_days' => ['sometimes', 'integer', 'min:1', 'max:30'],
            'default_design_versions_included' => ['sometimes', 'integer', 'min:1', 'max:10'],
            
            // Délais d'attente
            'weekday_wait_days' => ['sometimes', 'integer', 'min:0', 'max:365'],
            'weekend_wait_days' => ['sometimes', 'integer', 'min:0', 'max:365'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'bio.max' => 'La bio ne peut pas dépasser 2000 caractères.',
            'email.email' => 'L\'email doit être valide.',
            'website.url' => 'Le site web doit être une URL valide.',
            'minimum_deposit.numeric' => 'L\'acompte minimum doit être un nombre.',
            'default_deposit_rate.numeric' => 'Le taux d\'acompte doit être un nombre.',
            'default_deposit_rate.max' => 'Le taux d\'acompte ne peut pas dépasser 100%.',
        ];
    }
}
