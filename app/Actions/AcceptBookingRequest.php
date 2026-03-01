<?php

namespace App\Actions;

use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Enums\BookingRequestStatus;
use App\Enums\ConversationStatus;
use App\Notifications\BookingRequestAcceptedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcceptBookingRequest
{
    /**
     * Accept a booking request with conditions and create conversation
     */
    public function execute(BookingRequest $bookingRequest, array $data): void
    {
        DB::transaction(function () use ($bookingRequest, $data) {
            // 1. Validate required fields
            $this->validateData($data);

            // 2. Update the booking request with acceptance data
            $bookingRequest->update([
                'status' => BookingRequestStatus::ACCEPTED,
                'accepted_at' => now(),
                'price_estimate_min' => $data['price_estimate_min'],
                'price_estimate_max' => $data['price_estimate_max'],
                'deposit_amount' => $data['deposit_amount'],
                'deposit_deadline_hours' => $data['deposit_deadline_hours'],
                'included_designs' => $data['included_designs'],
                'modifications_per_design' => $data['modifications_per_design'],
                'proposed_dates' => $data['proposed_dates'], // JSON
                'tattooer_acceptance_message' => $data['message'] ?? null,
            ]);

            // 3. Create or activate the conversation
            $conversation = $this->createOrUpdateConversation($bookingRequest, $data);

            // 4. Synchronize deadline on booking request
            $bookingRequest->update([
                'deposit_deadline' => $conversation->deposit_deadline_at,
                'client_payment_deadline' => $conversation->deposit_deadline_at,
            ]);

            // 5. Send system message in the chat
            $this->sendSystemMessage($conversation, $data);

            // 6. Log the acceptance
            $this->logAcceptance($bookingRequest, $data);

            // 7. Notifier le client que sa demande a été acceptée
            if ($bookingRequest->client?->user) {
                $bookingRequest->client->user->notify(new BookingRequestAcceptedNotification($bookingRequest));
            }
        });
    }

    /**
     * Validate the acceptance data
     */
    private function validateData(array $data): void
    {
        $required = [
            'price_estimate_min',
            'price_estimate_max', 
            'deposit_amount',
            'deposit_deadline_hours',
            'included_designs',
            'modifications_per_design',
            'proposed_dates'
        ];

        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("Field {$field} is required");
            }
        }

        // Validate price range
        if ($data['price_estimate_min'] >= $data['price_estimate_max']) {
            throw new \InvalidArgumentException('Price estimate min must be less than max');
        }

        // Validate deposit amount
        if ($data['deposit_amount'] < 0) {
            throw new \InvalidArgumentException('Deposit amount must be positive');
        }

        // Validate proposed dates format
        if (!is_array($data['proposed_dates']) || empty($data['proposed_dates'])) {
            throw new \InvalidArgumentException('Proposed dates must be a non-empty array');
        }

        foreach ($data['proposed_dates'] as $date) {
            if (!isset($date['date']) || !isset($date['period'])) {
                throw new \InvalidArgumentException('Each proposed date must have date and period');
            }
            
            if (!in_array($date['period'], ['morning', 'afternoon', 'evening', 'anytime'])) {
                throw new \InvalidArgumentException('Invalid period value');
            }
        }
    }

    /**
     * Create or update conversation for the booking request
     */
    private function createOrUpdateConversation(BookingRequest $bookingRequest, array $data): Conversation
    {
        $deadline = now()->addHours($data['deposit_deadline_hours']);

        return $bookingRequest->conversation()->firstOrCreate(
            ['booking_request_id' => $bookingRequest->id],
            [
                'subject' => "Demande de tatouage - {$bookingRequest->tattoo_size}",
                'status' => ConversationStatus::ACTIVE,
                'deposit_deadline_at' => $deadline,
                'expires_at' => $deadline,
                'expiry_type' => 'deposit_pending',
            ]
        );
    }

    /**
     * Send system message about acceptance
     */
    private function sendSystemMessage(Conversation $conversation, array $data): void
    {
        $message = "✅ Demande acceptée !\n\n";
        $message .= "📋 Estimation : {$data['price_estimate_min']}€ - {$data['price_estimate_max']}€\n";
        $message .= "💰 Acompte de {$data['deposit_amount']}€ à payer sous {$data['deposit_deadline_hours']}h\n";
        $message .= "🎨 {$data['included_designs']} design(s) inclus avec {$data['modifications_per_design']} modification(s) par design\n\n";
        
        if (!empty($data['proposed_dates'])) {
            $message .= "📅 Dates proposées :\n";
            foreach ($data['proposed_dates'] as $date) {
                $periodLabel = match($date['period']) {
                    'morning' => 'Matin',
                    'afternoon' => 'Après-midi', 
                    'evening' => 'Soirée',
                    'anytime' => 'Toute la journée',
                    default => $date['period']
                };
                $message .= "• " . $date['date'] . " - " . $periodLabel . "\n";
            }
        }

        if (!empty($data['message'])) {
            $message .= "\n💬 Message du tatoueur :\n" . $data['message'];
        }

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $message,
        ]);
    }

    /**
     * Log the acceptance event
     */
    private function logAcceptance(BookingRequest $bookingRequest, array $data): void
    {
        Log::info('Booking request accepted', [
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $bookingRequest->client_id,
            'bookable_id' => $bookingRequest->bookable_id,
            'bookable_type' => $bookingRequest->bookable_type,
            'price_range' => [
                'min' => $data['price_estimate_min'],
                'max' => $data['price_estimate_max']
            ],
            'deposit_amount' => $data['deposit_amount'],
            'deposit_deadline_hours' => $data['deposit_deadline_hours'],
            'included_designs' => $data['included_designs'],
            'modifications_per_design' => $data['modifications_per_design'],
            'proposed_dates_count' => count($data['proposed_dates']),
        ]);
    }

}
