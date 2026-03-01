<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Review;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\ClientConsentForm;
use App\Models\Appointment;
use App\Enums\BookingRequestStatus;
use App\Enums\ConversationStatus;
use App\Enums\AppointmentStatus;
use App\Actions\ReportNoShowAction;
use App\Notifications\NewMessageNotification;
use App\Notifications\AppointmentConfirmedNotification;
use Illuminate\Support\Facades\Auth;


class ClientController extends Controller
{
    /**
     * Dashboard client avec demandes et messages
     */
    public function dashboard()
    {
        $client = auth()->user()->client;

        if (!$client) {
            abort(403, 'Profil client non trouvé');
        }

        // UNE SEULE requête avec eager loading avancé et withCount
        $bookingRequests = BookingRequest::where('client_id', $client->id)
            ->with([
                'bookable.user', // Charger le tatoueur en même temps
                'conversation' => function($query) {
                    $query->withCount(['messages as unread_count' => function($q) {
                        $q->where('sender_type', 'tattooer')
                              ->whereNull('read_by_client_at');
                    }]);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Stats calculées à partir de la collection (pas de requêtes supplémentaires)
        $stats = [
            'total_requests' => $bookingRequests->count(),
            'pending' => $bookingRequests->where('status', BookingRequestStatus::PENDING->value)->count(),
            'accepted' => $bookingRequests->whereIn('status', [
                BookingRequestStatus::ACCEPTED->value,
                BookingRequestStatus::DEPOSIT_REQUESTED->value,
            ])->count(),
            'active' => $bookingRequests->whereIn('status', [
                BookingRequestStatus::DEPOSIT_PAID->value,
                BookingRequestStatus::DATE_CONFIRMED->value,
            ])->count(),
            'completed' => $bookingRequests->where('status', BookingRequestStatus::COMPLETED->value)->count(),
            'unread_messages' => $bookingRequests->sum('conversation.unread_count'),
        ];

        // Prendre les 5 plus récents APRÈS le chargement (évite 2ème requête)
        $recentBookingRequests = $bookingRequests->take(5);

        return view('client.dashboard', compact('bookingRequests', 'stats', 'recentBookingRequests'));
    }

    /**
     * Liste des demandes du client
     */
    public function bookingRequests(Request $request)
    {
        $client = Auth::user()->client;

        if (!$client) {
            abort(403, 'Profil client non trouvé');
        }

        $query = BookingRequest::where('client_id', $client->id)
            ->with('bookable', 'conversation.messages', 'reviews');

        // Filtrer par statut si spécifié
        if ($request->filled('status')) {
            // Convertir le string en Enum si valide
            try {
                $statusEnum = BookingRequestStatus::from($request->status);
                $query->where('status', $statusEnum->value);
            } catch (\ValueError $e) {
                // Statut invalide, ignorer le filtre
            }
        }

        $bookingRequests = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('client.booking-requests', compact('bookingRequests'));
    }

    /**
     * Détails d'une demande
     */
    public function bookingRequestShow(BookingRequest $bookingRequest)
    {
        $client = Auth::user()->client;

        if (!$client || $bookingRequest->client_id !== $client->id) {
            abort(403, 'Non autorisé');
        }

        $bookingRequest->load('bookable', 'conversation.messages.sender');

        return view('client.booking-request-show', compact('bookingRequest'));
    }

    /**
     * Chat avec le tattooer
     */
    public function chat(Conversation $conversation)
    {
        $client = Auth::user()->client;

        // DEBUG : Log les IDs pour vérification
        Log::info('ClientController::chat - DEBUG', [
            'conversation_id' => $conversation->id,
            'auth_user_id' => Auth::user()->id,
            'client_id' => $client ? $client->id : 'null',
        ]);

        // Vérifier que le client est bien le propriétaire de la demande associée
        $bookingRequest = $conversation->bookingRequest;
        if (!$bookingRequest || $bookingRequest->client_id !== $client->id) {
            Log::warning('ClientController::chat - ACCESS DENIED', [
                'booking_request_client_id' => $bookingRequest ? $bookingRequest->client_id : 'null',
                'auth_client_id' => $client ? $client->id : 'null',
                'booking_request_id' => $bookingRequest ? $bookingRequest->id : 'null',
            ]);
            abort(403, 'Non autorisé - Cette conversation ne vous appartient pas.');
        }

        // Vérifier que le chat est ouvert (logique corrigée)
        // Supprimé : la vue utilisera @can('sendMessage', $conversation) à la place

        // ⭐ Récupérer les informations d'expiration pour l'affichage
        $expiryInfo = null;
        if ($conversation) {
            // Toujours récupérer les infos si la conversation existe
            $expiryInfo = [
                'expires_at' => $conversation->expires_at,
                'days_remaining' => $conversation->getDaysUntilExpiry(),
                'time_remaining' => $conversation->getTimeUntilExpiry(),
                'warning_message' => $conversation->getExpiryWarningMessage(),
                'is_expired' => $conversation->isExpired(),
                'expiry_type' => $conversation->expiry_type,
                'deposit_deadline_at' => $conversation->deposit_deadline_at,
            ];
        }

        // Récupérer les messages de la conversation (la vue gérera l'affichage conditionnel avec @can)
        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer les messages comme lus par le client
        $messages->where('sender_type', '!=', 'client')
            ->whereNull('read_by_client_at')
            ->each(function ($message) {
                $message->update(['read_by_client_at' => now()]);
            });

        return view('client.chat', compact('conversation', 'bookingRequest', 'messages', 'expiryInfo'));
    }

    /**
     * Supprimer une demande de réservation (uniquement si rejetée)
     */
    public function bookingRequestDelete(BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;

        // Vérifier que la demande appartient au client
        if ($bookingRequest->client_id !== $client->id) {
            abort(403, 'Cette demande ne vous appartient pas.');
        }

        // Vérifier que la demande est rejetée (seul statut autorisé pour suppression)
        if ($bookingRequest->status !== 'rejected') {
            return redirect()->back()
                ->with('error', 'Seules les demandes refusées peuvent être supprimées.');
        }

        // Supprimer la conversation associée si elle existe (hard delete)
        if ($bookingRequest->conversation) {
            $bookingRequest->conversation->messages()->delete(); // Supprimer messages
            $bookingRequest->conversation->delete(); // Supprimer conversation
        }

        // Supprimer la demande (hard delete - définitif)
        $bookingRequest->forceDelete(); // Hard delete au lieu de soft delete

        return redirect()->route('client.booking-requests')
            ->with('success', 'La demande refusée a été supprimée définitivement de la base de données.');
    }

    /**
     * Annuler une demande de réservation
     */
    public function bookingRequestCancel(BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;

        // Debug
        Log::info('Tentative annulation demande', [
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $client->id,
            'booking_client_id' => $bookingRequest->client_id,
            'status' => $bookingRequest->status->value,
        ]);

        // Vérifier que la demande appartient au client
        if ($bookingRequest->client_id !== $client->id) {
            Log::error('Tentative annulation demande non autorisée', [
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $client->id,
                'booking_client_id' => $bookingRequest->client_id,
            ]);
            abort(403, 'Cette demande ne vous appartient pas.');
        }

        // Vérifier que la demande peut être annulée
        if (!in_array($bookingRequest->status->value, ['pending', 'accepted'])) {
            Log::error('Tentative annulation demande statut non autorisé', [
                'booking_request_id' => $bookingRequest->id,
                'status' => $bookingRequest->status->value,
            ]);
            return redirect()->back()
                ->with('error', 'Cette demande ne peut plus être annulée.');
        }

        // Mettre à jour le statut
        $bookingRequest->update([
            'status' => \App\Enums\BookingRequestStatus::CANCELLED->value,
            'cancelled_by' => 'client',
            'cancelled_at' => now(),
            'cancellation_reason' => 'Annulation par le client',
        ]);

        // Fermer la conversation associée
        if ($bookingRequest->conversation) {
            $bookingRequest->conversation->update(['status' => \App\Enums\ConversationStatus::CLOSED->value]);
        }

        return redirect()->route('client.booking-requests')
            ->with('success', 'Votre demande a été annulée avec succès.');
    }

    /**
     * Liste des conversations du client
     */
    public function messages()
    {
        $conversations = auth()->user()
            ->conversations()                          // via pivot conversation_user (users.id)
            ->where('status', 'active')
            ->whereNull('deleted_at')                  // soft delete
            ->with([
                'bookingRequest.bookable.user',        // artiste (pour avatar + nom)
                'messages' => function ($query) {
                    $query->latest()->limit(1); // dernier message pour aperçu
                },
            ])
            ->orderByDesc('last_message_at')
            ->get();

        return view('client.messages', compact('conversations'));
    }

    /**
     * Liste des conversations (pour navigation)
     */
    public function conversationsList()
    {
        $user = auth()->user();
        $client = $user->client;

        // Récupérer toutes les conversations où le client est participant
        $conversations = $client->conversations()
            ->with([
                'lastMessage.sender',
                'bookingRequest' => function ($query) {
                    $query->with([
                        'bookable.user', // Tattooer ou Piercer
                        'client.user'
                    ]);
                }
            ])
            ->where('status', 'active')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Ajouter compteur non-lus pour chaque conversation
        $conversations->transform(function ($conversation) use ($client) {
            // Récupérer le pivot de l'utilisateur dans cette conversation
            $pivot = $conversation->pivot; // Disponible grâce à belongsToMany

            if ($pivot) {
                // Compter messages non lus (créés après last_read_at)
                $conversation->unread_count = $conversation->messages()
                    ->where('sender_id', '!=', $client->id)
                    ->where('sender_type', '!=', get_class($client))
                    ->where(function ($query) use ($pivot) {
                        $query->where('created_at', '>', $pivot->last_read_at ?? now()->subYears(10));
                    })
                    ->count();
            } else {
                $conversation->unread_count = 0;
            }

            return $conversation;
        });

        return view('client.messages', compact('conversations'));
    }

    /**
     * Envoyer un message au tattooer
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $client = Auth::user()->client;

        // Vérifier que le client est bien le propriétaire de la demande associée
        $bookingRequest = $conversation->bookingRequest;
        if (!$bookingRequest || $bookingRequest->client_id !== $client->id) {
            abort(403, 'Non autorisé - Cette conversation ne vous appartient pas.');
        }

        // Vérifier que le chat est ouvert avec les Policies
        // La vue utilise déjà @can(), ici on vérifie juste l'autorisation de base
        if (!$conversation || $bookingRequest->client_id !== $client->id) {
            return back()->with('error', 'Non autorisé');
        }

        $validated = $request->validate([
            'content' => 'nullable|string|max:2000',
            'attachments.*' => 'nullable|file|mimes:jpeg,jpg,png,webp,pdf|max:10240',
        ]);

        // Conserver le contenu même si vide quand il y a des pièces jointes
        $content = $validated['content'] ?? '';

        // Si le contenu est vide mais il y a des pièces jointes, ajouter un message par défaut
        if (empty($content) && $request->hasFile('attachments')) {
            $content = 'Image envoyée';
        }

        // Bloquer les pièces jointes si l'acompte n'est pas payé
        if ($request->hasFile('attachments') && !$bookingRequest->deposit_paid_at) {
            return back()->with('error', 'Les pièces jointes ne sont autorisées qu\'après paiement de l\'acompte');
        }

        // Créer le message
        $message = $conversation->messages()->create([
            'conversation_id' => $conversation->id,
            'booking_request_id' => $bookingRequest->id, // ✅ Ajouté
            'sender_id' => Auth::id(),
            'sender_type' => 'client',
            'content' => $content,
        ]);

        // Upload pièces jointes
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $message->addMedia($file)->toMediaCollection('attachments');
            }
        }

        // Mettre à jour la conversation
        $conversation->update([
            'last_message_id' => $message->id,
            'last_message_at' => now(),
        ]);

        // Notifier l'artiste du nouveau message
        if ($bookingRequest->bookable?->user) {
            $bookingRequest->bookable->user->notify(new NewMessageNotification($message));
        }

        return back()->with('success', 'Message envoyé');
    }


    /**
     * Sélectionner une date proposée par le tattooer
     */
    public function selectProposedDate(Request $request, BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;

        if (!$client || $bookingRequest->client_id !== $client->id) {
            abort(403, 'Non autorisé');
        }

        $validated = $request->validate([
            'index' => 'required|integer|min:0',
        ]);

        $proposedDates = $bookingRequest->proposed_dates;

        if (!isset($proposedDates[$validated['index']])) {
            return redirect()->back()
                ->with('error', 'Date invalide.');
        }

        // Vérifier que l'acompte est payé
        if ($bookingRequest->status->value !== 'deposit_paid') {
            return redirect()->back()
                ->with('error', 'Vous devez payer l\'acompte avant de choisir une date.');
        }

        $selectedDate = $proposedDates[$validated['index']];

        $bookingRequest->update([
            'confirmed_date'            => $selectedDate['date'],
            'confirmed_period'          => $selectedDate['period'] ?? null,
            'client_selected_dates'     => [$selectedDate],
            'client_dates_selected_at'  => now(),
        ]);

        // Message système dans le chat
        $conversation = $bookingRequest->conversation;
        if ($conversation) {
            $dateFr = \Carbon\Carbon::parse($selectedDate['date'])->translatedFormat('l d F Y');
            $period = match($selectedDate['period'] ?? '') {
                'morning'   => 'matin',
                'afternoon' => 'après-midi',
                'evening'   => 'soirée',
                default     => 'horaire flexible',
            };

            $conversation->messages()->create([
                'sender_type' => 'system',
                'sender_id'   => null,
                'content'     => "📅 Le client a choisi la date du {$dateFr} ({$period}).",
            ]);

            // Envoyer le formulaire de consentement dans le chat
            $conversation->messages()->create([
                'sender_type' => 'system',
                'sender_id'   => null,
                'content'     => '[CONSENT_FORM:' . $bookingRequest->id . ']',
            ]);
        }

        // Notifier l'artiste que le client a sélectionné une date
        if ($bookingRequest->bookable?->user) {
            $bookingRequest->bookable->user->notify(new AppointmentConfirmedNotification($bookingRequest));
        }

        return redirect()->back()
            ->with('success', 'Date sélectionnée ! L\'artiste va fixer l\'horaire.');
    }

    /**
     * Demander des dates alternatives
     */
    public function requestAlternativeDates(Request $request, BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;

        if (!$client || $bookingRequest->client_id !== $client->id) {
            abort(403, 'Non autorisé');
        }

        $conversation = $bookingRequest->conversation;
        if ($conversation) {
            $conversation->messages()->create([
                'sender_type' => 'system',
                'sender_id'   => null,
                'content'     => "⚠️ Le client ne peut à aucune des dates proposées et demande d'autres alternatives.",
            ]);
        }

        return redirect()->back()
            ->with('info', 'Votre demande a été envoyée à l\'artiste.');
    }

    /**
     * Enregistrer le consentement éclairé du client (SNAT 2026)
     */
    public function storeConsent(Request $request, BookingRequest $bookingRequest)
    {
        $client = Auth::user()->client;

        // Vérifier que la demande appartient au client
        if ($bookingRequest->client_id !== $client->id) {
            abort(403, 'Cette demande de réservation ne vous appartient pas.');
        }

        // Validation complète SNAT 2026
        $validated = $request->validate([
            // Identité client
            'client_full_name' => 'required|string|max:255',
            'client_birth_date' => 'required|date|before:today',
            'client_address' => 'required|string|max:500',
            'client_phone' => 'required|string|max:20',
            'client_email' => 'required|email|max:255',
            'client_id_type' => 'required_if:is_minor,true|nullable|in:cni,passeport,titre_sejour',
            'client_id_number' => 'required_if:is_minor,true|nullable|string|max:50',

            // Mineur
            'is_minor' => 'boolean',
            'parent_name' => 'required_if:is_minor,true|nullable|string|max:255',
            'parent_relation' => 'required_if:is_minor,true|nullable|in:pere,mere,tuteur',
            'parent_id_number' => 'required_if:is_minor,true|nullable|string|max:50',
            'parent_signature_data' => 'required_if:is_minor,true|nullable|string',

            // Acte (pré-rempli depuis BR)
            'act_type' => 'required|string|in:tatouage,piercing,dermographie,scarification,modification_corporelle',
            'body_zone' => 'required|string|max:255',
            'act_description' => 'required|string|max:1000',

            // Questionnaire médical
            'medical_allergies' => 'boolean',
            'medical_allergies_detail' => 'required_if:medical_allergies,true|nullable|string|max:1000',
            'medical_anticoagulant' => 'boolean',
            'medical_diabetes' => 'boolean',
            'medical_cicatrisation' => 'boolean',
            'medical_skin_disease' => 'boolean',
            'medical_skin_disease_detail' => 'required_if:medical_skin_disease,true|nullable|string|max:1000',
            'medical_vih_hepatite' => 'boolean',
            'medical_pregnant' => 'boolean',
            'medical_roaccutane' => 'boolean',
            'medical_cheloide' => 'boolean',
            'medical_other' => 'nullable|string|max:1000',

            // Confirmations obligatoires
            'confirm_medical_sincere' => 'required|accepted',
            'confirm_risks_informed' => 'required|accepted',
            'confirm_info_sheet_read' => 'required|accepted',
            'confirm_aftercare_received' => 'required|accepted',
            'confirm_not_intoxicated' => 'required|accepted',
            'confirm_over_18_or_authorized' => 'required|accepted',
            'confirm_rgpd' => 'required|accepted',

            // Financier (pré-rempli depuis BR)
            'total_price' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'retouche_included' => 'boolean',

            // Image
            'image_authorization' => 'required|boolean',

            // Signature
            'signature_data' => 'required|string',
            'handwritten_mention' => 'required|string|max:255',
        ], [
            // Messages d'erreur personnalisés
            'client_full_name.required' => 'Le nom complet est obligatoire.',
            'client_birth_date.required' => 'La date de naissance est obligatoire.',
            'client_address.required' => 'L\'adresse complète est obligatoire.',
            'client_phone.required' => 'Le numéro de téléphone est obligatoire.',
            'client_email.required' => 'L\'adresse email est obligatoire.',
            'client_id_type.required' => 'Le type de pièce d\'identité est obligatoire pour les mineurs.',
            'client_id_number.required' => 'Le numéro de pièce d\'identité est obligatoire pour les mineurs.',

            'act_type.required' => 'Le type d\'acte est obligatoire.',
            'body_zone.required' => 'La zone du corps est obligatoire.',
            'act_description.required' => 'La description de l\'acte est obligatoire.',

            'confirm_medical_sincere.accepted' => 'Vous devez certifier avoir répondu de manière sincère au questionnaire médical.',
            'confirm_risks_informed.accepted' => 'Vous devez confirmer avoir été informé des risques.',
            'confirm_info_sheet_read.accepted' => 'Vous devez confirmer avoir lu la fiche d\'information préalable.',
            'confirm_aftercare_received.accepted' => 'Vous devez confirmer avoir reçu les conseils de soins post-séance.',
            'confirm_not_intoxicated.accepted' => 'Vous devez confirmer ne pas être sous l\'emprise de substances.',
            'confirm_over_18_or_authorized.accepted' => 'Vous devez confirmer être majeur ou avoir l\'autorisation parentale.',
            'confirm_rgpd.accepted' => 'Vous devez accepter le traitement de vos données personnelles.',

            'signature_data.required' => 'La signature est obligatoire.',
            'handwritten_mention.required' => 'La mention manuscrite est obligatoire.',
            'image_authorization.required' => 'L\'autorisation de prise de photos est obligatoire.',
            'total_price.required' => 'Le prix total est obligatoire.',
            'deposit_amount.required' => 'Le montant de l\'acompte est obligatoire.',
        ]);

        $tattooer = $bookingRequest->bookable;

        // Pré-remplir les données depuis BookingRequest
        $consentData = array_merge($validated, [
            'client_id' => $client->id,
            'tattooer_id' => $tattooer->id,
            'appointment_id' => $bookingRequest->appointment?->id ?? null,
            'booking_request_id' => $bookingRequest->id,
            'signed_at' => now(),
            'signed_ip' => $request->ip(),
            'signed_user_agent' => $request->userAgent(),
            'status' => 'signed',
        ]);

        // Créer/Mettre à jour le consentement
        $consent = ClientConsentForm::updateOrCreate(
            ['booking_request_id' => $bookingRequest->id],
            $consentData
        );

        // Upload pièce d'identité parent si mineur
        if ($request->hasFile('parent_id_document') && $validated['is_minor']) {
            $consent->clearMediaCollection('parent_id');
            $consent->addMediaFromRequest('parent_id_document')
                ->toMediaCollection('parent_id');
        }

        // Message système confirmation dans le chat
        if ($bookingRequest->conversation) {
            $bookingRequest->conversation->messages()->create([
                'sender_type' => 'system',
                'sender_id' => null,
                'content' => '✅ Consentement éclairé signé le ' . now()->format('d/m/Y à H:i'),
            ]);
        }

        return back()->with('success', '✅ Consentement éclairé signé avec succès !');
    }

    /**
     * Client signale un no-show (artiste absent)
     */
    public function reportNoShow(Request $request, Appointment $appointment)
    {
        // Vérifier que le client est bien celui du booking
        $bookingRequest = $appointment->bookingRequest;
        abort_unless(
            $bookingRequest && $bookingRequest->client_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'no_show_reason' => 'nullable|string|max:1000',
        ]);

        $action = new ReportNoShowAction();
        $action->execute($appointment, 'client', $validated['no_show_reason'] ?? null);

        return back()->with('success', 'Signalement envoyé. Notre équipe va examiner la situation.');
    }

    /**
     * Créer un avis pour une demande terminée
     */
    public function createReview(Request $request, BookingRequest $bookingRequest)
    {
        try {
            // Debug: Vérifier l'authentification
            Log::info('createReview DEBUG', [
                'auth_check' => Auth::check(),
                'auth_id' => Auth::id(),
                'booking_request_id' => $bookingRequest->id,
                'booking_request_client_id' => $bookingRequest->client_id,
                'user_id' => Auth::user() ? Auth::user()->id : 'null',
                'user_client_id' => Auth::user() && Auth::user()->client ? Auth::user()->client->id : 'null',
            ]);

            // Vérifier que la demande est terminée et appartient au client
            if (!$bookingRequest->isCompleted() || (Auth::user() && Auth::user()->client ? $bookingRequest->client_id !== Auth::user()->client->id : true)) {
                Log::error('createReview: Action non autorisée', [
                    'is_completed' => $bookingRequest->isCompleted(),
                    'br_client_id' => $bookingRequest->client_id,
                    'auth_id' => Auth::id(),
                    'user_client_id' => Auth::user() && Auth::user()->client ? Auth::user()->client->id : 'null',
                ]);
                return response()->json(['success' => false, 'message' => 'Action non autorisée'], 403);
            }

            // Vérifier que le client n'a pas déjà laissé d'avis
            $clientId = Auth::user()->client ? Auth::user()->client->id : null;
            if (!$clientId) {
                return response()->json(['success' => false, 'message' => 'Profil client non trouvé'], 400);
            }

            $existingReview = \App\Models\Review::where('reviewable_type', 'App\Models\BookingRequest')
                ->where('reviewable_id', $bookingRequest->id)
                ->where('client_id', $clientId)
                ->first();

            if ($existingReview) {
                return response()->json(['success' => false, 'message' => 'Vous avez déjà laissé un avis pour cette demande'], 400);
            }

            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
                'reviewable_id' => 'required|integer',
            ]);

            $review = \App\Models\Review::create([
                'reviewable_type' => 'App\Models\BookingRequest',
                'reviewable_id' => $validated['reviewable_id'],
                'client_id' => $clientId,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'is_visible' => true, // Visible immédiatement pour le tattooer
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Merci pour votre avis !',
                'review' => $review
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur createReview: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la sauvegarde de votre avis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Liste des avis du client
     */
    public function reviews()
    {
        $client = auth()->user()->client;
        abort_unless($client, 403, 'Profil client non trouvé');

        $reviews = Review::where('client_id', $client->id)
            ->with(['reviewable.bookable.user', 'reviewable.bookable.media'])
            ->latest()
            ->get();

        return view('client.reviews', compact('reviews'));
    }

    /**
     * Liste des réclamations du client
     */
    public function complaints()
    {
        $complaints = \App\Models\Complaint::where('user_id', auth()->id())
            ->with('bookingRequest')
            ->latest()
            ->get();

        return view('client.complaints', compact('complaints'));
    }

    /**
     * Créer une réclamation
     */
    public function createComplaint(Request $request, BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;
        abort_unless($client && $bookingRequest->client_id === $client->id, 403);

        $validated = $request->validate([
            'type' => 'required|string|in:quality,behavior,delay,billing,other',
            'description' => 'required|string|max:2000',
        ]);

        \App\Models\Complaint::create([
            'booking_request_id' => $bookingRequest->id,
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Votre réclamation a été soumise. Notre équipe va l\'examiner.');
    }

    /**
     * Créer une réclamation (sans booking request obligatoire)
     */
    public function storeComplaint(Request $request)
    {
        $client = auth()->user()->client;
        abort_unless($client, 403);

        $validated = $request->validate([
            'type' => 'required|in:no_show,quality,hygiene,payment,other',
            'description' => 'required|string|max:2000',
            'booking_request_id' => 'nullable|exists:booking_requests,id',
        ]);

        // Vérifier que la booking request appartient bien au client si fournie
        if (!empty($validated['booking_request_id'])) {
            $bookingRequest = BookingRequest::find($validated['booking_request_id']);
            abort_unless($bookingRequest && $bookingRequest->client_id === $client->id, 403);
        }

        \App\Models\Complaint::create([
            'booking_request_id' => $validated['booking_request_id'] ?? null,
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Votre réclamation a été soumise. Notre équipe va l\'examiner.');
    }
}
