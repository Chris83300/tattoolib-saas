<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientConsentForm;
use App\Models\ParentalConsentForm;
use App\Models\TraceabilityRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TraceabilityController extends Controller
{
    /**
     * Liste des formulaires de consentement (tatoueur uniquement)
     */
    public function consentForms(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $query = ClientConsentForm::query()->forTattooer($user->tattooer->id)
            ->with(['client.user:id,name,email', 'appointment', 'parentalConsent']);

        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('start_time', '>=', $request->date_from);
            });
        }

        if ($request->has('date_to')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('start_time', '<=', $request->date_to);
            });
        }

        $consentForms = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($consentForms);
    }

    /**
     * Créer un formulaire de consentement (client signe)
     */
    public function storeConsentForm(Request $request)
    {
        $user = $request->user();

        // Le client peut créer son propre formulaire
        if (!$user->isClient()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'id_document_type' => 'required|in:' . implode(',', array_keys(ClientConsentForm::ID_DOCUMENT_TYPES)),
            'id_document_number' => 'required|string|max:255',
            'id_document_expiry' => 'required|date|after:today',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'address' => 'required|string',

            // Déclaration santé
            'has_allergies' => 'boolean',
            'allergies_details' => 'nullable|string',
            'has_skin_conditions' => 'boolean',
            'skin_conditions_details' => 'nullable|string',
            'has_blood_disorders' => 'boolean',
            'blood_disorders_details' => 'nullable|string',
            'has_diabetes' => 'boolean',
            'has_heart_conditions' => 'boolean',
            'is_pregnant' => 'boolean',
            'is_breastfeeding' => 'boolean',
            'taking_medications' => 'boolean',
            'medications_details' => 'nullable|string',
            'has_recent_surgery' => 'boolean',
            'recent_surgery_details' => 'nullable|string',

            // Consentements
            'consents_to_tattoo' => 'required|boolean',
            'understands_risks' => 'required|boolean',
            'understands_aftercare' => 'required|boolean',
            'consents_to_photos' => 'required|boolean',
            'consents_to_data_processing' => 'required|boolean',

            // Documents
            'id_document_photos' => 'required|array|min:1',
            'id_document_photos.*' => 'required|url',
            'consent_signature' => 'required|array',
        ]);

        $appointment = \App\Models\Appointment::findOrFail($validated['appointment_id']);

        // Vérifier que le RDV appartient bien au client
        if ($appointment->client_id !== $user->client->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // Vérifier l'âge
        $birthDate = \Carbon\Carbon::parse($validated['birth_date']);
        $isAdult = $birthDate->age >= 18;

        $consentForm = ClientConsentForm::create([
            'client_id' => $user->client->id,
            'tattooer_id' => $appointment->tattooer_id,
            'appointment_id' => $appointment->id,
            'is_adult' => $isAdult,
            ...$validated,
        ]);

        // Marquer comme signé
        $consentForm->markAsSigned();

        return response()->json($consentForm, 201);
    }

    /**
     * Créer un consentement parental
     */
    public function storeParentalConsent(Request $request, ClientConsentForm $consentForm)
    {
        Gate::authorize('view', $consentForm);

        if (!$consentForm->requiresParentalConsent()) {
            return response()->json(['message' => 'Consentement parental non requis'], 400);
        }

        $validated = $request->validate([
            'parent_full_name' => 'required|string|max:255',
            'parent_relationship' => 'required|in:' . implode(',', array_keys(ParentalConsentForm::RELATIONSHIPS)),
            'parent_id_document_type' => 'required|in:' . implode(',', array_keys(ClientConsentForm::ID_DOCUMENT_TYPES)),
            'parent_id_document_number' => 'required|string|max:255',
            'parent_id_document_expiry' => 'required|date|after:today',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'required|email',
            'parent_address' => 'required|string',

            // Consentements parentaux
            'parent_consents_to_tattoo' => 'required|boolean',
            'parent_understands_risks' => 'required|boolean',
            'parent_will_supervise_aftercare' => 'required|boolean',
            'parent_consents_to_emergency_treatment' => 'required|boolean',

            // Documents
            'parent_id_document_photos' => 'required|array|min:1',
            'parent_id_document_photos.*' => 'required|url',
            'parent_signature' => 'required|array',
        ]);

        $parentalConsent = ParentalConsentForm::create([
            'client_consent_form_id' => $consentForm->id,
            'tattooer_id' => $consentForm->tattooer_id,
            ...$validated,
        ]);

        $parentalConsent->markAsSigned();

        return response()->json($parentalConsent, 201);
    }

    /**
     * Vérifier un formulaire de consentement (tatoueur uniquement)
     */
    public function verifyConsentForm(Request $request, ClientConsentForm $consentForm)
    {
        Gate::authorize('verify', $consentForm);

        $validated = $request->validate([
            'verified' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validated['verified']) {
            $consentForm->markAsVerified($request->user()->id);
        } else {
            $consentForm->update([
                'status' => ClientConsentForm::STATUS_EXPIRED,
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);
        }

        return response()->json($consentForm);
    }

    /**
     * Liste des enregistrements de tracabilité (tatoueur uniquement)
     */
    public function traceabilityRecords(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $query = TraceabilityRecord::query()->forTattooer($user->tattooer->id)
            ->with(['clientConsentForm.client.user:id,name', 'appointment']);

        // Filtres
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->between($request->date_from, $request->date_to);
        }

        if ($request->has('verified_only')) {
            $query->verified();
        }

        $records = $query->orderBy('procedure_date', 'desc')->paginate(20);

        return response()->json($records);
    }

    /**
     * Créer un enregistrement de tracabilité (tatoueur uniquement)
     */
    public function storeTraceabilityRecord(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'procedure_date' => 'required|date',
            'procedure_start_time' => 'required|date_format:H:i',
            'procedure_end_time' => 'required|date_format:H:i|after:procedure_start_time',

            // Environnement
            'room_number' => 'nullable|string|max:50',
            'autoclave_batch_number' => 'nullable|string|max:100',
            'autoclave_test_date' => 'nullable|date',

            // Notes
            'procedure_notes' => 'nullable|string',
            'client_condition_notes' => 'nullable|string',
            'equipment_notes' => 'nullable|string',
        ]);

        $appointment = \App\Models\Appointment::findOrFail($validated['appointment_id']);

        // Vérifier que le RDV appartient bien au tatoueur
        if ($appointment->tattooer_id !== $user->tattooer->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // Récupérer le formulaire de consentement
        $consentForm = ClientConsentForm::where('appointment_id', $appointment->id)->first();

        if (!$consentForm || $consentForm->status !== ClientConsentForm::STATUS_VERIFIED) {
            return response()->json(['message' => 'Consentement non vérifié'], 400);
        }

        $traceabilityRecord = TraceabilityRecord::create([
            'tattooer_id' => $user->tattooer->id,
            'appointment_id' => $appointment->id,
            'client_consent_form_id' => $consentForm->id,
            ...$validated,
        ]);

        return response()->json($traceabilityRecord, 201);
    }

    /**
     * Ajouter des matériaux à la tracabilité (tatoueur uniquement)
     */
    public function addMaterials(Request $request, TraceabilityRecord $record)
    {
        Gate::authorize('update', $record);

        $validated = $request->validate([
            'needles' => 'nullable|array',
            'needles.*.type' => 'required|string',
            'needles.*.size' => 'required|string',
            'needles.*.quantity' => 'required|integer|min:1',
            'needles.*.lot_number' => 'required|string',
            'needles.*.expiration_date' => 'required|date',
            'needles.*.photo_url' => 'nullable|url',

            'inks' => 'nullable|array',
            'inks.*.brand' => 'required|string',
            'inks.*.color' => 'required|string',
            'inks.*.lot_number' => 'required|string',
            'inks.*.expiration_date' => 'required|date',
            'inks.*.quantity_ml' => 'required|numeric|min:0',
            'inks.*.photo_url' => 'nullable|url',
            'inks.*.is_vegan' => 'boolean',

            'sterile_equipment' => 'nullable|array',
            'sterile_equipment.*.type' => 'required|string',
            'sterile_equipment.*.brand' => 'required|string',
            'sterile_equipment.*.lot_number' => 'required|string',
            'sterile_equipment.*.expiration_date' => 'required|date',
            'sterile_equipment.*.photo_url' => 'nullable|url',

            'aftercare_products' => 'nullable|array',
            'aftercare_products.*.brand' => 'required|string',
            'aftercare_products.*.product_name' => 'required|string',
            'aftercare_products.*.lot_number' => 'required|string',
            'aftercare_products.*.expiration_date' => 'required|date',
            'aftercare_products.*.photo_url' => 'nullable|url',
        ]);

        // Ajouter les aiguilles
        if (!empty($validated['needles'])) {
            foreach ($validated['needles'] as $needle) {
                $record->addNeedle($needle);
            }
        }

        // Ajouter les encres
        if (!empty($validated['inks'])) {
            foreach ($validated['inks'] as $ink) {
                $record->addInk($ink);
            }
        }

        // Ajouter l'équipement stérile
        if (!empty($validated['sterile_equipment'])) {
            foreach ($validated['sterile_equipment'] as $equipment) {
                $record->addSterileEquipment($equipment);
            }
        }

        // Ajouter les produits de soin
        if (!empty($validated['aftercare_products'])) {
            foreach ($validated['aftercare_products'] as $product) {
                $record->addAftercareProduct($product);
            }
        }

        return response()->json($record);
    }

    /**
     * Ajouter des photos à la tracabilité (tatoueur uniquement)
     */
    public function addPhotos(Request $request, TraceabilityRecord $record)
    {
        Gate::authorize('update', $record);

        $validated = $request->validate([
            'procedure_photos' => 'nullable|array',
            'procedure_photos.*' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'procedure_photo_types' => 'nullable|array',
            'procedure_photo_types.*' => 'required|in:before,during,after,work_area',

            'workstation_photos' => 'nullable|array',
            'workstation_photos.*' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'workstation_photo_types' => 'nullable|array',
            'workstation_photo_types.*' => 'required|in:setup,during,cleanup',
        ]);

        // Ajouter les photos de procédure
        if (!empty($validated['procedure_photos'])) {
            foreach ($validated['procedure_photos'] as $index => $photo) {
                $path = $photo->store('traceability/procedure', 'public');
                $url = asset('storage/' . $path);
                $type = $validated['procedure_photo_types'][$index] ?? 'general';

                $record->addProcedurePhoto($url, $type);
            }
        }

        // Ajouter les photos de l'espace de travail
        if (!empty($validated['workstation_photos'])) {
            foreach ($validated['workstation_photos'] as $index => $photo) {
                $path = $photo->store('traceability/workstation', 'public');
                $url = asset('storage/' . $path);
                $type = $validated['workstation_photo_types'][$index] ?? 'general';

                $record->addWorkstationPhoto($url, $type);
            }
        }

        return response()->json($record);
    }

    /**
     * Valider la tracabilité (tatoueur uniquement)
     */
    public function verifyTraceability(Request $request, TraceabilityRecord $record)
    {
        Gate::authorize('update', $record);

        $validated = $request->validate([
            'verified' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validated['verified']) {
            $record->markAsVerified($validated['notes'] ?? null);
        }

        return response()->json($record);
    }

    /**
     * Générer un rapport de tracabilité (tatoueur uniquement)
     */
    public function generateReport(Request $request, TraceabilityRecord $record)
    {
        Gate::authorize('view', $record);

        $report = $record->generateTraceabilityReport();

        return response()->json($report);
    }

    /**
     * Statistiques de tracabilité (tatoueur uniquement)
     */
    public function statistics(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $records = TraceabilityRecord::query()->forTattooer($user->tattooer->id);

        return response()->json([
            'total_records' => $records->count(),
            'verified_records' => $records->verified()->count(),
            'pending_verification' => $records->where('tattooer_verified_traceability', false)->count(),
            'this_month' => $records->whereMonth('procedure_date', now()->month)->count(),
            'last_month' => $records->whereMonth('procedure_date', now()->subMonth()->month)->count(),
        ]);
    }
}
