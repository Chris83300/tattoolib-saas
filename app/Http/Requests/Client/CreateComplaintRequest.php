<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        $bookingRequest = $this->route('bookingRequest');
        $client = auth()->user()?->client;

        if (!$client) {
            return false;
        }

        // Si une booking request est passée en route, vérifier qu'elle appartient au client
        if ($bookingRequest) {
            return $bookingRequest->client_id === $client->id;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'type'        => 'required|string|in:quality,behavior,delay,billing,other',
            'description' => 'required|string|max:2000',
            'subject'     => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'        => 'Le type de réclamation est obligatoire.',
            'type.in'              => 'Le type de réclamation est invalide.',
            'description.required' => 'La description est obligatoire.',
            'description.max'      => 'La description ne peut pas dépasser 2000 caractères.',
        ];
    }
}
