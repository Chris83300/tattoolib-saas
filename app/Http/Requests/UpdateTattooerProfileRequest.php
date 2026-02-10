<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\InputSanitizerService;

class UpdateTattooerProfileRequest extends FormRequest
{
    /**
     * Determine if user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isTattooer();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $sanitizer = app(InputSanitizerService::class);
        
        $this->merge([
            'bio' => $sanitizer->sanitizeRichText($this->bio ?? ''),
            'instagram' => $sanitizer->sanitizeUrl($this->instagram ?? ''),
            'facebook' => $sanitizer->sanitizeUrl($this->facebook ?? ''),
            'website' => $sanitizer->sanitizeUrl($this->website ?? ''),
            'styles' => $sanitizer->sanitizeText($this->styles ?? ''),
            'studio_name' => $sanitizer->sanitizeText($this->studio_name ?? ''),
            'city' => $sanitizer->sanitizeText($this->city ?? ''),
            'postal_code' => $sanitizer->sanitizeText($this->postal_code ?? ''),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'styles' => 'nullable|string|max:500',
            'instagram' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'studio_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'siret' => 'nullable|string|max:14',
            'min_price' => 'nullable|numeric|min:0|max:10000',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'bio.max' => 'La bio ne peut pas dépasser 1000 caractères',
            'instagram.url' => 'L\'URL Instagram n\'est pas valide',
            'facebook.url' => 'L\'URL Facebook n\'est pas valide',
            'website.url' => 'L\'URL du site web n\'est pas valide',
            'styles.max' => 'Les styles ne peuvent pas dépasser 500 caractères',
            'name.required' => 'Le nom est requis',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'city.max' => 'La ville ne peut pas dépasser 255 caractères',
            'postal_code.max' => 'Le code postal ne peut pas dépasser 10 caractères',
            'phone.max' => 'Le téléphone ne peut pas dépasser 20 caractères',
            'email.email' => 'L\'email n\'est pas valide',
            'siret.max' => 'Le SIRET ne peut pas dépasser 14 caractères',
            'min_price.numeric' => 'Le prix minimum doit être un nombre',
            'min_price.min' => 'Le prix minimum ne peut pas être négatif',
            'min_price.max' => 'Le prix maximum est de 10000€',
            'description.max' => 'La description ne peut pas dépasser 500 caractères',
        ];
    }
}
