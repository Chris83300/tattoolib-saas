<?php

namespace App\Http\Requests\Api\Piercer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpecializationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manageSpecialization', $this->route('Piercer'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'specialization' => ['required', 'in:Piercer,bodemodeur,Piercer_bodemodeur'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'specialization.required' => 'La spécialisation est obligatoire.',
            'specialization.in' => 'La spécialisation doit être l\'une des valeurs suivantes : Piercer, bodemodeur, Piercer_bodemodeur.',
        ];
    }
}
