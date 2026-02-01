<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tattooer;
use App\Models\Project;
use App\Models\Client;
use App\Models\Message;
use App\Models\BookingRequest;
use App\Models\CalendarEvent;
use App\Models\TattooHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TattooerController extends Controller
{
    /**
     * Dashboard principal (Vue d'ensemble)
     */
    public function dashboard()
    {
        $tattooer = auth()->user()->tattooer;

        // Stats KPI
        $stats = [
            'pending_requests' => Project::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->where('status', 'pending')
                ->count(),

            'upcoming_appointments' => Project::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->where('status', 'in_progress')
                ->whereNotNull('appointment_date')
                ->where('appointment_date', '>=', now())
                ->count(),

            'total_clients' => Project::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->distinct('client_id')
                ->count('client_id'),

            'monthly_revenue' => TattooHistory::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->whereMonth('tattoo_date', now()->month)
                ->sum('total_paid'),

            'unread_messages' => Message::whereHas('bookingRequest', function($q) use ($tattooer) {
                    $q->where('bookable_id', $tattooer->id)
                     ->where('bookable_type', 'App\Models\Tattooer');
                })
                ->where('sender_type', 'client')
                ->count(),

            'appointments_this_week' => Project::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        // Activité récente (7 derniers jours)
        $recentActivity = [
            'new_requests' => Project::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),

            'completed_appointments' => Project::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->where('status', 'completed')
                ->where('completed_at', '>=', now()->subDays(7))
                ->count(),
        ];

        // Prochains RDV (3 prochains)
        $upcomingAppointments = Project::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->where('status', 'in_progress')
            ->whereNotNull('appointment_date')
            ->where('appointment_date', '>=', now())
            ->with('client')
            ->orderBy('appointment_date')
            ->limit(3)
            ->get();

        return view('tattooer.dashboard', compact('tattooer', 'stats', 'recentActivity', 'upcomingAppointments'));
    }

    /**
     * Gestion demandes projet
     */
    public function requests()
    {
        $tattooer = auth()->user()->tattooer;

        $requests = Project::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->with('client')
            ->orderByRaw("FIELD(status, 'pending', 'accepted', 'in_progress', 'completed', 'cancelled')")
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('tattooer.requests', compact('requests'));
    }

    /**
     * Détail demande projet
     */
    public function requestShow(Project $project)
    {
        $this->authorize('view', $project); // Policy

        $project->load(['client', 'media', 'traceability', 'consent']);

        return view('tattooer.request-show', compact('project'));
    }

    /**
     * Calendrier
     */
    public function calendar()
    {
        $tattooer = auth()->user()->tattooer;

        // Événements pour FullCalendar
        $events = [];

        // Rendez-vous depuis les booking requests
        $appointments = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->whereNotNull('appointment_datetime')
            ->where('status', 'accepted')
            ->with('client')
            ->get();

        foreach ($appointments as $appointment) {
            $events[] = [
                'id' => 'appointment_' . $appointment->id,
                'title' => 'RDV - ' . $appointment->client->first_name . ' ' . $appointment->client->last_name,
                'start' => $appointment->appointment_datetime,
                'end' => $appointment->appointment_end ?? $appointment->appointment_datetime->addHours(2),
                'backgroundColor' => '#06D6A0',
                'borderColor' => '#06D6A0',
                'editable' => false,
                'extendedProps' => [
                    'type' => 'appointment',
                    'client_id' => $appointment->client_id
                ]
            ];
        }

        // Événements personnalisés (pause, vacances, etc.)
        $customEvents = CalendarEvent::where('bookable_id', $tattooer->id)
            ->where('bookable_type', Tattooer::class)
            ->get();

        foreach ($customEvents as $event) {
            $events[] = [
                'id' => (string) $event->id,
                'title' => $event->notes ?: (CalendarEvent::TYPES[$event->type] ?? 'Événement'),
                'start' => $event->start_datetime->toIso8601String(),
                'end' => $event->end_datetime->toIso8601String(),
                'backgroundColor' => $event->color ?: (CalendarEvent::COLORS[$event->type] ?? '#D4B59E'),
                'borderColor' => $event->color ?: (CalendarEvent::COLORS[$event->type] ?? '#D4B59E'),
                'textColor' => '#0A0A0A',
                'editable' => true,
                'extendedProps' => [
                    'type' => $event->type,
                ]
            ];
        }

        return view('tattooer.calendar', compact('tattooer', 'events'));
    }

    /**
     * Messages
     */
    public function messages()
    {
        $tattooer = auth()->user()->tattooer;

        // Conversations directes depuis les booking_requests
        $conversations = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->whereHas('messages')
            ->with(['client', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => fn($q) =>
                $q->where('sender_type', 'client')
            ])
            ->orderByDesc('updated_at')
            ->get();

        return view('tattooer.messages', compact('conversations'));
    }

    /**
     * Conversation détaillée
     */
    public function messageShow(Project $project)
    {
        // Vérifier que le projet appartient bien au tattooer
        $hasAccess = Project::where('bookable_id', auth()->user()->tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->where('id', $project->id)
            ->exists();

        abort_unless($hasAccess, 403);

        $messages = $project->messages()
            ->with('sender', 'media')
            ->orderBy('created_at')
            ->get();

        // Marquer comme lu
        $project->messages()
            ->where('sender_type', 'client')
            ->update(['read_at' => now()]);

        return view('tattooer.message-show', compact('project', 'messages'));
    }

    /**
     * Clients (liste + recherche)
     */
    public function clients(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        // Récupérer tous les clients uniques via les projects
        $clientsQuery = Project::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->with('client')
            ->distinct('client_id')
            ->select('client_id');

        // Recherche par nom
        if ($request->search) {
            $clientsQuery->whereHas('client', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        $clientIds = $clientsQuery->pluck('client_id');

        $clients = Client::whereIn('id', $clientIds)
            ->withCount(['tattooHistory as tattoos_count'])
            ->with(['tattooHistory' => fn($q) => $q->latest()->first()])
            ->orderBy('first_name')
            ->paginate(20);

        return view('tattooer.clients', compact('clients'));
    }

    public function clientShow(Client $client)
    {
        // Vérifier que ce client appartient bien au tattooer
        $hasAccess = Project::where('bookable_id', auth()->user()->tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->where('client_id', $client->id)
            ->exists();

        abort_unless($hasAccess, 403);

        $history = TattooHistory::where('client_id', $client->id)
            ->with('project')
            ->orderByDesc('tattoo_date')
            ->get();

        return view('tattooer.client-show', compact('client', 'history'));
    }

    /**
     * Portfolio (gestion images)
     */
    public function portfolio()
    {
        $tattooer = auth()->user()->tattooer;

        // Images par collection
        $tattoos = $tattooer->getMedia('portfolio');
        $drawings = $tattooer->getMedia('drawings');
        $beforeAfter = $tattooer->getMedia('before_after');

        return view('tattooer.portfolio', compact('tattoos', 'drawings', 'beforeAfter'));
    }

    /**
     * Paramètres profil
     */
    public function settings()
    {
        $tattooer = auth()->user()->tattooer;
        $tattooer->load('workingHours');

        return view('tattooer.settings', compact('tattooer'));
    }

    /**
     * Stripe Connect
     */
    public function payments()
    {
        $tattooer = auth()->user()->tattooer;

        // Transactions récentes
        $payments = TattooHistory::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->with('client')
            ->orderByDesc('tattoo_date')
            ->paginate(20);

        // Stats paiements
        $paymentStats = [
            'total_earned' => TattooHistory::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->sum('total_paid'),
            'this_month' => TattooHistory::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->whereMonth('tattoo_date', now()->month)
                ->sum('total_paid'),
            'pending_deposits' => Project::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->whereNotNull('deposit_amount')
                ->whereNull('deposit_paid_at')
                ->sum('deposit_amount'),
        ];

        return view('tattooer.payments', compact('payments', 'paymentStats', 'tattooer'));
    }

    /**
     * Upgrade vers PRO
     */
    public function upgrade()
    {
        $tattooer = auth()->user()->tattooer;

        if ($tattooer->subscription_plan === 'pro') {
            return redirect()->route('tattooer.dashboard')
                ->with('info', 'Vous êtes déjà abonné au plan PRO.');
        }

        return view('tattooer.upgrade');
    }

    /**
     * Paramètres - Mise à jour
     */
    public function settingsUpdate(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        // Valider les données
        $validated = $request->validate([
            'pseudo' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'studio_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:1000',
            'styles' => 'nullable|array',
            'styles.*' => 'string|max:50',
            'minimum_deposit' => 'nullable|numeric|min:0|max:10000',
            'default_deposit_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        // Mettre à jour le user
        auth()->user()->update([
            'name' => $validated['pseudo'],
            'email' => $validated['email'],
        ]);

        // Mettre à jour le tattooer
        $tattooer->update([
            'studio_name' => $validated['studio_name'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'description' => $validated['description'] ?? null,
            'styles' => isset($validated['styles']) ? json_encode($validated['styles']) : null,
            'minimum_deposit' => $validated['minimum_deposit'] ?? 0,
            'default_deposit_rate' => $validated['default_deposit_rate'] ?? 20,
        ]);

        return redirect()->route('tattooer.settings')
            ->with('success', 'Vos paramètres ont été mis à jour avec succès.');
    }

    /**
     * Paramètres - Mise à jour horaires
     */
    public function settingsUpdateSchedule(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        $validated = $request->validate([
            'schedule' => 'required|array',
        ]);

        $dayMap = [
            'lundi' => 1,
            'mardi' => 2,
            'mercredi' => 3,
            'jeudi' => 4,
            'vendredi' => 5,
            'samedi' => 6,
            'dimanche' => 0,
        ];

        // On remplace complètement le planning
        $tattooer->workingHours()->delete();

        foreach ($dayMap as $dayKey => $dayOfWeek) {
            $dayData = $validated['schedule'][$dayKey] ?? [];
            $isOpen = isset($dayData['open']) && (string) $dayData['open'] === '1';

            $start = $dayData['start'] ?? null;
            $end = $dayData['end'] ?? null;

            if ($isOpen) {
                if (!$start || !$end) {
                    return back()
                        ->withErrors(['schedule' => "Merci de renseigner les heures d'ouverture pour $dayKey."])
                        ->withInput();
                }
            }

            $tattooer->workingHours()->create([
                'day_of_week' => $dayOfWeek,
                'is_open' => $isOpen,
                'start_time' => $isOpen ? $start : null,
                'end_time' => $isOpen ? $end : null,
                'slot_duration_minutes' => 60,
                'buffer_time_minutes' => 15,
            ]);
        }

        return redirect()->route('tattooer.settings')
            ->with('success', 'Vos horaires ont été mis à jour avec succès.');
    }

    /**
     * Paramètres - Mise à jour mot de passe
     */
    public function settingsUpdatePassword(Request $request)
    {
        $user = auth()->user();

        // Valider les données
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Vérifier le mot de passe actuel
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])
                ->withInput();
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('tattooer.settings')
            ->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }

    /**
     * Calendrier - Sauvegarder un événement
     */
    public function calendarStore(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'type' => 'required|in:appointment,break,vacation,closure',
            'notes' => 'nullable|string',
        ]);

        $notes = $validated['notes'] ?? null;
        if (!$notes) {
            $notes = $validated['title'];
        }

        CalendarEvent::create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => 'App\\Models\\Tattooer',
            'type' => $validated['type'],
            'start_datetime' => Carbon::parse($validated['start_datetime']),
            'end_datetime' => Carbon::parse($validated['end_datetime']),
            'notes' => $notes,
            'color' => CalendarEvent::COLORS[$validated['type']] ?? '#D4B59E',
        ]);

        return redirect()->route('tattooer.calendar')
            ->with('success', 'Événement créé avec succès');
    }

    /**
     * Calendrier - Mettre à jour un événement
     */
    public function calendarUpdate(Request $request, $event)
    {
        $tattooer = auth()->user()->tattooer;

        // On n'autorise la mise à jour que pour les événements custom (id numérique)
        if (!ctype_digit((string) $event)) {
            return response()->json(['success' => false], 422);
        }

        $validated = $request->validate([
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
        ]);

        $calendarEvent = CalendarEvent::where('id', (int) $event)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\\Models\\Tattooer')
            ->firstOrFail();

        $calendarEvent->update([
            'start_datetime' => Carbon::parse($validated['start_datetime']),
            'end_datetime' => Carbon::parse($validated['end_datetime']),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Calendrier - Supprimer un événement
     */
    public function calendarDestroy($event)
    {
        $tattooer = auth()->user()->tattooer;

        if (!$tattooer) {
            return response()->json([
                'success' => false,
                'error' => 'Profil tattooer non trouvé',
            ], 403);
        }

        // Autoriser la suppression de tous les événements (RDV inclus)
        $calendarEvent = CalendarEvent::where('id', (int) $event)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', Tattooer::class)
            ->first();

        if (!$calendarEvent) {
            return response()->json([
                'success' => false,
                'error' => 'Événement non trouvé',
            ], 404);
        }

        if (method_exists($calendarEvent, 'canBeDeleted') && !$calendarEvent->canBeDeleted()) {
            return response()->json([
                'success' => false,
                'error' => 'Cet événement ne peut pas être supprimé',
            ], 403);
        }

        try {
            $calendarEvent->delete();

            return response()->json([
                'success' => true,
                'message' => 'Événement supprimé avec succès',
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur suppression calendrier', [
                'event_id' => $calendarEvent->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la suppression : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Portfolio - Upload images
     */
    public function portfolioUpload(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|file|mimes:jpeg,png,jpg,gif,webp,heic,heif|max:10240',
            'collection' => 'required|in:portfolio,drawings,before_after'
        ], [
            'images.required' => 'Merci de sélectionner au moins une image.',
            'images.array' => 'Merci de sélectionner au moins une image.',
            'images.min' => 'Merci de sélectionner au moins une image.',
            'images.*.file' => 'Le fichier est invalide.',
            'images.*.mimes' => 'Format non supporté. Formats acceptés : JPG, PNG, GIF, WEBP, HEIC.',
            'images.*.max' => 'Image trop lourde (max 10MB).',
        ]);

        $isPro = (bool) ($tattooer->is_subscribed ?? false) || (($tattooer->current_plan ?? null) === 'pro');
        if (!$isPro) {
            $currentCount = $tattooer->getMedia('portfolio')->count()
                + $tattooer->getMedia('drawings')->count()
                + $tattooer->getMedia('before_after')->count();
            $incomingCount = count($request->file('images') ?? []);

            if (($currentCount + $incomingCount) > 20) {
                $message = 'Plan FREE : maximum 20 images au total sur le portfolio. Passez en PRO pour illimité.';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'errors' => ['images' => [$message]],
                    ], 422);
                }

                return back()->withErrors(['images' => $message]);
            }
        }

        $collection = $request->collection;

        foreach ($request->file('images') as $image) {
            $tattooer->addMedia($image)
                ->toMediaCollection($collection);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Images uploadées avec succès',
            ]);
        }

        return redirect()->route('tattooer.portfolio')
            ->with('success', 'Images uploadées avec succès');
    }

    /**
     * Portfolio - Store before/after pair
     */
    public function portfolioBeforeAfterStore(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        $request->validate([
            'before' => 'required|array|min:1',
            'before.*' => 'required|file|mimes:jpeg,png,jpg,gif,webp,heic,heif|max:10240',
            'after' => 'required|array|min:1',
            'after.*' => 'required|file|mimes:jpeg,png,jpg,gif,webp,heic,heif|max:10240',
        ], [
            'before.required' => 'Merci de sélectionner au moins une photo AVANT.',
            'before.array' => 'Merci de sélectionner au moins une photo AVANT.',
            'before.min' => 'Merci de sélectionner au moins une photo AVANT.',
            'before.*.required' => 'Merci de sélectionner une photo AVANT.',
            'before.*.file' => 'Le fichier AVANT est invalide.',
            'before.*.mimes' => 'Format non supporté pour la photo AVANT. Formats acceptés : JPG, PNG, GIF, WEBP, HEIC.',
            'before.*.max' => 'Photo AVANT trop lourde (max 10MB).',
            'after.required' => 'Merci de sélectionner au moins une photo APRÈS.',
            'after.array' => 'Merci de sélectionner au moins une photo APRÈS.',
            'after.min' => 'Merci de sélectionner au moins une photo APRÈS.',
            'after.*.required' => 'Merci de sélectionner une photo APRÈS.',
            'after.*.file' => 'Le fichier APRÈS est invalide.',
            'after.*.mimes' => 'Format non supporté pour la photo APRÈS. Formats acceptés : JPG, PNG, GIF, WEBP, HEIC.',
            'after.*.max' => 'Photo APRÈS trop lourde (max 10MB).',
        ]);

        $beforeFiles = $request->file('before') ?? [];
        $afterFiles = $request->file('after') ?? [];

        $pairCount = min(count($beforeFiles), count($afterFiles));
        if ($pairCount < 1) {
            $message = 'Merci de sélectionner au moins une paire (Avant + Après).';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => ['before' => [$message]],
                ], 422);
            }

            return back()->withErrors(['before' => $message]);
        }

        $isPro = (bool) ($tattooer->is_subscribed ?? false) || (($tattooer->current_plan ?? null) === 'pro');
        if (!$isPro) {
            $currentCount = $tattooer->getMedia('portfolio')->count()
                + $tattooer->getMedia('drawings')->count()
                + $tattooer->getMedia('before_after')->count();
            $incomingCount = $pairCount * 2;

            if (($currentCount + $incomingCount) > 20) {
                $message = 'Plan FREE : maximum 20 images au total sur le portfolio. Passez en PRO pour illimité.';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'errors' => ['before' => [$message]],
                    ], 422);
                }

                return back()->withErrors(['before' => $message]);
            }
        }

        for ($i = 0; $i < $pairCount; $i++) {
            $pairId = (string) Str::uuid();

            $tattooer->addMedia($beforeFiles[$i])
                ->withCustomProperties([
                    'pair_id' => $pairId,
                    'role' => 'before',
                ])
                ->toMediaCollection('before_after');

            $tattooer->addMedia($afterFiles[$i])
                ->withCustomProperties([
                    'pair_id' => $pairId,
                    'role' => 'after',
                ])
                ->toMediaCollection('before_after');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Photos avant/après ajoutées avec succès',
            ]);
        }

        return redirect()->route('tattooer.portfolio')
            ->with('success', 'Photos avant/après ajoutées avec succès');
    }

    /**
     * Portfolio - Delete media
     */
    public function portfolioDestroy($media)
    {
        $tattooer = auth()->user()->tattooer;

        $mediaModel = Media::query()
            ->where('id', $media)
            ->where('model_type', 'App\\Models\\Tattooer')
            ->where('model_id', $tattooer->id)
            ->firstOrFail();

        $mediaModel->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Image supprimée avec succès',
            ]);
        }

        return redirect()->route('tattooer.portfolio')
            ->with('success', 'Image supprimée avec succès');
    }

    /**
     * Portfolio - Delete before/after pair
     */
    public function portfolioBeforeAfterDestroy($beforeId, $afterId)
    {
        $tattooer = auth()->user()->tattooer;

        $before = Media::query()
            ->where('id', $beforeId)
            ->where('model_type', 'App\\Models\\Tattooer')
            ->where('model_id', $tattooer->id)
            ->where('collection_name', 'before_after')
            ->firstOrFail();

        $after = Media::query()
            ->where('id', $afterId)
            ->where('model_type', 'App\\Models\\Tattooer')
            ->where('model_id', $tattooer->id)
            ->where('collection_name', 'before_after')
            ->firstOrFail();

        $before->delete();
        $after->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Photos avant/après supprimées avec succès',
            ]);
        }

        return redirect()->route('tattooer.portfolio')
            ->with('success', 'Photos avant/après supprimées avec succès');
    }
}
