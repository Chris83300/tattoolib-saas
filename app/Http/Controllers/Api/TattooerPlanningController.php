<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Appointment;
use Illuminate\Http\Request;

class TattooerPlanningController extends Controller
{
    /**
     * ⭐ Dashboard planning tatoueur (vue complète)
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'view' => 'nullable|in:day,week,month',
        ]);

        $view = $validated['view'] ?? 'week';
        $startDate = isset($validated['start_date'])
            ? \Carbon\Carbon::parse($validated['start_date'])
            : now()->startOf($view);
        $endDate = isset($validated['end_date'])
            ? \Carbon\Carbon::parse($validated['end_date'])
            : now()->endOf($view);

        // S'assurer que les availabilities existent
        Availability::generateFromWorkingHours(
            $user->tattooer->id,
            $startDate,
            $endDate
        );

        // Récupérer toutes les availabilities
        $availabilities = Availability::forTattooer($user->tattooer->id)
            ->between($startDate, $endDate)
            ->with('appointment.client.user')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn($a) => $a->date->format('Y-m-d'));

        // Récupérer les RDV confirmés
        $appointments = Appointment::where('tattooer_id', $user->tattooer->id)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->with(['client.user', 'bookingRequest'])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
                'view' => $view,
            ],
            'availabilities_by_date' => $availabilities,
            'appointments' => $appointments,
            'statistics' => [
                'total_appointments' => $appointments->count(),
                'total_hours_booked' => $appointments->sum('duration_minutes') / 60,
            ],
        ]);
    }

    /**
     * ⭐ Bloquer manuellement un créneau
     */
    public function blockSlot(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|in:blocked,external_booking,holiday',
            'notes' => 'nullable|string|max:500',
        ]);

        $availability = Availability::blockSlot(
            $user->tattooer->id,
            $validated['date'],
            $validated['start_time'],
            $validated['end_time'],
            $validated['type'],
            $validated['notes'] ?? null
        );

        return response()->json([
            'message' => 'Créneau bloqué avec succès',
            'availability' => $availability,
        ], 201);
    }

    /**
     * ⭐ Créer un RDV externe (pris hors plateforme)
     */
    public function createExternalAppointment(Request $request)
    {
        $user = $request->user();

        if (!$user->isTattooer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'source' => 'required|in:external_walk_in,external_phone,external_social',
            'client_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Bloquer le créneau
        $availability = Availability::blockSlot(
            $user->tattooer->id,
            $validated['date'],
            $validated['start_time'],
            $validated['end_time'],
            Availability::TYPE_EXTERNAL_BOOKING,
            "Source: {$validated['source']}" .
            ($validated['client_name'] ? " - Client: {$validated['client_name']}" : "") .
            ($validated['notes'] ? " - {$validated['notes']}" : "")
        );

        return response()->json([
            'message' => 'RDV externe ajouté',
            'availability' => $availability,
        ], 201);
    }

    /**
     * ⭐ Libérer un créneau bloqué
     */
    public function releaseSlot(Request $request, Availability $availability)
    {
        $user = $request->user();

        if (!$user->isTattooer() || $availability->tattooer_id !== $user->tattooer->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // Ne peut libérer que les créneaux manuels ou externes
        if (!in_array($availability->source, [Availability::SOURCE_MANUAL, Availability::SOURCE_EXTERNAL])) {
            return response()->json([
                'message' => 'Ce créneau ne peut pas être libéré manuellement'
            ], 422);
        }

        $availability->delete();

        return response()->json(['message' => 'Créneau libéré']);
    }

    /**
     * ⭐ Obtenir les dates disponibles (pour sélecteur client)
     */
    public function availableDates(Request $request, $tattooerId)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date|after_or_equal:today',
            'months' => 'nullable|integer|min:1|max:12',
        ]);

        $startDate = isset($validated['start_date'])
            ? \Carbon\Carbon::parse($validated['start_date'])
            : now()->startOfDay();

        $months = $validated['months'] ?? 6; // Par défaut 6 mois
        $endDate = $startDate->copy()->addMonths($months);

        $dates = Availability::getAvailableDates($tattooerId, $startDate, $endDate);

        return response()->json([
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ],
            'available_dates' => $dates,
            'total_dates' => count($dates),
        ]);
    }

    /**
     * ⭐ Obtenir les créneaux disponibles pour une date
     */
    public function slotsForDate(Request $request, $tattooerId)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $slots = Availability::getAvailableSlotsForDay($tattooerId, $validated['date']);

        return response()->json([
            'date' => $validated['date'],
            'available_slots' => $slots,
            'total_slots' => count($slots),
        ]);
    }
}
