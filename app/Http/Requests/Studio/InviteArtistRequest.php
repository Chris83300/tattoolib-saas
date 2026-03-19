<?php

namespace App\Http\Requests\Studio;

use Illuminate\Foundation\Http\FormRequest;

class InviteArtistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->studio !== null;
    }

    public function rules(): array
    {
        return [
            'email'        => 'required|email',
            'artisan_type' => 'required|in:tattooer,piercer',
            'message'      => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email'    => 'L\'adresse email doit être valide.',
            'artisan_type.required' => 'Le type d\'artisan est obligatoire.',
            'artisan_type.in'       => 'Le type d\'artisan doit être "tattooer" ou "piercer".',
        ];
    }
}
