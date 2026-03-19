<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\Review;
use App\Models\Appointment;
use App\Actions\ReportNoShowAction;
use App\Http\Requests\Client\CreateReviewRequest;
use App\Http\Requests\Client\CreateComplaintRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientSocialController extends Controller
{
    /**
     * Créer un avis pour une demande terminée
     */
    public function createReview(CreateReviewRequest $request, BookingRequest $bookingRequest)
    {
        try {
            Log::debug('createReview', [
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

            $validated = $request->validated();

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
                'message' => 'Une erreur est survenue lors de la sauvegarde de votre avis. Veuillez réessayer.'
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
    public function createComplaint(CreateComplaintRequest $request, BookingRequest $bookingRequest)
    {
        $client = auth()->user()->client;
        abort_unless($client && $bookingRequest->client_id === $client->id, 403);

        $validated = $request->validated();

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
        $consent = \App\Models\ClientConsentForm::updateOrCreate(
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
}
