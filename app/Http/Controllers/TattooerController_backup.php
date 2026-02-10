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
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TattooerController extends Controller
{
    /**
     * Profil public du tattooer (vue interne)
     */
    public function profile()
    {
        $tattooer = auth()->user()->tattooer;

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Stats similaires au dashboard mais orientées profil
        $stats = [
            'completed_projects' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->where('status', 'confirmed')
                ->count(),

            'active_projects' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->where('status', 'pending')
                ->count(),

            'total_clients' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->distinct('client_id')
                ->count('client_id'),

            'portfolio_count' => $tattooer->getMedia('portfolio')->count(),
        ];

        // Portfolio récent
        $portfolio = $tattooer->getMedia('portfolio')
            ->sortByDesc('created_at')
            ->take(12);

        return view('tattooer.profile', compact('tattooer', 'stats', 'portfolio'));
    }

    /**
     * Dashboard principal (Vue d'ensemble)
     */
    public function dashboard()
    {
        $tattooer = auth()->user()->tattooer;

        // Stats KPI
        $stats = [
            'pending_requests' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->where('status', 'pending')
                ->count(),

            'upcoming_appointments' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->where('status', 'accepted')
                ->whereNotNull('appointment_datetime')
                ->where('appointment_datetime', '>=', now())
                ->count(),

            'total_clients' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->distinct('client_id')
                ->count('client_id'),

            'monthly_revenue' => TattooHistory::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->whereMonth('tattoo_date', now()->month)
                ->sum('total_paid'),

            'unread_messages' => $this->getUnreadMessagesCount($tattooer),

            'appointments_this_week' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->whereBetween('appointment_datetime', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        // Activité récente (7 derniers jours)
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

        // Prochains RDV (3 prochains)
        $upcomingAppointments = BookingRequest::where('bookable_id', $tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->where('status', 'accepted')
            ->whereNotNull('appointment_datetime')
            ->where('appointment_datetime', '>=', now())
            ->with('client')
            ->orderBy('appointment_datetime')
            ->take(3)
            ->get();

        return view('tattooer.dashboard', compact('tattooer', 'stats', 'recentActivity', 'upcomingAppointments'));
    }

    /**
     * Gestion demandes projet
     */
    public function requests(Request $request)
    {
        $tattooer = auth()->user()->tattooer;
        $filter = $request->query('status', 'pending'); // par défaut "pending"

        $query = BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')
            ->where('bookable_id', $tattooer->id)
            ->with(['client', 'client.user', 'client.media', 'media']);

        // Filtrer par statut selon l'onglet
        $query = match($filter) {
            'pending'   => $query->where('status', 'pending'),
            'accepted'  => $query->whereIn('status', ['accepted', 'awaiting_deposit', 'deposit_paid', 'design_sent']),
            'confirmed' => $query->where('status', 'confirmed'),
            'completed' => $query->where('status', 'completed'),
            'cancelled' => $query->whereIn('status', ['cancelled', 'rejected', 'expired']),
            default     => $query,
        };

        $requests = $query->orderBy('created_at', 'desc')->get();

        // Compteurs pour les badges onglets
        $counts = [
            'pending'   => BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id)->where('status', 'pending')->count(),
            'accepted'  => BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id)->whereIn('status', ['accepted', 'awaiting_deposit', 'deposit_paid', 'design_sent'])->count(),
            'confirmed' => BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id)->where('status', 'confirmed')->count(),
            'completed' => BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id)->where('status', 'completed')->count(),
            'cancelled' => BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id)->whereIn('status', ['cancelled', 'rejected', 'expired'])->count(),
        ];

        // Forcer le rechargement des compteurs depuis la base de données
        $freshCounts = [
            'pending'   => BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id)->where('status', 'pending')->count(),
            'accepted'  => BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id)->whereIn('status', ['accepted', 'awaiting_deposit', 'deposit_paid', 'design_sent'])->count(),
            'confirmed' => BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id)->where('status', 'confirmed')->count(),
            'completed' => BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id)->where('status', 'completed')->count(),
            'cancelled' => BookingRequest::where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id)->whereIn('status', ['cancelled', 'rejected', 'expired'])->count(),
        ];

        $counts = $freshCounts;

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
     * Accepter une demande de réservation avec modal complet
     */
    public function acceptRequest(Request $request, BookingRequest $bookingRequest)
    {
        $tattooer = auth()->user()->tattooer;

        // Vérifier propriété
        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== 'App\\Models\\Tattooer') {
            abort(403);
        }

        // Validation
        $validated = $request->validate([
            'price_range_min' => 'required|numeric|min:0',
            'price_range_max' => 'required|numeric|gt:price_range_min',
            'proposed_dates' => 'required|array|min:1|max:3',
            'proposed_dates.*' => 'required|date|after:today',
            'included_design_versions' => 'required|integer|min:1|max:3',
            'modifications_per_version' => 'required|integer|min:0|max:5',
            'design_modification_rules' => 'nullable|string',
            'total_deposit_amount' => 'required|numeric|min:0',
            'client_payment_deadline_days' => 'required|integer|min:1|max:30',
            'deposit_covers_description' => 'nullable|string',
            'tattooer_notes' => 'nullable|string|max:1000',
        ]);

        // Mettre à jour la demande de réservation
        $bookingRequest->update([
            // Statut et dates
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT, // Statut correct
            'accepted_at' => now(),

            // Fourchette de prix
            'price_range_min' => $validated['price_range_min'],
            'price_range_max' => $validated['price_range_max'],
            // estimated_total_price sera calculé après versement de l'acompte
            'proposed_dates' => $validated['proposed_dates'],

            // Phase création
            'included_design_versions' => $validated['included_design_versions'],
            'modifications_per_version' => $validated['modifications_per_version'],
            'design_modification_rules' => $validated['design_modification_rules'],

            // Acompte
            'total_deposit_amount' => $validated['total_deposit_amount'],
            'client_payment_deadline_days' => (int) $validated['client_payment_deadline_days'],
            'client_payment_deadline' => now()->addDays((int) $validated['client_payment_deadline_days']),
            'deposit_covers_description' => $validated['deposit_covers_description'],

            // Message
            'tattooer_notes' => $validated['tattooer_notes'],

            // Chat avec logique de délai
            'chat_status' => BookingRequest::CHAT_STATUS_OPEN,
            'chat_closes_at' => now()->addDays((int) $validated['client_payment_deadline_days']), // Fermeture après délai acompte
        ]);

        // Créer ou mettre à jour conversation
        $conversation = $bookingRequest->conversation;
        if (!$conversation) {
            $conversation = Conversation::create([
                'booking_request_id' => $bookingRequest->id,
                'expiry_type' => 'deposit_pending',
                'deposit_deadline_at' => $bookingRequest->client_payment_deadline,
                'status' => 'active',
            ]);

            // Ajouter participants
            $conversation->participants()->attach([
                $bookingRequest->client->user_id => ['role' => 'client'],
                $tattooer->user_id => ['role' => 'tattooer'],
            ]);
        }

        return redirect()->route('tattooer.requests')
            ->with('success', 'Demande acceptée ! Le client a été notifié.');
    }

    /**
     * Refuser une demande de réservation
     */
    public function requestReject(BookingRequest $bookingRequest)
    {
        $tattooer = auth()->user()->tattooer;

        // Vérifier que la demande appartient au tattooer
        if ($bookingRequest->bookable_id !== $tattooer->id ||
            $bookingRequest->bookable_type !== 'App\\Models\\Tattooer') {
            abort(403);
        }

        // Vérifier que la demande est en attente
        if ($bookingRequest->status !== 'pending') {
            return back()->with('error', 'Cette demande ne peut plus être refusée.');
        }

        // Mettre à jour le statut
        $bookingRequest->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('tattooer.requests')
            ->with('success', 'Demande refusée.');
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
            ->withCount(['messages as unread_count' => function($q) {
                $q->where('sender_type', 'client')
                  ->whereDoesntHave('conversation.participants', function($subQ) {
                      $subQ->where('user_id', auth()->id())
                            ->whereNotNull('last_read_at');
                  });
            }])
            ->orderByDesc('updated_at')
            ->get();

        return view('tattooer.messages', compact('conversations'));
    }

    /**
     * Conversation détaillée
     */
    public function messageShow(BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer
        abort_unless($bookingRequest->bookable_id == auth()->user()->tattooer->id &&
                    $bookingRequest->bookable_type == 'App\Models\Tattooer', 403);

        $messages = $bookingRequest->messages()
            ->with('sender', 'media')
            ->orderBy('created_at')
            ->get();

        // Marquer les messages du client comme lus
        if ($bookingRequest->conversation) {
            $tattooerUser = auth()->user();

            // Vérifier si le tattooer est participant
            $participant = $bookingRequest->conversation->participants()
                ->where('user_id', $tattooerUser->id)
                ->first();

            if ($participant) {
                // Mettre à jour last_read_at
                $participant->pivot->update(['last_read_at' => now()]);
            }
        }

        return view('tattooer.message-show', compact('bookingRequest', 'messages'));
    }

    /**
     * Envoyer un message au client
     */
    public function messageSend(Request $request, BookingRequest $bookingRequest)
    {
        // Vérifier que la demande appartient bien au tattooer
        abort_unless($bookingRequest->bookable_id == auth()->user()->tattooer->id &&
                    $bookingRequest->bookable_type == 'App\Models\Tattooer', 403);

        // Vérifier que le chat est ouvert
        if (!$bookingRequest->isChatOpen()) {
            return back()->with('error', 'Le chat est fermé');
        }

        $validated = $request->validate([
            'content' => 'nullable|string|max:2000',
            'attachments.*' => 'nullable|file|mimes:jpeg,jpg,png,webp,pdf|max:10240',
            'drawing_type' => 'nullable|in:new_design,modification,reference,other',
        ]);

        // Conserver le contenu même si vide quand il y a des pièces jointes
        $content = $validated['content'] ?? '';
        $drawingType = $validated['drawing_type'] ?? '';

        // Si le contenu est vide mais il y a des pièces jointes, ajouter un message par défaut
        if (empty($content) && $request->hasFile('attachments')) {
            $typeLabels = [
                'new_design' => '🎨 Nouveau dessin envoyé',
                'modification' => '✏️ Modification envoyée',
                'reference' => '📸 Référence envoyée',
                'other' => '📎 Fichier envoyé'
            ];
            $content = $typeLabels[$drawingType] ?? 'Dessin envoyé';
        }

        // Autoriser les pièces jointes pour les tattooers (envoi de dessins)
        // Seuls les clients sont bloqués si l'acompte n'est pas payé
        // if ($request->hasFile('attachments') && !$bookingRequest->deposit_paid_at) {
        //     return back()->with('error', 'Les pièces jointes ne sont autorisées qu\'après paiement de l\'acompte');
        // }

        // Vérifier qu'il y a du contenu ou des pièces jointes
        if (empty($validated['content']) && !$request->hasFile('attachments')) {
            return back()->with('error', 'Veuillez entrer un message ou joindre un fichier');
        }

        // Récupérer ou créer la conversation
        $conversation = $bookingRequest->conversation;
        if (!$conversation) {
            $conversation = Conversation::create([
                'booking_request_id' => $bookingRequest->id,
                'expiry_type' => 'deposit_pending',
                'deposit_deadline_at' => $bookingRequest->client_payment_deadline,
                'status' => 'active',
            ]);
        }

        // Créer le message
        $message = $conversation->messages()->create([
            'conversation_id' => $conversation->id,
            'booking_request_id' => $bookingRequest->id,
            'sender_id' => auth()->id(),
            'sender_type' => 'tattooer',
            'content' => $content,
        ]);

        // Upload pièces jointes
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $message->addMedia($file)->toMediaCollection('attachments');
            }

            // Mettre à jour les compteurs selon le type de dessin
            if (!empty($drawingType)) {
                switch ($drawingType) {
                    case 'new_design':
                        DB::table('booking_requests')
                            ->where('id', $bookingRequest->id)
                            ->increment('design_versions_used');
                        break;
                    case 'modification':
                        DB::table('booking_requests')
                            ->where('id', $bookingRequest->id)
                            ->increment('modifications_used');
                        break;
                    case 'reference':
                    case 'other':
                        // Ne pas compter les références et autres fichiers
                        break;
                }
                // Rafraîchir le modèle pour avoir les valeurs à jour
                $bookingRequest->refresh();
            }
        }

        // Mettre à jour la conversation
        $conversation->update([
            'last_message_id' => $message->id,
            'last_message_at' => now(),
        ]);

        // TODO: Notification au client
        // $bookingRequest->client->user->notify(new NewMessageNotification($message));

        return back()->with('success', 'Message envoyé');
    }

    /**
     * Clients (liste + recherche)
     */
    public function clients(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        // Récupérer tous les clients uniques via les booking requests
        $clientsQuery = BookingRequest::where('bookable_id', $tattooer->id)
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
        $hasAccess = BookingRequest::where('bookable_id', auth()->user()->tattooer->id)
            ->where('bookable_type', 'App\Models\Tattooer')
            ->where('client_id', $client->id)
            ->exists();

        abort_unless($hasAccess, 403);

        $history = TattooHistory::where('client_id', $client->id)
            ->orderByDesc('tattoo_date')
            ->get();

        return view('tattooer.client-show', compact('client', 'history'));
    }

    /**
     * Voir les demandes d'un client spécifique
     */
    public function clientRequests(int $clientId)
    {
        $tattooer = auth()->user()->tattooer;
        $client = \App\Models\Client::findOrFail($clientId);

        // Vérifier que le client appartient bien à ce tattooer
        $hasAnyRequest = \App\Models\BookingRequest::where('client_id', $clientId)
            ->where('bookable_type', 'App\\Models\\Tattooer')
            ->where('bookable_id', $tattooer->id)
            ->exists();

        if (!$hasAnyRequest) {
            abort(403, 'Ce client n\'a pas de demandes avec vous.');
        }

        $requests = \App\Models\BookingRequest::where('client_id', $clientId)
            ->where('bookable_type', 'App\\Models\\Tattooer')
            ->where('bookable_id', $tattooer->id)
            ->with(['conversation'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tattooer.client-requests', compact('client', 'requests'));
    }

    /**
     * Mettre à jour les horaires de travail
     */
    public function updateHours(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        // Récupérer et parser les horaires
        $workingHours = [];
        foreach ($request->get('working_hours', []) as $index => $dayJson) {
            $day = json_decode($dayJson, true);
            if ($day) {
                $workingHours[] = $day;
            }
        }

        $tattooer->update(['working_hours' => $workingHours]);

        return redirect()->back()->with('success', 'Horaires mis à jour !');
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

        // Charger la relation workingHours pour la vue
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
            'pending_deposits' => BookingRequest::where('bookable_id', $tattooer->id)
                ->where('bookable_type', 'App\Models\Tattooer')
                ->whereNotNull('total_deposit_amount')
                ->whereNull('deposit_paid_at')
                ->sum('total_deposit_amount'),
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
     * Conformité et badges
     */
    public function compliance()
    {
        $tattooer = auth()->user()->tattooer;
        $tattooer->load(['media', 'user']);

        return view('tattooer.compliance', compact('tattooer'));
    }

    /**
     * Paramètres - Mise à jour
     */
    public function settingsUpdate(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        // Valider les données
        $validated = $request->validate([
            'pseudo' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'studio_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'bio' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
            'styles' => 'nullable|array',
            'styles.*' => 'string|max:50',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'minimum_price' => 'nullable|numeric|min:0|max:1000',
            'wait_time_weeks_min' => 'nullable|integer|min:0|max:52',
            'wait_time_weeks_max' => 'nullable|integer|min:0|max:52',
            'minimum_deposit' => 'nullable|numeric|min:0|max:10000',
            'default_deposit_rate' => 'nullable|numeric|min:0|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Mettre à jour le user
        $updateData = [];
        if (isset($validated['pseudo'])) {
            $updateData['name'] = $validated['pseudo'];
        }
        if (isset($validated['email'])) {
            $updateData['email'] = $validated['email'];
        }

        if (!empty($updateData)) {
            auth()->user()->update($updateData);
        }

        // Mettre à jour le tattooer
        $tattooer->update([
            'studio_name' => $validated['studio_name'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'bio' => $validated['bio'] ?? $validated['description'] ?? null,
            'description' => $validated['description'] ?? $validated['bio'] ?? null,
            'styles' => isset($validated['styles']) ? json_encode($validated['styles']) : null,
            'years_of_experience' => $validated['years_of_experience'] ?? null,
            'minimum_price' => $validated['minimum_price'] ?? null,
            'wait_time_weeks_min' => $validated['wait_time_weeks_min'] ?? null,
            'wait_time_weeks_max' => $validated['wait_time_weeks_max'] ?? null,
            'minimum_deposit' => $validated['minimum_deposit'] ?? 0,
            'default_deposit_rate' => $validated['default_deposit_rate'] ?? 20,
        ]);

        // Gérer l'upload de l'avatar
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $tattooer->addMedia($avatar)->toMediaCollection('avatar');
        }

        // Gérer l'upload de la bannière
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $tattooer->addMedia($banner)->toMediaCollection('banner');
        }

        return redirect()->route('tattooer.settings')
            ->with('success', 'Vos paramètres ont été mis à jour avec succès.');
    }

    /**
     * Supprimer l'avatar
     */
    public function deleteAvatar()
    {
        $tattooer = auth()->user()->tattooer;

        if ($tattooer->hasMedia('avatar')) {
            $tattooer->clearMediaCollection('avatar');
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
     * Paramètres - Mise à jour horaires
     */
    public function settingsUpdateSchedule(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        $scheduleData = $request->get('schedule', []);

        foreach ($scheduleData as $dayName => $dayData) {
            $dayOfWeek = $this->getDayOfWeek($dayName);
            $isOpen = isset($dayData['open']) ? true : false;

            // Supprimer l'ancien enregistrement s'il existe
            $tattooer->workingHours()->where('day_of_week', $dayOfWeek)->delete();

            // Créer le nouvel enregistrement si ouvert
            if ($isOpen && isset($dayData['start']) && isset($dayData['end'])) {
                $tattooer->workingHours()->create([
                    'day_of_week' => $dayOfWeek,
                    'is_open' => true,
                    'start_time' => $dayData['start'],
                    'end_time' => $dayData['end'],
                ]);
            }
        }

        return redirect()->route('tattooer.settings')->with('success', 'Horaires mis à jour avec succès !');
    }

    private function getDayOfWeek($dayName)
    {
        $days = [
            'lundi' => 1,
            'mardi' => 2,
            'mercredi' => 3,
            'jeudi' => 4,
            'vendredi' => 5,
            'samedi' => 6,
            'dimanche' => 0,
        ];

        return $days[strtolower($dayName)] ?? 1;
    }

    /**
     * Paramètres - Mise à jour profil
     */
    public function settingsUpdateProfile(Request $request)
    {
        $tattooer = auth()->user()->tattooer;

        // Valider les données
        $validated = $request->validate([
            'pseudo' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'studio_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'bio' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
            'styles' => 'nullable|array',
            'styles.*' => 'string|max:50',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'minimum_price' => 'nullable|numeric|min:0|max:1000',
            'wait_time_weeks_min' => 'nullable|integer|min:0|max:52',
            'wait_time_weeks_max' => 'nullable|integer|min:0|max:52',
            'minimum_deposit' => 'nullable|numeric|min:0|max:10000',
            'default_deposit_rate' => 'nullable|numeric|min:0|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Mettre à jour le user
        $updateData = [];
        if (isset($validated['pseudo'])) {
            $updateData['name'] = $validated['pseudo'];
        }
        if (isset($validated['email'])) {
            $updateData['email'] = $validated['email'];
        }

        auth()->user()->update($updateData);

        // Mettre à jour le tattooer
        $tattooerUpdateData = [];
        if (isset($validated['phone'])) {
            $tattooerUpdateData['phone'] = $validated['phone'];
        }
        if (isset($validated['studio_name'])) {
            $tattooerUpdateData['studio_name'] = $validated['studio_name'];
        }
        if (isset($validated['address'])) {
            $tattooerUpdateData['address'] = $validated['address'];
        }
        if (isset($validated['city'])) {
            $tattooerUpdateData['city'] = $validated['city'];
        }
        if (isset($validated['postal_code'])) {
            $tattooerUpdateData['postal_code'] = $validated['postal_code'];
        }
        if (isset($validated['bio'])) {
            $tattooerUpdateData['bio'] = $validated['bio'];
        }
        if (isset($validated['description'])) {
            $tattooerUpdateData['description'] = $validated['description'];
        }
        if (isset($validated['styles'])) {
            $tattooerUpdateData['styles'] = $validated['styles'];
        }
        if (isset($validated['years_of_experience'])) {
            $tattooerUpdateData['years_of_experience'] = $validated['years_of_experience'];
        }
        if (isset($validated['minimum_price'])) {
            $tattooerUpdateData['minimum_price'] = $validated['minimum_price'];
        }
        if (isset($validated['wait_time_weeks_min'])) {
            $tattooerUpdateData['wait_time_weeks_min'] = $validated['wait_time_weeks_min'];
        }
        if (isset($validated['wait_time_weeks_max'])) {
            $tattooerUpdateData['wait_time_weeks_max'] = $validated['wait_time_weeks_max'];
        }
        if (isset($validated['minimum_deposit'])) {
            $tattooerUpdateData['minimum_deposit'] = $validated['minimum_deposit'];
        }
        if (isset($validated['default_deposit_rate'])) {
            $tattooerUpdateData['default_deposit_rate'] = $validated['default_deposit_rate'];
        }

        $tattooer->update($tattooerUpdateData);

        // Gérer les médias
        if ($request->hasFile('avatar')) {
            $tattooer->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }
        if ($request->hasFile('banner')) {
            if ($tattooer->hasMedia('banner')) {
                $tattooer->clearMediaCollection('banner');
            }
            $tattooer->addMediaFromRequest('banner')->toMediaCollection('banner');
        }

        return redirect()->back()->with('success', 'Profil mis à jour avec succès !');
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

    /**
     * Compter les messages non lus pour un tattooer
     */
    private function getUnreadMessagesCount($tattooer)
    {
        // Récupérer toutes les conversations du tattooer
        $conversationIds = \App\Models\Conversation::whereHas('bookingRequest', function($q) use ($tattooer) {
            $q->where('bookable_type', 'App\\Models\\Tattooer')
              ->where('bookable_id', $tattooer->id);
        })->pluck('id');

        // Compter messages non-lus
        $unreadCount = 0;

        foreach ($conversationIds as $conversationId) {
            $conversation = \App\Models\Conversation::find($conversationId);

            if ($conversation) {
                $pivot = $conversation->participants()
                    ->where('user_id', auth()->id())
                    ->first()
                    ?->pivot;

                $lastReadAt = $pivot?->last_read_at ?? now()->subYears(10);

                $unreadCount += $conversation->messages()
                    ->where('sender_id', '!=', auth()->id())
                    ->where('created_at', '>', $lastReadAt)
                    ->count();
            }
        }

        return $unreadCount;
    }
}
