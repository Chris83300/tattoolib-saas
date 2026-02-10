<?php

namespace App\Actions;

use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestAlternativeDate
{
    /**
     * Le client demande une date alternative
     */
    public function execute(BookingRequest $bookingRequest, string $message): void
    {
        DB::transaction(function () use ($bookingRequest, $message) {
            // 1. Valider que le client peut demander une nouvelle date
            $this->validateDateRequest($bookingRequest);

            // 2. Envoyer le message du client dans le chat
            $conversation = $bookingRequest->conversation;
            
            if ($conversation) {
                $conversation->messages()->create([
                    'sender_id' => $bookingRequest->client->user_id,
                    'sender_type' => 'client',
                    'content' => $message,
                ]);
            }

            // 3. Envoyer une notification système au tattooer
            $this->notifyTattooerAboutDateRequest($bookingRequest, $conversation, $message);

            // 4. Logger la demande
            $this->logDateRequest($bookingRequest, $message);
        });
    }

    /**
     * Valider que le client peut demander une nouvelle date
     */
    private function validateDateRequest(BookingRequest $bookingRequest): void
    {
        // Vérifier que le statut permet de demander une date
        if (!in_array($bookingRequest->status->value, ['deposit_paid', 'date_confirmed'])) {
            throw new \InvalidArgumentException('Le statut actuel ne permet pas de demander une nouvelle date');
        }

        // Vérifier qu'il y a déjà des dates proposées
        if (!$bookingRequest->proposed_dates) {
            throw new \InvalidArgumentException('Aucune date proposée à modifier');
        }

        // Vérifier que le RDV n'est pas déjà confirmé
        if ($bookingRequest->appointment_datetime && Carbon::parse($bookingRequest->appointment_datetime)->isPast()) {
            throw new \InvalidArgumentException('Le rendez-vous est déjà passé');
        }
    }

    /**
     * Notifier le tattooer de la demande de date alternative
     */
    private function notifyTattooerAboutDateRequest(BookingRequest $bookingRequest, ?Conversation $conversation, string $message): void
    {
        if (!$conversation) {
            return;
        }

        $content = "🔄 Demande de nouvelle date\n\n";
        $content .= "Le client souhaite une date différente de celles proposées.\n\n";
        $content .= "Message du client :\n";
        $content .= "\"{$message}\"\n\n";
        $content .= "Vous pouvez proposer de nouvelles dates dans votre interface.";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
            'is_tattooer_only' => true, // Message visible uniquement par le tattooer
        ]);
    }

    /**
     * Logger la demande de date alternative
     */
    private function logDateRequest(BookingRequest $bookingRequest, string $message): void
    {
        Log::info('Client requested alternative date', [
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $bookingRequest->client_id,
            'current_status' => $bookingRequest->status->value,
            'has_appointment_datetime' => !is_null($bookingRequest->appointment_datetime),
            'message_length' => strlen($message),
        ]);
    }

    /**
     * Vérifier si une demande de date est en attente
     */
    public function hasPendingDateRequest(BookingRequest $bookingRequest): bool
    {
        $conversation = $bookingRequest->conversation;
        
        if (!$conversation) {
            return false;
        }

        // Chercher le dernier message système de demande de date
        $lastSystemMessage = $conversation->messages()
            ->where('sender_type', 'system')
            ->where('content', 'like', '%🔄 Demande de nouvelle date%')
            ->latest()
            ->first();

        if (!$lastSystemMessage) {
            return false;
        }

        // Vérifier si le tattooer a proposé de nouvelles dates depuis
        $hasNewProposedDates = $conversation->messages()
            ->where('sender_type', 'system')
            ->where('content', 'like', '%📅 Nouvelles dates proposées%')
            ->where('created_at', '>', $lastSystemMessage->created_at)
            ->exists();

        return !$hasNewProposedDates;
    }

    /**
     * Obtenir les messages de demande de date
     */
    public function getDateRequestMessages(BookingRequest $bookingRequest): array
    {
        $conversation = $bookingRequest->conversation;
        
        if (!$conversation) {
            return [];
        }

        return $conversation->messages()
            ->where('sender_type', 'client')
            ->where('content', 'like', '%date%')
            ->orWhere('content', 'like', '%autre%')
            ->orWhere('content', 'like', '%disponible%')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'created_at' => $message->created_at,
                    'is_recent' => $message->created_at->diffInHours(now()) < 24,
                ];
            })
            ->toArray();
    }

    /**
     * Compter le nombre de demandes de date
     */
    public function getDateRequestCount(BookingRequest $bookingRequest): int
    {
        $conversation = $bookingRequest->conversation;
        
        if (!$conversation) {
            return 0;
        }

        return $conversation->messages()
            ->where('sender_type', 'client')
            ->where(function ($query) {
                $query->where('content', 'like', '%date%')
                      ->orWhere('content', 'like', '%autre%')
                      ->orWhere('content', 'like', '%disponible%');
            })
            ->count();
    }

    /**
     * Vérifier si le client peut encore demander des dates
     */
    public function canRequestMoreDates(BookingRequest $bookingRequest): bool
    {
        // Limite à 3 demandes de dates alternatives
        $requestCount = $this->getDateRequestCount($bookingRequest);
        
        if ($requestCount >= 3) {
            return false;
        }

        // Vérifier qu'il n'y a pas déjà une demande en attente
        return !$this->hasPendingDateRequest($bookingRequest);
    }

    /**
     * Obtenir le statut de la gestion des dates
     */
    public function getDateManagementStatus(BookingRequest $bookingRequest): array
    {
        return [
            'has_proposed_dates' => !empty($bookingRequest->proposed_dates),
            'has_confirmed_date' => !is_null($bookingRequest->confirmed_date),
            'has_appointment' => !is_null($bookingRequest->appointment_datetime),
            'pending_date_request' => $this->hasPendingDateRequest($bookingRequest),
            'date_request_count' => $this->getDateRequestCount($bookingRequest),
            'can_request_more_dates' => $this->canRequestMoreDates($bookingRequest),
            'date_request_messages' => $this->getDateRequestMessages($bookingRequest),
            'formatted_proposed_dates' => (new ConfirmAppointmentDate())->getFormattedProposedDates($bookingRequest),
        ];
    }
}
