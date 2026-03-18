<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class ProcessDepositRequest extends FormRequest
{
    public function authorize(): bool
    {
        $client = auth()->user()?->client;
        $bookingRequest = $this->route('bookingRequest');

        return $client
            && $bookingRequest
            && $bookingRequest->client_id === $client->id
            && $bookingRequest->status === 'accepted'
            && !$bookingRequest->deposit_paid_at;
    }

    public function rules(): array
    {
        // La session Stripe est créée côté serveur — pas de données utilisateur à valider.
        // L'autorisation et l'état métier sont vérifiés dans authorize().
        return [];
    }

    public function messages(): array
    {
        return [
            'This action is unauthorized.' => 'Vous n\'êtes pas autorisé à effectuer ce paiement.',
        ];
    }
}
