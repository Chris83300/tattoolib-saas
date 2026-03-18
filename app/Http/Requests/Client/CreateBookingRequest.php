<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->client !== null;
    }

    public function rules(): array
    {
        return [
            'bookable_type'      => 'required|string|in:App\Models\Tattooer,App\Models\Piercer',
            'bookable_id'        => 'required|integer|min:1',
            'tattoo_size'        => 'nullable|string|max:200',
            'body_zone'          => 'required|string|max:500',
            'description'        => 'required|string|min:20|max:15000',
            'estimated_budget'   => 'nullable|numeric|min:0|max:10000',
            'preferred_timeframe'=> 'nullable|in:asap,3-4months,5-6months,6plus',
            'preferred_days'     => 'nullable|array',
            'preferred_days.*'   => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'date_notes'         => 'nullable|string|max:500',
            'reference_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'bookable_id.required'   => 'Veuillez sélectionner un artiste.',
            'body_zone.required'     => 'La zone du corps est requise.',
            'description.required'   => 'Une description est requise.',
            'description.min'        => 'La description doit contenir au moins 20 caractères.',
            'description.max'        => 'La description ne peut pas dépasser 15 000 caractères.',
            'estimated_budget.max'   => 'Le budget estimé ne peut pas dépasser 10 000 €.',
        ];
    }
}
