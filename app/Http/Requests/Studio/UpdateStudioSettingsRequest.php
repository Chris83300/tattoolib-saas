<?php

namespace App\Http\Requests\Studio;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudioSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->studio !== null;
    }

    public function rules(): array
    {
        return [
            'name'                   => 'required|string|max:255',
            'description'            => 'nullable|string|max:2000',
            'address'                => 'nullable|string|max:255',
            'city'                   => 'nullable|string|max:255',
            'postal_code'            => 'nullable|string|max:10',
            'phone'                  => 'nullable|string|max:20',
            'email'                  => 'nullable|email|max:255',
            'website'                => 'nullable|url|max:255',
            'siret'                  => 'nullable|string|size:14',
            'payment_mode'           => 'required|in:artist_direct,studio_managed',
            'artist_commission_rate' => 'nullable|numeric|min:0|max:99.99',
            'opening_hours'          => 'nullable|array',
            'social_media_links'     => 'nullable|array',
        ];
    }
}
