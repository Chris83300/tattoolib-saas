<?php

namespace App\Http\Requests\Tattooer;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplianceDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isArtisan();
    }

    public function rules(): array
    {
        return [
            'document_type' => 'required|string|in:siret,insurance,id,diploma,other',
            'document'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'expires_at'    => 'nullable|date|after:today',
            'notes'         => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'document_type.required' => 'Le type de document est obligatoire.',
            'document_type.in'       => 'Le type de document est invalide.',
            'document.required'      => 'Le fichier est obligatoire.',
            'document.file'          => 'Le fichier est invalide.',
            'document.mimes'         => 'Le document doit être un PDF, JPG ou PNG.',
            'document.max'           => 'Le document ne peut pas dépasser 10 Mo.',
            'expires_at.date'        => 'La date d\'expiration doit être une date valide.',
            'expires_at.after'       => 'La date d\'expiration doit être dans le futur.',
        ];
    }
}
