<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'bookable_type' => 'required|string|in:' . \App\Models\Tattooer::class,
            'bookable_id' => 'required|exists:tattooers,id',
            'tattoo_size' => 'required|string|max:200',
            'body_zone' => 'required|string|max:500',
            'description' => 'required|string|min:20|max:15000',
            'estimated_budget' => 'nullable|numeric|min:0|max:10000',
            'preferred_timeframe' => 'nullable|in:asap,3-4months,5-6months,6plus',
            'preferred_days' => 'nullable|array',
            'preferred_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'date_notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'bookable_type.required' => 'Veuillez sélectionner un type de prestataire',
            'bookable_type.in' => 'Type de prestataire non valide',
            'bookable_id.required' => 'Veuillez sélectionner un tatoueur',
            'bookable_id.exists' => 'Ce tatoueur n\'existe pas',
            'tattoo_size.required' => 'La taille du tatouage est requise',
            'body_zone.required' => 'La zone du corps est requise',
            'description.required' => 'Une description est requise',
            'description.min' => 'La description doit contenir au moins 20 caractères',
            'description.max' => 'La description ne peut pas dépasser 15000 caractères',
            'estimated_budget.numeric' => 'Le budget doit être un nombre',
            'estimated_budget.max' => 'Le budget ne peut pas dépasser 10000€',
            'preferred_timeframe.in' => 'Période non valide',
            'preferred_days.*.in' => 'Jour non valide',
        ];
    }
}
