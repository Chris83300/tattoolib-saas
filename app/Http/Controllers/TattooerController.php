<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Client;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TattooerController extends Controller
{
    /**
     * Retourne le profil artisan (Tattooer ou Piercer) de l'utilisateur connecté.
     * Rend le controller polymorphique pour tattooers ET pierceurs.
     */
    private function artisan(): ?\Illuminate\Database\Eloquent\Model
    {
        return auth()->user()->artisan();
    }

    /**
     * Retourne les compteurs pendingCount et unreadCount pour le layout artiste.
     * Évite la duplication du même bloc dans chaque méthode du controller.
     */
    private function getDashboardCounts(\Illuminate\Database\Eloquent\Model $artisan): array
    {
        $pendingCount = BookingRequest::where('bookable_id', $artisan->id)
            ->where('bookable_type', get_class($artisan))
            ->where('status', 'pending')
            ->count();

        $unreadCount = \App\Models\Conversation::whereHas('messages', function ($query) {
                $query->where(function ($q) {
                    if (auth()->user()->isTattooer() || auth()->user()->isPiercer()) {
                        $q->whereNull('read_by_tattooer_at');
                    } else {
                        $q->whereNull('read_by_client_at');
                    }
                })
                ->where('sender_id', '!=', auth()->id());
            })
            ->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->count();

        return compact('pendingCount', 'unreadCount');
    }

    private function artisanType(): string
    {
        return auth()->user()->artisanType() ?? 'tattooer';
    }

    private function routePrefix(): string
    {
        return $this->artisanType() === 'piercer' ? 'pierceur' : 'tattooer';
    }

    /**
     * Profil public du tattooer (vue interne)
     */
    public function profile()
    {
        $tattooer = $this->artisan();

        if (!$tattooer) {
            return redirect()->route('register.' . $this->artisanType())
                ->with('error', 'Veuillez compléter votre profil tatoueur pour accéder à cette page.');
        }

        $cacheService = app(\App\Services\CacheService::class);

        // Charger données depuis cache
        $portfolio = $cacheService->getPortfolio($tattooer);
        $workingHours = $cacheService->getWorkingHours($tattooer);
        $stats = $cacheService->getDashboardStats($tattooer);

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.profile', compact(
            'tattooer',
            'portfolio',
            'workingHours',
            'stats',
            'pendingCount',
            'unreadCount'
        ));
    }

    /**
     * Gestion demandes projet
     */
    public function requests(Request $request)
    {
        $tattooer = $this->artisan();
        $filter = $request->query('status', 'all'); // par défaut "all" pour tout afficher

        // Service pour stats (1 requête au lieu de 5)
        $statsService = app(\App\Services\TattooerStatsService::class);
        $counts = $statsService->getRequestsStats($tattooer);

        // UNE SEULE requête avec eager loading optimisé
        $query = BookingRequest::where('bookable_type', get_class($tattooer))
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
            'rejected'  => $query->where('status', BookingRequestStatus::REJECTED->value),
            'cancelled' => $query->where('status', BookingRequestStatus::CANCELLED->value),
            'expired'  => $query->where('status', BookingRequestStatus::EXPIRED->value),
            default     => $query,
        };

        $requests = $query->orderBy('created_at', 'desc')->get();

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        // Utiliser la vue tattooer.requests pour tout le monde (tattooers et pierceurs)
        // La vue contient déjà les conditions pour adapter l'affichage selon le type d'artisan
        return view('tattooer.requests', compact('tattooer', 'requests', 'filter', 'counts', 'pendingCount', 'unreadCount'));
    }

    /**
     * Détail demande projet
     */
    public function requestShow(BookingRequest $bookingRequest)
    {
        $artisan = $this->artisan();

        // Vérifier que la demande appartient à l'artisan (tattooer ou piercer) via relation polymorphique
        if ($bookingRequest->bookable_id !== $artisan->id) {
            abort(403);
        }

        // Charger relations nécessaires
        $bookingRequest->load([
            'client.user',
            'conversation',
            'media'
        ]);

        // Compteurs pour le layout
        $pendingCount = \App\Models\BookingRequest::where('bookable_id', $artisan->id)
            ->where('bookable_type', get_class($artisan))
            ->where('status', 'pending')
            ->count();

        $unreadCount = \App\Models\Conversation::whereHas('messages', function ($query) {
                $query->where(function ($q) {
                    // Si l'utilisateur est un tattooer/piercer, vérifier read_by_tattooer_at
                    if (auth()->user()->isTattooer() || auth()->user()->isPiercer()) {
                        $q->whereNull('read_by_tattooer_at')
                          ->where('sender_type', 'client');
                    } else {
                        // Pour les clients, vérifier read_by_client_at
                        $q->whereNull('read_by_client_at')
                          ->where('sender_type', 'tattooer');
                    }
                });
            })
            ->whereHas('bookingRequest', function($query) use ($artisan) {
                $query->where('bookable_id', $artisan->id)
                    ->where('bookable_type', get_class($artisan));
            })
            ->count();

        return view('tattooer.request-show', [
            'bookingRequest' => $bookingRequest,
            'tattooer' => $artisan, // Passer l'artisan comme $tattooer pour la compatibilité avec la vue
            'pendingCount' => $pendingCount,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Page des paramètres du tattooer
     */
    public function settings()
    {
        $tattooer = $this->artisan();

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.settings', compact('tattooer', 'pendingCount', 'unreadCount'));
    }

    /**
     * Export RGPD — droit à la portabilité (Art. 20 RGPD)
     */
    public function exportGdpr(Request $request)
    {
        $user = $request->user();
        $path = app(\App\Services\GdprExportService::class)->exportUserData($user);

        return \Illuminate\Support\Facades\Storage::disk('local')->download(
            $path,
            'mes-donnees-inkpik-' . now()->format('Y-m-d') . '.json'
        );
    }

    /**
     * Mettre à jour les paramètres du tattooer
     */
    public function settingsUpdate(Request $request)
    {
        $tattooer = $this->artisan();

        // Valider les données du formulaire
        $validated = $request->validate([
            // Médias (fichiers)
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'banner' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',

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

            // Description et styles / types
            'bio' => 'nullable|string|max:2000',
            'styles' => 'nullable|array',
            'styles.*' => 'string|max:100',
            'custom_styles' => 'nullable|array',
            'custom_style_names' => 'nullable|array',
            'custom_style_names.*' => 'nullable|string|max:100',
            'piercing_types' => 'nullable|array',
            'piercing_types.*' => 'string|max:100',
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

        // Séparer les styles prédéfinis et personnalisés (tatoueur)
        $styles = array_values(array_filter(
            $validated['styles'] ?? [],
            fn($s) => $s !== 'Autres' && trim($s) !== ''
        ));
        $customStyleNames = array_values(
            array_filter($validated['custom_style_names'] ?? [], fn($s) => trim($s) !== '')
        );
        // Types de piercing (pierceur)
        $piercingTypes = array_values(array_filter(
            $validated['piercing_types'] ?? [],
            fn($t) => trim($t) !== ''
        ));

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
            'postal_code' => !empty($validated['postal_code']) ? $validated['postal_code'] : $tattooer->postal_code,
            'country' => $validated['country'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'styles' => $tattooer->isPiercer() ? ($validated['styles'] ?? []) : $styles,
            'custom_styles' => $tattooer->isPiercer() ? ($validated['custom_style_names'] ?? []) : $customStyleNames,
            'piercing_types' => $tattooer->isPiercer() ? $piercingTypes : ($tattooer->piercing_types ?? []),
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

        return redirect()->route($this->routePrefix() . '.settings')
            ->with('success', 'Vos paramètres ont été mis à jour avec succès !');
    }

    /**
     * Supprimer l'avatar
     */
    public function deleteAvatar()
    {
        $tattooer = $this->artisan();

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
        $tattooer = $this->artisan();

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
        $tattooer = $this->artisan();

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Service pour stats (1-2 requêtes au lieu de 5+)
        $statsService = app(\App\Services\TattooerStatsService::class);
        $stats = $statsService->getDashboardStats($tattooer);

        // Revenus du mois avec commission (pour les plans starter)
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $monthlyEarnings = $statsService->getMonthlyEarningsWithCommission($tattooer, $currentYear, $currentMonth);

        // Demandes récentes
        $recentRequests = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', get_class($tattooer))
            ->with(['client.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Rendez-vous à venir
        $upcomingAppointments = \App\Models\Appointment::query()
            ->forBookable($tattooer)
            ->upcoming()
            ->with(['client.user', 'bookingRequest.client.user'])
            ->take(5)
            ->get();

        // Activité récente
        $recentActivity = [
            'new_requests' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', get_class($tattooer))
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),

            'completed_appointments' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', get_class($tattooer))
                ->where('status', 'completed')
                ->where('updated_at', '>=', now()->subDays(7))
                ->count(),
        ];

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.dashboard', compact('tattooer', 'stats', 'monthlyEarnings', 'recentRequests', 'upcomingAppointments', 'recentActivity', 'pendingCount', 'unreadCount'));
    }

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
        $tattooer = $this->artisan();

        // Vérifier que la demande appartient bien à l'artisan (tattooer ou piercer)
        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== get_class($tattooer)) {
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

        // Marquer la conversation comme lue pour le tattooer (mettre à jour le pivot)
        $conversation->markAsRead($tattooer->user_id);

        // Récupérer les messages pour la vue
        $messages = $conversation->messages;

        // Compteurs pour le layout
        $pendingCount = \App\Models\BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', get_class($tattooer))
            ->where('status', 'pending')
            ->count();

        $unreadCount = \App\Models\Conversation::whereHas('messages', function ($query) {
                $query->where(function ($q) {
                    if (auth()->user()->isTattooer() || auth()->user()->isPiercer()) {
                        $q->whereNull('read_by_tattooer_at')
                          ->where('sender_type', 'client');
                    } else {
                        $q->whereNull('read_by_client_at')
                          ->where('sender_type', 'tattooer');
                    }
                });
            })
            ->whereHas('bookingRequest', function($query) use ($tattooer) {
                $query->where('bookable_id', $tattooer->id)
                    ->where('bookable_type', get_class($tattooer));
            })
            ->count();

        return view('tattooer.message-show', compact('bookingRequest', 'conversation', 'messages', 'tattooer', 'pendingCount', 'unreadCount'));
    }

    /**
     * Envoyer un message dans la conversation
     */
    /**
 * Envoyer un message dans la conversation (avec gestion des dessins)
 */
public function messageSend(Request $request, BookingRequest $bookingRequest)
{
    $tattooer = $this->artisan();

    if ($bookingRequest->bookable_id !== $tattooer->id ||
        $bookingRequest->bookable_type !== get_class($tattooer)) {
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

    // Protection : bloquer l'upload d'images pour les utilisateurs FREE
    if ($tattooer->isFree() && $request->hasFile('attachments')) {
        return redirect()->back()->with('error', '🔒 L\'envoi d\'images est réservé au plan PRO. <a href="' . route($this->routePrefix() . '.subscription.plans') . '" class="text-beige-peau underline">Passer PRO</a> pour débloquer cette fonctionnalité.');
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

    return redirect()->route($this->routePrefix() . '.message.show', $bookingRequest)
        ->with('success', 'Message envoyé !');
}


    /**
     * Liste des conversations/messages
     */
    public function messages()
    {
        $tattooer = $this->artisan();

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Récupérer les conversations/messages
        $conversations = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', get_class($tattooer))
            ->has('conversation')
            ->with(['client.user', 'conversation.messages'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.messages', compact('tattooer', 'conversations', 'pendingCount', 'unreadCount'));
    }


    /**
     * Clients du tattooer
     */
    public function clients()
    {
        $tattooer = $this->artisan();

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        // Recherche
        $search = request('search');

        // Récupérer les clients : soit ceux avec acompte payé, soit ceux créés manuellement par ce tattooer
        $clientIdsFromBookings = Client::whereHas('bookingRequests', function ($q) use ($tattooer) {
            $q->where('bookable_id', $tattooer->id)
              ->where('bookable_type', $tattooer->getMorphClass())
              ->whereNotNull('deposit_paid_at');
        })->pluck('id');

        // Récupérer les clients créés manuellement par ce tattooer
        $clientIdsFromManual = Client::where('tattooer_id', $tattooer->id)
            ->pluck('id');

        // Fusionner les deux listes d'IDs
        $allClientIds = $clientIdsFromBookings->merge($clientIdsFromManual)->unique();

        $query = Client::whereIn('id', $allClientIds);

        if ($search) {
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

            $client->artisan_stats = (object) [
                'total_requests' => $bookings->count(),
                'completed' => $bookings->where('status', 'completed')->count(),
                'total_paid' => $bookings->sum('total_deposit_amount'),
                'last_request_at' => $bookings->max('created_at'),
            ];

            return $client;
        });

        return view('tattooer.clients', compact('tattooer', 'clients', 'pendingCount', 'unreadCount'));
    }


    /**
     * Fiche client détaillée (PRO uniquement)
     */
    public function clientShow(\App\Models\Client $client)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        // Soit via une demande avec acompte payé, soit créé manuellement par ce tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        // Vérifier si le client a été créé manuellement par ce tattooer
        // (vérifier si le client->tattooer_id correspond au tattooer actuel)
        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            Log::info('Client access denied', [
                'client_id' => $client->id,
                'tattooer_id' => $tattooer->id,
                'client_tattooer_id' => $client->tattooer_id,
                'has_booking_relation' => $hasBookingRelation,
                'is_manually_created' => $isManuallyCreated
            ]);
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

        // Consentements manuels uploadés
        $consentDocuments = $client->getMedia('consent_documents');

        // Traçabilités standalone (client_id non null et appointment_id null)
        $standaloneTraces = TraceabilityRecord::where('client_id', $client->id)
            ->whereNull('appointment_id')
            ->where('tattooer_id', $tattooer->id)
            ->with('media')
            ->orderBy('session_date', 'desc')
            ->get();

        // Photos client uploadées
        $clientPhotos = $client->getMedia('client_photos');

        return view('tattooer.client-show', compact(
            'client', 'tattooer', 'bookingRequests', 'history',
            'appointments', 'consents', 'traceabilities', 'stats', 'chatMedia',
            'consentDocuments', 'standaloneTraces', 'clientPhotos'
        ));
    }

    /**
     * Mettre à jour les informations d'un client (édition inline)
     */
    public function updateClient(Request $request, \App\Models\Client $client)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'pseudo' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $client->user_id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
        ]);

        // Mettre à jour le user
        if ($client->user) {
            $client->user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'pseudo' => $validated['pseudo'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);
        }

        // Mettre à jour le client
        $client->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'pseudo' => $validated['pseudo'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'birth_date' => $validated['birth_date'],
            'address' => $validated['address'],
        ]);

        return redirect()->back()->with('success', '✅ Informations client mises à jour avec succès !');
    }

    /**
     * Uploader un consentement pour un client manuel
     */
    public function uploadConsent(Request $request, \App\Models\Client $client)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
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
    public function deleteConsent(\App\Models\Client $client, $media)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
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

    /**
     * Créer une traçabilité standalone pour un client
     */
    public function storeClientTraceability(Request $request, \App\Models\Client $client)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        $validated = $request->validate([
            'session_date' => 'required|date',
            'tattoo_description' => 'required|string|max:500',
            'body_zone' => 'required|string|max:100',
            'procedure_start_time' => 'required|date_format:H:i',
            'procedure_end_time' => 'required|date_format:H:i|after:procedure_start_time',
            'needles_used' => 'nullable|array',
            'inks_used' => 'nullable|array',
            'sterile_equipment' => 'nullable|string',
            'aftercare_products' => 'nullable|string',
            'room_number' => 'nullable|string|max:50',
            'autoclave_batch_number' => 'nullable|string|max:100',
            'autoclave_test_date' => 'nullable|date',
            'procedure_notes' => 'nullable|string|max:1000',
            'client_condition_notes' => 'nullable|string|max:500',
            'equipment_notes' => 'nullable|string|max:500',
        ]);

        // Créer la traçabilité standalone
        $traceability = TraceabilityRecord::create([
            'user_id' => $tattooer->user_id,
            'tattooer_id' => $tattooer->id,
            'client_id' => $client->id, // Lien direct avec le client
            'appointment_id' => null, // Pas d'appointment lié
            'studio_id' => $tattooer->studio_id,
            'session_date' => $validated['session_date'],
            'tattoo_description' => $validated['tattoo_description'],
            'body_zone' => $validated['body_zone'],
            'procedure_date' => $validated['session_date'],
            'procedure_start_time' => $validated['procedure_start_time'],
            'procedure_end_time' => $validated['procedure_end_time'],
            'needles_used' => $validated['needles_used'] ?? [],
            'inks_used' => $validated['inks_used'] ?? [],
            'sterile_equipment' => $validated['sterile_equipment'],
            'aftercare_products' => $validated['aftercare_products'],
            'room_number' => $validated['room_number'],
            'autoclave_batch_number' => $validated['autoclave_batch_number'],
            'autoclave_test_date' => $validated['autoclave_test_date'],
            'procedure_notes' => $validated['procedure_notes'],
            'client_condition_notes' => $validated['client_condition_notes'],
            'equipment_notes' => $validated['equipment_notes'],
            'tattooer_verified_traceability' => true,
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', '✅ Traçabilité enregistrée avec succès !');
    }

    /**
     * Uploader des photos pour un client
     */
    public function uploadClientPhotos(Request $request, \App\Models\Client $client)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        $request->validate([
            'photos' => 'required|array|min:1|max:10',
            'photos.*' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $uploadedCount = 0;
        foreach ($request->file('photos') as $photo) {
            $client->addMedia($photo)
                ->withCustomProperties([
                    'uploaded_by' => 'tattooer',
                    'tattooer_id' => $tattooer->id,
                    'upload_date' => now()->format('Y-m-d H:i:s'),
                ])
                ->toMediaCollection('client_photos');
            $uploadedCount++;
        }

        return redirect()->back()->with('success', "✅ {$uploadedCount} photo(s) uploadée(s) avec succès !");
    }

    /**
     * Supprimer une photo client
     */
    public function deleteClientPhoto(\App\Models\Client $client, $media)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        $mediaItem = $client->getMedia('client_photos')->where('id', $media)->first();

        if (!$mediaItem) {
            abort(404, 'Photo non trouvée.');
        }

        $mediaItem->delete();

        return redirect()->back()->with('success', '✅ Photo supprimée avec succès !');
    }

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
     * Enregistrer la traçabilité pour un rendez-vous
     */
    public function storeTraceability(Request $request, \App\Models\Appointment $appointment)
    {
        $tattooer = $this->artisan();

        // Vérifier propriété via booking request
        $bookingRequest = $appointment->bookingRequest;
        if (!$bookingRequest || $bookingRequest->bookable_id !== $tattooer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'needles' => 'nullable|array',
            'needles.*' => 'nullable|array',
            'needles.*.brand' => 'nullable|string|max:255',
            'needles.*.lot_number' => 'nullable|string|max:255',
            'needles.*.type' => 'nullable|string|max:255',
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
            'client_consent_form_id' => $appointment->bookingRequest?->clientConsentForm?->id ?? null,
            'procedure_date' => now()->format('Y-m-d'),
            'procedure_start_time' => $appointment->start_datetime?->format('H:i:s') ?? null,
            'procedure_end_time' => $appointment->end_datetime?->format('H:i:s') ?? null,
            'sterile_equipment' => [
                'needles' => $validated['needles'] ?? [],
                'inks' => $validated['inks'] ?? [],
                'sterilization_date' => $validated['sterilization_date'] ?? null,
                'sterilization_lot_number' => $validated['sterilization_lot_number'] ?? '',
                'autoclave_cycle_number' => $validated['autoclave_cycle_number'] ?? ''
            ],
            'aftercare_products' => $validated['aftercare_products'] ?? [],
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
        $hasPhotos = false;
        if ($request->hasFile('lot_photos')) {
            $hasPhotos = true;
            foreach ($request->file('lot_photos') as $photo) {
                $traceability->addMedia($photo)->toMediaCollection('lot_photos');
            }
        }

        // Si des photos ont été uploadées, marquer comme complète
        if ($hasPhotos) {
            $traceability->update([
                'tattooer_verified_traceability' => true,
                'verified_at' => now(),
            ]);
        }

        return redirect()->to(url()->previous() . '#trace')->with('success', '✅ Traçabilité enregistrée.');
    }

    /**
     * Mettre à jour les notes privées du client
     */
    public function updateClientNotes(Request $request, \App\Models\Client $client)
    {
        $tattooer = $this->artisan();

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
        $tattooer = $this->artisan();

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
        $tattooer = $this->artisan();

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
        $tattooer = $this->artisan();

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

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.portfolio', compact('tattooer', 'tattoos', 'drawings', 'beforeAfter', 'pendingCount', 'unreadCount'));
    }

    /**
     * Upload d'images pour le portfolio
     */
    public function portfolioUpload(Request $request)
    {
        try {
            $tattooer = $this->artisan();
            $collection = $request->input('collection', 'portfolio');

            // Validation
            $request->validate([
                'images' => 'required|array|max:10', // max 10 images par upload
                'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB max par image
                'collection' => 'required|in:portfolio,drawings,before_after'
            ]);

            // Vérifier la limite d'images selon le plan
            $tattooer = $this->artisan();

            // Compter le TOTAL des images portfolio (toutes collections confondues)
            $totalPortfolioCount = $tattooer->getMedia('portfolio')->count() +
                                   $tattooer->getMedia('drawings')->count() +
                                   $tattooer->getMedia('before_after')->count();

            $newImagesCount = count($request->file('images'));

            // Définir la limite selon le plan (15 images AU TOTAL)
            $maxImages = $tattooer->isPro() ? PHP_INT_MAX : 15;

            if ($totalPortfolioCount + $newImagesCount > $maxImages) {
                return response()->json([
                    'success' => false,
                    'message' => "Limite de {$maxImages} images atteinte (plan Free). Passez au plan Pro pour un portfolio illimité."
                ], 422);
            }
            $uploadedCount = 0;
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
                'message' => 'Une erreur est survenue lors de l\'upload. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Stockage des photos avant/après
     */
    public function portfolioBeforeAfterStore(Request $request)
    {
        try {
            $tattooer = $this->artisan();

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
                'message' => 'Une erreur est survenue lors de l\'upload. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Supprimer un média du portfolio
     */
    public function portfolioDestroy($media)
    {
        try {
            $tattooer = $this->artisan();

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
                'message' => 'Une erreur est survenue lors de la suppression. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Supprimer une paire avant/après
     */
    public function portfolioBeforeAfterDestroy($beforeId, $afterId)
    {
        try {
            $tattooer = $this->artisan();

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
                'message' => 'Une erreur est survenue lors de la suppression. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Mettre à jour les horaires du tattooer
     */
    public function settingsUpdateSchedule(Request $request)
    {
        try {
            $tattooer = $this->artisan();

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
        $tattooer = $this->artisan();

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Récupérer les paiements : demandes avec acompte payé ou solde payé
        $payments = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', get_class($tattooer))
            ->whereNotNull('deposit_paid_at')
            ->with(['client', 'bookingTransactions'])
            ->orderBy('deposit_paid_at', 'desc')
            ->paginate(10);

        // Statistiques depuis les vraies transactions comptables
        $transactionsQuery = \App\Models\BookingTransaction::whereHas('bookingRequest', function ($q) use ($tattooer) {
            $q->where('bookable_id', $tattooer->id)
              ->where('bookable_type', get_class($tattooer));
        })->where('status', 'completed');

        $paymentStats = [
            'total_earned'    => (clone $transactionsQuery)->sum('amount'),
            'this_month'      => (clone $transactionsQuery)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'pending_deposits' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', get_class($tattooer))
                ->whereIn('status', ['accepted', 'deposit_requested', 'awaiting_deposit'])
                ->sum('total_deposit_amount'),
        ];

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        $stats = [
            'total_revenue' => $paymentStats['total_earned'],
            'total_payments' => $payments->count(),
            'this_month' => $paymentStats['this_month'],
        ];

        return view('tattooer.payments', compact('tattooer', 'payments', 'paymentStats', 'stats', 'pendingCount', 'unreadCount'));
    }

    /**
     * Connecter Stripe Connect — initie ou reprend l'onboarding Stripe.
     */
    public function connectStripe(Request $request)
    {
        $tattooer = $this->artisan();

        if (!$tattooer) {
            return redirect()->back()->with('error', 'Profil artiste introuvable.');
        }

        // Studio centralisé : l'artiste n'a pas besoin de son propre Connect
        if (!$tattooer->needsOwnStripeConnect()) {
            return redirect()->route($this->routePrefix() . '.payments')
                ->with('info', 'Les paiements sont gérés par votre studio.');
        }

        try {
            $connectLink = $tattooer->generateStripeConnectLink();
            return redirect($connectLink);
        } catch (\Exception $e) {
            Log::error('Erreur génération lien Stripe Connect', [
                'tattooer_id' => $tattooer->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Impossible de générer le lien Stripe Connect. Veuillez réessayer.');
        }
    }

    /**
     * Accepter une demande de réservation
     */
    public function acceptRequest(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer connecté
        $tattooer = $this->artisan();

        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== get_class($tattooer)) {
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

        return redirect()->route($this->routePrefix() . '.request.show', $bookingRequest)
            ->with('success', 'Demande acceptée avec succès !');
    }

    /**
     * Refuser une demande de réservation
     */
    public function requestReject(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer connecté
        $tattooer = $this->artisan();

        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== get_class($tattooer)) {
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
                'status' => 'archived', // Utiliser 'archived' qui existe dans la table
                'expiry_type' => 'archived',
                'archived_at' => now(),
            ]);
        } else {
            // Utiliser une requête directe pour éviter le problème d'enum
            DB::table('conversations')
                ->where('id', $conversation->id)
                ->update([
                    'status' => 'archived',
                    'expiry_type' => 'archived',
                    'archived_at' => now(),
                    'updated_at' => now(),
                ]);
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

        return redirect()->route($this->routePrefix() . '.request.show', $bookingRequest)
            ->with('success', 'Demande refusée avec succès !');
    }

    /**
     * Re-proposer de nouvelles dates après refus client
     */
    public function reproposeDates(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer connecté
        $tattooer = $this->artisan();

        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== get_class($tattooer)) {
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

    /**
     * Afficher la page d'upgrade vers PRO
     */
    public function upgrade()
    {
        $tattooer = $this->artisan();

        if ($tattooer->isPro()) {
            return redirect()->route($this->routePrefix() . '.profile')
                ->with('info', 'Vous êtes déjà abonné au plan PRO.');
        }

        return view('tattooer.upgrade');
    }

    /**
     * Afficher le formulaire de création de client manuel
     */
    public function createClient()
    {
        return view('tattooer.clients-create');
    }

    /**
     * Stocker un nouveau client manuel
     */
    public function storeClient(Request $request)
    {
        try {
            // Debug
            Log::info('storeClient called', [
                'request_data' => $request->all(),
                'tattooer_id' => $this->artisan()?->id,
                'is_pro' => $this->artisan()?->isPro()
            ]);

            $validated = $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'pseudo' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'birth_date' => 'nullable|date',
                'address' => 'nullable|string|max:500',
                'notes' => 'nullable|string|max:2000',
            ]);

            Log::info('Validation passed', ['validated' => $validated]);

            // Créer l'utilisateur client
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'pseudo' => $validated['pseudo'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => 'client',
                'password' => bcrypt(str()->random(32)), // Mot de passe aléatoire
            ]);

            Log::info('User created', ['user_id' => $user->id]);

            // Créer le client
            $client = \App\Models\Client::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'pseudo' => $validated['pseudo'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'birth_date' => $validated['birth_date'],
                'address' => $validated['address'],
                'notes' => $validated['notes'],
                'tattooer_id' => $this->artisan()?->id, // Associer au tattooer actuel
            ]);

            Log::info('Client created', ['client_id' => $client->id]);

            return redirect()->route($this->routePrefix() . '.client.show', $client)
                ->with('success', '✅ Fiche client créée avec succès !');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Store client failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', '❌ Erreur lors de la création du client: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Enregistrer un consentement numérique pour un client
     */
    public function storeDigitalConsent(Request $request, \App\Models\Client $client)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
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
     * Marquer une demande comme terminée (RDV validé)
     */
    public function completeBooking(Request $request, BookingRequest $bookingRequest)
    {
        $tattooer = $this->artisan();

        // Vérifier que la demande appartient bien au tattooer
        if ($bookingRequest->bookable_id !== $tattooer->id || $bookingRequest->bookable_type !== get_class($tattooer)) {
            abort(403, 'Non autorisé');
        }

        // Vérifier que la transition est autorisée
        if (!$bookingRequest->status->canTransitionTo(BookingRequestStatus::COMPLETED)) {
            return redirect()->back()->with('error', 'Impossible de terminer cette demande.');
        }

        // Marquer comme terminé
        $bookingRequest->update(['completed_at' => now()]);
        $bookingRequest->transitionTo(BookingRequestStatus::COMPLETED);

        return redirect()->back()->with('success', 'RDV validé avec succès.');
    }

    /**
     * Déclarer un client comme absent (no-show)
     */
    public function markNoShow(Request $request, BookingRequest $bookingRequest)
    {
        $tattooer = $this->artisan();

        // Vérifier que la demande appartient bien au tattooer
        if ($bookingRequest->bookable_id !== $tattooer->id || $bookingRequest->bookable_type !== get_class($tattooer)) {
            abort(403, 'Non autorisé');
        }

        // Vérifier que la transition est autorisée
        if (!$bookingRequest->status->canTransitionTo(BookingRequestStatus::NO_SHOW)) {
            return redirect()->back()->with('error', 'Impossible de déclarer no-show pour cette demande.');
        }

        // Marquer comme no-show
        $bookingRequest->update(['no_show_at' => now()]);
        $bookingRequest->transitionTo(BookingRequestStatus::NO_SHOW);

        // Incrémenter le compteur no-show du client
        $bookingRequest->client->increment('no_show_count');

        return redirect()->back()->with('success', 'No-show déclaré avec succès.');
    }

    /**
     * Page des plans d'abonnement (pricing)
     */
    public function pricing()
    {
        return redirect()->route($this->routePrefix() . '.subscription.plans');
    }

    /**
     * Mettre à jour les paramètres de soins aftercare
     */
    public function settingsAftercareUpdate(Request $request)
    {
        $tattooer = $this->artisan();

        // Vérifier que c'est un plan Pro
        if (!$tattooer->isPro()) {
            return redirect()->back()->with('error', 'Cette fonctionnalité est réservée aux plans Pro.');
        }

        $validated = $request->validate([
            'aftercare_sheet' => 'nullable|string|max:2000',
            'aftercare_reminder_2h' => 'boolean',
            'aftercare_reminder_7d' => 'boolean',
            'aftercare_reminder_14d' => 'boolean',
        ]);

        // Convertir les checkboxes en booléens
        $validated['aftercare_reminder_2h'] = $request->has('aftercare_reminder_2h');
        $validated['aftercare_reminder_7d'] = $request->has('aftercare_reminder_7d');
        $validated['aftercare_reminder_14d'] = $request->has('aftercare_reminder_14d');

        $tattooer->update($validated);

        return redirect()->back()->with('success', 'Fiche de soins mise à jour avec succès.');
    }

    /**
     * Mettre à jour la grille tarifaire (pierceur Pro uniquement)
     */
    public function settingsPricingUpdate(Request $request)
    {
        $tattooer = $this->artisan();

        $validated = $request->validate([
            'pricing_grid' => 'nullable|array',
            'pricing_grid.*.type' => 'required|string|max:100',
            'pricing_grid.*.price' => 'required|numeric|min:0',
            'custom_pricing_note' => 'nullable|string|max:500',
        ]);

        $tattooer->update([
            'pricing_grid' => $validated['pricing_grid'] ?? [],
            'custom_pricing_note' => $validated['custom_pricing_note'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Grille tarifaire mise à jour avec succès.');
    }

    public function compliance()
    {
        $tattooer = $this->artisan();
        return view('tattooer.compliance', compact('tattooer'));
    }

    public function complianceDocuments()
    {
        $tattooer = $this->artisan();
        $complianceRecords = $tattooer->complianceRecords()->with('verifier')->get();

        return view('tattooer.compliance-documents', compact('tattooer', 'complianceRecords'));
    }

    public function complianceDocumentsUpload(Request $request)
    {
        $tattooer = $this->artisan();

        $hasFile = $request->hasFile('certificate_file');
        $certificationTypes = $request->input('certification_types', []);

        if (empty($certificationTypes)) {
            return redirect()->back()->with('error', 'Veuillez sélectionner au moins un type de document.');
        }

        $request->validate([
            'certification_types' => 'required|array|min:1',
            'certification_types.*' => 'in:hygiene_salubrite,certibiocide,declaration_ars',
            'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'certificate_number' => 'nullable|string|max:255',
            'training_organization' => 'nullable|string|max:255',
            'obtained_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:obtained_at',
            'ars_region' => 'nullable|string|max:100',
            'ars_number' => 'nullable|string|max:100',
            'biocide_type' => 'nullable|string|max:50',
        ]);

        $baseData = $request->except(['certification_types', 'certificate_file', 'ars_proof_file']);

        // Si pas de fichier, on s'assure que les champs obligatoires sont présents
        if (!$hasFile) {
            $request->validate([
                'certificate_number' => 'required|string|max:255',
                'training_organization' => 'required|string|max:255',
                'obtained_at' => 'required|date',
            ]);
        }

        // Validation conditionnelle pour les champs spécifiques
        if (in_array('declaration_ars', $certificationTypes) && !$hasFile) {
            $request->validate([
                'ars_region' => 'required|string|max:100',
                'ars_number' => 'required|string|max:100',
            ]);
        }

        if (in_array('certibiocide', $certificationTypes) && !$hasFile) {
            $request->validate([
                'biocide_type' => 'required|string|max:50',
            ]);
        }

        // Gérer l'upload des fichiers par type
        $filePaths = [];
        $arsProofPath = null;

        if ($hasFile) {
            $file = $request->file('certificate_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('compliance-documents', $filename, 'local');

            // Créer une copie du fichier pour chaque type sélectionné
            foreach ($certificationTypes as $certificationType) {
                $typeFilename = time() . '_' . $certificationType . '_' . $file->getClientOriginalName();
                $typePath = $file->storeAs('compliance-documents', $typeFilename, 'local');
                $filePaths[$certificationType] = $typePath;
            }
        }

        // Gérer l'upload du fichier ARS si applicable
        if ($request->hasFile('ars_proof_file') && in_array('declaration_ars', $certificationTypes)) {
            $file = $request->file('ars_proof_file');
            $filename = time() . '_ars_' . $file->getClientOriginalName();
            $arsProofPath = $file->storeAs('compliance-documents', $filename, 'local');
        }

        $createdRecords = [];

        // Créer un enregistrement pour chaque type de document sélectionné
        foreach ($certificationTypes as $certificationType) {
            // Vérifier si un enregistrement existe déjà pour ce type
            $existingRecord = $tattooer->complianceRecords()
                ->where('certification_type', $certificationType)
                ->first();

            if ($existingRecord) {
                // Mettre à jour l'enregistrement existant
                $data = $baseData;
                $data['certificate_file_path'] = $filePaths[$certificationType] ?? null;
                $data['ars_proof_file_path'] = ($certificationType === 'declaration_ars') ? $arsProofPath : null;
                $data['status'] = 'pending'; // Repasser en pending pour nouvelle vérification

                // S'assurer que les champs nullables sont bien null si vides
                // Si obtained_at est requis mais qu'on a un fichier, on utilise la date du jour
                if (empty($data['obtained_at']) && $hasFile) {
                    $data['obtained_at'] = now()->format('Y-m-d');
                }

                $data['expires_at'] = $data['expires_at'] ?? null;
                $data['certificate_number'] = $data['certificate_number'] ?? null;
                $data['training_organization'] = $data['training_organization'] ?? null;
                $data['ars_region'] = $data['ars_region'] ?? null;
                $data['ars_number'] = $data['ars_number'] ?? null;
                $data['biocide_type'] = $data['biocide_type'] ?? null;

                $existingRecord->update($data);
                $createdRecords[] = $existingRecord->getCertificationLabel() . ' (mis à jour)';
            } else {
                // Créer un nouvel enregistrement
                $data = $baseData;
                $data['certification_type'] = $certificationType;
                $data['certificate_file_path'] = $filePaths[$certificationType] ?? null;
                $data['ars_proof_file_path'] = ($certificationType === 'declaration_ars') ? $arsProofPath : null;
                $data['status'] = 'pending';
                $data['compliant_type'] = get_class($tattooer);
                $data['compliant_id'] = $tattooer->id;

                // S'assurer que les champs nullables sont bien null si vides
                // Si obtained_at est requis mais qu'on a un fichier, on utilise la date du jour
                if (empty($data['obtained_at']) && $hasFile) {
                    $data['obtained_at'] = now()->format('Y-m-d');
                }

                $data['expires_at'] = $data['expires_at'] ?? null;
                $data['certificate_number'] = $data['certificate_number'] ?? null;
                $data['training_organization'] = $data['training_organization'] ?? null;
                $data['ars_region'] = $data['ars_region'] ?? null;
                $data['ars_number'] = $data['ars_number'] ?? null;
                $data['biocide_type'] = $data['biocide_type'] ?? null;

                $complianceRecord = \App\Models\ComplianceRecord::create($data);
                $createdRecords[] = $complianceRecord->getCertificationLabel();
            }
        }

        $message = count($createdRecords) > 1
            ? count($createdRecords) . ' documents téléchargés avec succès. Ils seront vérifiés par notre équipe dans les 48h.'
            : 'Document téléchargé avec succès. Il sera vérifié par notre équipe dans les 48h.';

        return redirect()->route('tattooer.compliance.documents')
            ->with('success', $message);
    }

    public function complianceDocumentServe(\App\Models\ComplianceRecord $complianceRecord, string $field)
    {
        $tattooer = $this->artisan();
        $user = auth()->user();

        // Seul le propriétaire ou un admin peut consulter le document
        if (!$user->hasRole('admin')) {
            if ($complianceRecord->compliant_type !== get_class($tattooer) || $complianceRecord->compliant_id !== $tattooer->id) {
                abort(403);
            }
        }

        if (!in_array($field, ['certificate_file_path', 'ars_proof_file_path'])) {
            abort(404);
        }

        $path = $complianceRecord->$field;
        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->response($path);
    }

    public function complianceDocumentDelete(\App\Models\ComplianceRecord $complianceRecord)
    {
        $tattooer = $this->artisan();

        // Vérifier que le document appartient bien à l'utilisateur
        if ($complianceRecord->compliant_type !== get_class($tattooer) || $complianceRecord->compliant_id !== $tattooer->id) {
            abort(403);
        }

        // Supprimer les fichiers physiques
        if ($complianceRecord->certificate_file_path) {
            Storage::disk('local')->delete($complianceRecord->certificate_file_path);
        }
        if ($complianceRecord->ars_proof_file_path) {
            Storage::disk('local')->delete($complianceRecord->ars_proof_file_path);
        }

        $complianceRecord->delete();

        return redirect()->route('tattooer.compliance.documents')
            ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Supprimer une demande expirée, refusée ou annulée
     */
    public function destroyRequest(Request $request, BookingRequest $bookingRequest)
    {
        $user   = $request->user();
        $artist = $user->tattooer ?? $user->piercer;

        abort_unless(
            $artist
            && $bookingRequest->bookable_id === $artist->id
            && $bookingRequest->bookable_type === get_class($artist),
            403,
            'Non autorisé'
        );

        $deletableStatuses = ['expired', 'rejected', 'cancelled'];
        abort_unless(
            in_array($bookingRequest->status->value, $deletableStatuses),
            422,
            'Seules les demandes expirées, refusées ou annulées peuvent être supprimées.'
        );

        $bookingRequest->conversation?->messages()->forceDelete();
        $bookingRequest->conversation?->forceDelete();
        $bookingRequest->forceDelete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Demande supprimée.');
    }

    /**
     * Annuler une demande de réservation (côté artiste)
     */
    public function cancelRequest(Request $request, BookingRequest $bookingRequest)
    {
        $artist = $this->artisan();
        abort_unless($artist, 403);
        abort_unless($bookingRequest->bookable_id === $artist->id, 403);
        abort_unless(
            !in_array($bookingRequest->status->value, ['completed', 'cancelled']),
            422,
            'Cette demande ne peut plus être annulée.'
        );

        $refundInfo = app(\App\Services\CancellationService::class)->processCancellation(
            $bookingRequest,
            'artist',
            $request->input('cancellation_message', '')
        );

        // Notifier le client
        try {
            $bookingRequest->client?->user?->notify(
                new \App\Notifications\BookingCancelledNotification($bookingRequest)
            );
        } catch (\Exception $e) {
            Log::warning('Notification annulation artiste échouée: ' . $e->getMessage());
        }

        $msg = 'Demande annulée.';
        if ($refundInfo['refund_amount'] > 0) {
            $msg .= ' Remboursement de ' . number_format($refundInfo['refund_amount'], 2, ',', ' ') . '€ en cours.';
        }

        return redirect()->back()->with('success', $msg);
    }
}
