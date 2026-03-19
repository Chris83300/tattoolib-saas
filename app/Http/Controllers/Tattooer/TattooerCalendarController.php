<?php

namespace App\Http\Controllers\Tattooer;

use App\Models\BookingRequest;
use App\Models\CalendarEvent;
use App\Models\Appointment;
use App\Enums\BookingRequestStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TattooerCalendarController extends ArtisanBaseController
{
    /**
     * Calendrier du tattooer
     */
    public function calendar()
    {
        $tattooer = $this->artisan();
        $tattooer->load(['media', 'user']);

        // ═══════════════════════════════════════════════
        // 1. CalendarEvents (breaks, vacances, fermetures, RDV manuels)
        // ═══════════════════════════════════════════════
        $calendarEvents = CalendarEvent::where('bookable_type', $tattooer->getMorphClass())
            ->where('bookable_id', $tattooer->id)
            ->get()
            ->map(fn($event) => $event->toFullCalendarEvent())
            ->toArray();

        // ═══════════════════════════════════════════════
        // 2. Appointments bookés via la plateforme
        // ═══════════════════════════════════════════════
        $appointments = \App\Models\Appointment::where('bookable_type', $tattooer->getMorphClass())
            ->where('bookable_id', $tattooer->id)
            ->whereNotIn('status', ['cancelled'])
            ->with(['bookingRequest.client.user'])
            ->get()
            ->filter(fn($apt) => !CalendarEvent::where('appointment_id', $apt->id)->exists()) // Réactiver anti-doublons
            ->map(function ($apt) {
                $clientPseudo = $apt->bookingRequest?->client?->user?->pseudo
                    ?? ($apt->bookingRequest?->client?->user?->first_name . ' ' . $apt->bookingRequest?->client?->user?->last_name)
                    ?? 'Client';

                return [
                    'id' => 'apt_' . $apt->id,
                    'title' => 'Tattoo - ' . $clientPseudo,
                    'start' => $apt->start_datetime->toIso8601String(),
                    'end' => $apt->end_datetime->toIso8601String(),
                    'backgroundColor' => '#06D6A0',
                    'borderColor' => '#06D6A0',
                    'textColor' => '#FFFFFF',
                    'extendedProps' => [
                        'type' => 'appointment',
                        'appointment_id' => $apt->id,
                        'booking_request_id' => $apt->booking_request_id,
                        'client_name' => $clientPseudo,
                        'client_pseudo' => $apt->bookingRequest?->client?->user?->pseudo ?? $clientPseudo,
                        'body_zone' => $apt->bookingRequest?->body_zone ?? '',
                        'tattoo_size' => $apt->bookingRequest?->tattoo_size ?? '',
                        'deposit_paid' => $apt->bookingRequest?->deposit_paid_at !== null,
                        'deposit_amount' => (float) ($apt->bookingRequest?->deposit_amount ?? 0),
                        'total_price' => (float) ($apt->bookingRequest?->total_price ?? $apt->bookingRequest?->estimated_total_price ?? 0),
                        'status' => $apt->status,
                        'notes' => $apt->notes ?? '',
                    ],
                ];
            })
            ->values()
            ->toArray();

        // ═══════════════════════════════════════════════
        // 3. BookingRequests confirmées sans Appointment (compatibilité)
        // ═══════════════════════════════════════════════

        $bookingEvents = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->where('status', BookingRequestStatus::DATE_CONFIRMED->value)
            ->with(['client.user'])
            ->get()
            ->map(function ($booking) {
                $clientPseudo = $booking->client?->user?->pseudo
                    ?? ($booking->client?->user?->first_name . ' ' . $booking->client?->user?->last_name)
                    ?? 'Client';

                return [
                    'id' => 'booking_' . $booking->id,
                    'title' => 'Tattoo - ' . $clientPseudo,
                    'start' => $booking->appointment_datetime->toIso8601String(),
                    'end' => $booking->appointment_datetime->copy()->addMinutes($booking->appointment_duration_minutes ?? 120)->toIso8601String(),
                    'backgroundColor' => '#D4B59E',
                    'borderColor' => '#D4B59E',
                    'textColor' => '#0A0A0A',
                    'extendedProps' => [
                        'type' => 'appointment',
                        'booking_id' => $booking->id,
                        'booking_request_id' => $booking->id,
                        'client_name' => $clientPseudo,
                        'client_pseudo' => $booking->client?->user?->pseudo ?? $clientPseudo,
                        'body_zone' => $booking->body_zone ?? '',
                        'tattoo_size' => $booking->tattoo_size ?? '',
                        'deposit_paid' => $booking->deposit_paid_at !== null,
                        'deposit_amount' => (float) ($booking->deposit_amount ?? 0),
                        'total_price' => (float) ($booking->total_price ?? $booking->estimated_total_price ?? 0),
                        'status' => 'scheduled', // Pas d'appointment = scheduled par défaut
                        'notes' => '',
                    ],
                ];
            })
            ->toArray();

        // Fusionner tous les events
        $events = array_merge($calendarEvents, $appointments, $bookingEvents);

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.calendar', compact('tattooer', 'events', 'pendingCount', 'unreadCount'));
    }

    /**
     * Store un nouvel événement calendrier
     */
    public function calendarStore(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:appointment,break,vacation,closure',
            'title' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
        ]);

        $tattooer = $this->artisan();

        // Couleur selon le type
        $color = match($validated['type']) {
            'appointment' => '#06D6A0',
            'break' => '#F77F00',
            'vacation' => '#E63946',
            'closure' => '#2E3440',
            default => '#D4B59E',
        };

        try {
            $event = CalendarEvent::create([
                'bookable_type' => $tattooer->getMorphClass(),
                'bookable_id' => $tattooer->id,
                'title' => $validated['title'],
                'type' => $validated['type'],
                'start_datetime' => $validated['start_datetime'],
                'end_datetime' => $validated['end_datetime'],
                'color' => $color,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Événement créé avec succès',
                'event' => $event->toFullCalendarEvent(),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur création CalendarEvent', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Une erreur est survenue. Veuillez réessayer.',
            ], 500);
        }
    }

    /**
     * Update un événement calendrier (drag & drop)
     */
    public function calendarUpdate(Request $request, $event)
    {
        $validated = $request->validate([
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after:start_datetime',
        ]);

        $tattooer = $this->artisan();
        $calendarEvent = CalendarEvent::where('id', $event)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->where('bookable_id', $tattooer->id)
            ->first();

        if (!$calendarEvent) {
            return response()->json(['success' => false, 'error' => 'Événement non trouvé'], 404);
        }

        $calendarEvent->update([
            'start_datetime' => $validated['start_datetime'],
            'end_datetime' => $validated['end_datetime'] ?? $calendarEvent->end_datetime,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Événement mis à jour',
        ]);
    }

    /**
     * Delete un événement calendrier
     */
    public function calendarDestroy($event, Request $request)
    {
        $tattooer = $this->artisan();
        $calendarEvent = CalendarEvent::where('id', $event)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->where('bookable_id', $tattooer->id)
            ->first();

        if (!$calendarEvent) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Événement non trouvé'], 404);
            }
            return redirect()->back()->with('error', 'Événement non trouvé');
        }

        if (!$calendarEvent->canBeDeleted()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Cet événement ne peut pas être supprimé'], 422);
            }
            return redirect()->back()->with('error', 'Cet événement ne peut pas être supprimé');
        }

        $calendarEvent->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Événement supprimé avec succès',
                'redirect' => route($this->routePrefix() . '.calendar')
            ]);
        }

        return redirect()->route($this->routePrefix() . '.calendar')->with('success', 'Événement supprimé avec succès');
    }

    /**
     * Get events for calendar (API)
     */
    public function calendarEvents(Request $request)
    {
        $tattooer = $this->artisan();

        // Récupérer les rendez-vous confirmés comme événements
        $appointments = Appointment::where('bookable_id', $tattooer->id)
            ->where('bookable_type', get_class($tattooer))
            ->where('status', 'scheduled')
            ->with(['bookingRequest.client.user'])
            ->orderBy('start_datetime', 'asc')
            ->get();

        $appointmentEvents = $appointments->map(function($appointment) {
            return [
                'id' => 'appointment_' . $appointment->id,
                'title' => $appointment->title,
                'start' => $appointment->start_datetime->format('Y-m-d\TH:i:s'),
                'end' => $appointment->end_datetime->format('Y-m-d\TH:i:s'),
                'backgroundColor' => '#D4B59E', // beige-peau
                'borderColor' => '#B8955A',
                'textColor' => '#0A0A0A',
                'extendedProps' => [
                    'type' => 'appointment',
                    'appointment_id' => $appointment->id,
                    'booking_request_id' => $appointment->booking_request_id,
                    'client_name' => $appointment->bookingRequest->client->user->name ?? $appointment->bookingRequest->client->user->pseudo,
                    'client_id' => $appointment->bookingRequest->client_id
                ]
            ];
        })->toArray();

        // Récupérer les demandes confirmées comme événements (pour compatibilité)
        $bookingRequests = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', get_class($tattooer))
            ->where('status', 'date_confirmed')
            ->whereDoesntHave('appointment') // Éviter les doublons
            ->with(['client.user'])
            ->orderBy('created_at', 'asc')
            ->get();

        $bookingEvents = $bookingRequests->map(function($booking) {
            return [
                'id' => 'booking_' . $booking->id,
                'title' => 'RDV - ' . ($booking->client->user->name ?? $booking->client->user->pseudo),
                'start' => $booking->confirmed_date ? \Carbon\Carbon::parse($booking->confirmed_date)->format('Y-m-d\TH:i:s') : $booking->created_at->format('Y-m-d\TH:i:s'),
                'end' => $booking->confirmed_date ? \Carbon\Carbon::parse($booking->confirmed_date)->addHours(2)->format('Y-m-d\TH:i:s') : $booking->created_at->addHours(2)->format('Y-m-d\TH:i:s'),
                'backgroundColor' => '#10b981', // vert-succes
                'borderColor' => '#059669',
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => 'appointment',
                    'booking_id' => $booking->id,
                    'client_name' => $booking->client->user->name ?? $booking->client->user->pseudo,
                    'client_id' => $booking->client_id
                ]
            ];
        })->toArray();

        $events = array_merge($appointmentEvents, $bookingEvents);

        return response()->json($events);
    }
}
