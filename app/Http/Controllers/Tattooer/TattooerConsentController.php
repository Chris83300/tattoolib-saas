<?php

namespace App\Http\Controllers\Tattooer;

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\ClientConsentForm;
use Illuminate\Http\Request;

class TattooerConsentController extends ArtisanBaseController
{
    /**
     * Enregistrer le consentement pour une booking request
     */
    public function storeConsent(Request $request, BookingRequest $bookingRequest)
    {
        $tattooer = $this->artisan();

        // Vérifier propriété
        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== $tattooer->getMorphClass()) {
            abort(403);
        }

        $validated = $request->validate([
            'medical_conditions' => 'nullable|array',
            'medical_conditions.*' => 'string|max:255',
            'allergies' => 'nullable|string|max:1000',
            'medications' => 'nullable|string|max:1000',
            'is_pregnant' => 'boolean',
            'has_skin_conditions' => 'boolean',
            'accepts_terms' => 'required|accepted',
            'accepts_aftercare' => 'required|accepted',
            'signature_data' => 'required|string', // base64
            // Mineur
            'is_minor' => 'boolean',
            'parent_name' => 'required_if:is_minor,true|nullable|string|max:255',
            'parent_relation' => 'required_if:is_minor,true|nullable|string|max:100',
            'parent_phone' => 'required_if:is_minor,true|nullable|string|max:20',
            'parent_email' => 'nullable|email|max:255',
            'parent_signature_data' => 'required_if:is_minor,true|nullable|string',
            'parent_id_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $consent = ClientConsentForm::updateOrCreate(
            ['booking_request_id' => $bookingRequest->id],
            array_merge($validated, [
                'client_id' => $bookingRequest->client_id,
                'signed_at' => now(),
                'parent_signed_at' => ($validated['is_minor'] ?? false) ? now() : null,
            ])
        );

        // Upload pièce d'identité parent si mineur
        if ($request->hasFile('parent_id_document')) {
            $consent->clearMediaCollection('parent_id');
            $consent->addMediaFromRequest('parent_id_document')
                ->toMediaCollection('parent_id');
        }

        return back()->with('success', '✅ Consentement enregistré.');
    }

    /**
     * Enregistrer un consentement numérique pour un client
     */
    public function storeDigitalConsent(Request $request, Client $client)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = \App\Models\BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        $validated = $request->validate([
            // Identité client
            'client_full_name' => 'required|string|max:255',
            'client_birth_date' => 'required|date',
            'client_phone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string|max:500',
            'client_id_type' => 'nullable|string|in:cni,passeport,permis,titre_sejour',
            'client_id_number' => 'nullable|string|max:50',

            // Mineur
            'is_minor' => 'nullable|boolean',
            'parent_name' => 'required_if:is_minor,1|string|max:255',
            'parent_relation' => 'required_if:is_minor,1|string|in:pere,mere,tuteur',
            'parent_id_number' => 'required_if:is_minor,1|string|max:50',

            // Médical
            'medical_allergies' => 'nullable|boolean',
            'medical_allergies_detail' => 'nullable|string|max:500',
            'medical_anticoagulant' => 'nullable|boolean',
            'medical_diabetes' => 'nullable|boolean',
            'medical_cicatrisation' => 'nullable|boolean',
            'medical_skin_disease' => 'nullable|boolean',
            'medical_skin_disease_detail' => 'nullable|string|max:500',
            'medical_vih_hepatite' => 'nullable|boolean',
            'medical_pregnant' => 'nullable|boolean',
            'medical_roaccutane' => 'nullable|boolean',
            'medical_cheloide' => 'nullable|boolean',
            'medical_other' => 'nullable|string|max:500',

            // Financier
            'total_price' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'retouche_included' => 'nullable|boolean',

            // Image
            'image_authorization' => 'nullable|boolean',

            // Confirmations
            'confirm_medical_sincere' => 'required|boolean',
            'confirm_risks_informed' => 'required|boolean',
            'confirm_info_sheet_read' => 'required|boolean',
            'confirm_aftercare_received' => 'required|boolean',
            'confirm_not_intoxicated' => 'required|boolean',
            'confirm_over_18_or_authorized' => 'required|boolean',
            'confirm_rgpd' => 'required|boolean',

            // Signature
            'handwritten_mention' => 'required|string|max:255',
            'signature_data' => 'required|string',
        ]);

