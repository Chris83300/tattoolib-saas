<?php

namespace App\Http\Requests\Studio;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentModeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->studio !== null;
    }

    public function rules(): array
    {
        return [
            'payment_mode'           => 'required|in:studio,direct_artist',
            'artist_commission_rate' => 'nullable|numeric|min:0|max:99.99',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_mode.required' => 'Le mode de paiement est obligatoire.',
            'payment_mode.in'       => 'Le mode de paiement doit être "studio" ou "direct_artist".',
            'artist_commission_rate.numeric' => 'Le taux de commission doit être un nombre.',
            'artist_commission_rate.min'     => 'Le taux de commission ne peut pas être négatif.',
            'artist_commission_rate.max'     => 'Le taux de commission ne peut pas dépasser 99.99%.',
        ];
    }
}
