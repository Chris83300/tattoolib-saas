<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientCareSheet;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ClientCareSheetController extends Controller
{
    /**
     * Liste des fiches de soins du tatoueur
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Uniquement les tatoueurs peuvent voir toutes leurs fiches
        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $query = ClientCareSheet::query()->forTattooer($user->tattooer->id)
            ->with(['client.user:id,name,email', 'appointment']);

        // Filtres
        if ($request->has('status')) {
            $query->where('healing_status', $request->status);
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

        $careSheets = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($careSheets);
    }

    /**
     * Liste des fiches de soins du client (accès limité)
     */
    public function clientIndex(Request $request)
    {
        $user = $request->user();

        if (!$user->isClient()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $careSheets = ClientCareSheet::query()
            ->forClient($user->client->id)
            ->with(['tattooer.user:id,name,studio_name', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($careSheets);
    }

    /**
     * Créer une fiche de soins
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'tattoo_description' => 'required|string',
            'tattoo_location' => 'required|string',
            'tattoo_size' => 'required|string',
            'technique_used' => 'nullable|string',
            'ink_colors_used' => 'nullable|string',

            // Informations médicales
            'allergies' => 'nullable|string',
            'skin_conditions' => 'nullable|string',
            'medications' => 'nullable|string',
            'has_diabetes' => 'boolean',
            'has_blood_disorders' => 'boolean',
            'is_pregnant' => 'boolean',

            // Soins immédiats
            'immediate_care_instructions' => 'required|string',
            'products_used' => 'required|string',
            'bandage_type' => 'required|string',
            'bandage_removal_time' => 'required|date',

            // Instructions
            'washing_instructions' => 'required|string',
            'moisturizing_instructions' => 'required|string',
            'activity_restrictions' => 'required|string',
            'sun_exposure_warnings' => 'required|string',

            // Suivi
            'healing_estimated_date' => 'required|date|after:today',
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);

        // Vérifier que le RDV appartient bien au tatoueur
        if ($appointment->bookable_type !== \App\Models\Tattooer::class ||
            $appointment->bookable_id !== $user->tattooer->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $careSheet = ClientCareSheet::create([
            'user_id' => $user->id,
            'client_id' => $appointment->client_id,
            'appointment_id' => $appointment->id,
            ...$validated,
        ]);

        return response()->json($careSheet, 201);
    }

    /**
     * Afficher une fiche de soins
     */
    public function show(ClientCareSheet $careSheet)
    {
        Gate::authorize('view', $careSheet);

        $careSheet->load([
            'client.user:id,name,email,phone',
            'tattooer.user:id,name,studio_name',
            'appointment'
        ]);

        return response()->json($careSheet);
    }

    /**
     * Mettre à jour une fiche de soins
     */
    public function update(Request $request, ClientCareSheet $careSheet)
    {
        Gate::authorize('update', $careSheet);

        $validated = $request->validate([
            'healing_status' => 'required|in:' . implode(',', ClientCareSheet::HEALING_STATUSES),
            'healing_notes' => 'nullable|string',
        ]);

        $careSheet->updateHealingStatus(
            $validated['healing_status'],
            $validated['healing_notes'] ?? null
        );

        return response()->json($careSheet);
    }

    /**
     * Ajouter une photo de suivi
     */
    public function addPhoto(Request $request, ClientCareSheet $careSheet)
    {
        Gate::authorize('update', $careSheet);

        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'stage' => 'required|in:day_1,day_3,day_7,day_14,day_30,final,complication',
        ]);

        // Upload de la photo
        $path = $request->file('photo')->store('care-sheets/photos', 'public');
        $url = asset('storage/' . $path);

        $careSheet->addHealingPhoto($url, $validated['stage']);

        return response()->json([
            'message' => 'Photo ajoutée avec succès',
            'photo_url' => $url,
        ]);
    }

    /**
     * Créer automatiquement une fiche de soins depuis un RDV terminé
     */
    public function createFromAppointment(Request $request, Appointment $appointment)
    {
        $user = $request->user();

        if (!$user->isTattooer() ||
            $appointment->bookable_type !== \App\Models\Tattooer::class ||
            $appointment->bookable_id !== $user->tattooer->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        if ($appointment->status !== Appointment::STATUS_COMPLETED) {
            return response()->json(['message' => 'Le rendez-vous doit être terminé'], 400);
        }

        // Vérifier si une fiche existe déjà
        $existingCareSheet = ClientCareSheet::where('appointment_id', $appointment->id)->first();
        if ($existingCareSheet) {
            return response()->json(['message' => 'Une fiche de soins existe déjà'], 400);
        }

        $validated = $request->validate([
            'tattoo_description' => 'required|string',
            'tattoo_location' => 'required|string',
            'tattoo_size' => 'required|string',
            'technique_used' => 'nullable|string',
            'ink_colors_used' => 'nullable|string',
            'bandage_type' => 'required|string',
        ]);

        $careSheet = ClientCareSheet::createFromAppointment($appointment, $validated);

        return response()->json($careSheet, 201);
    }

    /**
     * Statistiques de suivi pour le tatoueur
     */
    public function statistics(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $careSheets = ClientCareSheet::query()->forTattooer($user->tattooer->id);

        return response()->json([
            'total_care_sheets' => $careSheets->count(),
            'healing_in_progress' => $careSheets->healingInProgress()->count(),
            'healed' => $careSheets->healed()->count(),
            'needs_touchup' => $careSheets->needsTouchup()->count(),
            'complicated' => $careSheets->complicated()->count(),
            'touchup_recommended' => $careSheets->whereHas('appointment', function ($q) {
                $q->where('start_time', '>=', now()->subMonths(3));
            })->where('healing_status', ClientCareSheet::HEALING_STATUS_HEALED)->count(),
        ]);
    }
}
