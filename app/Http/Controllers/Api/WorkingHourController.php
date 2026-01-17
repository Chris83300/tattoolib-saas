<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkingHour;
use Illuminate\Http\Request;

class WorkingHourController extends Controller
{
    /**
     * Liste des horaires de travail du tatoueur
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $workingHours = WorkingHour::where('tattooer_id', $user->tattooer->id)
            ->orderBy('day_of_week')
            ->get()
            ->map(function ($wh) {
                return [
                    'id' => $wh->id,
                    'day_of_week' => $wh->day_of_week,
                    'day_label' => $wh->getDayLabel(),
                    'is_open' => $wh->is_open,
                    'start_time' => $wh->start_time,
                    'end_time' => $wh->end_time,
                    'break_start' => $wh->break_start,
                    'break_end' => $wh->break_end,
                    'slot_duration_minutes' => $wh->slot_duration_minutes,
                    'buffer_time_minutes' => $wh->buffer_time_minutes,
                    'working_duration_minutes' => $wh->getWorkingDurationMinutes(),
                    'has_break' => $wh->hasBreak(),
                ];
            });

        return response()->json($workingHours);
    }

    /**
     * Met à jour les horaires d'un jour
     */
    public function update(Request $request, WorkingHour $workingHour)
    {
        $user = $request->user();

        if (!$user->isTattooer() || $workingHour->tattooer_id !== $user->tattooer->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'is_open' => 'boolean',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i|after:break_start',
            'slot_duration_minutes' => 'integer|min:15|max:480',
            'buffer_time_minutes' => 'integer|min:0|max:60',
        ]);

        $workingHour->update($validated);

        // Valider la cohérence
        $errors = $workingHour->validate();
    if (!empty($errors)) {
    return response()->json([
    'message' => 'Erreurs de validation',
    'errors' => $errors
    ], 422);
    }    return response()->json([
            'message' => 'Horaires mis à jour',
            'working_hour' => $workingHour,
        ]);
    }/**
    * Mise à jour groupée de tous les jours
    */
    public function bulkUpdate(Request $request)
    {
        $user = $request->user();    if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }    $validated = $request->validate([
            'working_hours' => 'required|array|size:7',
            'working_hours.*.day_of_week' => 'required|integer|min:0|max:6',
            'working_hours.*.is_open' => 'boolean',
            'working_hours.*.start_time' => 'nullable|date_format:H:i',
            'working_hours.*.end_time' => 'nullable|date_format:H:i',
            'working_hours.*.break_start' => 'nullable|date_format:H:i',
            'working_hours.*.break_end' => 'nullable|date_format:H:i',
            'working_hours.*.slot_duration_minutes' => 'integer|min:15|max:480',
            'working_hours.*.buffer_time_minutes' => 'integer|min:0|max:60',
        ]);    foreach ($validated['working_hours'] as $data) {
            WorkingHour::updateOrCreate(
                [
                    'tattooer_id' => $user->tattooer->id,
                    'day_of_week' => $data['day_of_week']
                ],
                $data
            );
        }    return response()->json(['message' => 'Horaires mis à jour']);
    }/**
    * Génère les availabilities à partir des WorkingHours
    */
    public function generateAvailabilities(Request $request)
    {
        $user = $request->user();    if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }    $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);    $generated = \App\Models\Availability::generateFromWorkingHours(
            $user->tattooer->id,
            \Carbon\Carbon::parse($validated['start_date']),
            \Carbon\Carbon::parse($validated['end_date'])
        );    return response()->json([
            'message' => 'Availabilities générées',
            'count' => $generated,
        ]);
    }/**
    * Aperçu des créneaux pour un jour donné
    */
    public function previewSlots(Request $request)
    {
        $user = $request->user();    if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }    $validated = $request->validate([
            'day_of_week' => 'required|integer|min:0|max:6',
        ]);    $workingHour = WorkingHour::where('tattooer_id', $user->tattooer->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->first();    if (!$workingHour) {
            return response()->json(['message' => 'Jour non configuré'], 404);
        }    $slots = $workingHour->generateTimeSlots();    return response()->json([
            'day' => $workingHour->getDayLabel(),
            'slots' => $slots,
            'total_slots' => count($slots),
            'working_duration' => $workingHour->getWorkingDurationMinutes(),
        ]);
    }
}
