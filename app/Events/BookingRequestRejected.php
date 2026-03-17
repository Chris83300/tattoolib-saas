<?php

namespace App\Events;

use App\Models\BookingRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingRequestRejected
{
    use Dispatchable, SerializesModels;

    public BookingRequest $bookingRequest;

    public function __construct(BookingRequest $bookingRequest)
    {
        $this->bookingRequest = $bookingRequest;
    }
}
