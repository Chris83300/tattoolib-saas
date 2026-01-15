<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequestRequest extends FormRequest
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
            'description' => 'sometimes|string|min:20|max:15000',
            'estimated_budget' => 'sometimes|numeric|min:0|max:10000',
            'preferred_timeframe' => 'sometimes|in:asap,3-4months,5-6months,6plus',
            'preferred_days' => 'sometimes|array',
            'preferred_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'date_notes' => 'sometimes|nullable|string|max:500',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'description.min' => 'La description doit contenir au moins 20 caractères',
            'description.max' => 'La description ne peut pas dépasser 15000 caractères',
            'estimated_budget.numeric' => 'Le budget doit être un nombre',
            'preferred_timeframe.in' => 'Période non valide',
        ];
    }
}
