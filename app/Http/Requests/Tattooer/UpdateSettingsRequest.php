<?php

namespace App\Http\Requests\Tattooer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isArtisan();
    }

    public function rules(): array
    {
        $artisan = auth()->user()->artisan();
        $artisanId = $artisan?->id;

        return [
            'first_name'          => 'nullable|string|max:255',
            'last_name'           => 'nullable|string|max:255',
            'pseudo'              => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tattooers', 'pseudo')->ignore($artisanId),
            ],
            'bio'                 => 'nullable|string|max:2000',
            'city'                => 'nullable|string|max:255',
            'postal_code'         => ['nullable', 'string', 'regex:/^\d{5}$/'],
            'phone'               => 'nullable|string|max:20',
            'minimum_price'       => 'nullable|numeric|min:0',
            'years_of_experience' => 'nullable|integer|min:0|max:99',
            'instagram'           => 'nullable|url|max:255',
            'facebook'            => 'nullable|url|max:255',
            'tiktok'              => 'nullable|url|max:255',
            'website'             => 'nullable|url|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.regex' => 'Le code postal doit contenir exactement 5 chiffres.',
        ];
    }
}
