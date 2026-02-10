<?php

namespace App\Actions;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\ClientReview;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ManageClientReviews
{
    /**
     * Créer un avis client
     */
    public function createReview(Appointment $appointment, int $rating, ?string $comment = null, ?array $photos = null): ClientReview
    {
        return DB::transaction(function () use ($appointment, $rating, $comment, $photos) {
            // Valider l'avis
            $this->validateReview($appointment, $rating, $comment, $photos);

            // Créer l'avis
            $review = ClientReview::create([
                'appointment_id' => $appointment->id,
                'booking_request_id' => $appointment->bookingRequest->id,
                'client_id' => $appointment->client_id,
                'bookable_id' => $appointment->bookable_id,
                'bookable_type' => $appointment->bookable_type,
                'rating' => $rating,
                'comment' => $comment,
                'photos' => $photos ?? [],
                'status' => 'published',
                'reviewed_at' => now(),
            ]);

            // Mettre à jour la note moyenne du tattooer
            $this->updateTattooerRating($appointment->bookable);

            // Envoyer un message système dans la conversation
            $this->sendReviewNotificationMessage($appointment, $review);

            // Logger la création de l'avis
            $this->logReviewCreation($appointment, $review);

            // Envoyer les notifications
            $this->sendReviewNotifications($appointment, $review);

            return $review;
        });
    }

    /**
     * Valider un avis
     */
    private function validateReview(Appointment $appointment, int $rating, ?string $comment, ?array $photos): void
    {
        // Vérifier que le rendez-vous est terminé
        if ($appointment->status !== 'completed') {
            throw new \InvalidArgumentException('L\'avis ne peut être laissé que pour un rendez-vous terminé');
        }

        // Vérifier que le rendez-vous est terminé depuis au moins 14 jours
        $fourteenDaysAgo = $appointment->end_datetime->addDays(14);
        if (now()->isBefore($fourteenDaysAgo)) {
            throw new \InvalidArgumentException('L\'avis ne peut être laissé qu\'à partir de J+14 après le rendez-vous');
        }

        // Vérifier que le client n'a pas déjà laissé d'avis
        $existingReview = ClientReview::where('appointment_id', $appointment->id)->first();
        if ($existingReview) {
            throw new \InvalidArgumentException('Vous avez déjà laissé un avis pour ce rendez-vous');
        }

        // Valider la note
        if ($rating < 1 || $rating > 5) {
            throw new \InvalidArgumentException('La note doit être comprise entre 1 et 5');
        }

        // Valider le commentaire
        if ($comment && strlen($comment) > 1000) {
            throw new \InvalidArgumentException('Le commentaire ne peut pas dépasser 1000 caractères');
        }

        // Valider les photos
        if ($photos && count($photos) > 5) {
            throw new \InvalidArgumentException('Maximum 5 photos autorisées');
        }
    }

    /**
     * Mettre à jour la note moyenne du tattooer
     */
    private function updateTattooerRating($bookable): void
    {
        $reviews = ClientReview::where('bookable_id', $bookable->id)
            ->where('bookable_type', get_class($bookable))
            ->where('status', 'published')
            ->get();

        $averageRating = $reviews->avg('rating');
        $totalReviews = $reviews->count();

        $bookable->update([
            'average_rating' => round($averageRating, 2),
            'total_reviews' => $totalReviews,
            'rating_updated_at' => now(),
        ]);

        Log::info('Tattooer rating updated', [
            'bookable_id' => $bookable->id,
            'bookable_type' => get_class($bookable),
            'average_rating' => $averageRating,
            'total_reviews' => $totalReviews,
        ]);
    }

