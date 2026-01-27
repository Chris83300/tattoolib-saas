<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Tattooer;
use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AvailabilityController extends Controller
{
    /**
     * Liste des disponibilités du tatoueur
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $query = Availability::query()->forTattooer($user->tattooer->id);

        // Filtres
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->between($request->start_date, $request->end_date);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $availabilities = $query->orderBy('date')->orderBy('start_time')->get();

        return response()->json($availabilities);
    }

    /**
     * Créer une disponibilité
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|in:' . implode(',', Availability::TYPES),
            'notes' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_pattern' => 'nullable|in:daily,weekly,monthly',
            'recurring_end_date' => 'nullable|date|after:date',
            'recurring_days' => 'nullable|array',
            'recurring_days.*' => 'integer|min:0|max:6',
        ]);

        // Créer la disponibilité principale
        $availability = Availability::create([
            'owner_type' => \App\Models\Tattooer::class,
            'owner_id' => $user->tattooer->id,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'type' => $validated['type'],
            'notes' => $validated['notes'] ?? null,
            'is_recurring' => $validated['is_recurring'] ?? false,
            'recurring_pattern' => $validated['recurring_pattern'] ?? null,
        ]);

        // Si récurrent, créer les autres occurrences
        if ($validated['is_recurring'] && $validated['recurring_pattern']) {
            $recurringCount = Availability::generateRecurring(
                $user->tattooer->id,
                \Carbon\Carbon::parse($validated['date']),
                \Carbon\Carbon::parse($validated['recurring_end_date'] ?? $validated['date'])->addMonth(),
                $validated['start_time'],
                $validated['end_time'],
                $validated['recurring_pattern'],
                $validated['recurring_days'] ?? []
            );
        }

        return response()->json($availability, 201);
    }

    /**
     * Mettre à jour une disponibilité
     */
    public function update(Request $request, Availability $availability)
    {
        Gate::authorize('update', $availability);

        $validated = $request->validate([
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i|after:start_time',
            'type' => 'in:' . implode(',', Availability::TYPES),
            'notes' => 'nullable|string',
        ]);

        $availability->update($validated);

        return response()->json($availability);
    }

    /**
     * Supprimer une disponibilité
     */
    public function destroy(Availability $availability)
    {
        Gate::authorize('delete', $availability);

        $availability->delete();

        return response()->json(null, 204);
    }

    /**
     * Générer les disponibilités à partir des horaires de travail
     */
    public function generateFromWorkingHours(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $availabilities = Availability::createFromWorkingHours(
            $user->tattooer->id,
            \Carbon\Carbon::parse($validated['start_date']),
            \Carbon\Carbon::parse($validated['end_date'])
        );

        // Supprimer les existantes pour cette période
        Availability::where('owner_type', \App\Models\Tattooer::class)
            ->where('owner_id', $user->tattooer->id)
            ->whereBetween('date', [$validated['start_date'], $validated['end_date']])
            ->where('type', Availability::TYPE_AVAILABLE)
            ->delete();

        // Insérer les nouvelles
        Availability::insert($availabilities->toArray());

        return response()->json([
            'message' => 'Disponibilités générées avec succès',
            'count' => $availabilities->count(),
        ]);
    }

    /**
     * Vérifier les créneaux disponibles
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'duration_minutes' => 'required|integer|min:15',
        ]);

        $availabilities = Availability::where('user_id', $validated['user_id'])
            ->where('date', $validated['date'])
            ->available()
            ->get();

        $availableSlots = [];

        foreach ($availabilities as $availability) {
            if ($availability->isTimeSlotAvailable('09:00', $validated['duration_minutes'])) {
                $availableSlots[] = [
                    'start_time' => $availability->start_time,
                    'end_time' => $availability->end_time,
                    'duration_minutes' => $availability->getDurationMinutes(),
                ];
            }
        }

        return response()->json([
            'date' => $validated['date'],
            'available_slots' => $availableSlots,
        ]);
    }
}
