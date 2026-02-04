<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookingRequest;
use App\Models\Conversation;
use Carbon\Carbon;

class ManageChatStatus extends Command
{
    protected $signature = 'chat:manage-status';
    protected $description = 'Gérer le statut des chats (fermeture automatique après délai acompte)';

    public function handle()
    {
        $this->info('Gestion du statut des chats...');

        // Fermer les chats UNIQUEMENT si le délai de paiement est VRAIMENT expiré ET non payé
        $expiredRequests = BookingRequest::whereIn('status', ['accepted', 'awaiting_deposit'])
            ->where('chat_status', 'open')
            ->whereNotNull('client_payment_deadline') // Vérifier qu'il y a une deadline
            ->where('client_payment_deadline', '<', now()->subDays(1)) // Donner 1 jour de marge
            ->whereNull('deposit_paid_at') // Non payé
            ->get();

        foreach ($expiredRequests as $request) {
            $request->update(['chat_status' => 'closed']);

            // Fermer la conversation associée
            if ($request->conversation) {
                $request->conversation->update(['status' => 'closed']);
            }

            $this->info("Chat fermé pour la demande #{$request->id} (délai acompte VRAIMENT expiré depuis > 24h)");
        }

        // S'assurer que les chats avec deadline non expirée sont ouverts
        $shouldOpenRequests = BookingRequest::whereIn('status', ['accepted', 'awaiting_deposit'])
            ->where('chat_status', '!=', 'open')
            ->whereNotNull('client_payment_deadline')
            ->where('client_payment_deadline', '>', now()->subDays(1)) // Deadline pas encore expirée
            ->whereNull('deposit_paid_at')
            ->get();

        foreach ($shouldOpenRequests as $request) {
            $request->update(['chat_status' => 'open']);

            // Ouvrir la conversation associée
            if ($request->conversation) {
                $request->conversation->update(['status' => 'active']);
            }

            $this->info("Chat rouvert pour la demande #{$request->id} (délai encore valide)");
        }

        // Prolonger les chats pour les demandes avec acompte payé jusqu'à la date de RDV
        $paidRequests = BookingRequest::whereIn('status', ['accepted', 'awaiting_deposit', 'deposit_paid'])
            ->where('chat_status', 'open')
            ->whereNotNull('deposit_paid_at')
            ->whereNotNull('appointment_datetime')
            ->get();

        foreach ($paidRequests as $request) {
            $request->update([
                'chat_closes_at' => $request->appointment_datetime,
            ]);

            $this->info("Chat prolongé jusqu'au RDV pour la demande #{$request->id}");
        }

        $this->info('Gestion des chats terminée.');
        return 0;
    }
}
