<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\BookingRequest;
use App\Models\ClientConsentForm;
use App\Models\TraceabilityRecord;
use App\Models\Appointment;
use App\Models\CalendarEvent;
use App\Enums\BookingRequestStatus;
use App\Actions\CompleteAppointmentAction;
use App\Actions\ReportNoShowAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TattooerController extends Controller
{
    /**
     * Profil public du tattooer (vue interne)
     */
    public function profile()
    {
        $tattooer = auth()->user()->tattooer;
        $cacheService = app(\App\Services\CacheService::class);

        // Charger données depuis cache
        $portfolio = $cacheService->getPortfolio($tattooer);
        $workingHours = $cacheService->getWorkingHours($tattooer);
        $stats = $cacheService->getDashboardStats($tattooer);

        return view('tattooer.profile', compact(
            'tattooer',
            'portfolio',
            'workingHours',
            'stats'
        ));
    }

    /**
     * Gestion demandes projet
     */
    public function requests(Request $request)
    {
        $tattooer = auth()->user()->tattooer;
        $filter = $request->query('status', 'all'); // par défaut "all" pour tout afficher

        // Service pour stats (1 requête au lieu de 5)
        $statsService = app(\App\Services\TattooerStatsService::class);
        $counts = $statsService->getRequestsStats($tattooer);

        // UNE SEULE requête avec eager loading optimisé
        $query = BookingRequest::where('bookable_type', 'App\Models\Tattooer')
            ->where('bookable_id', $tattooer->id)
            ->with(['client.user', 'conversation' => function($query) {
                $query->withCount(['messages as unread_count' => function($q) {
                    $q->where('sender_type', 'client')
                          ->whereNull('read_by_tattooer_at');
                }]);
            }]);

        // Filtrer par statut selon l'onglet
        $query = match($filter) {
            'all'       => $query, // toutes les demandes
            'pending'   => $query->where('status', BookingRequestStatus::PENDING->value),
            'accepted'  => $query->whereIn('status', [
                BookingRequestStatus::ACCEPTED->value,
                BookingRequestStatus::DEPOSIT_REQUESTED->value,
                BookingRequestStatus::DEPOSIT_PAID->value,
            ]),
            'confirmed' => $query->where('status', BookingRequestStatus::DATE_CONFIRMED->value),
            'completed' => $query->where('status', BookingRequestStatus::COMPLETED->value),
            'rejected'  => $query->where('status', BookingRequestStatus::CANCELLED->value), // utiliser CANCELLED pour rejected
            'cancelled' => $query->where('status', BookingRequestStatus::CANCELLED->value),
            default     => $query,
        };

        $requests = $query->orderBy('created_at', 'desc')->get();

        return view('tattooer.requests', compact('requests', 'filter', 'counts'));
    }

    /**
     * Détail demande projet
     */
    public function requestShow(BookingRequest $bookingRequest)
    {
        $tattooer = auth()->user()->tattooer;

        // Vérifier que la demande appartient au tattooer
        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== 'App\\Models\\Tattooer') {
            abort(403);
        }

        // Charger relations nécessaires
        $bookingRequest->load([
            'client.user',
            'conversation',
            'media'
        ]);

        return view('tattooer.request-show', compact('bookingRequest'));
    }

    /**
     * Page des paramètres du tattooer
     */
    public function settings()
    {
        $tattooer = auth()->user()->tattooer;

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        return view('tattooer.settings', compact('tattooer'));
    }

    /**
     * Mettre à jour les paramètres du tattooer
     */
    public function settingsUpdate(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        // Valider les données du formulaire
        $validated = $request->validate([
            // Médias (fichiers)
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:4096',

            // Informations personnelles (user)
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'pseudo' => 'nullable|string|max:255|unique:users,pseudo,' . $tattooer->user_id,
            'email' => 'required|email|unique:users,email,' . $tattooer->user_id,
            'phone' => 'nullable|string|max:20',

            // Informations professionnelles (tattooer)
            'studio_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',

            // Description et styles
            'bio' => 'nullable|string|max:2000',
            'styles' => 'nullable|array',
            'styles.*' => 'string|max:100',
            'custom_styles' => 'nullable|array',
            'custom_style_names' => 'nullable|array',
            'custom_style_names.*' => 'nullable|string|max:100',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'minimum_price' => 'nullable|numeric|min:0|max:10000',

            // Délai d'attente
            'wait_time_weeks_min' => 'nullable|integer|min:0|max:52',
            'wait_time_weeks_max' => 'nullable|integer|min:0|max:52',

            // Réseaux sociaux
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',

            // Paramètres de disponibilité
            'is_available' => 'boolean',
            'accepts_new_clients' => 'boolean',

            // Préférences de notification
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
        ]);

        // Séparer les styles prédéfinis et personnalisés
        $styles = array_values(array_filter(
            $validated['styles'] ?? [],
            fn($s) => $s !== 'Autres' && trim($s) !== ''
        ));
        $customStyleNames = array_values(
            array_filter($validated['custom_style_names'] ?? [], fn($s) => trim($s) !== '')
        );

        // Mettre à jour l'utilisateur
        $tattooer->user->update([
            'first_name' => $validated['first_name'] ?? $tattooer->user->first_name,
            'last_name' => $validated['last_name'] ?? $tattooer->user->last_name,
            'pseudo' => $validated['pseudo'] ?? $tattooer->user->pseudo,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $tattooer->user->phone,
        ]);

        // Mettre à jour le tattooer
        $tattooer->update([
            'first_name' => $validated['first_name'] ?? $tattooer->user->first_name,
            'last_name' => $validated['last_name'] ?? $tattooer->user->last_name,
            'studio_name' => $validated['studio_name'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'country' => $validated['country'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'styles' => $styles,
            'custom_styles' => $customStyleNames,
            'years_of_experience' => $validated['years_of_experience'] ?? null,
            'minimum_price' => $validated['minimum_price'] ?? null,
            'wait_time_weeks_min' => $validated['wait_time_weeks_min'] ?? null,
            'wait_time_weeks_max' => $validated['wait_time_weeks_max'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'facebook' => $validated['facebook'] ?? null,
            'website' => $validated['website'] ?? null,
            'is_available' => $validated['is_available'] ?? false,
            'accepts_new_clients' => $validated['accepts_new_clients'] ?? false,
            'email_notifications' => $validated['email_notifications'] ?? true,
            'sms_notifications' => $validated['sms_notifications'] ?? false,
        ]);

        // Gérer l'upload de l'avatar si présent
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($tattooer->user->hasMedia('avatar')) {
                $tattooer->user->clearMediaCollection('avatar');
            }

            $avatar = $request->file('avatar');
            $tattooer->user->addMedia($avatar)
                ->toMediaCollection('avatar');
        }

        // Gérer l'upload de la bannière si présente
        if ($request->hasFile('banner')) {
            // Supprimer l'ancienne bannière si elle existe
            if ($tattooer->hasMedia('banner')) {
                $tattooer->clearMediaCollection('banner');
            }

            $banner = $request->file('banner');
            $tattooer->addMedia($banner)
                ->toMediaCollection('banner');
        }

        return redirect()->route('tattooer.settings')
            ->with('success', 'Vos paramètres ont été mis à jour avec succès !');
    }

    /**
     * Supprimer l'avatar
     */
    public function deleteAvatar()
    {
        $tattooer = auth()->user()->tattooer;

        if ($tattooer->user->hasMedia('avatar')) {
            $tattooer->user->clearMediaCollection('avatar');
        }

        return response()->json(['success' => true]);
    }

    /**
     * Supprimer la bannière
     */
    public function deleteBanner()
    {
        $tattooer = auth()->user()->tattooer;

        if ($tattooer->hasMedia('banner')) {
            $tattooer->clearMediaCollection('banner');
        }

        return response()->json(['success' => true]);
    }

    /**
     * Tableau de bord du tattooer
     */
    public function dashboard()
    {
        $tattooer = auth()->user()->tattooer;

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Service pour stats (1-2 requêtes au lieu de 5+)
        $statsService = app(\App\Services\TattooerStatsService::class);
        $stats = $statsService->getDashboardStats($tattooer);

        // Demandes récentes
        $recentRequests = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->with(['client.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Rendez-vous à venir (pour l'instant, utilise les demandes confirmées)
        $upcomingAppointments = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->where('status', 'confirmed')
            ->with(['client.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Activité récente
        $recentActivity = [
            'new_requests' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),

            'completed_appointments' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->where('status', 'completed')
                ->where('updated_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return view('tattooer.dashboard', compact('tattooer', 'stats', 'recentRequests', 'upcomingAppointments', 'recentActivity'));
    }

    /**
     * Calendrier du tattooer
     */
    public function calendar()
    {
        $tattooer = auth()->user()->tattooer;
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
            ->filter(fn($apt) => !CalendarEvent::where('appointment_id', $apt->id)->exists()) // Éviter doublons
            ->map(function ($apt) {
                $clientName = $apt->bookingRequest?->client?->user?->pseudo
                    ?? $apt->bookingRequest?->client?->user?->name
                    ?? 'Client';
                $bookingRequest = $apt->bookingRequest;
                return [
                    'id' => 'apt_' . $apt->id,
                    'title' => 'Tattoo → ' . $clientName,
                    'start' => $apt->start_datetime->toIso8601String(),
                    'end' => $apt->end_datetime->toIso8601String(),
                    'backgroundColor' => '#06D6A0',
                    'borderColor' => '#06D6A0',
                    'textColor' => '#FFFFFF',
                    'extendedProps' => [
                        'type' => 'appointment',
                        'appointment_id' => $apt->id,
                        'booking_request_id' => $apt->booking_request_id,
                        'client_name' => $clientName,
                        'client_pseudo' => $apt->bookingRequest?->client?->user?->pseudo ?? $clientName,
                        'body_zone' => $bookingRequest?->body_zone ?? '',
                        'tattoo_size' => $bookingRequest?->tattoo_size ?? '',
                        'deposit_paid' => $bookingRequest?->deposit_paid_at !== null,
                        'deposit_amount' => (float) ($bookingRequest?->deposit_amount ?? 0),
                        'total_price' => (float) ($bookingRequest?->total_price ?? $bookingRequest?->estimated_total_price ?? 0),
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
            ->whereDoesntHave('appointment')
            ->with(['client.user'])
            ->get()
            ->map(function ($booking) {
                $clientName = $booking->client?->user?->pseudo ?? $booking->client?->user?->name ?? 'Client';
                $startDate = $booking->confirmed_date
                    ? \Carbon\Carbon::parse($booking->confirmed_date)
                    : $booking->created_at;
                return [
                    'id' => 'booking_' . $booking->id,
                    'title' => 'Tattoo → ' . $clientName,
                    'start' => $startDate->format('Y-m-d\TH:i:s'),
                    'end' => $startDate->copy()->addHours(2)->format('Y-m-d\TH:i:s'),
                    'backgroundColor' => '#D4B59E',
                    'borderColor' => '#D4B59E',
                    'textColor' => '#0A0A0A',
                    'extendedProps' => [
                        'type' => 'appointment',
                        'booking_id' => $booking->id,
                        'booking_request_id' => $booking->id,
                        'client_name' => $clientName,
                        'client_pseudo' => $booking->client?->user?->pseudo ?? $clientName,
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

        return view('tattooer.calendar', compact('tattooer', 'events'));
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

        $tattooer = auth()->user()->tattooer;

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
                'error' => 'Erreur serveur : ' . $e->getMessage(),
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

        $tattooer = auth()->user()->tattooer;
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
    public function calendarDestroy($event)
    {
        $tattooer = auth()->user()->tattooer;
        $calendarEvent = CalendarEvent::where('id', $event)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->where('bookable_id', $tattooer->id)
            ->first();

        if (!$calendarEvent) {
            return response()->json(['success' => false, 'error' => 'Événement non trouvé'], 404);
        }

        if (!$calendarEvent->canBeDeleted()) {
            return response()->json(['success' => false, 'error' => 'Cet événement ne peut pas être supprimé'], 422);
        }

        $calendarEvent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Événement supprimé',
        ]);
    }

    /**
     * Get events for calendar (API)
     */
    public function calendarEvents(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        // Récupérer les rendez-vous confirmés comme événements
        $appointments = Appointment::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
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
            ->where('bookable_type', 'App\Models\Tattooer')
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

    /**
     * Get event color by type
     */
    private function getEventColor(string $type): string
    {
        $colors = [
            'appointment' => '#10b981', // vert-succes
            'break' => '#f59e0b',       // ambre-warning
            'vacation' => '#ef4444',    // rouge-alerte
            'closure' => '#6b7280',     // titane
        ];

        return $colors[$type] ?? $colors['appointment'];
    }

    /**
     * Afficher la conversation avec un client
     */
    public function messageShow(BookingRequest $bookingRequest)
    {
        $tattooer = auth()->user()->tattooer;

        // Vérifier que la demande appartient bien au tattooer
        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== 'App\Models\Tattooer') {
            abort(403, 'Non autorisé');
        }

        // Créer la conversation si elle n'existe pas
        if (!$bookingRequest->conversation) {
            $conversation = \App\Models\Conversation::create([
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $bookingRequest->client_id,
                'tattooer_id' => $tattooer->id,
                'status' => 'active',
            ]);

            // Marquer les messages comme lus pour le tattooer
            $bookingRequest->conversation = $conversation;
        }

        // Charger la conversation avec les messages
        $conversation = $bookingRequest->conversation->load(['messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        // Marquer les messages du client comme lus
        $conversation->messages()
            ->where('sender_type', 'client')
            ->whereNull('read_by_tattooer_at')
            ->update(['read_by_tattooer_at' => now()]);

        // Récupérer les messages pour la vue
        $messages = $conversation->messages;

        return view('tattooer.message-show', compact('bookingRequest', 'conversation', 'messages'));
    }

    /**
     * Envoyer un message dans la conversation
     */
    /**
 * Envoyer un message dans la conversation (avec gestion des dessins)
 */
public function messageSend(Request $request, BookingRequest $bookingRequest)
{
    $tattooer = auth()->user()->tattooer;

    if ($bookingRequest->bookable_id !== $tattooer->id ||
        $bookingRequest->bookable_type !== 'App\Models\Tattooer') {
        abort(403, 'Non autorisé');
    }

    $validated = $request->validate([
        'content' => 'nullable|string|max:2000',
        'attachments' => 'nullable|array',
        'attachments.*' => 'file|mimes:jpeg,jpg,png,gif,webp,pdf|max:10240',
        'design_type' => 'nullable|in:new_design,modification',
        'coverage_type' => 'nullable|in:included,send_free',
    ]);

    // Vérifier qu'il y a du contenu ou des pièces jointes
    if (empty($validated['content']) && !$request->hasFile('attachments')) {
        return redirect()->back()->with('error', 'Veuillez entrer un message ou ajouter une pièce jointe');
    }

    // Créer la conversation si elle n'existe pas
    if (!$bookingRequest->conversation) {
        $conversation = \App\Models\Conversation::create([
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $bookingRequest->client_id,
            'tattooer_id' => $tattooer->id,
            'status' => 'active',
        ]);
        $bookingRequest->setRelation('conversation', $conversation);
    }

    // Créer le message
    $messageContent = $validated['content'] ?? '';
    $designLabel = null;

    // ═══ DÉTERMINER LE LABEL DE TRACKING AVANT DE CRÉER LE MESSAGE ═══
    $designType = $validated['design_type'] ?? null;
    $coverageType = $validated['coverage_type'] ?? null;

    if ($designType && $request->hasFile('attachments')) {
        $bookingRequest->refresh();

        // Mettre à jour les compteurs
        if ($coverageType === 'included' || $coverageType === 'send_free') {
            if ($designType === 'new_design') {
                $bookingRequest->recordNewDesign();
                $designLabel = "🎨 Dessin complet #{$bookingRequest->designs_sent_count}";
            } else {
                $bookingRequest->recordModification();
                $tracker = $bookingRequest->design_modifications_tracker ?? [];
                $currentDesign = $bookingRequest->designs_sent_count;
                $modifCount = $tracker[(string) $currentDesign] ?? 0;
                $designLabel = "✏️ Modification #{$modifCount} du dessin #{$currentDesign}";
            }
        } else {
            $designLabel = ($designType === 'new_design')
                ? "🎨 Nouveau dessin (supplément demandé)"
                : "✏️ Modification (supplément demandé)";
        }

        // Message système de tracking
        $systemContent = match($coverageType) {
            'included'  => "{$designLabel} envoyé (inclus dans le forfait).",
            'send_free' => "⚠️ {$designLabel} envoyé (hors forfait — envoi gracieux).",
            default     => "{$designLabel} envoyé.",
        };
    }

    // ═══ CRÉER UN SEUL MESSAGE AVEC CONTENU + LABEL ═══
    $finalContent = $messageContent;
    if ($designLabel && $request->hasFile('attachments')) {
        if (empty(trim($messageContent))) {
            // Pas de contenu utilisateur → utiliser le label comme contenu principal
            $finalContent = match($coverageType) {
                'included'  => "{$designLabel} envoyé (inclus dans le forfait).",
                'send_free' => "⚠️ {$designLabel} envoyé (hors forfait — envoi gracieux).",
                default     => "{$designLabel} envoyé.",
            };
        } else {
            // Contenu utilisateur présent → ajouter le label en préfixe
            $finalContent = match($coverageType) {
                'included'  => "{$designLabel} envoyé (inclus dans le forfait).\n\n{$messageContent}",
                'send_free' => "⚠️ {$designLabel} envoyé (hors forfait — envoi gracieux).\n\n{$messageContent}",
                default     => "{$designLabel} envoyé.\n\n{$messageContent}",
            };
        }
    }

    $message = \App\Models\Message::create([
        'conversation_id' => $bookingRequest->conversation->id,
        'sender_type' => 'tattooer',
        'sender_id' => $tattooer->user_id,
        'content' => $finalContent,
        'read_by_client_at' => null,
        'read_by_tattooer_at' => now(),
    ]);

    // Gérer les pièces jointes
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $attachment) {
            $message->addMedia($attachment)
                ->withCustomProperties([
                    'design_type' => $validated['design_type'] ?? null,
                    'coverage_type' => $validated['coverage_type'] ?? null,
                    'uploaded_by' => 'tattooer',
                ])
                ->toMediaCollection('attachments');
        }
    }

    // ═══ GESTION DU COMPTAGE DESSINS (déjà fait plus haut) ═══
    // Le tracking est déjà intégré dans le message unique ci-dessus

    // Si envoi gratuit hors forfait
    if ($coverageType === 'send_free') {
        $bookingRequest->update([
            'overage_decision' => 'send_free',
            'overage_reason'   => $designType === 'new_design'
                ? 'Dessin complet supplémentaire offert'
                : 'Modification supplémentaire offerte',
        ]);
    }

    return redirect()->route('tattooer.message.show', $bookingRequest)
        ->with('success', 'Message envoyé !');
}


    /**
     * Liste des conversations/messages
     */
    public function messages()
    {
        $tattooer = auth()->user()->tattooer;

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Récupérer les conversations/messages
        $conversations = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->has('conversation')
            ->with(['client.user', 'conversation.messages'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tattooer.messages', compact('tattooer', 'conversations'));
    }

    /**
     * Clients du tattooer
     */
    public function clients(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        // Récupérer les IDs clients uniques qui ont PAYÉ L'ACOMPTE avec ce tattooer
        $clientIds = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')  // ← UNIQUEMENT après acompte payé
            ->distinct()
            ->pluck('client_id');

        // Construire la query sur le modèle Client (pas BookingRequest)
        $query = \App\Models\Client::whereIn('id', $clientIds)
            ->with(['user.media', 'media', 'tattooHistory']);

        // Recherche
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('pseudo', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('pseudo', 'LIKE', "%{$search}%")
                         ->orWhere('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $clients = $query->orderBy('updated_at', 'desc')->paginate(12);

        // Ajouter des stats par client pour ce tattooer
        $clients->getCollection()->transform(function ($client) use ($tattooer) {
            // Stats spécifiques à CE tattooer
            $bookings = BookingRequest::where('client_id', $client->id)
                ->where('bookable_id', $tattooer->id)
                ->where('bookable_type', $tattooer->getMorphClass())
                ->get();

            $client->tattooer_stats = (object) [
                'total_requests' => $bookings->count(),
                'completed' => $bookings->where('status', 'completed')->count(),
                'total_paid' => $bookings->sum('total_deposit_amount'),
                'last_request_at' => $bookings->max('created_at'),
            ];

            return $client;
        });

        return view('tattooer.clients', compact('tattooer', 'clients'));
    }

    /**
     * Fiche client détaillée (PRO uniquement)
     */
    public function clientShow(\App\Models\Client $client)
    {
        $tattooer = auth()->user()->tattooer;

        // Vérifier que ce client a au moins une demande avec acompte payé chez ce tattooer
        $hasRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        if (!$hasRelation) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        // Charger les relations
        $client->load(['user.media', 'media']);

        // Toutes les demandes de CE client avec CE tattooer
        $bookingRequests = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->with(['conversation.messages.media', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Historique tattoos
        $history = $client->tattooHistory()
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->orderBy('tattoo_date', 'desc')
            ->get();

        // Consentements par booking request (booking_request_id)
        $consents = ClientConsentForm::whereIn('booking_request_id', $bookingRequests->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->keyBy('booking_request_id');

        // Appointments liés aux demandes de ce tattooer
        $appointments = collect();
        foreach ($bookingRequests as $br) {
            if ($br->appointment) {
                $appointments->push($br->appointment);
            }
        }

        // Traçabilités par appointment

        // Traçabilités par appointment
        $traceabilities = TraceabilityRecord::whereIn('appointment_id', $appointments->pluck('id'))
            ->with('media')
            ->get()
            ->keyBy('appointment_id');

        // Stats résumé
        $stats = (object) [
            'total_requests' => $bookingRequests->count(),
            'completed' => $bookingRequests->where('status', 'completed')->count(),
            'cancelled' => $bookingRequests->whereIn('status', ['cancelled', 'rejected'])->count(),
            'no_shows' => $client->no_show_count,
            'total_paid' => $bookingRequests->sum('total_deposit_amount'),
            'total_appointments' => $appointments->count(),
            'first_visit' => $bookingRequests->min('created_at'),
            'last_visit' => $bookingRequests->max('created_at'),
        ];

        // Médias sauvegardés depuis les chats (Phase 2 pour la gestion complète)
        $chatMedia = collect();
        foreach ($bookingRequests as $br) {
            if ($br->conversation) {
                foreach ($br->conversation->messages as $msg) {
                    foreach ($msg->getMedia('attachments') as $media) {
                        if (str_starts_with($media->mime_type, 'image/')) {
                            $chatMedia->push($media);
                        }
                    }
                }
            }
        }

        return view('tattooer.client-show', compact(
            'client', 'tattooer', 'bookingRequests', 'history',
            'appointments', 'consents', 'traceabilities', 'stats', 'chatMedia'
        ));
    }

    /**
     * Enregistrer le consentement pour une booking request
     */
    public function storeConsent(Request $request, BookingRequest $bookingRequest)
    {
        $tattooer = auth()->user()->tattooer;

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
     * Enregistrer la traçabilité pour un rendez-vous
     */
    public function storeTraceability(Request $request, \App\Models\Appointment $appointment)
    {
        $tattooer = auth()->user()->tattooer;

        // Vérifier propriété via booking request
        $bookingRequest = $appointment->bookingRequest;
        if (!$bookingRequest || $bookingRequest->bookable_id !== $tattooer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'needle_brand' => 'nullable|string|max:255',
            'needle_lot_number' => 'nullable|string|max:255',
            'cartridge_brand' => 'nullable|string|max:255',
            'cartridge_lot_number' => 'nullable|string|max:255',
            'inks' => 'nullable|array',
            'inks.*' => 'nullable|array',
            'inks.*.brand' => 'nullable|string|max:255',
            'inks.*.color' => 'nullable|string|max:255',
            'inks.*.lot_number' => 'nullable|string|max:255',
            'sterilization_date' => 'nullable|date',
            'sterilization_lot_number' => 'nullable|string|max:255',
            'autoclave_cycle_number' => 'nullable|string|max:255',
            'other_supplies' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
            'lot_photos' => 'nullable|array|max:5',
            'lot_photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Filtrer les encres vides
        if (isset($validated['inks'])) {
            $validated['inks'] = array_values(array_filter($validated['inks'], function ($ink) {
                return !empty(trim($ink['brand'] ?? '')) || !empty(trim($ink['color'] ?? '')) || !empty(trim($ink['lot_number'] ?? ''));
            }));
            if (empty($validated['inks'])) {
                unset($validated['inks']);
            }
        }

        // Préparer les données pour le modèle (adapter aux colonnes existantes)
        $traceData = [
            'tattooer_id' => $tattooer->id,
            'appointment_id' => $appointment->id,
            'procedure_date' => now()->format('Y-m-d'),
            'sterile_equipment' => [
                'needles' => [
                    ['brand' => $validated['needle_brand'] ?? '', 'lot_number' => $validated['needle_lot_number'] ?? ''],
                    ['brand' => $validated['cartridge_brand'] ?? '', 'lot_number' => $validated['cartridge_lot_number'] ?? '']
                ],
                'inks' => $validated['inks'] ?? [],
                'sterilization_date' => $validated['sterilization_date'] ?? null,
                'sterilization_lot_number' => $validated['sterilization_lot_number'] ?? '',
                'autoclave_cycle_number' => $validated['autoclave_cycle_number'] ?? ''
            ],
            'procedure_notes' => $validated['other_supplies'] ?? '',
            'equipment_notes' => $validated['notes'] ?? '',
            'tattooer_verified_traceability' => true,
            'verified_at' => now(),
        ];

        $traceability = TraceabilityRecord::updateOrCreate(
            ['appointment_id' => $appointment->id],
            $traceData
        );

        // Upload photos de lots via Media Library
        if ($request->hasFile('lot_photos')) {
            foreach ($request->file('lot_photos') as $photo) {
                $traceability->addMedia($photo)->toMediaCollection('lot_photos');
            }
        }

        return redirect()->to(url()->previous() . '#trace')->with('success', '✅ Traçabilité enregistrée.');
    }

    /**
     * Mettre à jour les notes privées du client
     */
    public function updateClientNotes(Request $request, \App\Models\Client $client)
    {
        $tattooer = auth()->user()->tattooer;

        // Vérifier la relation
        $hasRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->exists();

        if (!$hasRelation) {
            abort(403);
        }

        $request->validate(['notes' => 'nullable|string|max:5000']);
        $client->update(['notes' => $request->notes]);

        return back()->with('success', 'Notes enregistrées.');
    }

    /**
     * Upload photos du tattoo réalisé
     */
    public function uploadClientTattooPhotos(Request $request, \App\Models\Client $client, BookingRequest $bookingRequest)
    {
        $tattooer = auth()->user()->tattooer;

        if ($bookingRequest->bookable_id !== $tattooer->id) {
            abort(403);
        }

        $request->validate([
            'photos' => 'required|array|max:10',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        foreach ($request->file('photos') as $photo) {
            $bookingRequest->addMedia($photo)
                ->withCustomProperties([
                    'type' => 'tattoo_result',
                    'uploaded_by' => 'tattooer',
                    'uploaded_at' => now()->toISOString(),
                ])
                ->toMediaCollection('tattoo_results');
        }

        return redirect()->to(url()->previous() . '#media')->with('success', '📸 Photos enregistrées.');
    }

    /**
     * Supprimer un média client
     */
    public function deleteClientMedia(\App\Models\Client $client, $mediaId)
    {
        $tattooer = auth()->user()->tattooer;

        // Vérifier la relation client-tattooer
        $hasRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->exists();

        if (!$hasRelation) {
            abort(403);
        }

        // Trouver le media dans les booking requests de ce tattooer
        $bookingRequestIds = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->pluck('id');

        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::where('id', $mediaId)
            ->where('model_type', BookingRequest::class)
            ->whereIn('model_id', $bookingRequestIds)
            ->firstOrFail();

        $media->delete();

        return back()->with('success', 'Photo supprimée.');
    }

    /**
     * Portfolio du tattooer
     */
    public function portfolio()
    {
        $tattooer = auth()->user()->tattooer;

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Récupérer les images du portfolio par collections
        $tattoos = $tattooer->getMedia('portfolio')
            ->sortByDesc('created_at')
            ->values();

        $drawings = $tattooer->getMedia('drawings')
            ->sortByDesc('created_at')
            ->values();

        $beforeAfter = $tattooer->getMedia('before_after')
            ->sortByDesc('created_at')
            ->values();

        // Debug temporaire pour voir les collections
        Log::info('Portfolio collections', [
            'tattoos_count' => $tattoos->count(),
            'drawings_count' => $drawings->count(),
            'before_after_count' => $beforeAfter->count(),
            'all_media_count' => $tattooer->media->count()
        ]);

        return view('tattooer.portfolio', compact('tattooer', 'tattoos', 'drawings', 'beforeAfter'));
    }

    /**
     * Upload d'images pour le portfolio
     */
    public function portfolioUpload(Request $request)
    {
        try {
            $tattooer = auth()->user()->tattooer;
            $collection = $request->input('collection', 'portfolio');

            // Validation
            $request->validate([
                'images' => 'required|array|max:10', // max 10 images par upload
                'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB max par image
                'collection' => 'required|in:portfolio,drawings,before_after'
            ]);

            $uploadedCount = 0;
            $maxImages = 20; // Limite FREE

            // Vérifier le nombre total d'images déjà présentes
            $currentCount = $tattooer->getMedia($collection)->count();
            $newImagesCount = count($request->file('images'));

            if ($currentCount + $newImagesCount > $maxImages) {
                return response()->json([
                    'success' => false,
                    'message' => "Limite d'images dépassée. Maximum {$maxImages} images autorisées ({$currentCount} déjà présentes)."
                ], 422);
            }

            // Upload des images
            foreach ($request->file('images') as $image) {
                $tattooer->addMedia($image)
                    ->withCustomProperties([
                        'type' => $collection === 'portfolio' ? 'tattoo' : $collection,
                        'uploaded_at' => now()->toISOString()
                    ])
                    ->toMediaCollection($collection);
                $uploadedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "{$uploadedCount} image(s) uploadée(s) avec succès !"
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur upload portfolio: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stockage des photos avant/après
     */
    public function portfolioBeforeAfterStore(Request $request)
    {
        try {
            $tattooer = auth()->user()->tattooer;

            // Validation
            $request->validate([
                'before' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
                'after' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
                'description' => 'nullable|string|max:500'
            ]);

            // Vérifier la limite d'images
            $currentCount = $tattooer->getMedia('before_after')->count();
            $maxImages = 20;

            if ($currentCount + 2 > $maxImages) {
                return response()->json([
                    'success' => false,
                    'message' => "Limite d'images dépassée. Maximum {$maxImages} images autorisées."
                ], 422);
            }

            // Générer un pair_id pour lier avant/après
            $pairId = uniqid('pair_') . '_' . time();

            // Upload avant
            $beforeMedia = $tattooer->addMedia($request->file('before'))
                ->withCustomProperties([
                    'type' => 'before',
                    'pair_id' => $pairId,
                    'description' => $request->input('description'),
                    'uploaded_at' => now()->toISOString()
                ])
                ->toMediaCollection('before_after');

            // Upload après
            $afterMedia = $tattooer->addMedia($request->file('after'))
                ->withCustomProperties([
                    'type' => 'after',
                    'pair_id' => $pairId,
                    'description' => $request->input('description'),
                    'uploaded_at' => now()->toISOString()
                ])
                ->toMediaCollection('before_after');

            return response()->json([
                'success' => true,
                'message' => 'Photos avant/après uploadées avec succès !'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur upload avant/après: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un média du portfolio
     */
    public function portfolioDestroy($media)
    {
        try {
            $tattooer = auth()->user()->tattooer;

            // Récupérer le média
            $mediaItem = $tattooer->media()->find($media);

            if (!$mediaItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image non trouvée'
                ], 404);
            }

            // Vérifier que le média appartient bien au tattooer
            if ($mediaItem->model_id != $tattooer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé'
                ], 403);
            }

            // Supprimer le média
            $mediaItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur suppression média: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une paire avant/après
     */
    public function portfolioBeforeAfterDestroy($beforeId, $afterId)
    {
        try {
            $tattooer = auth()->user()->tattooer;

            // Récupérer les médias
            $beforeMedia = $tattooer->media()->find($beforeId);
            $afterMedia = $tattooer->media()->find($afterId);

            if (!$beforeMedia || !$afterMedia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Images non trouvées'
                ], 404);
            }

            // Vérifier que les médias appartiennent bien au tattooer
            if ($beforeMedia->model_id != $tattooer->id || $afterMedia->model_id != $tattooer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé'
                ], 403);
            }

            // Supprimer les deux médias
            $beforeMedia->delete();
            $afterMedia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Paire avant/après supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur suppression avant/après: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour les horaires du tattooer
     */
    public function settingsUpdateSchedule(Request $request)
    {
        try {
            $tattooer = auth()->user()->tattooer;

            // Valider les données du formulaire
            $validated = $request->validate([
                'working_hours' => 'nullable|array',
                'working_hours.*.is_open' => 'nullable|string',
                'working_hours.*.open' => 'nullable|string',
                'working_hours.*.close' => 'nullable|string',
                'working_hours.*.break_start' => 'nullable|string',
                'working_hours.*.break_end' => 'nullable|string',
            ]);

            // Nettoyer les horaires existants
            $workingHours = [];

            if (isset($validated['working_hours'])) {
                foreach ($validated['working_hours'] as $day => $dayData) {
                    // Si le jour est fermé, ne garder que le statut fermé
                    if (!isset($dayData['is_open']) || $dayData['is_open'] !== '1') {
                        $workingHours[$day] = [
                            'open' => null,
                            'close' => null,
                            'break_start' => null,
                            'break_end' => null,
                        ];
                    } else {
                        // Si le jour est ouvert, garder les horaires
                        $workingHours[$day] = [
                            'open' => $dayData['open'] ?? null,
                            'close' => $dayData['close'] ?? null,
                            'break_start' => $dayData['break_start'] ?? null,
                            'break_end' => $dayData['break_end'] ?? null,
                        ];
                    }
                }
            }

            // Mettre à jour le tattooer
            $tattooer->update([
                'working_hours' => json_encode($workingHours),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Horaires mis à jour avec succès !'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour horaires: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour des horaires: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Paiements du tattooer
     */
    public function payments()
    {
        $tattooer = auth()->user()->tattooer;

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Récupérer les paiements (pour l'instant, utilise les demandes confirmées)
        $payments = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->where('status', 'confirmed')
            ->with(['client.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistiques des paiements
        $paymentStats = [
            'total_earned' => $payments->sum('estimated_total_price'),
            'this_month' => $payments->where('created_at', '>=', now()->startOfMonth())->sum('estimated_total_price'),
            'pending_deposits' => 0, // TODO: Calculer les acomptes en attente
        ];

        $stats = [
            'total_revenue' => $paymentStats['total_earned'],
            'total_payments' => $payments->count(),
            'this_month' => $paymentStats['this_month'],
        ];

        return view('tattooer.payments', compact('tattooer', 'payments', 'paymentStats', 'stats'));
    }

    /**
     * Accepter une demande de réservation
     */
    public function acceptRequest(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer connecté
        $tattooer = auth()->user()->tattooer;

        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== 'App\Models\Tattooer') {
            abort(403, 'Non autorisé');
        }

        // Valider les données du formulaire
        $validated = $request->validate([
            'price_estimate_min' => 'required|numeric|min:0',
            'price_estimate_max' => 'required|numeric|min:0',
            'proposed_dates' => 'nullable|array|max:3',
            'proposed_dates.*.date' => 'required|date|after:today',
            'proposed_dates.*.period' => 'nullable|in:morning,afternoon,evening',
            'included_design_versions' => 'required|integer|min:1',
            'modifications_per_design' => 'required|integer|min:0',
            'total_deposit_amount' => 'required|numeric|min:0',
            'client_payment_deadline_days' => 'required|integer|min:1',
        ]);

        // Mettre à jour le statut de la demande
        $bookingRequest->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Mettre à jour avec les données du formulaire d'acceptation
        $bookingRequest->update([
            'price_estimate_min' => $validated['price_estimate_min'],
            'price_estimate_max' => $validated['price_estimate_max'],
            'proposed_dates' => $validated['proposed_dates'] ?? [],
            'included_design_versions' => $validated['included_design_versions'],
            'modifications_per_design' => $validated['modifications_per_design'],
            'total_deposit_amount' => $validated['total_deposit_amount'],
            'client_payment_deadline_days' => $validated['client_payment_deadline_days'],
            'deposit_deadline' => now()->addDays((int)$validated['client_payment_deadline_days']),
            'date_selection_deadline' => now()->addHours(48), // 48h pour choisir les dates
        ]);

        // Créer une conversation si elle n'existe pas
        if (!$bookingRequest->conversation) {
            $conversation = \App\Models\Conversation::create([
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $bookingRequest->client_id,
                'tattooer_id' => $tattooer->id,
                'status' => 'active',
            ]);

            // Envoyer le message d'acceptation
            $conversation->messages()->create([
                'sender_type' => 'tattooer',
                'sender_id' => $tattooer->id,
                'content' => "Bonjour ! \n\n" .
                           "J'accepte votre demande de tattoo avec plaisir !\n\n" .
                           " Zone : {$bookingRequest->body_zone}\n" .
                           " Prix : {$validated['price_estimate_min']}€ - {$validated['price_estimate_max']}€\n" .
                           " Acompte : {$validated['total_deposit_amount']}€\n\n" .
                           "N'hésitez pas à me contacter si vous avez des questions !",
                'read_by_client_at' => null,
                'read_by_tattooer_at' => now(),
            ]);
        }

        // Envoyer une notification au client
        // TODO: Implémenter le système de notifications

        return redirect()->route('tattooer.request.show', $bookingRequest)
            ->with('success', 'Demande acceptée avec succès !');
    }

    /**
     * Refuser une demande de réservation
     */
    public function requestReject(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer connecté
        $tattooer = auth()->user()->tattooer;

        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== 'App\Models\Tattooer') {
            abort(403, 'Non autorisé');
        }

        // Valider le message optionnel
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $reason = $validated['rejection_reason'] ?? null;
        $defaultReason = 'Demande refusée par l\'artiste.';

        // Mettre à jour le statut de la demande
        $bookingRequest->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'cancellation_reason' => $reason ?: $defaultReason,
        ]);

        // Créer une conversation si elle n'existe pas + envoyer un message
        $conversation = $bookingRequest->conversation;

        if (!$conversation) {
            $conversation = \App\Models\Conversation::create([
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $bookingRequest->client_id,
                'tattooer_id' => $tattooer->id,
                'status' => 'closed',
            ]);
        } else {
            $conversation->update(['status' => 'closed']);
        }

        // Message système de rejet
        $messageContent = "❌ L'artiste a décliné votre demande.";
        if ($reason) {
            $messageContent .= "\n\n💬 Message de l'artiste :\n\"{$reason}\"";
        }

        $conversation->messages()->create([
            'sender_type' => 'system',
            'sender_id'   => null,
            'content'     => $messageContent,
        ]);

        // TODO: Envoyer notification au client

        return redirect()->route('tattooer.request.show', $bookingRequest)
            ->with('success', 'Demande refusée avec succès !');
    }

    /**
     * Re-proposer de nouvelles dates après refus client
     */
    public function reproposeDates(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer connecté
        $tattooer = auth()->user()->tattooer;

        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== 'App\Models\Tattooer') {
            abort(403, 'Non autorisé');
        }

        // Valider les dates proposées
        $validated = $request->validate([
            'proposed_dates' => 'required|json',
        ]);

        $datesData = json_decode($validated['proposed_dates'], true);

        // Gérer différents formats possibles
        if (isset($datesData['selectedDates'])) {
            // Format avec selectedDates (notre JavaScript)
            $dates = $datesData['selectedDates'];
        } else {
            // Format direct (Livewire standard)
            $dates = $datesData;
        }

        abort_unless(is_array($dates) && count($dates) >= 1 && count($dates) <= 3, 422, 'Sélectionnez 1 à 3 dates.');

        // Nettoyer et valider chaque date
        $cleanDates = [];
        foreach ($dates as $date) {
            if (isset($date['date'])) {
                $cleanDates[] = [
                    'date' => $date['date'],
                    'period' => !empty($date['period']) ? $date['period'] : null
                ];
            }
        }

        if (empty($cleanDates)) {
            abort(422, 'Sélectionnez au moins une date valide.');
        }

        // Mettre à jour la booking request avec les nouvelles dates
        $bookingRequest->update([
            'proposed_dates' => $cleanDates,
            'client_selected_dates' => null,
            'client_dates_selected_at' => null,
            'date_selection_deadline' => now()->addHours(48),
        ]);

        // Envoyer un message système dans le chat
        $conversation = $bookingRequest->conversation;
        if ($conversation) {
            $datesFormatted = collect($cleanDates)->map(function ($d) {
                $date = \Carbon\Carbon::parse($d['date'])->translatedFormat('l d F Y');
                $period = match ($d['period'] ?? '') {
                    'morning' => 'matin',
                    'afternoon' => 'après-midi',
                    '' => '',  // Période vide = journée entière
                    default => $d['period'] ?? '',
                };
                return $date . ($period ? " ($period)" : '');
            })->join(', ');

            $conversation->messages()->create([
                'sender_id' => auth()->id(),
                'sender_type' => 'App\\Models\\User',
                'content' => "📅 Nouvelles dates proposées : {$datesFormatted}. Merci de sélectionner votre préférence.",
                'read_by_tattooer_at' => now(),
            ]);

            $conversation->update([
                'last_message_at' => now(),
            ]);
        }

        // Notifier le client
        $client = $bookingRequest->client?->user;
        if ($client) {
            $client->notifications()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\\Notifications\\NewDatesProposedNotification',
                'data' => [
                    'title' => 'Nouvelles dates proposées',
                    'message' => "L'artiste vous propose de nouvelles dates pour votre projet.",
                    'booking_request_id' => $bookingRequest->id,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Nouvelles dates proposées au client !');
    }

    /**
     * Marquer un rendez-vous comme terminé
     */
    public function completeAppointment(Request $request, Appointment $appointment)
    {
        // Vérifier que le tattooer est bien le propriétaire
        $this->authorizeAppointmentOwner($appointment);

        // Vérifier que le RDV est bien passé (end_datetime < now)
        if ($appointment->end_datetime->isFuture()) {
            return back()->with('error', 'Ce rendez-vous n\'est pas encore terminé.');
        }

        $validated = $request->validate([
            'completion_notes' => 'nullable|string|max:1000',
        ]);

        $action = new CompleteAppointmentAction();
        $action->execute($appointment, 'tattooer', $validated['completion_notes'] ?? null);

        return back()->with('success', 'Rendez-vous marqué comme terminé !');
    }

    /**
     * Signaler un no-show (client absent)
     */
    public function reportNoShow(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointmentOwner($appointment);

        $validated = $request->validate([
            'no_show_reason' => 'nullable|string|max:1000',
        ]);

        $action = new ReportNoShowAction();
        $action->execute($appointment, 'tattooer', $validated['no_show_reason'] ?? null);

        return back()->with('success', 'No-show signalé. Notre équipe va examiner la situation.');
    }

    /**
     * Vérifier que le tattooer connecté est bien le propriétaire du RDV
     */
    private function authorizeAppointmentOwner(Appointment $appointment): void
    {
        $bookingRequest = $appointment->bookingRequest;
        $user = auth()->user();

        // Adapter selon la logique polymorphique (bookable_type/bookable_id)
        abort_unless(
            $bookingRequest &&
            $bookingRequest->bookable_type === get_class($user->tattooer) &&
            $bookingRequest->bookable_id === $user->tattooer?->id,
            403,
            'Vous n\'êtes pas autorisé à modifier ce rendez-vous.'
        );
    }
}
