<?php

namespace App\Observers;

use App\Models\BookingRequest;
use App\Services\TattooerStatsService;

class BookingRequestObserver
{
    /**
     * Handle the BookingRequest "created" event.
     */
    public function created(BookingRequest $bookingRequest): void
    {
        // Invalider le cache stats du bookable lors de la création
        if ($bookingRequest->bookable) {
            $statsService = app(TattooerStatsService::class);
            $statsService->invalidateAllCaches($bookingRequest->bookable);
        }
    }

    /**
     * Handle the BookingRequest "updated" event.
     */
    public function updated(BookingRequest $bookingRequest): void
    {
        // Invalider le cache stats uniquement si le statut change
        if ($bookingRequest->isDirty('status') && $bookingRequest->bookable) {
            $statsService = app(TattooerStatsService::class);
            $statsService->invalidateAllCaches($bookingRequest->bookable);
        }
    }

    /**
     * Handle the BookingRequest "deleted" event.
     */
    public function deleted(BookingRequest $bookingRequest): void
    {
        // Invalider le cache stats du bookable lors de la suppression
        if ($bookingRequest->bookable) {
            $statsService = app(TattooerStatsService::class);
            $statsService->invalidateAllCaches($bookingRequest->bookable);
        }
    }
}
