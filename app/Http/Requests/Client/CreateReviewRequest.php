<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\BookingRequest;

class CreateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        $bookingRequest = $this->route('bookingRequest');
        $client = auth()->user()?->client;

        if (!$client || !$bookingRequest) {
            return false;
        }

        // Le booking doit appartenir au client et être completed
        return $bookingRequest->client_id === $client->id
            && $bookingRequest->isCompleted();
    }

    public function rules(): array
    {
        return [
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:500',
            'reviewable_id' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'La note est obligatoire.',
            'rating.integer'  => 'La note doit être un nombre entier.',
            'rating.min'      => 'La note minimale est 1.',
            'rating.max'      => 'La note maximale est 5.',
            'comment.max'     => 'Le commentaire ne peut pas dépasser 500 caractères.',
        ];
    }
}