        // Créer le consentement numérique
        $artisanForConsent = $this->artisan();
        $consent = \App\Models\ClientConsentForm::create([
            'client_id' => $client->id,
            'booking_request_id' => null, // Consentement manuel
            'studio_id' => $artisanForConsent?->studio_id,
            'client_full_name' => $validated['client_full_name'],
            'client_birth_date' => $validated['client_birth_date'],
            'client_phone' => $validated['client_phone'],
            'client_email' => $validated['client_email'],
            'client_address' => $validated['client_address'],
            'client_id_type' => $validated['client_id_type'],
            'client_id_number' => $validated['client_id_number'],
            'is_minor' => $validated['is_minor'] ?? false,
            'parent_name' => $validated['parent_name'] ?? null,
            'parent_relation' => $validated['parent_relation'] ?? null,
            'parent_id_number' => $validated['parent_id_number'] ?? null,
            'medical_allergies' => $validated['medical_allergies'] ?? false,
            'medical_allergies_detail' => $validated['medical_allergies_detail'] ?? null,
            'medical_anticoagulant' => $validated['medical_anticoagulant'] ?? false,
            'medical_diabetes' => $validated['medical_diabetes'] ?? false,
            'medical_cicatrisation' => $validated['medical_cicatrisation'] ?? false,
            'medical_skin_disease' => $validated['medical_skin_disease'] ?? false,
            'medical_skin_disease_detail' => $validated['medical_skin_disease_detail'] ?? null,
            'medical_vih_hepatite' => $validated['medical_vih_hepatite'] ?? false,
            'medical_pregnant' => $validated['medical_pregnant'] ?? false,
            'medical_roaccutane' => $validated['medical_roaccutane'] ?? false,
            'medical_cheloide' => $validated['medical_cheloide'] ?? false,
            'medical_other' => $validated['medical_other'] ?? null,
            'total_price' => $validated['total_price'] ?? null,
            'deposit_amount' => $validated['deposit_amount'] ?? null,
            'retouche_included' => $validated['retouche_included'] ?? false,
            'image_authorization' => $validated['image_authorization'] ?? null,
            'confirm_medical_sincere' => $validated['confirm_medical_sincere'],
            'confirm_risks_informed' => $validated['confirm_risks_informed'],
            'confirm_info_sheet_read' => $validated['confirm_info_sheet_read'],
            'confirm_aftercare_received' => $validated['confirm_aftercare_received'],
            'confirm_not_intoxicated' => $validated['confirm_not_intoxicated'],
            'confirm_over_18_or_authorized' => $validated['confirm_over_18_or_authorized'],
            'confirm_rgpd' => $validated['confirm_rgpd'],
            'handwritten_mention' => $validated['handwritten_mention'],
            'signature_data' => $validated['signature_data'],
            'signed_at' => now(),
            'signed_ip' => $request->ip(),
            'is_valid' => true, // Consentement numérique considéré comme valide
        ]);

        return redirect()->back()->with('success', '✅ Consentement numérique enregistré avec succès !');
    }

    /**
     * Uploader un consentement pour un client manuel
     */
    public function uploadConsent(Request $request, Client $client)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = \App\Models\BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        $request->validate([
            'consent_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'consent_date' => 'required|date',
        ]);

        $file = $request->file('consent_file');
        $consentDate = $request->input('consent_date');

        // Ajouter le fichier à la collection consent_documents
        $media = $client->addMedia($file)
            ->withCustomProperties([
                'consent_date' => $consentDate,
                'uploaded_by' => 'tattooer',
                'tattooer_id' => $tattooer->id,
            ])
            ->toMediaCollection('consent_documents');

        return redirect()->back()->with('success', '✅ Consentement uploadé avec succès !');
    }

    /**
     * Supprimer un consentement
     */
    public function deleteConsent(Client $client, $media)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = \App\Models\BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        $mediaItem = $client->getMedia('consent_documents')->where('id', $media)->first();

        if (!$mediaItem) {
            abort(404, 'Consentement non trouvé.');
        }

        $mediaItem->delete();

        return redirect()->back()->with('success', '✅ Consentement supprimé avec succès !');
    }
}
