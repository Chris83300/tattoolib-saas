<?php

namespace App\Http\Requests\Tattooer;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isArtisan();
    }

    public function rules(): array
    {
        return [
            // Identité client
            'client_full_name'    => 'required|string|max:255',
            'client_birth_date'   => 'required|date|before:today',
            'client_address'      => 'required|string|max:500',
            'client_phone'        => 'required|string|max:20',
            'client_email'        => 'required|email|max:255',

            // Acte
            'act_type'            => 'required|string|in:tatouage,piercing,dermographie,scarification,modification_corporelle',
            'body_zone'           => 'required|string|max:255',
            'act_description'     => 'required|string|max:1000',

            // Questionnaire médical
            'medical_allergies'              => 'boolean',
            'medical_allergies_detail'       => 'required_if:medical_allergies,true|nullable|string|max:1000',
            'medical_skin_disease'           => 'boolean',
            'medical_skin_disease_detail'    => 'required_if:medical_skin_disease,true|nullable|string|max:1000',
            'medical_anticoagulant'          => 'boolean',
            'medical_diabetes'               => 'boolean',
            'medical_cicatrisation'          => 'boolean',
            'medical_vih_hepatite'           => 'boolean',
            'medical_pregnant'               => 'boolean',
            'medical_roaccutane'             => 'boolean',
            'medical_cheloide'               => 'boolean',
            'medical_other'                  => 'nullable|string|max:1000',

            // Mineur
            'is_minor'              => 'boolean',
            'parent_name'           => 'required_if:is_minor,true|nullable|string|max:255',
            'parent_relation'       => 'required_if:is_minor,true|nullable|in:pere,mere,tuteur',
            'parent_id_number'      => 'required_if:is_minor,true|nullable|string|max:50',
            'parent_signature_data' => 'required_if:is_minor,true|nullable|string',

            // Financier
            'total_price'      => 'required|numeric|min:0',
            'deposit_amount'   => 'required|numeric|min:0',
            'retouche_included' => 'boolean',

            // Image
            'image_authorization' => 'required|boolean',

            // Signature
            'signature_data'       => 'required|string',
            'handwritten_mention'  => 'required|string|max:255',

            // Confirmations
            'confirm_medical_sincere'        => 'required|accepted',
            'confirm_risks_informed'         => 'required|accepted',
            'confirm_info_sheet_read'        => 'required|accepted',
            'confirm_aftercare_received'     => 'required|accepted',
            'confirm_not_intoxicated'        => 'required|accepted',
            'confirm_over_18_or_authorized'  => 'required|accepted',
            'confirm_rgpd'                   => 'required|accepted',
        ];
    }
}
