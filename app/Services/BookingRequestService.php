<?php

namespace App\Services;

use App\Models\BookingRequest;
use App\Models\Tattooer;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\Appointment;
use App\Models\Message;
use App\Exceptions\BookingException;
use App\Services\TattooerStatsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingRequestService
{
    /**
     * Accepter une demande de réservation
     */
    public function accept(
        BookingRequest $bookingRequest,
        array $data
    ): BookingRequest {
        DB::beginTransaction();

        try {
            // Valider que la demande est en pending
            if ($bookingRequest->status !== BookingRequest::STATUS_PENDING) {
                throw new BookingException('Cette demande ne peut plus être acceptée.');
            }

            // Calculer montant acompte
            $depositAmount = $this->calculateDeposit(
                $data['estimated_total_price'],
                $data['deposit_rate'] ?? 30
            );

            // Mettre à jour la demande
            $bookingRequest->update([
                'status' => BookingRequest::STATUS_ACCEPTED,
                'accepted_at' => now(),
                'estimated_total_price' => $data['estimated_total_price'],
                'price_range_min' => $data['price_range_min'] ?? null,
                'price_range_max' => $data['price_range_max'] ?? null,
                'deposit_rate' => $data['deposit_rate'] ?? 30,
                'total_deposit_amount' => $depositAmount,
                'client_payment_deadline' => now()->addDays(7),
                'tattooer_design_deadline' => $data['design_deadline'] ?? now()->addDays(14),
                'included_design_versions' => $data['design_versions'] ?? 3,
                'modifications_per_version' => $data['modifications_per_version'] ?? 2,
            ]);

            // Créer/mettre à jour conversation
            $conversation = $this->ensureConversation($bookingRequest);
            $conversation->update([
                'expiry_type' => 'deposit_pending',
                'deposit_deadline_at' => $bookingRequest->client_payment_deadline,
            ]);

            // Notifier client
            $this->notifyClient($bookingRequest, 'accepted');

            // Logger l'acceptation
            Log::info('Booking request accepted', [
                'booking_request_id' => $bookingRequest->id,
                'tattooer_id' => $bookingRequest->bookable_id,
                'client_id' => $bookingRequest->client_id,
                'total_deposit_amount' => $depositAmount,
            ]);

            DB::commit();

            return $bookingRequest->fresh(['client', 'bookable', 'conversation']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error accepting booking request', [
                'booking_request_id' => $bookingRequest->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Rejeter une demande
     */
    public function reject(
        BookingRequest $bookingRequest,
        string $reason
    ): BookingRequest {
        DB::beginTransaction();

        try {
            $bookingRequest->update([
                'status' => BookingRequest::STATUS_REJECTED,
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);

            // Fermer conversation si existe
            if ($conversation = $bookingRequest->conversation) {
                $conversation->update(['status' => 'closed']);
            }

            // Notifier client
            $this->notifyClient($bookingRequest, 'rejected');

            // Logger le rejet
            Log::info('Booking request rejected', [
                'booking_request_id' => $bookingRequest->id,
                'tattooer_id' => $bookingRequest->bookable_id,
                'client_id' => $bookingRequest->client_id,
                'reason' => $reason,
            ]);

            DB::commit();

            return $bookingRequest;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting booking request', [
                'booking_request_id' => $bookingRequest->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Confirmer paiement acompte
     */
    public function confirmDeposit(
        BookingRequest $bookingRequest,
        string $paymentIntentId
    ): BookingRequest {
        DB::beginTransaction();

        try {
            $bookingRequest->update([
                'status' => BookingRequest::STATUS_DEPOSIT_PAID,
                'deposit_paid_at' => now(),
                'stripe_payment_intent_id' => $paymentIntentId,
            ]);

            // Conversation devient permanente
            $conversation = $bookingRequest->conversation;
            $conversation->update([
                'expiry_type' => 'permanent',
                'deposit_deadline_at' => null,
                'expires_at' => null,
            ]);

            // Invalider cache stats tatoueur
            app(CacheService::class)->invalidateArtist($bookingRequest->bookable);

            // Notifier tatoueur
            $this->notifyTattooer($bookingRequest, 'deposit_paid');

            // Logger la confirmation
            Log::info('Deposit confirmed', [
                'booking_request_id' => $bookingRequest->id,
                'payment_intent_id' => $paymentIntentId,
                'deposit_amount' => $bookingRequest->total_deposit_amount,
            ]);

            DB::commit();

            return $bookingRequest;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming deposit', [
                'booking_request_id' => $bookingRequest->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Envoyer version design
     */
    public function sendDesign(
        BookingRequest $bookingRequest,
        array $images,
        ?string $message = null
    ): void {
        // Vérifier versions restantes
        $maxVersions = $bookingRequest->included_design_versions;
        $usedVersions = $bookingRequest->design_versions_used;

        if ($usedVersions >= $maxVersions && !$bookingRequest->bookable->is_subscribed) {
            throw new BookingException('Nombre maximum de versions atteint.');
        }

        DB::beginTransaction();

        try {
            // Incrémenter compteur versions
            $bookingRequest->increment('design_versions_used');

            // Créer message avec designs
            $conversation = $bookingRequest->conversation;
            $designMessage = $conversation->messages()->create([
                'sender_id' => $bookingRequest->bookable->user_id,
                'sender_type' => 'tattooer',
                'content' => $message ?? 'Voici ma proposition de design.',
                'is_design_version' => true,
                'design_version_number' => $bookingRequest->fresh()->design_versions_used,
            ]);

            // Attacher images
            foreach ($images as $image) {
                $designMessage->addMedia($image)
                    ->toMediaCollection('attachments');
            }

            // Changer statut si premier design
            if ($bookingRequest->status === BookingRequest::STATUS_DEPOSIT_PAID) {
                $bookingRequest->update([
                    'status' => BookingRequest::STATUS_DESIGN_SENT,
                    'design_sent_at' => now(),
                ]);
            }

            // Notifier client
            $this->notifyClient($bookingRequest, 'design_sent');

            // Logger l'envoi
            Log::info('Design sent', [
                'booking_request_id' => $bookingRequest->id,
                'version_number' => $bookingRequest->fresh()->design_versions_used,
                'images_count' => count($images),
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending design', [
                'booking_request_id' => $bookingRequest->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Confirmer RDV final
     */
    public function confirmAppointment(
        BookingRequest $bookingRequest,
        Carbon $startTime,
        int $durationMinutes
    ): Appointment {
        DB::beginTransaction();

        try {
            // Vérifier disponibilité
            $this->validateAppointmentAvailability($bookingRequest->bookable, $startTime, $durationMinutes);

            // Créer appointment
            $appointment = Appointment::create([
                'booking_request_id' => $bookingRequest->id,
                'bookable_type' => $bookingRequest->bookable_type,
                'bookable_id' => $bookingRequest->bookable_id,
                'client_id' => $bookingRequest->client_id,
                'start_time' => $startTime,
                'end_time' => $startTime->copy()->addMinutes($durationMinutes),
                'duration_minutes' => $durationMinutes,
                'deposit_amount' => $bookingRequest->total_deposit_amount,
                'total_price' => $bookingRequest->estimated_total_price,
                'remaining_amount' => $bookingRequest->estimated_total_price - $bookingRequest->total_deposit_amount,
                'status' => 'confirmed',
            ]);

            // Mettre à jour booking request
            $bookingRequest->update([
                'status' => BookingRequest::STATUS_CONFIRMED,
                'confirmed_at' => now(),
                'scheduled_date' => $startTime->toDateString(),
                'scheduled_time' => $startTime->toTimeString(),
            ]);

            // Notifier client
            $this->notifyClient($bookingRequest, 'appointment_confirmed');

            // Logger la confirmation
            Log::info('Appointment confirmed', [
                'booking_request_id' => $bookingRequest->id,
                'appointment_id' => $appointment->id,
                'start_time' => $startTime,
                'duration_minutes' => $durationMinutes,
            ]);

            DB::commit();

            return $appointment;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming appointment', [
                'booking_request_id' => $bookingRequest->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Annuler une demande
     */
    public function cancel(
        BookingRequest $bookingRequest,
        string $reason,
        bool $refund = false
    ): BookingRequest {
        DB::beginTransaction();

        try {
            $bookingRequest->update([
                'status' => BookingRequest::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            // Gérer remboursement si acompte payé
            if ($refund && $bookingRequest->deposit_paid_at) {
                $this->processRefund($bookingRequest);
            }

            // Archiver conversation
            $conversation = $bookingRequest->conversation;
            if ($conversation) {
                $conversation->update([
                    'status' => 'archived',
                    'archived_at' => now(),
                ]);
            }

            // Notifier les deux parties
            $this->notifyClient($bookingRequest, 'cancelled');
            $this->notifyTattooer($bookingRequest, 'cancelled');

            // Logger l'annulation
            Log::info('Booking request cancelled', [
                'booking_request_id' => $bookingRequest->id,
                'reason' => $reason,
                'refund' => $refund,
            ]);

            DB::commit();

            return $bookingRequest;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling booking request', [
                'booking_request_id' => $bookingRequest->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Modifier une demande acceptée
     */
    public function modifyAccepted(
        BookingRequest $bookingRequest,
        array $data
    ): BookingRequest {
        DB::beginTransaction();

        try {
            $allowedFields = [
                'estimated_total_price',
                'price_range_min',
                'price_range_max',
                'deposit_rate',
                'included_design_versions',
                'modifications_per_version',
                'tattooer_design_deadline',
            ];

            $updateData = array_intersect_key($data, array_flip($allowedFields));

            if (isset($updateData['estimated_total_price']) || isset($updateData['deposit_rate'])) {
                $totalPrice = $updateData['estimated_total_price'] ?? $bookingRequest->estimated_total_price;
                $depositRate = $updateData['deposit_rate'] ?? $bookingRequest->deposit_rate;
                $updateData['total_deposit_amount'] = $this->calculateDeposit($totalPrice, $depositRate);
            }

            $bookingRequest->update($updateData);

            // Notifier client des changements
            $this->notifyClient($bookingRequest, 'modified');

            DB::commit();

            return $bookingRequest->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error modifying booking request', [
                'booking_request_id' => $bookingRequest->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Obtenir les statistiques d'un tatoueur
     */
    public function getTattooerStats(Tattooer $tattooer, array $filters = []): array
    {
        $query = BookingRequest::where('bookable_type', Tattooer::class)
            ->where('bookable_id', $tattooer->id);

        // Appliquer filtres
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return [
            'total' => $query->count(),
            'pending' => $query->clone()->where('status', BookingRequest::STATUS_PENDING)->count(),
            'accepted' => $query->clone()->where('status', BookingRequest::STATUS_ACCEPTED)->count(),
            'confirmed' => $query->clone()->where('status', BookingRequest::STATUS_CONFIRMED)->count(),
            'cancelled' => $query->clone()->where('status', BookingRequest::STATUS_CANCELLED)->count(),
            'total_revenue' => $query->clone()->where('status', BookingRequest::STATUS_CONFIRMED)->sum('estimated_total_price'),
            'total_deposits' => $query->clone()->whereIn('status', [BookingRequest::STATUS_DEPOSIT_PAID, BookingRequest::STATUS_CONFIRMED])->sum('total_deposit_amount'),
        ];
    }

    // ==================== MÉTHODES PRIVÉES ====================

    /**
     * Calculer le montant de l'acompte
     */
    private function calculateDeposit(float $totalPrice, int $rate): float
    {
        return round($totalPrice * ($rate / 100), 2);
    }

    /**
     * S'assurer qu'une conversation existe
     */
    private function ensureConversation(BookingRequest $bookingRequest): Conversation
    {
        if ($bookingRequest->conversation) {
            return $bookingRequest->conversation;
        }

        return Conversation::create([
            'booking_request_id' => $bookingRequest->id,
            'subject' => "Demande de tatouage - {$bookingRequest->tattoo_size}",
            'status' => 'active',
        ]);
    }

    /**
     * Valider la disponibilité pour un RDV
     */
    private function validateAppointmentAvailability(Tattooer $tattooer, Carbon $startTime, int $durationMinutes): void
    {
        $endTime = $startTime->copy()->addMinutes($durationMinutes);
        $dayOfWeek = $startTime->dayOfWeek;

        // Vérifier les horaires de travail
        $workingHours = $tattooer->workingHours()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_closed', false)
            ->first();

        if (!$workingHours) {
            throw new BookingException('Le tatoueur ne travaille pas ce jour.');
        }

        if ($startTime->format('H:i') < $workingHours->open_time ||
            $endTime->format('H:i') > $workingHours->close_time) {
            throw new BookingException('L\'heure choisie est en dehors des horaires de travail.');
        }

        // Vérifier les conflits
        $conflictingAppointments = Appointment::where('bookable_id', $tattooer->id)
            ->where('status', 'confirmed')
            ->where(function($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($conflictingAppointments) {
            throw new BookingException('Un RDV est déjà prévu à cette heure.');
        }
    }

    /**
     * Notifier le client
     */
    private function notifyClient(BookingRequest $bookingRequest, string $event): void
    {
        // TODO: Implémenter notifications (email + push)
        Log::info('Client notification sent', [
            'booking_request_id' => $bookingRequest->id,
            'event' => $event,
            'client_id' => $bookingRequest->client_id,
        ]);
    }

    /**
     * Notifier le tatoueur
     */
    private function notifyTattooer(BookingRequest $bookingRequest, string $event): void
    {
        // TODO: Implémenter notifications
        Log::info('Tattooer notification sent', [
            'booking_request_id' => $bookingRequest->id,
            'event' => $event,
            'tattooer_id' => $bookingRequest->bookable_id,
        ]);
    }

    /**
     * Traiter le remboursement
     */
    private function processRefund(BookingRequest $bookingRequest): void
    {
        // TODO: Implémenter remboursement Stripe
        Log::info('Refund processed', [
            'booking_request_id' => $bookingRequest->id,
            'amount' => $bookingRequest->total_deposit_amount,
        ]);
    }
}
