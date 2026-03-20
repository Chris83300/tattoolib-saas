<?php

namespace App\Http\Controllers\Tattooer;

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\ClientConsentForm;
use App\Models\TraceabilityRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TattooerClientController extends ArtisanBaseController
{
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
    public function clientShow(Client $client)
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
    public function updateClient(Request $request, Client $client)
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
            Log::info('storeClient called', [
                'fields' => array_keys($request->except(['password', 'password_confirmation', '_token', '_method'])),
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
                ->with('error', 'Une erreur est survenue lors de la création du client. Veuillez réessayer.')
                ->withInput();
        }
    }

    /**
     * Mettre à jour les notes privées du client
     */
    public function updateClientNotes(Request $request, Client $client)
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
     * Demandes d'un client spécifique
     */
    public function clientRequests($clientId)
    {
        $tattooer = $this->artisan();

        $requests = BookingRequest::where('client_id', $clientId)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->with(['client.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.client-requests', compact('tattooer', 'requests', 'pendingCount', 'unreadCount'));
    }
}
