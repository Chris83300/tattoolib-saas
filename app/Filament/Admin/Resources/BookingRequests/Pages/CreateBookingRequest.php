<?php

namespace App\Filament\Admin\Resources\BookingRequests\Pages;

use App\Filament\Admin\Resources\BookingRequests\BookingRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBookingRequest extends CreateRecord
{
    protected static string $resource = BookingRequestResource::class;
}
