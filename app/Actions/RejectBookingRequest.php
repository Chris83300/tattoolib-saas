<?php

namespace App\Actions;

use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Enums\BookingRequestStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RejectBookingRequest
{
    /**
     * Reject a booking request with optional reason
     */
    public function execute(BookingRequest $bookingRequest, ?string $reason = null): void
    {
        DB::transaction(function () use ($bookingRequest, $reason) {
            // 1. Update booking request status and reason
            $bookingRequest->update([
                'status' => BookingRequestStatus::CANCELLED,
                'cancelled_at' => now(),
                'cancelled_by' => 'tattooer',
                'cancellation_reason' => $reason ?? 'Demande refusée par le tatoueur',
            ]);

            // 2. Close existing conversation if it exists
            if ($bookingRequest->conversation) {
                $this->closeConversation($bookingRequest->conversation, $reason);
            }

            // 3. Log the rejection
            $this->logRejection($bookingRequest, $reason);

            // 4. TODO: Send notification to client
            // $this->notifyClient($bookingRequest, $reason);
        });
    }

    /**
     * Close the conversation with rejection message
     */
    private function closeConversation(Conversation $conversation, ?string $reason): void
    {
        // Update conversation status
        $conversation->update([
            'status' => 'closed',
            'archived_at' => now(),
        ]);

        // Send rejection message
        $message = "❌ Demande refusée\n\n";
        
        if ($reason) {
            $message .= "Raison : " . $reason;
        } else {
            $message .= "Le tatoueur a malheureusement dû refuser votre demande.";
        }

        $message .= "\n\nVous pouvez soumettre une nouvelle demande à tout moment.";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $message,
        ]);
    }

    /**
     * Log the rejection event
     */
    private function logRejection(BookingRequest $bookingRequest, ?string $reason): void
    {
        Log::info('Booking request rejected', [
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $bookingRequest->client_id,
            'bookable_id' => $bookingRequest->bookable_id,
            'bookable_type' => $bookingRequest->bookable_type,
            'reason' => $reason,
            'cancelled_by' => 'tattooer',
        ]);
    }

    /**
     * TODO: Send notification to client
     */
    private function notifyClient(BookingRequest $bookingRequest, ?string $reason): void
    {
        // Implementation for email and in-app notifications
        // This will be implemented in a future phase
    }
}
