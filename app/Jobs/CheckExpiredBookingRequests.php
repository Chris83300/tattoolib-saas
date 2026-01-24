<?php

namespace App\Jobs;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckExpiredBookingRequests implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $expiredRequests = BookingRequest::where('status', BookingRequest::STATUS_ACCEPTED)
            ->where('deposit_deadline', '<', now())
            ->whereNull('deposit_paid_at')
            ->get();

        $expiredCount = $expiredRequests->count();

        if ($expiredCount > 0) {
            foreach ($expiredRequests as $request) {
                $request->update([
                    'status' => BookingRequest::STATUS_EXPIRED,
                    'expired_at' => now(),
                ]);

                // TODO: Envoyer notification au client et au tatoueur
                Log::info("Booking request #{$request->id} expired", [
                    'client_id' => $request->client_id,
                    'bookable_type' => $request->bookable_type,
                    'bookable_id' => $request->bookable_id,
                    'deposit_deadline' => $request->deposit_deadline,
                ]);
            }

            Log::info("Processed {$expiredCount} expired booking requests");
        } else {
            Log::info("No expired booking requests found");
        }
    }
}
