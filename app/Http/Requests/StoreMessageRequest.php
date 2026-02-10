<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\InputSanitizerService;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('content')) {
            $sanitizer = app(InputSanitizerService::class);
            $this->merge([
                'content' => $sanitizer->sanitizeText($this->content)
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'content' => [
                'required_without:attachment',
                'string',
                'max:5000',
                'regex:/^[^<>]*$/', // Interdit < et > après sanitization
            ],
            'attachment' => [
                'nullable',
                'file',
                'mimes:jpeg,png,webp,pdf',
                'max:10240', // 10MB
                function ($attribute, $value, $fail) {
                    // Vérification type MIME réel
                    $realMimeType = $value->getMimeType();
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];

                    if (!in_array($realMimeType, $allowedMimes)) {
                        $fail('Le type de fichier n\'est pas autorisé.');
                    }
                },
            ],
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