    /**
     * Envoyer un message système pour l'avis
     */
    private function sendReviewNotificationMessage(Appointment $appointment, ClientReview $review): void
    {
        $bookingRequest = $appointment->bookingRequest;
        $conversation = $bookingRequest->conversation;
        
        if (!$conversation) {
            return;
        }

        $stars = str_repeat('⭐', $review->rating);
        $content = "⭐ Avis laissé !\n\n";
        $content .= "Merci d'avoir partagé votre expérience !\n\n";
        $content .= "📋 Votre avis :\n";
        $content .= "• Note : {$stars} ({$review->rating}/5)\n";
        
        if ($review->comment) {
            $content .= "• Commentaire : \"{$review->comment}\"\n";
        }
        
        if ($review->photos && !empty($review->photos)) {
            $content .= "• Photos : " . count($review->photos) . " photo(s) jointe(s)\n";
        }
        
        $content .= "\nVotre avis aide d'autres clients à faire le bon choix !\n";
        $content .= "Merci pour votre confiance.";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
        ]);
    }

    /**
     * Logger la création de l'avis
     */
    private function logReviewCreation(Appointment $appointment, ClientReview $review): void
    {
        Log::info('Client review created', [
            'appointment_id' => $appointment->id,
            'booking_request_id' => $appointment->bookingRequest->id,
            'client_id' => $appointment->client_id,
            'review_id' => $review->id,
            'rating' => $review->rating,
            'has_comment' => !is_null($review->comment),
            'has_photos' => !empty($review->photos),
            'days_after_appointment' => $appointment->end_datetime->diffInDays(now()),
        ]);
    }

    /**
     * Envoyer les notifications pour l'avis
     */
    private function sendReviewNotifications(Appointment $appointment, ClientReview $review): void
    {
        // Notification au tattooer
        try {
            $appointment->bookingRequest->bookable->user->notify(
                new \App\Notifications\Tattooer\NewReviewNotification($appointment, $review)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send new review notification to tattooer', [
                'appointment_id' => $appointment->id,
                'review_id' => $review->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Notification à l'admin (pour modération si nécessaire)
        if ($review->rating <= 2) {
            try {
                $adminUsers = \App\Models\User::where('role', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    $admin->notify(
                        new \App\Notifications\Admin\LowRatingNotification($appointment, $review)
                    );
                }
            } catch (\Exception $e) {
                Log::error('Failed to send low rating notification to admin', [
                    'appointment_id' => $appointment->id,
                    'review_id' => $review->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Vérifier si un client peut laisser un avis
     */
    public function canLeaveReview(Appointment $appointment): bool
    {
        // Le rendez-vous doit être terminé
        if ($appointment->status !== 'completed') {
            return false;
        }

        // Le rendez-vous doit être terminé depuis au moins 14 jours
        $fourteenDaysAgo = $appointment->end_datetime->addDays(14);
        if (now()->isBefore($fourteenDaysAgo)) {
            return false;
        }

        // Le client ne doit pas avoir déjà laissé d'avis
        $existingReview = ClientReview::where('appointment_id', $appointment->id)->first();
        if ($existingReview) {
            return false;
        }

        return true;
    }

    /**
     * Obtenir le statut d'avis pour un rendez-vous
     */
    public function getReviewStatus(Appointment $appointment): array
    {
        $existingReview = ClientReview::where('appointment_id', $appointment->id)->first();
        $fourteenDaysAgo = $appointment->end_datetime->addDays(14);
        
        return [
            'can_leave_review' => $this->canLeaveReview($appointment),
            'has_review' => $existingReview !== null,
            'review' => $existingReview,
            'days_until_can_review' => max(0, now()->diffInDays($fourteenDaysAgo, false)),
            'can_review_date' => $fourteenDaysAgo->format('d/m/Y'),
            'is_too_early' => now()->isBefore($fourteenDaysAgo),
            'appointment_completed' => $appointment->status === 'completed',
        ];
    }

    /**
     * Obtenir les statistiques d'avis d'un tattooer
     */
    public function getTattooerReviewStats($bookable): array
    {
        $reviews = ClientReview::where('bookable_id', $bookable->id)
            ->where('bookable_type', get_class($bookable))
            ->where('status', 'published')
            ->get();

        $ratingDistribution = [
            1 => $reviews->where('rating', 1)->count(),
            2 => $reviews->where('rating', 2)->count(),
            3 => $reviews->where('rating', 3)->count(),
            4 => $reviews->where('rating', 4)->count(),
            5 => $reviews->where('rating', 5)->count(),
        ];

        return [
            'total_reviews' => $reviews->count(),
            'average_rating' => $bookable->average_rating,
            'rating_distribution' => $ratingDistribution,
            'recent_reviews' => $reviews->orderBy('reviewed_at', 'desc')->limit(10)->get(),
            'reviews_with_photos' => $reviews->whereNotNull('photos')->where('photos', '!=', '[]')->count(),
            'reviews_with_comments' => $reviews->whereNotNull('comment')->count(),
        ];
    }

    /**
     * Modérer un avis (admin)
     */
    public function moderateReview(ClientReview $review, string $action, ?string $reason = null): void
    {
        DB::transaction(function () use ($review, $action, $reason) {
            switch ($action) {
                case 'hide':
                    $review->update([
                        'status' => 'hidden',
                        'moderated_at' => now(),
                        'moderation_reason' => $reason,
                    ]);
                    break;
                    
                case 'delete':
                    $review->delete();
                    break;
                    
                default:
                    throw new \InvalidArgumentException('Action de modération invalide');
            }

            // Mettre à jour la note moyenne du tattooer
            $this->updateTattooerRating($review->bookable);

            Log::info('Review moderated', [
                'review_id' => $review->id,
                'action' => $action,
                'reason' => $reason,
                'moderated_by' => auth()->id() ?? 'system',
            ]);
        });
    }

    /**
     * Répondre à un avis (tattooer)
     */
    public function replyToReview(ClientReview $review, string $response): void
    {
        DB::transaction(function () use ($review, $response) {
            $review->update([
                'tattooer_response' => $response,
                'tattooer_responded_at' => now(),
            ]);

            // Notifier le client de la réponse
            try {
                $review->client->user->notify(
                    new \App\Notifications\Client\ReviewResponseNotification($review)
                );
            } catch (\Exception $e) {
                Log::error('Failed to send review response notification to client', [
                    'review_id' => $review->id,
                    'error' => $e->getMessage(),
                ]);
            }

            Log::info('Tattooer responded to review', [
                'review_id' => $review->id,
                'appointment_id' => $review->appointment_id,
                'tattooer_id' => $review->bookable_id,
            ]);
        });
    }

    /**
     * Obtenir les avis récents pour le dashboard
     */
    public function getRecentReviews(int $limit = 10): array
    {
        return ClientReview::with(['client', 'bookable'])
            ->where('status', 'published')
            ->orderBy('reviewed_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'client_name' => $review->client->full_name,
                    'tattooer_name' => $review->bookable->name,
                    'reviewed_at' => $review->reviewed_at->format('d/m/Y H:i'),
                    'has_photos' => !empty($review->photos),
                    'has_response' => !is_null($review->tattooer_response),
                ];
            })
            ->toArray();
    }
}
