<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // L'autorisation est gérée par Gate dans le contrôleur
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'content' => 'required_without:attachment|string|max:5000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx',
            'is_design_version' => 'nullable|boolean',
            'design_version_number' => 'nullable|required_if:is_design_version,true|integer|min:1',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'content.required_without' => 'Le message ou une pièce jointe est requis',
            'content.max' => 'Le message ne peut pas dépasser 5000 caractères',
            'attachment.max' => 'La pièce jointe ne peut pas dépasser 10 Mo',
            'attachment.mimes' => 'Format de fichier non supporté (jpg, png, gif, pdf, doc, docx uniquement)',
            'design_version_number.required_if' => 'Le numéro de version est requis pour un design',
        ];
    }
}
